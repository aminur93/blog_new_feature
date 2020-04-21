@extends('layouts.admin.master')

@section('page')
Category Edit
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
                    <h4>Edit Category</h4>
                </div>

                <div class="card-body">
                    <form method="post" id="Update_category">
                        @csrf

                        <input type="hidden" id="category_id" value="{{ $category->id }}">

                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" value="{{ $category->name }}" id="category_name" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="">Slug</label>
                            <input type="text" value="{{ $category->slug }}" id="category_slug" name="slug" class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}">
                            @if ($errors->has('slug'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('slug') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <a href="{{ route('category') }}" class="btn btn-warning">Cancel</a>
                            <button type="submit" class="btn btn-success">Edit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {

            $("#Update_category").on("submit",function (e) {
                e.preventDefault();

                var id = $("#category_id").val();
                console.log(id);
                var name = $("#category_name").val();
                var slug = $("#category_slug").val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: '/category/update/' + id,
                    method : 'post',
                    data: {id:id, name:name, slug:slug, _token:_token},
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
                        $("form").trigger("reload");
                        $('.form-group').find('.valids').hide();
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
                    }
                });
            })
        })
    </script>
@endpush