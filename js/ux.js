function save(page_name, key) {
	var clean_page_name = page_name.replace('/', '-');

	if(SirTrevor.onBeforeSubmit() == 0) {
		$('#page-'+clean_page_name+' .st-outer').each(function() {
			editorId = $(this).attr('id');
			trevor = SirTrevor.getInstance(editorId);
			$('#'+editorId+' textarea.form-control').val(trevor.$el.val());
		});

		var formData = $('#page-'+clean_page_name+' form').serializeArray();
		formData.push({
			name: "q",
			value: "save"
		});
		formData.push({
			name: "00key",
			value: key
		});
		formData.push({
			name: "00page",
			value: page_name
		});
		formData.push({
			name: "00pull",
			value: $('#page-'+clean_page_name).parent().attr('data-parentrecord')
		});
		formData.push({
			name: "00csrf",
			value: $('#csrf-key').parent().attr('data-csrf')
		});

		$.ajax(getAjaxEndpoint(), {
			type: "POST",
			data: formData,
			beforeSend: function(data) {
				$('#page-'+clean_page_name+' .form-status').hide();
				$('#page-'+clean_page_name+' .save-button').html('<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Saving...');
				$('#page-'+clean_page_name+' .save-button').attr('disabled', 'disabled');
				$('#page-'+clean_page_name+' .form-group').removeClass('has-error');
			},
			complete: function(data) {
				$('#page-'+clean_page_name+' .save-button').html('<span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save');
				$('#page-'+clean_page_name+' .save-button').removeAttr('disabled');
			},
			error: function(data) {
				message = data.responseJSON.message;

				if(data.responseJSON.widgets) {
					message += "\n\n";

					data.responseJSON.widgets.forEach(function(widget) {
						message += widget.reason + "\n";
						$('#page-'+clean_page_name+' #grp-'+widget.name).addClass('has-error');
					});
				}

				alert(message);
			},
			success: function(data) {
				console.log('#page-'+clean_page_name+' .form-status');
				$('#page-'+clean_page_name+' .form-status').html('<span class="glyphicon glyphicon-ok"></span> '+data);
				$('#page-'+clean_page_name+' .form-status').fadeIn('fast').delay(5000).fadeOut('slow');

				$('html, body').animate({
					scrollTop: $("#page-"+clean_page_name).offset().top
				}, 500);
			},
			cache: false
		});
	} else {
		alert("There is an error with your submission. Please scroll down and correct the highlighted errors.");
	}
}

