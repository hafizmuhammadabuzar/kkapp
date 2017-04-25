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
                    <form action="{{ url('admin/update-language') }}" method="post" enctype="multipart/form-data">
                        <div class="field-wrap">
                            <div class="left">
                                <label for="event-name">Language</label>
                                <input type="text" id="language" name="language" value="{{$language->title}}" required="required">
                            </div>
                        </div>
                        <div class="field-wrap">
                            <div class="right">
                                <input type="hidden" name="language_id" id="language_id" value="{{Crypt::encrypt($language->id)}}">
                                <input type="submit" value="Update" class="btn-submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection