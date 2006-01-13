<?
require_once('include/query.inc.php');
require_once('include/query_form.inc.php');
require_once('include/schedule.inc.php');
require('include/header.inc.php');
// output compression
ob_start();
if(empty($_GET['s']) && empty($_POST['s'])) {

if(isset($_GET['sch_no']))
	$sch_no = $_GET['sch_no'];
	else
$sch_no = 'sc1';

if(!empty($_COOKIE[$sch_no])) {
	list($semester, $C) = explode('|', $_COOKIE[$sch_no]);
	$COU = explode(';', $C);
}
		
if(!empty($_COOKIE[$sch_no])) {
?>
您的課表 URL 是:
<textarea id="url" cols="" style="width: 100%;" wrap="virtual">
<?
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?s='.
	str_replace('=','',base64_encode(gzdeflate($_COOKIE[$sch_no])));
echo $url;
?>
</textarea>
<script type="text/javascript">
document.getElementById("url").select();
</script>
<p>長網址縮短可使用:
<?
if(strlen($url) < 498) {
?>
<form action="http://0rz.net/create.php" method="post" style="margin: 0px;">
<table cellpadding="5" bgcolor="#e7e7f7"><tr><td>
<b>很長的 URL</b><br>
<input type="text" name="url" size="30" value="<?=$url?>">
<input type="Submit" value="Make Shorter" style="border:1px solid gray;">
</td></tr>
<tr><td align="right"><a href="http://0rz.net/" target="_blank"><img src="http://0rz.net/logo/0rz.jpg" border="0"></a></td></tr></table>
</form>
<? } ?>
<p>
<form action="http://tinyurl.com/create.php" method="post" target="_blank">
<table cellpadding="5" bgcolor="#E7E7F7"><tr><td>
<b>很長的 URL</b><br>
<input type="text" name="url" size="30" value="<?=$url?>">
<input type="submit" name="submit" value="Make TinyURL!">
</td></tr></table>
</form>

<? } else { ?>
<p>此課表無內容.
<? } ?>
<p><table border="1" width="100%" align="center">
<?
} else { // DISPLAY
$var = &$_POST;
$csv = empty($var['csv']) ? false : true;
$s = empty($_GET['s']) ? $_POST['s'] : $_GET['s'];
$decoded = gzinflate(base64_decode(str_replace(' ', '+', $s)));

if(!empty($decoded)) {
	list($semester, $C) = explode('|', $decoded);
	$COU = explode(';', $C);
}
?>
<script type="text/javascript" src="js/schedule.js"></script>
<h1>課表<?=!empty($semester)?" (學期 $semester)":'';?></h1>
<table border="0">
<tr valign="top"><td>
<form action="http://<?=$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="s" value="<?=$s?>">
<table border="0" bgcolor="#FFFFCC">
<tr valign="top"><td rowspan="2"><span style="font-size: 80%;">欄位:</span><br><? formOutColSelect(); ?>
<td><? formCheckbox('csv', '純文字', '可更方便地存入 Excel:<br>編輯->選擇性貼上->文字'); ?><br>
<tr><td><input type="submit" class="submit" value="變更">
</table></form>
</table>
<?
if(empty($COU)) {
	echo '<p>此課表無內容';
} else {
?>
<form action="http://<? echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>" method="post" name="del_sel">
<input type="hidden" name="delete" value="1">
<input type="hidden" name="sch_no" value="<? echo $sch_no; ?>">
<?
if(!$csv) {
	echo '<table border="1" width="100%" align="center">';

// BELOW ARE SIMILAR TO THOSE CODE IN COURSE.PHP
	table_header(array_merge(array('sch_count'),$SelectedFields), false);

} else { // CSV
	table_header(array_merge(array('sch_count'),$SelectedFields), true);
}

$total_course = $total_credit = 0;

$SelectedFieldsSQL = column_sql($SelectedFields, split(" ", "cou_cname cou_code dpt_code class daytime credit"));

if(isset($COU)) {
	$size = sizeof($COU);
	$subquery = makeScheduleQuery($semester, $COU, $SelectedFieldsSQL);
} else
	$size = 0;

if($size > 0) {
	$query = implode(" UNION ALL ", $subquery);
	$result = mysql_query($query, $dbh);
}

for($i = 0; isset($subquery) && $i < $size; ++$i) {
	$tmp = mysql_fetch_assoc($result);
	$row["$tmp[n]"] = $tmp;
}

for($i = 0; $i < $size; ++$i) {
	displayRow($row[$i], $row[$i]['t'], $csv, true, false, true, true);
	fillTimeTable();
	++$total_course;
	$total_credit += $row[$i]['credit'];
}
echo ($csv ? '</pre>' : '</table>');
?>
</form>
<p align="center">
<? echo "$total_course 堂課, 共 $total_credit 學分"; ?>
<p>
<?
displayScheduleTable();
} // END OF CONDITION 'COURSE(S) IN SCHEDULE'
}
require('include/footer.inc.php');
?>

