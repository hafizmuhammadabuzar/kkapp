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
					<form action="{{url('admin/search-events')}}" method="post" class="search-form col-xs-5 no-pad push-xs-1">
						<input type="text" placeholder="Search" class="col-xs-9" name="search">
						<input type="submit" value="Search" class="btn btn-primary col-xs-2 offset-xs-1">
					</form>
					<?php 
					if(isset($search)){ $search_sort = $search; $action = 'admin/search-events'; } 
					else{ $search_sort = ''; $action = 'admin/view-events'; }
					?>
					<form id="sorting-form" action="{{url($action)}}" method="post">
					<div class="col-xs-3 no-pad push-xs-2">
					<input type="hidden" value="{{$search_sort}}" name="search">
						<select class="form-control" id="sort" name="sort">
							<option value="">Sort By:</option>
							<option value="paid">Paid</option>
							<option value="free">Free</option>
							<option value="date">Date/Time</option>
							<option value="name">Name</option>
							<option value="company">Company/Organizer</option>
						</select>
					</div>
					</form>
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
							<td>{{$event->reference_no}}</td>
							<td>{{$event->eng_name}}</td>
							<td>
								@foreach($categories[$key] as $cat)
								{{$cat->english.','}}
								@endforeach
							</td>
							<td>
								@foreach($locations[$key] as $loc)
								{{$loc->city.','}}
								@endforeach
							</td>
							<td>{{$event->eng_company_name}}</td>
<?php
$all_day  = ($event->all_day == 1)?'All Day':date('d-M-Y h:i A', strtotime($event->start_date));
$username = ($event->username == '')?'Admin':$event->username;
$featured = ($event->is_featured == 1)?'Yes':'No';
$free     = ($event->free_event == 1)?'Free':'Paid';

?>
<td>{{$all_day}}</td>
							<td>{{$username}}</td>
							<td>{{$featured}}</td>
							<td>{{$free}}</td>
							<td>{{$event->status}}</td>
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
								@if($event->status == 'Active')
								<a href="javascript:" data-status="{{'approve'.Crypt::encrypt($event->id)}}" title="delete" class="event-status">
									<i class="fa fa-ban" aria-hidden="true"></i>
								</a>
								@else
								<a href="{{url('admin/event-status/'.Crypt::encrypt($event->id))}}" title="delete">
									<i class="fa fa-check" aria-hidden="true"></i>
								</a>
								@endif
							</td>
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

		$('#sort').change(function(){
			if($(this).val() != ''){
				$('#sorting-form').submit();
			}
		});	

		$('.event-status').click(function(){
			alert('hee');

		});
	});
</script>

@endsection