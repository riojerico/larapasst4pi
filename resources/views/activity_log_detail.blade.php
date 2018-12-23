@extends('layout')
@section('content')

    @php /** @var \App\CBModels\ApiLogs $row */ @endphp
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th width="30%">Created At</th><td>:</td><td>{{ $row->getCreatedAt() }}</td>
        </tr>
        <tr>
            <th>Response Code</th><td>:</td><td>{{ $row->getResponseCode() }}</td>
        </tr>
        <tr>
            <th>URL</th><td>:</td><td>{{ $row->getUrl() }}</td>
        </tr>
        <tr>
            <th>Name</th><td>:</td><td>{{ $row->getName() }}</td>
        </tr>
        <tr>
            <th>Description</th><td>:</td><td>{{ $row->getDescription() }}</td>
        </tr>
        <tr>
            <th>User Agent</th><td>:</td><td>{{ $row->getUseragent() }}</td>
        </tr>
        <tr>
            <th>IP Address</th><td>:</td><td>{{ $row->getIp() }}</td>
        </tr>
        <tr>
            <th>Request Data</th><td>:</td><td><pre>{!! print_r(json_decode($row->getRequestData(),true),true) !!}</pre></td>
        </tr>
        <tr>
            <th>Response Data</th><td>:</td><td><pre>{!!  $row->getResponseData() !!} </pre></td>
        </tr>
        <tr>
            <th>Old Data</th><td>:</td><td><pre>{!! print_r(json_decode($row->getOldData(),true),true) !!}</pre></td>
        </tr>
        <tr>
            <th>New Data</th><td>:</td><td><pre>{!! print_r(json_decode($row->getNewData(),true),true) !!}</pre></td>
        </tr>
        </tbody>
    </table>

@endsection