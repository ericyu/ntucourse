<?
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
<h1>免責聲明</h1>
本網站所使用資料來源為 <a href="http://info.ntu.edu.tw">
http://info.ntu.edu.tw/</a> 上的 "課程 Excel
檔下載"。 本網站不保證所引用資料的正確性與時效性，<em>請務必到 <a href="http://info.ntu.edu.tw">http://info.ntu.edu.tw/</a>
確認最新資料。</em>本資料庫中不包括: <em>90-1之前的學程, 90-2之前的進修學士班</em>。

<?
$initday = '1-7';
$d = dir('diffs/');
while (false !== ($entry = $d->read()))
	if(preg_match('/^.+\.out$/', $entry))
		$list[] = $entry;
$d->close();
if(!empty($list)) {
	$list = array_merge(array($initday), preg_replace('/(.+)\.out$/', '\1', $list));
	natsort($list);
	$list = array_values($list);
	$list2 = preg_replace('/^(..?)-(..?)(_.)?$/', '\1/\2\3', $list);
	echo "<h1>課程異動記錄 - 93 學年上學期課程</h1>";
	for($i = 0; $i < count($list2)-1; ++$i) {
		echo "$list2[$i] <a href=\"showdiff.php?d1=$list[$i]&amp;d2=";
		echo $list[$i+1]."\">=></a> ";
	}
	echo $list2[$i];
}
echo ' (最後更新日期：94/'.
	(!empty($list) ? $list2[$i] : preg_replace('/^(..?)-(..?)(_.)?$/',
	'\1/\2\3', $initday)).')';
?>
<h1>選課輔助程式</h1>
<p style="background: #FFFFCC;">
<a href="tutorial.php">使用說明</a>
<a href="javascript:example();">範例</a><br>
<span class="info">將滑鼠移到畫藍色底線的文字<span class="tooltip">這是說明</span></span>上會出現補充說明.
<span class="smallnote">(本網頁部分功能需要瀏覽器支援 JavaScript 及 Cookies 方可正常運作)</span>
<br><em><a href="http://ericyu.org/phpBB2/index.php" style="color: red;">歡迎到討論區表達您的意見</a></em>
</p>
<? }
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
<div><? formSelect('table', $SEMESTERS); ?></div>
</fieldset>
<fieldset>
<legend>時間</legend>
<div>
<span style="color: #ff0000">請勾選有空堂的時間</span><br>
或 <? formCheckbox('grep', 'Grep 模式', '改為找出: 包含「任何所選取的時間」的課程'); ?>
<hr>
<a href="javascript:FillClasses()">依課表中空堂選擇</a><br>
<input type="button" value="全部選取" onClick="javascript:checkAll(1)" class="selectButton">
<input type="button" value="取消選取" onClick="javascript:checkAll(0)" class="selectButton"><br>
<table border="0">
<?

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
<? formOutColSelect(1,5); ?>
</div>
</fieldset>
<td>
<fieldset class="rhsodd">
<legend>院系代碼</legend>
<div>
<span class="smallnote">(體育/軍訓/大學部/研究所, 不限定請不要填)</span><br>
<input type="text" name="dpt_choice" id="dpt_choice" size="35" maxlength="200"
<? if(!empty($_POST['send'])) fixDptInput();?> onChange="javascript:UpdateFromInput();">
<? require('dpt/embed.html'); ?>
<a href="javascript:ClearAllInput();">清除</a>
<a target="_blank" href="dpt/prev.php">無 JavaScript | 查看歷史資料...</a>
</div>
</fieldset>
<fieldset class="rhseven">
<legend>通識</legend>
<div>
若要限定僅顯示通識, 領域為:(<a href="<? echo $GE_NOTE; ?>">通識說明</a> &amp;
<a href="<? echo $GE_REALM; ?>">各系歸屬領域</a>)<br>
<?
$ge_field = array('1'=>'人文學', '2'=>'社會科學', '3'=>'物質科學', '4'=>'生命科學');
for($i = 1; $i <= 4; ++$i)
	echo '<input type="checkbox" id="ge'. $i .'" name="ge_sel[' . $i . ']"' .
			(!empty($var['ge_sel'][$i]) ? ' checked' : '') . '>' .
			'<label for="ge'. $i .'">' . $ge_field[$i] . '</label>';
?>
<br>
<? formCheckbox('no_multi_ge', '不顯示跨領域通識(需勾選至少一個通識領域)'); ?>
</div>
</fieldset>
<fieldset class="rhsodd">
<legend>課號限制</legend>
<div>
<?
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
<tr><td>課名內包括<td><? formInputText('cou_cname'); ?>
<td><span class="note"><? formRadioButton('cou_cname'); ?></span>
<tr><td>課名內不包括<td><? formInputText('not_cou_cname'); ?><td>

<tr><td>教師包括<td><? formInputText('tea_cname'); ?>
<td><span class="note"><? formRadioButton('tea_cname'); ?></span>
<tr><td>教師不包括<td><? formInputText('not_tea_cname'); ?><td>

<tr><td>年級包括<td><? formInputText('year'); ?>
<td><span class="note"><? formRadioButton('year'); ?></span>
<tr><td>年級不包括<td><? formInputText('not_year'); ?><td>

<tr><td>地點包括<td><? formInputText('clsrom'); ?>
<td><span class="note"><? formRadioButton('clsrom'); ?></span>
<tr><td>地點不包括<td><? formInputText('not_clsrom'); ?><td>

