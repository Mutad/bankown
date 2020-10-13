@extends('app')

@section('content')
<div class="h-100 w-100 d-flex flex-row flex-grow-1 hub">
    @include('modules.banking.sidebar')
    <div class="d-flex flex-column w-75">
        @section('info-panel')

            {{-- <div class="bg-light p-2 d-flex justify-content-between">
                <div></div>
                <div class="text-muted">{{date('F j, Y')}}</div>
            </div> --}}
        @show   
        @section('layout')
        info panel
        @show
    </div>
</div>
@endsection