<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * webmemo helper
 * 
 * @author uzuranoie@gmail.com
 */

// search_word から検索対象カテゴリを取得
function get_category_from_search_word($search_word, $is_task = false)
{
	$search_word = urldecode($search_word);
	$search_cate_id = 0;
	if (preg_match('/^([a-zA-Z0-9０-９ａ-ｚＡ-Ｚ_＿\-ー]+)[:：]{1}(.+)?$/u', $search_word, $matches))
	{
		$word = mb_convert_kana($matches[1], 'a', 'UTF-8');

		$CI =& get_instance();
		$CI->load->model('webmemo/category');
		$search_cate_id = $CI->category->get_id4key($word);

		if ($search_cate_id) $search_word = '';
		if ($search_cate_id && !empty($matches[2])) $search_word = $matches[2];
	}

	return array($search_word, $search_cate_id);
}

/* End of file Webmemo.php */
/* Location: ./application/helpers/webmemo_helper.php */
