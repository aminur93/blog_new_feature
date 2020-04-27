@extends('layouts.admin.master')

@section('page')
Post Edit
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
                    <h4>Edit Post</h4>
                </div>

                <div class="card-body">
                    <form  method="post" id="ajax_post_update" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}">

                        <div class="form-group">
                            <label for="">Category</label>
                            <select name="category_id" id="category_id" class="form-control selectric">
                                <option value="">Select Category</option>
                                @foreach($category as $value)
                                    <option value="{{ $value->id }}" @if($post->category_id == $value->id) selected @endif>{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Tag</label>
                            <select name="tag_id" id="tag_id" class="form-control selectric">
                                <option value="">Select Tag</option>
                                @foreach($tag as $val)
                                    <option value="{{ $val->id }}" @if($post->tag_id == $val->id) selected @endif>{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" value="{{ $post->title }}" id="title" name="title" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Slug</label>
                            <input type="text" value="{{ $post->slug }}" id="slug" name="slug" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Body</label>
                            <textarea class="summernote" name="body" id="body">{{ $post->body }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="text" value="{{ $post->post_date }}" name="post_date" id="post_date" class="form-control datepicker">
                        </div>

                        <div class="form-group">
                            <label for="">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <input type="hidden" name="current_image" id="current_image" value="{{ $post->image }}">
                            <br>

                            <img src="{{ asset('assets/uploads/thumbnail/'.$post->image) }}" alt="" width="40">
                        </div>

                        <div class="form-group">
                            <label for="">Status</label>
                            <input type="checkbox" name="status" id="status" @if($post->status == 1) checked @endif>
                        </div>

                        <div class="form-group">
                            <label for="">Approved</label>
                            <input type="checkbox" name="is_approved" id="is_approved" @if ($post->is_approved == 1)
                                checked
                            @endif>
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

            $("#ajax_post_update").on("submit",function (e) {
                e.preventDefault();
                //var myData = $('#post').serializeArray();
                var id = $("#post_id").val();
                var formData = new FormData( $("#ajax_post_update").get(0));
                $.ajax({
                    url : "/post/update/"+id,
                    method : "post",
                    data :  formData,
                    dataType : "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success : function (data) {
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