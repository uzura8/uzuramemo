<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Site extends MY_Controller
{
	private $site_keywords = array();
	private $site_description = '';
	private $breadcrumbs    = array();
	private $category_list_all = array();

	function __construct()
	{
		parent::__construct();

		// load models
		$this->load->model('webmemo/category');
		$this->load->model('webmemo/memo');

		$this->_configure();
	}

	private function _configure()
	{
		$this->site_keywords    = $GLOBALS['SITE_KEYWORDS'];
		$this->site_description = SITE_DESCRIPTION;

		$this->breadcrumbs['list'] = array();
		$this->breadcrumbs['list'][] = array('uri' => '/', 'name' =>  UM_TOPPAGE_NAME);

		// side_menu用カテゴリリスト
		$this->category_list_all = $this->category->get_list_all();
	}

	private function _get_default_view_data()
	{
		return array(
			'site_keywords'    => $this->site_keywords,
			'site_description' => $this->site_description,
			'breadcrumbs' => $this->breadcrumbs,
			'cate_list' => $this->category_list_all,
			'cate_id_list' => $this->site_util->convert_to_category_id_list($this->category_list_all),
			'cate_list_important_articles' => $this->memo->get_important_list(),
			'foot_info' => $this->_set_footer_info(),
		);
	}

	private function _set_footer_info()
	{
		return '';
	}

	public function index()
	{
		$this->sitemap();
	}

	public function sitemap()
	{
		// page description
		$page_title = 'サイトマップ';
		$this->site_keywords[]   = $page_title;
		$this->site_description .= sprintf('このページは「%s」の%sです。', SITE_TITLE, $page_title);
		$this->breadcrumbs['list'][] = array('uri' => '', 'name' => $page_title);

		// template
		$view_data = $this->_get_default_view_data();
		$view_data['page_title'] = $page_title;

		$this->smarty_parser->parse('ci:site/'.$this->_get_template_name('sitemap'), $view_data);
	}
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
