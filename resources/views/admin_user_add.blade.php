@extends('layout')
@section('content')


    <form action="{{ action('AdminUserController@postAddSave') }}" method="post">
        {!! csrf_field() !!}
    <table class="table table-striped table-bordered">
        <tbody>
        <tr>
            <td width="20%">Nama</td><td width="10px">:</td><td><input type="text" class="form-control" name="name" required ></td>
        </tr>
        <tr>
            <td>Email</td><td>:</td><td><input type="text" class="form-control" name="email" required ></td>
        </tr>
        <tr>
            <td>Password</td><td>:</td><td><input type="password" class="form-control" name="password" required ></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="submit" class="btn btn-primary" value="Add New Data"></td>
        </tr>
        </tbody>
    </table>
    </form>
@endsection