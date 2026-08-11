@push('head')
    <script>
        function roastSession(cfg) {
            return {
                /* ─── batch config ─── */
                roasterName: cfg.roasterName,
                beanName: cfg.beanName,
                origin: cfg.origin || '',
                varietas: cfg.varietas || '',
                proses: cfg.proses || '',
                greenWeight: parseFloat(cfg.greenWeight) || 0,
                chargeTemp: parseFloat(cfg.chargeTemp) || 0,

                /* ─── timer ─── */
                startTime: Date.now(),
                elapsed: 0,
                stopped: false,
                frozenElapsed: 0,
                _interval: null,
                _resizeFn: null,

                /* ─── roasting data ─── */
                checklist: {},
                tempLog: [],
                tempInput: '',
                notes: '',
                showFinish: false,
                showCancelModal: false,
                saveCompleted: false,

                stages: [{
                        key: 'charge',
                        label: 'Charge (Masuk Biji)'
                    },
                    {
                        key: 'turning_point',
                        label: 'Titik Balik (TP)'
                    },
                    {
                        key: 'yellowing',
                        label: 'Yellowing (Menguning)'
                    },
                    {
                        key: 'first_crack',
                        label: 'First Crack'
                    },
                    {
                        key: 'second_crack',
                        label: 'Second Crack (opsional)'
                    },
                    {
                        key: 'drop',
                        label: 'Drop / Discharge'
                    },
                ],

                /* ─── lifecycle ─── */
                init() {
                    const LS = 'surakana_roast_v1_' + (window._userId || '0');
                    try {
                        const s = JSON.parse(localStorage.getItem(LS) || 'null');
                        if (s && s.roasterName === this.roasterName && s.beanName === this.beanName) {
                            this.checklist = s.checklist || {
                                charge: 0
                            };
                            this.tempLog = s.tempLog || [{
                                time: 0,
                                temp: this.chargeTemp
                            }];
                            this.notes = s.notes || '';
                            this.frozenElapsed = s.frozenElapsed || 0;
                            this.stopped = !!s.stopped;
                            this.showFinish = this.stopped && ('drop' in this.checklist);
                            if (!this.stopped) {
                                this.startTime = Date.now() - (s.elapsed || 0) * 1000;
                            }
                        } else {
                            this._freshInit();
                        }
                    } catch (e) {
                        this._freshInit();
                    }

                    this._interval = setInterval(() => {
                        if (!this.stopped) this.elapsed = (Date.now() - this.startTime) / 1000;
                    }, 250);

                    this._resizeFn = () => this._chart();
                    window.addEventListener('resize', this._resizeFn);

                    window.addEventListener('beforeunload', (e) => {
                        if (!this.saveCompleted) {
                            e.preventDefault();
                            e.returnValue = '';
                        }
                    });

                    this.$nextTick(() => this._chart());
                },

                destroy() {
                    if (this._interval) clearInterval(this._interval);
                    if (this._resizeFn) window.removeEventListener('resize', this._resizeFn);
                },

                _freshInit() {
                    this.checklist = {
                        charge: 0
                    };
                    this.tempLog = [{
                        time: 0,
                        temp: this.chargeTemp
                    }];
                },

                _persist() {
                    const LS = 'surakana_roast_v1_' + (window._userId || '0');
                    try {
                        localStorage.setItem(LS, JSON.stringify({
                            roasterName: this.roasterName,
                            beanName: this.beanName,
                            checklist: this.checklist,
                            tempLog: this.tempLog,
                            notes: this.notes,
                            elapsed: this.currentTime,
                            frozenElapsed: this.frozenElapsed,
                            stopped: this.stopped,
                        }));
                    } catch (e) {}
                },

                _clearStorage() {
                    const LS = 'surakana_roast_v1_' + (window._userId || '0');
                    try {
                        localStorage.removeItem(LS);
                    } catch (e) {}
                },

                /* ─── computed ─── */
                get currentTime() {
                    return this.stopped ? this.frozenElapsed : this.elapsed;
                },

                get timerDisplay() {
                    return this._fmt(this.stopped ? this.frozenElapsed : this.elapsed);
                },

                get metaText() {
                    const p = [this.roasterName, this.beanName, this.greenWeight + 'g', 'charge ' + this.chargeTemp +
                        '°C'
                    ];
                    if (this.origin) p.push(this.origin);
                    if (this.varietas) p.push(this.varietas);
                    if (this.proses) p.push(this.proses);
                    return p.join(' · ');
                },

                get combinedLog() {
                    const items = this.tempLog.map(e => ({
                        ...e,
                        type: 'temp'
                    }));
                    this.stages.forEach(s => {
                        if (Object.prototype.hasOwnProperty.call(this.checklist, s.key))
                            items.push({
                                time: this.checklist[s.key],
                                type: 'stage',
                                label: s.label
                            });
                    });
                    return items.slice().sort((a, b) => b.time - a.time);
                },

                /* ─── helpers ─── */
                _fmt(sec) {
                    sec = Math.max(0, Math.round(sec));
                    return Math.floor(sec / 60) + ':' + String(sec % 60).padStart(2, '0');
                },

                isRecorded(key) {
                    return Object.prototype.hasOwnProperty.call(this.checklist, key);
                },

                stageTime(key) {
                    return this.isRecorded(key) ? this._fmt(this.checklist[key]) + ' menit' : 'belum dicatat';
                },

                /* ─── actions ─── */
                recordStage(key) {
                    if (this.isRecorded(key)) {
                        if (key === 'charge') return;
                        const c = {
                            ...this.checklist
                        };
                        delete c[key];
                        this.checklist = c;
                        if (key === 'drop') {
                            this.stopped = false;
                            this.startTime = Date.now() - this.frozenElapsed * 1000;
                            this.showFinish = false;
                        }
                    } else {
                        const t = this.currentTime;
                        this.checklist = {
                            ...this.checklist,
                            [key]: t
                        };
                        if (key === 'drop') {
                            this.frozenElapsed = t;
                            this.stopped = true;
                            this.showFinish = true;
                        }
                    }
                    this._persist();
                    this.$nextTick(() => this._chart());
                },

                logTemp() {
                    const v = parseFloat(this.tempInput);
                    if (isNaN(v) || v <= 0) return;
                    this.tempLog = [...this.tempLog, {
                        time: this.currentTime,
                        temp: v
                    }];
                    this.tempInput = '';
                    this._persist();
                    this.$nextTick(() => this._chart());
                },

                pressNum(n) {
                    if (this.tempInput === '0' || this.tempInput === '') {
                        this.tempInput = String(n);
                    } else {
                        if (this.tempInput.includes('.')) {
                            const parts = this.tempInput.split('.');
                            if (parts[1].length >= 2) return;
                        }
                        if (this.tempInput.length >= 6) return;
                        this.tempInput += String(n);
                    }
                },

                pressDot() {
                    if (!this.tempInput.includes('.')) {
                        this.tempInput = (this.tempInput || '0') + '.';
                    }
                },

                pressClear() {
                    this.tempInput = '';
                },

                pressBackspace() {
                    if (this.tempInput.length > 0) {
                        this.tempInput = this.tempInput.slice(0, -1);
                    }
                },

                doCancel() {
                    this.saveCompleted = true;
                    this._clearStorage();
                    document.getElementById('cancelForm').submit();
                },

                doFinish() {
                    const payload = JSON.stringify({
                        roasterName: this.roasterName,
                        beanName: this.beanName,
                        origin: this.origin,
                        varietas: this.varietas,
                        proses: this.proses,
                        greenWeight: this.greenWeight,
                        chargeTemp: this.chargeTemp,
                        duration: Math.round(this.frozenElapsed),
                        checklist: this.checklist,
                        tempLog: this.tempLog,
                        notes: this.notes,
                    });
                    this.saveCompleted = true;
                    this._clearStorage();
                    document.getElementById('payloadInput').value = payload;
                    document.getElementById('saveForm').submit();
                },

                /* ─── chart (pure canvas, no library) ─── */
                _ror() {
                    if (this.tempLog.length < 2) return [];
                    const s = [...this.tempLog].sort((a, b) => a.time - b.time);
                    const out = [];
                    for (let i = 1; i < s.length; i++) {
                        const dt = (s[i].time - s[i - 1].time) / 60;
                        if (dt > 0) out.push({
                            x: s[i].time / 60,
                            y: (s[i].temp - s[i - 1].temp) / dt
                        });
                    }
                    return out;
                },

                _chart() {
                    const canvas = document.getElementById('roastChart');
                    if (!canvas) return;
                    const wrap = canvas.parentElement;
                    const W = wrap.clientWidth;
                    if (!W) return;

                    const H = 240;
                    const dpr = window.devicePixelRatio || 1;
                    canvas.width = Math.round(W * dpr);
                    canvas.height = Math.round(H * dpr);
                    canvas.style.width = W + 'px';
                    canvas.style.height = H + 'px';

                    const ctx = canvas.getContext('2d');
                    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                    ctx.clearRect(0, 0, W, H);

                    const C = {
                        grid: '#dccab4',
                        axis: '#c9c6b4',
                        txt: '#786557',
                        temp: '#bd7947',
                        ror: '#b5453a',
                        mk: '#ddd7c8',
                        mkTxt: '#a09277',
                    };

                    /* markers */
                    const MLBL = {
                        charge: 'Charge',
                        turning_point: 'TP',
                        yellowing: 'Dry',
                        first_crack: 'First',
                        second_crack: 'Second',
                        drop: 'Drop'
                    };
                    const markers = Object.entries(this.checklist)
                        .map(([k, t]) => ({
                            t: t / 60,
                            lbl: MLBL[k] || k
                        }))
                        .sort((a, b) => a.t - b.t);

                    const tPts = this.tempLog.map(e => ({
                        x: e.time / 60,
                        y: e.temp
                    }));
                    const rPts = this._ror();

                    /* X range */
                    let maxX = 10;
                    if (tPts.length) maxX = Math.max(maxX, ...tPts.map(p => p.x)) * 1.08;
                    if (markers.length) maxX = Math.max(maxX, ...markers.map(m => m.t)) * 1.15;

                    const hasMk = markers.length > 0;
                    const pad = {
                        top: hasMk ? 26 : 12,
                        right: 10,
                        bottom: 26,
                        left: 38
                    };
                    const pW = W - pad.left - pad.right;
                    const pH = H - pad.top - pad.bottom;

                    const xP = x => pad.left + (x / maxX) * pW;

                    /* Y temp */
                    let minT = 100,
                        maxT = 260;
                    if (tPts.length) {
                        minT = Math.min(...tPts.map(p => p.y)) - 10;
                        maxT = Math.max(...tPts.map(p => p.y)) + 10;
                        if (maxT <= minT) maxT = minT + 20;
                    }
                    const yT = y => pad.top + (1 - (y - minT) / (maxT - minT)) * pH;

                    /* Y RoR (independent scale) */
                    let yR = () => pad.top + pH / 2;
                    if (rPts.length) {
                        const vals = rPts.map(p => p.y);
                        let minR = Math.min(...vals),
                            maxR = Math.max(...vals);
                        const rng = (maxR - minR) || 2;
                        minR -= rng * 0.15;
                        maxR += rng * 0.15;
                        yR = y => pad.top + (1 - (y - minR) / (maxR - minR)) * pH;
                    }

                    /* grid + Y labels */
                    ctx.font = '10px sans-serif';
                    ctx.lineWidth = 1;
                    for (let i = 0; i <= 5; i++) {
                        const val = minT + (maxT - minT) * i / 5;
                        const yp = yT(val);
                        ctx.strokeStyle = C.grid;
                        ctx.beginPath();
                        ctx.moveTo(pad.left, yp);
                        ctx.lineTo(W - pad.right, yp);
                        ctx.stroke();
                        ctx.fillStyle = C.txt;
                        ctx.textAlign = 'right';
                        ctx.textBaseline = 'middle';
                        ctx.fillText(Math.round(val), pad.left - 4, yp);
                    }

                    /* X axis */
                    ctx.strokeStyle = C.axis;
                    ctx.beginPath();
                    ctx.moveTo(pad.left, H - pad.bottom);
                    ctx.lineTo(W - pad.right, H - pad.bottom);
                    ctx.stroke();

                    /* X ticks */
                    const nT = Math.min(8, Math.ceil(maxX));
                    for (let j = 0; j <= nT; j++) {
                        const xv = j * maxX / nT;
                        ctx.fillStyle = C.txt;
                        ctx.font = '10px sans-serif';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'top';
                        ctx.fillText(this._fmt(xv * 60), xP(xv), H - pad.bottom + 3);
                    }

                    /* phase markers */
                    markers.forEach(m => {
                        const xp = xP(m.t);
                        ctx.strokeStyle = C.mk;
                        ctx.setLineDash([4, 3]);
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(xp, pad.top);
                        ctx.lineTo(xp, H - pad.bottom);
                        ctx.stroke();
                        ctx.setLineDash([]);
                        ctx.fillStyle = C.mkTxt;
                        ctx.font = '9.5px sans-serif';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        ctx.fillText(m.lbl, xp, pad.top - 2);
                    });

                    /* temp curve */
                    if (tPts.length) {
                        ctx.strokeStyle = C.temp;
                        ctx.lineWidth = 2.2;
                        ctx.setLineDash([]);
                        ctx.beginPath();
                        tPts.forEach((p, i) => {
                            i ? ctx.lineTo(xP(p.x), yT(p.y)) : ctx.moveTo(xP(p.x), yT(p.y));
                        });
                        ctx.stroke();
                        ctx.fillStyle = C.temp;
                        tPts.forEach(p => {
                            ctx.beginPath();
                            ctx.arc(xP(p.x), yT(p.y), 2.5, 0, Math.PI * 2);
                            ctx.fill();
                        });
                    }

                    /* RoR curve */
                    if (rPts.length) {
                        ctx.strokeStyle = C.ror;
                        ctx.lineWidth = 1.5;
                        ctx.setLineDash([5, 4]);
                        ctx.beginPath();
                        rPts.forEach((p, i) => {
                            i ? ctx.lineTo(xP(p.x), yR(p.y)) : ctx.moveTo(xP(p.x), yR(p.y));
                        });
                        ctx.stroke();
                        ctx.setLineDash([]);
                    }
                },
            };
        }
    </script>
