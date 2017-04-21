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
							<option>Sr#</option>
							<option>Ref#</option>
							<option>Name</option>
							<option>Category</option>
							<option>City</option>
							<option>Company / Organizer</option>
							<option>Date / Time</option>
							<option>Username</option>
							<option>Featured</option>
							<option>Free / Paid</option>
							<option>Status</option>
						</select>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead class="thead thead-inverse">
						<tr>
							<th>Sr#</th>
							<th>Ref#</th>
							<th>Name</th>
							<th>Category</th>
							<th>City</th>
							<th>Company / Organizer</th>
							<th>Date / Time</th>
							<th>Username</th>
							<th>Featured</th>
							<th>Free / Paid</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
						@foreach($events as $key => $event)
						<tr>
							<td>{{$key + 1}}</td>
							<td>{{$key + 1}}</td>
							<td>{{$event->eng_name}}</td>
							<td>Category</td>
							<td>City</td>
							<td>{{$event->eng_company_name}}</td>
<?php $all_day = ($event->all_day == 1)?'All Day':date('d-M-Y h:i A', strtotime($event->start_date));?>
							<td>{{$all_day}}</td>
							<td>Username</td>
							<td>Yes / No</td>
							<td>Free</td>
							<td>Status</td>
							<td>
								<a href="{{url('admin/event-detail/'.Crypt::encrypt($event->id))}}" title="view">
									<i class="fa fa-eye"></i>
								</a> |
								<a href="{{url('admin/edit-event/'.Crypt::encrypt($event->id))}}" title="edit">
									<i class="fa fa-edit"></i>
								</a> |
								<a href="{{url('admin/duplicate-event/'.Crypt::encrypt($event->id))}}" title="duplicate">
									<i class="fa fa-copy"></i>
								</a> |
								<a href="{{url('admin/delete-event/'.Crypt::encrypt($event->id))}}" title="delete">
									<i class="fa fa-remove" style="color: #880000"></i>
								</a>
							</td>
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