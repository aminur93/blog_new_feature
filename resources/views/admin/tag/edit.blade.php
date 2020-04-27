@extends('layouts.admin.master')

@section('page')
Tag Edit
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
                    <h4>Edit Tag</h4>
                </div>

                <div class="card-body">
                    <form method="post" id="update_tag">
                        @csrf

                        <input type="hidden" id="tag_id" value="{{ $tag->id }}">

                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" value="{{ $tag->name }}" id="name" name="name" class="form-control">
                        </div>

                        <div class="form-group">
                            <a href="{{ route('tag') }}" class="btn btn-warning">Cancel</a>
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

            $("#update_tag").on("submit",function (e) {
                e.preventDefault();

                var id = $("#tag_id").val();
                var name = $("#name").val();
                var _token = $('input[name = "_token"]').val();

                $.ajax({
                    url : "/tag/update/"+id,
                    method : "post",
                    data : {name:name,id:id, _token:_token},
                    dataType : "json",
                    success : function (data) {
                        if(data) {
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