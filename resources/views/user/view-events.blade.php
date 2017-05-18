@extends('user.layout.master')
@section('content')
<div class="main">
    <div class="container">
        <div class="row">
            <div class="head head-on">
                <h4>Events</h4>
            </div>
            @include('partials.flash_messages')
            <div class="add-event-wrap clearfix">
                <a href="{{url('user/add-event')}}" class="btn btn-primary col-xs-2 text-center">Add Event</a>
                <form action="{{url('user/search-events')}}" method="post" class="search-form col-xs-5 no-pad push-xs-1">
                    <input type="text" placeholder="Search" class="col-xs-9" name="search">
                    <input type="submit" value="Search" class="btn btn-primary col-xs-2 offset-xs-1">
                </form>
                <?php
                if (isset($search)) {
                    $search_sort = $search;
                    $action = 'user/search-events';
                } else {
                    $search_sort = '';
                    $action = 'user/view-events';
                }
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
                            <td>{{$sr}}</td>
                            <td>{{$event->reference_no}}</td>
                            <td>{!!$event->eng_name.'<br/>'.$event->ar_name!!}</td>
                            <td>
                                @foreach($categories[$key] as $cat_key => $cat)
                                @if($cat_key < 3)
                                {{$cat->english.','}}
                                @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($locations[$key] as $loc_key => $loc)
                                @if($loc_key < 3)
                                {{$loc->city.','}}
                                @endif
                                @endforeach
                            </td>
                            <td>{!!$event->eng_company_name.'<br/>'.$event->ar_company_name!!}</td>
                            <?php
                            $all_day = date('d-M-Y h:i A', strtotime($event->start_date));
                            $username = ($event->username == '') ? 'Admin' : $event->username;
                            $featured = ($event->is_featured == 1) ? 'Yes' : 'No';
                            $free = ($event->free_event == 1) ? 'Free' : 'Paid';
                            ?>
                            <td>{{$all_day}}</td>
                            <td>{{$username}}</td>
                            <td>{{$featured}}</td>
                            <td>{{$free}}</td>
                            <td>
                                <span class="{{'status-'.$key}}">
                                    {{$event->status.' |'}}
                                </span>
                                @if($event->status == 'Active')
                                <a href="javascript:" data-status="{{'Inactive-'.$key.'-'.Crypt::encrypt($event->id)}}" title="Inactive" class="e-status">
                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                </a>
                                @else
                                <a href="javascript:" data-status="{{'Active-'.$key.'-'.Crypt::encrypt($event->id)}}" title="Active" class="e-status">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </a>
                                @endif
                            </td>
                            <td>
                                <a href="{{url('user/event-detail/'.Crypt::encrypt($event->id))}}" title="view">
                                    <i class="fa fa-eye"></i>
                                </a> |
                                <a href="{{url('user/edit-event/'.Crypt::encrypt($event->id))}}" title="edit">
                                    <i class="fa fa-edit"></i>
                                </a> |
                                <a href="{{url('user/duplicate-event/'.Crypt::encrypt($event->id))}}" title="duplicate">
                                    <i class="fa fa-copy"></i>
                                </a> |
                                <a href="{{url('user/delete-event/'.Crypt::encrypt($event->id))}}" title="delete">
                                    <i class="fa fa-remove" style="color: #880000"></i>
                                </a>
                                
                            </td>
                        </tr>
                        <?php $sr++; ?>
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
    $(document).ready(function () {

        $('#sort').change(function () {
            if ($(this).val() != '') {
                $('#sorting-form').submit();
            }
        });

        $(document).on("click", ".e-status", function (e) {

            var status = $(this).data('status');
            status = status.split('-');
            var self = this;
            $.ajax({
                method: "POST",
                data: {status: status[0], id: status[2]},
                url: "<?php echo url('user/event-status'); ?>",
                success: function (response) {
                    if (response == 1) {
                        $('.status-' + status[1]).text(status[0]+' |');
                        alert('Successfully ' + status[0]);
                        $(self).removeAttr('href');
                        if (status[0] == 'Active') {
                            $(self).replaceWith('<a href="javascript:" data-status="Inactive-' + status[1] + '-' + status[2] + '" title="Inactive" class="e-status"><i class="fa fa-ban" aria-hidden="true"></i></a>');
                        } else {
                            $(self).replaceWith('<a href="javascript:" data-status="Active-' + status[1] + '-' + status[2] + '" title="Active" class="e-status"><i class="fa fa-check" aria-hidden="true"></i></a>');
                        }
                    }
                }
            });
        });
    });
</script>

@endsection