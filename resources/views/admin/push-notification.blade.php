@extends('admin.layout.master')

@section('content')

<div class="main">
    <div class="container">
        <div class="row">
            <div class="head head-on">
                <h4>Push Notification</h4>
            </div>
            <div class="table-responsive">
                <form action="{{url('admin/push-notification')}}" method="post" class="push-form">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Title" name="title">
                    </div>
                    <div class="form-group">
                        <textarea rows="6" class="form-control" placeholder="Body" name="message"></textarea>
                    </div>
                    <div class="field-wrap clearfix">
                        <div class="left">
                            <h4>Select Language</h4>
                            <div class="radio-wrap">
                                <input id="english" name="lang" type="radio">
                                <label for="english">English&nbsp;&nbsp;&nbsp;&nbsp; </label>
                            </div>
                            <div class="radio-wrap">
                                <input id="arabic" name="lang" type="radio">
                                <label for="arabic">Arabic&nbsp;&nbsp;&nbsp;&nbsp; </label>
                            </div>
                        </div>
                        <div class="right left-right">
                            <h4>Select City</h4>
                            <div class="radio-wrap">
                                <input id="abu-dhabi" name="abu-dhabi" type="checkbox">
                                <label for="abu-dhabi">Abu Dhabi&nbsp;&nbsp;&nbsp;&nbsp; </label>
                            </div>
                            <div class="radio-wrap">
                                <input id="dubai" name="dubai" type="checkbox">
                                <label for="dubai">Dubai&nbsp;&nbsp;&nbsp;&nbsp; </label>
                            </div>
                            <div class="radio-wrap">
                                <input id="sharjah" name="sharjah" type="checkbox">
                                <label for="sharjah">Sharjah&nbsp;&nbsp;&nbsp;&nbsp; </label>
                            </div>
                        </div>
                    </div>
                    <div class="field-wrap clearfix">
                        <div class="left">
                            <h4>Select Category</h4>
                            <div class="radio-wrap">
                                <input id="cat1" name="english" type="checkbox">
                                <label for="cat1">Category1&nbsp;&nbsp;&nbsp;&nbsp; </label>
                            </div>
                            <div class="radio-wrap">
                                <input id="cat2" name="arabic" type="checkbox">
                                <label for="cat2">Category2&nbsp;&nbsp;&nbsp;&nbsp; </label>
                            </div>
                        </div>
                        <div class="right left-right">
                            <h4>Select Intl Type</h4>
                            <div class="radio-wrap">
                                <input id="type1" name="abu-dhabi" type="checkbox">
                                <label for="type1">Type1&nbsp;&nbsp;&nbsp;&nbsp; </label>
                            </div>
                            <div class="radio-wrap">
                                <input id="type2" name="dubai" type="checkbox">
                                <label for="type2">Type2&nbsp;&nbsp;&nbsp;&nbsp; </label>
                            </div>
                            <div class="radio-wrap">
                                <input id="type3" name="sharjah" type="checkbox">
                                <label for="type3">Type3&nbsp;&nbsp;&nbsp;&nbsp; </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection