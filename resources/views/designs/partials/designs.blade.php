			<div class="designs col-lg-12 mb-2">
				<div class=" row ">
						@forelse($desings as $design)
						<div class="col-lg-4 col-sm-6">
							<div class="product-item">
								<div class="pi-pic">
									@if($design->state != "sketch")
									<div class="tag-sale">Sold</div>
									@endif
									<a href="{{route('designs.show', ['design' => $design->id])}}"><img src="{{asset ('storage/'.$design->images()->first()->image) }} " alt="Design Image" id="designImage"></a>
									<div class="pi-links">

										@if((Auth::user())&&(Auth::user()->role == "company") && ($design->state == "sketch") )
										<div class="pi-links">
											<a href="javascript:void(0)" data-id="{{ $design->id }}" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
										</div>
										@endif

										@if((Auth::user())&&(Auth::user()->role == "user") && ($design->state == "sketch") )
										<a href="{{route('designs.show', ['design' => $design->id])}}" class="wishlist-btn"><i class="flaticon-heart"></i></a>
										@endif
									</div>
								</div>

								<div class="pi-text">
									@if((Auth::user())&&(Auth::user()->role != "user"))
									<h6>&dollar;{{$design->price}}</h6>
									@endif
									<a href="{{route('designs.show', ['design' => $design->id])}}"><h5>{{$design->title}}</h5></a>
									<div class="designer-name">
									<i style="font-style: italic;">By</i> 
									<a href="{{route('designer.show', ['designer' => $design->designer->id])}}" class="designer">{{$design->designer->name}}</a>
									</div>
								</div>

							</div>
						</div>
						@empty
							<div class="alert alert-danger " style="margin:auto;">No Designs Yet!</div>
						@endforelse
				</div>

				<div class="row">
							 {!! $desings->links() !!}  
				</div>	
			</div>
