<?
require_once('include/config.inc.php');
require_once('include/query.inc.php');
require_once('include/query_form.inc.php');
// output compression
ob_start('ob_gzhandler');
$var = &$_POST;
require('include/header.inc.php');
?>
<h1>流水號快速查詢</h1>
<form action="serial.php" method="post" name="Form">
<table class="sn" border="0" align="center">
<tr valign="middle"><td>查詢學期:<? formSelect('table', $SEMESTERS); ?></td>
<td><? formCheckbox('csv', '輸出成純文字 (tab 分隔)', '可更方便地存入 Excel:<br>編輯->選擇性貼上->文字'); ?></td>
<td>輸出欄位:</td><td><? formOutColSelect(); ?></td>
<tr><td colspan="4">請輸入欲查詢流水號: <input type="text" name="serial">
<input type="submit" class="submit" value="確定"> (請以逗號, 空白, 或是句點分隔)
</table>
</form>
<?
if(!empty($_POST['serial']) && $serial = $_POST['serial']) {
$serial = preg_split("/[., ]+/", trim($serial));
foreach($serial as $v) {
	if(strlen($v)==4)
		$serial_split[] = '0' . $v;
	else
		$serial_split[] = $v;
}
$ser_qu = 'ser_no="'.implode('" or ser_no="', $serial_split).'"';
$query='select ' . implode(',', $sel_column) .
(in_array('cou_code', $sel_column) ? '' : ',cou_code') .
(in_array('dpt_code', $sel_column) ? '' : ',dpt_code') .
(in_array('class', $sel_column) ? '' : ',class') .
" from $var[table] where $ser_qu";
$res=mysql_query($query,$dbh);

echo "<p>";
formAddToScheduleTable(true);

if(empty($var['csv'])) {
	echo '<table class="sn" border="1" width="100%">';
	table_header($sel_column);
	while($row = mysql_fetch_assoc($res))
			displayRow($row, $var['table']);
} else {
	// CSV here
	table_header($sel_column, true);
	for($j = 0; $row = mysql_fetch_assoc($res); ++$j)
		displayRow($row, $var['table'], $var['csv']);
}
echo (empty($var['csv']) ? '</table>' : '</pre>');

formAddToScheduleTable(false);
}
require('include/footer.inc.php');
?>
