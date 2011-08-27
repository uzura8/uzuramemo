<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form Prep
 *
 * 特殊文字のエスケープを行わないバージョン
 *
 */
function form_prep($str = '', $field_name = '')
{
	static $prepped_fields = array();

	// if the field name is an array we do this recursively
	if (is_array($str))
	{
		foreach ($str as $key => $val)
		{
			$str[$key] = form_prep($val);
		}

		return $str;
	}

	if ($str === '')
	{
		return '';
	}

	// we've already prepped a field with this name
	// @todo need to figure out a way to namespace this so
	// that we know the *exact* field and not just one with
	// the same name
	if (isset($prepped_fields[$field_name]))
	{
		return $str;
	}

	//$str = htmlspecialchars($str);

	// In case htmlspecialchars misses these.
	//$str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);

	if ($field_name != '')
	{
		$prepped_fields[$field_name] = $field_name;
	}

	return $str;
}

function form_dropdown($name = '', $options = array(), $selected = array(), $extra = '')
{
	if ( ! is_array($selected))
	{
		$selected = array($selected);
	}

	// If no selected state was submitted we will attempt to set it automatically
	if (count($selected) === 0)
	{
		// If the form name appears in the $_POST array we have a winner!
		if (isset($_POST[$name]))
		{
			$selected = array($_POST[$name]);
		}
	}

	if ($extra != '') $extra = ' '.$extra;

	$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

	$form = '<select name="'.$name.'"'.$extra.$multiple.">\n";

	foreach ($options as $key => $val)
	{
		$key = (string) $key;

		if (is_array($val) && ! empty($val))
		{
			if (isset($val['name']))
			{
				$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';
				$ext = (in_array($key, $selected)) ? ' selected="selected"' : '';
				$extra_option = '';
				if (isset($val['extra']) && $val['extra'] != '') $extra_option = ' '.$val['extra'];

				$form .= '<option value="'.$key.'"'.$extra_option.$sel.'>'.(string) $val['name']."</option>\n";
			}
			else
			{
				$form .= '<optgroup label="'.$key.'">'."\n";

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';

					$form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
				}

				$form .= '</optgroup>'."\n";
			}
		}
		else
		{
			$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';

			$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
		}
	}

	$form .= '</select>';

	return $form;
}