@endpush

<x-app-layout>

    {{-- expose user id for per-user localStorage key --}}
    <script>
        window._userId = {{ auth()->id() ?? 0 }};
    </script>

    <div x-data="roastSession(@js($batch))" class="mx-auto max-w-4xl space-y-4 px-4 py-4 sm:px-6 lg:px-8">

        {{-- ── Cancel confirm modal ── --}}
        <div x-show="showCancelModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="w-full max-w-sm rounded-3xl bg-white p-5 sm:p-6 shadow-xl">
                <p class="font-heading text-base text-[var(--coffee)] sm:text-lg">Batalkan Batch?</p>
                <p class="mt-2 text-sm text-[var(--muted)]">Data timer, checklist, dan suhu yang belum tersimpan akan
                    hilang.</p>
                <div class="mt-5 flex justify-end gap-3">
                    <button @click="showCancelModal = false" class="btn-ghost text-sm">Tidak</button>
                    <button @click="doCancel()"
                        class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700 sm:px-5">
                        Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Top action bar ── --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <button @click="showCancelModal = true"
                class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-2xl bg-red-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-800 sm:w-auto">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="hidden sm:inline">Batalkan Batch</span>
                <span class="sm:hidden">Batalkan</span>
            </button>

            <button x-show="showFinish" x-cloak @click="doFinish()"
                class="btn-earth inline-flex w-full items-center justify-center gap-2 sm:w-auto">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span class="hidden sm:inline">Selesai &amp; Simpan Batch</span>
                <span class="sm:hidden">Selesai &amp; Simpan</span>
            </button>
        </div>

        {{-- ── Checklist tahapan ── --}}
        <div class="surface-card p-5">
            <p class="eyebrow mb-3">Checklist Tahapan</p>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                <template x-for="stage in stages" :key="stage.key">
                    <div @click="recordStage(stage.key)"
                        :class="isRecorded(stage.key) ?
                            'bg-[var(--forest)] text-white border-[var(--forest)]' :
                            'bg-[var(--sand)] text-[var(--coffee)] border-[var(--line)] hover:bg-[var(--line)]'"
                        class="cursor-pointer select-none rounded-2xl border p-3 transition">
                        <p x-text="stage.label" class="text-sm font-semibold leading-tight"></p>
                        <p x-text="stageTime(stage.key)"
                            :class="isRecorded(stage.key) ? 'text-white/70' : 'text-[var(--muted)]'"
                            class="mt-1 text-xs"></p>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Temperature Input & Timer Panel ── --}}
        <div class="surface-card p-5 border border-[var(--accent)]/30">
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-[var(--line)] pb-4">
                <div>
                    <span x-text="timerDisplay"
                        class="font-mono text-4xl sm:text-6xl font-extrabold tracking-tight text-[var(--coffee)]">0:00</span>
                    <p x-text="metaText" class="text-xs text-[var(--muted)] mt-1"></p>
                </div>
                <div class="flex items-center gap-2">
                    <button x-show="showFinish" x-cloak @click="doFinish()"
                        class="btn-earth flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Selesai &amp; Simpan</span>
                    </button>
                    <button @click="showCancelModal = true"
                        class="rounded-xl border border-red-200 bg-red-50 px-3.5 py-2.5 text-xs font-semibold text-red-700 hover:bg-red-100 transition">
                        Batal
                    </button>
                </div>
            </div>

            <p class="eyebrow mb-3">Input Suhu Manual (°C)</p>

            {{-- Input display & action --}}
            <div class="mb-4 flex gap-3">
                <div class="relative flex-1">
                    <input type="text" readonly x-model="tempInput" placeholder="0°"
                        class="w-full rounded-2xl border border-[var(--line)] bg-white py-3 px-4 text-center text-3xl font-mono font-bold text-[var(--coffee)] shadow-inner focus:outline-none focus:ring-2 focus:ring-[var(--accent)]">
                    <button x-show="tempInput.length > 0" @click="pressClear()" class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-gray-100 p-1.5 text-gray-500 hover:bg-gray-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <button @click="logTemp()" class="btn-earth whitespace-nowrap px-6 py-3 font-bold text-base shadow-sm">
                    Catat Suhu
                </button>
            </div>

            {{-- Numpad Keyboard --}}
            <div class="grid grid-cols-4 gap-2 max-w-md mx-auto mb-4">
                <button type="button" @click="pressNum(7)" class="rounded-xl border border-gray-200 bg-gray-50 py-3 text-xl font-bold text-[var(--coffee)] hover:bg-amber-100/50 active:scale-95 transition">7</button>
                <button type="button" @click="pressNum(8)" class="rounded-xl border border-gray-200 bg-gray-50 py-3 text-xl font-bold text-[var(--coffee)] hover:bg-amber-100/50 active:scale-95 transition">8</button>
                <button type="button" @click="pressNum(9)" class="rounded-xl border border-gray-200 bg-gray-50 py-3 text-xl font-bold text-[var(--coffee)] hover:bg-amber-100/50 active:scale-95 transition">9</button>
                <button type="button" @click="pressBackspace()" class="rounded-xl border border-red-200 bg-red-50 py-3 text-sm font-bold text-red-700 hover:bg-red-100 active:scale-95 transition flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414-6.414A2 2 0 0110.828 5H19a2 2 0 012 2v10a2 2 0 01-2 2h-8.172a2 2 0 01-1.414-.586L3 12z"/>
                    </svg>
                </button>

                <button type="button" @click="pressNum(4)" class="rounded-xl border border-gray-200 bg-gray-50 py-3 text-xl font-bold text-[var(--coffee)] hover:bg-amber-100/50 active:scale-95 transition">4</button>
                <button type="button" @click="pressNum(5)" class="rounded-xl border border-gray-200 bg-gray-50 py-3 text-xl font-bold text-[var(--coffee)] hover:bg-amber-100/50 active:scale-95 transition">5</button>
                <button type="button" @click="pressNum(6)" class="rounded-xl border border-gray-200 bg-gray-50 py-3 text-xl font-bold text-[var(--coffee)] hover:bg-amber-100/50 active:scale-95 transition">6</button>
                <button type="button" @click="pressClear()" class="rounded-xl border border-amber-200 bg-amber-50 py-3 text-xs font-bold text-amber-800 hover:bg-amber-100 active:scale-95 transition">C</button>

                <button type="button" @click="pressNum(1)" class="rounded-xl border border-gray-200 bg-gray-50 py-3 text-xl font-bold text-[var(--coffee)] hover:bg-amber-100/50 active:scale-95 transition">1</button>
                <button type="button" @click="pressNum(2)" class="rounded-xl border border-gray-200 bg-gray-50 py-3 text-xl font-bold text-[var(--coffee)] hover:bg-amber-100/50 active:scale-95 transition">2</button>
                <button type="button" @click="pressNum(3)" class="rounded-xl border border-gray-200 bg-gray-50 py-3 text-xl font-bold text-[var(--coffee)] hover:bg-amber-100/50 active:scale-95 transition">3</button>
                <button type="button" @click="pressDot()" class="rounded-xl border border-gray-200 bg-gray-50 py-3 text-xl font-bold text-[var(--coffee)] hover:bg-amber-100/50 active:scale-95 transition">.</button>

                <button type="button" @click="pressNum(0)" class="col-span-3 rounded-xl border border-gray-200 bg-gray-50 py-3 text-xl font-bold text-[var(--coffee)] hover:bg-amber-100/50 active:scale-95 transition">0</button>
                <button type="button" @click="logTemp()" class="rounded-xl bg-[var(--forest)] py-3 text-sm font-bold text-white shadow hover:bg-[var(--forest)]/90 active:scale-95 transition flex items-center justify-center">
                    ↵
                </button>
            </div>

            <p class="eyebrow mb-2">Log Suhu &amp; Checklist</p>
            <div class="max-h-44 space-y-0.5 overflow-y-auto rounded-xl bg-[var(--canvas)] p-2 sm:p-3 border border-[var(--line)]">
                <template x-if="combinedLog.length === 0">
                    <p class="text-xs text-[var(--muted)] sm:text-sm">Belum ada data suhu / checklist.</p>
                </template>
                <template x-for="(entry, idx) in combinedLog" :key="idx">
                    <div
                        class="flex justify-between gap-2 border-b border-[var(--line)] py-1 text-xs sm:text-sm last:border-0">
                        <span x-text="_fmt(entry.time)" class="font-mono text-[var(--muted)] shrink-0"></span>
                        <span x-text="entry.type === 'temp' ? entry.temp + '°C' : entry.label"
                            :class="entry.type === 'stage' ?
                                'font-semibold text-[var(--forest)]' :
                                'font-mono text-[var(--ink)]'"
                            class="text-right"></span>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Notes ── --}}
        <div class="surface-card p-5">
            <p class="eyebrow mb-2">Catatan Roasting</p>
            <textarea x-model="notes" @input.debounce.500ms="_persist()" rows="3"
                class="block w-full rounded-2xl border border-[var(--line)] bg-white p-3 text-sm focus:border-[var(--accent)] focus:ring-0"
                placeholder="Aroma, suara crack, hasil pengamatan, dll."></textarea>
        </div>

        {{-- ── Chart ── --}}
        <div class="surface-card p-5">
            <p class="eyebrow mb-3">Grafik Evaluasi (Suhu &amp; RoR)</p>
            <div class="overflow-hidden rounded-xl">
                <canvas id="roastChart" style="display:block;width:100%;"></canvas>
            </div>
            <div class="mt-3 flex flex-col gap-2 text-xs text-[var(--muted)] sm:flex-row sm:justify-center sm:gap-6">
                <span class="flex items-center gap-1.5">
                    <span class="inline-block h-0.5 w-4 rounded bg-[var(--accent)]"></span>
                    <span class="whitespace-nowrap">Suhu (°C)</span>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-4 border-t-2 border-dashed border-red-600"></span>
                    <span class="whitespace-nowrap">RoR (°C/min)</span>
                </span>
            </div>
        </div>

        {{-- ── Hidden forms ── --}}
        <form id="cancelForm" method="POST" action="{{ route('admin.roasting-logs.session.cancel') }}" hidden>
            @csrf
            @method('DELETE')
        </form>

        <form id="saveForm" method="POST" action="{{ route('admin.roasting-logs.store') }}" hidden>
            @csrf
            <input type="hidden" id="payloadInput" name="payload">
        </form>

    </div>
</x-app-layout>
