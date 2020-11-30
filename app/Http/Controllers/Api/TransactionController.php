<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use App\Http\Requests\TransactionRequest;
use DB;
use Log;

class TransactionController extends Controller
{
    public function createTransaction(TransactionRequest $request)
    {
        $senderCard = \App\Card::find($request->sender_card_id);
        $recieverCard = \App\Card::find($request->reciever_card_id);

        if ($senderCard->balance >= $request->amount)
        {
            try {
                // dd($request->amount);
                DB::transaction(function () use (&$senderCard,&$recieverCard,&$request){
                    $senderCard->balance-=$request->amount;
                    $recieverCard->balance+=$request->amount;
                    
                    $transaction = new Transaction($request->only(['sender_card_id','reciever_card_id','amount']));
                    $transaction->save();
                    $senderCard->save();
                    $recieverCard->save();
                });
                return response()->json(null, 200);
            } catch (\Throwable $th) {
                return response()->json(['error'=>'an error has occured durring transaction'], 406);
            }
        }
        else{
                return response()->json(['error'=>'not enough money on balance to perform transaction'], 406);
        }
    }
}