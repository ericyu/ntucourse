<?php
$WeekdayName = array('星期<br>節次','一','二','三','四','五','六');
$ClassTimeName = array('','0','1','2','3','4','@','5','6','7','8','9','A','B','C','D');
$ClassTimeDetail = array('', '7:10-8:00', '8:10-9:00', '9:10-10:00', '10:20-11:10', '11:20-12:10', '12:20-13:10', '13:20-14:10', '14:20-15:10', '15:30-16:20', '16:30-17:20', '17:30-18:20', '18:30-19:20', '19:25-20:15', '20:25-21:15', '21:20-22:10');

$AllFields = array(
'ser_no' => '流水號',	'co_chg' => '異動',		'dptname' => '院系',
'cou_code' => '課號',	'class' => '班次',		'year' => '年級',
'credit' => '學分',		'forth' => '期間',		'sel_code' => '選/必',
'cou_cname' => '課名',	'tea_cname' => '教師',	'clsrom' => '教室',
'daytime' => '時間',	'mark' => '備註',		'co_gmark' => '通識',
'co_select' => '加選方式');

$AllFieldsForTable = array_merge($AllFields,
	array('sch_count' => '編號', 'bgcolor' => '背景顏色'));

$DefaultSelection = array('ser_no', 'co_chg', 'dptname', 'class', 'year',
				'credit', 'forth', 'sel_code', 'cou_cname',
				'tea_cname', 'clsrom', 'daytime', 'mark', 'co_gmark', 'co_select');

// 在 query.inc.php 和 save_read.inc.php 中使用
$check1 = array('cou_cname', 'tea_cname', 'year', 'clsrom', 'mark');
$check2 = array('cou_cname' => 'not_cou_cname',
			'tea_cname' => 'not_tea_cname',
			'year' => 'not_year',
			'clsrom' => 'not_clsrom',
			'mark' => 'not_mark');
?>
