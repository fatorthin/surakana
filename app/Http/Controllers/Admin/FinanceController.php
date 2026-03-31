<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function index(): View
    {
        $entries = Finance::query()->latest('transaction_date')->paginate(12);

        return view('admin.finances.index', [
            'entries' => $entries,
            'types' => Finance::types(),
            'summary' => [
                'income' => (float) Finance::query()->where('type', Finance::TYPE_INCOME)->sum('amount'),
                'expense' => (float) Finance::query()->where('type', Finance::TYPE_EXPENSE)->sum('amount'),
            ],
        ]);
    }
}
