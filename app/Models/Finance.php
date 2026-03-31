<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['type', 'amount', 'description', 'transaction_date', 'payment_method'])]
class Finance extends Model
{
    use HasFactory;

    public const TYPE_INCOME = 'income';

    public const TYPE_EXPENSE = 'expense';

    public const PAYMENT_CASH = 'cash';

    public const PAYMENT_QRIS = 'qris';

    public const PAYMENT_TRANSFER = 'transfer';

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    public static function types(): array
    {
        return [
            self::TYPE_INCOME,
            self::TYPE_EXPENSE,
        ];
    }

    public static function paymentMethods(): array
    {
        return [
            self::PAYMENT_CASH,
            self::PAYMENT_QRIS,
            self::PAYMENT_TRANSFER,
        ];
    }
}
