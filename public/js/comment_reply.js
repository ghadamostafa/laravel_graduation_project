        let formId='';
		function displayReplyForm(id){
			formId=id;
            $('#'+id).toggleClass('displayForm');
		}
		function commentReply()
		{
			let comment_id = $('#'+formId).children('form').children( 'input[name=commentId]')[0].value;
			let Reply_body=$('#'+formId).children('form').children("div").children( 'input[type=text]')[0].value;
			   	$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					type: 'POST',
					url: 'http://localhost:8000/comments/'+comment_id+'/commentReply',
					data: {
					    'Reply_body':Reply_body
					},
					success: function (ReplyData) {
						let form=$('#'+formId).children('form')[0];
						let reply=`<div class="media g-mb-30 media-comment mb-2 replies">
				            <img class="d-flex g-width-50 g-height-50 rounded-circle g-mt-3 g-mr-15" src="/storage/${ReplyData.user.image}" alt="Image Description">
				            <div class="media-body u-shadow-v18 g-bg-secondary g-pa-30">
				              <div class="g-mb-15">
				                <h5 class="h5 g-color-gray-dark-v1 mb-0">${ReplyData.user.name}</h5>
				                <span class="g-color-gray-dark-v4 g-font-size-12">${ReplyData.reply.created_at}</span>
				              </div>
				              <p>${ReplyData.reply.body}</p>
				            </div>
				        </div>`;
                        $(reply).insertBefore($(form));
                        //clear reply text input
						$('#'+formId).children('form').children("div").children( 'input[type=text]')[0].value="";
						//get number of total replies
						let count=$('#Commentreplies'+comment_id).html().split("")[0];
						//increase number of replies
						$('#Commentreplies'+comment_id).html(parseInt(count)+1+" replies");

					},
					error: function (XMLHttpRequest) {
		        }
			});

		}