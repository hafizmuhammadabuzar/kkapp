@extends('admin.layout.master')

@section('content')

<div class="main">
    <div class="container">
        <div class="row">
            <div class="head head-on">
                <h4>Push Notification</h4>
            </div>
            <div class="table-responsive">
                <form action="#" method="post" class="push-form">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Title">
                    </div>
                    <div class="form-group">
                        <textarea rows="6" class="form-control" placeholder="Body"></textarea>
                    </div>
                    <div class="form-group clearfix">
                        <div class="select-wrap">
                            <select>
                                <option>Select Language</option>
                                <option>English</option>
                                <option>Arabic</option>
                            </select>
                        </div>
                        <div class="select-wrap">
                            <select multiple>
                                <option>Select City</option>
                                <option>Dubai</option>
                                <option>Abu Dhabi</option>
                                <option>Sharjah</option>
                            </select>
                        </div>
                        <div class="select-wrap">
                            <select multiple>
                                <option>Select Category</option>
                                <option>category1</option>
                                <option>category2</option>
                            </select>
                        </div>
                        <div class="select-wrap">
                            <select multiple>
                                <option>Select Intl Type</option>
                                <option>type1</option>
                                <option>type2</option>
                            </select>
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