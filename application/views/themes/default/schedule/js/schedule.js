function remove_scheduled_str(in_str)
{
	in_str = in_str.replace(/\*/g, '');
	in_str = in_str.replace(" ( " + _scheduled_label + " )", '');
	return in_str;
}

function create_filename()
{
	if (!$('#saved_report_id option:selected').val()) {
		$('input[name=filename]').val('');
		return false;
	}
	var new_filename = $('#saved_report_id option:selected').text();
	new_filename = remove_scheduled_str(new_filename);
	new_filename += '_' + $('#period option:selected').text() + '.pdf';
	new_filename = new_filename.replace(/ /g, '_');
	if ($('input[name=filename]').val() != '' && $('input[name=filename]').val() != current_filename) {
		if (!confirm(_schedule_change_filename)) {
			return false;
		}
	}
	$('input[name=filename]').val(new_filename);
	current_filename = new_filename;
	return true;
}

$(document).ready(function() {
	$("#saved_report_id").change(function() {
		create_filename();
	});
	fill_scheduled();
	setup_editable();
	$("#period").change(function() {
		var sel_report = $("#saved_report_id").fieldValue();
		if (sel_report[0] != '')
			create_filename();
	});

		// delete single schedule
	$('body').on('click', '.delete_schedule', schedule_delete);
	$('body').on('click', '.send_report_now', send_report_now);

	$("#type").change(function() {
		var report_type = $(this).fieldValue()[0];
		$.ajax(
			_site_domain + _index_page + "/schedule/list_by_type/"+report_type,
			{
				error: function(xhr) {
					alert(xhr.responseText);
				},
				success: function(response) {
					var saved_reports = document.getElementById("saved_report_id");
					var child;
					while(child = saved_reports.firstChild) {
						saved_reports.removeChild(child);
					}
					if(!response.length) {
						return;
					}
					var options = document.createDocumentFragment();
					for(var i = 0; i < response.length; i++) {
						var option = document.createElement("option");
						var result = response[i];
						option.appendChild(document.createTextNode(result.report_name));
						option.setAttribute("value", result.id);
						options.appendChild(option);
					}
					saved_reports.appendChild(options);
					create_filename();
				},
				dataType: 'json'
			}
		);
	});

	$('#new_schedule_report_form').submit(function(ev) {
		ev.preventDefault();

		var rep_type_str = $('#type option:selected').val();

		var recipients = $.trim($('#recipients').fieldValue()[0]);
		if (recipients.indexOf('@') === -1) {
			alert(_reports_invalid_email);
			return false;
		}

		if(!validate_form()) {
			return false;
		}
		show_progress('progress', _wait_str);
		$.ajax({
			url: _site_domain + _index_page + '/schedule/schedule',
			type: 'POST',
			data: {
				report_id: 0,
				type: $('#type').fieldValue()[0],
				saved_report_id: $('#saved_report_id').fieldValue()[0],
				period: $('#period').fieldValue()[0],
				recipients: recipients,
				filename: $('#filename').fieldValue()[0],
				description: $('#description').fieldValue()[0],
				local_persistent_filepath: $.trim($('#local_persistent_filepath').val())
			},
			complete: function() {
				$('#progress').hide();
				// make sure we hide message about no schedules and show table headers
				$('#' + rep_type_str + '_no_result').hide();
				$('#' + rep_type_str + '_headers').show();
			},
			error: function(data) {
				jgrowl_message(data.responseText, _reports_error);
			},
			success: function(data) {
				var rep_type = $('#type').attr('value');
				var saved_report_id = $('#saved_report_id').attr('value');
				var report_name = $('#saved_report_id option:selected').text();
				var period_str = $('#period option:selected').text();
				var recipients = $('#recipients').attr('value');
				var filename = $('#filename').attr('value');
				var local_persistent_filepath = $('#local_persistent_filepath').attr('value');
				var description = $('#description').attr('value');
				if (description == '')
					description = '&nbsp;';
				create_new_schedule_rows(data.id, rep_type, report_name, saved_report_id, period_str, recipients, filename, local_persistent_filepath, description)
				setup_editable();
				$('#new_schedule_report_form').clearForm();

				jgrowl_message(_reports_schedule_create_ok, _reports_success);
			},
			dataType: 'json'
		});
	});
});

function schedule_delete()
{
	if (!confirm(_reports_confirm_delete_schedule)) {
		return false;
	}

	var elem = $(this);
	var type = elem.data('type');
	var schedule_id = elem.data('schedule');
	var report_id = elem.data('report_id');

	var img = $('img', elem);
	var img_src = img.attr('src');
	img.attr('src', loadimg.src);

	$.ajax({
		url:_site_domain + _index_page + '/schedule/delete_schedule',
		data: {'id': schedule_id},
		complete: function() {
			img.attr('src', img_src);
		},
		success: function(data) {
			jgrowl_message(data, _reports_success);
			var table = $('#'+type+'_scheduled_reports_table tbody');
			$('tr#report-'+schedule_id, table).detach();
			if (!$(':visible', table).length)
				$('.no-result', table).show();
		},
		error: function(data) {
			jgrowl_message(data, _reports_error);
		},
		type: 'POST',
		dataType: 'json'
	});
}

