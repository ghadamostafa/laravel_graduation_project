@extends('layouts.app')
	@section('content')
	<!-- product section -->
	<section class="product-section">
	@forelse($designer as $designer_data)
		<div class="container">
			<div class="row">
				<div class="col-lg-6" >
						<div style="width:300px;height:400px;margin-bottom : 30px;">
							<img class="product-big-img" style="width:300px;height:400px;"src="<?php echo asset("storage/$designer_data->image")?>" alt="{{ $designer_data->name }}">
							</br>
							<h3 style="text-align: center;">{{ $designer_data->name }}
								@if($vote_exist->count() > 0)
									<i class="flaticon-heart text-danger" value="{{$designer_data->id}}"></i>
								@else
									@if($designer_data->id != Auth::id())
										<i class="flaticon-heart text-dark" value="{{$designer_data->id}}"></i>
									@endif
								@endif
							</h3>
						</div>
						</br>
						<div class="row" >
							@can('update',$designer_data)
							@if($user->role == "designer")
								<div class="col-lg-3" style="margin-right:15px;"  >
								<a  href="{{ route('user.edit',$designer_data) }}" class="editDesign">Edit Profile</a>
								</div> 
							@endif
							@endcan
							@can('update',$designer_data)
							@if($user->role == "designer")
								<div class="col-lg-3" >
								<a  href="{{ route('user.create',$designer_data) }}" class="editDesign">Add Piography</a>
								</div> 
							@endif
							@endcan
						</div>
						@can('update',$designer_data)
						@if($user->role == "designer")
						
						</br>
						<div class="col-lg-3"style="margin-left:60px;" >
							{!! Form::open(['route'=>['designer.destroy',$designer_data],'method'=>'delete']) !!} 
							{!! Form::submit('DELETE',['class'=>'deleteDesign btn-danger']) !!}
							{!! Form::close() !!} 
                        </div>
						@endif
						@endcan

					
					
                </div>
                </br> 
				<div class="col-lg-6 product-details"style="margin-top: 80px;">
					<h2 class="p-title"> {{ $designer_data->name }}</h2></br>
					<h4 class="p-stock">followers  <span id = "followers">{{$likes}}</span></h4>
					<h4 class="p-stock">Emial <span>{{$designer_data->email}}</span></h4>
                    <h4 class="p-stock">Phone <span>{{$designer_data->phone}}</span></h4>
					<h4 class="p-stock">Address <span>{{$designer_data->address}}</span></h4>
					@can('update',$designer_data)
                    @if($user->role == "designer")
					<a href="{{ route('design.create',$designer_data) }}" class="btn site-btn"style="margin-top:15px;">ADD NEW DESIGN</a>
					@endif
					@endcan
					<div id="accordion" class="accordion-area">
						<div class="panel">
							<div class="panel-header" id="headingOne">
								<button class="panel-link " data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">about</button>
							</div>
							<div id="collapse1" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
								<div class="panel-body">
									<p>{{$about[0]->about}}</p>
								</div>
							</div>
						</div>
						<div class="panel">
							<div class="panel-header" id="headingTwo">
								<button class="panel-link" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">Achievements</button>
							</div>
							<div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
								<div class="panel-body">
								<h5 style="color: #f51167;">Designs</h5>
								<h6>{{$design_count}}</h6>
									</br>
								<h5 style="color: #f51167;">Sold Designs</h5>
								<h6>{{$prev_count}}</h6>
								</div>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- product section end -->
	<!-- featured designs section -->
	<section class="related-product-section">
		<div class="container">
			<div class="section-title">
				<h2>FEATURED DESIGNS</h2>
			</div>
			{{-- <div class="product-slider owl-carousel" >    --}}
			<div class="row" id="featuresection">
				@foreach($featured_images as $fimage)
					<div class="col-lg-3 col-sm-6"  id="design{{$fimage->design->id}}">
						<div class="product-item" >
							<a href="{{route('design.show', ['design'=>$fimage->design_id])}}"><img style="width:250px;height:300px;"class='featured_image'id =' {{$fimage->design->id}}' src="{{asset ('storage/'.$fimage->image) }}" alt=""></a>
							@can('update',$designer_data)
								@if($user->role == "designer"&& $fimage->design->featured )
								<i class="fa fa-trash"id ="{{$fimage->design->id}}"></i>
								@endif
							@endcan
						</div>
					</div>		
            	@endforeach
			</div>
		</div>
	</section>
	<!-- featured designs section end -->
    	<!-- current designs section -->
	<section class="related-product-section">
		<div class="container">
			<div class="section-title">
				<h2>CURRENT DESIGNS</h2> 
			</div>
				{{-- <div class="col-lg-3 col-sm-6">
					<div class="product-item"> --}}
			@if($designs)
				<div class="product-slider owl-carousel">
					{{-- @foreach($designs as $design) --}}
					@foreach($designs as $design)
								<div class="product-item">
								<div class="pi-pic current" id="{{$design->id}}" >
									<a href="{{route('design.show', ['design' => $design->id])}}"><img class="current_designs" id="{{$design->id}}" style="width:250px;height:300px;"src="{{asset ('storage/'.$design->images()->first()->image) }}" alt=""></a>
								</div>
								</br>
								@can('update',$designer_data)
								@if($user->role == "designer"&& ! $design->featured )
								{{-- <a  href="{{ route('featuredesign',$design->id) }}" class="btn btn-info featured">Add as a Featured</a> --}}
								<button class="btn btn-info featured" id="{{$design->id}}">Add as a Featured</button>
										{{-- @else
											<button type="button" class="btn btn-danger"value="{{$cimage->design_id}}">x</button>
										@endif --}}			
								@endif
								@endcan
							</div>			
					@endforeach
				</div>
			@else
				<h3 style="text-align:center;color:navy;">There are no designs  yet</h3>
			@endif
		</div>		
	</section>
	<!-- current designs section end -->
	</br>
	</br>
		<!-- previous work section start -->
	<section class="related-product-section">
		<div class="container">
			<div class="section-title">
				<h2>PREVIOUS DESIGNS</h2>
			</div>
			@if($prev_works)
				<div class="product-slider owl-carousel">
					
	            @foreach($prev_works as $design)
					<div class="product-item">
						<div class="pi-pic">
							<img style="height:300px;width:250px;"src="{{asset ('storage/'.$design->images()->first()->image) }}" alt="">
						</div>
						</br>
					</div>
				@endforeach
					
				</div> 
			@else
				<h3 style="text-align:center;color:navy;">There are no designs sold yet</h3>
			@endif
			
		</div>
	</section>		
	<!-- previous work section end -->
	@empty
	<div style="height:300px;margin:auto;">
	<h3 style="text-align:center;color:navy;">This Designer Doesn't Exist</h3>
	</div>
	@endforelse
@endsection