@extends('layout')
@section('content')


    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>CREATED</th>
                <th>USER AGENT</th>
                <th>IP</th>
                <th>REQUEST COUNT</th>
                <th>UNBLOCK</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result as $row)
            <tr>
                <td>{{ $row->created_at }}</td>
                <td>{{ $row->useragent }}</td>
                <td>{{ $row->ip }}</td>
                <td>{{ $row->request_count }}</td>
                <td>
                    <a onclick="if(!confirm('Are you sure want to unblock?')) return false" href="{{ action('BlockedRequestController@getDelete',['id'=>$row->id]) }}" class="btn btn-warning btn-sm">Un Block</a>
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