<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * A controller that is sometimes available without authorization.
 *
 * That is, things here shouldn't expose secrets, but for DoS reasons,
 * they're still not available from anybody who's both non-localhost (reports)
 * and non-logged-in (actual users)
 */
class Public_Controller extends Controller {
	public function __construct()
	{
		// No current user
		if (!Auth::instance()->get_user()) {
			// And we don't come from ::1 or 127.0.0.0/8
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) || !($_SERVER['REMOTE_ADDR'] == '::1' || (ip2long($_SERVER['REMOTE_ADDR']) & (127 << 24)) !== (127 << 24))) {
				// So we won't do anything
				die("Invalid request");
			}
		}
	}

	/**
	*	Create a piechart
	*/
	public function piechart($in_data)
	{
		$this->auto_render = false;
		$data = i18n::unserialize( base64_decode($in_data) );
		charts::load('Pie');
		$graph = new PieChart(300, 200);
		$graph->set_data($data, 'pie');
		$graph->set_margins(30);

		$graph->draw();
		$graph->display();
	}

	/**
	*	Create a barchart
	*/
	public function barchart($in_data)
	{
		$this->auto_render = false;
		$data = i18n::unserialize( base64_decode($in_data) );
		charts::load('MultipleBar');
		$graph = new MultipleBarChart(800, 600);

		$barvalues = false;
		$barcolors = false;
		foreach ($data as $tmpkey => $tmpval) {
			$barvalues[$tmpkey] = array($tmpval[1], $tmpval[0]);
			$barcolors[] = false;
			$barcolors[] = $tmpval[2] ? Reports_Controller::$colors['red'] : Reports_Controller::$colors['green'];
		}

		$graph->add_bar_colors($barcolors);
		$graph->set_background_style(null);
		$graph->set_plot_bg_color('#fff');
		$graph->set_data($barvalues);
		$graph->set_margins(7, 20);
		$graph->set_approx_line_gap(50);
		$graph->set_legend_y(_('Percent (%)'));
		$graph->set_legend_x(_('Period'));

		$graph->draw();
		$graph->display();
	}
}
