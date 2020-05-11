@extends('layouts.admin.master')

@section('page')
Create category
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
                <h4>Create Category</h4>
            </div>

            <div class="card-body">
                <form method="post" id="category">
                    @csrf

                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" id="category_name" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}">
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="">Slug</label>
                        <input type="text" id="category_slug" name="slug" class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}">
                        @if ($errors->has('slug'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('slug') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <a href="{{ route('category') }}" class="btn btn-warning">Cancel</a>
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

            $("#category").on("submit",function (e) {
                e.preventDefault();

                var name = $("#category_name").val();
                var slug = $("#category_slug").val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: '{{ route('category.store') }}',
                    method : 'post',
                    data: {name:name, slug:slug, _token:_token},
                    dataType: 'json',
                    error: function (err) {
                        if (err.status == 422) {

                            $.each(err.responseJSON.errors, function (i, error) {
                                var el = $(document).find('[name="'+i+'"]');
                                el.after($('<span class="valids" style="color: red;">'+error+'</span>'));
                            });
                        }
                    },

                    success: function (data) {

                        $('#data-table').DataTable().ajax.reload();

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
                    }
                });
            })
        })
    </script>
@endpush