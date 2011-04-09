<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_pager
{
	public $base_url			= ''; // The page we are linking to
	private $delimitter		= '&';
	private $query_string_segment = 'per_page';

	public $total_rows			= ''; // Total number of items (database results)
	public $per_page			= 10; // Max number of items you want shown per page
	public $num_links			=  2; // Number of "digit" links to show before/after the currently viewed page
	public $cur_page			=  0; // The current page being viewed
	public $offset				= 0;
	public $first_link			= '&lsaquo; First';
	public $next_link			= '&gt;';
	public $prev_link			= '&lt;';
	public $last_link			= 'Last &rsaquo;';
	public $pages_delimitter		= '|';
	public $pages_marker		= '...';

	function __construct($params)
	{
		if (count($params) > 0)
		{
			$this->initialize($params);
		}

		if (!strpos($this->base_url, '?')) $this->delimitter = '?';
	}

	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	private function initialize($params = array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}

	public function set_params($key, $value)
	{
		if (!isset($this->$key)) return;

		$this->$key = $value;
	}

	private function get_prev_link()
	{
		if (!$this->offset) return '';

		$prev_from = $this->offset - $this->per_page;
		if($prev_from < 0) $prev_from = 0;

		return $this->get_link_string($prev_from, $this->prev_link);
	}

	public function get_next_offset()
	{
		$next_offset = $this->offset + $this->per_page;
		if ($next_offset >= $this->total_rows) return 0;

		return $next_offset;
	}

	public function get_next_url()
	{
		if (!$next_from = $this->get_next_offset()) return '';

		return sprintf('%s%s%s=%s', $this->base_url, $this->delimitter, $this->query_string_segment, $next_from);
	}

	private function get_next_link()
	{
		if (!$next_from = $this->get_next_offset()) return '';

		return $this->get_link_string($next_from, $this->next_link);
	}

	private function get_last_page_offset()
	{
		if (!$this->total_rows || !$this->per_page) return 0;

		$rest = $this->total_rows % $this->per_page;
		if (!$rest) return $this->total_rows - $this->per_page;

		return $this->total_rows - $rest;
	}

	private function check_is_at_page($check_offset)
	{
		if ($this->offset == $check_offset) return true;

		return false;
	}

	private function get_link_string($target_offset, $view_string)
	{
		return sprintf('<a href="%s%s%s=%s">%s</a>', $this->base_url, $this->delimitter, $this->query_string_segment, $target_offset, $view_string);
	}

	private function get_last_page_link()
	{
		$last_page_offset = $this->get_last_page_offset();
		if ($this->check_is_at_page($last_page_offset)) return '';

		return $this->get_link_string($last_page_offset, $this->last_link);
	}

	private function get_first_page_link()
	{
		if ($this->check_is_at_page(0)) return '';

		return $this->get_link_string(0, $this->first_link);
	}

	static private function get_page_number($count, $per_page)
	{
		if (!$count || !$per_page) return 1;

		$page_count_calc_result       = $count / $per_page;
		$page_count_calc_result_floor = floor($page_count_calc_result);
		if ($page_count_calc_result > $page_count_calc_result_floor)
		{
			return $page_count_calc_result_floor + 1;
		}

		return $page_count_calc_result_floor;
	}

	private function set_cur_page()
	{
		$this->cur_page = self::get_page_number($this->offset + $this->per_page, $this->per_page);
	}

	private function get_cur_page_first_num()
	{
		return $this->offset + 1;
	}

	private function get_cur_page_last_num()
	{
		$return = $this->offset + $this->per_page;
		if ($return > $this->total_rows) return $this->total_rows;

		return $return;
	}

	private function get_pages_link()
	{
		$cur_first_page = $this->cur_page - $this->num_links;
		if ($cur_first_page < 1) $cur_first_page = 1;

		$last_page = self::get_page_number($this->total_rows, $this->per_page);
		$cur_last_page = $this->cur_page + $this->num_links;
		if ($cur_last_page > $last_page) $cur_last_page = $last_page;

		$prefix = '';
		if ($this->cur_page > 1 + $this->num_links) $prefix = $this->pages_marker;

		$suffix = '';
		if ($cur_last_page < $last_page) $suffix = $this->pages_marker;

		$page_list = array();
		for ($i = $cur_first_page; $i <= $cur_last_page; $i++)
		{
			if ($i == $this->cur_page)
			{
				$page_list[] = sprintf('<b>&nbsp;%d&nbsp;</b>', $this->cur_page);
				continue;
			}

			$page_list[] = $this->get_link_string($this->per_page * ($i - 1), sprintf('&nbsp;%d&nbsp;', $i));
		}

		return $prefix.implode($this->pages_delimitter, $page_list).$suffix;
	}

	public function get_prev_page_rows()
	{
		if ($this->offset - ($this->per_page * 2) > 0)
		{
			return $this->per_page;
		}

		return $this->offset;
	}

	public function get_next_page_rows()
	{
		if ($this->offset + ($this->per_page * 2) < $this->total_rows)
		{
			return $this->per_page;
		}

		return $this->total_rows - ($this->offset + $this->per_page);
	}

	public function create_links()
	{
		if (!$this->per_page) return '';
		$this->set_cur_page();

		$return   = array();
		$return['prev_link'] = $this->get_prev_link();
		$return['next_link'] = $this->get_next_link();

		$return['cur_page_first_num'] = $this->get_cur_page_first_num();
		$return['cur_page_last_num']  = $this->get_cur_page_last_num();

		$return['first_link'] = $this->get_first_page_link();
		$return['last_link']  = $this->get_last_page_link();

		$return['pages_link']  = $this->get_pages_link();

		return $return;
	}
}
