<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
			<div id="schedules_area">
				<?php echo isset($new_schedule) ? $new_schedule : '' ?>
				<br /><br />
				<div id="scheduled_avail_reports" style='width: 100%'>
					<table id="avail_scheduled_reports_table" style='width: 100%;' class="white-table">
					<caption><?php echo $avail_header ?></caption>
						<thead>
							<tr class="setup">
								<th class="headerNone left" style='width: 9%'><?php echo $label_sch_interval ?></th>
								<th class="headerNone left" style='width: 9%'><?php echo $label_sch_name ?></th>
								<th class="headerNone left" style='width: 20%'><?php echo $label_sch_recipients ?></th>
								<th class="headerNone left" style='width: 20%'><?php echo $label_sch_filename ?></th>
								<th class="headerNone left" style='width: 50%'><?php echo $label_sch_description ?></th>
								<th class="headerNone left" style='width: 1%'></th>
							</tr>
						</thead>
						<tbody>
						<?php if (!empty($avail_schedules) && count($avail_schedules)) { ?>
							<?php	$recipients = false;
								foreach ($avail_schedules as $schedule) {
									$schedule = (object)$schedule;
									$recipients = str_replace(' ', '', $schedule->recipients);
									$recipients = str_replace(',', ', ', $recipients);	?>
								<tr id="report-<?php echo $schedule->id ?>">
								<td class="period_select" title="<?php echo $label_dblclick ?>" id="period_id-<?php echo $schedule->id ?>"><?php echo $schedule->periodname ?></td>
								<td class="report_name" id="<?php echo $schedule->report_type_id ?>.report_id-<?php echo $schedule->id ?>"><?php echo $schedule->reportname ?></td>
								<td class="iseditable" title="<?php echo $label_dblclick ?>" id="recipients-<?php echo $schedule->id ?>"><?php echo $recipients ?></td>
								<td class="iseditable" title="<?php echo $label_dblclick ?>" id="filename-<?php echo $schedule->id ?>"><?php echo $schedule->filename ?></td>
								<td class="iseditable_txtarea" title="<?php echo $label_dblclick ?>" id="description-<?php echo $schedule->id ?>"><?php echo utf8_decode($schedule->description) ?></td>
								<td class="delete_schedule" id="alldel_<?php echo $schedule->id ?>" style='text-align: right'>
									<?php echo html::image($this->add_path('icons/12x12/cross.gif'), array('class' => 'deleteimg')) ?>
								</td>
							</tr>
							<?php } } else {?>
								<tr class="even"><td colspan="6"><?php echo $label_no_schedules ?></td></tr>
							<?php } ?>
						</tbody>
					</table>
				</div>

				<br /><br />
				<div id="scheduled_sla_reports" style="width: 100%">
					<table id="sla_scheduled_reports_table" style='width: 100%;' class="white-table">
					<caption><?php echo $sla_header ?></caption>
						<thead>
							<tr class="setup">
								<th class="headerNone left" style='width: 9%'><?php echo $label_sch_interval ?></th>
								<th class="headerNone left" style='width: 9%'><?php echo $label_sch_name ?></th>
								<th class="headerNone left" style='width: 20%'><?php echo $label_sch_recipients ?></th>
								<th class="headerNone left" style='width: 20%'><?php echo $label_sch_filename ?></th>
								<th class="headerNone left" style='width: 50%'><?php echo $label_sch_description ?></th>
								<th class="headerNone left" style='width: 1%'></th>
							</tr>
						</thead>
						<tbody>
						<?php if (!empty($sla_schedules) && count($sla_schedules)) { ?>
							<?php	$recipients = false;
								foreach ($sla_schedules as $schedule) {
									$schedule = (object)$schedule;
									$recipients = str_replace(' ', '', $schedule->recipients);
									$recipients = str_replace(',', ', ', $recipients);	?>
								<tr id="report-<?php echo $schedule->id ?>">
								<td class="period_select" title="<?php echo $label_dblclick ?>" id="period_id-<?php echo $schedule->id ?>"><?php echo $schedule->periodname ?></td>
								<td class="report_name" id="<?php echo $schedule->report_type_id ?>.report_id-<?php echo $schedule->id ?>"><?php echo $schedule->reportname ?></td>
								<td class="iseditable" title="<?php echo $label_dblclick ?>" id="recipients-<?php echo $schedule->id ?>"><?php echo $recipients ?></td>
								<td class="iseditable" title="<?php echo $label_dblclick ?>" id="filename-<?php echo $schedule->id ?>"><?php echo $schedule->filename ?></td>
								<td class="iseditable_txtarea" title="<?php echo $label_dblclick ?>" id="description-<?php echo $schedule->id ?>"><?php echo utf8_decode($schedule->description) ?></td>
								<td class="delete_schedule" id="alldel_<?php echo $schedule->id ?>" style='text-align: right'><?php echo html::image($this->add_path('icons/12x12/cross.gif')) ?></td>
							</tr>
							<?php } } else {?>
							<tr class="even"><td colspan="6"><?php echo $label_no_schedules ?></td></tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>