function create(page_name) {
	var clean_page_name = page_name.replace('/', '-');

	if(SirTrevor.onBeforeSubmit() == 0) {
		$('#page-'+clean_page_name+' .st-outer').each(function() {
			editorId = $(this).attr('id');
			trevor = SirTrevor.getInstance(editorId);
			$('#'+editorId+' textarea.form-control').val(trevor.$el.val());
		});

		var formData = new FormData($('#page-'+clean_page_name+' form')[0]);
		formData.append('q', 'new');
		formData.append('00page', page_name);
		formData.append('00pull', $('#page-'+clean_page_name).parent().attr('data-parentrecord'));		
		formData.append('"00csrf"', $('#csrf-key').attr('data-csrf'));

		$.ajax(getAjaxEndpoint(), {
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			beforeSend: function(data) {
				$('#page-'+clean_page_name+' .form-status').hide();
				$('#page-'+clean_page_name+' .new-button').html('<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Creating...');
				$('#page-'+clean_page_name+' .new-button').attr('disabled', 'disabled');
				$('#page-'+clean_page_name+' .form-group').removeClass('has-error');
			},
			complete: function(data) {
				$('#page-'+clean_page_name+' .new-button').html('<span class="glyphicon glyphicon-saved" aria-hidden="true"></span> Create');
				$('#page-'+clean_page_name+' .new-button').removeAttr('disabled');
			},
			error: function(data) {
				message = data.responseJSON.message;

				if(data.responseJSON.widgets) {
					message += "\n\n";

					data.responseJSON.widgets.forEach(function(widget) {
						message += widget.reason + "\n";
						$('#page-'+clean_page_name+' #grp-'+widget.name).addClass('has-error');
					});
				}

				alert(message);
			},
			success: function(data) {
				$('#page-'+clean_page_name+' .form-status').html('<span class="glyphicon glyphicon-ok"></span> Succesfully created! ' + data.key);
				$('#page-'+clean_page_name+' .form-status').fadeIn('fast').delay(5000).fadeOut('slow');
				$('#page-'+clean_page_name+'.datetimefield').each(function() {
					$(this).data('DateTimePicker').date(null);
				});

				$('#page-'+clean_page_name+' input').each(function() {
					$(this).val('');
					if($(this).attr('type') == 'checkbox') {
						$(this).prop('checked', false);
					}
				});

				$('#page-'+clean_page_name+' form').trigger("reset");

				$('#page-'+clean_page_name+' .st-outer').each(function() {
					editorId = $(this).attr('id');
					trevor = SirTrevor.getInstance(editorId);
					trevor.reinitialize();
				});

				$('#page-'+clean_page_name+' .select2').val(null).trigger('change');

				$('#page-'+clean_page_name+' select[data-default-pk]').each(function() {
					$(this).append($("<option></option>")
						.attr("value", $(this).attr('data-default-pk'))
						.attr("selected", "selected")
						.text($(this).attr('data-default-value') + " (" + $(this).attr('data-default-pk') + ")"));

					$(this).val($(this).attr('data-default-pk')).trigger('change');
				});

				$('#page-'+clean_page_name+' input[data-default]').each(function() {
					if($(this).attr('type') == 'checkbox') {
						if($(this).attr('data-default') == 1) {
							$(this).prop('checked', true);
						}
					} else {
						$(this).val($(this).attr('data-default'));
						if($(this).hasClass('datetimefield')) {
							$(this).data('DateTimePicker').date($(this).attr('data-default'));
						}
					}
				});

				$('#page-'+clean_page_name+' .image-group .image-key').val(null);
				$('#page-'+clean_page_name+' .image-group .current').html('<i>No image selected.</i>');

				$('html, body').animate({
					scrollTop: $("#page-"+clean_page_name).offset().top
				}, 500);
			},
			cache: false
		});
	} else {
		alert("There is an error with your submission. Please scroll down and correct the highlighted errors.");
	}
}

function runSearch(page_name, paginator_page) {
	var clean_page_name = page_name.replace('/', '-');

	var formData = $('#page-'+clean_page_name+' .search-form').serializeArray();
	formData.push({
		name: "q",
		value: "search"
	});
	formData.push({
		name: "00page",
		value: page_name
	});
	formData.push({
		name: "00page2",
		value: paginator_page
	});
	formData.push({
		name: "00pull",
		value: $('#page-'+clean_page_name).parent().attr('data-parentrecord')
	});
	formData.push({
		name: "00csrf",
		value: $('#csrf-key').parent().attr('data-csrf')
	});

	$.ajax(getAjaxEndpoint(), {
		type: "POST",
		data: formData,
		beforeSend: function(data) {
			$('#page-'+clean_page_name+' .search-button').html('<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Searching...');
			$('#page-'+clean_page_name+' .search-button').attr('disabled', 'disabled');
			$('#page-'+clean_page_name+' .searcharea').html('');
		},
		complete: function(data) {
			$('#page-'+clean_page_name+' .search-button').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search');
			$('#page-'+clean_page_name+' .search-button').removeAttr('disabled');
		},
		error: function(data) {
			message = data.responseJSON.message;

			alert(message);
		},
		success: function(data) {
			$('#page-'+clean_page_name+' .searcharea').html(data.form);

			$('#page-'+clean_page_name+' .sortable').tablesorter({
				theme: "bootstrap",
				widgets: [ "uitheme", "zebra", "stickyHeaders" ],
				headerTemplate: "{content} {icon}",
				widgetOptions: {
					zebra: ["even", "odd"]
				}
			});

			$('html, body').animate({
				scrollTop: $('#page-'+clean_page_name+' .searcharea').offset().top
			}, 500);
		},
		cache: false
	});
}

