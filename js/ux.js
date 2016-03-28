function save(page_name, key) {
	clean_page_name = page_name.replace('/', '-');

	if(SirTrevor.onBeforeSubmit() == 0) {
		$('.st-outer').each(function() {
			editorId = $(this).attr('id');
			trevor = SirTrevor.getInstance(editorId);
			$('#'+editorId+' textarea.form-control').val(trevor.$el.val());
		});

		var formData = $('form').serializeArray();
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

		$.ajax(getAjaxEndpoint(), {
			type: "POST",
			data: formData,
			beforeSend: function(data) {
				$('.form-status-'+clean_page_name).hide();
				$('.save-button').html('<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Saving...');
				$('.save-button').attr('disabled', 'disabled');
				$('.form-group').removeClass('has-error');
			},
			complete: function(data) {
				$('.save-button').html('<span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save');
				$('.save-button').removeAttr('disabled');
			},
			error: function(data) {
				message = data.responseJSON.message;

				if(data.responseJSON.widgets) {
					message += "\n\n";

					data.responseJSON.widgets.forEach(function(widget) {
						message += widget.reason + "\n";
						$('#grp-'+widget.name).addClass('has-error');
					});
				}

				alert(message);
			},
			success: function(data) {
				if($('#detailsview').length) {
					$('#detailsview').modal('hide');
				}

				$('.form-status-'+clean_page_name).html('<span class="glyphicon glyphicon-ok"></span> '+data);
				$('.form-status-'+clean_page_name).fadeIn('fast').delay(5000).fadeOut('slow');

				if($('.datatable-'+clean_page_name).length > 0) {
					// Get paginator page
					paginator_page = $('.datatable-'+clean_page_name).attr('data-currentpage');

					if ($('.searchtable-'+clean_page_name).length) {
						runSearch(page_name, paginator_page); // Reload results
					} else if($('.datatable-'+clean_page_name).length) {
						refreshList(page_name, paginator_page); // Reload results
					}
				}
			},
			cache: false
		});
	} else {
		alert("There is an error with your submission. Please scroll down and correct the highlighted errors.");
	}
}

