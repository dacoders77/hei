// App
(function($){

	// Get Parameters from string
	var getParamsFromString = function(a) {
		if (a == "") a = window.location.search;
		a = a.substr(1).split('&');
	    if (a == "") return {};
	    var b = {};
	    for (var i = 0; i < a.length; ++i)
	    {
	        var p=a[i].split('=', 2);
	        if (p.length == 1)
	            b[p[0]] = "";
	        else
	            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
	    }
	    return b;
	};

	// Set AJAX Header with csrf token
	var AjaxTokenSetup = function(){

		$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': csrf_token
	        }
		});

		$( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
			if(window.ajaxDebug) console.log([event, jqxhr, settings, thrownError]);
		});

	}

	// Open parent menu items
	var setupSidebar = function(){

		$('.main-sidebar .treeview-menu li.active').closest('li.treeview').addClass('active').addClass('menu-open');

	}

	// Init tables list
	var DataTables = function(){

		$('table[data-table="basic"]').each(function(){
			var _this = $(this);
			var table = _this.DataTable({
				'dom': (_this.attr('data-filters')?'<"row"<"col-sm-12"t>><"row"<"col-sm-5"i><"col-sm-7"p>>':''),
				'paging'      : _this.attr('data-paging') || true,
				'lengthChange': _this.attr('data-lengthChange') || true,
				'searching'   : _this.attr('data-searching') || true,
				'ordering'    : _this.attr('data-ordering') || true,
				'info'        : _this.attr('data-info') || true,
				'autoWidth'   : _this.attr('data-autoWidth') || false,
				"order": [[ _this.attr('data-orderIndex') || 1, _this.attr('data-orderASC') || "desc" ]],
				'columnDefs'  : [ { "targets": "no-sort", "orderable": false } ],
				'initComplete': function(){
					var dataTable = this;

					if(_this.attr('data-filters')) {


						$( _this.attr('data-filters') ).find('input[data-type],select[data-type]').on('change input keydown',function(){
							switch ( $(this).attr('data-type') ) {
								case 'lengthChange':
									dataTable.api().page.len( $(this).val() ).draw();

									var hash = $( _this.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
					                history.replaceState(null,null,'#'+hash);
									break;

								case 'lengthRange':
									var val = $(this).val();
									var colIndex = $(this).attr('data-col'),
										column = dataTable.api().column(colIndex);

									switch (val) {
										case String(val.match(/7days/)):
										case String(val.match(/30days/)):
											console.log('Log message');
											var length = /7days/.test(val) ? 7 : 30;
											var start = new Date();
											var dates = {};
											for(let n = 0; n < length; n++){
												var date = new Date(new Date().setDate(new Date().getDate() - n));
												var yyyy = date.getFullYear().toString();
												var mm = (date.getMonth()+1).toString();
												var dd = date.getDate().toString();

												if( !dates[yyyy] ) dates[yyyy] = {};
												if( !dates[yyyy][mm] ) dates[yyyy][mm] = [];
												dates[yyyy][mm][n] = dd;
											}
											var regex = [];
											$.each(dates,function(year, months) {
												$.each(months,function(month, days) {
													regex.push( '^0?(' +days.join('|')+ ')\\/0?(' +month+ ')\\/' +year );
												});
											});
											val = regex.join('|');
											break;
										default:
											val = '\\d{2}\\/'+val;
											break;
									}

									column.search( val, true ).draw();

									var hash = $( _this.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
					                history.replaceState(null,null,'#'+hash);

									break;
							}
						});

						$( _this.attr('data-filters') ).find('input[data-col],select[data-col]').not('[data-type]').on('change input keydown',function(){

							var colIndex = $(this).attr('data-col'),
								column = dataTable.api().column(colIndex);

							if( $(this).is('[type="checkbox"]') ){
	                        	var val = $.fn.dataTable.util.escapeRegex( $(this).is(':checked') ? $(this).val() : '' );
	                        } else {
	                        	var val = $.fn.dataTable.util.escapeRegex( $(this).val() );
	                        }

			                column.search( val ).draw();

							var hash = $( _this.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
			                history.replaceState(null,null,'#'+hash);

						});

						var params = getParamsFromString( window.location.hash );

						$.each(params,function(k,v){
							$( _this.attr('data-filters') ).find('[name="'+k+'"]').val(v).trigger('change');
						});
					}
				}
			});
	    });

	    $('table[data-table="ajax"]').each(function(){
			var _this = $(this);
			var columnNames = function(){
				var names = [];
				_this.find('thead th').each(function(){
					names.push( { name: $(this).attr('data-name') } );
				});
				return names;
			}
			var columnDefaults = function(){
				var cols = new Array( _this.find('thead th').length );

				$( _this.attr('data-filters') ).find('[data-name]').each(function(){
					var name = $(this).attr('data-name');
					var i = _this.find('thead th[data-name="'+name+'"]').index();
					$(this).attr('data-col',i);
					var v = null;
					if( $(this).is('[type="checkbox"],[type="radio"]') && $(this).is(':checked') || !$(this).is('[type="checkbox"],[type="radio"]') ) {
						v = $(this).val() || $(this).attr('value');
					} else if( !$(this).is('[type="checkbox"],[type="radio"]') ) {
						v = $(this).val() || $(this).attr('value');
					}
					cols[i] = { 'search' : v };
				});

				return cols;
			}
			var table = _this.DataTable({
				"processing": false,
				"serverSide": true,
				'dom': (_this.attr('data-filters')?'<"table-responsive"t><"row"<"col-sm-5"i><"col-sm-7"p>>':''),
				'ajax'		  : {
					'url': _this.attr('data-url'),
					'data': function(data) {
						if(_this.data('loaded')) return;

						var params = getParamsFromString( window.location.hash );

						$.each(params,function(k,v){
							var filter =  $( _this.attr('data-filters') ).find('[name="'+k+'"]');

							if( filter.is('[data-type]') ) {
								switch ( filter.attr('data-type') ) {
									case 'lengthChange':
										data.length = v;
										break;
									case 'lengthRange':
										var colIndex = $( _this.attr('data-filters') ).find('[name="'+k+'"]').attr('data-col');
										if( data.columns[colIndex] ) data.columns[colIndex].search.value = v;
										break;
								}
							} else {
								var colIndex = $( _this.attr('data-filters') ).find('[name="'+k+'"]').attr('data-col');
								if( data.columns[colIndex] ) data.columns[colIndex].search.value = v;
							}

							if(filter.is('[type="checkbox"],[type="radio"]')){
								filter.filter('[value="'+v+'"]').prop('checked',1);
							} else {
								filter.val(v);
							}
						});

						return data;
					},
					complete: function(){
						InitTooltips();
					}
				},
				'paging'      : _this.attr('data-paging') || true,
				'lengthChange': _this.attr('data-lengthChange') || true,
				'searching'   : _this.attr('data-searching') || true,
				'ordering'    : _this.attr('data-ordering') || true,
				'info'        : _this.attr('data-info') || true,
				'autoWidth'   : _this.attr('data-autoWidth') || false,
				"order": [[ _this.attr('data-orderIndex') || 1, _this.attr('data-orderASC') || "desc" ]],
				'columnDefs'  : [ { "targets": "no-sort", "orderable": false } ],
				'columns' : columnNames(),
				'searchCols': columnDefaults(),
				'initComplete': function(){
					var dataTable = this;
					_this.data('loaded',true);

					if(dataTable.attr('data-filters')) {

						$( dataTable.attr('data-filters') ).find('input[data-type],select[data-type]').on('change keydown',function(e){

							if (e.originalEvent && e.originalEvent.key && e.originalEvent.key.length > 1) {
								return;
							}

							if(dataTable.DataTable().context[0].jqXHR) {
								dataTable.DataTable().context[0].jqXHR.abort();
							}

							switch ( $(this).attr('data-type') ) {
								case 'lengthChange':
									dataTable.api().page.len( $(this).val() ).draw();

									var hash = $( _this.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
					                history.replaceState(null,null,'#'+hash);
									break;

								case 'lengthRange':
									var val = $(this).val();
									var colIndex = $(this).attr('data-col'),
										column = dataTable.api().column(colIndex);

									switch (val) {
										case String(val.match(/7days/)):
										case String(val.match(/30days/)):
											var length = /7days/.test(val) ? 7 : 30;
											var start = new Date();
											var dates = {};
											for(let n = 0; n < length; n++){
												var date = new Date(new Date().setDate(new Date().getDate() - n));
												var yyyy = date.getFullYear().toString();
												var mm = (date.getMonth()+1).toString();
												var dd = date.getDate().toString();

												if( !dates[yyyy] ) dates[yyyy] = {};
												if( !dates[yyyy][mm] ) dates[yyyy][mm] = [];
												dates[yyyy][mm][n] = dd;
											}
											var regex = [];
											$.each(dates,function(year, months) {
												$.each(months,function(month, days) {
													regex.push( '^0?(' +days.join('|')+ ')\\/0?(' +month+ ')\\/' +year );
												});
											});
											val = regex.join('|');
											break;
										default:
											val = '\\d{2}\\/'+val;
											break;
									}

									column.search( val, true ).draw();

									var hash = $( dataTable.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
					                history.replaceState(null,null,'#'+hash);

									break;
							}
						});

						$( dataTable.attr('data-filters') ).find('input[data-col],select[data-col]').not('[data-type]').on('change keydown',function(e){

							if (e.type == 'keydown' && e.originalEvent && e.originalEvent.key && e.originalEvent.keyCode !== 8 && e.originalEvent.key.length > 1) {
								return;
							}

							var _this = $(this);

							if(_this.is('select') && e.type !== 'change') return;


							if(dataTable.DataTable().context[0].jqXHR) {
								dataTable.DataTable().context[0].jqXHR.abort();
							}

							var _this = $(this);

							if(_this.is('select') && e.type !== 'change') return;
							if(_this.is('[type="text"]') && e.type !== 'keydown') return;
							// if(_this.is('[type="checkbox"],[type="radio"]') && e.type !== 'change') return;
							// if(!_this.is('[type="checkbox"],[type="radio"]') && e.type !== 'change') return;

							if( window.dataTableUpdate ) clearTimeout(window.dataTableUpdate);

							window.dataTableUpdate = setTimeout(function(){

								var colIndex = _this.attr('data-col'),
									column = dataTable.api().column(colIndex);

								if( _this.is('[type="checkbox"]') ){
		                        	var val = _this.is(':checked') ? _this.val() : '';
		                        } else if( _this.is('select') ){
		                        	var val = _this.val();
		                        } else {
		                        	var val = _this.val() || _this.attr('value') || '';
		                        }

				                column.search( val ).draw();

								var hash = $( dataTable.attr('data-filters') ).find(':input:not(.filter-ignore)').serialize();
				                history.replaceState(null,null,'#'+hash);

				            }, 300);

						});
					}

					dataTable.on('processing.dt', function (e, settings, processing) {
						if(processing) {
							dataTable.find('tbody').html('<tr><td colspan="'+columnNames().length+'" style="padding:0;"><div class="progress active" style="margin:0;"><div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span>Loading...</span></div></div></td></tr>');
						}
					});
				}
			});
		});

	}

	// Setup Datepikers
	var DatePickers = function(){
		$('.datepicker').datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy'
		});

		$('.btn-clear').on('click',function(){
			var id = $(this).attr('data-id');
			$('#'+id).val('').trigger('change');
		});
	}

	// Setup CKEditor WYWIWYG
	var InitCKEditor = function(){

		$('textarea.editor').each(function(){
	  		var id = $(this).attr('id');
	  		CKEDITOR.replace(id, {
	  			toolbarGroups: [
					{ name: 'styles', groups: [ 'styles' ] },
					{ name: 'colors', groups: [ 'colors' ] },
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
					{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
					{ name: 'forms', groups: [ 'forms' ] },
					{ name: 'paragraph', groups: [ 'align', 'blocks', 'list', 'indent', 'bidi', 'paragraph' ] },
					{ name: 'links', groups: [ 'links' ] },
					{ name: 'insert', groups: [ 'insert' ] },
					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
					{ name: 'tools', groups: [ 'tools' ] },
					{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
					{ name: 'others', groups: [ 'others' ] },
					{ name: 'about', groups: [ 'about' ] }
				],

				removeButtons: 'Save,Templates,NewPage,Preview,Print,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,HiddenField,ImageButton,PasteFromWord,PasteText,Subscript,Superscript,Strike,CopyFormatting,RemoveFormat,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,Smiley,SpecialChar,PageBreak,Iframe,Maximize,About,BGColor,ShowBlocks',
				removeFormatAttributes: "lang,width,height,align,hspace,valign",

				extraPlugins: 'uploadfile,uploadwidget,justify',
				removePlugins: 'dragdrop,basket',
				filebrowserUploadUrl: fb_upload,
				filebrowserImageUploadUrl: fb_imageupload,
				filebrowserBrowseUrl: '/admin/files/browse?type=Files',
				filebrowserImageBrowseUrl: '/admin/files/browse?type=Images',
				filebrowserUploadMethod: 'form',
	  		});
	  	});

	}

	// File Upload for Media
	var UploadFile = function(){

		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();

		        reader.onload = function (e) {
		        	var id = $(input).attr('id');
		            $('#'+id+'-preview').attr('src', e.target.result);
		        }

		        reader.readAsDataURL(input.files[0]);
		    }
		}

		function CSVImportGetHeaders(input,modal) {
			$(modal).find('select').html('<option value="">- Select -</option>');

			if (input.files && input.files[0]) {
			    // Get our CSV file from upload
			    var file = input.files[0];

			    // Instantiate a new FileReader
			    var reader = new FileReader();

			    // Read our file to an ArrayBuffer
			    reader.readAsArrayBuffer(file);

			    // Handler for onloadend event.  Triggered each time the reading operation is completed (success or failure) 
			    reader.onloadend = function (evt) {
			        // Get the Array Buffer
			        var data = evt.target.result;

			        // Grab our byte length
			        var byteLength = data.byteLength;

			        // Convert to conventional array, so we can iterate though it
			        var ui8a = new Uint8Array(data, 0);

			        // Used to store each character that makes up CSV header
			        var headerString = '';

			        // Iterate through each character in our Array
			        for (var i = 0; i < byteLength; i++) {
			            // Get the character for the current iteration
			            var char = String.fromCharCode(ui8a[i]);

			            // Check if the char is a new line
			            if (char.match(/[^\r\n]+/g) !== null) {

			                // Not a new line so lets append it to our header string and keep processing
			                headerString += char;
			            } else {
			                // We found a new line character, stop processing
			                break;
			            }
			        }

			        // Split our header string into an array
			        var headers = headerString.split(',');

			        $(modal).find('select').each(function(e){
				    	var _select = $(this);
				    	_select.html('<option value="">- Select -</option>');
				    	$.each(headers,function(i,e){
				    		e = e.replace(/\"/g,'');
				    		var selected = _select.attr('name').endsWith(e);
				    		_select.append('<option value="'+e+'" '+(selected?'selected':'')+'>'+e+'</option>');
				    	});
				    });
				    $(modal).modal('show');
			    };
			}
		}

		$(document).on('change', '.btn-file :file', function() {
			var input = $(this),
				label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {
		    var input = $(this).parents('.input-group').find(':text'),
		        log = label;

		    if( input.length ) {
		        input.val(log);
		    } else {
		        if( log ) alert(log);
		    }
		});

		$(".show-preview").change(function(){
		    readURL(this);
		});

		$(".read-upvo").change(function(){
		    CSVImportGetHeaders(this,'#modal-upvo');
		});

		$("#sms_file_upload").change(function(){
		    CSVImportGetHeaders(this,'#modal-sms-upload');
		});

		$("#sms_file_upload_2").change(function(){
		    CSVImportGetHeaders(this,'#modal-sms-upload-2');
		});

		$("#eligible_file_upload").change(function(){
		    CSVImportGetHeaders(this,'#modal-eligible-upload');
		});

		$("#active_file_upload").change(function(){
		    CSVImportGetHeaders(this,'#modal-active-upload');
		});

		$("#tracking_codes").change(function(){
		    CSVImportGetHeaders(this,'#modal-tracking');
		});

		// $('#tracking_codes').on('hide.bs.modal', function () {
		// 	// var valid = true;
		// 	// $(this).find('select').each(function(){
		// 	// 	$(this).closest('.form-group').removeClass('has-error');
		// 	// 	if(!$(this).val()) {
		// 	// 		$(this).closest('.form-group').addClass('has-error');
		// 	// 		valid = false;
		// 	// 	}
		// 	// });
		//  //    return valid;
		// })

		$('.btn-clear[data-id]').on('click',function(){
			var id = $(this).attr('data-id');
			$('#'+id+'-preview').attr('src', '');
			$('input#'+id).val('');
			$(this).closest('.input-group').find(':text').val('');
		});

	};

	var CampaignFormBuilder = function(){
		var builderElem = document.getElementById('form_builder');
		var formContent = $('#form_content');

		var defaultFields = $(formContent).val() ? JSON.parse( $(formContent).val() ) : [{
                type: "text",
                label: "First name",
                className: "form-control",
                name: "first_name",
                required: true,
                wrapperColumns: 'col-xs-12 col-sm-6'
            }, {
                type: "text",
                label: "Last name",
                className: "form-control",
                name: "last_name",
                required: true,
                wrapperColumns: 'col-xs-12 col-sm-6'
            }];

		var formBuilder = $( builderElem ).formBuilder({
			showActionButtons: false,
			disableFields: ['autocomplete','button','header'],
			disabledFieldButtons: {
				row: ['edit'],
				recaptcha: ['edit']
			},
			disabledSubtypes: {
				text: ['password','color','email','tel']
			},
			disabledAttrs: ["access"],
			fields: [{
				label: "Email",
				type: "email",
				icon: "<i class=\"fa fa-envelope-o\"></i>",
			}, {
				label: "Phone",
				type: "tel",
				icon: "<i class=\"fa fa-phone\"></i>",
			}, {
				label: "Row break",
				type: "row",
				icon: "–",
			}, {
				label: "Conditional row",
				type: "crow",
				icon: "?",
			}, {
				label: "reCaptcha",
				type: "recaptcha",
				icon: "<i class=\"fa fa-google\"></i>",
			}, {
				label: "Autocomplete Address",
				type: "autocomplete-address",
				icon: "<i class=\"fa fa-map-marker\"></i>",
			}],
			typeUserAttrs: {
				paragraph: {
					subtype: {
						label: 'Wrapper',
						options: {
							'div': 'div',
							'p': 'p',
							'label': 'label',
							'h1': 'h1',
							'h2': 'h2',
							'h3': 'h3',
							'h4': 'h4',
							'h5': 'h5',
							'h6': 'h6',
						},
					},
				},
				email: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				tel: {
					className: {
						label: 'Class',
						value: 'form-control aumobile',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				text: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					},
					inputMask: {
						label: 'Input mask',
						value: '',
					},
				},
				textarea: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				number: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					},
				},
				'checkbox-group': {
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				'radio-group': {
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				date: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					datePicker: {
						label: 'Datepicker',
						options: {
							'false': 'No',
							'true': 'Yes'
						}
					},
					startDate: {
						label: 'Start Date',
						value: '',
						placeholder: 'DD-MM-YYYY',
					},
					endDate: {
						label: 'End Date',
						value: '',
						placeholder: 'DD-MM-YYYY',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				select: {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				file: {
					accept: {
						label: 'Accept',
						value: '',
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					}
				},
				'autocomplete-address': {
					className: {
						label: 'Class',
						value: 'form-control',
					},
					addressFinder: {
						label: 'AddressFinder',
						options: {
							'AU': 'AU',
							'NZ': 'NZ',
						}
					},
					wrapperColumns: {
						label: 'Columns',
						options: {
							'col-xs-12 small-12': 'Full width',
							'col-xs-12 col-sm-6 small-12 medium-6': 'Half width',
							'col-xs-12 col-sm-4 small-12 medium-4': 'Third width',
							'col-xs-12 col-sm-3 small-12 medium-3': 'Quarter width',
						}
					},
					label_street_1: {
						label: 'Address Label 1',
						value: '',
					},
					placeholder_street_1: {
						label: 'Address Placeholder 1',
						value: '',
					},
					label_street_2: {
						label: 'Address Label 2',
						value: '',
					},
					placeholder_street_2: {
						label: 'Address Placeholder 2',
						value: '',
					},
					label_suburb: {
						label: 'Suburb Label',
						value: '',
					},
					placeholder_suburb: {
						label: 'Suburb Placeholder',
						value: '',
					},
					label_state: {
						label: 'State Label',
						value: '',
					},
					placeholder_state: {
						label: 'State Placeholder',
						value: '',
					},
					label_postcode: {
						label: 'Postcode Label',
						value: '',
					},
					placeholder_postcode: {
						label: 'Postcode Placeholder',
						value: '',
					},
				},
				crow: {
					placeholder: {},
					description: {},
					className: {},
					label: {},
					name: {},
					value: {},
					conditions: {
						label: 'Conditions',
						value: '',
						help: 'xxx',
					}
				},
			},
			replaceFields: [{
				type: "text",
				label: "Textfield",
				icon: "<i class=\"fa fa-font\"></i>",
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Textarea',
				type: 'textarea',
				icon: '<i class="fa fa-edit"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				type: 'number',
				label: 'Number',
				icon: '<i class="fa fa-hashtag"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Checkbox group',
				type: 'checkbox-group',
				icon: '<i class="fa fa-check-square-o"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Radio group',
				type: 'radio-group',
				icon: '<i class="fa fa-dot-circle-o"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Date',
				type: 'date',
				icon: '<i class="fa fa-calendar"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'File',
				type: 'file',
				icon: '<i class="fa fa-file-o"></i>',
				accept: '',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Selectbox',
				type: 'select',
				icon: '<i class="fa fa-list-alt"></i>',
				wrapperColumns: 'col-xs-12 small-12',
			}, {
				label: 'Markup',
				type: 'paragraph',
				icon: '<i class="fa fa-paragraph"></i>',
			}],
			templates: {
				email: function(fieldData){
					return {
						field: '<input class="'+fieldData.className+'" name="'+fieldData.name+'" type="'+fieldData.type+'" id="'+fieldData.id+'">',
						layout: 'noLabel',
						onRender: function(){
							$('#'+this.id).closest('.form-field').addClass(fieldData.wrapperColumns)
						}
					}
				},
				tel: function(fieldData){
					return {
						field: '<input class="'+fieldData.className+'" name="'+fieldData.name+'" type="'+fieldData.type+'" id="'+fieldData.id+'">',
						layout: 'noLabel',
						onRender: function(){
							$('#'+this.id).closest('.form-field').addClass(fieldData.wrapperColumns)
						}
					}
				},
				row: function(fieldData){
					return {
						field: '<div class="col-xs-12">&nbsp;</div>',
						layout: 'noLabel',
						onRender: function(){
							$('.field-'+this.id).closest('.form-field').find('>label').replaceWith('<p class="text-muted text-center no-margin"><small>–––––– Row Break ––––––</small></p>');
							$('.field-'+this.id).closest('.form-field').find('>.prev-holder').remove();
						}
					}
				},
				crow: function(fieldData){
					return {
						field: '<div class="col-xs-12">&nbsp;</div>',
						layout: 'noLabel',
						onRender: function(){
							$('.field-'+this.id).closest('.form-field').find('>label').replaceWith('<p class="text-muted text-center no-margin"><small>–––––– Conditional Row ––––––</small></p>');
							$('.field-'+this.id).closest('.form-field').find('>.frm-holder>.form-elements>.form-group:not(.conditions-wrap):not(.required-wrap)').remove();
						}
					}
				},
				recaptcha: function(fieldData){
					return {
						field: '<div class="g-recaptcha"></div>',
						layout: 'noLabel',
						onRender: function(){
							if( $('.field-'+this.id).closest('.frmb.stage-wrap').find('li[type="recaptcha"]').length > 1 ) {
								$('.field-'+this.id).closest('.form-field').remove();
								alert('Only one reCaptcha is allowed.');
							} else {
								$('.field-'+this.id).closest('.form-field').find('>label').replaceWith('<img src="/assets/images/recaptcha_pl.png" alt="reCaptcha" />');
								$('.field-'+this.id).closest('.form-field').find('>.prev-holder').remove();
							}
						}
					}
				},
				'autocomplete-address': function(fieldData){
					return {
						field: '<input class="'+fieldData.className+'" name="'+fieldData.name+'" type="text" id="'+fieldData.id+'">',
						layout: 'noLabel',
						onRender: function(){
							$('#'+this.id).closest('.form-field').addClass(fieldData.wrapperColumns)
						}
					}
				}
			},
			defaultFields: defaultFields,
			inputSets: [{
				label: "First name / Last name",
				icon: "<i class=\"fa fa-user-o\"></i>",
				name: "user-details",
				showHeader: false,
				fields: [{
                    type: "text",
                    label: "First name",
                    className: "form-control",
                    name: "first_name",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }, {
                    type: "text",
                    label: "Last name",
                    className: "form-control",
                    name: "last_name",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }]
			},
			{
				label: "Email / Confirm Email",
				icon: "<i class=\"fa fa-envelope-o\"></i>",
				name: "email-confirm",
				showHeader: false,
				fields: [{
                    type: "email",
                    label: "Email Address",
                    className: "form-control",
                    name: "emai",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }, {
                    type: "email",
                    label: "Confirm Email Address",
                    className: "form-control",
                    name: "email_confirm",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }]
			},
			{
				label: "Address / Suburb / State / Postcode",
				icon: "<i class=\"fa fa-map-marker\"></i>",
				name: "address-fields",
				showHeader: false,
				fields: [{
                    type: "text",
                    label: "Street address line 1",
                    className: "form-control",
                    name: "address_line_1",
                    required: true,
                    wrapperColumns: 'col-xs-12 small-12'
                }, {
                    type: "row",
                    label: "Row break",
                }, {
                    type: "text",
                    label: "Street address line 2",
                    className: "form-control",
                    name: "address_line_2",
                    required: false,
                    wrapperColumns: 'col-xs-12 small-12'
                }, {
                    type: "row",
                    label: "Row break",
                }, {
                    type: "text",
                    label: "Suburb",
                    className: "form-control",
                    name: "address_suburb",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }, {
                    type: "select",
                    label: "State",
                    className: "form-control",
                    name: "address_state",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6',
                    "values": [
				    {
						value: "NSW",
						label: "New South Wales",
					},
					{
						value: "QLD",
						label: "Queensland",
					},
					{
						value: "SA",
						label: "South Australia",
					},
					{
						value: "TAS",
						label: "Tasmania",
					},
					{
						value: "VIC",
						label: "Victoria",
					},
					{
						value: "WA",
						label: "Western Australia",
					},
					{
						value: "ACT",
						label: "Australian Capital Territory",
					},
					{
						value: "NT",
						label: "Northern Territory",
					}]
                }, {
                    type: "row",
                    label: "Row break",
                }, {
                    type: "number",
                    label: "Postcode",
                    className: "form-control",
                    name: "address_postcode",
                    max: "9999",
                    min: "0",
                    step: "1",
                    required: true,
                    wrapperColumns: 'col-xs-12 col-sm-6 small-12 medium-6'
                }]
			}],
			controlOrder: [
				'text',
				'tel',
				'email',
				'number',
				'textarea',
				'select',
				'date',
				'file',
				'checkbox-group',
				'radio-group',
				'user-details',
				'autocomplete-address',
		    ],
		    layoutTemplates: {
		    	default: function(field, label, help, fieldData){
		    		setTimeout(function(){
						$(field).closest('.form-field')
							.removeClass('col-xs-12')
							.removeClass('col-sm-6')
							.removeClass('col-sm-4')
							.removeClass('col-sm-3')
							.addClass(fieldData.wrapperColumns);
					}, 100 );
		    		return field;
		    	}
		    },
		    onAddField: function(id,fieldData){
		    	$(window).trigger('updateFBFields', [ '#'+id, fieldData.wrapperColumns ] );
		    },
		    typeUserEvents: {
		    	text: {
		    		onadd: function(field){
		    			$('.fld-wrapperColumns', field).on('change',function(){
		    				$(window).trigger('updateFBFields', [ field, $(this).val() ] );
		    			});
		    		}
		    	}
		    }
		});

		$(window).on('updateFBFields',function(e,field,wrapperColumns){
			setTimeout(function(){
				$(field)
					.removeClass('col-xs-12')
					.removeClass('col-sm-6')
					.removeClass('col-sm-4')
					.removeClass('col-sm-3')
					.addClass(wrapperColumns);
			}, 100 );
		});


		$('[data-toggle="tab"][href="#form"]').on('click',function(){
			$( builderElem ).toggle().show();
		});

		$('#campaign_form').on('submit',function(e){
			if( !$(this).data('valid') ) {
				e.preventDefault();

				if(builderElem) {
					var data = JSON.stringify(JSON.parse(formBuilder.actions.getData('json', true)));
					$('#form_content').val(data).trigger('change');
				}

				$('#form_builder').find('[name]').attr('disabled',true);

				$(this).data('valid',true);
				$(this).submit();
			}
		});
	}


	var VenueEditButtons = function() {
		$('#modal-tracking .modal-confirm').on('click',function(e){

			var form = $('#modal-tracking form');
			var valid = true;

			$(form).find('select').each(function(){
				$(this).closest('.form-group').removeClass('has-error');
				if(!$(this).val()) {
					$(this).closest('.form-group').addClass('has-error');
					valid = false;
				}
			});
		    if( valid ) {
		    	form.submit();
		    } else {
		    	return false;
		    }
		});
	}


	var InitTooltips = function() {
		$('[data-toggle="tooltip"]').tooltip({
			container: 'body',
		});

		$(document).ready(function(){
		    $('[data-toggle="popover"]').popover();
		});

		$('[data-toggle="popover"]').on('click', function(e) {
			e.preventDefault();
		});
	}


	var ActionDeleteButton = function() {
		$(document).on('click','.delete-button',function(e){
			e.preventDefault();
			var href = {
				url: $(this).attr('href'),
				redirect: $(this).attr('data-redirect')
			};
			$('#modal-delete').data('href',href).modal('show');

		});

		$('#modal-delete .modal-confirm').on('click',function(e){

			var href = $('#modal-delete').data('href');

			var form = $('<form>').attr({
					'method' : 'POST',
					'action' : href.url,
				}),
				token = $('<input>').attr({
					'type' : 'hidden',
					'name' : '_token',
					'value' : csrf_token,
				}),
				method = $('<input>').attr({
					'type' : 'hidden',
					'name' : '_method',
					'value' : 'DELETE',
				})


			form.append( token ).append( method );

			form.appendTo('body').submit();
		});
	}

	var ViewSubmissionButton = function() {

		function buildHtmlTable(myList, selector) {
		  $(selector).html('');

		  var columns = addAllColumnHeaders(myList, selector);

		  for (var i = 0; i < myList.length; i++) {
		    var row$ = $('<tr/>');
		    for (var colIndex = 0; colIndex < columns.length; colIndex++) {
		      var cellValue = myList[i][columns[colIndex]];
		      if (cellValue == null) cellValue = "";
		      row$.append($('<td/>').html(cellValue));
		    }
		    $(selector).append(row$);
		  }

		  $(selector).find('>tr:first-child').wrap('<thead/>');
		  $(selector).find('>tr').wrapAll('<tbody/>');
		}

		function addAllColumnHeaders(myList, selector) {
		  var columnSet = [];
		  var headerTr$ = $('<tr/>');

		  for (var i = 0; i < myList.length; i++) {
		    var rowHash = myList[i];
		    for (var key in rowHash) {
		      if ($.inArray(key, columnSet) == -1) {
		        columnSet.push(key);
		        headerTr$.append($('<th/>').html(key));
		      }
		    }
		  }
		  $(selector).append(headerTr$);

		  return columnSet;
		}


		$('.view-submission').on('click',function(e){
			e.preventDefault();
			var href = $(this).attr('href');

			$.ajax({
				url: href,
				type: 'GET',
				data: {
					_token: csrf_token,
				},
				success: function(data, status, xhr){
					var data = JSON.parse(data);

					buildHtmlTable([data], '#table-view-submission');

					$('#modal-view').modal('show');
				}
			});

			// $('#modal-delete').data('href',href).modal('show');

		});
	};


	var UpdateSubmissionButtons = function() {
		$('#modal-reject .modal-confirm').on('click',function(e){

			if(!$('#modal-reject textarea').val()) {
				alert('Comment is required');
				return;
			}

			var href = $('#modal-reject').data('href');

			var form = $('<form>').attr({
					'method' : 'POST',
					'action' : href.url,
				}),
				token = $('<input>').attr({
					'type' : 'hidden',
					'name' : '_token',
					'value' : csrf_token,
				}),
				method = $('<input>').attr({
					'type' : 'hidden',
					'name' : '_method',
					'value' : 'PUT',
				}),
				id = $('<input>').attr({
					'type' : 'hidden',
					'name' : 'id',
					'value' : href.id,
				}),
				status = $('<input>').attr({
					'type' : 'hidden',
					'name' : 'status',
					'value' : '3',
				}),
				comment = $('<input>').attr({
					'type' : 'hidden',
					'name' : 'comment',
					'value' : $('#modal-reject textarea').val(),
				}),
				hash = $('<input>').attr({
					'type' : 'hidden',
					'name' : '_hash',
					'value' : window.location.hash,
				})


			form.append( token ).append( method ).append( id ).append( status ).append( comment ).append( hash );

			form.appendTo('body').submit();
		});
	}


	var DownloadSubmissions = function() {
		$('.download-submissions').on('click',function(e){
			e.preventDefault();
			var params = [];
			var table = $( $(this).attr('data-table') );


			$(this).closest('.filters').find(':input[data-col]').each(function(){
				var i = $(this).attr('data-col');
				var name = table.find('thead th').eq(i).attr('data-name');
				var a = $(this).serializeArray();

				if(a.length) {
					params.push( name + '=' + encodeURIComponent( a[0].value ) );
				}
			});
			window.location.href = $(this).attr('href')+'?'+params.join('&');
		});
	}


	BulkTrackingCodes = function() {
		$('#modal-tracking').modal('show');
	}

	UploadWinners = function() {
		$('#modal-winners-upload').modal('show');
	}

	// SubmitModal = function(el) {
	// 	$(el).closest('.modal').find('form .has-error').removeClass('has-error');
	// 	if( $(el).closest('.modal').find('form').valid() ) {
	// 		$(el).closest('.modal').find('form').submit();
	// 		// console.log( $(el).closest('.modal').find('form').serializeArray() );
	// 	} else {
	// 		$(el).closest('.modal').find('form .error').closest('.form-group').addClass('has-error');
	// 	}
	// }

	BulkDeliveryCSV = function(el) {
		$('#modal-delivery-upload').modal('show');
	}

	ModalMapCSV = function(input) {
		var modal = $(input).closest('.modal');

		if (input.files && input.files[0]) {
		    // Get our CSV file from upload
		    var file = input.files[0];

		    // Instantiate a new FileReader
		    var reader = new FileReader();

		    // Read our file to an ArrayBuffer
		    reader.readAsArrayBuffer(file);

		    // Handler for onloadend event.  Triggered each time the reading operation is completed (success or failure) 
		    reader.onloadend = function (evt) {
		        // Get the Array Buffer
		        var data = evt.target.result;

		        // Grab our byte length
		        var byteLength = data.byteLength;

		        // Convert to conventional array, so we can iterate though it
		        var ui8a = new Uint8Array(data, 0);

		        // Used to store each character that makes up CSV header
		        var headerString = '';

		        // Iterate through each character in our Array
		        for (var i = 0; i < byteLength; i++) {
		            // Get the character for the current iteration
		            var char = String.fromCharCode(ui8a[i]);

		            // Check if the char is a new line
		            if (char.match(/[^\r\n]+/g) !== null) {

		                // Not a new line so lets append it to our header string and keep processing
		                headerString += char;
		            } else {
		                // We found a new line character, stop processing
		                break;
		            }
		        }

		        // Split our header string into an array
		        var headers = headerString.split(',');

		        $(modal).find('select').each(function(e){
			    	var _select = $(this);
			    	_select.html('<option value="">- Select -</option>');
			    	$.each(headers,function(i,e){
			    		e = e.replace(/\"/g,'');
			    		var selected = _select.attr('name').endsWith(e);
			    		_select.append('<option value="'+e+'" '+(selected?'selected':'')+'>'+e+'</option>');
			    	});
			    });
		    };
		}
	}

	BulkSubmissionApprove = function(el){
		var ids;
		var href = {
			url: $(el).attr('data-href'),
		};

		if( $(el).attr('data-approve') == 'all' ) {
			ids = 'all';
		} else {
			ids = $( $(el).attr('data-table') ).find('[name="bulkcheck"]:checked').map(function(){
				return $(this).val();
			}).get();
		}

		if(!ids || !ids.length) return;

		var form = $('<form>').attr({
				'method' : 'POST',
				'action' : href.url,
			}),
			token = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_token',
				'value' : csrf_token,
			}),
			method = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_method',
				'value' : 'PUT',
			}),
			ids = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'ids',
				'value' : ids
			}),
			status = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'status',
				'value' : '2',
			}),
			comment = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'comment',
				'value' : '',
			}),
			hash = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_hash',
				'value' : window.location.hash,
			});

		form.append( token ).append( method ).append( ids ).append( status ).append( comment ).append( hash );

		form.appendTo('body').submit();
	}

	SubmissionApprove = function(el){
		var href = {
			url: $(el).attr('data-href'),
		};

		var id = $(el).attr('data-id');

		if(!id) return;

		var form = $('<form>').attr({
				'method' : 'POST',
				'action' : href.url,
			}),
			token = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_token',
				'value' : csrf_token,
			}),
			method = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_method',
				'value' : 'PUT',
			}),
			ids = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'ids',
				'value' : id
			}),
			status = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'status',
				'value' : '2',
			}),
			comment = $('<input>').attr({
				'type' : 'hidden',
				'name' : 'comment',
				'value' : '',
			}),
			hash = $('<input>').attr({
				'type' : 'hidden',
				'name' : '_hash',
				'value' : window.location.hash,
			});

		form.append( token ).append( method ).append( ids ).append( status ).append( comment ).append( hash );

		form.appendTo('body').submit();
	}

	// SubmissionReject = function(el){
	// 	var href = {
	// 		url: $(el).attr('data-href'),
	// 		id: $(el).attr('data-id'),
	// 	};

	// 	$('#modal-reject textarea').val('');

	// 	$('#modal-reject').data('href',href).modal('show');
	// }
	BulkSubmissionReject = function(el){
		var ids,s;

		if( $(el).attr('data-approve') == 'all' ) {
			ids = 'all';
			s = $(el).attr('data-status')||'1';
		} else {
			ids = $( $(el).attr('data-table') ).find('[name="bulkcheck"]:checked').map(function(){
				return $(this).val();
			}).get();
		}

		if(!ids || !ids.length) return;

		$(el).data('id',ids);
		$(el).data('s',s);

		SubmissionReject(el);
	}

	SubmissionReject = function(el){
		$('#modal-reject textarea').val('');
		$('#modal-reject [name="ids"]').val( $(el).data('id') );
		$('#modal-reject [name="status"]').val( $(el).data('s') );

		$(el).data({
			modal: '#modal-reject'
		});

		openModal(el);
	}

	openModal = function(el){
		var data = $(el).data();
		$(data.modal).data(data).modal('show');
	}

	submitModal = function(el){

		var form = $(el).closest('.modal').find('form');
		var valid = true;

		$(form).find(':input[required]').each(function(){
			$(this).closest('.form-group').removeClass('has-error');
			if(!$(this).val()) {
				$(this).closest('.form-group').addClass('has-error');
				valid = false;
			}
		});
	    if( valid ) {
	    	form.submit();
	    } else {
	    	return false;
	    }

	}

	openOCRModal = function(el){
		var data = $(el).data();
		$('#modal-ocr pre.ocr-data').text(data.ocr);
		$('#modal-ocr').modal('show');
	}



	// Load Functions
	AjaxTokenSetup();
	setupSidebar();
	DataTables();
	DatePickers();
	InitCKEditor();
	UploadFile();
	CampaignFormBuilder();
	VenueEditButtons();
	InitTooltips();
	ActionDeleteButton();
	ViewSubmissionButton();
	// UpdateSubmissionButtons();
	// BulkSubmissionApprove();

	DownloadSubmissions();


})(jQuery);