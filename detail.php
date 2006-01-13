<?
require_once('include/query.inc.php');
// output compression
ob_start('ob_gzhandler');
$info[1] = 'http://investea.aca.ntu.edu.tw/course/course_asp/ConQuery.asp';
$info[2] = 'http://couweb3.aca.ntu.edu.tw/course/course_asp/ConQuery.asp';

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

$query = "SELECT tea,$se.".implode(",$se.", $SelectedFields).",comment,$se.cou_code".
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
<?
if($size != 0) {  
	if(array_shift(array_keys($SEMESTERS)) == $se) {
		$r = mysql_fetch_assoc($result);
		$tea = $r['tea'];
		mysql_data_seek($result, 0);
/*
		echo "<p>查詢教務處網頁上的課程大綱: ";
		list($c1, $c2) = explode(' ', $cou_code);
		foreach($info as $i => $loc)
			echo "<a href=\"$loc?COU_CODE_1=$c1&amp;COU_CODE_2=$c2&amp;CLASS_1=$class&amp;tea=$tea\">伺服器 $i</a>&nbsp;&nbsp;";
*/
	}

	echo "<p>學期: $se<br>課號: $cou_code<br>班次: $class";
	echo '<p><table border="1"><tr>';

	foreach($SelectedFields as $s)
		echo '<th>' . preg_replace("/<br>/", '', $AllFields[$s]);

	while($row = mysql_fetch_assoc($result)) {
		displayRow($row, $se, false, true, true);
		$comment = $row['comment'];
	}
	echo "</table>";
?>
<p>本地端備份資料: (最後更新日期 <?=$table_info['Update_time']?>. 可能較舊, 請再次確認)
<p><div style="padding: 20px; background: #fffcd8; border: 3px solid #ccc; width: 80%;">
<?
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
