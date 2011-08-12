function util_check_date_format(value) {
	var ret = true;
	var result = value.match(/^(2[0-9]{3})\-([0-9]{2})\-([0-9]{2})$/);
	if (!result) ret = false;
	if (ret == true) {
		var yy = parseInt(result[1], 10);
		var mm = parseInt(result[2], 10);
		var dd = parseInt(result[3], 10);
		if (yy < 1901 || yy > 2999) ret = false;
		if (mm < 1 || mm > 12) ret = false;
		if (dd < 1 || dd > 31) ret = false;
	}

	return ret;
}

function util_check_numeric(value) {
//  return value.match(/^[0-9]{1,}(\.[0-9]+)?$/);
	if (value != parseFloat(value)) return false;
	return true;
}

function util_check_integer(value) {
//  return value.match(/^[1-9]{1}{0-9}+$/);
	if (value != parseInt(value)) return false;
	return true;
}
