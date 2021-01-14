@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/tagsinput.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

	@if (session('success'))
        <div class="alert alert-success" style="width:600px;margin:0 auto;">
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
					<form class="checkout-form" enctype="multipart/form-data" method="POST" action="{{ route('designs.update', ['design'=> $design]) }}">
						<div class="cf-title">Edit Design</div>
						<div class="row address-inputs">
							<div class="col-md-12">
								@method('PATCH')
								@include('designs.partials.form')
								 
							</div>
							
						</div>
						<button class="site-btn submit-order-btn">Edit Design</button>
					</form>
				</div>
			</div>
		</div>
	</section>
	
@endsection
@push('scripts')
<!-- JavaScript -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"> </script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
	<script src="{{ asset('js/tagsinput.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script type="text/javascript">
		$('.selection').select2();
		 $(".materials").select2({
		    placeholder: "Material"
		});
		 $('.DesignImage').on('click', function(event) {
		 	event.preventDefault();
		    $('#image-upload').click();
		});
		 function displayImage(input,ImageId)
		 {
		 	console.log(ImageId);
		 	var image= $('#'+ImageId);
		 	if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                image
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
		 }
	</script>
@endpush