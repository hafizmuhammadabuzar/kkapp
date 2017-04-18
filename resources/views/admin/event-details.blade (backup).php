@extends('admin.layout.master')

@section('content')

	<div class="main">
		<div class="container">
			<div class="row">
				<div class="head head-on">
					<h4>Add Event</h4>
				</div>
				<div class="add-event">
				@include('..partials/errors')
					@if($uri_segment == 'add-event')
					<form action="{{url('save-event')}}" method="post" enctype="multipart/form-data">
					@elseif($uri_segment == 'edit-event')
					<form action="{{url('update-event')}}" method="post" enctype="multipart/form-data">
					@endif
						<div class="field-wrap">
							<div class="left">
								<label for="event-name">Type</label>
								<?php $type_id = (isset($event)) ? $event->type_id : old('type'); ?>
								<select class="form-control" name="type">
									<option value=""> Select Type</option>
								@foreach($types as $tp)
									<option value="{{$tp->id}}" <?php if($type_id==$tp->id) echo 'selected="selected"'; ?>>{{$tp->english.' '.$tp->arabic}}</option>
								@endforeach
								</select>
							</div>
							<div class="left" style="margin-left: 10px;">
								<label for="event-name">Category</label>
								<?php $cat_id = (isset($event)) ? $event->category_id : old('category'); ?>
								<select class="form-control" name="category">
									<option value=""> Select Category</option>
								@foreach($categories as $cat)
									<option value="{{$cat->id}}" <?php if($cat_id==$cat->id) echo 'selected="selected"'; ?>>{{$cat->english.' '.$cat->arabic}}</option>
								@endforeach
								</select>
							</div>
						</div>
						<div class="field-wrap">
							<div class="left" style="margin-left: 10px;">
								<label for="event-name">Keyword</label>
								<?php $keyword = (isset($event)) ? $event->keyword : old('keyword'); ?>
								<input type="text" id="keyword" name="keyword" value="{{$keyword}}">
							</div>
						</div>
						<div class="field-wrap">
							<div class="left">
								<label for="event-name">Event Name</label>
								<?php $eng_name = (isset($event)) ? $event->eng_name : old('event_name'); ?>
								<input type="text" id="event-name" name="event_name" value="{{$eng_name}}">
							</div>
							<div class="right">
								<label for="event-name-ar">اسم الحدث</label>
								<?php $ar_name = (isset($event)) ? $event->ar_name : old('event_name_ar'); ?>
								<input type="text" id="event-name-ar" name="event_name_ar" value="{{$ar_name}}">
							</div>
						</div>

						@if($uri_segment != 'event-detail')
						<div class="field-wrap no-margin">
							<div class="left pic-clone">
								<label>Pictures (<i class="fa fa-plus-circle" aria-hidden="true"></i> Add more)</label>
								<div id="pictures">
								<input type="file" name="picture[]" id="picture" /><i class="fa fa-times pic-remove" aria-hidden="true"></i>
								</div>
							</div>
							<div class="right">
								<label>صور</label>
							</div>
						</div>
						@endif

						@if($uri_segment != 'add-event')
						<div class="field-wrap">
							<label>Pictures</label>
							<ul class="img-list">
							@foreach($event->pictures as $pic)
								<li><img src="{{URL::asset('public/uploads/'.$pic->picture)}}" alt="image" id="pic"></li>
							@endforeach
							</ul>
						</div>
						@endif
						<div class="field-wrap">
							<div class="left">
								<label for="event-company">Organizer/Company Name</label>
								<?php $comp_name_eng = (isset($event)) ? $event->eng_company_name : old('event_company'); ?>
								<input type="text" id="event-company" name="event_company" value="{{$comp_name_eng}}">
							</div>
							<div class="right">
								<label for="event-company-ar">اسم الشركة</label>
								<?php $comp_name_ar = (isset($event)) ? $event->ar_company_name : old('event_company_ar'); ?>
								<input type="text" id="event-company-ar" name="event_company_ar" value="{{$comp_name_ar}}">
							</div>
						</div>
						<div class="field-wrap">
							<div class="left">
								<div class="label-wrap">
									<label for="phone">Phone</label>
								<?php $phone = (isset($event)) ? $event->phone : old('phone'); ?>
									<label for="phone">هاتف</label>
								</div>
								<input type="text" id="phone" name="phone" value="{{$phone}}">
							</div>
							<div class="right">
								<div class="label-wrap">
									<label for="email">Email</label>
									<label for="email">البريد الإلكتروني</label>
								<?php $email = (isset($event)) ? $event->email : old('email'); ?>
								</div>
								<input type="text" id="email" name="email" value="{{$email}}">
							</div>
						</div>
						<div class="field-wrap">
							<div class="left">
								<div class="label-wrap">
									<label for="url">Web link</label>
									<label for="url">رابط موقع</label>
								<?php $url = (isset($event)) ? $event->weblink : old('url'); ?>
								</div>
								<input type="text" id="url" name="url" value="{{$url}}">
							</div>
							<div class="right cal event_dates">
								<div class="start-date">
									<span>Start Date</span>
									<?php $s_date = (isset($event)) ? $event->start_date : old('start_date');?>
									<input type="text" placeholder="date" name="start_date" value="{{date('d-m-Y', strtotime($s_date))}}" class="start-input">
									<input type="text" placeholder="time" name="start_time" value="{{date('H:i', strtotime($s_date))}}" class="timepicker">
								</div>
								<div class="end-date">
									<span>End Date</span>
									<?php $e_date = (isset($event)) ? $event->end_date : old('end_date');?>
									<input type="text" placeholder="date" name="end_date" value="{{date('d-m-Y', strtotime($e_date))}}" class="end-input">
									<input type="text" placeholder="time" name="end_time" value="{{date('H:i', strtotime($e_date))}}" class="timepicker">
								</div>
							</div>
						</div>
						<div class="field-wrap">
							<div class="left">
								<h4>Locations: &nbsp;
