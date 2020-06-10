
		$('.js-example-basic-single').select2();
		let domselected=0;
		let filters="";
		$( "#selectable" ).selectable({
		    	 stop: function() {
			        domselected = $("#selectable .ui-selected").map(function() {
                        return $(this).text();

                    });
					console.log(domselected[0]);
					getValues();
			      }	    
		});
		$( ".filter" ).selectmenu({
	      change: function( event, data ) {
	      filters=data.item.value;
	      getValues();
	       console.log(data.item.value);
	      }
	    });
		function getValues(){
				var category = domselected[0];
				var min=$('#minamount').val();
				var max=$('#maxamount').val();
				var filterType=filters;
				console.log(category);
				console.log(min);
				console.log(max);
				console.log(filterType);
				$.ajaxSetup({
			        headers: {
			          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			        }
			      });
				$.ajax({
		        type: 'POST',
		        url: 'http://localhost:8000/design/filterBy',
		        data: {
		            'category':category,
		            'minPrice':min,
		            'maxPrice':max,
		            'filterType':filterType
		        },
		        success: function (data) {
		            console.log(data.designs);
		            let designs=data.designs;
		            $('.designs').html("");
		            designs.forEach((element) => {
		            	$('.designs').append(`
		            		<div class="col-lg-4 col-sm-6">
							<div class="product-item">
								<div class="pi-pic">
									<a href="design/${element.id}"><img src="/storage/${element.image}" alt="Design Image" id="designImage"></a>
																	</div>

								<div class="pi-text">
									<h6>&dollar;${element.price} </h6>
									<h5>${element.title} </h5>
									<p>By ${element.designer.name}</p>
								</div>

							</div>
						</div>
		            		`);
		            });
		        },
		        error: function (XMLHttpRequest) {
		        }
		    });

			}
			$('.price-range').slider({change: function() { getValues(); }});

		$( document ).ready(function() {		
			
			// $('.filter').change(function(event){
			// 	event.preventDefault();
			// 	console.log('hi');
				
			// 	getValues();
			// 	// var f=$('.filter').options[$('filter'.selectedIndex)].value;
			// })
			$("#selectable li").click(function() {
			  $(this).addClass("selected");
			});
		});