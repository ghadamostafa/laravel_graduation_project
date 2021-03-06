@extends('layouts.app')

@section('content')

	@if (session('success'))
        <div class="alert alert-success" style="margin:0 auto;">
            {{ session('success') }}
        </div>
    @endif
    <h5 style="margin: 20px auto;text-align: center;">Here is the source pattron files for the designs you have just bought ,download it .</h5>
<section class="category-section spad">
		<div class="container">
			<div class="row">
				<div class="col-lg-10 mb-2 row" style="margin: 0 auto;">
					
						 @forelse($designs as $design)
							<div class="col-lg-4 col-sm-6">
								<div class="product-item">
									<div class="pi-pic">
	                                   <a href="{{route('design.show', ['design' => $design->id])}}"><img src="{{asset ('storage/'.$design->images()->first()->image) }} " alt="Design Image" id="designImage"></a>
										
									</div>
									
									<div class="pi-text">
										<a href="{{route('design.show',$design->id)}}" style="color: black;">{{$design->title}}</a>
										<a href="{{asset ('storage/'.$design->source_file) }}" download="{{$design->source_file}}" class="btn btn-dark" style="float: right;">Download</a>									
							
									</div>
									
								</div>
							</div>
						@empty
							<div class="alert alert-danger">No Designs Yet!</div>
						@endforelse 
				</div>
			</div >
		</div>
</section>
@endsection