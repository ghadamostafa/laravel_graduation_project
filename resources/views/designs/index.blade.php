@extends('layouts.app')

@section('content')

	<!-- Category section -->
	<section class="category-section spad">
		<div class="container">
			<div class="row">
<!-- start filter  -->
				<div class="col-lg-3 order-2 order-lg-1">
					<!-- Categories -->
					<div class="filter-widget">
						<h2 class="fw-title">Categories</h2>
						<ul class="category-menu" id = "categories">
							@foreach($categories as $category)
								<li ><a href="#" class="{{$selectedCategory == $category?'ui-selected':''}}">{{ $category }}</a></li>		
							@endforeach
						</ul>
					</div>
					<!-- tags -->
					<div class="filter-widget">
						<h2 class="fw-title">Tags</h2>
						<ul class="category-menu" id = "tags">
							@foreach ($tags as $tag)
							<li><a href="#">{{$tag->name}}</a></li>
							@endforeach
						</ul>
					</div>
					
					<!-- Materials -->
					<div class="filter-widget">
						<h2 class="fw-title">Materials</h2>
						<ul class="category-menu" id = "materials">
							@foreach ($materials as $material)
							<li><a href="#">{{$material->name}}</a></li>
							@endforeach
						</ul>
					</div>

					<!-- price -->
					<div class="filter-widget mb-0">
						<h2 class="fw-title">refine by</h2>
						<div class="price-range-wrap">
							<h4>Price</h4>
                            <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" data-min="{{$minPrice}}" data-max="{{$maxPrice}}">
								<div class=" ssss ui-slider-range ui-corner-all ui-widget-header" style="left: 0%; width: 100%;"></div>
								<span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 0%;">
								</span>
								<span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 100%;">
								</span>
							</div>
							<div class="range-slider">
                                <div class="price-input">
                                    <input type="text" id="minamount" >
                                    <input type="text" id="maxamount" >
                                </div>
                            </div>
                        </div>
					</div>
					
					
					
				</div>

				<div class="col-lg-9  order-1 order-lg-2 mb-5 mb-lg-0">
					<div class="row">
						@if($desings->total() >0 )
							<div class="col-lg-12 mb-2 row">
								
								<div class="col-lg-6 filterTags">						  
									
								</div>
								<div class="dropdown show col-lg-6">
									<select class="btn sorting" role="menu" aria-labelledby="dropdownMenuLink">
										<option selected disabled> Sort</option>
										<option> <a class="dropdown-item " href="#" >Top Rated</a></option>
										<option> <a class="dropdown-item " href="#" >Latest</a></option>
									</select>
								</div>
							</div>
						@endif
<!-- filter end -->					
						@include('designs.partials.designs')
					</div>
				
				</div>
			</div>
		</div>
	</section>
	<!-- Category section end -->
@endsection
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script src="{{ asset('js/filter.js') }}"></script>

@endpush