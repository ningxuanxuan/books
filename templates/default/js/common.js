function strtotime(str)
{
	return Date.parse(str) / 1000;
}

function time2string( timestamp )
{
	var dt = new Date(timestamp);
	year = dt.getYear() + 1900 + "";
	month = dt.getMonth() + 1 + "";
	day   = dt.getDate() + "";
	
	return year + "-" + month + "-" + day;
}