@extends('layouts.app')
@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/companyStyle.css') }}">
@endsection
@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center py-4">
      <div>
        @can('update', $company)
			<a name="" id="" class="btn site-btn" href="{{ route('user.edit',$company) }}" role="button" style="background-color: gray;">Edit Profile</a>
		@endcan
      </div>
    </div>
</div>
<div class="container">
    <div class="text-center py-5">
	     <img class="img-fluid ui-w-100 rounded-circle" src="{{asset('storage/'.$company->image)}}" alt="">

	     <div class="col-md-8 col-lg-6 col-xl-5 p-0 mx-auto">
	        <h4 class="font-weight-bold my-4">{{$company->name}}</h4>
	        @if($company->profile)
				@if($company->profile->about)
					<div class="text-muted mb-4">
			         {{$company->profile->about}}
			        </div>
				@else
					<div class="text-muted mb-4">No About Yet </div>
				@endif
			@endif
	        
	     </div>

	     <div class="text-center">
				@if(($company->profile) && ($company->profile->website))
					<div class="dispInline mr-3"><i class="fa fa-globe" aria-hidden="true"></i> <p class="dispInline" > {{$company->profile->website}} </p></div>
				@endif
				@if($company->email)
				<div class="dispInline mr-3"> <i class="fa fa-envelope fa-sm fa-fw"></i>	<p class="dispInline" >{{$company->email}}</p></div>
				@endif
				@if($company->address)
				<div class="dispInline mr-3"> <i class="fa fa-home fa-sm fa-fw"> </i>	<p class="dispInline">  {{$company->address}}</p></div>
				@endif
				@if($company->phone)
				<div class="dispInline mr-3"> <i class="fa fa-phone fa-sm fa-fw"></i>	<p class="dispInline">{{$company->phone}}</p></div>
				@endif
	     </div>
    </div>
</div>
  <hr class="m-0">

  <ul class=" nav nav-pills tabs-alt justify-content-center" id="CompanyDesigns">
  	<li class="nav-item " >
      <a class="nav-link py-4 {{! Request::query('bought') ? 'active' : ''}}" href="#Shop" data-toggle="tab">Shop</a>
    </li>

    <li class="nav-item  ">
      <a class="nav-link py-4 {{ Request::query('bought') ? 'active':''}}" href="#boughtSketches" data-toggle="tab">Bought Designs</a>
    </li>
    
  </ul>
  <div  class="tab-content">
  	<!-- bought Designs -->
  	<div class="tab-pane fade  {{Request::query('bought') ? 'show active':''}} row" id="boughtSketches">
  		<div class="col-lg-10 col-sm-6 row" style="margin: 20px auto;">
		@forelse($boughtDesigns as $design)
			<div class="col-lg-3 col-sm-6 row ml-2">
				<div class="product-item">
					<div class="pi-pic">
	                     <a href="{{route('design.show', ['design' => $design->id])}}"><img src="{{asset ('storage/'.$design->images()->first()->image) }} " alt="Design Image"  class="img-thumbnail" style="width: 300px; height:200px"></a>					
					</div>
					<div class="pi-text">
						<a href="{{route('design.show',$design->id)}}" class="title" >{{$design->title}}</a>
						
							@can('update', $company)
							<a href="{{asset ('storage/'.$design->source_file) }}" download="{{$design->source_file}}" class="btn btn-dark" style="float: right;">Download</a>
							@endcan
						
					</div>
									
				</div>
			</div>
		@empty
			<div class="alert alert-danger text-center col-12"><span>No Designs Yet!</span></div>
		@endforelse 
		</div>
		<div style="margin: 20px 200px;">
			{!! $boughtDesigns->links() !!}
		</div>
	</div>
	<!-- bought designs end -->
 

<!-- Shop -->
  	<div class="tab-pane fade {{! Request::query('bought') ? 'show active' : ''}} row mb-2" id="Shop">
  		@can('make_company_design',$company)
  			<div class="row" style="margin: 20px auto;">
  				<div class="col-lg-10" style="text-align: center;margin-left: 100px;">
					<a name="" id="" class="btn btn-secondary" href="{{ route('company_design.create') }}" role="button">Add {{$company->name}} Design</a>
				</div>
			</div>
		@endcan
		<div class="col-lg-10 col-sm-6 row" style="margin: 20px auto;">
		  	@forelse( $shopDesigns as $design)
				<div class="col-lg-3 col-sm-6 row ml-2 mb-2">
					<div class="product-item">
						<div class="pi-pic">
							<img src="{{asset('storage/'.$design->image)}}" alt="" class="img-thumbnail" style="width: 300px; height:200px">
						</div>
						<div class="pi-text text-center">
							<h5 class="mb-1 text-primary">{{$design->title}}</h5>
							<p>{{$design->price}}$</p>
							<a href="//{{$design->link}}" class="btn btn-outline-success">Buy Now</a>
						</div>
					</div>
				</div>
			@empty
				<div class="alert alert-danger text-center col-12"><span>No Designs Yet!</span></div>
			@endforelse
		</div>
		<div style="margin: 30px 200px;">
			{!! $shopDesigns->links() !!}
		</div>
	</div>
	<!-- Shop end-->

  </div>

  
@endsection