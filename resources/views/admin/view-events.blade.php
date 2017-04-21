@extends('admin.layout.master')
@section('content')
	<div class="main">
		<div class="container">
			<div class="row">
				<div class="head head-on">
					<h4>Events</h4>
				</div>
                @include('partials.flash_messages')
				<div class="add-event-wrap clearfix">
					<a href="{{url('admin/add-event')}}" class="btn btn-primary col-xs-2 text-center">Add Event</a>
					<form action="{{url('admin/search-event')}}" method="post" class="search-form col-xs-5 no-pad push-xs-1">
						<input type="text" placeholder="Search" class="col-xs-9" name="search">
						<input type="submit" value="Search" class="btn btn-primary col-xs-2 offset-xs-1">
					</form>
					<div class="col-xs-3 no-pad push-xs-2">
						<select class="form-control">
							<option value="">Sort By:</option>
							<option value="paid">Paid</option>
							<option value="free">Free</option>
							<option value="date">Date/Time</option>
							<option value="name">Name</option>
							<option value="company">Comapny/Organizer</option>
							<option value="category">Category</option>
							<option value="city">City</option>
						</select>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead class="thead thead-inverse">
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Company</th>
							<th>Date/Time</th>
							<th>Email</th>
							<th>Phone</th>
							<th>View</th>
							<th>Edit</th>
							<th>Duplicate</th>
							<th>Del</th>
						</tr>
						</thead>
						<tbody>
						@foreach($events as $key => $event)
						<tr>
							<td>{{$key + 1}}</td>
							<td>{{$event->eng_name}}</td>
							<td>{{$event->eng_company_name}}</td>
<?php $all_day = ($event->all_day == 1)?'All Day':date('d-M-Y h:i A', strtotime($event->start_date));?>
							<td>{{$all_day}}</td>
							<td>{{$event->email}}</td>
							<td>{{$event->phone}}</td>
							<td><a href="{{url('admin/event-detail/'.Crypt::encrypt($event->id))}}"><i class="fa fa-eye"></i></a></td>
							<td><a href="{{url('admin/edit-event/'.Crypt::encrypt($event->id))}}"><i class="fa fa-edit"></i></a></td>
							<td><a href="{{url('admin/duplicate-event/'.Crypt::encrypt($event->id))}}"><i class="fa fa-copy"></i></a></td>
							<td><a href="{{url('admin/delete-event/'.Crypt::encrypt($event->id))}}"><i class="fa fa-remove" style="color: #880000"></i></a></td>
						</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		@if($uri_segment != 'search')
        {{ $events->links() }}
        @endif
	</div>
</div>
@endsection

@section('script')

<script type="text/javascript">
	$(document).ready(function(){

		if($('#uri').val() == 'event-detail'){
			$(":input").attr("disabled",true);
		}

		if($('#uri').val() == 'duplicate-event'){
			$("#all_day").attr('checked', false);
			$("#start_time").val('');
			$("#start_date").val('');
			$("#end_time").val('');
			$("#end_date").val('');
			$("#start_time").focus();
		}

		$('.pic-clone a').click(function(e){
			e.preventDefault();
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

		$('.attach-clone a').click(function(e){
			e.preventDefault();
			var attch_count = $('div[id^=attachments]').length + $('img[id^=attch_pic]').length;
			if(attch_count < 3){
				$("#attachments:last").clone().find("input:file").val("").end().appendTo(".attach-clone");
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
			initMap(24.4539, 54.3773);
		});
	});
</script>

@endsection