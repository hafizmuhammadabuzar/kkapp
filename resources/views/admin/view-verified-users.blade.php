@extends('admin.layout.master')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="head head-on">
                    <h4>Verified Users</h4>
                </div>
                <div class="table-responsive">
                    @include('partials.flash_messages')
                    <a href="{{url('admin/add-user')}}" class="add-btn">Add User</a>
                    <table class="table table-striped">
                        <thead class="thead thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>Picture</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
<?php foreach ($users as $key => $user):?>
                        <tr>
                            <td><?php echo $key+1;?></td>
                            <td><?php echo $user->username;?></td>
                            <td><?php echo $user->email;?></td>
                            <td><?php echo $user->gender;?></td>
                            <td><?php echo $user->age;?></td>
                            <td><img src="{{url('public/uploads/'.$user->image)}}" width="50" height="50" /></td>
                            <td><?php echo $user->status;?></td>
                            <td><?php echo date('d-M-Y', strtotime($user->created_at));?></td>
                            <td><a href="{{url('admin/edit-verified-user/'.Crypt::encrypt($user->id))}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            @if($user->status != 'Deleted')
                                | <a href="{{url('admin/delete-verified-user/'.Crypt::encrypt($user->id))}}"><i class="fa fa-trash-o" aria-hidden="true"></i><a/>
                            @endif
                            </td>
                        </tr>
<?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{ $users->links() }}
    </div>
@endsection