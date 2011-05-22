<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function is_url($str)
{
	if (preg_match("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str)) return true;

	return false;
}

function is_site_url($str)
{
	if (preg_match(sprintf("#^%s\w+[^\s\)\<]+$#i", site_url()), $str)) return true;

	return false;
}

function auto_link($str, $type = 'both', $popup = FALSE, $trimwidth = 0)
{
	if ($type != 'email')
	{
		if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches))
		{
			$pop = '';
			if ($popup)
			{
				if ($popup === true)
				{
					$popup = '_blank';
				}
				$pop = sprintf(' target="%s" ', $popup);
			}

			for ($i = 0; $i < count($matches['0']); $i++)
			{
				$period = '';
				if (preg_match("|\.$|", $matches['6'][$i]))
				{
					$period = '.';
					$matches['6'][$i] = substr($matches['6'][$i], 0, -1);
				}

				$url = sprintf('http%s://%s%s', $matches['4'][$i], $matches['5'][$i], $matches['6'][$i]);
				$url_display = $url;
				if ($trimwidth) $url_display = mb_strimwidth($url, 0, $trimwidth, '...');
				$replaced = sprintf('<a href="%s"%s>%s</a>', $url, $pop, $url_display);

				$str = str_replace($matches['0'][$i], $replaced, $str);
			}
		}
	}

	if ($type != 'url')
	{
		if (preg_match_all("/([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches))
		{
			for ($i = 0; $i < count($matches['0']); $i++)
			{
				$period = '';
				if (preg_match("|\.$|", $matches['3'][$i]))
				{
					$period = '.';
					$matches['3'][$i] = substr($matches['3'][$i], 0, -1);
				}

				$str = str_replace($matches['0'][$i], safe_mailto($matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i]).$period, $str);
			}
		}
	}

	return $str;
}
