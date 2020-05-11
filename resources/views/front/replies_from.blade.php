<div class="card card-body">
    <form method="post" id="comment-form" class="comment-form" name="comment-form">
        @csrf

        <input type="hidden" value="{{ $comment->id }}" name="comment_id" id="comment_id">

        <div class="row">
            <div class="span8">
                <label>Reply <span>*</span></label>
                <textarea rows="9" name="reply_description" id="reply_description" class="input-block-level" placeholder="Your Reply"></textarea>
                <button class="btn btn-theme" type="submit">Submit Replies</button>
            </div>
        </div>
    </form>
</div>
