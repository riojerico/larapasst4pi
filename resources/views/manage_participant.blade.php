@extends('layout')
@section('content')


    <table class="table datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>CREATED</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>PHOTO</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->created_at }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->email }}</td>
                    <td><img src="{{ $row->photo?asset($row->photo):'http://placehold.it/50x50' }}" width="50px" height="50px" alt="Photo"></td>
                    <td>
                        <a href="{{ action('ManageParticipantController@getEdit',['id'=>$row->id]) }}" title="Edit participant" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                        <a href="{{ action('ManageParticipantController@getDelete',['id'=>$row->id]) }}" onclick="if(!confirm('Are you sure want to delete?')) return false" title="Delete participant" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


@endsection