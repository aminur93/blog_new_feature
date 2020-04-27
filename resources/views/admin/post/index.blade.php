@extends('layouts.admin.master')

@section('page')
Post
@endsection

@push('css')
@endpush

@section('content')
    @if(Session::has('flash_message_success'))
    <div class="alert alert-success alert-block">
       <button type="button" class="close" data-dismiss="alert">x</button>
       <strong>{{ Session::get('flash_message_success')  }}</strong>
    </div>
    @endif

    <div class="text-right">
        <a href="{{ route('post.create') }}" class="btn btn-primary" style="margin-bottom: 5px;"><i class="fa fa-plus"></i> Add Post</a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Post List</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="data-table">
                            <thead>
                            <tr>
                                <th>#Sl No</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Category</th>
                                <th>Tag</th>
                                <th>Author</th>
                                <th>Date</th>
                                <th>Total View</th>
                                <th>Status</th>
                                <th>Approve</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){
            $('#data-table').DataTable({
                //reset auto order
                processing: true,
                responsive: true,
                serverSide: true,
                pagingType: "full_numbers",
                dom: "<'row'<'col-sm-2'l><'col-sm-4'i><'col-sm-3 text-center'B><'col-sm-3'f>>tp",
                ajax: {
                    url: '{!!  route('post.getData') !!}',
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'image', name: 'image'},
                    {data: 'title', name: 'title'},
                    {data: 'slug', name: 'slug'},
                    {data: 'category_name', name: 'category_name'},
                    {data: 'tag_name', name: 'tag_name'},
                    {data: 'role_name', name: 'role_name'},
                    {data: 'post_date', name: 'post_date'},
                    {data: 'view_count', name: 'view_count'},
                    {data: 'status', name: 'status'},
                    {data: 'is_approved', name: 'is_approved'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn-sm btn-info',
                        exportOptions: {
                            columns: ':visible'
                        },
                        header: false
                    },
                    {
                        extend: 'csv',
                        className: 'btn-sm btn-success',
                        exportOptions: {
                            columns: ':visible'
                        },
                        header: false
                    },
                    {
                        extend: 'excel',
                        className: 'btn-sm btn-warning',
                        exportOptions: {
                            columns: ':visible'
                        },
                        header: false
                    },
                    {
                        extend: 'pdf',
                        className: 'btn-sm btn-primary',
                        exportOptions: {
                            columns: ':visible'
                        },
                        header: false
                    },
                    {
                        extend: 'print',
                        autoPrint: true,
                        className: 'btn-sm btn-default',
                        exportOptions: {
                            columns: ':visible'
                        },
                        header: false
                    }
                ]
            });
        });
    </script>

    <script>
        $(document).on('click','.deleteRecord', function(e){
            var id = $(this).attr('rel');
            var deleteFunction = $(this).attr('rel1');
            swal({
                    title: "Are You Sure?",
                    text: "You will not be able to recover this record again",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, Delete It"
                },
                function(){
                    window.location.href="/post/"+deleteFunction+"/"+id;
                });
        });
    </script>

    <script>
        $(document).on('click','.check_approve', function(e){
            var id = $(this).attr('rel');
            var checkFunction = $(this).attr('rel1');
            swal({
                    title: "Are You Sure?",
                    text: "You will not be Approve This Post",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, Approve It"
                },
                function(){
                    window.location.href="/post/"+checkFunction+"/"+id;
                });
        });
    </script>

    <script>
        $(document).on('click','.post_publish', function(e){
            var id = $(this).attr('rel');
            var publishFunction = $(this).attr('rel1');
            swal({
                    title: "Are You Sure?",
                    text: "You will not be Publish This Post",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, Publish It"
                },
                function(){
                    window.location.href="/post/"+publishFunction+"/"+id;
                });
        });
    </script>
@endpush