&nbsp;
&nbsp;
&nbsp;
  مواقع 
  @if($uri_segment != 'event-detail')
  (<a href="#add-location" class="add-location"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Location</a>)
@endif
  </h4>

<?php
if($uri_segment != 'event-detail'){
	$old_cities    = explode('~', old('city'));
	$old_locations = explode('~', old('location'));
	$old_event_languages = (!empty(old('event_language'))) ? old('event_language') : array();
}
else{
	$old_event_languages = explode(',', $event->event_language);
}
?>
							<ul class="location-list">

							@if(!isset($event))
								@for ($loc = 1; $loc < count($old_cities); $loc++)
									<li><a href="#" class="add-location">{{$old_locations[$loc].', '.$old_cities[$loc]}}<a/></li>
								@endfor
							@else
								@foreach($event->locations as $loc)
									<li><a href="#" class="add-location">{{$loc->location.', '.$loc->city}}<a/></li>
								@endforeach
							@endif
							</ul>
							</div>
							<div class="right left-right">
								<h4>All day event  &nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
    كل حدث اليوم</h4>
								<div class="radio-wrap">
									<?php $all_day = (isset($event)) ? $event->all_day : old('all_day');?>
									<input type="checkbox" id="all_day" name="all_day" value="1" <?php if($all_day == 1) echo 'checked="checked"' ?>><label for="yes">Yes   &nbsp;
&nbsp;
&nbsp;
  نعم </label>
								</div>

								<h4>Paid/Free event  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    دفع / الحدث مجانا</h4>
								<div class="radio-wrap">
									<?php $fee = (isset($event)) ? $event->fee : old('fee');?>
									<input type="radio" id="fee" name="fee" value="paid" <?php if($fee == 'paid') echo 'checked="checked"' ?>>
									<label for="paid">Paid   &nbsp;&nbsp;&nbsp;  دفع </label>
								</div>
								<div class="radio-wrap">
									<?php $fee = (isset($event)) ? $event->fee : old('fee');?>
									<input type="radio" id="fee" name="fee" value="free" <?php if($fee == 'free') echo 'checked="checked"' ?>>
									<label for="free">Free   &nbsp;&nbsp;&nbsp;  حر </label>
								</div>

								<h4>Language of Event  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    لغة الحدث</h4>
							    @foreach($languages as $lang)
								<div class="radio-wrap">
									<input type="checkbox" id="event_language" name="event_language[]" value="{{$lang->title}}" <?php if(in_array($lang->title, $old_event_languages)) echo 'checked="checked"' ?>>
									<label for="english">{{$lang->title}}<label>
								</div>
							    @endforeach
							</div>
						</div>
						<div class="field-wrap">
							<div class="left">
								<label for="event-desc">Description</label>
								<?php $en_dsc = (isset($event)) ? $event->eng_description : old('eng_description');?>
								<textarea rows="6" name="eng_description">{{$en_dsc}}</textarea>
							</div>
							<div class="right">
								<label for="event-desc-ar">وصف</label>
								<?php $ar_dsc = (isset($event)) ? $event->ar_description : old('ar_description');?>
								<textarea rows="6" name="ar_description">{{$ar_dsc}}</textarea>
							</div>
						</div>						
						<div class="field-wrap">
							<div class="left" style="margin-left: 10px;">
								<label for="event-name">Facebook URL</label>
								<input type="text" id="facebook" name="facebook">
							</div>
						</div>						
						<div class="field-wrap">
							<div class="left" style="margin-left: 10px;">
								<label for="event-name">Twitter URL</label>
								<input type="text" id="twitter" name="twitter">
							</div>
						</div>						
						<div class="field-wrap">
							<div class="left" style="margin-left: 10px;">
								<label for="event-name">Instagram URL</label>
								<input type="text" id="instagram" name="instagram">
							</div>
						</div>
						@if($uri_segment != 'event-detail')
						<div class="field-wrap no-margin">
							<div class="left attch-clone">
								<p>Attachments (<i class="fa fa-plus-circle" aria-hidden="true"></i> Add more)</p>
								<div id="attachments">
								<input type="file" name="attachment[]" id="attachment" /><i class="fa fa-times attch-remove" aria-hidden="true"></i>
								</div>
							</div>
							<div class="right">
								<p>المرفق</p>
							</div>
						</div>
						@endif
						
						@if($uri_segment != 'add-event')
						<div class="field-wrap">
							<p>Attachment</p>
							<ul class="img-list">
							@foreach($event->attachments as $attch)
								<li><img src="{{URL::asset('public/uploads/'.$attch->picture)}}" alt="image" id="attch_pic"></li>
							@endforeach
							</ul>
						</div>
						@endif
						<div class="field-wrap">
							<h4>Venue  &nbsp;
