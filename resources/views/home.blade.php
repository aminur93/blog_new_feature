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
                        <p class="text-justify">
                            <?= strip_tags($posts->body) ?>
                        </p>
                    </div>
                </div>
            </article>
            <!-- end article 1 -->

        <h4>Comments</h4>
        <ul class="media-list" id="comment">
            <div id="reply_success_message"></div>
            <div id="reply_error_message"></div>

            @foreach($commentss as $comment)
                @include('front.comment_data', ['comments' => $comment])
            @endforeach
        </ul>

        <div class="comment-post">
            <div id="success_message"></div>
            <div id="error_message"></div>
            <h4>Leave a comment</h4>
            <form method="post" id="comment_form" class="comment-form" name="comment-form">
                @csrf

                <input type="hidden" value="{{ $posts->id }}" name="post_id" id="post_id">

                <input type="hidden" value="{{ $posts->slug }}" name="post_slug" id="post_slug">

                <div class="row">
                    <div class="span8">
                        <label>Comment <span>*</span></label>
                        <textarea rows="9" name="description" id="description" class="input-block-level" placeholder="Your comment"></textarea>
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
    <script>
        $(document).ready(function () {
            $(document).on("click","#replies",function () {

                var id = $(this).data('id');

                $.ajax({
                    url: "/replies_from/"+id,
                    type: "get",
                    data: {id:id},
                    success: function (response) {

                        if (response != 0) {
                            var data = JSON.parse(response);
                            $('#replies_from').toggle('slow');
                            if (data.code == 1) {
                                $('#replies_from').html(data.view);

                            } else if (data.code == 500) {

                            } else {
                                alert('Something Went Horribly Wrong!!');
                            }
                        }
                    }
                });
            })
        })
    </script>

    <script>
        $(document).ready(function () {

            $("#comment_form").on("submit",function (e) {
                e.preventDefault();

                var myDaya = $("#comment_form").serializeArray();

                $.ajax({
                    url: "{{ route('comments.store') }}",
                    type: "post",
                    data: $.param(myDaya),
                    dataType: "json",
                    success: function (data) {

                        $("#comment").append(data['comment']);

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
            })
        });
    </script>

    <script>
        $(document).ready(function () {

            $(document).on("submit","#comment-form",function (e) {
                e.preventDefault();

                var comment_id = $("#comment_id").val();

                var reply_description = $("#reply_description").val();

                var _token = $('input[name="_token"]').val();


                $.ajax({
                    url: "/comments_replyss/post-comments/"+comment_id,
                    type: "post",
                    data: {comment_id:comment_id, reply_description:reply_description,_token:_token},
                    dataType: "json",
                    success: function (data) {

                        //$("#comment").append(data['comment']);

                        if(data.flash_message_success) {
                            $('#reply_success_message').html(' <div class="alert alert-success alert-block">\n' +
                                '                <button type="button" class="close" data-dismiss="alert">x</button>\n' +
                                '               <strong>' + data.flash_message_success + '</strong>\n' +
                                '            </div>');
                        }else {

                            $('#reply_error_message').html(' <div class="alert alert-danger alert-block">\n' +
                                '                <button type="button" class="close" data-dismiss="alert">x</button>\n' +
                                '               <strong>' + data.error + '</strong>\n' +
                                '            </div>');
                        }

                        $("#replies_from").trigger("reset");

                        $("#replies_from").hide();

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