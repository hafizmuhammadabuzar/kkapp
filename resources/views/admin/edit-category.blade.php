@extends('admin.layout.master')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="head head-on">
                    <h4>Edit Category</h4>
                </div>
                <div class="add-event">
                    @include('partials.errors')
                    @include('partials.flash_messages')
                    <form action="{{ url('admin/update-category') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="field-wrap">
                            <div class="left">
                                <label for="event-name">Category</label>
                                <input type="text" id="english_category" name="english_category" value="{{$category->english}}" required="required">
                            </div>
                            <div class="right">
                                <label for="event-name-ar">الفئة</label>
                                <input type="text" id="arabic_category" name="arabic_category" value="{{$category->arabic}}" required="required">
                            </div>
                        </div>
                        <div class="field-wrap">
                            <div class="left">
                                <label for="event-name-ar">Selected Icon</label>
                                <input type="file" id="selected_icon" name="selected_icon">
                                <img src="{{url('public/admin/icons/'.$category->selected_icon)}}" width="50" height="50"/>
                            </div>
                        </div>
                        <div class="field-wrap">
                            <div class="left">
                                <label for="event-name-ar">Non Selected Icon</label>
                                <input type="file" id="non_selected_icon" name="non_selected_icon">
                                <img src="{{url('public/admin/icons/'.$category->non_selected_icon)}}" width="50" height="50"/>
                            </div>
                        </div>
                        <div class="field-wrap">
                            <div class="right">
                                <input type="hidden" name="cat_id" id="cat_id" value="{{Crypt::encrypt($category->id)}}">
                                <input type="hidden" name="old_icon" id="old_icon" value="{{$category->selected_icon}}">
                                <input type="hidden" name="old_icon_non" id="old_icon_non" value="{{$category->non_selected_icon}}">
                                <input type="submit" value="Update" class="btn-submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection