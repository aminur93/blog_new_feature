@extends('layouts.front.master')

@section('page')
All Post
@endsection

@push('css')
@endpush

@section('content')
    <div class="row">
        @foreach($posts as $post)
            <div class="span3 features e_pulse">
                <img src="/assets/uploads/original_image/{{ $post->image }}" style="height: 100px;width: 400px;" alt="" />
                <div class="box">
                    <div class="divcenter">
                        <a href="{{ route('single.post',$post->slug) }}"><i class="icon-circled icon-48 icon-eye-open active icon"></i></a>
                        <h4>{!! Str::limit($post->title, 25, ' ...') !!}</h4>
                    </div>
                </div>
                <br>
            </div>

        @endforeach

    </div>


    <div class="pagination text-center">
        {{ $posts->links() }}
    </div>

@endsection

@push('js')
@endpush