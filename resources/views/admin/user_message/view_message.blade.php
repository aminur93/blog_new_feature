@extends('layouts.admin.master')

@section('page')
View Message
@endsection

@push('css')
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>User Message View</h4>
            </div>

            <div class="card-body">
                <p>Name : {{ $message->name }}</p>
                <p>Email : {{ $message->email }}</p>
                <p>Subject : {{ $message->subject }}</p>
                <p>Description : {{ $message->message }}</p>

                <a href="{{ route('message') }}" class="btn btn-warning">Back</a>
                <a href="{{ route('reply',$message->id) }}" class="btn btn-success">Reply</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
@endpush