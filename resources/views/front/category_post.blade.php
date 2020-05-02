@extends('layouts.front.master')

@section('page')
Category Post
@endsection

@push('css')
@endpush

@section('content')
    <div class="span8">
    @foreach($categories as $cat_post)
        <!-- start article 1 -->
            <article class="blog-post">
                <div class="post-heading">
                    <h3><a href="#">{{ $cat_post->title }}</a></h3>
                </div>
                <div class="row">
                    <div class="span8">
                        <div class="post-image">
                            <a href="#"><img src="/assets/uploads/original_image/{{$cat_post->image }}" alt="" /></a>
                        </div>
                        <ul class="post-meta">
                            <li class="first"><i class="icon-calendar"></i><span><?= date('d F Y',strtotime($cat_post->post_date))?></span></li>
                            <li><i class="icon-comments"></i><span><a href="#">4 comments</a></span></li>
                            <li class="last"><i class="icon-tags"></i><span><a href="#">{{ $cat_post->tag_name }}</a></span></li>
                            <li class="last"><i class="icon-reorder"></i><span><a href="{{ route('post.category_post',$cat_post->id) }}">{{ $cat_post->cat_name }}</a></span></li>
                        </ul>
                        <div class="clearfix">
                        </div>
                        <p>
                            <?= substr(strip_tags($cat_post->body),0,200)?>
                        </p>
                        <a href="{{ route('single.post',$cat_post->slug) }}" class="btn btn-small btn-theme">Read more</a>
                    </div>
                </div>
            </article>
            <!-- end article 1 -->
        @endforeach

        <div class="pagination text-center">
            {{--{{ $cat_post->links() }}--}}
        </div>
    </div>

    <div class="span4">
        <aside>

            <div class="widget">
                <h4 class="rheading">Categories<span></span></h4>
                <ul class="cat">
                    @foreach($category as $value)
                        <li><a href="{{ route('post.category_post',$value->id) }}">{{ $value->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="widget">
                <h4 class="rheading">Recent posts<span></span></h4>
                <ul class="recent-posts">
                    @foreach($latest_post as $lp)
                        <li><a href="{{ route('single.post',$lp->slug) }}">{{ $lp->title }}</a>
                            <div class="clear">
                            </div>
                            <span class="date"><i class="icon-calendar"></i> <?= date('d F Y', strtotime($lp->post_date))?></span>
                            <span class="comment"><i class="icon-comment"></i> 4 Comments</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="widget">
                <h4 class="rheading">Post tags<span></span></h4>
                <ul class="tags">
                    @foreach($tags as $tag)
                        <li><a href="{{ route('post.tag_post',$tag->id) }}" class="btn">{{ $tag->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
@endsection

@push('js')
@endpush