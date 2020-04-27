@extends('layouts.admin.master')

@section('page')
Role Create
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
                    <h4>Create Role</h4>
                </div>

                <div class="card-body">
                    <form method="post" id="role">
                        @csrf

                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>

                        <strong>Permission:</strong>
                        <br/>

                        @foreach($permission->chunk(2) as $chunk)
                            <div class="row">
                                @foreach($chunk as $add)
                                    <div class="col-md-6">
                                        <label>
                                            <input type="checkbox" id="permission" name="permission[]" value="{{ $add->id }}"> {{ $add->name }}
                                        </label>
                                        <br/>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <div class="form-group">
                            <a href="{{ route('role') }}" class="btn btn-warning">Cancel</a>
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

            $("#role").on("submit",function (e) {
                e.preventDefault();
                var name = $("#name").val();

                var _token = $('input[name = "_token"]').val();

                var myData = $('#role').serializeArray();
                console.log(myData);
                $.ajax({
                    url : "{{ route('role.store') }}",
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