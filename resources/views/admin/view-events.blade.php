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
					<form action="#" class="search-form col-xs-5 no-pad push-xs-1">
						<input type="text" placeholder="Search" class="col-xs-9">
						<input type="submit" value="Search" class="btn btn-primary col-xs-2 offset-xs-1">
					</form>
					<div class="col-xs-3 no-pad push-xs-2">
						<select class="form-control">
							<option>Sort By:</option>
							<option>Paid/Free</option>
							<option>Date/Time</option>
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
        {{ $events->links() }}
	</div>
</div>
@endsection