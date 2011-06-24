<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Task extends MY_Controller
{
	private $limit  = 10;
	private $offset = 0;
	private $order  = 0;
	private $search = '';

	function __construct()
	{
		parent::__construct();

		// load models
		$this->load->model('task/model_task');
	}

	public function index()
	{
		$this->smarty_parser->parse('ci:task/index.tpl', array());
	}

	public function test()
	{
		echo '<b>1111111111</b>';
	}

	public function ajax_task_list()
	{
		// template
		$view_data = $this->_get_default_view_data();
		$view_data['list'] =  $this->model_task->get_main_list($this->offset, $this->limit);

		$this->smarty_parser->parse('ci:task/list.tpl', $view_data);
	}

	private function _get_default_view_data()
	{
		return array(
/*
			'site_keywords'    => $this->site_keywords,
			'site_description' => $this->site_description,
			'breadcrumbs' => $this->breadcrumbs,
*/
		);
	}

	public function execute_insert()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('body', '内容', 'trim|required');
		if (!$this->form_validation->run()) return;

		// 登録
		$this->model_task->insert(array('body' => set_value('body')));
	}

	public function execute_update()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'id番号', 'trim|required|is_natural_no_zero');
		$this->form_validation->set_rules('value', '内容', 'trim|required');
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
$isActive = 1;
$isExit   = 0;
$isEcho   = 0;
$isAdd    = 1;
if ($isActive) {
  $type = 'wb';
  if ($isAdd) $type = 'a';
  $fp = fopen("/tmp/test.log", $type);
  ob_start();
  var_dump($_POST, set_value('value'), set_value('id'));
  $out=ob_get_contents();
  fwrite( $fp, $out . "\n" );
  ob_end_clean();
  fclose( $fp );
  if ($isEcho) echo $out;
if ($isExit)  exit;
}
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		if (!$this->form_validation->run()) return;

		// 登録
		$this->model_task->update4id(array('body' => set_value('value')), set_value('id'));

		echo nl2br(set_value('value'));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
