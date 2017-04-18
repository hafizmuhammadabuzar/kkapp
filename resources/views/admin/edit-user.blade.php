@extends('admin.layout.master')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="head head-on">
                    <h4>Edit User</h4>
                </div>
                <div class="add-event">
                    @include('partials.errors')
                    @include('partials.flash_messages')
                    <form action="{{ url('admin/update-user') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <label for="event-name">Email</label>
                                <input type="text" id="email" name="email" value="{{$user->email}}" required="required">
                            </div>
                            <div class="right left-right">
                                <label for="event-name">Username</label>
                                <input type="text" id="username" name="username" value="{{$user->username}}" required="required">
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <label for="event-name">Date of Birth</label>
                                <input type="text" id="dob" name="dob" value="{{$user->dob}}" required="required">
                            </div>
                            <div class="right left-right">
                                <h4>Gender</h4>
                                <div class="radio-wrap">
                                    <input type="radio" id="male" name="gender" value="Male" <?php if ($user->gender == 'Male') {echo 'checked="checked"';}
?> required="required">
                                    <label for="male">Male</label>
                                </div>
                                <div class="radio-wrap">
                                    <input type="radio" id="female" name="gender" value="Female" <?php if ($user->gender == 'Female') {echo 'checked="checked"';} ?> required="required">
                                <label for="female">Female</label>
                            </div>
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <label for="event-name-ar">Picture</label>
                                <input type="file" id="picture" name="picture">
                                <img src="{{url('public/uploads/'.$user->image)}}" width="50" height="50"/>
                            </div>
                            <div class="right left-right">
                                <h4>User Verified</h4>
                                <div class="radio-wrap">
                                    <input type="radio" id="yes" name="user" value="yes" required="required">
                                    <label for="yes">Yes</label>
                                </div>
                                <div class="radio-wrap">
                                    <input type="radio" id="no" name="user" value="no" required="required">
                                    <label for="no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="right">
                                <input type="hidden" name="user_id" id="user_id" value="{{Crypt::encrypt($user->id)}}">
                                <input type="hidden" name="old_picture" id="old_picture" value="{{$user->image}}">
                                <input type="submit" value="Submit" class="btn-submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection