<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require "smarty/Smarty.class.php";

class Smarty_parser extends Smarty {

	function Smarty_parser($config = array())
	{
		parent::Smarty();

		if (count($config) > 0)
		{
			$this->initialize($config);
		}

		// register Smarty resource named "ci"
		$this->register_resource("ci", array($this,
						"ci_get_template", "ci_get_timestamp", "ci_get_secure", "ci_get_trusted")
		);

		log_message('debug', "Smarty_parser Class Initialized");
	}

	/**
	 * Initialize preferences
	 */
	function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$method = 'set_'.$key;

				if (method_exists($this, $method))
				{
					$this->$method($val);
				}
				else
				{
					$this->$key = $val;
				}
			}
		}
	}

	/**
	 *  Set the left/right variable delimiters
	 */
	function set_delimiters($l = '{', $r = '}')
	{
		$this->left_delimiter = $l;
		$this->right_delimiter = $r;
	}

	/**
	 *  Parse a template using Smarty engine
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param.
	 * Allows CI and Smarty code to be combined in the same template
	 * by prefixing template name with "ci:".
	 */
	function parse($template, $data, $return = FALSE)
	{
		if ($template == '')
		{
			return FALSE;
		}

		$CI =& get_instance();

		$CI->benchmark->mark('smarty_parse_start');

		if (is_array($data))
		{
			//$this->assign(&$data);
			$this->assign($data);
		}

		// make CI object directly accessible from a template (optional)
		$this->assign_by_ref('CI', $CI);

		$template = $this->fetch($template);

		if ($return == FALSE)
		{
			// $CI->output->final_output = $template;
			$CI->output->set_output($template);
		}

		$CI->benchmark->mark('smarty_parse_end');

		return $template;
	}

	/**
	 * Smarty resource accessor functions
	 */
	function ci_get_template ($tpl_name, &$tpl_source, &$smarty_obj)
	{
		$CI =& get_instance();

		// ask CI to fetch our template
		$tpl_source = $CI->load->view($tpl_name, $smarty_obj->get_template_vars(), true);
		return true;
	}

	function ci_get_timestamp($view, &$timestamp, &$smarty_obj)
	{
		$CI =& get_instance();

		// Taken verbatim from _ci_load (Loader.php, 580):
		$ext = pathinfo($view, PATHINFO_EXTENSION);
		$file = ($ext == '') ? $view.EXT : $view;
		$path = $CI->load->_ci_view_path.$file;

		// get file modification date
		$timestamp = filectime($path);
		return ($timestamp !== FALSE);
	}

	function ci_get_secure($tpl_name, &$smarty_obj)
	{
		// assume all templates are secure
		return true;
	}

	function ci_get_trusted($tpl_name, &$smarty_obj)
	{
		// not used for templates
	}
}
