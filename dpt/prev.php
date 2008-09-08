<?php
// output compression
ob_start('ob_gzhandler');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>系所列表</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<?php
#<link rel="stylesheet" type="text/css" href="prev_menu.css" title="Menu View">
#<link rel="stylesheet" type="text/css" href="prev_list.css" title="List View">
#<script type="text/javascript" src="styleswitcher.js"></script>
?>
<script language="JavaScript" type="text/javascript">
function disableStyleSheet() {
	document.getElementsByTagName("style")[0].disabled = true;
	document.getElementsByTagName("style")[1].disabled = true;
}
</script>
<style type="text/css">
h1 { font-size: 16pt; font-family: Tahoma; }
a {font-size: 11pt; }
#help {font-size: 11pt; }

ul.makeMenu, ul.makeMenu ul {
	width: 250px;
	border: 1px solid #000;
	background-color: #99ccff;
	padding-left: 0px;
	margin-left: 0px;
}
ul.makeMenu li {
	list-style-type: none;
	padding: 2px;
	position: relative;
	color: #000;
	cursor: default;
	font-size: 10pt;
}
ul.makeMenu li > ul {
	display: none;
	position: absolute;
	top: -12px;
	left: 248px;
}
ul.makeMenu li:hover {
	background-color: #ffffcc;
	color: #000;
}
ul.makeMenu li:hover > ul {
  display: block;
}
</style>
<!--[if gte IE 5]>
<style type="text/css">
/* that IE 5+ conditional comment makes this only visible in IE 5+ */
ul.makeMenu li {
	behavior: url('IEmen.htc');
}
ul.makeMenu ul {
	display: none; position: absolute; top: 2px; left: 248px;
}
</style>
<![endif]-->
</head>
<body>
<?php
$available = array(97, 96, 95, 94, 93, 92, 91, 90);
$year = $_GET[year];
if(!in_array($year, $available))
	$year = $available[0];
echo "<h1>$year 年度</h1>";
foreach($available as $v)
	if($v != $year)
		echo "<a href=\"$_SERVER[PHP_SELF]?year=$v\">$v 年度</a> ";
?>
<ol id="help">
<li>選了文學院就等於選了文學院的所有系, 以此類推. 有分組的系也是如此.
<li>將所要選擇的院系以<span style="color: #ff0000">逗號分隔相連接, 且中間無任何空白</span>.
例如, 要限定查詢 體育, 數學系, 以及管理學院的課程時, 填寫: <input size="15" value="T010,2010,7"></li>
</ol>
<script language="JavaScript" type="text/javascript">
document.writeln('<a href="javascript:disableStyleSheet();">關閉階層式選單</a> (抱歉, IE 尚無法正常使用)');
</script>
<?php
include("$year.html");
?>
</body>
</html>
