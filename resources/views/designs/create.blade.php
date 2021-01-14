@extends('layouts.app')

@section('content')
	
	
	@if (session('success'))
        <div class="alert alert-success" style="margin:0 auto;">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif  
	<section class="checkout-section spad">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 order-2 order-lg-1 design">
					<form class="checkout-form " enctype="multipart/form-data" method="POST" action="{{route('designs.store')}}" >
						<div class="cf-title">Add New Design</div>
						<div class="row address-inputs">
							<div class="col-md-12">
								@include('designs.partials.form')
							</div>
							
						</div>
						<button class="site-btn submit-order-btn">Add Design</button>
					</form>
				</div>
				<!-- <div class="col-lg-6 order-2 order-lg-1 ">
				</div> -->
			</div>
		</div>
	</section>
	
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script type="text/javascript">
		 $('.selection').select2();
		 $(".materials").select2({
		    placeholder: "Material"
		});
	</script>
@endpush