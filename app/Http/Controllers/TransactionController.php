<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class TransactionController extends Controller
{
    public function transfer(Request $request)
    {
        $request->validate([
            'receiver_wallet' => 'required|string|exists:wallets,serial',
            'receiver_name' => 'required|string|exists:users,name',
            'amount' => 'required|numeric',
        ], [
                'receiver_wallet.exists' => 'This Wallet does not exist',
                'receiver_name.exists' => 'This User does not exist',
                'receiver_wallet.required' => 'This Receiver Wallet is required',
                'receiver_name.required' => 'This Username is required',
        ]);

        DB::beginTransaction();
        $receiver_wallet = Wallet::where('serial', $request['receiver_wallet'])->first();
        $receiver_name = $request['receiver_name'];

        $senderIsValid = auth()->user()->wallet->serial != null ? true : false;
        $validAmount = auth()->user()->wallet->amount >= $request['amount'];

        if(!$validAmount){
            return response()->json(['message' => 'Invalid amount'], 400);
        }

        if($receiver_name === $receiver_wallet->user->name && $receiver_wallet->serial != $request['receiver_serial'] && $senderIsValid && $validAmount) {
            $receiver_wallet->amount += $request['amount'];
            auth()->user()->wallet->amount -= $request['amount'];

            auth()->user()->wallet->save();
            $receiver_wallet->save();
            DB::commit();
        } else {
            DB::rollBack();
        }
    }
}
