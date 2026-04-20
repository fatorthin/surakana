<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'total_amount',
    'status',
    'shipping_address',
    'shipping_method',
    'customer_notes',
    'payment_method',
])]
class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_SHIPPED = 'shipped';

    public const STATUS_COMPLETED = 'completed';

    public const PAYMENT_CASH = 'cash';

    public const PAYMENT_QRIS = 'qris';

    public const PAYMENT_TRANSFER = 'transfer';

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED,
            self::STATUS_COMPLETED,
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
