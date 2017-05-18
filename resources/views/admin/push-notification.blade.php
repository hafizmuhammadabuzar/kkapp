@extends('admin.layout.master')

@section('content')

<div class="main">
    <div class="container">
        <div class="row">
            <div class="head head-on">
                <h4>Push Notification</h4>
            </div>
            <div class="table-responsive">
                @include('partials/flash_messages')
                @include('partials/errors')
                <form action="{{url('admin/push-notification')}}" method="post" class="push-form">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Title" name="title" value="{{old('title')}}" required="required">
                    </div>
                    <div class="form-group">
                        <textarea rows="6" class="form-control" placeholder="Message" name="message" value="{{old('message')}}" required="required"></textarea>
                    </div>
                    <div class="left">
                        <!--<h4>For all</h4>-->
                        <div class="radio-wrap">
                            <input id="chk_all" name="chk_all" type="radio" value="all" checked="checked">
                            <label>Send to all&nbsp;&nbsp;&nbsp;&nbsp; </label>
                            <input id="chk_all" name="chk_all" type="radio" value="filter">
                            <label>Filter&nbsp;&nbsp;&nbsp;&nbsp; </label>
                        </div>
                    </div>
                    <div class="filter-data" style="display: none;">
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <h4>Select Language</h4>
                                @foreach($languages as $lang)
                                <div class="radio-wrap">
                                    <input id="{{$lang->title}}" name="language[]" type="checkbox" value="{{$lang->id}}">
                                    <label for="{{$lang->title}}">{{$lang->title}}&nbsp;&nbsp;&nbsp;&nbsp; </label>
                                </div>
                                @endforeach
                            </div>
                            <div class="right left-right">
                                <h4>Select City</h4>
                                @foreach($cities as $city)
                                <div class="radio-wrap">
                                    <input id="{{$city->city_name}}" name="city[]" type="checkbox" value="{{$city->city_name}}">
                                    <label for="{{$city->city_name}}">{{$city->city_name}}&nbsp;&nbsp;&nbsp;&nbsp; </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="field-wrap clearfix">
                            <div class="left">
                                <h4>Select Category</h4>
                                @foreach($categories as $cat)
                                <div class="radio-wrap">
                                    <input id="{{$cat->english}}" name="category[]" type="checkbox" value="{{$cat->id}}">
                                    <label for="{{$cat->english}}">{{$cat->english}}&nbsp;&nbsp;&nbsp;&nbsp; </label>
                                </div>
                                @endforeach
                            </div>
                            <div class="right left-right">
                                <h4>Select Type</h4>
                                @foreach($types as $type)
                                <div class="radio-wrap">
                                    <input id="{{$type->english}}" name="type[]" type="checkbox" value="{{$type->id}}">
                                    <label for="{{$type->english}}">{{$type->english}}&nbsp;&nbsp;&nbsp;&nbsp; </label>
                                </div>
                                @endforeach
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

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        $("input[id='chk_all']").click(function(){
           if ($(this).val() == 'all') {
               $('.filter-data').hide();
           } 
           else{
               $('.filter-data').show();
           }
        });
        
    });
</script>
@endsection