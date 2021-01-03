@extends('app')

@section('content')


@switch($error_code)
@case(404)
<h1>The page you’re looking for can’t be found.</h1>
<h5><a href="{{route('app.')}}/hub" class="more">Go to Hub</a></h5>
@break
@case(2)
@break
@default

@endswitch


@endsection