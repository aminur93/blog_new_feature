@extends('layouts.admin.master')

@section('page')
Post Image Create DropZone
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
                    <h4>Create Post Image Create DropZone</h4>
                </div>

                <div class="card-body">
                    <form method="post" action="{{ route('post.image_upload',$post->id) }}" class="dropzone" id="dropzone" enctype="multipart/form-data">
                        @csrf
                    </form>
                    <br>
                    <a href="{{ route('post.dropzone',$post->id) }}" class="btn btn-warning">Back</a>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        Dropzone.options.dropzone =
            {
                maxFilesize: 12,
                renameFile: function(file) {
                    var dt = new Date();
                    var time = dt.getTime();
                    return time+file.name;
                },
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                timeout: 5000,
                removedfile: function(file)
                {
                    var _token = $('input[name = "_token"]').val();
                    var name = file.upload.filename;
                    $.ajax({
                        type: 'POST',
                        url: '{{ url("/post/image_delete") }}',
                        data: {filename: name, _token:_token},
                        success: function (data){
                            console.log("File has been successfully removed!!");
                        },
                        error: function(e) {
                            console.log(e);
                        }});
                    var fileRef;
                    return (fileRef = file.previewElement) != null ?
                        fileRef.parentNode.removeChild(file.previewElement) : void 0;
                },
                success: function(file, response)
                {
                    console.log(response);
                },
                error: function(file, response)
                {
                    return false;
                }
            };
    </script>
@endpush