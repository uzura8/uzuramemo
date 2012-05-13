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

function add_date(basedate, add_days)
{
	var bds = basedate.split('-');
	base_time = (new Date(bds[0], bds[1], bds[2])).getTime();
	add_time = add_days * 1000 * 60 * 60 * 24;
	added_date = new Date(base_time + add_time);

	y = String(added_date.getFullYear());
	m = String(added_date.getMonth());
	d = String(added_date.getDate());
	if (m.length == 1) var m = '0' + m;
	if (d.length == 1) var d = '0' + d;

	return y + '-' + m + '-' + d;
}

function get_today_for_sql_format() {
	ans = new Date();

	var month = ans.getMonth();
	var month_str = "" + month;
	var date = ans.getDate();
	var date_str = "" + date;

	//convert month to 2 digits
	var twoDigitMonth = (month_str.length === 1) ? '0' + (month + 1) : month;
	var twoDigitDate  = (date_str.length === 1) ? '0' + (date + 1) : date;

	return ans.getFullYear() + '-' + twoDigitMonth + '-' + twoDigitDate;
}

function get_date_int_format() {
	var after = (arguments.length > 0) ? arguments[0] : 0;

	var fullDate = new Date()
	var nowms = fullDate.getTime();

	//var after = 1; //何日後かを入れる
	after = after*24*60*60*1000; //ミリ秒に変換
	ans = new Date(nowms+after); //現在＋何日後 のミリ秒で日付オブジェクト生成

	var month = ans.getMonth();
	var month_str = "" + month;
	var date = ans.getDate();
	var date_str = "" + date;

	//convert month to 2 digits
	var twoDigitMonth = (month_str.length === 1) ? '0' + (month + 1) : month;
	var twoDigitDate  = (date_str.length === 1) ? '0' + (date + 1) : date;

	return ans.getFullYear() + twoDigitMonth + twoDigitDate;
}
