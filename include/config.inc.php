<?
error_reporting(E_ALL);

// 資料庫設定見 db.inc.php

mb_internal_encoding("utf-8");

$GE_NOTE = 'http://investea.aca.ntu.edu.tw/course/temp/com.htm';
$GE_REALM = 'http://investea.aca.ntu.edu.tw/course/temp/comarea.htm';
$SEMESTERS = array('93_1'=>'93-1',
			'92_2'=>'92-2', '92_1'=>'92-1',
			'91_2'=>'91-2', '91_1'=>'91-1',
			'90_2'=>'90-2', '90_1'=>'90-1');

$WeekdayName=array('星期<br>節次','一','二','三','四','五','六');
$ClassTimeName=array('','0','1','2','3','4','@','5','6','7','8','9','A','B','C','D');
$MaxStartRow = 2001;

$all_field = array(
'ser_no' => '流水號',	'co_chg' => '異動',		'dptname' => '院系',
'cou_code' => '課號',	'class' => '班次',		'year' => '年級',
'credit' => '學分',		'forth' => '期間',		'sel_code' => '選/必',
'cou_cname' => '課名',	'tea_cname' => '教師',	'clsrom' => '教室',
'daytime' => '時間',	'mark' => '備註',		'co_gmark' => '通識',
'sch_count' => '編號', 'bgcolor' => '背景顏色');

$pre_selection = array('ser_no', 'co_chg', 'dptname', 'class', 'year',
				'credit', 'forth', 'sel_code', 'cou_cname',
				'tea_cname', 'clsrom', 'daytime', 'mark', 'co_gmark');

// 在 query.inc.php 和 save_read.inc.php 中使用
$check1 = array ('cou_cname', 'tea_cname', 'year', 'clsrom', 'mark');
$check2 = array ('cou_cname' => 'not_cou_cname',
			'tea_cname' => 'not_tea_cname',
			'year' => 'not_year',
			'clsrom' => 'not_clsrom',
			'mark' => 'not_mark');
?>
