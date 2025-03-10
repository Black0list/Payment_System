<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
            'user_id' => 'required|integer'
        ]);

        Wallet::create([
            'user_id' => $data['user_id'],
            'amount' => $data['amount'],
            'serial' => $data['serial']
        ]);

    }
}