function create(page_name) {
	if(SirTrevor.onBeforeSubmit() == 0) {
		$('.st-outer').each(function() {
			editorId = $(this).attr('id');
			trevor = SirTrevor.getInstance(editorId);
			$('#'+editorId+' textarea.form-control').val(trevor.$el.val());
		});

		var formData = new FormData($('#new-form')[0]);
		formData.append('q', 'new');
		formData.append('00page', page_name);

		$.ajax(getAjaxEndpoint(), {
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			beforeSend: function(data) {
				$('.form-status-'+clean_page_name).hide();
				$('.new-button').html('<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Creating...');
				$('.new-button').attr('disabled', 'disabled');
				$('.form-group').removeClass('has-error');
			},
			complete: function(data) {
				$('.new-button').html('<span class="glyphicon glyphicon-saved" aria-hidden="true"></span> Create');
				$('.new-button').removeAttr('disabled');
			},
			error: function(data) {
				message = data.responseJSON.message;

				if(data.responseJSON.widgets) {
					message += "\n\n";

					data.responseJSON.widgets.forEach(function(widget) {
						message += widget.reason + "\n";
						$('#grp-'+widget.name).addClass('has-error');
					});
				}

				alert(message);
			},
			success: function(data) {
				$('.form-status-'+clean_page_name).html('<span class="glyphicon glyphicon-ok"></span> Succesfully created! ' + data.key);
				$('.form-status-'+clean_page_name).fadeIn('fast').delay(5000).fadeOut('slow');
				$('.datetimefield').each(function() {
					$(this).data('DateTimePicker').date(null);
				});

				$('input').each(function() {
					$(this).val('');
					if($(this).attr('type') == 'checkbox') {
						$(this).prop('checked', false);
					}
				});

				$('form').trigger("reset");

				$('.st-outer').each(function() {
					editorId = $(this).attr('id');
					trevor = SirTrevor.getInstance(editorId);
					trevor.reinitialize();
				});

				$('.select2').val(null).trigger('change');

				$('select[data-default-pk]').each(function() {
					$(this).append($("<option></option>")
						.attr("value", $(this).attr('data-default-pk'))
						.attr("selected", "selected")
						.text($(this).attr('data-default-value') + " (" + $(this).attr('data-default-pk') + ")"));

					$(this).val($(this).attr('data-default-pk')).trigger('change');
				});

				$('input[data-default]').each(function() {
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

				$('.image-group .image-key').val(null);
				$('.image-group #current').html('<i>No image selected.</i>');

				$('html, body').animate({
					scrollTop: $("body").offset().top
				}, 500);
			},
			cache: false
		});
	} else {
		alert("There is an error with your submission. Please scroll down and correct the highlighted errors.");
	}
}

function runSearch(page_name, paginator_page) {
	clean_page_name = page_name.replace('/', '-');

	var formData = $('#search-form').serializeArray();
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

	$.ajax(getAjaxEndpoint(), {
		type: "POST",
		data: formData,
		beforeSend: function(data) {
			$('.search-button').html('<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Searching...');
			$('.search-button').attr('disabled', 'disabled');
			$('.searchtable-'.page_name).html('');
		},
		complete: function(data) {
			$('.search-button').html('<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search');
			$('.search-button').removeAttr('disabled');
		},
		error: function(data) {
			message = data.responseJSON.message;

			alert(message);
		},
		success: function(data) {
			$('.searchtable-'+clean_page_name).html(data.form);

			$(".sortable").tablesorter({
				theme: "bootstrap",
				widgets: [ "uitheme", "zebra", "stickyHeaders" ],
				headerTemplate: "{content} {icon}",
				widgetOptions: {
					zebra: ["even", "odd"]
	    		}
			});

			$('html, body').animate({
				scrollTop: $('.searchtable-'+clean_page_name).offset().top
			}, 500);
		},
		cache: false
	});
}

function refreshList(page_name, paginator_page) {
	clean_page_name = page_name.replace('/', '-');

	$.ajax(getAjaxEndpoint(), {
		type: "POST",
		data: {
			q: "refresh",
			page: page_name,
			page2: paginator_page
		},
		beforeSend: function(data) {
			$('.load-msg-'+clean_page_name).show();
			$('.dataarea-'+clean_page_name).html('');
		},
		complete: function(data) {
			$('.load-msg-'+clean_page_name).hide();
		},
		error: function(data) {
			message = data.responseJSON.message;

			alert(message);
		},
		success: function(data) {
			$('.dataarea-'+clean_page_name).html(data.form);

			$(".sortable").tablesorter({
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

	$('input#'+location).val('');
}

function imageForm(location, image, hasEditor, inTrevor) {
	$.ajax(getAjaxEndpoint(), {
		type: "POST",
		data: {
			q: "image",
			name: location,
			image: image,
			hasEditor: hasEditor
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
	clean_page_name = page_name.replace('/', '-');

	if(confirm("Are you sure you want to delete this?")) {
		$.ajax(getAjaxEndpoint(), {
			type: "POST",
			data: {
				q: "delete",
				page: page_name,
				key: key
			},
			beforeSend: function(data) {
				$('#del-'+key).addClass("glyphicon-hourglass");
				$('#del-'+key).removeClass("glyphicon-trash");
				$('#del-'+key).removeClass("text-danger");
			},
			error: function(data) {
				$('#del-'+key).removeClass("glyphicon-hourglass");
				$('#del-'+key).addClass("glyphicon-trash");
				$('#del-'+key).addClass("text-danger");
				message = data.responseJSON.message;
				alert(message);
			},
			success: function(data) {
				$('.form-status-'+clean_page_name).html('<span class="glyphicon glyphicon-ok"></span> '+data);
				$('.form-status-'+clean_page_name).fadeIn('fast').delay(5000).fadeOut('slow');

				// Get paginator page
				paginator_page = $('.datatable-'+clean_page_name).attr('data-currentpage');

				if ($('.searchtable-'+clean_page_name).length) {
					runSearch(page_name, paginator_page); // Reload results
				} else {
					refreshList(page_name, paginator_page); // Reload results
				}
			},
			cache: false
		});
	}
}

function show_details(page_name, key) {
	$('.glyphicon-'+key).removeClass('glyphicon-pencil');
	$('.glyphicon-'+key).addClass('glyphicon-hourglass');

	$('#detailsview .modal-body').html('Please wait...');
	$('#detailsview .modal-title').html('Loading');
	$('#detailsview').modal();

	var formData = $('form').serializeArray();
	formData.push({
		name: "q",
		value: "popupDetails"
	});
	formData.push({
		name: "00page",
		value: page_name
	});
	formData.push({
		name: "00key",
		value: key
	});

	$.ajax(getAjaxEndpoint(), {
		type: "POST",
		data: formData,
		error: function(data) {
			message = data.responseJSON.message;

			$('#detailsview .modal-body').html('An error occured when loading this screen: '+message);
			$('#detailsview .modal-title').html('Error');
		},
		success: function(data) {
			$('#detailsview .modal-body').html(data.form.string);
			$('#detailsview .modal-title').html(data.form.heading);

			addCalendar();
		},
		complete: function(data) {
			$('.glyphicon-'+key).removeClass('glyphicon-hourglass');
			$('.glyphicon-'+key).addClass('glyphicon-pencil');
		},
		cache: false
	});
}

function runAction(action, page_name) {
	var records = [];

	$('table.table input[name^=record]:checked').each(function() {
		records.push($(this).data('record'));
	});

	clean_page_name = page_name.replace('/', '-');

	$.ajax(getAjaxEndpoint(), {
			type: "POST",
			data: {
				q: "action",
				action: action,
				page: page_name,
				records: records
			},
			beforeSend: function(data) {
				$('.action-msg-'+clean_page_name).show();
				$('.dataarea-'+clean_page_name).hide();
			},
			error: function(data) {
				$('.action-msg-'+clean_page_name).hide();
				$('.dataarea-'+clean_page_name).show();
				// Get paginator page
				paginator_page = $('.datatable-'.page_name.replace("/", "-")).attr('data-currentpage');

				if ($('.searchtable-'+clean_page_name).length) {
					runSearch(page_name, paginator_page); // Reload results
				} else if($('.datatable-'+clean_page_name).length) {
					refreshList(page_name, paginator_page); // Reload results
				}

				message = data.responseJSON.message;
				alert(message);
			},
			success: function(data) {
				$('.action-msg-'+clean_page_name).hide();
				$('.dataarea-'+clean_page_name).show();

				// Get paginator page
				paginator_page = $('.datatable-'+clean_page_name).attr('data-currentpage');

				if ($('.searchtable-'+clean_page_name).length) {
					runSearch(page_name, paginator_page); // Reload results
				} else if($('.datatable-'+clean_page_name).length) {
					refreshList(page_name, paginator_page); // Reload results
				}

				$('.form-status-'+clean_page_name).html('<span class="glyphicon glyphicon-ok"></span> '+data.message);
				$('.form-status-'+clean_page_name).fadeIn('fast').delay(5000).fadeOut('slow');
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
			q: "logout"
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
			url: window.location.href
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

function toggleSelect() {
	if($('.toggler').html() == 'Select all') {
		$('.recordBox').prop('checked', true);
		$('.toggler').html('Deselect all');
	} else {
		$('.recordBox').prop('checked', false);
		$('.toggler').html('Select all');
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

function toggleAudit() {
	$('#auditButton').toggleClass('active');
	$('#widgetForm').toggle();
	$('#auditForm').toggle();
}

addCalendar();

// http://codepen.io/DawsonMediaD/pen/byDqv/
$(".modal-wide").on("show.bs.modal", function() {
  var height = $(window).height() - 200;
  $(this).find(".modal-body").css("max-height", height);
});

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