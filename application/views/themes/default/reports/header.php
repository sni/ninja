<div id="header" class="report-block">
	<div class="report-links">
		<?php
		echo html::anchor(
			'#',
			html::image(
				$this->add_path('icons/32x32/square-print.png'),
				array(
					'alt' => _('Print report'),
					'title' => _('Print report'),
					'onclick' => 'window.print()'
				)
			)
		);

		echo form::open($type.'/generate');
		echo $options->as_form();
		echo '<input type="hidden" name="output_format" value="csv" />';
		$csv_alt = _('Download report as CSV');
		echo "<input type='image' src='".$this->add_path('icons/32x32/page-csv.png')."' alt='".$csv_alt."' title='".$csv_alt."'/>";
		echo "</form>\n";

		echo form::open($type.'/generate');
		echo $options->as_form();
		echo '<input type="hidden" name="output_format" value="pdf" />';
		$pdf_alt = _('Show as pdf');
		echo '<input type="image" src="'.$this->add_path('icons/32x32/page-pdf.png').'" title="'.$pdf_alt.'" alt="'.$pdf_alt.'" />';
		echo '</form>';
		?>
		<a href="#" id="save_report"><?php echo html::image($this->add_path('/icons/32x32/square-save.png'), array('alt' => _('Save report'), 'title' => _('Save report'))); ?></a>
		<a href="#options" class="fancybox"><?php echo html::image($this->add_path('/icons/32x32/square-edit.png'), array('alt' => _('edit settings'), 'title' => _('edit settings'))); ?></a>
		<?php if ($options['report_id']) { ?>
		<a id="show_schedule" href="<?php echo url::base(true) ?>schedule/show"><?php echo html::image($this->add_path('/icons/32x32/square-view-schedule.png'), array('alt' => _('View schedule'), 'title' => _('View schedule'))); ?></a>
		<?php }
		if (Session::instance()->get('main_report_params', false)
			!= Session::instance()->get('current_report_params', false) && Session::instance()->get('main_report_params', false)) {
			# we have main_report_params and we are NOT showing the report (i.e we are showing a sub report)
			# => show backlink
			echo '&nbsp;'.html::anchor($type.'/generate?'.Session::instance()->get('main_report_params'), html::image($this->add_path('/icons/32x32/square-back.png'), array('title' => _('Back'), 'alt' => '')), array('title' => _('Back to original report'))).'&nbsp;';
		}
			# make it possible to get the link (GET) to the current report
			echo '&nbsp;'.html::anchor($type.'/generate?'.$options->as_keyval_string(), html::image($this->add_path('/icons/32x32/square-link.png'),array('alt' => '','title' => _('Direct link'))), array('id' => 'current_report_params', 'title' => _('Direct link to this report. Right click to copy or click to view.')));
		?>
	</div>
	<div id="link_container" class="form-dropdown"></div>
	<div id="save_report_form" class="form-dropdown">
		<form>
			<?php
			$report_name = $options['report_name'];
			unset($options['report_name']);
			echo $options->as_form();
			echo '<input class="wide" type="text" name="report_name" value="'.$report_name.'" />';
			$options['report_name'] = $report_name;
			?>
			<input type="button" class="save_report_btn" value="<?php echo _('Save report') ?>" />
		</form>
	</div>
	<h1><?php echo $title ?></h1>
	<p><?php echo _('Reporting period').': '.$report_time_formatted; ?>
	<?php echo (isset($str_start_date) && isset($str_end_date)) ? ' ('.$str_start_date.' '._('to').' '.$str_end_date.')' : '';
	if ($options['use_average']) echo " <strong>("._('using averages').")</strong>"; ?>
	</p>
	<p><?php echo reports::get_included_states('hosts', $options); ?></p>
	<div class="description">
		<p><?php echo nl2br($options['description']) ?></p>
	</div>
</div>
