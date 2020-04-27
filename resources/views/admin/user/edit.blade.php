@extends('layouts.admin.master')

@section('page')
Users Edit
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
                    <h4>Edit User</h4>
                </div>

                <div class="card-body">
                    <form method="post" id="Update_user">
                        @csrf

                        <input type="hidden" id="user_id" value="{{ $user->id }}">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" value="{{ $user->name }}" id="name" name="name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" value="{{ $user->email }}" id="email" name="email" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">Select Role</option>
                                @foreach($role as $value)
                                    <option value="{{ $value->id }}" @if($user->role_id == $value->id) selected @endif>{{ $value->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <a href="{{ route('user') }}" class="btn btn-warning">Cancel</a>
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

            $("#Update_user").on("submit",function (e) {
                e.preventDefault();

                var id = $("#user_id").val();
                var myData = $('#Update_user').serializeArray();
                $.ajax({
                    url : "/user/update/"+id,
                    method : "post",
                    data :  $.param(myData),
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