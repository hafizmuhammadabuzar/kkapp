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
                    <form action="{{ url('admin/add-language') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="field-wrap">
                            <div class="left">
                                <label for="event-name">Language</label>
                                <input type="text" id="language" name="language" value="{{old('title')}}" required="required">
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