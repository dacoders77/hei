(function($){

    //alert('campaign.js');

    function getAge(birthDateString) {
        var today = new Date();
        var birthDate = new Date(birthDateString);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    // // Age number fixes
    $('#age-form .inputs input').autotab({
        format: 'all',
        nospace: true,
    });

    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        $('#age-form .input.hide').removeClass('hide');
    }

    $('#age-form #ym').on('input change',function(){

        function padDigits(digits, number) {
            return Array(Math.max(digits - String(number).length + 1, 0)).join(0) + number;
        }

        var v = $(this).val();
        var d = new Date( v );

        if(!v || !d.valueOf()) return false;

        year = padDigits(4, d.getFullYear()).split('');
        month = padDigits(2, d.getMonth() + 1).split('');
        day = padDigits(2, d.getDate()).split('');

        // console.log($(this).val());
        // console.log(year);

        for(let i = 0; i < day.length; i++){
            $('#age-form').find('#d'+(i+1)).val(day[i]).trigger('change');
        }

        for(let i = 0; i < month.length; i++){
            $('#age-form').find('#m'+(i+1)).val(month[i]).trigger('change');
        }

        for(let i = 0; i < year.length; i++){
            $('#age-form').find('#y'+(i+1)).val(year[i]).trigger('change');
        }
    });

    // Age Restriction
    $('#age-form').validate({
        focusInvalid: false,
        errorPlacement: function(error, element) {},
        submitHandler: function(form) {

            $(form).find('.input-wrap').removeClass('error');
            $(form).find('.age-error').remove();

            var day = [
                    $(form).find('#d1').val(),
                    $(form).find('#d2').val(),
                ];

            var month = [
                    $(form).find('#m1').val(),
                    $(form).find('#m2').val(),
                ];

            var year = [
                    $(form).find('#y1').val(),
                    $(form).find('#y2').val(),
                    $(form).find('#y3').val(),
                    $(form).find('#y4').val(),
                ];

            var age = 18;
            var mydate = new Date();
            mydate.setFullYear(year.join(''), Number(month.join(''))-1, day.join(''));

            if ( getAge(mydate) < 18 ){
                $(form).find('.input-wrap').after('<div class="error age-error text-center">Sorry, only persons over the age of 18 may enter this site</div>')
                $(form).find('.button').addClass('shake animated');
                setTimeout(function(){
                    $(form).find('.button').removeClass('shake').removeClass('animated');
                },1000);
                return false;
            }

            $(form).hide();

            $('#jsform').find('[name="dob"]').val( day.join('') + '-' + month.join('') + '-' + year.join('')).trigger('change');

            document.activeElement.blur();
            $("input, select").blur();

            $('#age-splash').hide().next('.page-wrapper').hide().removeClass('hide').fadeIn(300);
            return false;
        },
        invalidHandler: function(e) {
            var form = $(this);
            form.find('.button').addClass('shake animated');
            setTimeout(function(){
                form.find('.button').removeClass('shake').removeClass('animated');
            },1000);
        }
    });


    // Generated form submit click
    $("#jsform").submit(function( event ) {
        //alert( "Handler for .submit() called. campaign.js" );


        // http://hei.kk/submit/1
        /*$.ajax({
            //url: $(form).attr('action'),
            //type: $(form).attr('method'),
            url: 'http://hei.kk/submit/1',
            type: 'post',
            success: function(data,status,xhr){
                alert('gggg campaign.js');
            },
            error: function(xhr,status,error){
                alert('campaign.js ERROR in ajax send gen form');
                console.log(error);
            }
        });*/

        //event.preventDefault();

    });

    $('#voucher-form').on('input change keyup keydown','#voucher_code.code-error',function(){
        $(this).attr('placeholder','Enter code here').removeClass('code-error').removeClass('error');
    });

    $('#voucher-form').validate({
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {

            alert('submit claim clicked campaign.js')

            $(form).find('#submit_working').removeClass('hide');
            $(form).find('#submit').addClass('hide');

            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method'),
                success: function(data,status,xhr){
                    alert('gggg6534 campaign.js');
                },
                error: function(xhr,status,error){

                    console.log('gggg6534 error campaign.js');

                    $(form).find('#submit_working').addClass('hide');
                    $(form).find('#submit').removeClass('hide');

                    xhr.responseJSON.errors = {
                        voucher_code: ['']
                    };

                    if(xhr.responseJSON.errors) {

                        // var validator = $(form).validate();
                        // var showErrors = {
                        //     voucher_code: ''
                        // };

                        // validator.showErrors(showErrors);

                        $(form).find('#voucher_code').addClass('error code-error').attr('placeholder','Invalid code. Please check and try again.').val('');

                    } else {
                        alert('Oops 555! There seems to have been an error. Please try again.');
                    }
                }
            });

            return false;
        }
    });

    $('#jsform .autocomplete-hidden-wrapper').after('<div class="mandatory-fields">* Mandatory fields</div>');

    $('#jsform').find('p.help-text').each(function(){
        var text = $(this).text();
        $(this).parent().find('>label').first().append('<span class="help-text" title="'+text+'"><i class="fa fa-question" aria-hidden="true"></i></span>');
        $(this).remove();
    });

    $('span.help-text').tooltipster({
        delay: 50,
        theme: 'tooltipster-dark'
    });

    // $('#jsform .thank-you').closest('.row').remove();

    // $('#jsform > .row').eq(7).wrap('<div class="row"><div class="columns small-12 large-7"></div></div>');

    // $('#jsform .form-submit').appendTo($('#jsform > .row').eq(7)).wrap('<div class="columns small-12 large-5 text-right"></div>');


    // $('#jsform #invoice_total').on('focus',function(){
    //     var v = $(this).val().trim();
    //     if(v==''||v==undefined) {
    //         $(this).val('$ ');
    //     }
    // });

    // $('#jsform #invoice_total').on('blur',function(){
    //     var v = $(this).val().trim();
    //     if(v=='$') {
    //         $(this).val('');
    //     }
    // });

    // $('#jsform #invoice_total').on('keydown keyup change input',function(){
    //     var v = $(this).val().trim().replace(/[^0-9\.]/gi,'').replace(/\.([0-9]{1,2}).*$/gi,'.$1');
    //     v = '$ '+v.trim();
    //     if(v=='$'||v==''||v==undefined) {
    //         $(this).val('$ ');
    //     } else {
    //         $(this).val(v);
    //     }
    // });

    // $('#jsform #phone').on('focus',function(){
    //     var v = $(this).val().trim();
    //     if(v==''||v==undefined) {
    //         $(this).val('04');
    //     }
    // });

    // $('#jsform #phone').on('blur',function(){
    //     var v = $(this).val().trim();
    //     if(v=='04'||v=='0') {
    //         $(this).val('');
    //     }
    // });

    // $('#jsform #phone').mask( '0499999999' );


    // AddressFinder Autocomplete
    $('#redeem-form input[data-autocomplete-widget]').on("focus", function() {
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

    if( $('#redeem-form input[data-autocomplete-widget]').length ) {
        downloadAddressFinder();
    }

    $('#redeem-form .autocomplete-toggle-wrapper input[type="checkbox"]').on('change',function(){

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

    $('#redeem-form').validate({
        ignore: ':hidden:not(input[type="file"]):not(input[type="radio"]):not(input[type="checkbox"])',
        errorPlacement: function(error, element) {
            if(element.hasClass('input-group-field')){
                error.insertAfter(element.closest('.input-group'));
            } else if(element.is('[type="checkbox"]')){
                error.insertAfter(element.closest('.checkbox-group'));
            } else if(element.is('[type="radio"]')){
                error.insertAfter(element.closest('.radio-group'));
            } else if(element.is('[class*="select2"]')){
                error.insertAfter(element.next('.select2'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {

            alert('submit handlr 22 campaignюjs');

            $(form).find('#submit_working').removeClass('hide');
            $(form).find('#submit').addClass('hide');

            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method'),
                data: $(form).serialize(),
                success: function(data,status,xhr){
                    $(form).trigger('reset');
                    $(form).find(':input').trigger('change');

                    $(form).replaceWith('<div class="text-center text-uppercase"><h3>Thank you<br/>you\'re prize will be with you soon.</h3></div>');
                },
                error: function(xhr,status,error){

                    alert('Form button send err campaign.js');

                    $(form).find('#submit_working').addClass('hide');
                    $(form).find('#submit').removeClass('hide');

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
                        alert('Oops778! There seems to have been an error. Please try again.');
                    }
                },
            });

            return false;
        }
    });


    // $('#jsform').find('.medium-6').removeClass('small-12').addClass('small-6');

    // $('#jsform').find('#address_suburb,#address_state').parent().unwrap().wrapAll('<div class="row"></div>');
    // $('#jsform').find('#address_suburb').parent().addClass('col-xs-12 col-sm-6 small-12 medium-6');
    // $('#jsform').find('#address_state,#address_postcode').parent().addClass('col-xs-12 col-sm-6 small-12 medium-6');

    // $('#jsform').find(':input[name="destination"]').each(function(){
    //     var slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/gi,'-');
    //     $(this).closest('.radio').addClass('margin-bottom-1');
    //     $(this).parent().addClass(
    //         'destination-' + slug
    //     ).append('<span class="destination-icon"></span>').after('<a href="/map/'+slug+'" target="_blank" class="destination-link">LEARN MORE</a>');
    // });

    // $('#jsform').find('label[for=product]').append('<span class="required_hint">*</span>');
    // $('#jsform').find('#product').prop('required',true).after('<div class="checkbox"><label for="product-other"><input id="product-other" type="checkbox"><span class="check"></span>Can\'t find your product?</label></div>');
    // $('#jsform').find('#product-other').on('change',function(){
    //     $('#jsform').find('#product').val('').prop('disabled',$(this).prop('checked')).prop('required',!$(this).prop('checked')).trigger('change');
    // });

    // $('#product').select2().on("select2:select", function(e) {
    //     $('#product').valid();
    // });

    // $('#jsform .thank-you').closest('.row').remove();

    // $('#jsform .form-submit > .small-12').addClass('text-right margin-top-1').after('<div class="small-12 xlarge-7"><div class="thank-you"></div></div>');

    // $('#contact-form .thank-you').closest('.row').remove();

    // $('#contact-form .form-submit > .small-12').addClass('xlarge-5').after('<div class="small-12 xlarge-7"><div class="thank-you"></div></div>');

    // var contentMinHeight = function() {
    //     $('section.main-content').css({
    //         'min-height': 'calc(100vh - ' + $('.main-header').outerHeight() + 'px)'
    //     });

    //     $('body[class*="page--map-"] section.main-content > .info-wrapper').css({
    //         'min-height': 'calc(100vh - ' + ( $('.main-header').outerHeight() + $('.info-footer').outerHeight() ) + 'px)'
    //     });

    //     $('body.page--map section.main-content > .map-wrapper').css({
    //         'min-height': 'calc(100vh - ' + $('.main-header').outerHeight() + 'px)'
    //     });
    // };
    // contentMinHeight();

    // $(window).on('load resize',function(){
    //     contentMinHeight();
    // });

    // $('.hamburger').on('click',function(){
    //     $('.mobile-menu').stop().fadeToggle(100);
    // });

})(jQuery);