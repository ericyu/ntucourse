<?
require_once('config.inc.php');

// 系所選擇判斷
function condDpt() {
	global $condition,$dpt_choice_filtered;
	$cond = '';
	if($dpt_choice_filtered != '') {
		foreach($dpt_choice_filtered as $cur) {
			if($cond != '')
				$cond .=' OR';
			if(preg_match("/^[1-9ABP]$/", $cur))
				$cond .= " dpt_code regexp '^".$cur."[01]'";
			elseif(preg_match("/^[1-9AB]M$/", $cur))
				$cond .= " dpt_code regexp '^".substr($cur,0,1)."[234]'";
			elseif(preg_match("/^[1-9AB]..0$/", $cur))
				$cond .= " dpt_code like '".substr($cur,0,3)."%'";
			else
				$cond .= " dpt_code=\"$cur\"";
		}
		$condition .= " and ($cond)";
	}
}

// 包括或不包括的條件, some are passed by globalization
function condAndOrNot() {
	global $condition, $var, $check1, $check2;
	// 包括: 取聯集
	foreach($check1 as $a) {
		if(!empty($var[$a])) {
			$c = '';
			foreach(preg_split("/[, ]+/", $var[$a]) as $cur) {
				if($cur != '') {
					if($c != '')
						$c = $c . ' ' . $var["radio_$a"];
					$c .= " position('$cur' in $a)";
				}
			}
			$condition .= " and ($c)";
		}
	}
	// 學分要例外處理
	if(!empty($var['credit'])) {
		$c = '';
		foreach(preg_split("/[, ]+/", $var['credit']) as $cur) {
			if($c != "")
				$c = $c . " OR ";
			$c .= " credit = '$cur'";
		}
		$condition .= " and ($c)";
	}
	
	// 不包括的條件
	foreach($check2 as $a => $b) {
		if(!empty($var[$b])) {
			foreach(preg_split("/[, ]+/", $var[$b]) as $cur)
				if($cur != "")
					$condition.=" and !position('$cur' in $a)";
		}
	}
}

// 時間限制
function condClassTime() {
	global $condition, $var;
	global $ClassTimeName, $WeekdayName;		// in config.inc.php
	$class = &$var['class'];
	if(empty($var['grep'])) {
		// 非 grep 模式
		for($c = 1; $c < 16; ++$c)
			for($week = 1; $week < 7; ++$week)
				if(empty($class["$week$ClassTimeName[$c]"]))
					$condition .= " and daytime not regexp '${WeekdayName[$week]}[A-Da-d0-9@]*${ClassTimeName[$c]}[A-Da-d0-9@]*'\n";
	} else {
		$cond = array();
		for($c = 1; $c < 16; ++$c)
			for($week = 1; $week < 7; ++$week)
				if(!empty($class["$week$ClassTimeName[$c]"]))
					$cond[] = "daytime regexp '${WeekdayName[$week]}[A-Da-d0-9@]*${ClassTimeName[$c]}[A-Da-d0-9@]*'";
		if($cond != '')
			$condition .= ' and (' . implode(' OR ', $cond) . ')';
	}
}

function condGeneralEdu() {
	global $condition, $var;
	$c = '';
	for($i = 1; $i <= 4; ++$i) {
		if(!empty($var['ge_sel'][$i])) {
			if($c)
				$c .= ' OR';
			$c .= " position($i in co_gmark)";
		}
	}
	if($c) {
		$condition .= " AND type='12' AND ($c)";
		if(!empty($var['no_multi_ge'])) {
			$condition .= " AND co_gmark regexp '^[1-4](\\\\*?)$'";
		}
	}
}

function condCouCodeType() {
	global $condition, $var;
	$type = array('U'=>'U', 'M'=>'M', 'D'=>'D', 'O'=>'[^UMD]');
	$c = '';
	foreach($type as $key => $item) {
		if(!empty($var['cou_code_type'][$key])) {
			if($c)
				$c .= ' OR';
			$c .= " cou_code regexp '^.{3,3} ${item}'";
		}
	}
	if($c)
		$condition .= " AND ($c)";
}

function condOthers() {
	global $condition, $AllFields, $var;

	if($var['interval'] == 'full')
		$condition .= " and forth='全年'";
	elseif($var['interval'] == 'half')
		$condition .= " and forth='半年'";

	if($var['elective'] == 'ob')
		$condition .= " and sel_code='必修'";
	elseif($var['elective'] == 'op')
		$condition .= " and sel_code='選修'";

	if($var['modified'] == 'new')
		$condition .= " and co_chg='加開'";
	elseif($var['modified'] == 'halt')
		$condition .= " and co_chg='停開'";
	elseif($var['modified'] == 'mod')
		$condition .= " and co_chg='異動'";

	// 無時間的課
	if(!empty($var['no_void_time']))
		$condition .= " and daytime != ''";

	// 無流水號的課
	if(!empty($var['no_void_serial']))
		$condition .= " and ser_no != ''";

	// 進修學士班
	if(empty($var['night'])) {
		$condition .= " and class not regexp '^E.'";
	}

	if(!empty($var['no_cancelled'])) {
		$condition .= " and co_chg != '停開'";
	}

	if(array_key_exists($var['sortby'], $AllFields)) {
		$condition .= ' order by '.$var['sortby'];
		if($var['order'] == 'asc')
			$condition .= ' asc';
		elseif($var['order'] == 'desc')
			$condition .= ' desc';
	}
}

