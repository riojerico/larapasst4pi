@extends('layout')
@section('content')


    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>CREATED</th>
                <th>STATUS</th>
                <th>NAME</th>
                <th>IP</th>
                <th>USER AGENT</th>
                <th>DESCRIPTION</th>
                <th>DETAIL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result as $row)
            <tr>
                <td>{{ $row->created_at }}</td>
                <td>{{ $row->response_code }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->description }}</td>
                <td>{{ $row->useragent }}</td>
                <td>{{ $row->ip }}</td>
                <td>
                    <a href="{{ action('ActivityLogController@getDetail',['id'=>$row->id]) }}" class="btn btn-primary btn-sm">Detail</a>
                </td>
            </tr>
            @endforeach
            @if(count($result)==0)
            <tr>
                <td colspan="7" align="center">There is no data yet</td>
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