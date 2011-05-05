<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_util
{

	function __construct()
	{
	}

	public function set_post_data_from_submit_key($submit_key, $post_key, $is_return = false)
	{
		$id = (int)key($_POST[$submit_key]);
		$_POST[$post_key] = $id;

		if ($is_return) return $id;
	}

	// 本文よりURLを抽出する
	public function get_url_from_body($body, $is_all = false)
	{
		$buffer = $body;
		$http_URL_regex = "(s?https?:\/\/[-_.!~*'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)";
		preg_match_all("/$http_URL_regex/", $buffer, $urls);
		if ($urls[0])
		{
			$unique_urls = array_unique($urls[0], SORT_STRING);
			$new_urls = array();
			foreach ($unique_urls as $url)
			{
				$deny_regx = ".+\.(jpg)|(jpeg)|(gif)|(png)|(js)|(css)|(swf)|(rss)|(rdf)$";
				if (preg_match("/$deny_regx/", $url)) continue;

				if (!$is_all) return $url;
				$new_urls[] = $url;
			}
		}

		return $new_urls;
	}

	// memo_category 名リストへ変換する
	public function convert_category_name_list($cate_list, $is_parent_only = false)
	{
		$cate_name_list = array();
		foreach ($cate_list as $row)
		{
			if ($is_parent_only && !empty($row['sub_id'])) continue;

			$id = $row['id'];
			$cate_name_list[$id] = $row['name'];
		}

		return $cate_name_list;
	}
}
