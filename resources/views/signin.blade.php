@extends('auth_layout')
@section('content')

    <form method="post" action="{{ action('AuthController@postLogin') }}" class="form-signin">
        {!! csrf_field() !!}
        @if(session()->has('message'))
            <div class="alert alert-{{ session('message_type') }}">
                {!! session('message') !!}
            </div>
        @endif

        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="email" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <p></p>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <hr>
        <p style="font-size: 14px">
            Didn't have an account? <a href="{{ action('AuthController@getRegister') }}">click here</a> to register
        </p>
        <p class="mt-5 mb-3 text-muted" style="font-size: 14px">&copy; Copyright {{ date('Y') }}. All Right Reserved</p>
    </form>

@endsection