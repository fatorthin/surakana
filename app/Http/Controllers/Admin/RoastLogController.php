<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoastLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoastLogController extends Controller
{
    public function index(): View
    {
        return view('admin.roasting-logs.index', [
            'activeSession' => session('roast_session'),
        ]);
    }

    public function startSession(Request $request): RedirectResponse
    {
        $request->validate([
            'roaster_name'   => ['required', 'string', 'max:150'],
            'bean_name'      => ['required', 'string', 'max:150'],
            'origin'         => ['nullable', 'string', 'max:200'],
            'varietas'       => ['nullable', 'string', 'max:200'],
            'process_method' => ['nullable', 'string', 'max:200'],
            'green_weight'   => ['required', 'numeric', 'min:0.01'],
            'charge_temp'    => ['required', 'numeric', 'min:0'],
        ]);

        session(['roast_session' => [
            'roasterName' => $request->input('roaster_name'),
            'beanName'    => $request->input('bean_name'),
            'origin'      => $request->input('origin', ''),
            'varietas'    => $request->input('varietas', ''),
            'proses'      => $request->input('process_method', ''),
            'greenWeight' => (float) $request->input('green_weight'),
            'chargeTemp'  => (float) $request->input('charge_temp'),
        ]]);

        return redirect()->route('admin.roasting-logs.session');
    }

    public function session(): View|RedirectResponse
    {
        $batch = session('roast_session');

        if (! $batch) {
            return redirect()->route('admin.roasting-logs.index');
        }

        return view('admin.roasting-logs.session', compact('batch'));
    }

    public function cancelSession(): RedirectResponse
    {
        session()->forget('roast_session');

        return redirect()->route('admin.roasting-logs.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'payload' => ['required', 'string', 'max:131072'],
        ]);

        $data = json_decode($request->input('payload'), true);

        if (! is_array($data) || empty($data['roasterName']) || empty($data['beanName'])) {
            return back()->with('error', 'Data batch tidak valid.');
        }

        RoastLog::create([
            'created_by'       => Auth::id(),
            'roaster_name'     => Str::limit((string) $data['roasterName'], 150),
            'bean_name'        => Str::limit((string) $data['beanName'], 150),
            'origin'           => ! empty($data['origin']) ? Str::limit((string) $data['origin'], 200) : null,
            'varietas'         => ! empty($data['varietas']) ? Str::limit((string) $data['varietas'], 200) : null,
            'process_method'   => ! empty($data['proses']) ? Str::limit((string) $data['proses'], 200) : null,
            'green_weight'     => (float) ($data['greenWeight'] ?? 0),
            'charge_temp'      => isset($data['chargeTemp']) ? (float) $data['chargeTemp'] : null,
            'duration_seconds' => isset($data['duration']) ? (int) $data['duration'] : null,
            'checklist'        => is_array($data['checklist'] ?? null) ? $data['checklist'] : null,
            'temp_log'         => is_array($data['tempLog'] ?? null) ? $data['tempLog'] : null,
            'notes'            => ! empty($data['notes']) ? (string) $data['notes'] : null,
            'roast_date'       => now(),
        ]);

        session()->forget('roast_session');

        return redirect()
            ->route('admin.roasting-logs.index')
            ->with('status', 'Batch roasting berhasil disimpan.');
    }
}
