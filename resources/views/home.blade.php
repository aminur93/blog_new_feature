@extends('layouts.front.master')

@section('page')
    Post
@stop

@push('css')
    @endpush

@section('content')
    <div class="span8">

        <!-- start article 1 -->
            <article class="blog-post">
                <div class="post-heading">
                    <h3><a href="#">{{ $posts->title }}</a></h3>
                </div>
                <div class="row">
                    <div class="span8">
                        <div class="post-image">
                            <a href="#"><img src="/assets/uploads/original_image/{{ $posts->image }}" alt="" /></a>
                        </div>
                        <ul class="post-meta">
                            <li class="first"><i class="icon-calendar"></i><span><?= date('d F Y',strtotime($posts->post_date))?></span></li>
                            <li><i class="icon-comments"></i><span><a href="#">4 comments</a></span></li>
                            <li class="last"><i class="icon-tags"></i><span><a href="#">{{ $posts->tag->name }}</a></span></li>
                            <li class="last"><i class="icon-reorder"></i><span><a href="#">{{ $posts->category->name }}</a></span></li>
                        </ul>
                        <div class="clearfix">
                        </div>
                        <p>
                            <?= strip_tags($posts->body) ?>
                        </p>
                    </div>
                </div>
            </article>
            <!-- end article 1 -->

        <h4>Comments</h4>
        <ul class="media-list">
            <li class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" src="/frontend/assets/img/small-avatar.png" alt="" />
                </a>
                <div class="media-body">
                    <h5 class="media-heading"><a href="#">John doe</a></h5>
                    <span>3 March, 2013</span>
                    <p>
                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
                    </p>
                    <a href="#" class="reply">Reply</a>
                    <div class="clearfix">
                    </div>
                    <!-- Nested media object -->
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" src="/frontend/assets/img/small-avatar.png" alt="" />
                        </a>
                        <div class="media-body">
                            <h5 class="media-heading"><a href="#">Tom slayer</a></h5>
                            <span>3 March, 2013</span>
                            <p>
                                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
                            </p>
                            <a href="#" class="reply">Reply</a>
                            <div class="clearfix">
                            </div>
                            <!-- Nested media object -->
                            <div class="media">
                                <a class="pull-left" href="#">
                                    <img class="media-object" src="/frontend/assets/img/small-avatar.png" alt="" />
                                </a>
                                <div class="media-body">
                                    <h5 class="media-heading"><a href="#">Erick doe</a></h5>
                                    <span>3 March, 2013</span>
                                    <p>
                                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
                                    </p>
                                    <a href="#" class="reply">Reply</a>
                                    <div class="clearfix">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Nested media object -->
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" src="/frontend/assets/img/small-avatar.png" alt="" />
                        </a>
                        <div class="media-body">
                            <h5 class="media-heading"><a href="#">Jimmy doe</a></h5>
                            <span>3 March, 2013</span>
                            <p>
                                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
                            </p>
                            <a href="#" class="reply">Reply</a>
                            <div class="clearfix">
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" src="/frontend/assets/img/small-avatar.png" alt="" />
                </a>
                <div class="media-body">
                    <h5 class="media-heading"><a href="#">Mike sullivan</a></h5>
                    <span>3 March, 2013</span>
                    <p>
                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
                    </p>
                    <a href="#" class="reply">Reply</a>
                    <div class="clearfix">
                    </div>
                </div>
            </li>
        </ul>
        <div class="comment-post">
            <h4>Leave a comment</h4>
            <form action="" method="post" class="comment-form" name="comment-form">
                <div class="row">
                    <div class="span4">
                        <label>Name <span>*</span></label>
                        <input type="text" class="input-block-level" placeholder="Your name" />
                    </div>
                    <div class="span4">
                        <label>Email <span>*</span></label>
                        <input type="text" class="input-block-level" placeholder="Your email" />
                    </div>
                    <div class="span4">
                        <label>URL</label>
                        <input type="text" class="input-block-level" placeholder="Your website url" />
                    </div>
                    <div class="span8">
                        <label>Comment <span>*</span></label>
                        <textarea rows="9" class="input-block-level" placeholder="Your comment"></textarea>
                        <button class="btn btn-theme" type="submit">Submit comment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="span4">
        <aside>

            <div class="widget">
                <h4 class="rheading">Categories<span></span></h4>
                <ul class="cat">
                    @foreach($category as $value)
                        <li><a href="#">{{ $value->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="widget">
                <h4 class="rheading">Recent posts<span></span></h4>
                <ul class="recent-posts">
                    @foreach($latest_post as $lp)
                        <li><a href="#">{{ $lp->title }}</a>
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
                        <li><a href="#" class="btn">{{ $tag->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
@endsection

@push('js')
    @endpush