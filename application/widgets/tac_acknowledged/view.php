<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<table class="w-table">
	<?php for ($i = 0; $i < count($problem); $i++) { ?>
		<tr>
			<td class="dark"><?php echo html::image($this->add_path('icons/16x16/acknowledged.png'), array('alt' => $problem[$i]['status'])) ?></td>
			<td class="status-<?php echo strtolower($problem[$i]['status']);?>">
				<?php echo strtoupper($problem[$i]['status']) ?><br />
				<?php
					echo html::anchor($problem[$i]['url'],$problem[$i]['title']);
				?>
			</td>
		</tr>
	<?php } if (count($problem) == 0) { ?>
		<tr>
			<td class="dark"><?php echo html::image($this->add_path('icons/16x16/acknowledged-not.png'), array('alt' => _('N/A'))) ?></td>
			<td><?php echo _('N/A')?></td>
		</tr>
	<?php } ?>
</table>
