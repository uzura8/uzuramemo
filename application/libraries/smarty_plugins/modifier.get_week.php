<?php

// from textile to html
function smarty_modifier_get_week($w)
{
  $CI =& get_instance();
  return $CI->date_util->get_week($w);
}
