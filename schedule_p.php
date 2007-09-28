<?php
require_once('include/query.inc.php');
require_once('include/query_form.inc.php');
require_once('include/schedule.inc.php');
// output compression
ob_start('ob_gzhandler');

if(isset($_GET['sch_no']))
	$sch_no = $_GET['sch_no'];
else
	$sch_no = 'sc1';

if(!preg_match('/^sc([123]?)$/', $sch_no))
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

if(!empty($_COOKIE[$sch_no])) {
	list($semester, $C) = explode('|', $_COOKIE[$sch_no]);
	$COU = explode(';', $C);
}
		
require('include/header.inc.php');
?>
<script type="text/javascript" src="js/color.js"></script>
<script type="text/javascript">
function PlainText(val) {
	var tbl = document.getElementById("tbl");
	tbl.style.display = (val == 1 ? "none" : "block");
}
</script>
<h1>課表輸出格式</h1>
<p style="color: red;">注意: 所選課程必須不衝堂才能輸出!</p>
<form name="Form" action="http://<?php echo $_SERVER['HTTP_HOST'].preg_replace("/schedule_p/", "schedule_pout", $_SERVER['PHP_SELF']); ?>" method="post">
<input type="hidden" name="fieldName" value="">
<input type="hidden" name="sch_no" value="<?php echo $sch_no; ?>">
輸出欄位<br><?php formOutColSelect(); ?><br>
<hr width="30%">
<input type="checkbox" name="display_place" id="display_place" value="1"><label for="display_place">顯示地點</label><br>
<input type="checkbox" name="display_time" id="display_time" value="1"><label for="display_time">顯示課堂時間 (8:10-9:00, ...)</label><br>
<input type="checkbox" name="trans" id="trans" value="1"><label
for="trans">轉置顯示</label><br>
<input type="checkbox" name="no_sat" id="no_sat" value="1" checked><label for="no_sat">不顯示週六</label><br>
<input type="checkbox" name="no_zero" id="no_zero" value="1" checked><label for="no_zero">不顯示第 0 節</label><br>
<input type="checkbox" name="no_nine" id="no_nine" value="1" checked><label for="no_nine">不顯示第 9 節及 A/B/C/D 節</label><br>
<input type="checkbox" name="no_night" id="no_night" value="1"><label for="no_night">不顯示 A/B/C/D 節</label>
<hr width="30%">
<input type="checkbox" name="text" id="text" onClick="javascript:PlainText(this.checked);"><label for="text">純文字輸出</label><br>
畫面寬度: <input type="text" name="width" value="76" maxlength="3" size="3"> (一般 BBS 最大寬度為 80)
<hr width="30%">
<div id="tbl">
<input type="checkbox" name="no_link" id="no_link" value="1"><label for="no_link">課名不顯示連結</label><br>
課表背景顏色: <input type="text" name="bg" value="#f5f5f5" size="5" maxlength="7" style="background: #f5f5f5;">
<input type="button" class="button" name="testClr1" value="選取" onclick="NewWindow('bg');return false;"><br>
課表文字顏色: <input type="text" name="fg" value="#000000" size="5" maxlength="7" style="background: #000000;">
<input type="button" class="button" name="testClr2" value="選取" onclick="NewWindow('fg');return false;">
<table border="1">
<?php
$SelectedFields = array('ser_no', 'dptname', 'class', 'year', 'credit',
				'cou_cname', 'tea_cname', 'clsrom', 'daytime');

$SelectedFieldsSQL = column_sql($SelectedFields, array('cou_code', 'dpt_code'));

if(isset($COU)) {
	$size = sizeof($COU);
	$subquery = makeScheduleQuery($semester, $COU, $SelectedFieldsSQL);
} else
	$size = 0;

table_header(array_merge(array('bgcolor'), $SelectedFields));

if($size > 0) {
	$query=implode(" UNION ALL ", $subquery);
	$result = mysql_query($query, $dbh);
}

for($i = 0; isset($subquery) && $i < $size; ++$i) {
	$tmp = mysql_fetch_assoc($result);
	$row["$tmp[n]"] = $tmp;
}

for($i=0; isset($subquery) && $i<$size; ++$i) {
	$bgcolorSet = '<td><input type="text" name="CouClr'.$i.'" value="#ffffff" size="5" maxlength="7">'.
		'<input type="button" class="button" name="Clr'.$i.'" value="選取" onclick="NewWindow(\'CouClr'.$i.'\');return false;">';
	displayRow($row[$i], $row[$i]['t'], false, true, false, false, true, $bgcolorSet);
}
?>
</table>
</div>
<p><input type="submit" class="submit" value="送出">
</form>
<?php
require('include/footer.inc.php');
?>
