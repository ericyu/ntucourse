<?
include_once('include/query.inc.php');
include_once('include/schedule.inc.php');
// output compression
ob_start('ob_gzhandler');
$var = &$_POST;
$sel_column = $pre_selection;

if(isset($_GET['sch_no']))
	$sch_no = $_GET['sch_no'];
else
	$sch_no = 'SCHEDULE';

if(!preg_match('/^SCHEDULE([23]?)$/', $sch_no))
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

if(isset($_COOKIE[$sch_no]))
	$COU = preg_split("/;/", $_COOKIE[$sch_no]);

// Cookies must be dealed with before any context
if(isset($_COOKIE[$sch_no]))
	$COU = preg_split('/;/', $_COOKIE[$sch_no]);
if(isset($var['src']) && isset($var['dst'])) {
	$src = $var['src'] - 1;
	$dst = $var['dst'] - 1;
	if($src >= sizeof($COU) || $dst >= sizeof($COU)) {
		echo 'Wrong position!';
		return;
	}
	$hopper = $COU[$src];
	$array = array_merge(array_slice($COU,0,$src), array_slice($COU,$src+1));
	$array = array_merge(array_slice($array,0,$dst), $hopper, array_slice($array,$dst));
	$COU = $array;
	setcookie($sch_no,implode(';', $COU), time()+5184000);
}
include('include/header.inc.php');
?>
<script type="text/javascript">
function swap(src, dst) {
	document.order.src.value=src;
	document.order.dst.value=dst+1;
	document.order.submit();
}
</script>
<a href="schedule.php?sch_no=<? echo $sch_no; ?>">返回課表</a>
<p>(本頁功能需 JavaScript)
<?
if(empty($COU)) {
	echo '<p>此課表無內容';
} else {
?>
<form action="http://<? echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?sch_no=".$sch_no; ?>" method="post" name="order">
<input type="hidden" name="src" value="">
<input type="hidden" name="dst" value="">
<table border="1" width="100%" align="center">
<?
// BELOW ARE SIMILAR TO THOSE CODE IN COURSE.PHP

table_header(array_merge(array('sch_count'), $sel_column));

$total_course = $total_credit = 0;
$sel_column_sql = column_sql($sel_column, split(" ", "cou_code dpt_code daytime credit"));

$size = sizeof($COU);
$subquery = schedule_make_query($COU, $sel_column_sql);

if($size > 0) {
	$query=implode(" UNION ALL ", $subquery);
	$result = mysql_query($query, $dbh);
}

for($i = 0; isset($subquery) && $i < $size; ++$i) {
	$tmp = mysql_fetch_assoc($result);
	$row["$tmp[n]"] = $tmp;
}

for($i = 0; isset($COU) && $i < $size; ++$i)
	displayRow($row[$i], $row[$i]['t'], false, true, false, false, '<td>'.sel_order($size, $i+1));
echo '</table>';
?>
</form>
</table>
<?
} // END OF CONDITION 'COURSE(S) IN SCHEDULE'
?>
<p><a href="schedule.php?sch_no=<? echo $sch_no; ?>">返回課表</a>
<?
include('include/footer.inc.php');

function sel_order($size, $pos) {
	$str = "<select name=\"pos$pos\" size=\"1\" onChange=\"javascript:swap($pos, this.form.pos$pos.selectedIndex);\">";
	for($i = 1; $i <= $size; ++$i) {
		$str .= '<option'.($i==$pos?' selected':'').'>'.$i;
	}
	$str .= '</select>';
	return $str;
}
?>
