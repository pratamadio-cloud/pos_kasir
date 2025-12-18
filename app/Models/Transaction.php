<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_no',
        'cashier_id',
        'total',
        'paid',
        'change',
        'payment_method'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'paid' => 'decimal:2',
        'change' => 'decimal:2',
        'created_at' => 'datetime'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}