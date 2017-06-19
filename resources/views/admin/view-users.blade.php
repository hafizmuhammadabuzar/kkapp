@extends('admin.layout.master')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="head head-on">
                    <h4>Users</h4>
                </div>
                <div class="add-event-wrap clearfix">
                    <form action="{{url('admin/search-users')}}" method="post" class="search-form col-xs-5 no-pad push-xs-1">
                        <input type="text" placeholder="Search" class="col-xs-9" name="search">
                        <input type="submit" value="Search" class="btn btn-primary col-xs-2 offset-xs-1">
                    </form>
                </div>
                <div class="table-responsive">
                    @include('partials.flash_messages')
                    {{-- <a href="{{url('admin/add-user')}}" class="add-btn">Add User</a> --}}
                    <table class="table table-striped">
                        <thead class="thead thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Picture</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
<?php foreach ($users as $key => $user):?>
                        <tr>
                            <td><?php echo $sr; ?></td>
                            <td><?php echo $user->username;?></td>
                            <td><?php echo $user->email;?></td>
                            <td><img src="{{url('public/uploads/'.$user->image)}}" width="50" height="50" /></td>
                            <td id="user-status-<?php echo $key;?>"><?php echo $user->status;
?></td>
                            <td><?php echo date('d-M-Y', strtotime($user->created_at));?></td>
                            <td>
                                    <a href="{{url('admin/edit-user/'.Crypt::encrypt($user->id))}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            @if($user->status != 'Deleted')
                            | <a href="javascript:" data-status="{{'Delete-'.$key.'-'.Crypt::encrypt($user->id)}}" title="Delete" class="e-status">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                            @endif
                            @if($user->status != 'Active')
                            | <a href="javascript:" data-status="{{'Active-'.$key.'-'.Crypt::encrypt($user->id)}}" title="Active" class="e-status">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </a>
                            @endif
                                </td>
                        </tr>
<?php $sr++;
endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if($uri_segment != 'search')
        {{ $users->links() }}
        @endif
    </div>
@endsection

@section('script')

<script type="text/javascript">
    $(document).ready(function () {

        $(document).on("click", ".e-status", function () {

            var status = $(this).data('status');
            status = status.split('-');
            var self = this;
            $.ajax({
                method: "POST",
                data: { status: status[0], id: status[2] },
                url: "<?php echo url('admin/user-status');?>",
                success: function (response) {
                    if (response == 1) {
                        alert(status[0] + ' Successful');
                        $(self).removeAttr('href');
                        if (status[0] == 'Active') {
                            $('#user-status-'+status[1]).html('Active');
                            $(self).replaceWith('<a href="javascript:" data-status="Delete-' + status[1] + '-' + status[2] + '" title="Delete" class="e-status"><i class="fa fa-trash-o" aria-hidden="true"></i></a>');
                        } else {
                            $('#user-status-'+status[1]).html('Deleted');
                            $(self).replaceWith('<a href="javascript:" data-status="Active-' + status[1] + '-' + status[2] + '" title="Active" class="e-status"><i class="fa fa-check" aria-hidden="true"></i></a>');
                        }
                    }
                }
            });
        });
    });
</script>

@endsection