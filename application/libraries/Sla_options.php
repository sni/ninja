<?php defined('SYSPATH') OR die('No direct access allowed.');

class Sla_options_Core extends Report_options {
	public function __construct($options) {
		unset($this->vtypes['include_trends']);
		$this->vtypes['report_period'] = array('type' => 'enum', 'default' => 'thisyear', 'options' => array(
			"thisyear" => _('This Year'),
			"lastyear" => _('Last Year'),
			"lastmonth" => _('Last Month'),
			"last3months" => _('Last 3 Months'),
			"last6months" => _('Last 6 months'),
			"lastquarter" => _('Last Quarter'),
			"last12months" => _('Last 12 months')
		));
		// Warning! months is 1-indexed
		$this->vtypes['months'] = array('type' => 'array', 'default' => false);

		parent::__construct($options);
	}

	public function set($name, $value)
	{
		$resp = parent::set($name, $value);
		if ($resp === false && preg_match('/^month/', trim($name))) {
			$id = (int)str_replace('month_', '', $name);
			if (trim($value) == '')
				return;
			$value = str_replace(',', '.', $value);
			$value = (float)$value;
			// values greater than 100 doesn't make sense
			if ($value>100)
				$value = 100;
			$this->options['months'][$id] = $value;
			return true;
		}
		return $resp;
	}
}
