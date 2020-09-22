@extends('app')

@section('body')
@include('partials.header')

<div class="container-lg d-flex flex-column content welcome">
  <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center">
    @include('modules.bankown-description')
    @include('modules.placeholder')
  </div>
</div>
</div>
@include('partials.footer')
@endsection