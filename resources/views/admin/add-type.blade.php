@extends('admin.layout.master')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="head head-on">
                    <h4>Add Type</h4>
                </div>
                <div class="add-event">
                    @include('partials.errors')
                    @include('partials.flash_messages')
                    <form action="{{ url('admin/add-type') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="field-wrap">
                            <div class="left">
                                <label for="event-name">Type</label>
                                <input type="text" id="english_type" name="english_type" value="{{old('english_type')}}" required="required">
                            </div>
                            <div class="right">
                                <label for="event-name-ar">اكتب</label>
                                <input type="text" id="arabic_type" name="arabic_type" value="{{old('arabic_type')}}" required="required">
                            </div>
                        </div>
                        <div class="field-wrap">
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