<li class="media">
    <a class="pull-left" href="#">
        <img class="media-object" src="/frontend/assets/img/small-avatar.png" alt="" />
    </a>
    <div class="media-body">
        <h5 class="media-heading"><a href="#">John doe</a></h5>
        <span>{!! \App\Helpers\Helper::date_convert($comments->created_at) !!}</span>
        <p>
            {{ $comments->description }}
        </p>
        <button class="btn btn-sm btn-success" id="replies" data-id="{{ $comments->id }}">Reply</button>
        <div class="clearfix">
        </div>
    </div>

    <div id="replies_from"></div>
</li>