<?php

// from textile to html
function smarty_modifier_textile($body)
{
  $CI =& get_instance();
  return $CI->textile->TextileThis($body);
}