function displayRow(&$row, $se, $csv = false, $no_select = false, $no_link = false, $sch = false, $before = '') {
	global $SelectedFields, $var;	// $SelectedFields 在呼叫此函式的程式內設定
	global $_rowCount;
	if(!isset($_rowCount))
		$_rowCount = 0;
	else
		$_rowCount += 1;

	list ($c1, $c2) = explode(' ', $row['cou_code']);
	if(!$csv) {
		if($sch || !$no_select)
				echo "<tr onclick='javascript:setCheck(event,\"c$_rowCount\");' ".
				"onmouseover='javascript:this.className=\"highlightrow\"' ".
				"onmouseout='javascript:this.className=\"\"'>";
		else
			echo '<tr>';
	}

	echo $before;

	if($sch) {
		echo ($csv ? '':'<td class="tdCheckbox">').'<input type="checkbox" name="selected_cou[]" class="tnum" value="'.
			$_rowCount.'" id="c'.$_rowCount.'">'.($_rowCount+1).($csv ? "\t" : '');
	}
	foreach($SelectedFields as $f) {
		// 如果是 schedule 就
		if($f == 'ser_no') {
			$array[$f] = '';
			if(!$no_select) {
				$array[$f] = '<input type="checkbox" name="selected_cou[]" value="'.
				"$var[table],$row[ser_no],$c1 $c2,$row[dpt_code],$row[class]".'" '.
				"id='c$_rowCount'" . '">';
			}
			$array[$f] .= ($row[$f] ? $row[$f] : '-----');
		} elseif($f == 'cou_cname') {
			if($no_link)
				$array[$f] = $row[$f];
			else
				$array[$f] = "<a href=\"detail.php?se=$se&amp;c1=$c1&amp;c2=$c2&amp;class=$row[class]\" target=\"_blank\">".htmlspecialchars($row[$f])."</a>";
			
		} elseif($row[$f] == '')
			$array[$f] = ($csv ? '' : '&nbsp;');
		else
			$array[$f] = htmlspecialchars($row[$f]);
	}
	if($csv) {
		echo implode("\t", $array);
	} else {
		foreach($SelectedFields as $f)
//		echo '<td>' . implode('<td>', $array);
		echo "<td id=\"t$f\" name=\"t$f\">$array[$f]";
	}
	echo ($csv ? "\n" : "</tr>\n");
}

function formAddToScheduleTable($header) {
	$sch = array("SCHEDULE"=>"課表一", "SCHEDULE2"=>"課表二", "SCHEDULE3"=>"課表三");
	global $SelectedFields;
	if(!in_array('ser_no', $SelectedFields))
		return;
	if($header)
		echo '<form action="schedule.php" method="post" name="sch_sel" target="_new">
<input type="hidden" name="add" value="1">';

echo '<table border="0"><tr valign="middle">
<td><img src="images/arrow_'.($header?'ld':'lt').'.gif" alt="ld">
<td><span style="font-size: 10pt">
<a href="javascript:setCheckboxes(\'sch_sel\',true)">全部勾選</a> /
<a href="javascript:setCheckboxes(\'sch_sel\',false)">全部取消</a></span><td>';
	formSelect('sch_no'.($header ? '1' : '0'), $sch);
	echo '<td>&nbsp;&nbsp;<input name="sub'.($header ? '1' : '0').
		'" type="submit" class="submit" '.
	'value="加入課表"></td></tr></table>';
	if(!$header)
		echo '</form>';
}

function getCourseTime($dt) {
	global $ClassTimeName;
	$dt = preg_replace("/(一|二|三|四|五|六)([A-Da-d0-9@\-]+)/", "\\0,", $dt);
	$dt = preg_replace("/^(.+),$/", "\\1", $dt); // 消去最後多餘的逗點
	$dt = split(',', $dt);
	for($i = 0; $i < sizeof($dt); ++$i) {
		if(preg_match("/[A-Da-d0-9@]-[A-Da-d0-9@]/", $dt[$i])) {
			$tmp = mb_substr($dt[$i], 1);
			list($a, $b) = split(",", preg_replace("/([A-Da-d0-9@])-([A-Da-d0-9@])/", "\\1,\\2", $tmp));
			$start = array_search($a, $ClassTimeName);
			$length = array_search($b, $ClassTimeName)-1;
			$dt[$i] = mb_substr($dt[$i], 0, 1) . implode("", array_slice($ClassTimeName, $start, $length));
		}
	}
	return $dt;
}

function table_header($sel, $csv = false) {
	global $AllFieldsForTable;
	if($csv) {
		echo '<pre>';
		$tmp = array();
		foreach($sel as $s)
			$tmp[] = $AllFieldsForTable[$s];
		echo implode("\t", $tmp)."\n";
	} else {
		echo '<tr>';
		foreach($sel as $s)
			echo "<th id='t$s' name='t$s'>" . $AllFieldsForTable[$s];
		echo '</tr>';
	}	
}
?>
