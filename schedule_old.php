<?
require_once('include/query.inc.php');
require_once('include/query_form.inc.php');
require_once('include/schedule.inc.php');
// output compression
ob_start('ob_gzhandler');
$var = &$_POST;

if(isset($var[sch_no]))
	$sch_no = $var[sch_no];
else
	$sch_no = 'SCHEDULE';

if(!preg_match('/^SCHEDULE([23]?)$/', $sch_no))
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

// Cookies must be dealed with before any context
if(isset($_COOKIE[$sch_no]))
	$COU = preg_split("/;/", $_COOKIE[$sch_no]);

$sel_column=array('dptname', 'cou_code', 'class', 'year',
		'credit', 'forth', 'sel_code', 'cou_cname',
		'tea_cname', 'clsrom', 'daytime', 'mark', 'co_gmark');
#$sel_column=array_merge(array_slice($pre_selection,0,3), array('cou_code'),array_slice($pre_selection,3));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta name="robots" content="noindex,nofollow">
<style type="text/css">
	TH { font-size: 80%; white-space: nowrap; text-align: center; }
</style>
<title>課表</title>
</head>
<body bgcolor="#FFFFFF">
<form action="http://<? echo $_SERVER[HTTP_HOST].$_SERVER[PHP_SELF]; ?>" method="post">
<?
$sch=array("SCHEDULE"=>"課表一", "SCHEDULE2"=>"課表二", "SCHEDULE3"=>"課表三");
pull_down('sch_no', $sch);
?>
<input type="submit" class="submit" value="變更">
</form>
<p style="color: red;">以下資料僅供讀取使用</p>
<? if(!$var[csv]) {
	echo '<table border="1" width="100%" align="center">';

// BELOW ARE SIMILAR TO THOSE CODE IN COURSE.PHP

table_header($sel_column);
echo "</tr>\n";

} else { // CSV
	echo '<pre>';
	$tmp = array();

	foreach($sel_column as $s) {
			$tmp[] = $all_field[$s];
	}
	echo implode("\t", $tmp)."\n";
}

$total_course = $total_credit = 0;

$size = sizeof($COU);
$subquery = schedule_make_query($COU, implode(",", $sel_column));

if($size > 0) {
	$query=implode(" UNION ALL ", $subquery);
	$result = mysql_query($query, $dbh);
}

for($i = 0; isset($subquery) && $i < $size; ++$i) {
	$tmp = mysql_fetch_assoc($result);
	$row["$tmp[n]"] = $tmp;
}

for($i=0; isset($subquery) && $i<$size; ++$i) {
	echo ($var[csv]?'':'<tr>');
	displayRow($row[$i], $row[$i][t], $var[csv], 1);
	echo ($var[csv]?"\n":"</tr>\n");

	$daytime = getCourseTime($row[$i]['daytime']);
	foreach($daytime as $sd) {	// 將時間填入表格
		$w = mb_substr($sd, 0, 1);
		$c = mb_substr($sd, 1);
		for($j = 0; $j < strlen($c); ++$j) {
			if($class["$w$c[$j]"] != '')
				$class["$w$c[$j]"] .= ','. ($i+1);
			else
				$class["$w$c[$j]"]=$i+1;
		}
	}
	++$total_course;
	$total_credit += $row[$i]['credit'];
}
if(!$var[csv])
	echo '</table>';
else
	echo '</pre>';
?>
<p><table border="0" align="center">
<tr><td>
</table>
<p align="center">
<? echo "$total_course 堂課, 共 $total_credit 學分"; ?>
<p><table border="1" align="center">
<?
for($c=0;$c<16;++$c) {	// $c = sizeof($ClassTimeName)
	echo '<tr>';
	for($week=0;$week<7;++$week) {
		echo '<td align="center">';
		if($c==0)
			echo $WeekdayName[$week];
		elseif($week==0)
			echo $ClassTimeName[$c];
		elseif($class["$WeekdayName[$week]$ClassTimeName[$c]"]=='')
			echo '　&nbsp;　&nbsp;　';
		else
			echo $class["$WeekdayName[$week]$ClassTimeName[$c]"];
		echo '</td>';
	}
	echo "</tr>\n";
}
?>
</table>
</body>
</html>
