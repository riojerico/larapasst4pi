@extends('auth_layout')
@section('content')

    <form method="post" enctype="multipart/form-data" action="{{ action('AuthController@postRegister') }}" class="form-signin">
        {!! csrf_field() !!}
        @if(session()->has('message'))
            <div class="alert alert-{{ session('message_type') }}">
                {!! session('message') !!}
            </div>
        @endif

        <h1 class="h3 mb-3 font-weight-normal">REGISTER T4T WEB API</h1>
        <hr>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Last Name</label>
            <input type="text" class="form-control" name="lastname" required>
        </div>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Comment</label>
            <input type="text" class="form-control" name="comment" required>
        </div>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Photo</label>
            <input type="file" class="form-control" name="photo" required>
        </div>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Password</label>
            <input type="password" class="form-control" name="password" required>
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