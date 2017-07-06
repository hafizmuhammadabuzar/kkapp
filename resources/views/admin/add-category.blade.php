@extends('admin.layout.master')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="head head-on">
                    <h4>Add Category</h4>
                </div>
                <div class="add-event">
                    @include('partials.errors')
                    @include('partials.flash_messages')
                    <form action="{{ url('admin/add-category') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="field-wrap">
                            <div class="left">
                                <label for="event-name">Category</label>
                                <input type="text" id="english_category" name="english_category" value="{{old('english_category')}}" required="required">
                            </div>
                            <div class="right">
                                <label for="event-name-ar">الفئة</label>
                                <input type="text" id="arabic_category" name="arabic_category" value="{{old('arabic_category')}}" required="required">
                            </div>
                        </div>
                        {{-- <div class="field-wrap">
                            <div class="left">
                                <label for="event-name">Color</label>
                                <input type="text" id="color" name="color" value="{{old('color')}}" required="required">
                            </div>
                        </div> --}}
                        <div class="field-wrap">
                            <div class="left">
                                <label for="event-name-ar">Selected Icon</label>
                                <input type="file" id="selected_icon" name="selected_icon" required="required">
                            </div>
                        </div>
                        <div class="field-wrap">
                            <div class="left">
                                <label for="event-name-ar">Non Selected Icon</label>
                                <input type="file" id="non_selected_icon" name="non_selected_icon" required="required">
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
