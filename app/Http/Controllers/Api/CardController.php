<?php

namespace App\Http\Controllers\Api;

use App\Card;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Log;
use App\Http\Requests\CardCreationRequest;
use App\Http\Requests\CardUpdationRequest;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check())
            return response()->json(Auth::user()->cards, 200);
        else
            return response()->json(['message' => 'access forbiden'], 403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CardCreationRequest $request)
    {
        $card = new Card($request->all());

        $card->balance = 0;
        $card->number = "0000000000000000";
        $card->user_id = Auth::user()->id;

        Auth::user()->cards()->save($card);

        $card->number = $this->generateCardNumber($card->id);
        $card->save();

        return response()->json($card, 201);
    }

    function generateCardNumber($id)
    {
        $prefix = "41110441";

        $postfix = "";
        for ($i = 0; $i < 8 - strlen($id); $i++) {
            $postfix .= "0";
        }
        $postfix .= $id;
        return $prefix . $postfix;
    }

    public function showByNumber($number)
    {
        $card = Card::where('number', $number)->first();
        if ($card) {
            return response()->json([
                'currency' => $card->currency,
                'owner' => $card->owner->full_name(),
            ], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        if (Auth::user()->id == $card->owner->id) {
            return response()->json($card, 200);
        }

        return response()->json(['error' => 'not enough access'], 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CardUpdationRequest  $request
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(CardUpdationRequest $request, Card $card)
    {
        if (Auth::user()->id != $card->owner->id)
            return response()->json(['error' => 'not enough access'], 403);

        $card->name = $request->name;
        $card->save();
        return response()->json($card, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        if ($card->user_id != Auth::user()->id)
            return response()->json(['error' => 'not enough access'], 403);

        $card->delete();
        return response()->json(null, 200);
    }
}