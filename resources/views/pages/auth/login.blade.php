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
            <form action="">

                <div class="form-group">
                    <input class="form-control" type="text" name="login" id="input-login" placeholder="Your login">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="password" id="input-password"
                        placeholder="Your password">
                </div>
                <input type="submit" class="btn btn-primary btn-block" value="Submit">
            </form>
            <div>
                <a href="/auth/forgot">Forgot your login or password?</a>
            </div>
            <div>
                <a href="/auth/register">Dont have an account? Create one now.</a>
            </div>
        </div>
        <div class="col-sm">
            @include('modules.features')
        </div>
    </div>
</div>
@endsection