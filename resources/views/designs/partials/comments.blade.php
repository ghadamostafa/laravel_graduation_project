
<div class="panel-body row comments">
      @forelse ($comments as $comment)
      <div class="col-md-12">
        <div class="media g-mb-30 media-comment">
          <img class="d-flex g-width-50 g-height-50 rounded-circle g-mt-3 g-mr-15" src="{{asset('storage/'.$comment->user->image)}}" alt="Image Description">
          <div class="media-body u-shadow-v18 g-bg-secondary g-pa-30">
              <div class="g-mb-15">
                  <h5 class="h5 g-color-gray-dark-v1 mb-0">{{$comment->user->name}}</h5>
                  <span class="g-color-gray-dark-v4 g-font-size-12">{{ $comment->created_at }}</span>
              </div>
              <p>{{ $comment->body }}</p>
              <ul class="list-inline d-sm-flex my-0">
                  <li class="list-inline-item ml-2 ">
                    <p id="Commentreplies{{$comment->id}}">{{$comment->replies->count() }} Replies</p>
                  </li>
                  <li class="list-inline-item ml-auto">
                    <a class="u-link-v5 g-color-gray-dark-v4 g-color-primary--hover" href="javascript:void(0)" onclick=" displayReplyForm({{$comment->id}})">
                      <i class="fa fa-reply g-pos-rel g-top-1 g-mr-3"></i>
                          Reply
                    </a>
                  </li>
              </ul>

          </div>
        </div>

        <!-- comment reply -->
        <div style="margin:10px 0;margin-left: 65px;" class="ReplyForm displayForm" id="{{$comment->id}}">
          @foreach ($comment->replies as $reply)
            <div class="media g-mb-30 media-comment mb-2 replies">
                <img class="d-flex g-width-50 g-height-50 rounded-circle g-mt-3 g-mr-15" src="{{asset('storage/'.$reply->user->image)}}" alt="{{$reply->user->name}} Image">
                <div class="media-body u-shadow-v18 g-bg-secondary g-pa-30">
                    <div class="g-mb-15">
                      <h5 class="h5 g-color-gray-dark-v1 mb-0">{{$reply->user->name}}</h5>
                      <span class="g-color-gray-dark-v4 g-font-size-12">{{$reply->created_at }}</span>
                    </div>
                    <p>{{ $reply->body }}</p>
                </div>
            </div>
          @endforeach      
          <!-- Reply Form -->
          @auth
              <form  action="#" >
                  {{ csrf_field() }}
                  <input type="hidden" name="commentId" id="commentId" value="{{$comment->id}}">
                  <div style="display: inline;width: 100px;">
                      <input type="text" class="form-control reply_body" placeholder="write a reply ..." style="width: 440px;display: inline;">
                  </div>
                  <div class="send-icon" style="display: inline;">
                      <button onclick="commentReply()" type="button"> <i class="reply fa fa-paper-plane" aria-hidden="true" style="color: #f51167;"></i></button>
                  </div>
              </form>
          @endauth
        </div>   
      </div>
      @empty
        <div style="text-align: center;margin:10px auto;" class="NoComment">No Comments Yet!</div>
      @endforelse
</div>

<!-- add comment -->
@auth
<div class="panel-body row addComment">
	<div class="col-md-12">
		<div class="widget-area no-padding blank">
			<div class="status-upload">
				<form  action="#" id="addCommentForm">
					{{ csrf_field() }}
					<input type="hidden" name="designId" id="designId" value="{{$design->id}}">
					<textarea placeholder="Leave a comment.." id="comment_body"></textarea>
					<button type="submit" class="btn site-btn"><i class="fa fa-share"></i> ADD</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endauth


    
