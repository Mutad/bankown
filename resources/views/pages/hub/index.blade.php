@extends('pages.hub.layout')

@section('layout')
<div class="d-flex flex-column flex-wrap">
    <div class="m-4">
        <h2>Bankown Dashboard</h2>
        <h6 class="text-muted">Welcome back, {{Auth::user()->full_name()}}</h6>
    </div>
    <div class="d-flex flex-nowrap plate-list p-2">
        @foreach (Auth::user()->cards as $card)
        <a href="#" class="mr-4 btn p-0">
            @include('modules.banking.card.single',['card'=>$card])
        </a>

        @endforeach
        {{-- @include('modules.banking.card.add-btn') --}}
    </div>
    <div class="d-flex flex-nowrap overflow-auto p-3 plate-list">
        <a href="" class="btn btn-primary mr-4 text-nowrap">Make transaction</a>
        <a href="" class="btn btn-primary mr-4 text-nowrap">Open new card</a>
    </div>
    <example-component></example-component>
</div>
@endsection