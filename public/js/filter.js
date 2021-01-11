	
	let category="";
	let tag="";
	let material="";
	let sortType="";
	let minPrice=0;
	let maxPrice=0;

		//categories filter
		$( "#categories" ).selectable({			
			stop: function() {
			   	categorySelected = $("#categories .ui-selected").map(function() {
				   return $(this).text();
			   });
			   category=categorySelected[0];
			   display_selected_filter_badge('categories',categorySelected[0]);
			   display_designs();
			 }	    
		});  
		//tags filter
		$( "#tags" ).selectable({
		    stop: function() {
			    tagSelected = $("#tags .ui-selected").map(function() {
                        return $(this).text();
				});
				tag=tagSelected[0];
				display_selected_filter_badge('tags',tagSelected[0]);
				display_designs();
			}	    
		});
		//materials filter
		$( "#materials" ).selectable({
			stop: function() {
			    materialSelected = $("#materials .ui-selected").map(function() {
				   return $(this).text();
				});
				material=materialSelected[0];
				display_selected_filter_badge('materials',materialSelected[0]);
				display_designs();
			 }	    
		   });
		
		//sorting filter
		$( ".sorting" ).selectmenu({
			change: function( event, data ) {
			sortType=data.item.value;
			console.log(sortType);
			display_designs(); 
			}
		});

		//price filter
		$('.price-range').slider({
			change: function() { 
				getPrice();
				console.log(minPrice);
				display_designs(); 
			}
		});

		function display_selected_filter_badge(filter,selectedValue)
		{
			if($('.filterTags').has(`.${filter}`).length > 0)
			{
				$('.filterTags').children(`.${filter}`).html(`${selectedValue}<i class="close fa fa-times"></i>`);
			}
			else
			{
				$('.filterTags').append(`<span class="badge badge-pill badge-light ${filter}" style="size: 200px" onclick="deleteFilters('${filter}',this)">
				${selectedValue}<i class="close fa fa-times" ></i>
				</span>`);
			}
		} 

		function deleteFilters(filter,filter_badge)
		{
			$(`#${filter} .ui-selected`).removeClass('ui-selected');

			if(filter == 'tags')
			{	
				tag="";
			}
			else if(filter == 'materials')
			{
				material="";
			}
			else if(filter == 'categories')
			{
				category="";
			}
			filter_badge.remove();
			display_designs();
		}
		function getPrice(){
			minPrice= parseInt($('#minamount').val().split('$')[1]);
			maxPrice= parseInt($('#maxamount').val().split('$')[1]);
		}
		function display_designs(page){
				let url='http://localhost:8000/designs/?min='+minPrice+'&max='+maxPrice;
				if(category)
				{
					url+='&category='+category;
				}
				if(tag)
				{
					url+='&tag='+tag;
				}
				if(material)
				{
					url+='&material='+material;
				}
				if(sortType)
				{
					url+='&sortType='+sortType;
				}
				console.log(material);
				$.ajax({
		        type: 'GET',
		        url: url+'&filteredPages=' + page,
		        success: function (data) {
		            $('.designs').html(data);
		        },
		        error: function (XMLHttpRequest) {
		        }
		    });

			}
			

		$( document ).ready(function() {	
			// categorySelected = $("#categories .ui-selected").map(function() {
            //             return $(this).text();
            //         });	
			$("#categories li").click(function() {
			  $(this).addClass("selected");
			});
			$("#tags li").click(function() {
			  $(this).addClass("selected");
			});
			$("#materials li").click(function() {
			  $(this).addClass("selected");
			});
			getPrice();
	        $(document).on('click', '.pagination a', function(e) {
	        	if($(this).attr('href').includes('filteredPages'))
	        	{
		        	let page=$(this).attr('href').split('filteredPages=')[1];
		        	display_designs(page);
		            e.preventDefault();
	        	}
	        });

		});
