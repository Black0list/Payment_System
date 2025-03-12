<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial',
        'sender_wallet',
        'receiver_wallet',
        'date',
        'amount',
        'state_id',
        'user_id',
    ];

    public function state()
    {
        return $this->hasOne(State::class);
    }

    public function sender()
    {
        return $this->belongsTo(Wallet::class, 'sender_wallet');
    }

    public function receiver()
    {
        return $this->belongsTo(Wallet::class, 'receiver_wallet');
    }

    public function admin()
    {
        return $this->hasOne(User::class);
    }
}
