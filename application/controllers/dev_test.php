<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Dev_test extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');

		if (!DEV_MODE) common_error();
	}

	public function index()
	{
		$this->session->set('hoge', 1111111);
		var_dump($this->session->get('hoge'));
		$this->smarty_parser->parse('ci:session_test/index.tpl', array());
	}

	public function execute_test()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('input', 'ユーザ名', 'trim|required');
		$this->form_validation->run();

		$data = array();
		$data['raw'] = $_POST['test'];
		$data['input'] = $this->input->post('test');
		$this->smarty_parser->parse('ci:session_test/index.tpl', $data);
	}
}
