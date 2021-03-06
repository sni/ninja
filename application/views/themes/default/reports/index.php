<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="response"></div>
<div id="progress"></div>
<div class="report-page">
<?php
	echo isset($error) ? $error : '';
	echo $header;
?>
<div style="display: none">
<div id="options">
<?php echo form::open($type.'/generate', array('id' => 'report_form', 'onsubmit' => 'return check_form_values(this);'));?>
<?php
	echo $report_options;
	echo $options->as_form(false, true);
?>
</form>
</div>
</div>
<?php
	if (isset($links)) {
		echo '<div class="report-block">';
		echo _('View').': ';
		$html_links = array();
		foreach($links as $url => $name) {
			$html_links[] = html::anchor(url::site($url),html::image($this->add_path('/icons/16x16/'.strtolower(str_replace(' ','-',$name))).'.png',array('alt' => $name, 'title' => $name, 'style' => 'margin-bottom: -3px')),array('style' => 'border: 0px')).
			' <a href="'.url::site($url).'">'.$name.'</a>';
		}
		echo implode(', &nbsp;', $html_links);
		echo '</div>';
	}
	if (!empty($trends_graph)) {
		echo '<div class="report-block">'.help::render('trends').' '._('Trends');
		echo $trends_graph;
		echo '</div>';
	}
	if (!empty($content)) {
		echo $content;
		echo !empty($svc_content) ? $svc_content : '';
		echo isset($pie) ? $pie : '';
		echo !empty($log_content) ? $log_content : '';
	}
?>
</div>
