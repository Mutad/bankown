<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css')  }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css')  }}">

    <link rel="icon" type="image/png" href="{{asset('logo.png')}}"/>
    {{-- <link rel="icon" type="image/png" href="https://example.com/favicon.png"/> --}}
</head>

<body>
    {{-- @include('partials.header') --}}

    @section('body')
    @show
    <script src="{{asset('js/app.js')}}"></script>
</body>

</html>