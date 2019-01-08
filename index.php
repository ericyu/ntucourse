<?php
$begin_time = microtime();
//			May Be Best Viewed with Tab Space = 2
// output compression
ob_start('ob_gzhandler');
require_once('include/config.inc.php');
require_once('include/query_form.inc.php');

if(!empty($_POST['send'])) {
	require_once('include/query.inc.php');
	if($_POST['SubmitType'] == '儲存查詢' ||
		$_POST['SubmitType'] == '取回儲存' ||
		$_POST['SubmitType'] == '清除儲存') {
		require_once('include/save_read.inc.php');
		$save_read = true;
	} else {
		$var = &$_POST;
	}
}

require('include/header.inc.php');

if(empty($_POST['send'])) { ?>
<div id="opensource" style="float: right; background-color: #ffffcc; color: #ffffff; padding: 16px; font-weight: bold; font-size: 150%; border: 3px solid #cccccc; text-decoration: none;">
<a href="https://github.com/ericyu/ntucourse">開放原始碼</a>
</div>
<h1>免責聲明</h1>
本網站所使用資料來源為 <a href="https://nol.ntu.edu.tw/">台大課程網</a> 上的 "課程 Excel
檔下載"。 本網站不保證所引用資料的正確性與時效性，<em>請務必到 <a href="http://info.ntu.edu.tw">http://info.ntu.edu.tw/</a>
確認最新資料。</em>本資料庫中不包括: <em>90-1之前的學程, 90-2之前的進修學士班</em>。

<?php
$d = dir('diffs/');
while (false !== ($entry = $d->read()))
	if(preg_match('/^.+\.out$/', $entry))
		$list[] = $entry;
$d->close();
if(!empty($list)) {
	$list = array_merge(array($initday), preg_replace('/(.+)\.out$/', '\1', $list));
	natsort($list);
	$list = array_values($list);
	$list2 = preg_replace('/^\d{4}-(..?)-(..?)(_.)?$/', '\1/\2\3', $list);
	echo "<h1>課程異動記錄 - $SemesterName 課程</h1>";
	for($i = 0; $i < count($list2)-1; ++$i) {
		echo "$list2[$i] <a href=\"showdiff.php?d1=$list[$i]&amp;d2=";
		echo $list[$i+1]."\">=></a> ";
	}
	echo $list2[$i];
}
echo ' (最後更新日期：'.
	(!empty($list) ? $list2[$i] : preg_replace('/^(..?)-(..?)(_.)?$/',
	'\1/\2\3', $initday)).')';
?>
<h1>選課輔助程式</h1>
<p style="background: #FFFFCC;">
<a href="tutorial.php">使用說明</a>
<a href="javascript:example();">範例</a><br>
<span class="info">將滑鼠移到畫藍色底線的文字<span class="tooltip">這是說明</span></span>上會出現補充說明.
<span class="smallnote">(本網頁部分功能需要瀏覽器支援 JavaScript 及 Cookies 方可正常運作)</span>
</p>
<?php }
echo '<a id="switchOptLink" href="javascript:switchOptions();">';
if(!empty($_POST['send']) && empty($save_read))
	echo '<img src="images/triangle.gif" height="11" width="11" alt="closedtriangle">顯示搜尋選項</a>';
else
	echo '<img src="images/opentriangle.gif" height="11" width="11" alt="opentriangle">隱藏搜尋選項</a>';
echo '<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'" method="post" name="ThisForm">';
echo '<table id="optiontable" bgcolor="#ffffcc" border="0" cellpadding="8"'.
	((!empty($_POST['send']) && empty($save_read)) ? ' style="display: none;"' : '') . '>';
?>
<tr><td colspan="2" align="left">
<input type="hidden" name="send" value="1">
<input type="submit" class="submit" name="SubmitType" value="確定">
<input type="button" class="button" value="全部清除" onClick="javascript:ClearAll()">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" class="submit" name="SubmitType" value="儲存查詢">
<input type="submit" class="submit" name="SubmitType" value="取回儲存">
<input type="submit" class="submit" name="SubmitType" value="清除儲存">
</td></tr>
<tr><td>
<fieldset>
<legend>查詢學期</legend>
<div><?php formSelect('table', $SEMESTERS); ?></div>
</fieldset>
<fieldset>
<script type="text/javascript">
$("#table").change(updateGECheckboxes);
</script>
<legend>時間</legend>
<div>
<span style="color: #ff0000">請勾選有空堂的時間</span><br>
或 <?php formCheckbox('grep', 'Grep 模式', '改為找出: 包含「任何所選取的時間」的課程'); ?>
<hr>
<a href="javascript:FillClasses()">依課表中空堂選擇</a><br>
<input type="button" value="全部選取" onClick="javascript:checkAll(1)" class="selectButton">
<input type="button" value="取消選取" onClick="javascript:checkAll(0)" class="selectButton"><br>
<table border="0">
<?php

