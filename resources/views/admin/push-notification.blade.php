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
                    <div class="form-group clearfix">
                        <div class="select-wrap">
                            <label>Notification Language:</label>
                            <select name="language">
                                <option>Select Language</option>
                                <option value="english">English</option>
                                <option value="arabic" selected="selected">Arabic</option>
                            </select>
                        </div>
                        <div class="select-wrap">
                            <select multiple name="city[]">
                                <option>Select City</option>
                                @foreach($cities as $city):
                                <option value="{{$city->city_name}}">{{$city->city_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="select-wrap">
                            <select multiple name="type[]">
                                <option>Select Type</option>
                                @foreach($types as $type):
                                <option value="{{$type->id}}">{{$type->english.' '.$type->arabic}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="select-wrap">
                            <select multiple name="category[]">
                                <option>Select Category</option>
                                @foreach($categories as $cat):
                                <option value="{{$cat->id}}">{{$cat->english.' '.$cat->arabic}}</option>
                                @endforeach
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