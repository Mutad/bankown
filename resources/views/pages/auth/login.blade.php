@extends('app')

@section('content')
{{-- <form class="d-flex flex-column login container align-items-center ">
    <h1 class="align-self-start">Please sign in</h1>

    <div class="form-group">
        <input class="form-control" type="text" name="login" id="input-login" placeholder="Your login">
    </div>
    <div class="form-group">
        <input class="form-control" type="text" name="password" id="input-password" placeholder="Your password">
    </div>
</form> --}}
<div class="container">
    <div class="row">
        <div class="col-lg login">
            <h1 class="align-self-start">Please sign in</h1>
            <form method="POST" action="{{route('auth.login')}}">
                @csrf
                <div class="form-group">
                    <input class="form-control" type="email" name="email" id="input-email" placeholder="Your email">
                    @if ($errors->has('email'))
                    <div class="error">
                        {{ $errors->first('email') }}
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="password" id="input-password"
                        placeholder="Your password">
                    @if ($errors->has('password'))
                    <div class="error">
                        {{ $errors->first('password') }}
                    </div>
                    @endif
                </div>
                @if ($errors->has('result'))
                <div class="alert alert-danger" role="alert">
                    {{$errors->first('result')}}
                </div>
                @endif
                <input type="submit" class="btn btn-primary btn-block" value="Submit">
            </form>
            <div>
            <a href="{{route('auth.forgot')}}">Forgot your login or password?</a>
            </div>
            <div>
                <a href="{{route('auth.register')}}">Dont have an account? Create one now.</a>
            </div>
        </div>
        <div class="col-sm">
            @include('modules.features')
        </div>
    </div>
</div>
@endsection