function refreshList(page_name, paginator_page) {
	var clean_page_name = page_name.replace('/', '-');

	$.ajax(getAjaxEndpoint(), {
		type: "POST",
		data: {
			q: "refresh",
			page: page_name,
			page2: paginator_page,
			pull: $('#page-'+clean_page_name).parent().attr('data-parentrecord'),
			"00csrf": $('#csrf-key').attr('data-csrf')
		},
		beforeSend: function(data) {
			$('#page-'+clean_page_name+' .load-msg').show();
			$('#page-'+clean_page_name+' .dataarea').html('');
		},
		complete: function(data) {
			$('#page-'+clean_page_name+' .load-msg').hide();
		},
		error: function(data) {
			message = data.responseJSON.message;

			alert(message);
		},
		success: function(data) {
			$('#page-'+clean_page_name+' .dataarea').html(data.form);

			$('#page-'+clean_page_name+' .sortable').tablesorter({
				theme: "bootstrap",
				widgets: [ "uitheme", "zebra", "stickyHeaders" ],
				headerTemplate: "{content} {icon}",
				widgetOptions: {
					zebra: ["even", "odd"]
	    		}
			});
		},
		cache: false
	});
}

function removeImage(location) {
	$('#tabs-'+location+'-content #'+location+'-current').html('<i>No image selected.</i>');

	$('#tabs-'+location+'-content input#'+location).val('');
}

function imageForm(location, image, hasEditor, inTrevor) {
	$.ajax(getAjaxEndpoint(), {
		type: "POST",
		data: {
			q: "image",
			name: location,
			image: image,
			hasEditor: hasEditor,
			"00csrf": $('#csrf-key').attr('data-csrf')
		},
		beforeSend: function(data) {
			$('#tabs-'+location+'-content #'+location+'-current').html('');
		},
		error: function(data) {
			message = data.responseJSON.message;

			$('#tabs-'+location+'-content #'+location+'-current').html('<i>'+message+'</i>');
		},
		success: function(data) {
			$('#tabs-'+location+'-content #'+location+'-current').html(data.form);

			$('input#'+location).val(data.id);

			$('#tabs-'+location+' #'+location+'-current-tab').tab('show');

			if(inTrevor) {
				$('#'+location+'-remove').remove();

				$('#tabs-'+location+'-content label').each(function() { $(this).addClass('st-input-label');});

				$('#tabs-'+location+'-content .form-control').each(function() {
					$(this).addClass('st-input-string');
					$(this).removeClass('form-control');
					$(this).attr('style', 'width: 100%');
				});

				$('#tabs-'+location+'-content #'+location+'-descr').each(function() {
					$(this).attr('name', 'description');

					if(typeof inTrevor === 'object') {
						caption = inTrevor.caption;
					} else {
						caption = '';
					}

					$(this).parent().after('<div class="form-group"><label for="caption" class="st-input-label">Caption</label><input type="text" class="st-input-string js-caption-input" id="" name="caption" value="'+caption+'" style="width: 100%"></div>');

				});

				$('#tabs-'+location+'-content #'+location+'-attr').each(function() {
					$(this).attr('name', 'attribution');
					$(this).addClass('js-attribution-input');

					if(typeof inTrevor === 'object') {
						$(this).val(inTrevor.attribution);
					}
				});

				$('#tabs-'+location+'-content #'+location+'-attrlink').each(function() {
					$(this).attr('name', 'attributionLink');
					$(this).addClass('js-attributionLink-input');

					if(typeof inTrevor === 'object') {
						$(this).val(inTrevor.attributionLink);
					}
				});
			}
		},
		cache: false
	});
}

