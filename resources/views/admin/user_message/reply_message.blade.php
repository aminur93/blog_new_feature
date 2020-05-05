@extends('layouts.admin.master')

@section('page')
Reply Message
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>User Message Reply</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('send') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="">To</label>
                            <input type="text" value="{{ $message->email }}" readonly name="to_email" id="to_email" class="form-control" placeholder="To">
                        </div>

                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" value="{{ $message->name }}" readonly name="name" id="name" class="form-control" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label for="">From</label>
                            <input type="text" value="rashidkhan420123@gmail.com" readonly name="from_email" id="from_email" class="form-control" placeholder="To">
                        </div>

                        <div class="form-group">
                            <label for="">Subject</label>
                            <input type="text"  name="subject" id="subject" class="form-control" placeholder="Subject">
                        </div>

                        <div class="form-group">
                            <label for="">Message</label>
                            <textarea class="summernote" name="message" id="message"></textarea>
                        </div>

                        <a href="{{ route('message') }}" class="btn btn-warning">Back</a>
                        <button class="btn btn-success" type="submit">Send Email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush