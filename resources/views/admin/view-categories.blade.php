@extends('admin.layout.master')

@section('content')
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="head head-on">
                    <h4>Categories</h4>
                </div>
                <div class="table-responsive">
                    @include('partials.flash_messages')
                    <a href="{{url('admin/add-category')}}" class="add-btn">Add Category</a>
                    <table class="table table-striped">
                        <thead class="thead thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>English</th>
                            <th>Arabic</th>
                            <th>Non Selected Icon</th>
                            <th>Selected Icon</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody style="background-color:#c5c5c5;">
                        <?php foreach($categories as $key => $cat): ?>
                        <tr>
                            <td><?php echo $sr; ?></td>
                            <td><?php echo $cat->english; ?></td>
                            <td><?php echo $cat->arabic; ?></td>
                            <td><img src="{{url('public/admin/icons/'.$cat->non_selected_icon)}}" width="50" height="50" /></td>
                            <td><img src="{{url('public/admin/icons/'.$cat->selected_icon)}}" width="50" height="50" /></td>
                            <td><?php echo date('d-M-Y', strtotime($cat->created_at)); ?></td>
                            <td><a href="{{url('admin/edit-category/'.Crypt::encrypt($cat->id))}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  |
                                <a href="{{url('admin/delete-category/'.Crypt::encrypt($cat->id))}}"><i class="fa fa-trash-o" aria-hidden="true"></i><a/></td>
                        </tr>
                        <?php $sr++; 
                        endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{ $categories->links() }}
    </div>
@endsection