for($n = 0; $n <= 1; ++$n) {
	echo '<tr><td>'.($n?'下午':'上午');
	for($i = 1; $i <= 6; ++$i)
		echo '<td><input type="checkbox" name="c'.$i.($n?'p':'a').'" '.
		'onClick="javascript:checkPart(' . "$i, $n" . ', this.checked)"'.
		(empty($_POST['send']) ? ' checked' : '') . ">\n";
	echo '</tr>';
}

for($c = 0;$c < 16; ++$c) {	// $c = sizeof($ClassTimeName)
	echo '<tr>';
	for($week = 0; $week < 7; ++$week) {
		if($c == 0) {
			echo '<td bgcolor="#DDDDEE">'.$WeekdayName[$week];
			if($week!=0)
			echo '<br><input type="checkbox" onClick="javascript:checkDay('.
			$week.', this.checked)" name="day_sel"'.
			(empty($_POST['send']) ? ' checked' : '') . '>';
		} elseif($week == 0)
			echo '<td bgcolor="#DDDDEE">'.$ClassTimeName[$c].
			'<input type="checkbox" onClick="javascript:checkClass(\''.
			$ClassTimeName[$c].'\', this.checked)" name="cls_sel"'.
			(empty($_POST['send']) ? ' checked' : '') . '>';
		else {
			$curtime = $week.$ClassTimeName[$c];
			echo '<td><input type="checkbox" name="class[' . $curtime . ']"'.
				( !empty($var['class'][$curtime]) || empty($_POST['send']) ? ' checked' : '') . '>';
		}
	}
	echo "\n";
}
?>
</table>
</div>
</fieldset>
<fieldset>
<legend>顯示欄位</legend>
<div>
<span class="smallnote">(以 Ctrl 多選)</span><br>
<?php formOutColSelect(1,5); ?>
</div>
</fieldset>
<fieldset>
<legend>開學後之加選方式</legend>
<div>
<?php
$co_select_type = array(1=>'1 不限人數 上網加選',
2=>'2 取得授權碼後加選', 3=>'3 人數限制 登記分發');
foreach($co_select_type as $key => $item)
  echo '<input type="checkbox" id="cstype' . $key .
		'" name="co_select_type[' .  $key . ']"' .
		(!empty($var['co_select_type'][$key]) ? ' checked' : '') . '>'.
		'<label for="cstype' . $key . '">' . $item . '</label><br>';
?>
</div>
</fieldset>
<td>
<fieldset class="rhsodd">
<legend>院系代碼</legend>
<div>
<span class="smallnote">(體育/軍訓/大學部/研究所, 不限定請不要填)</span><br>
<input type="text" name="dpt_choice" id="dpt_choice" size="35" maxlength="200"
<?php if(!empty($_POST['send'])) fixDptInput();?> onChange="javascript:UpdateFromInput();">
<?php require('dpt/embed.html'); ?>
<a href="javascript:ClearAllInput();">清除</a>
<a target="_blank" href="dpt/prev.php">無 JavaScript | 查看歷史資料...</a>
</div>
</fieldset>
<fieldset class="rhseven">
<legend>通識</legend>
<div>
若要限定僅顯示通識, 領域為:(<a target="_blank" href="<?php echo $GE; ?>">通識說明</a>)<br>
<div id="geContainer"></div>
</div>
<script type="text/javascript">
updateGECheckboxes();
var selectedGE = new Array();
<?php
if(!empty($var['ge_sel'])) {
	foreach(array_keys($var['ge_sel']) as $i => $val) {
		echo "selectedGE[$i] = $val;";
	}
}
?>
for(var i = 0; i < selectedGE.length; i++) {
	document.getElementById("ge"+selectedGE[i]).checked = true;
}
</script>
<br>
<?php formCheckbox('no_multi_ge', '不顯示跨領域通識(需勾選至少一個通識領域)'); ?>
</fieldset>
<fieldset class="rhsodd">
<legend>課號限制</legend>
<div>
<?php
$cou_code_type=array('U'=>'大學部/研究所選修[列於研究所](U)<br>','M'=>'碩士班(M)','D'=>'博士班(D)','O'=>'以上皆非(O)');
foreach($cou_code_type as $key => $item)
	echo '<input type="checkbox" id="ccode' . $key . '" name="cou_code_type[' .
			$key . ']"' . (!empty($var['cou_code_type'][$key]) ? ' checked' : '') . '>'.
			'<label for="ccode' . $key . '">' . $item . '</label>';