function del(page_name, key) {
	var clean_page_name = page_name.replace('/', '-');

	if(confirm("Are you sure you want to delete this?")) {
		$.ajax(getAjaxEndpoint(), {
			type: "POST",
			data: {
				q: "delete",
				page: page_name,
				key: key,
				pull: $('#page-'+clean_page_name).parent().attr('data-parentrecord'),
				"00csrf": $('#csrf-key').attr('data-csrf')
			},
			beforeSend: function(data) {
				$('#page-'+clean_page_name+' .del-'+key).addClass("glyphicon-hourglass");
				$('#page-'+clean_page_name+' .del-'+key).removeClass("glyphicon-trash");
				$('#page-'+clean_page_name+' .del-'+key).removeClass("text-danger");
			},
			error: function(data) {
				$('#page-'+clean_page_name+' .del-'+key).removeClass("glyphicon-hourglass");
				$('#page-'+clean_page_name+' .del-'+key).addClass("glyphicon-trash");
				$('#page-'+clean_page_name+' .del-'+key).addClass("text-danger");
				message = data.responseJSON.message;
				alert(message);
			},
			success: function(data) {
				$('#page-'+clean_page_name+' .form-status').html('<span class="glyphicon glyphicon-ok"></span> '+data);
				$('#page-'+clean_page_name+' .form-status').fadeIn('fast').delay(5000).fadeOut('slow');

				// Get paginator page
				paginator_page = $('#page-'+clean_page_name+' .datatable').attr('data-currentpage');

				if ($('#page-'+clean_page_name+' .searcharea').length) {
					runSearch(page_name, paginator_page); // Reload results
				} else {
					refreshList(page_name, paginator_page); // Reload results
				}
			},
			cache: false
		});
	}
}

function loadPage(page_name, renderInto, hideTabs, pullThrough, showTitle) {
	pullThrough = pullThrough || null;
	hideTabs = hideTabs || false;
	showTitle = showTitle || false;

	$.ajax(getAjaxEndpoint(), {
			type: "POST",
			data: {
				q: "getPage",
				page: page_name,
				hideTabs: hideTabs,
				pullThrough: pullThrough,
				showTitle: showTitle,
				"00csrf": $('#csrf-key').attr('data-csrf')
			},
			beforeSend: function(data) {
				window.pageIsLoading = true;
				$(renderInto).html('<center class="spinner"><img src="ajax-loader.gif"></center>');
			},
			error: function(data) {
				window.pageIsLoading = false;

				if(data.responseJSON) {
					message = data.responseJSON.message;

					$(renderInto).html('<div class="alert alert-danger">' + message + '</div>');
				} else {
					$(renderInto).html('<div class="alert alert-danger">' + data.responseText + '</div>');
				}
			},
			success: function(data) {
				window.pageIsLoading = false;
				$(renderInto).html(data.screen);

				addCalendar();

				$(renderInto + " .sortable").tablesorter({
					theme: "bootstrap",
					widgets: [ "uitheme", "zebra", "stickyHeaders" ],
					headerTemplate: "{content} {icon}",
					widgetOptions: {
						zebra: ["even", "odd"]
					}
				});
			},
			cache: false
	});
}

function runAction(action, page_name) {
	var records = [];

	var clean_page_name = page_name.replace('/', '-');

	$('#page-'+clean_page_name+' table.table input[name^=record]:checked').each(function() {
		records.push($(this).data('record'));
	});

	$.ajax(getAjaxEndpoint(), {
			type: "POST",
			data: {
				q: "action",
				action: action,
				page: page_name,
				records: records,
				pull: $('#page-'+clean_page_name).parent().attr('data-parentrecord'),
				"00csrf": $('#csrf-key').attr('data-csrf')
			},
			beforeSend: function(data) {
				$('#page-'+clean_page_name+' .action-msg').show();
				$('#page-'+clean_page_name+' .datatable').hide();
			},
			error: function(data) {
				$('#page-'+clean_page_name+' .action-msg').hide();
				$('#page-'+clean_page_name+' .datatable').show();
				// Get paginator page
				paginator_page = $('#page-'+clean_page_name+' .datatable').attr('data-currentpage');

				if ($('#page-'+clean_page_name+' .searcharea').length) {
					runSearch(page_name, paginator_page); // Reload results
				} else if($('#page-'+clean_page_name+' .searcharea').length) {
					refreshList(page_name, paginator_page); // Reload results
				}

				message = data.responseJSON.message;
				alert(message);
			},
			success: function(data) {
				$('#page-'+clean_page_name+' .action-msg').hide();
				$('#page-'+clean_page_name+' .datatable').show();

				// Get paginator page
				paginator_page = $('#page-'+clean_page_name+' .datatable').attr('data-currentpage');

				if ($('#page-'+clean_page_name+' .searcharea').length) {
					runSearch(page_name, paginator_page); // Reload results
				} else if($('#page-'+clean_page_name+' .datatable').length) {
					refreshList(page_name, paginator_page); // Reload results
				}

				$('#page-'+clean_page_name+' .form-status').html('<span class="glyphicon glyphicon-ok"></span> '+data.message);
				$('#page-'+clean_page_name+' .form-status').fadeIn('fast').delay(5000).fadeOut('slow');
			},
			cache: false
	});
}

