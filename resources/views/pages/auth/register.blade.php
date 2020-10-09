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
<div class="container register">
    <div class="text-center">
        <h1>Create your account</h1>
        <div>
            <a href="/auth/login">Already have an account? Find it here.</a>
        </div>
        <hr>
    </div>
    <form method="POST" action="{{route('register')}}" class="d-flex flex-column align-items-center">
        @csrf

        {{-- Name --}}
        <div class="wrapper col-lg-8">
            <div class="form-group w-100">
                <input type="text" placeholder="First name" name="first_name"
                    class="form-control mr-1 {{ $errors->has('first_name') ? 'error' : '' }}" value="{{ old('first_name') }}">
                @if ($errors->has('first_name'))
                <div class="error">
                    {{ $errors->first('first_name') }}
                </div>
                @endif

            </div>
            <div class="form-group w-100">
                <input type="text" placeholder="Last name" name="last_name"
                    class="form-control ml-1 {{ $errors->has('last_name') ? 'error' : '' }}" value="{{ old('last_name') }}">
                @if ($errors->has('last_name'))
                <div class="error">
                    {{ $errors->first('last_name') }}
                </div>
                @endif

            </div>
        </div>
        {{-- Country --}}
        <div class="form-group col-lg-8 flex-column">
            @include('partials.select-country')
            @if ($errors->has('country'))
            <div class="error">
                {{ $errors->first('country') }}
            </div>
            @endif
        </div>
        {{-- Birthday --}}
        <div class="form-group col-lg-8">
            <input type="date" name="birth_date" placeholder="dd/mm/yyyy"
                class="form-control {{ $errors->has('birth_date') ? 'error' : '' }}" value="{{ old('birth_date') }}">
            @if ($errors->has('birth_date'))
            <div class="error">
                {{ $errors->first('birth_date') }}
            </div>
            @endif
        </div>
        <hr>
        {{-- Login --}}
        <div class="form-group col-lg-8 flex-column">
            <input type="email" name="email" id="input-email" placeholder="name@example.com"
                class="form-control {{ $errors->has('email') ? 'error' : '' }}" value="{{ old('email') }}">
            @if ($errors->has('email'))
            <div class="error">
                {{ $errors->first('email') }}
            </div>
            @endif
            <small id="loginHelp" class="form-text text-muted">This will be your new login.</small>
        </div>
        {{-- Password --}}
        <div class="form-group col-lg-8">
            <input type="password" name="password" id="input-password" placeholder="Password"
                class="form-control {{ $errors->has('password') ? 'error' : '' }}" value="{{ old('password') }}">
            @if ($errors->has('password'))
            <div class=" error">
            {{ $errors->first('password') }}
        </div>
        @endif
        </div>
        <div class="form-group col-lg-8">
            <input type="password" name="password_repeat" id="input-password-repeat" placeholder="Confirm password"
                class="form-control {{ $errors->has('password_repeat') ? 'error' : '' }}" value="{{ old('password_repeat') }}">
                @if ($errors->has('password_repeat'))
                <div class=" error">
                    {{ $errors->first('password_repeat') }}
                </div>
                @endif
        </div>

{{-- Phone number --}}
{{-- <hr>
        <div class="form-group col-lg-8">
            @include('partials.select-country-codes')
        </div> --}}

{{-- <hr>
        <div class="form-check">

            <input type="checkbox" name="announcements" id="checkbox-announcements" class="form-check-input">
            <label for="checkbox-announcements" class="form-check-label d-flex flex-column">
                <span class="h5 mb-0">Announcements</span>
                <small class="text-muted">Get announcements, recommendations, and updates about Apple products, services, and software.</small>
            </label>
        </div> --}}
{{-- <div class="form-check">

            <input type="checkbox" name="announcements" id="checkbox-announcements" class="form-check-input">
            <label for="checkbox-announcements" class="form-check-label d-flex flex-column">
                <span class="h5 mb-0">Announcements</span>
                <small class="text-muted">Get announcements, recommendations, and updates about Apple products, services, and software.</small>
            </label>
        </div> --}}
<hr>

<div class="col-lg-8">
    <input type="submit" class="btn btn-primary btn-block form-control" value="Continue">
</div>
</form>
<div class="text-center col-sm-4 offset-sm-4 text-muted">
    <small>

        Your data is stored on our server for your security, support and comfort.
        <a href="/legal/privacy">See how your data is managed</a>
    </small>
</div>
</div>
@endsection