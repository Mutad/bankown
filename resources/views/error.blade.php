@extends('app')

@section('body')

<div class="content d-flex justify-content-center align-items-center text-center flex-column">
    {{$error_code}}
    @switch($error_code)
    @case(404)
    <p>The page you are looking for does not exist</p>
    @break
    @case(2)

    @break
    @default

    @endswitch

</div>
@endsection