<?php
ob_start('ob_gzhandler');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta name="robots" content="noindex,nofollow">
<title>課程異動記錄</title>
</head>
<body bgcolor="#FFFFFF">
<?php
if(!preg_match("/^\d{4}-\d{1,2}-\d{1,2}$/", $_GET['d1']))
	echo "error in d1<br>";
if(!preg_match("/^\d{4}-\d{1,2}-\d{1,2}$/", $_GET['d2'])) {
	echo "error in d2<br>";
	die();
}
?>
<a name="top">
顏色代表的是:
<table border='1'><tr bgcolor='#FFEFC0'><td>前次的內容(<?php echo $_GET['d1']; ?>)
<tr bgcolor='#89D8FE'><td>本次的內容(<?php echo $_GET['d2']; ?>)
<tr bgcolor='#FF9966'><td>新增的內容(<?php echo $_GET['d2']; ?>)</table>
<?php
include("./diffs/$_GET[d2].out");
?>
</body>
</html>