?>
</div>
</fieldset>
<fieldset>
<legend>條件篩選</legend>
<div class="rhseven">
<div style="color: red">以下欄位, 填寫多項時, 以逗點或空白分隔多個條件.<br>
「不包括」的條件是指: 符合其中任一即被排除.</div>
<table style="white-space: nowrap;">
<tr><td>課名內包括<td><?php formInputText('cou_cname'); ?>
<td><span class="note"><?php formRadioButton('cou_cname'); ?></span>
<tr><td>課名內不包括<td><?php formInputText('not_cou_cname'); ?><td>

<tr><td>教師包括<td><?php formInputText('tea_cname'); ?>
<td><span class="note"><?php formRadioButton('tea_cname'); ?></span>
<tr><td>教師不包括<td><?php formInputText('not_tea_cname'); ?><td>

<tr><td>年級包括<td><?php formInputText('year'); ?>
<td><span class="note"><?php formRadioButton('year'); ?></span>
<tr><td>年級不包括<td><?php formInputText('not_year'); ?><td>

<tr><td>地點包括<td><?php formInputText('clsrom'); ?>
<td><span class="note"><?php formRadioButton('clsrom'); ?></span>
<tr><td>地點不包括<td><?php formInputText('not_clsrom'); ?><td>

<tr><td>備註包括<td><?php formInputText('mark'); ?>
<td><span class="note"><?php formRadioButton('mark'); ?></span>
<tr><td>備註不包括<td><?php formInputText('not_mark'); ?><td>
</table>
</div>
</fieldset>
<fieldset class="rhsodd">
<legend>&nbsp;</legend>
<div>
<table border="0">
<tr><td>學分數<td>
<?php formInputText('credit'); ?>
<td>取 OR (聯集)
<tr><td>期間<td colspan="2">
<?php formSelect('interval', array(''=>'全年/半年', 'full'=>'全年', 'half'=>'半年')); ?>
<tr><td>必修/選修<td colspan="2">
<?php formSelect('elective', array(''=>'必修/選修', 'ob'=>'必修', 'op'=>'選修')); ?>
<tr><td>異動<td colspan="2">
<?php formSelect('modified', array(''=>'', 'new'=>'加開', 'halt'=>'停開', 'mod'=>'異動')); ?>
</table>
</div>
</fieldset>
<fieldset class="rsheven">
<legend>&nbsp;</legend>
<div>
<table border="0">
<tr><td><?php formCheckbox('no_void_time', '不顯示無時間的課', '將時間欄為空白的課程排除, 但是時間可能寫在備註欄', true); ?>
<td><?php formCheckbox('no_void_serial', '不顯示無流水號的課', '', true); ?>
<tr><td><?php formCheckbox('night', '查詢包括進修學士班'); ?>
<td><?php formCheckbox('no_cancelled', '不顯示已停開的課'); ?>
</table>
</div>
</fieldset>
</tr>
<tr><td colspan="2">
<fieldset class="rhsodd">
<legend>結果檢視</legend>
<div>
<?php

if(!empty($var['start']) && !is_numeric($var['start']))
	$var['start'] = 1;
if(!empty($var['number']) && !is_numeric($var['number']))
	$var['number'] = $RecordsPerTable;
?>
從搜尋結果的第 <?php
$array = array();
for($i = 1; $i <= 2001; $i += $RecordsPerTable)
	$array["$i"] = $i;
formSelect("start", $array);
?> 筆開始顯示 <?php
$array = array();
for($i = $RecordsPerTable; $i <= 1000; $i += $RecordsPerTable)
	$array[$i] = $i;
formSelect("number", $array);
?> 筆
<span class="smallnote">(每多 100 筆約加 20~50KB)</span><br>
<!--
依 <?php
$array = array();
$array[''] = '';
foreach($AllFields as $k => $s) {
	$array[$k] = preg_replace('/<br>/', '', $s);
}
formSelect('sortby', $array);
?> 排序 (順序: <?php
formSelect('order', array(''=>'', 'asc'=>'小到大', 'desc'=>'大到小'));
?>)
-->
<?php formCheckbox('csv', '純文字輸出 (tab 分隔)', '可更方便地存入 Excel:<br>編輯->選擇性貼上->文字'); ?>
</div>
</fieldset>
</td></tr>
<tr><td colspan="2" align="left">
<input type="hidden" name="send" value="1">
<input type="submit" class="submit" name="SubmitType" value="確定">
<input type="button" class="button" value="全部清除" onClick="javascript:ClearAll()">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" class="submit" name="SubmitType" value="儲存查詢">
<input type="submit" class="submit" name="SubmitType" value="取回儲存">
<input type="submit" class="submit" name="SubmitType" value="清除儲存">
<input type="hidden" name="qid" value="<?php echo (empty($var['qid'])) ? md5(uniqid(rand(),1)) : $var['qid']; ?>">
</td></tr></table>
</form>

