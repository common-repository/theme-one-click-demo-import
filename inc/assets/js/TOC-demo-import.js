jQuery(document).ready(function($){

	$('.install-plugin').click(function(e){		
		e.preventDefault();
		var $this = $(this); 

		var import_value = $this.val();
		//alert(import_value);
		// var fd = new FormData();
		// console.log(fd);
		// fd.append('action', 'TOC_demo_import_ajax_setup');

		$.ajax({
			type: 'POST',
			beforeSend: function(){
		    	$('.ajax-modal-loader').css("visibility", "visible");
		  	},
            url: TOC_demo_import_object.ajaxurl,
            data:  {"action": "TOC_demo_install_plugin",import_id:import_value},
            success   : function (data) {
               // console.log(data);
               $this.removeClass('install-plugin');
			    $this.removeClass('btn-primary');
			    $this.addClass('btn-secondary');
			    $this.html('Active');
			    $this.attr('disabled', 'disabled');
            },
            error : function (data){
            	// console.log("test");
            	// 	  console.log(data);
            },
            complete: function(){
			    $('.ajax-modal-loader').css("visibility", "hidden");

			  }
		});
	});

	$('.import-option').click(function(e){		
		e.preventDefault();
		var $this = $(this); 

		var import_value = $this.val();

		var xml_id = '#download-xml'+import_value;
		var wie_id = '#download-wie'+import_value;
		var dat_id = '#download-dat'+import_value;
		var import_result = '.import-result'+import_value;
		
		if($(xml_id).is(':checked')){
			var dl_xml = $(xml_id).val();	
		}

		if($(wie_id).is(':checked')){
			var dl_wie = $(wie_id).val();	
		}

		if($(dat_id).is(':checked')){
			var dl_dat = $(dat_id).val();	
		}
		

		$.ajax({
			type: 'POST',
			beforeSend: function(){
		    	$('.ajax-xml-loader').css("visibility", "visible");
		    	if(dl_xml != "yes"){
		    		$('.ajax-xml-loader span.message').css("display", "none");
		    		$('.ajax-xml-loader span.no-message').css("display", "cell");
		    	}
		    	else {
		    		$('.ajax-xml-loader span.no-message').css("display", "none");
		    	}


		  	},
            url: TOC_demo_import_object.ajaxurl,
            data:  {"action": "TOC_demo_import_ajax_setup",import_id:import_value, dl_xml_file:dl_xml },
            success   : function (data) {
               // console.log(data);
			    if(dl_xml == "yes"){
					    $(import_result).html("<h5>Import Result</h5><p>XML File successfully imported</p>");
				}
				else {

					$(import_result).html("<h5>Import Result</h5>");
				}
            },
            error : function (data){
            	// console.log("test");
            	// 	  console.log(data);
            },
            complete: function(){
			    $('.ajax-xml-loader').css("visibility", "hidden");
			    $('.ajax-xml-loader span.no-message').css("display", "none");
			    $.ajax({
					type: 'POST',
					beforeSend: function(){
				    	$('.ajax-wie-loader').css("visibility", "visible");
				    	if(dl_wie != "yes"){
				    		$('.ajax-wie-loader span.message').css("display", "none");
				    		$('.ajax-wie-loader span.no-message').css("display", "cell");
				    	}
				    	else {
				    		$('.ajax-wie-loader span.no-message').css("display", "none");
				    	}
				  	},
		            url: TOC_demo_import_object.ajaxurl,
		            data:  {"action": "TOC_demo_import_ajax_setup",import_id:import_value, dl_wie_file:dl_wie},
		            success   : function (data) {
		               // console.log(data);
		               if(dl_wie == "yes"){
					    $(import_result).append("<p>WIE File successfully imported</p>");
						}
		            },
		            error : function (data){
		            	// console.log("test");
		            	// 	  console.log(data);
		            },
		            complete: function(){
					    $('.ajax-wie-loader').css("visibility", "hidden");
					   	$('.ajax-wie-loader span.no-message').css("display", "none");
					    if(dl_dat != "yes"){
				    		$('.ajax-dat-loader span.message').css("display", "none");
				    		$('.ajax-dat-loader span.no-message').css("display", "cell");
				    	}
				    	else {
				    		$('.ajax-dat-loader span.no-message').css("display", "none");
				    	}
					    $.ajax({
							type: 'POST',
							beforeSend: function(){
						    	$('.ajax-dat-loader').css("visibility", "visible");
						  	},
				            url: TOC_demo_import_object.ajaxurl,
				            data:  {"action": "TOC_demo_import_ajax_setup",import_id:import_value, dl_dat_file:dl_dat},
				            success   : function (data) {
				               // console.log(data);
				               if(dl_dat == "yes"){
							    $(import_result).append("<p>DAT File successfully imported</p>");
								}
				            },
				            error : function (data){
				            	// console.log("test");
				            	// 	  console.log(data);
				            },
				            complete: function(){
							    $('.ajax-dat-loader').css("visibility", "hidden");
							    $('.ajax-dat-loader span.no-message').css("display", "none");
							    $this.removeClass('btn-primary');
							    $this.addClass('btn-secondary');
							    $this.html('Imported');
								$this.attr('disabled', 'disabled');
								$('.import-preview').css("display", "block");

							  }
						});


					  }
				});


			  }
		});
	});



});