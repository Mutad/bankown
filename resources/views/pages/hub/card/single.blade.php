@extends('pages.hub.layout')

@section('layout')
<div class="d-flex align-content-start flex-wrap">
    <div class="plate">
        <h1>Available funds</h1>
        <p>{{$card->balance}}</p>
    </div>
</div>
@endsection