<p>
<?php
if(!empty($_POST['send'])) {			// 程式開始, 有送出時才處理

// log 到資料庫
if($LogQuery == true)
	$dbh->query("INSERT INTO querylog (ipaddr,sid,qid,query) VALUES ('".getIP()."','".session_id()."','$var[qid]','".serialize($var)."')");

// 這裡是處理使用者輸入條件的部份, see query.inc.php
condDpt();
condAndOrNot();
condClasstime();
condGeneralEdu();
condCouCodeType();
condOthers();
// 結束條件處理

$from = $var['start'] - 1;
if(preg_match('/drop|delete/i', $condition))
	$condition = '';

$query = 'SELECT SQL_CALC_FOUND_ROWS ' . implode(',', $SelectedFields) .
(in_array('cou_code', $SelectedFields) ? '' : ',cou_code') .
(in_array('dpt_code', $SelectedFields) ? '' : ',dpt_code') .
(in_array('class', $SelectedFields) ? '' : ',class') .
" FROM $var[table] WHERE 1 $condition LIMIT $from,$var[number]";
$res = $dbh->query($query) or die('Invalid Query');

echo $dbh->error;
$count_result = $dbh->query("SELECT FOUND_ROWS() AS count");
$total_count = $count_result->fetch_assoc()['count'];
$display_count = $res->num_rows;

echo "<p>全部結果共有 $total_count 筆</p>";
if($display_count == 0) {
	if($total_count != 0) {
		echo "<p>(開始顯示的筆數 $var[start] 大於總結果筆數 $total_count)</p>";
		echo '<input type="button" class="button" value="到第一頁" onClick="document.ThisForm.start.value=1; document.ThisForm.SubmitType[0].click();">';
	} else
		echo '<p>無符合條件的課程</p>';
} else {
	echo '<p>學期: '.$var['table'].', 從第 '.$var['start'].' 筆顯示至第 '.($var['start']+$display_count-1).
	' 筆<br>(按課名可連結至大綱)</p>';
	if(empty($var['csv'])) {
?>
<script type="text/javascript">
	var opt = document.ThisForm.elements["outcol_sel[]"];
	document.writeln('<form name="tcol" onsubmit="return false">');
	for (var i = 0; i < opt.length; ++i) {
		if(opt[i].selected) {
			document.write('<input type="checkbox" name="'+opt[i].value);
			document.write('" id="tog'+opt[i].value+'" onclick="toggleVis(this.name);" checked>');
			document.write('<label for="tog'+opt[i].value+'">');
			document.write(opt[i].text+'</label>');
		}
	}
	document.writeln('</form>');
</script>
<?php
	}
	displayPager();
	formAddToScheduleTable(true);

if(empty($var['csv'])) {
	for($p = 0; $p < $display_count; $p += $RecordsPerTable) {
	// Display the First Row of the Table
		echo '<table border="1" width="100%">';
		table_header($SelectedFields, false, true);

		for($j = 0; ($j < $RecordsPerTable && $row = $res->fetch_assoc()); ++$j)
			displayRow($row, $var['table'], false, false, false, false, false, '', true);
	}
} else { // CSV here
	table_header($SelectedFields, true);
	for($j = 0; $row = $res->fetch_assoc(); ++$j)
		displayRow($row, $var['table'], true);
}
echo (empty($var['csv']) ? '</table>' : '</pre>');

formAddToScheduleTable(false);
displayPager();

}
//	echo "<br>\n query = [$query]<br>\n";
$beg = explode (" ",$begin_time); 
$end = explode (" ",microtime()); 
echo "<p>本頁 ".(($end[1] - $beg[1])+($end[0] - $beg[0]))." 秒完成</p>";
}

if(empty($_POST['send'])) {
?>
<!--[if lte IE 6]>
<div style="background-color:#DDECFF;margin:5px 0 5px 0;padding:3px 10px 3px 10px;border-color:#F6F6F6; border-style:solid;border-width:2px;">
<p><font size="2"><strong>您好</strong>，您目前使用的是舊版的<del>IE 6.0網路瀏覽器</del>，建議使用更快、更好用的瀏覽器！ 如：<big><a target="_blank" href="http://briian.com/?p=5726"><u>Google瀏覽器</u> <font color="red">(推薦!)</font></a></big>、<a target="_blank" href="http://briian.com/?p=6248">Firefox</a>、<a target="_blank" href="http://briian.com/?p=6264">Opera</a>、<a target="_blank" href="http://briian.com/?p=6139">Safari</a> 或 <a target="_blank" href="http://briian.com/?p=6166">IE
8.0</a>。</font></p>
</div>
<![endif]-->
<?php
}

require('include/footer.inc.php');

function getIP() {
	if(!empty($_SERVER["HTTP_CLIENT_IP"]))
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	elseif(!empty($_SERVER['REMOTE_ADDR']))
		$ip = $_SERVER['REMOTE_ADDR'];
	return $ip;
}
?>