&nbsp;
&nbsp;
&nbsp;
&nbsp;
    مكان</h4>
							<?php $venue = (isset($event)) ? $event->venue : old('venue');?>
							<div class="radio-wrap">
								<input type="radio" id="venue" name="venue" value="Men" <?php if($venue == 'Men') echo 'checked="checked"' ?>><label for="men">Men   &nbsp;
&nbsp;
&nbsp;
  الذكر</label>
							</div>
							<div class="radio-wrap">
								<input type="radio" id="venue" name="venue" value="Women" <?php if($venue == 'Women') echo 'checked="checked"' ?>><label for="women">Women   &nbsp;
&nbsp;
&nbsp;
  إناثا</label>
							</div>
							<div class="radio-wrap">
								<input type="radio" id="venue" name="venue" value="Seperate" <?php if($venue == 'Seperate') echo 'checked="checked"' ?>><label for="separate">Separate Sitting   &nbsp;
&nbsp;
&nbsp;
  جلوس منفصلة</label>
							</div>
							<div class="radio-wrap">
								<input type="radio" id="venue" name="venue" value="Public" <?php if($venue == 'Public') echo 'checked="checked"' ?>><label for="public">Public   &nbsp;
&nbsp;
&nbsp;
  جلوس منفصلة</label>
							</div>
						</div>
						@if($uri_segment != 'event-detail')
						<input type="text" id="event_id" name="event_id" value="{{Crypt::encrypt($event->id)}}">
						<input type="text" id="city" name="city" value="{{old('city')}}">
						<input type="text" id="location" name="location" value="{{old('location')}}">
						<input type="text" id="latlng" name="latlng" value="{{old('latlng')}}">
						<div class="field-wrap">
							<div class="right">
								<input type="submit" value="Submit" class="btn-submit">
							</div>
						</div>
						@endif
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('script')

<script type="text/javascript">
	$(document).ready(function(){
		$('.pic-clone label').click(function(){
			var pic_count = $('div[id^=pictures]').length + $('img[id^=pic]').length; 
			if(pic_count < 4){
				$("#pictures:last").clone().find("input:file").val("").end().appendTo(".pic-clone");
			}
		});
		$(document).on("click", ".pic-remove", function (e) {
			if($('div[id^=pictures]').length > 1){
				$(this).closest('#pictures').remove();
			}
			else{
				$('#picture').val('');
			}
		});

		$('.attch-clone p').click(function(){
			var attch_count = $('div[id^=attachments]').length + $('img[id^=attch_pic]').length; 
			if(attch_count < 4){
				$("#attachments:last").clone().find("input:file").val("").end().appendTo(".attch-clone");
			}
		});
		$(document).on("click", ".attch-remove", function (e) {
			if($('div[id^=attachments]').length > 1){
				$(this).closest('#attachments').remove();
			}
			else{
				$('#attachment').val('');
			}
		});

		$('.add-location').click(function(){
			$('#event-latlngs').val('');
			initMap();
		});
		$('.form-location').submit(function(){
			$('#city').val($('#city').val()+'~'+$('#event-city').val());
			$('#location').val($('#location').val()+'~'+$('#event-location').val());
			$('#latlng').val($('#latlng').val()+'~'+$('#event-latlngs').val());
			$('.location-list').append('<li><a href="#" class="add-location">'+$('#event-location').val()+', '+$('#event-city').val()+'<a/></li>');
			$('.form-location')[0].reset();
			initMap();
		});
		$('#all_day').click(function(){
			if($(this).is(':checked')){
				$('.event_dates').hide();
			}else{
				$('.event_dates').show();
			}
		});
	});
</script>

@endsection