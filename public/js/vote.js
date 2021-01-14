$('.wishlist-btn').click(function(e) {
			let design_id = $('#designId').val();
			let vote_element_classes = e.target.className.split(" ");
			let vote_action="";
			if (vote_element_classes.includes("not-voted"))
			{
				vote_action="add";
			}
			else if (vote_element_classes.includes("voted")) {
				vote_action="remove";
			}
			  $.ajaxSetup({
			        headers: {
			          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			        }
			      });
				$.ajax({
		        type: 'POST',
		        url: 'http://localhost:8000/designs/vote',
		        data: {
		            'design_id':design_id,
		            'vote_action':vote_action
		        },
		        success: function (total_likes) {
		        	console.log(total_likes);
		        	$( ".fa-heart" ).toggleClass( "voted" );
		        	$( ".fa-heart" ).toggleClass( "not-voted" );
		        	$(".votes").html(`Total Votes : ${total_likes}`);

		        },
		        error: function (XMLHttpRequest) {
		        }
		    });
		});