function send_report_now()
{
	var elem = $(this);
	var type = elem.data('type');
	var sched_id = elem.data('schedule');
	var report_id = elem.data('report_id');
	var img = $('img', elem);
	var img_src = img.attr('src');
	img.attr('src', loadimg.src);

	$.ajax({
		url: _site_domain + _index_page + '/schedule/send_now/' + sched_id,
		type: 'POST',
		complete: function() {
			img.attr('src', img_src);
		},
		success: function(data) {
			jgrowl_message(data, _reports_success);
		},
		error: function(data) {
			if(data.responseText) {
				jgrowl_message(_reports_schedule_send_error + ': ' + data.responseText, _reports_error);
			} else {
				jgrowl_message(_reports_schedule_send_error, _reports_error);
			}
			img.attr('src', img_src);
		},
		dataType: 'json'
	});
}

function setup_editable()
{
	var save_url = _site_domain + _index_page + "/schedule/save_schedule_item/";
	$(".iseditable").editable(save_url, {
		id   : 'elementid',
		name : 'newvalue',
		type : 'text',
		event : 'dblclick',
		width : 'auto',
		height : '14px',
		submit : _ok_str,
		cancel : _cancel_str,
		placeholder:_reports_edit_information
	});
	$(".period_select").editable(save_url, {
		data : function(value) {
			var intervals = [];
			$('#period option').map(function() {
				intervals.push("'"+$(this).val()+"': '"+$(this).text()+"' ");
			});
			intervals = "{"+intervals.join(",")+"}";
			return intervals;
		},
		id   : 'elementid',
		name : 'newvalue',
		event : 'dblclick',
		type : 'select',
		submit : _ok_str,
		cancel : _cancel_str
	});
	$(".iseditable_txtarea").editable(save_url, {
		indicator : "<img src='" + _site_domain + "application/media/images/loading.gif'>",
		id   : 'elementid',
		name : 'newvalue',
		type : 'textarea',
		event : 'dblclick',
		rows: '3',
		submit : _ok_str,
		cancel : _cancel_str,
		cssclass: "txtarea",
		placeholder:_reports_edit_information
	});
	$(".report_name").editable(save_url, {
		data : function (){
			switch (_report_types_json[this.id.split('-')[0].split('.')[0]]) {
				case 'avail':
					return _saved_avail_reports;
				case 'sla':
					return _saved_sla_reports;
				case 'summary':
					return _saved_summary_reports;
			}
			return false;
		},
		id   : 'elementid',
		name : 'newvalue',
		event : 'dblclick',
		type : 'select',
		submit : 'OK',
		cancel : 'cancel'
	});
}

function create_new_schedule_rows(schedule_id, rep_type, report_name, report_id, report_period, recipients, filename, local_persistent_filepath, description)
{
	var template_row = $('#schedule_template tr');

	var report_type_id = -1;
	for (var i in _report_types_json) {
		if (_report_types_json[i] == rep_type) {
			report_type_id = i;
		}
	}

	template_row = template_row.clone()
	$('#' + rep_type + '_scheduled_reports_table .no-result').hide();
	template_row.attr('id', 'report-'+schedule_id);
	$('.report_name', template_row).text(report_name);
	$('.description', template_row).text(description);
	$('.period_select', template_row).text(report_period);
	$('.recipients', template_row).text(recipients);
	$('.filename', template_row).text(filename);
	$('.local-path', template_row).text(local_persistent_filepath);
	var actions = $('.action', template_row);
	$('.direct_link', actions).attr('href', _site_domain + _index_page + '/' + rep_type + '/generate?report_id=' + report_id);
	$('.send_report_now, .delete_schedule', actions).data('schedule', schedule_id).data('report_id', report_id).data('type', rep_type);
	var par = $('#' + rep_type + '_scheduled_reports_table tbody');
	if (par.children().last().hasClass('odd'))
		template_row.attr('class', 'even');
	else
		template_row.attr('class', 'odd');
	par.append(template_row);
}

function fill_scheduled() {
	for (var type in _scheduled_reports) {
		if (!_scheduled_reports[type].length) {
			$('#' + type + '_scheduled_reports_table .no-result').show();
			continue;
		}
		for (var i = 0; i < _scheduled_reports[type].length; i++) {
			var report = _scheduled_reports[type][i];
			create_new_schedule_rows(report.id, type, report.reportname, report.report_id, report.periodname, report.recipients, report.filename, report.local_persistent_filepath, report.description);
		}
	}
}
