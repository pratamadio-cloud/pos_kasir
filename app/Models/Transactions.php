<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'transactions';
    protected $fillable = ['invoice_no', 'cashier_id', 'total', 'paid', 'change', 'payment_method'];
}
