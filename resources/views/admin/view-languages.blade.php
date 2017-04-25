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
                    <a href="{{url('admin/add-language')}}" class="add-btn">Add Language</a>
                    <table class="table table-striped">
                        <thead class="thead thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>Language</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
<?php foreach ($languages as $key => $lang):?>
                        <tr>
                            <td><?php echo $key+1;?></td>
                            <td><?php echo $lang->title;?></td>
                            <td><?php echo date('d-M-Y', strtotime($lang->created_at));?></td>
                            <td><a href="{{url('admin/edit-language/'.Crypt::encrypt($lang->id))}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  |
                                <a href="{{url('admin/delete-language/'.Crypt::encrypt($lang->id))}}"><i class="fa fa-trash-o" aria-hidden="true"></i><a/></td>
                        </tr>
<?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{ $languages->links() }}
    </div>
@endsection