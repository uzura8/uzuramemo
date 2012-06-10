<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bin extends BIN_Controller
{
	public function webclip()
	{
		$url = $_SERVER['argv'][3];
		if (!$url) $this->error('No url.');
		$url = prep_url($url);
		if (!is_url($url)) $this->error('No url format.');

		$this->load->library('simple_html_dom');
		$dom = file_get_html($url);
		$title = '';
		foreach($dom->find('h2') as $element) {
			$title = $element->plaintext;
			$find = $element->outertext;
			break;
		}
		$body = $dom->find('body', 0)->innertext;

		$remove_tags = array('script', 'iframe', 'object', 'form');
		foreach($remove_tags as $remove_tag) {
			foreach($dom->find($remove_tag) as $element) {
				$tag = $element->outertext;
				$body = str_replace($tag, '', $body);
			}
		}
		$dom->clear();
		var_dump($title, $url, $body);
	}
}

/* End of file site.php */
/* Location: ./application/controllers/bin.php */
