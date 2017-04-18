@extends('admin.layout.master')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="head head-on">
                    <h4>Edit Type</h4>
                </div>
                <div class="add-event">
                    @include('partials.errors')
                    @include('partials.flash_messages')
                    <form action="{{ url('admin/update-type') }}" method="post" enctype="multipart/form-data">
                        <div class="field-wrap">
                            <div class="left">
                                <label for="event-name">Type</label>
                                <input type="text" id="english_type" name="english_type" value="{{$type->english}}" required="required">
                            </div>
                            <div class="right">
                                <label for="event-name-ar">اكتب</label>
                                <input type="text" id="arabic_type" name="arabic_type" value="{{$type->arabic}}" required="required">
                            </div>
                        </div>
                        <div class="field-wrap">
                            <div class="right">
                                <input type="hidden" name="type_id" id="type_id" value="{{Crypt::encrypt($type->id)}}">
                                <input type="submit" value="Update" class="btn-submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection