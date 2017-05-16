@extends('admin.layout.master')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="head head-on">
                    <h4>Types</h4>
                </div>
                <div class="table-responsive">
                    @include('partials.flash_messages')
                    <a href="{{url('admin/add-type')}}" class="add-btn">Add Type</a>
                    <table class="table table-striped">
                        <thead class="thead thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>English</th>
                            <th>Arabic</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
<?php foreach ($types as $key => $type):?>
                        <tr>
                            <td><?php echo $sr;?></td>
                            <td><?php echo $type->english;?></td>
                            <td><?php echo $type->arabic;?></td>
                            <td><?php echo date('d-M-Y', strtotime($type->created_at));?></td>
                            <td><a href="{{url('admin/edit-type/'.Crypt::encrypt($type->id))}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  |
                                <a href="{{url('admin/delete-type/'.Crypt::encrypt($type->id))}}"><i class="fa fa-trash-o" aria-hidden="true"></i><a/></td>
                        </tr>
<?php $sr++; 
endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{ $types->links() }}
    </div>
@endsection