<tr><td>備註包括<td><? formInputText('mark'); ?>
<td><span class="note"><? formRadioButton('mark'); ?></span>
<tr><td>備註不包括<td><? formInputText('not_mark'); ?><td>
</table>
</div>
</fieldset>
<fieldset class="rhsodd">
<legend>&nbsp;</legend>
<div>
<table border="0">
<tr><td>學分數<td>
<? formInputText('credit'); ?>
<td>取 OR (聯集)
<tr><td>期間<td colspan="2">
<? formSelect('interval', array(''=>'全年/半年', 'full'=>'全年', 'half'=>'半年')); ?>
<tr><td>必修/選修<td colspan="2">
<? formSelect('elective', array(''=>'必修/選修', 'ob'=>'必修', 'op'=>'選修')); ?>
<tr><td>異動<td colspan="2">
<? formSelect('modified', array(''=>'', 'new'=>'加開', 'halt'=>'停開', 'mod'=>'異動')); ?>
</table>
</div>
</fieldset>
<fieldset class="rsheven">
<legend>&nbsp;</legend>
<div>
<table border="0">
<tr><td><? formCheckbox('no_void_time', '不顯示無時間的課', '將時間欄為空白的課程排除, 但是時間可能寫在備註欄', true); ?>
<td><? formCheckbox('no_void_serial', '不顯示無流水號的課'); ?>
<tr><td><? formCheckbox('night', '查詢包括進修學士班'); ?>
<td><? formCheckbox('no_cancelled', '不顯示已停開的課'); ?>
</table>
</div>
</fieldset>
</tr>
<tr><td colspan="2">
<fieldset class="rhsodd">
<legend>結果檢視</legend>
<div>
<?

if(!empty($var['start']) && !is_numeric($var['start']))
	$var['start'] = 1;
if(!empty($var['number']) && !is_numeric($var['number']))
	$var['number'] = $RecordsPerTable;
?>
從搜尋結果的第 <?
$array = array();
for($i = 1; $i <= 2001; $i += $RecordsPerTable)
	$array["$i"] = $i;
formSelect("start", $array);
?> 筆開始顯示 <?
$array = array();
for($i = $RecordsPerTable; $i <= 1000; $i += $RecordsPerTable)
	$array[$i] = $i;
formSelect("number", $array);
?> 筆
<span class="smallnote">(每多 100 筆約加 20~50KB)</span><br>
<!--
依 <?
$array = array();
$array[''] = '';
foreach($AllFields as $k => $s) {
	$array[$k] = preg_replace('/<br>/', '', $s);
}
formSelect('sortby', $array);
?> 排序 (順序: <?
formSelect('order', array(''=>'', 'asc'=>'小到大', 'desc'=>'大到小'));
?>)
-->
<? formCheckbox('csv', '純文字輸出 (tab 分隔)', '可更方便地存入 Excel:<br>編輯->選擇性貼上->文字'); ?>
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
<input type="hidden" name="qid" value="<?= (empty($var['qid'])) ? md5(uniqid(rand(),1)) : $var['qid']; ?>">
</td></tr></table>
</form>

<p>
<?
if(!empty($_POST['send'])) {			// 程式開始, 有送出時才處理

// log 到資料庫
if($LogQuery == true)
	mysql_query("INSERT INTO querylog (ipaddr,sid,qid,query) VALUES ('".getIP()."','".session_id()."','$var[qid]','".serialize($var)."')", $dbh);

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
$res = mysql_query($query, $dbh) or die('Invalid Query');

echo mysql_error($dbh);
$count_result = mysql_query("SELECT FOUND_ROWS() AS count");
$total_count = mysql_result($count_result, 0);
$number = mysql_num_rows($res);

echo "<p>全部結果共有 $total_count 筆</p>";
if($number == 0) {
	if($total_count != 0) {
		echo "<p>(開始顯示的筆數 $var[start] 大於總結果筆數 $total_count)</p>";
		echo '<input type="button" class="button" value="到第一頁" onClick="document.ThisForm.start.value=1; document.ThisForm.SubmitType[0].click();">';
	} else
		echo '<p>無符合條件的課程</p>';
} else {
	echo '<p>學期: '.$var['table'].', 從第 '.$var['start'].' 筆顯示至第 '.($var['start']+$number-1).
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
<?
	}
	displayPager();
	formAddToScheduleTable(true);

if(empty($var['csv'])) {
	for($p = 0; $p < $number; $p += $RecordsPerTable) {
	// Display the First Row of the Table
		echo '<table border="1" width="100%">';
		table_header($SelectedFields);

		for($j = 0; ($j < $RecordsPerTable && $row = mysql_fetch_assoc($res)); ++$j)
			displayRow($row, $var['table']);
	}
} else { // CSV here
	table_header($SelectedFields, true);
	for($j = 0; $row = mysql_fetch_assoc($res); ++$j)
		displayRow($row, $var['table'], $var['csv']);
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
	$fp = @fopen('acc.txt','r+');
	if($fp) {
		flock($fp,2);
		$count = fgets($fp,1024);
		$cnew = $count+1;
		rewind($fp);
		fputs($fp,$cnew);
		echo '<br><a href="hitStats.php">今日人數: '.$cnew.'</a><br>';
		flock($fp,3);
		fclose($fp);
	}
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
