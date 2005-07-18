<?
function column_sql($cols, $extra) {
	foreach ($extra as $a)
		if(!in_array($a, $cols))
			$cols[] = $a;
	return implode(',', $cols);
}

function makeScheduleQuery($table, $COU, $SelectedFieldsSQL) {
	global $size;
	for($i = 0; isset($COU) && $i < $size; ++$i) {
		list($s, $c, $d, $cls) = split(',', $COU[$i]);
		if(!preg_match("/^\d\d_\d$/", $table)
		|| !preg_match("/^(|\d{5})$/", $s)
		|| !preg_match("/^[0-9A-Z]{3} [0-9A-Z]{5}$/", $c)
		|| !preg_match("/^(|([0-9A-Z]|[0-9A-Z]{4}))$/", $d)
		|| !preg_match("/^.{0,2}$/", $cls)) {
			echo "</table><p>Data format error at No. " . ($i+1) .": $table $s $c $d $cls";
			exit();
		}
		$subquery[$i] = "(select $i AS n, '$table' AS t, $SelectedFieldsSQL FROM ".
			"$table WHERE ser_no='$s' AND cou_code='$c' AND dpt_code='$d' AND class='$cls' LIMIT 0,1)";
	}
	return $subquery;
}
?>
