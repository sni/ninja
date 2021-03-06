<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<?php
$saved_reports_exists = false;
if (isset($saved_reports) && count($saved_reports)>0 && !empty($saved_reports)) {
	$saved_reports_exists = true;
}
?>

<div class="left w98">
	<div class="report-page-setup availsla">
		<div id="response"></div>
		<div class="setup-table">
			<h1 id="report_type_label"><?php echo $label_create_new ?></h1>

			<div id="switcher" class="report-block">
				<a id="switch_report_type" href="<?php echo url::base(true) . ($type == 'avail' ? 'sla' : 'avail') ?>/index" style="border: 0px; float: left; margin-right: 5px">
				<?php
					echo $type == 'avail' ?
					html::image($this->add_path('icons/16x16/sla.png'), array('alt' => _('SLA'), 'title' => _('SLA'), 'ID' => 'switcher_image')) :
					html::image($this->add_path('icons/16x16/availability.png'), array('alt' => _('Availability'), 'title' => _('Availability'), 'ID' => 'switcher_image'));
				?>
				<span id="switch_report_type_txt" style="border-bottom: 1px dotted #777777">
				<?php echo $type == 'avail' ? _('Switch to SLA report') :_('Switch to Availability report'); ?>
				</span>
				</a>
			</div>

			<?php echo form::open($type.'/index', array('id' => 'saved_report_form', 'class' => 'report-block')); ?>
				<div id="saved_reports_display" style="width: 100%; padding-left: 0px;<?php if (!$saved_reports_exists) { ?>display:none;<?php } ?>">
					<?php echo help::render('saved_reports') ?> <?php echo _('Saved reports') ?><br />
					<select name="report_id" id="report_id">
						<option value=""> - <?php echo _('Select saved report') ?> - </option>
						<?php	$sched_str = "";
						if ($saved_reports_exists) {
							foreach ($saved_reports as $info) {
								$sched_str = in_array($info->id, $scheduled_ids) ? " ( *"._('Scheduled')."* )" : "";
								if (in_array($info->id, $scheduled_ids)) {
									$sched_str = " ( *"._('Scheduled')."* )";
									$title_str = $scheduled_periods[$info->id]." "._('schedule');
								} else {
									$sched_str = "";
									$title_str = "";
								}
								echo '<option title="'.$title_str.'" '.(($options['report_id'] == $info->id) ? 'selected="selected"' : '').
									' value="'.$info->id.'">'.$info->report_name.$sched_str.'</option>'."\n";
							}
						} ?>
					</select>
					<input type="hidden" name="type" value="<?php echo $type ?>" />
					<input type="submit" class="button select" value="<?php echo _('Select') ?>" name="fetch_report" />
					<input type="button" class="button new" value="<?php echo _('New') ?>" name="new_report" title="<?php echo $new_saved_title ?>" id="new_report" />
					<input type="button" class="button delete" value="Delete" name="delete_report" title="<?php echo _('Delete report') ?>" id="delete_report" />
					<?php if (isset($is_scheduled) && $is_scheduled) { ?>
					<div id="single_schedules" style="display:inline">
						<span id="is_scheduled" title="<?php echo _('This report has been scheduled. Click the icons below to change settings') ?>">
							<?php echo _('This is a scheduled report') ?>
							<a href="<?php echo url::base(true) ?>schedule/show" id="show_scheduled" class="help">[<?php echo _('edit') ?>]</a>
						</span>
					</div>
					<?php	} ?>
				</div>
			<?php echo form::close();?>
		</div>

		<?php echo form::open($type.'/generate', array('id' => 'report_form')); ?>
			<input type="hidden" name="type" value="<?php echo $type ?>" />
			<table summary="Select report type" class="setup-tbl"><!--id="main_table"-->
				<tr>
					<td colspan="3">
						<?php echo help::render('report-type').' '._('Report type'); ?><br />
						<select name="report_type" id="report_type" onchange="set_selection(this.value);">
							<option value="hostgroups"><?php echo _('Hostgroups') ?></option>
							<option value="hosts"><?php echo _('Hosts') ?></option>
							<option value="servicegroups"><?php echo _('Servicegroups') ?></option>
							<option value="services"><?php echo _('Services') ?></option>
						</select>
						<input type="button" id="sel_report_type" class="button select20" onclick="set_selection(document.forms['report_form'].report_type.value);" value="<?php echo _('Select') ?>" />
						<div id="progress"></div>
					</td>
				</tr>
				<tr id="filter_row">
					<td colspan="3">
						<?php echo help::render('filter').' '._('Filter') ?><br />
						<input type="text" name="filter_field" id="filter_field" autocomplete=off size="10" value="">
						<input type="button" name="clear_filter" id="clear_filter" value="<?php echo _('Clear') ?>">
					</td>
				</tr>
				<tr id="hostgroup_row">
					<td>
						<?php echo _('Available').' '._('Hostgroups') ?><br />
						<select name="hostgroup_tmp[]" id="hostgroup_tmp" multiple="multiple" size='8' class="multiple">
						</select>
					</td>
					<td class="move-buttons">
						<input type="button" value="&gt;" id="mv_hg_r" class="button arrow-right" /><br />
						<input type="button" value="&lt;" id="mv_hg_l" class="button arrow-left" />
					</td>
					<td>
						<?php echo _('Selected').' '._('Hostgroups') ?><br />
						<select name="hostgroup[]" id="hostgroup" multiple="multiple" size="8" class="multiple">
						</select>
					</td>
				</tr>
				<tr id="servicegroup_row">
					<td>
						<?php echo _('Available').' '._('Servicegroups') ?><br />
						<select name="servicegroup_tmp[]" id="servicegroup_tmp" multiple="multiple" size='8' class="multiple">
						</select>
					</td>
					<td class="move-buttons">
						<input type="button" value="&gt;" id="mv_sg_r" class="button arrow-right" /><br />
						<input type="button" value="&lt;" id="mv_sg_l" class="button arrow-left" />
					</td>
					<td>
						<?php echo _('Selected').' '._('Servicegroups') ?><br />
						<select name="servicegroup[]" id="servicegroup" multiple="multiple" size="8" class="multiple">
						</select>
					</td>
				</tr>
				<tr id="host_row_2">
					<td>
						<?php echo _('Available').' '._('Hosts') ?><br />
						<select name="host_tmp[]" id="host_tmp" multiple="multiple" size="8" class="multiple">
						</select>
					</td>
					<td class="move-buttons">
						<input type="button" value="&gt;" id="mv_h_r" class="button arrow-right" /><br />
						<input type="button" value="&lt;" id="mv_h_l" class="button arrow-left" />
					</td>
					<td>
						<?php echo _('Selected').' '._('Hosts') ?><br />
						<select name="host_name[]" id="host_name" multiple="multiple" size="8" class="multiple">
						</select>
					</td>
				</tr>
				<tr id="service_row_2">
					<td>
						<?php echo _('Available').' '._('Services') ?><br />
						<select name="service_tmp[]" id="service_tmp" multiple="multiple" size="8" class="multiple">
						</select>
					</td>
					<td class="move-buttons">
						<input type="button" value="&gt;" id="mv_s_r" class="button arrow-right" /><br />
						<input type="button" value="&lt;" id="mv_s_l" class="button arrow-left"  />
					</td>
					<td>
						<?php echo _('Selected').' '._('Services') ?><br />
						<select name="service_description[]" id="service_description" multiple="multiple" size="8" class="multiple">
						</select>
					</td>
				</tr>
			</table>

			<?php echo $report_options; ?>
		</form>
	</div>
</div>
