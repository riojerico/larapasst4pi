@extends('auth_layout')
@section('content')

    <form method="post" enctype="multipart/form-data" action="{{ action('AuthController@postRegister') }}" class="form-signin">
        {!! csrf_field() !!}
        @if(session()->has('message'))
            <div class="alert alert-{{ session('message_type') }}">
                {!! session('message') !!}
            </div>
        @endif

        <h1 class="h3 mb-3 font-weight-normal">REGISTER API FOR PARTICIPANT</h1>
        <hr>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Email</label>
            <input type="email" class="form-control" name="email" required>
            <div class="help-block">Input your registered email at T4T</div>
        </div>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Password</label>
            <input type="password" class="form-control" name="password" required>
            <div class="help-block">Enter a new password</div>
        </div>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Captcha</label>
            <p><img src="{!! captcha_src() !!}" alt=""></p>
            <input type="text" class="form-control" name="captcha" required >
            <div class="help-block">Enter the text image shown</div>
        </div>

        <p></p>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign Up</button>
        <hr>
        <p style="font-size: 14px">
            Do you have an account? <a href="{{ action('AuthController@getLogin') }}">click here</a> to login
        </p>

        <p style="font-size: 14px;" class="mt-5 mb-3 text-muted">&copy; Copyright {{ date('Y') }}. All Right Reserved</p>
    </form>

@endsection