<?php
require_once('include/query.inc.php');
// output compression
ob_start('ob_gzhandler');

$se = $_GET['se'];
$cou_code = $_GET['c1'].' '.$_GET['c2'];
$class = $_GET['class'];

$pattern = array("se" => "/^\d\d_\d$/",
				"cou_code" => "/^[0-9A-Z]{3} [0-9A-Z]{5}$/",
				"class" => "/^.{0,2}$/");
foreach($pattern as $f => $p)
		if(!preg_match($p, $$f)) {
			echo "Input error at $f";
			exit();
		}

$SelectedFields = array('dptname', 'ser_no', 'cou_cname', 'credit', 'year',
'tea_cname', 'clsrom', 'daytime', 'mark', 'co_gmark');

$query = "SELECT tea,$se.".implode(",$se.", $SelectedFields).",dpt_code,comment,$se.cou_code".
" FROM `$se` NATURAL LEFT JOIN `comment$se` WHERE $se.cou_code = '$cou_code'".
" AND $se.class = '$class' LIMIT 0 , 30 ";
$result = mysql_query($query, $dbh);
if(!$result) {
	header('Content-Type: text/plain; charset=utf-8');
	die('此學期資料不存在');
}
$size = mysql_num_rows($result);
$table_info = mysql_fetch_array(mysql_query("show table status from $db like 'comment$se'"));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta name="robots" content="noindex,nofollow">
<title>選課輔助程式</title>
</head>
<body bgcolor="#FFFFFF">
<?php
if($size != 0) {  
	echo "<p>學期: $se<br>課號: $cou_code<br>班次: $class";
	echo '<p><table border="1"><tr>';

	foreach($SelectedFields as $s)
		echo '<th>' . preg_replace("/<br>/", '', $AllFields[$s]);

	while($row = mysql_fetch_assoc($result)) {
		displayRow($row, $se, false, true, true);
		$comment = $row['comment'];
		$dptcode = $row['dpt_code'];
	}
	echo "</table>";
	if(array_shift(array_keys($SEMESTERS)) == $se) {
		$r = mysql_fetch_assoc($result);
		$tea = $r['tea'];
		mysql_data_seek($result, 0);
		echo "<p>";
		list($c1, $c2) = explode(' ', $cou_code);
		echo '<a href="https://nol.ntu.edu.tw/nol/coursesearch/print_table.php?'.
		"course_id=$c1 $c2&amp;class=$class&amp;dpt_code=$dptcode&amp;semester=".str_replace("_","-",$se).
		"\">查詢教務處網頁上的課程大綱</a>";
	}
?>
<p>本地端備份資料: (最後更新日期 <?php echo $table_info['Update_time']; ?>. 可能較舊, 請再次確認)
<p><div style="padding: 20px; background: #fffcd8; border: 3px solid #ccc; width: 80%;">
<?php
	if(preg_match("/^http(s?):\/\//", $comment))
		echo "<a href=\"".urldecode($comment)."\">".urldecode($comment)."</a>";
	else
		echo nl2br($comment);
	echo '</div>';
} else {
	echo "<p>查無資料";
}
?>
</body>
</html>