function login() {
	$.ajax(getAjaxEndpoint(), {
		type: "POST",
		data: {
			q: "login",
			username: $('#username').val(),
			password: $('#password').val(),
			"00csrf": $('#csrf-key').attr('data-csrf')
		},
		beforeSend: function(data) {
			$('.login-button').html('<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Loading...');
			$('.login-button').attr('disabled', 'disabled');
		},
		error: function(data) {
			message = data.responseJSON.message;

			alert(message);
		},
		success: function(data) {
			location.reload(true);
		},
		complete: function(data) {
			$('.login-button').html('<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Log in');
			$('.login-button').removeAttr('disabled');
		},
		cache: false
	});
}

function logout() {
	$.ajax(getAjaxEndpoint(), {
		type: "POST",
		data: {
			q: "logout",
			"00csrf": $('#csrf-key').attr('data-csrf')
		},
		beforeSend: function(data) {
			$('.logout-button').html('<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Loading...');
			$('.logout-button').attr('disabled', 'disabled');
		},
		error: function(data) {
			message = data.responseJSON.message;

			alert(message);
		},
		success: function(data) {
			window.location.href = data.url;
		},
		complete: function(data) {
			$('.logout-button').html('<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Log out');
			$('.logout-button').removeAttr('disabled');
		},
		cache: false
	});
}

function rap() {
	$.ajax(getAjaxEndpoint(), {
		type: "POST",
		data: {
			q: "rap",
			feedback: $('#rap-feedback').val(),
			url: window.location.href,
			"00csrf": $('#csrf-key').attr('data-csrf')
		},
		beforeSend: function(data) {
			$('.rap-button').html('<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Loading...');
			$('.rap-button').attr('disabled', 'disabled');
		},
		error: function(data) {
			message = data.responseJSON.message;

			alert(message);
		},
		success: function(data) {
			alert('Your feedback has been submitted. Report ID '+data.id+'.');
			$('#rap').modal('hide');
			$('#rap-feedback').val('');
		},
		complete: function(data) {
			$('.rap-button').html('<span class="glyphicon glyphicon-send" aria-hidden="true"></span> Send');
			$('.rap-button').removeAttr('disabled');
		},
		cache: false
	});
}

function toggleSelect(page_name) {
	var clean_page_name = page_name.replace('/', '-');

	if($('#page-'+clean_page_name+' .toggler').html() == 'Select all') {
		$('#page-'+clean_page_name+' .recordBox').prop('checked', true);
		$('#page-'+clean_page_name+' .toggler').html('Deselect all');
	} else {
		$('#page-'+clean_page_name+' .recordBox').prop('checked', false);
		$('#page-'+clean_page_name+' .toggler').html('Select all');
	}
	
}

function addCalendar() {
	$('.datetimefield').datetimepicker({
		locale: 'en',
		format: 'YYYY-MM-DD HH:mm:ss',
		showClear: true,
		showClose: true,
		showTodayButton: true,
		useCurrent: false,
		icons: {
			clear: "glyphicon glyphicon-ban-circle"
		},
		inline: false,
		sideBySide: true
	});
}

function toggleAudit(page_name) {
	var clean_page_name = page_name.replace('/', '-');

	$('#page-'+clean_page_name+' .auditButton').toggleClass('active');
	$('#page-'+clean_page_name+' .widgetForm').toggle();
	$('#page-'+clean_page_name+' .auditForm').toggle();
}

addCalendar();

function pickLookupPic(blockId, isTrevor) {
	if(!$("#"+blockId+"-picker").val()) {
		alert('Please select a picture');
		return;
	}

	imageForm(blockId, $("#"+blockId+"-picker").val(), "1", isTrevor);
	$("#"+blockId+"-picker").val(null).trigger("change");
	return;
}

function formatImagePicker(img) {
	if (img.loading) return img.text;
	var image = $('<span><img src="' + img.url + '" class="lookup-img" /> ' + img.text + '</span>');
	return image;
};