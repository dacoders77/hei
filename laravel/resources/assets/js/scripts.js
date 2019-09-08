jQuery(document).ready(function($) {

	if(window.ajaxDebug) {
		$( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
			console.log([event, jqxhr, settings, thrownError]);
		});
	}

	function formatDate(date) {
	  var monthNames = [
	    "Jan", "Feb", "Mar",
	    "Apr", "May", "Jun", "Jul",
	    "Aug", "Sep", "Oct",
	    "Nov", "Dec"
	  ];

	  var day = date.getDate();
	  var monthIndex = date.getMonth();
	  var year = date.getFullYear();

	  return day + ' ' + monthNames[monthIndex] + ' ' + year;
	}

	$.validator.addMethod('phone', function (value, element, param) {
	    var regex = /^(?:\+?(61))? ?(?:\((?=.*\)))?(0?[2-57-8])\)? ?(\d\d(?:[- ](?=\d{3})|(?!\d\d[- ]?\d[- ]))\d\d[- ]?\d[- ]?\d{3})$/;
	    return this.optional(element) || regex.test(value);
	}, function(param,elem){
		return 'Invalid phone number';
	});

	$.validator.addMethod('aumobile', function (value, element, param) {
	    var regex = /^(04)[0-9]{2}\s[0-9]{3}\s[0-9]{3}$/;
	    return this.optional(element) || regex.test(value);
	}, function(param,elem){
		return 'Invalid Australian phone number';
	});

	$.validator.addMethod('nzmobile', function (value, element, param) {
	    var regex = /^(02)[0-9]{7,9}$/;
	    return this.optional(element) || regex.test(value);
	}, function(param,elem){
		return 'Invalid New Zealand phone number';
	});

	$.validator.addMethod('confirm', function (value, element, param) {
		var name = $(element).attr('name').replace('_confirm','');
		var conf_val = $(element).closest('form').find('[name="'+name+'"]').val();
	    return this.optional(element) || conf_val == value;
	}, function(param,elem){
		return 'Fields do not match';
	});

	$.validator.addMethod('voucher_code', function (value, element, param) {
	    var regex = /^[a-zA-Z0-9]{1,12}$/;
	    return this.optional(element) || regex.test(value);
	}, function(param,elem){
		return 'Invalid voucher code';
	});

	$.validator.addMethod('filesize', function (value, element, arg) {
        if(this.optional(element) || element.files[0].size<=arg){
            return true;
        }else{
            return false;
        }
    }, function(param,elem){
    	function bytesToSize(bytes) {
		   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		   if (bytes == 0) return '0 Byte';
		   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		   return Math.round(bytes / Math.pow(1024, i), 2) + sizes[i];
		}
		return "File size must be less than "+bytesToSize(param);
	});

	$.validator.addMethod("minDate", function(value, element) {
		var min = new Date( Number($(element).attr('data-min')) );
	    var inputDate = value;
		if( /^(1[3-9]|[2-3][0-9])\/[0-9]{2}\/[0-9]{4}/.test(inputDate) ) {
			inputDate = inputDate.split('/');
			inputDate = [inputDate[1],inputDate[0],inputDate[2]].join('/');
		}
	    inputDate = new Date(inputDate);
	    if (inputDate >= min)
	        return true;
	    return false;
	}, function(args,element) {
		var min = new Date( Number($(element).attr('data-min')) );
		return "Must be on or after "+formatDate(min);
	});

	$.validator.addMethod("maxDate", function(value, element) {
		var max = new Date( Number($(element).attr('data-max')) );
		max.setDate(max.getDate() + 1);
	    var inputDate = value;
		if( /^(1[3-9]|[2-3][0-9])\/[0-9]{2}\/[0-9]{4}/.test(inputDate) ) {
			inputDate = inputDate.split('/');
			inputDate = [inputDate[1],inputDate[0],inputDate[2]].join('/');
		}
	    inputDate = new Date(inputDate);
	    if (inputDate < max)
	        return true;
	    return false;
	}, function(args,element) {
		var max = new Date( Number($(element).attr('data-max')) );
		return "Must be on or before "+formatDate(max);
	});

	$.validator.addClassRules('phone', {
	    phone: true
	});

	$.validator.addClassRules('aumobile', {
	    aumobile: true
	});

	$.validator.addClassRules('nzmobile', {
	    nzmobile: true
	});

	$.validator.addClassRules('confirm', {
	    confirm: true
	});

	$.validator.addClassRules('voucher_code', {
	    voucher_code: true
	});

	$.validator.addClassRules('max4mb', {
	    filesize: 4194304
	});

	$.validator.addClassRules('minDate', {
	    minDate: true
	});

	$.validator.addClassRules('maxDate', {
	    maxDate: true
	});

	// Field conditions
	$('#jsform :input').on('change',function(){

		var name = $(this).attr('name');

		$('#jsform .row[data-condition^="'+name+'="]').addClass('hide');
		$('#jsform .row[data-condition^="'+name+'="]').find(':input').each(function(){
			if( $(this).is(':radio,:checkbox') ) {
				$(this).prop('checked',false).prop('disabled',true);
			} else {
				$(this).val('').prop('disabled',true);
			}
		});

		var field_val = $('#jsform [name="'+name+'"]').map(function () {
			if( $(this).is(':radio,:checkbox') ) {
				return $(this).is(':checked') ? this.value : null;
			}
		    return this.value || null;
		}).get(0);

		if( field_val ) {
			$('#jsform .row[data-condition^="'+name+'='+field_val+'"]').removeClass('hide');
			$('#jsform .row[data-condition^="'+name+'='+field_val+'"]').find(':input[required]').prop('disabled',false);
		}
	});

	$('#jsform .row[data-condition]').each(function(i,e){
		var condition = $(this).attr('data-condition').split('=');
		if(!condition) return;

		var _this = $(this);

		if( $(this).is('[data-required]') ) {
			$(this).find(':input').attr({'required':'required'});
			$(this).find(':input').each(function(){
				var id = $(this).attr('id');
				_this.find('label[for="'+id+'"]').append('<span class="required_hint">*</span>');
			});
		}

		$('#jsform [name="'+condition[0]+'"]').trigger('change');
	});

	$('#jsform .autocomplete-toggle-wrapper input[type="checkbox"]').on('change',function(){

		if( $(this).is(':checked') ) {
			$(this).closest('.autocomplete-toggle-wrapper').addClass('hide');
			$(this).closest('.autocomplete-toggle-wrapper').prev().prev('label').addClass('hide');
			$(this).closest('.autocomplete-toggle-wrapper').prev('.autocomplete-wrapper').addClass('hide').find(':input').val('').prop('disabled',true);
			$(this).closest('.autocomplete-toggle-wrapper').next('.autocomplete-hidden-wrapper').removeClass('hide').find(':input').val('').prop('disabled',false);
		} else {
			$(this).closest('.autocomplete-toggle-wrapper').prev('.autocomplete-wrapper').removeClass('hide').find(':input').val('').prop('disabled',false);
			$(this).closest('.autocomplete-toggle-wrapper').next('.autocomplete-hidden-wrapper').addClass('hide').find(':input').val('').prop('disabled',true);
		}

	});

	$('form').removeClass('hide');

	$('#jsform').validate({
		ignore: ':hidden:not(input[type="file"]):not(input[type="radio"]):not(input[type="checkbox"])',
		errorPlacement: function(error, element) {
			if(element.hasClass('input-group-field')){
				error.insertAfter(element.closest('.input-group'));
			} else if(element.is('[type="checkbox"]')){
				error.insertAfter(element.closest('.checkbox-group').find('.checkbox').last());
			} else if(element.is('[type="radio"]')){
				error.insertAfter(element.closest('.radio-group'));
			} else if(element.is('[class*="select2"]')){
				error.insertAfter(element.next('.select2'));
			} else {
				error.insertAfter(element);
			}
		},
	    submitHandler: function(form) {

			// alert('Submit scripts.js clicked 3667');

			$(form).find('#submit_working').removeClass('hide');
			$(form).find('#submit').addClass('hide');

			var formData;

			if( $(form).find('input[type="file"][name]').length ) {
				formData = {
					data: new FormData(form),
					processData: false,
					contentType: false,
				};
				$(form).find('input[type="file"][name]').each(function(){
					formData.data.append( $(this).attr('name'), $(this)[0].files[0]);
				});
			} else {
				formData = {
					data: $(form).serialize(),
					processData: true,
					contentType: false,
				};
			}

			$.ajax({
				url: $(form).attr('action'),
				type: $(form).attr('method'),
				data: formData.data,
				processData: formData.processData,
				contentType: formData.contentType,
				success: function(data,status,xhr){

					// alert('Success from scripts.js! form sent! 778899');

					$(form).trigger('reset');
					$(form).find(':input').trigger('change');

					$(form).find('#submit_working').addClass('hide');
					$(form).find('#submit').removeClass('hide');

                    $(form).find('#age-splash2').addClass('hide'); // Hide step 2

					//$(form).find('.thank-you').html('<h3 class="text-center text-uppercase">THANK YOU FOR ENTERING!</h3><p class="text-center">Your claim will be validated within 2 business days and you will receive an email letting you know if it has been approved.</p>');
					$(form).find('#tt55').html('<h3 class="text-center text-uppercase">THANK YOU FOR ENTERING!</h3><p class="text-center">Your claim will be validated within 2 business days and you will receive an email letting you know if it has been approved.</p>');
					$(form).find('.thank-you').hide().removeClass('hide').removeClass('padding-bottom').addClass('padding-top--2x').fadeIn(300);

					// $(window).scrollTop(0);

                    $('#age-splash3').removeClass('hide'); // Step 3 show
                    $('#age-splash1').addClass('hide'); // step 2 hide
				},
				error: function(xhr,status,error){
					$(form).find('#submit_working').addClass('hide');

                    //alert('Error from scripts.js! form sent! 56843');

					if(xhr.responseJSON.errors) {

						var validator = $(form).validate();
						var showErrors = {};

						$.each(xhr.responseJSON.errors, function(name, errors) {
							if( !$(form).find('[name="'+name+'"]').is(':hidden') ) {
								showErrors[name] = errors[0];
							}
						});

						validator.showErrors(showErrors);

						$('html,body').animate({
							scrollTop: $(form).find(':input.error').closest('.row').offset().top
						},300);

					} else {
						alert('Oops: 9899! There seems to have been an error. Please try again.');
					}
				},
			});

			return false;
		}
	});


	// Tooltips
	$('.tooltipster').tooltipster({
		delay: 50,
		theme: 'tooltipster-light'
	});

	// Mobile input fields
	// $('input.aumobile').on('change keyup keydown',function(){
	// 	var v = $(this).val();
	// 	v = v.replace(/[^0-9]+/,'');
	// 	$(this).val(v);
	// });
	$('input.aumobile').mask( '0499 999 999',{placeholder:" ",autoclear: false} );
	// $('input.nzmobile').mask( '0299 999 999' );

	// Wizard
	$('#jsform .wizard-next').on('click',function(e){
		e.preventDefault();
		var _this = $(this);

		_this.addClass('hide');
		_this.next('.button').removeClass('hide');

		if( _this.closest('.wizard-step').find(':input').valid() ) {
			if( _this.has('[data-function]') ) {
				switch ( _this.attr('data-function') ) {
					case 'voucher_code':
						$.ajax({
							url: '/web/api/v2/voucher/'+$('#jsform .voucher_code').val(),
							success: function(data,status,xhr){
								$(window).scrollTop(0);
								_this.closest('.wizard-step').next('.wizard-step').find('.venue-name').text(data.venue.meta.venue_name);
								_this.closest('.wizard-step').hide().next('.wizard-step').fadeIn(300);
								$('#left-col').addClass('show-for-large');
							},
							error: function(xhr,error){

								var validator = $('#jsform').validate();
								var showErrors = {
									'code': 'Invalid voucher code'
								};

								validator.showErrors(showErrors);

								_this.removeClass('hide');
								_this.next('.button').addClass('hide');
							}
						});
						break;
				}
			} else {
				if( _this.closest('.wizard-step').next('.wizard-step').length ){
					$(window).scrollTop(0);
					_this.closest('.wizard-step').hide().next('.wizard-step').fadeIn(300);
				} else {
					_this.closest('form').submit();
				}
			}
		} else {
			_this.removeClass('hide');
			_this.next('.button').addClass('hide');
		}
	});

	$('#jsform input[type="file"]').on('change',function(){
		var input = this;
		if (input.files && input.files[0]) {
			var path = input.value;
		    var filename = path.substring((path.indexOf('\\') >= 0 ? path.lastIndexOf('\\') : path.lastIndexOf('/')));
		    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
		        filename = filename.substring(1);
		    }
			var ext = filename.substring(filename.lastIndexOf('.'));
			filename = filename.split('.').slice(0, -1).join('.');

			$(input).prev('label').html('<span class="filename">'+filename+'</span><span class="fileext">'+ext+'</span>');
	    } else {
	    	$(input).prev('label').html( $(input).attr('placeholder')||'Choose file' );
	    }
	});

	$('#jsform input[data-datepicker]').each(function(){
		$(this).datepicker({
			startDate: $(this).attr('data-startdate') || null,
			endDate: $(this).attr('data-enddate') || null,
			offset: 5,
			format: 'dd-mm-yyyy',
		}).on('keyup keydown',function(e){
			e.preventDefault();
		});
	});



	// Cntact form
	$('#contact-form').validate({
		ignore: ':hidden:not(input[type="file"]):not(input[type="radio"]):not(input[type="checkbox"])',
		errorPlacement: function(error, element) {
			if(element.hasClass('input-group-field')){
				error.insertAfter(element.closest('.input-group'));
			} else if(element.is('[type="checkbox"]')){
				error.insertAfter(element.closest('.checkbox'));
			} else {
				error.insertAfter(element);
			}
		},
		submitHandler: function(form) {
			$(form).find('#submit_working').removeClass('hide');
			$(form).find('#submit').addClass('hide');

			$.ajax({
				url: $(form).attr('action'),
				type: $(form).attr('method'),
				data: $(form).serialize(),
				success: function(data,status,xhr){
					$(form).trigger('reset').prop('disabled',true);
					$(form).find(':input').trigger('change');

					var msg = data.message||'<p>Thank you, your enquiry has been submitted successfully.</p>';
					$(form).find('.thank-you').removeClass('hide').html(msg);
				},
				error: function(xhr,status,error){
					if(xhr.responseJSON.errors) {

						var validator = $(form).validate();
						var showErrors = {};

						$.each(xhr.responseJSON.errors, function(name, errors) {
							showErrors[name] = errors[0];
						});

						validator.showErrors(showErrors);

					} else {
						alert('Oops! There seems to have been an error. Please try again.');
					}
				},
				complete: function(){
					$(form).find('#submit_working').addClass('hide');
					$(form).find('#submit').removeClass('hide');
				}
			});

			return false;
		}
	});


	// AddressFinder Autocomplete
	$('#jsform input[data-autocomplete-widget]').on("focus", function() {
	    var address = $(this).val();
	    $(this)
	        .data('address', address)
	        .removeClass('error')
	        .next('.error').remove();
	})
	.on("blur", function() {
	    var address = $(this).val();
	    if (!address) {
	    	var target = $(this).attr('data-autocomplete-widget');
	    	$('#jsform #'+target+'_line_1').val('');
	    	$('#jsform #'+target+'_line_2').val('');
	    	$('#jsform #'+target+'_suburb').val('');
	    	$('#jsform #'+target+'_state').val('');
	    	$('#jsform #'+target+'_postcode').val('');
	    } else if (address !== $(this).data('address')) {
	    	$(this).val($(this).data('address'));
	    }
	});

 	var initAddressFinder = function() {
 		$('input[data-autocomplete-widget]').each(function(){
	 		var target = $(this).attr('data-autocomplete-widget');
	 		var _this = $(this)[0];
	 		var country = $(this).attr('data-country')||'AU';

	 		var widget = new AddressFinder.Widget(
	 			_this,
	 			'GNECFVWJAK6XTDBQH839',
	 			country, {
	                "address_params": {},
	                "empty_content": "No addresses were found."
	            }
            );

            widget.on('address:select', function(fullAddress, metaData) {
            	if(country=='AU') {
		            document.getElementById(target+'_line_1').value = metaData.address_line_1;
		            document.getElementById(target+'_line_2').value = metaData.address_line_2||'';
		            document.getElementById(target+'_suburb').value = metaData.locality_name;
		            document.getElementById(target+'_state').value = metaData.state_territory;
		            document.getElementById(target+'_postcode').value = metaData.postcode;
		        } else {
		        	document.getElementById(target+'_line_1').value = metaData.address_line_1;
		            document.getElementById(target+'_line_2').value = metaData.address_line_2||'';
		            document.getElementById(target+'_suburb').value = metaData.selected_suburb||metaData.selected_city;
		            document.getElementById(target+'_state').value = metaData.selected_city||metaData.selected_suburb;
		            document.getElementById(target+'_postcode').value = metaData.postcode;
		        }
	        });
	 	});
 	};

 	var downloadAddressFinder = function() {
        var script = document.createElement('script');
        script.src = 'https://api.addressfinder.io/assets/v3/widget.js';
        script.async = true;
        script.onload = initAddressFinder;
        document.body.appendChild(script);
    };

    if( $('#jsform input[data-autocomplete-widget]').length ) {
    	downloadAddressFinder();
    }

    // $.mask.definitions['+'] = "[0-9]{1,99}";

    $(":input[data-inputmask]").each(function(){
    	$(this).mask( $(this).attr('data-inputmask') );
    });

});