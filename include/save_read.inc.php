<?
$ctime = explode(' ', '10 11 12 13 14 1@ 15 16 17 18 19 1A 1B 1C 1D '.
'20 21 22 23 24 2@ 25 26 27 28 29 2A 2B 2C 2D 30 31 32 33 34 3@ '.
'35 36 37 38 39 3A 3B 3C 3D 40 41 42 43 44 4@ 45 46 47 48 49 4A '.
'4B 4C 4D 50 51 52 53 54 5@ 55 56 57 58 59 5A 5B 5C 5D 60 61 62 '.
'63 64 6@ 65 66 67 68 69 6A 6B 6C 6D');

if($_POST['SubmitType'] == '儲存查詢') {
	function stripEach(&$element) {
		if(!is_array($element))
			$element = stripslashes($element);
	}

	$to_save = array('grep', 'table', 'dpt_choice', 'no_multi_ge',
	'interval', 'elective', 'modified', 'no_void_time',
	'no_void_serial', 'csv', 'night', 'no_cancelled', 'start', 'number',
	'sortby', 'order', 'outcol_sel', 'ge_sel', 'cou_code_type');

	$to_save = array_merge($to_save, $check1, array_values($check2));

	foreach($check1 as $v)
		$to_save[] = "radio_$v";

	foreach ($to_save as $s)
		if(!empty($_POST[$s]))
			$to_save_r[$s] = $_POST[$s];
	array_walk($to_save_r, 'stripEach');

	$saved = serialize($to_save_r);	
	setcookie('saved_data', $saved, time()+5184000);

	$var = &$_POST;

	foreach($ctime as $v) {
		if($var['class'][$v] == 'on')
			$class_r[$v] = "$v,1";
		else
			$class_r[$v] = "$v,0";
	}

	setcookie('classes', implode(';',$class_r), time()+5184000);

} elseif($_POST['SubmitType'] == '取回儲存') {
	if(!empty($_COOKIE['saved_data'])) {
		$var = unserialize(stripslashes($_COOKIE['saved_data']));
		foreach(explode(';', $_COOKIE['classes']) as $x) {
			list($a, $b) = explode(',', $x);
			$var['class'][$a] = $b;
		}
	} else {
		$var = $_POST;
	}
} elseif($_POST['SubmitType'] == '清除儲存') {
	$var = unserialize(stripslashes($_COOKIE['saved_data']));
	setcookie('classes', '', time()-3600);
	setcookie('saved_data', '', time()-3600);
	$_POST['send'] = 0;
}
?>
