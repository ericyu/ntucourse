<?php
require_once('include/query.inc.php');
require_once('include/query_form.inc.php');
require_once('include/schedule.inc.php');
// output compression
ob_start('ob_gzhandler');
$var = &$_POST;
$csv = empty($var['csv']) ? false : true;

// Cookies must be dealed with before any context

if(isset($var['sub1']))		// 課表一/二/三
	$sch_no = $var['sch_no1'];
elseif(isset($var['sub0']))
	$sch_no = $var['sch_no0'];
elseif(isset($var['sch_no']))
	$sch_no = $var['sch_no'];
elseif(isset($_GET['sch_no']))
	$sch_no = $_GET['sch_no'];
else
	$sch_no = 'sc1';

$var['sch_no'] = $sch_no;

if(!preg_match('/^sc([123])$/', $sch_no))
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

if(!empty($_COOKIE[$sch_no])) {
	list($semester, $C) = explode('|', $_COOKIE[$sch_no]);
	$COU = explode(';', $C);
}

if(!empty($var['add'])) {				// ADD
	if(!empty($semester)) {
		if($semester != $var['semester']) {
			die("所加課程之資料與本課表學期不符合 (課表: $semester v.s. 新增: $var[semester])");
		}
	} else {
		$semester = $var['semester'];
	}

	if(isset($var['selected_cou'])) {
		foreach($var['selected_cou'] as $t)
			$COU[] = $t;
		setcookie($sch_no, $semester.'|'.implode(';', $COU), time() + 5184000);
	}
} elseif(!empty($var['delete']) && sizeof($var['selected_cou'])) {	// DELETE
	$COU_tmp = array();
	for($i = 0; $i < sizeof($COU); ++$i) {
		if(in_array($i, $var['selected_cou']))		// Pass the deleted one
			continue;
		$COU_tmp[] = $COU[$i];
	}
	$COU = $COU_tmp;
	unset($COU_tmp);
	if(sizeof($COU) > 0)
		setcookie($sch_no, $semester.'|'.implode(';', $COU), time() + 5184000);
	else {
		unset($semester);
		setcookie($sch_no, '', time() - 3600);
	}
}
require('include/header.inc.php');
?>
<script type="text/javascript" src="js/schedule.js"></script>
<h1>課表<?php echo !empty($semester)?" (學期 $semester)":''; ?></h1>
<!--
<p style="color: red;">如果有因為加入時沒有流水號, 而因此無法在這頁讀出來的課程,
請先讀取<a href="schedule_old.php?sch_no=<?php echo $sch_no; ?>">原先的課表</a>, 記下後, 再於本頁刪除該課程, 然後重新加入.</p>
-->
<table border="0">
<tr valign="top"><td>
<form action="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>" method="post">
<table border="0" bgcolor="#FFFFCC">
<tr valign="top"><td rowspan="2"><span style="font-size: 80%;">欄位:</span><br><?php formOutColSelect(); ?>
<td><?php formCheckbox('csv', '純文字', '可更方便地存入 Excel:<br>編輯->選擇性貼上->文字'); ?><br>
<?php
$sch = array('sc1'=>'課表一', 'sc2'=>'課表二', 'sc3'=>'課表三');
formSelect('sch_no', $sch);
?>
<tr><td><input type="submit" class="submit" value="變更">
</table></form>
<td>
<a href="ln.php?sch_no=<?php echo $sch_no; ?>">分享此課表</a><br><br>
<a href="schedule_order.php?sch_no=<?php echo $sch_no; ?>">調整列表順序</a><br>
<a href="schedule_p.php?sch_no=<?php echo $sch_no; ?>">輸出成有完整課名的課表</a>
</table>
<?php
if(empty($COU)) {
	echo '<p>此課表無內容';
} else {
?>
<form action="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>" method="post" name="del_sel">
<input type="hidden" name="delete" value="1">
<input type="hidden" name="sch_no" value="<?php echo $sch_no; ?>">
<?php
if(!$csv) {
	echo '<table border="1" width="100%" align="center">';
	// BELOW ARE SIMILAR TO THOSE CODE IN COURSE.PHP
	table_header(array_merge(array('sch_count'),$SelectedFields), false);
} else { // CSV
	table_header(array_merge(array('sch_count'),$SelectedFields), true);
}

$total_course = $total_credit = 0;

$SelectedFieldsSQL = column_sql($SelectedFields, preg_split("/ /", "cou_cname cou_code dpt_code class daytime credit"));

if(isset($COU)) {
	$size = sizeof($COU);
	$subquery = makeScheduleQuery($semester, $COU, $SelectedFieldsSQL);
} else
	$size = 0;

if($size > 0) {
	$query = implode(" UNION ALL ", $subquery);
	$result = $dbh->query($query);
}

for($i = 0; isset($subquery) && $i < $size; ++$i) {
	$tmp = $result->fetch_assoc();
	$row["$tmp[n]"] = $tmp;
}

for($i = 0; $i < $size; ++$i) {
	displayRow($row[$i], $row[$i]['t'], $csv, true, false, true);
	fillTimeTable();
	++$total_course;
	$total_credit += $row[$i]['credit'];
}
echo ($csv ? '</pre>' : '</table>');
?>
<table border="0"><tr valign="middle">
<td><img src="images/arrow_lt.gif" alt="lt">
<td><span style="font-size: 10pt">
<a href="javascript:setCheckboxes('del_sel',true)">全部勾選</a> /
<a href="javascript:setCheckboxes('del_sel',false)">全部取消</a></span>
<td>&nbsp;&nbsp;<input type="submit" class="submit" value="刪除">
</td></tr></table>
</form>
<p align="center">
<?php echo "$total_course 堂課, 共 $total_credit 學分"; ?>
<p>
<?php
displayScheduleTable();
} // END OF CONDITION 'COURSE(S) IN SCHEDULE'
require('include/footer.inc.php');
?>
