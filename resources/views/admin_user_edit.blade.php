@extends('layout')
@section('content')

    @php /** @var \App\CBModels\Users $row */ @endphp

    <form action="{{ action('AdminUserController@postEditSave',['id'=>$row->getId()]) }}" method="post">
        {!! csrf_field() !!}
    <table class="table table-striped table-bordered">
        <tbody>
        <tr>
            <td width="20%">Nama</td><td width="10px">:</td><td><input value="{{ $row->getName() }}" type="text" class="form-control" name="name" required ></td>
        </tr>
        <tr>
            <td>Email</td><td>:</td><td><input value="{{ $row->getEmail() }}" type="text" class="form-control" name="email" required ></td>
        </tr>
        <tr>
            <td>Password</td><td>:</td><td><input type="password" class="form-control" name="password" ><br/><small>Please leave empty if not change</small></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="submit" class="btn btn-primary" value="Save"></td>
        </tr>
        </tbody>
    </table>
    </form>
@endsection