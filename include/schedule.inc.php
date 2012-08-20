<?php
function column_sql($cols, $extra) {
	foreach ($extra as $a)
		if(!in_array($a, $cols))
			$cols[] = $a;
	return implode(',', $cols);
}

function makeScheduleQuery($table, $COU, $SelectedFieldsSQL, $old = false) {
	global $size;
	for($i = 0; isset($COU) && $i < $size; ++$i) {
		list($s, $c, $d, $cls) = preg_split('/,/', $COU[$i]);
		if(!preg_match("/^\d{2,3}_\d$/", $table)
		|| !preg_match("/^(|\d{5})$/", $s)
		|| !preg_match("/^[0-9A-Z]{3} [0-9A-Z]{5}$/", $c)
		|| !preg_match("/^(|([0-9A-Z]|[0-9A-Z]{4}))$/", $d)
		|| !preg_match("/^.{0,2}$/", $cls)) {
			echo "</table><p>Data format error at No. " . ($i+1) .": $table $s $c $d $cls";
			exit();
		}
		$subquery[$i] = "(select $i AS n, '$table' AS t, $SelectedFieldsSQL FROM ".
			"$table WHERE " . ($old ? '':" ser_no='$s' AND ") .
			" cou_code='$c' AND dpt_code='$d' AND class='$cls' LIMIT 0,1)";
	}
	return $subquery;
}

function fillTimeTable() {
	global $row, $i, $class, $cname;
	$daytime = getCourseTime($row[$i]['daytime']);
	foreach($daytime as $sd) {	// 將時間 & 中文課名填入表格
		$w = mb_substr($sd, 0, 1);
		$c = strtoupper(mb_substr($sd, 1));
		for($j = 0; $j < strlen($c); ++$j) {
			if(!empty($class["$w$c[$j]"])) {
				$class["$w$c[$j]"] .= ','. ($i+1);
				$cname["$w$c[$j]"] .= '<br>'.($i+1).": ".$row[$i]['cou_cname'];
			} else {
				$class["$w$c[$j]"] = $i+1;
				$cname["$w$c[$j]"] = ($i+1).": ".$row[$i]['cou_cname'];
			}
		}
	}
}

function displayScheduleTable() {
	global $WeekdayName, $ClassTimeName, $class, $cname;
	echo '<table border="1" align="center">';
	for($c = 0; $c < 16; ++$c) {	// $c = sizeof($ClassTimeName)
		echo '<tr>';
		for($week = 0; $week < 7; ++$week) {
			$time = "$WeekdayName[$week]$ClassTimeName[$c]";
			echo '<td align="center"'.
		(!($c == 0 || $week == 0 || empty($class[$time])) ?
			" onMouseOver=\"popup('$time','$cname[$time]');".
			'" onMouseOut="kill()"' : '') . '>'; 
			if($c == 0)
				echo $WeekdayName[$week];
			elseif($week == 0)
				echo $ClassTimeName[$c];
			elseif(empty($class["$WeekdayName[$week]$ClassTimeName[$c]"]))
				echo '　&nbsp;　&nbsp;　';
			else
				echo $class["$WeekdayName[$week]$ClassTimeName[$c]"];
			echo '</td>';
		}
		echo "</tr>\n";
	}
	echo "</table>\n";
}
?>
