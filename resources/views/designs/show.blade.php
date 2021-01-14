@extends('layouts.app')

@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/comments.css') }}">
@endsection

@section('content')
	@if (session('success'))
        <div class="alert alert-success" style="margin:0 auto;">
            {{ session('success') }}
        </div>
    @endif
	<!-- product section -->
	<section class="product-section">
		<div class="container">
			<div class="back-link">
				<a href="{{ route('designs.index') }}"> &lt;&lt; Back to Designs</a>
			</div>
			<div class="row">
				<div class="col-lg-6">
					<div class="product-pic-zoom">
						<img class="product-big-img" src="{{asset ('storage/'.$design->firstImage() ) }}" alt="">
					</div>
					<div class="product-thumbs" tabindex="1" style="overflow: hidden; outline: none;">
						<div class="product-thumbs-track">
							@forelse ($design->images as $Image)
							<div class="pt active" data-imgbigurl="{{asset ('storage/'. $Image->image) }}"><img src="{{asset ('storage/'. $Image->image) }}" alt=""></div>
							@empty
								<div>No Images for this product</div>
							@endforelse
						</div>
					</div>
				</div>
				<div class="col-lg-6 product-details">
					<h2 class="p-title ">{{$design->title}}</h2>
					@auth
						@if(Auth::user()->role != "user")
						<h3 class="p-price" style="display: inline;">&dollar;{{$design->price}} </h3>
						@endif

						<!-- vote -->
						@if ( (Auth::user()->role == "user") && ($design->state == "sketch") )
						<a href="javascript:void(0)" class="wishlist-btn " style="font-size: 40px;margin-left: 10px;"><i class="fa fa-heart {{ $userVoted ? 'voted':'not-voted' }}"></i>
						</a>
						@endif
					@endauth
					<input type="hidden" name="designId" value="{{ $design->id }}" id="designId">

					

					<div class="pi-links">
						<p>Designer : {{ $design->designer->name}}</p> 
						 <p>Materials: 
						@foreach($design->materials as $material) 
							{{ $material->name }}
							@if ( ! $loop->last)
							 -
							@endif
						@endforeach</p> 
						<p>Tag: {{$design->tag->name}}</p> 
						<p>Category: {{$design->category}}</p>
					</div>

					@if($design->state == "sketch")
						<h4 class="p-stock">Available: <span>{{$design->state}}</span></h4>
					@else
						<h4 class="p-stock"><span>Not Available</span></h4>
					@endif

					
					<div class="pi-links">
						<p class="votes">Total Votes : {{ $design->total_likes}}</p> 
					</div>
					<!-- <div class="p-review">
						<a href="">3 reviews</a>|<a href="">Add your review</a>
					</div> -->

					@auth
						@if( $design->state == "sketch" )
							@canany(['delete','update'],$design)
								<!-- delete design -->
								<form action="{{route('designs.destroy',$design->id)}}" method="POST" style="display: inline;">
										@method('DELETE')
										@csrf
									<button class="deleteDesign btn-danger" onclick="return confirm('Are you sure?')"  type="submit">Delete</button>
								</form>
								<!-- edit design -->
								<a class=" editDesign " href="{{route('designs.edit',$design->id)}}"  >Edit</a>
							@elsecanany(['buy'],App\Models\Design::class)
							<!-- buy design -->
								<a href="javascript:void(0)" data-id="{{ $design->id }}" class="add-card site-btn mb-2"  >ADD TO CART</a>		
							@endcanany
						@endif
					@endauth
					<div id="accordion" class="accordion-area">
						<div class="panel">
							<div class="panel-header" id="headingOne">
								<button class="panel-link active" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">information</button>
							</div>
							<div id="collapse1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
								<div class="panel-body">
									<p>{{$design->description}}</p>
								</div>
							</div>
						</div>

						<!-- Reviews -->
						<div class="panel">
							<div class="panel-header" id="headingTwo">
								<button class="panel-link" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">Comments </button>
							</div>
							<div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
								
									@include('designs.partials.comments')
								

							</div>
						</div>
						
					</div>
					
				</div>
			</div>
		</div>
	</section>
	<!-- product section end -->


	<!-- RELATED PRODUCTS section -->
	@if(count($RelatedDesigns) > 0)
		<section class="related-product-section">
			<div class="container">
				<div class="section-title">
					<h2>RELATED Designs</h2>
				</div>
				@if(count($RelatedDesigns) == 1)
					<div class="product-item" style="text-align: center;">
							<div class="pi-pic">
								<a href="{{route('designs.show', ['design' => $RelatedDesigns->first()->id])}}"><img style="height:300px;width:250px;"src="{{asset ('storage/'.$RelatedDesigns->first()->firstImage() )}}" alt=""></a>
							</div>
							</br>
					</div>
				@else
					<div class="product-slider owl-carousel">
						@foreach($RelatedDesigns as $design)
						<div class="product-item">
							<div class="pi-pic">
								<a href="{{route('designs.show', ['design' => $design->id])}}"><img id="designImage" src="{{asset ('storage/'.$design->firstImage()) }}" alt=""></a>
							</div>
							<div class="pi-text ">
								@auth
									@if((Auth::user()->role != "user"))
									<h6>&dollar;{{ $design->price }}</h6>
									@endif
								@endauth
								<p>{{ $design->title }} </p>
							</div>
						</div>
						@endforeach
					</div>
				@endif
			</div>
		</section>
	@endif
	<!-- RELATED PRODUCTS section end -->

@endsection
@push('scripts')
	<script src="{{ asset('js/vote.js') }}"></script>
	
	<script type="text/javascript">
		let formId='';
		function displayReplyForm(id){
			formId=id;
			console.log('#'+id);
			$('#'+id).toggleClass('displayForm');
		}
		function commentReply()
		{
			let comment_id = $('#'+formId).children('form').children( 'input[name=commentId]')[0].value;
			let Reply_body=$('#'+formId).children('form').children("div").children( 'input[type=text]')[0].value;
			console.log(comment_id);
			console.log(Reply_body);
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
					success: function (data) {
						console.log(data);
						let form=$('#'+formId).children('form')[0];
						let reply=`<div class="media g-mb-30 media-comment mb-2 replies">
				            <img class="d-flex g-width-50 g-height-50 rounded-circle g-mt-3 g-mr-15" src="/storage/${data.user.image}" alt="Image Description">
				            <div class="media-body u-shadow-v18 g-bg-secondary g-pa-30">
				              <div class="g-mb-15">
				                <h5 class="h5 g-color-gray-dark-v1 mb-0">${data.user.name}</h5>
				                <span class="g-color-gray-dark-v4 g-font-size-12">${new Date(data.reply.created_at).toISOString().replace(/T/, ' ').replace(/\..+/, '')}</span>
				              </div>
				              <p>${data.reply.body}</p>
				            </div>
				        </div>`;
						$(reply).insertBefore($(form));
						$('#'+formId).children('form').children("div").children( 'input[type=text]')[0].value="";
						let replies= $('#Commentreplies'+comment_id).html();
						let count=replies.split("")[0];
						console.log(replies);
						$('#Commentreplies'+comment_id).html(parseInt(count)+1+" replies");

					},
					error: function (XMLHttpRequest) {
		        }
			});

		}
					
		
	</script>
	<script src="{{ asset('js/comments.js') }}"></script>
@endpush