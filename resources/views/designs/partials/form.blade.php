
	{{ csrf_field() }}

	<!-- title -->
	<input type="text" placeholder="Title" name="title" value="{{ old('title') ?? $design->title}}" class="form-control  {{ $errors->first('title') ? 'is-invalid invalid':''}}" autofocus>
	@error('title')
		<span class="invalid-feedback  d-block" role="alert">{{ $message }}</span>
    @enderror

	<!-- price -->
	<input type="text" placeholder="Price" name="price" autofocus value="{{ old('price') ?? $design->price}}" class="form-control {{ $errors->first('price') ? 'is-invalid invalid':''}}">
	@error('price')
		<span class="invalid-feedback  d-block" role="alert">{{ $message }}</span>
    @enderror

	<!-- <input type="text" data-role="tagsinput" class="form-control" name="tags" placeholder="Tags" value="{{ old('tags') ?? $design->tags}}" > -->
								
	<!-- description -->
	<textarea  name="description" placeholder="Description" class="form-control mb-2 mt-2 {{ $errors->first('description') ? 'is-invalid':''}}" rows="4" cols="50" autofocus>{{ old('description') ?? $design->description}}</textarea>
	@error('description')
        <span class="invalid-feedback  d-block " role="alert">{{ $message }}</span>
    @enderror	

	<!-- tags -->
	<div  >
        <select id="tags" name="tag_id" class="form-control mb-2 selection {{ $errors->first('tag_id') ? 'is-invalid':''}}" autofocus>
            <option value="" disabled selected>Tags</option>
            @foreach ($tags as $tag)              
                <option value="{{ $tag ->id}}" {{   ( ($tag->id == old('tag_id')) || ( ($design->tag) && ($design->tag->id == $tag->id) ) ) ? 'selected':'' }} >{{ $tag ->name}}</option>
			@endforeach
		</select>
	</div>
	@error('tag_id')
		<span class="invalid-feedback  d-block " role="alert">{{$message }}</span>
	@enderror

	<!-- material -->
	<div class="mt-2">
		<select id="Material" name="Material[]" class=" form-control materials {{ $errors->first('Material') ? 'is-invalid':''}}" multiple="multiple" autofocus>								  
		  @foreach ($designMaterials as $material) 
		  	@if (old('Material'))
			  	@foreach( old('Material') as $oldMaterial )
			  	<option value="{{ $material ->id}}" {{ ($oldMaterial == $material->id) ? 'selected':''}}>{{ $material ->name}}</option>
			  	@endforeach
            @elseif($design->materials->count() > 0)
                @foreach($design->materials as $selectMaterial) 
					<option value="{{ $material ->id}}" {{ ($selectMaterial->id == $material->id)? 'selected':''  }} >
									  			{{ $material ->name}}</option>
				@endforeach
            @else 
		  		<option value="{{ $material ->id}}" >{{ $material ->name}}</option>
		  	@endif
		  @endforeach
		</select>
	</div>
	@error('Material')
		<span class="invalid-feedback  d-block" role="alert">{{$message }}</span>
	@enderror

								
	<!-- category -->
	<div style="margin: 10px 0;">
		<select id="cars" name="category" class="form-control selection" autofocus>
            <option value="" disabled selected >Category</option>   
            @foreach($categories as $category)
            <option value="{{ $category }}" {{ ( (old('category')==$category) || (($design->category)&&($design->category == $category)) ) ? 'selected':'' }} >{{ ucfirst($category) }}</option>
            @endforeach
		  
		</select>
	</div>
	@error('category')
		<span class="invalid-feedback  d-block" role="alert">{{$message }}</span>
	@enderror

	<!-- source file pattron -->
	<div class="form-group">
		<label for="Pattern">Source Design Pattron</label>
		<input type="file" name="sourceFile" id="Pattern" class="form-control  {{ $errors->first('sourceFile') ? 'is-invalid ':''}}"  autofocus>
	</div>
	@error('sourceFile')
	    <span class="invalid-feedback  d-block" role="alert">{{$message }}</span>
	@enderror

	<!-- images -->
	<div class="form-group">
		<label for="imgeFile">Design Images  (can attach more than one) </label>
		<input type="file" id="imgeFile" name="images[]" class="form-control {{ $errors->first('images') ? 'is-invalid ':''}}" multiple autofocus >
	</div>
	@if($errors->first('images'))
		<span class="invalid-feedback  d-block" role="alert">{{$errors->first('images') }}</span>
	@elseif($errors->first('images.*'))
		<span class="invalid-feedback  d-block" role="alert">{{$errors->first('images.*') }}</span>
	@endif

