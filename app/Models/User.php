<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi mass-assignment
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Kolom yang disembunyikan saat serialize
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+
    ];

    /**
     * Relasi: User (Kasir) punya banyak transaksi
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'cashier_id');
    }

    /**
     * Helper role
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function roleLabel()
    {
        return ucfirst($this->name);
    }

}
