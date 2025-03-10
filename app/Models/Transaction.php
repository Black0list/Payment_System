<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial',
        'sender',
        'receiver',
        'date',
        'amount',
        'state_id'
    ];

    public function state()
    {
        return $this->hasOne(State::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
