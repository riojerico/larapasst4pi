@extends('layout')
@section('content')

    @php
        /**
        * @var \App\CBModels\Users $row
        * @var \App\CBModels\T4tParticipant $participant
        */
    @endphp

    <p>
        <a href="{{ action('ManageParticipantController@getIndex') }}"><i class="fa fa-arrow-left"></i> Back To List</a>
    </p>

    <form method="post" action="{{ action('ManageParticipantController@postEditSave',['id'=>$row->getId()]) }}">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="">Name</label>
            <input type="text" class="form-control" name="name" required value="{{ $row->getName() }}">
            {{--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>--}}
        </div>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Last Name</label>
            <input type="text" class="form-control" name="lastname" value="{{ $participant->getLastname() }}" required>
        </div>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Address</label>
            <input type="text" class="form-control" name="address" value="{{ $participant->getAddress() }}" required>
        </div>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Comment</label>
            <input type="text" class="form-control" name="comment" value="{{ $participant->getComment() }}" required>
        </div>

        <div class="form-group">
            <label for="" style="text-align: left;display: block">Photo</label>
            <p>
                @if($row->getPhoto())
                    <img src="{{ asset($row->getPhoto()) }}" width="100px" height="100px" alt="Photo">
                @endif
            </p>
            <input type="file" class="form-control" name="photo">
        </div>

        <div class="form-group">
            <label for="">Email</label>
            <input type="email" class="form-control" name="email" required value="{{ $row->getEmail() }}">
            {{--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>--}}
        </div>
        <div class="form-group">
            <label for="">Password</label>
            <input type="password" class="form-control" name="password"  placeholder="Please leave empty if do not change">
        </div>

        <button type="submit" class="btn btn-primary">Save Data</button>
    </form>


@endsection