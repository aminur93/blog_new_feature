@extends('layouts.admin.master')

@section('page')
Post Create
@endsection

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div id="success_message"></div>

            <div id="error_message"></div>

            <div class="card">

                <div class="card-header">
                    <h4>Create Post</h4>
                </div>

                <div class="card-body">
                    <form  method="post" id="ajax_post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="">Category</label>
                            <select name="category_id" id="category_id" class="form-control selectric">
                                <option value="">Select Category</option>
                                @foreach($category as $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Tag</label>
                            <select name="tag_id" id="tag_id" class="form-control selectric">
                                <option value="">Select Tag</option>
                                @foreach($tag as $val)
                                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" id="title" name="title" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Body</label>
                            <textarea class="summernote" name="body" id="body"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="text" name="post_date" id="post_date" class="form-control datepicker">
                        </div>

                        <div class="form-group">
                            <label for="">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>

                        <div class="form-group">
                            <label for="">Status</label>
                            <input type="checkbox" name="status" id="status">
                        </div>

                        <div class="form-group">
                            <label for="">Approved</label>
                            <input type="checkbox" name="is_approved" id="is_approved">
                        </div>

                        <div class="form-group">
                            <a href="{{ route('post') }}" class="btn btn-warning">Cancel</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {

            $("#ajax_post").on("submit",function (e) {
                e.preventDefault();
                //var myData = $('#post').serializeArray();
                var formData = new FormData( $("#ajax_post").get(0));
                console.log(formData);
                $.ajax({
                    url : "{{ route('post.store') }}",
                    method : "post",
                    data :  formData,
                    dataType : "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success : function (data) {
                        console.log(data);
                        if(data.flash_message_success) {
                            $('#success_message').html(' <div class="alert alert-success alert-block">\n' +
                                '                <button type="button" class="close" data-dismiss="alert">x</button>\n' +
                                '               <strong>' + data.flash_message_success + '</strong>\n' +
                                '            </div>');
                        }else {

                            $('#error_message').html(' <div class="alert alert-danger alert-block">\n' +
                                '                <button type="button" class="close" data-dismiss="alert">x</button>\n' +
                                '               <strong>' + data.error + '</strong>\n' +
                                '            </div>');
                        }

                        $("form").trigger("reset");

                        $('.form-group').find('.valids').hide();
                    },

                    error : function (err) {
                        if (err.status == 422) {

                            $.each(err.responseJSON.errors, function (i, error) {
                                var el = $(document).find('[name="'+i+'"]');
                                el.after($('<span class="valids" style="color: red;">'+error+'</span>'));
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush