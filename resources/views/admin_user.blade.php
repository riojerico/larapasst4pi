@extends('layout')
@section('content')

    <p>
        <a href="{{ action('AdminUserController@getAdd') }}" class="btn btn-primary">Add New User</a>
    </p>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>CREATED</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>ROLE</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result as $row)
            <tr>
                <td>{{ $row->created_at }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->role }}</td>
                <td>
                    <a href="{{ action('AdminUserController@getEdit',['id'=>$row->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                    <a onclick="if(!confirm('Are you sure want to delete?')) return false" href="{{ action('AdminUserController@getDelete',['id'=>$row->id]) }}" class="btn btn-warning btn-sm">Delete</a>
                </td>
            </tr>
            @endforeach
            @if(count($result)==0)
            <tr>
                <td colspan="6" align="center">There is no data yet</td>
            </tr>
            @endif
        </tbody>

    </table>

    <p>
        <div align="center">
        {!! $result->appends($_GET)->links() !!}
        </div>
    </p>
@endsection