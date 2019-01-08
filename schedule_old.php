<?php
require_once('include/query.inc.php');
require_once('include/query_form.inc.php');
require_once('include/schedule.inc.php');
// output compression
ob_start('ob_gzhandler');
$var = &$_POST;

if(isset($var['sch_no']))
	$sch_no = $var['sch_no'];
elseif(isset($_GET['sch_no']))
	$sch_no = $_GET['sch_no'];
else
	$sch_no = 'sc1';
$var['sch_no'] = $sch_no;

if(!preg_match('/^sc([123])$/', $sch_no))
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

// Cookies must be dealed with before any context
if(!empty($_COOKIE[$sch_no])) {
	list($semester, $C) = explode('|', $_COOKIE[$sch_no]);
	$COU = explode(';', $C);
}

$SelectedFields = array('dptname', 'cou_code', 'class', 'year',
		'credit', 'forth', 'sel_code', 'cou_cname',
		'tea_cname', 'clsrom', 'daytime', 'mark', 'co_gmark');
#$SelectedFields=array_merge(array_slice($DefaultSelection,0,3), array('cou_code'),array_slice($DefaultSelection,3));
require('include/header.inc.php');
?>
<script type="text/javascript" src="js/schedule.js"></script>
<form action="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>" method="post">
<?php
$sch = array('sc1'=>'課表一', 'sc2'=>'課表二', 'sc3'=>'課表三');
formSelect('sch_no', $sch);
?>
<input type="submit" class="submit" value="變更">
</form>
<script type="text/javascript">
function deleteCookie(name) {
	document.cookie = name + "=; expires=Thu, 01-Jan-70 00:00:01 GMT";
	alert('已刪除課表 '+name);
}
</script>
<?php

echo "<input type='button' class='button' value='清除此課表 ($sch[$sch_no])' ".
		"onClick='javascript:deleteCookie(\"$sch_no\");'> ";
?>
<p style="color: red;">以下資料僅供讀取使用</p>
<?php
// BELOW ARE SIMILAR TO THOSE CODE IN COURSE.PHP

if(empty($COU)) {
  echo '<p>此課表無內容';
} else {
echo '<table border="1" width="100%" align="center">';
table_header(array_merge(array('sch_count'),$SelectedFields), false);

$total_course = $total_credit = 0;

$size = sizeof($COU);
$subquery = makeScheduleQuery($semester, $COU, implode(",", $SelectedFields), true);

if($size > 0) {
	$query = implode(" UNION ALL ", $subquery);
	$result = $dbh->query($query);
}

for($i = 0; isset($subquery) && $i < $size; ++$i) {
	$tmp = $result->fetch_assoc();
	$row["$tmp[n]"] = $tmp;
}

for($i=0; isset($subquery) && $i<$size; ++$i) {
	displayRow($row[$i], $row[$i]['t'], false, true, true, true, true);
	fillTimeTable();
	++$total_course;
	$total_credit += $row[$i]['credit'];
}
?>
</table>
<p align="center">
<?php echo "$total_course 堂課, 共 $total_credit 學分"; ?>
<p>
<?php
displayScheduleTable();
}
require('include/footer.inc.php');
?>

