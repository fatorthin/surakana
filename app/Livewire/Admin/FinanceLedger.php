<?php

namespace App\Livewire\Admin;

use App\Models\Finance;
use Livewire\Component;
use Livewire\WithPagination;

class FinanceLedger extends Component
{
    use WithPagination;

    public string $type = Finance::TYPE_INCOME;
    public string $amount = '';
    public string $description = '';
    public string $transaction_date = '';
    public string $payment_method = Finance::PAYMENT_CASH;

    public string $notification = '';

    public function boot(): void
    {
        if (! $this->transaction_date) {
            $this->transaction_date = now()->toDateString();
        }
    }

    public function save(): void
    {
        $this->validate([
            'type'             => ['required', 'in:' . implode(',', Finance::types())],
            'amount'           => ['required', 'numeric', 'min:0'],
            'description'      => ['required', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'payment_method'   => ['required', 'in:' . implode(',', Finance::paymentMethods())],
        ]);

        Finance::query()->create([
            'type'             => $this->type,
            'amount'           => $this->amount,
            'description'      => $this->description,
            'transaction_date' => $this->transaction_date,
            'payment_method'   => $this->payment_method,
        ]);

        $this->reset(['amount', 'description']);
        $this->resetPage();

        $this->notification = 'Catatan keuangan berhasil ditambahkan.';
        $this->js('setTimeout(() => $wire.set("notification", ""), 3000)');
    }

    public function delete(Finance $finance): void
    {
        $finance->delete();

        $this->notification = 'Catatan berhasil dihapus.';
        $this->js('setTimeout(() => $wire.set("notification", ""), 3000)');
    }

    public function render()
    {
        $entries = Finance::query()->latest('transaction_date')->paginate(12);

        $summary = [
            'income'  => (float) Finance::query()->where('type', Finance::TYPE_INCOME)->sum('amount'),
            'expense' => (float) Finance::query()->where('type', Finance::TYPE_EXPENSE)->sum('amount'),
        ];

        // Summary by payment method (income only)
        $byMethod = [];
        foreach (Finance::paymentMethods() as $method) {
            $byMethod[$method] = (float) Finance::query()
                ->where('type', Finance::TYPE_INCOME)
                ->where('payment_method', $method)
                ->sum('amount');
        }

        return view('livewire.admin.finance-ledger', [
            'entries'        => $entries,
            'types'          => Finance::types(),
            'paymentMethods' => Finance::paymentMethods(),
            'summary'        => $summary,
            'byMethod'       => $byMethod,
        ]);
    }
}
