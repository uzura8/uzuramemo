<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Webmemo extends MY_Controller
{
	private $nowurl_noqry = '';

	function __construct()
	{
		parent::__construct();

		// load models
		$this->load->model('webmemo/category');
		$this->load->model('webmemo/memo');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$now_category_id = 0;

		$now_category = array();
		if ($now_category_id) $now_category = $this->category->get_row4id($now_category_id);

		$view_data = array(
			'cate_list' => $this->category->get_list_all(),
			'cate_id_list' => $this->category->get_id_list(true),
			'now_category' => $now_category,
			'now_category_id' => $now_category_id,
			'cate_list_important_articles' => $this->memo->get_important_list(),
			'foot_info' => $this->_set_footer_info(),
		);

		$this->smarty_parser->parse('ci:webmemo/index.tpl', $view_data);
	}

	protected function _set_footer_info()
	{
		return '';
	}
}

/* End of file webmemo.php */
/* Location: ./application/controllers/webmemo.php */
