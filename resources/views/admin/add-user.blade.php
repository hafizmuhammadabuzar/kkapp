@extends('admin.layout.master')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="head head-on">
                    <h4>Add User</h4>
                </div>
                <div class="add-event">
                    @include('partials.errors')
                    @include('partials.flash_messages')
                    <form action="{{ url('admin/save-user') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <label for="event-name">Username</label>
                                <input type="text" id="username" name="username" value="{{old('username')}}" required="required">
                            </div>
                            <div class="right left-right">
                                <label for="event-name">Email</label>
                                <input type="text" id="email" name="email" value="{{old('email')}}" required="required">
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <label for="event-name-ar">Password</label>
                                <input type="text" id="password" name="password" value="{{old('password')}}" required="required">
                            </div>
                            <div class="right left-right">
                                <div class="left">
                                    <label for="dob">Date of Birth</label>
                                    <input type="text" id="dob" name="dob" value="{{old('dob')}}" class="start-input" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <label for="event-name-ar">Gender</label>
                                <div class="radio-wrap">
                                    <input type="radio" id="male" name="gender" value="Male" required="required">
                                    <label for="male">Male</label>
                                </div>
                                <div class="radio-wrap">
                                    <input type="radio" id="female" name="gender" value="Female" required="required">
                                    <label for="female">Female</label>
                                </div>
                            </div>
                            <div class="right left-right">
                                <label for="event-name-ar">Picture</label>
                                <input type="file" id="picture" name="picture" required="required">
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="right">
                                <input type="submit" value="Submit" class="btn-submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection