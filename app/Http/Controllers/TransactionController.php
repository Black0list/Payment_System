<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\State;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TransactionController extends Controller
{
    public function transfer(Request $request)
    {
        $data = $request->validate([
            'receiver_email' => 'required|string|exists:users,email',
            'receiver_name' => 'required|string|exists:users,name',
            'amount' => 'required|numeric',
        ], [
                'receiver_email.exists' => 'This Receiver Email does not exist',
                'receiver_name.exists' => 'This Username does not exist',
                'receiver_email.required' => 'This Receiver Email is required',
                'receiver_name.required' => 'This Username is required',
        ]);

        DB::beginTransaction();
        $sender = auth()->user();
        $receiver = User::where('email', $data['receiver_email'])->first();
        $name = $data['receiver_name'];

        if($receiver->wallet->amount < $data['amount']){
            return [
                'error' => 'Insufficient funds',
            ];
        }

        if($receiver->email === $sender->email){
            return [
                'error' => 'You cannot transfer money to yourself'
            ];
        }

        if($receiver->name != $name || $receiver->email != $data['receiver_email']){
            return [
                'error' => 'Receiver data is Invalid'
            ];
        }


        if(!$receiver->wallet || !$sender->wallet){
            return [
                "error" => 'Wallet not Found'
            ];
        }

        if($receiver->name === $name && $receiver->email === $data['receiver_email'] && $receiver->wallet && $sender->wallet && $sender->wallet->amount >= $data['amount'])
        {
            $sender->wallet->amount -= $request['amount'];
            $receiver->wallet->amount += $request['amount'];

            $sender->wallet->save();
            $receiver->wallet->save();

            try {
                $randomString = Str::random(10).$sender->id.'S'.$receiver->id;

                $state = 'done';

                $StateExists = State::whereRaw('LOWER(name) = ?', $state)->exists();

                if (!$StateExists) {
                    $state = State::create(['name' => $state]);
                    $stateId = $state->id;
                } else {
                    $stateId = State::whereRaw('LOWER(name) = ?', $state)->first()->id;
                }

                Transaction::create([
                    'serial' => $randomString,
                    'amount' => $request['amount'],
                    'receiver_wallet' => $receiver->wallet->id,
                    'sender_wallet' => $sender->wallet->id,
                    'state_id' => $stateId,
                    'date' => now(),
                    'user_id' => null
                ]);

                DB::commit();
            } catch (\Exception $e) {
                return [
                    'error' => $e->getMessage()
                ];
            }
        } else {
            DB::rollBack();
        }








//        DB::beginTransaction();
//        $receiver_wallet = Wallet::where('serial', $request['receiver_wallet'])->first();
//        $receiver_name = $request['receiver_name'];
//
//        $senderIsValid = $sender->wallet->serial != null ? true : false;
//        $validAmount = auth()->user()->wallet->amount >= $request['amount'];
//
//        if(!$validAmount){
//            return response()->json(['message' => 'Invalid amount'], 400);
//        }
//
//        if($receiver_name === $receiver_wallet->user->name && $receiver_wallet->serial != $request['receiver_serial'] && $senderIsValid && $validAmount) {
//            $receiver_wallet->amount += $request['amount'];
//            auth()->user()->wallet->amount -= $request['amount'];
//
//            auth()->user()->wallet->save();
//            $receiver_wallet->save();
//            DB::commit();
//        } else {
//            DB::rollBack();
//        }
    }

    public function cancel(Request $request)
    {
        $data = $request->validate([
            'state' => "required|string",
            'transaction' => "required|string|exists:transactions,id",
        ], [
            'state.exists' => 'This State does not exist',
        ]);

        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($data['transaction']);

            if (!$transaction->sender || !$transaction->receiver) {
                return response()->json(['error' => 'Sender or Receiver wallet not found'], 404);
            }

            $state = strtolower($data['state']);
            $stateModel = State::firstOrCreate(['name' => $state]);

            if ($transaction->state_id === $stateModel->id) {
                return response()->json(['message' => 'Transaction is already cancelled'], 400);
            }

            $transaction->sender->amount += $transaction->amount;
            $transaction->receiver->amount -= $transaction->amount;

            $transaction->sender->save();
            $transaction->receiver->save();

            $transaction->state_id = $stateModel->id;
            $transaction->save();

            DB::commit();
            return response()->json(['message' => 'Transaction successfully cancelled'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
