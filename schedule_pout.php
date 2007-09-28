<?php
require_once('include/query.inc.php');
require_once('include/query_form.inc.php');
require_once('include/schedule.inc.php');
// output compression
ob_start();
$var = &$_POST;
$SelectedFields = $DefaultSelection;
$trans = empty($var['trans']) ? false : true;
$displayTime = empty($var['display_time']) ? false : true;

if(isset($_POST['sch_no']))
	$sch_no = $_POST['sch_no'];
else
	$sch_no = 'sc1';

if(!empty($_COOKIE[$sch_no])) {
	list($semester, $C) = explode('|', $_COOKIE[$sch_no]);
	$COU = explode(';', $C);
}

formOutColSelect(0);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta name="robots" content="noindex,nofollow">
<style type="text/css">
	TH { font-size: 80%; white-space: nowrap; text-align: center; }
</style>
<title>課表</title>
</head>
<body bgcolor="#FFFFFF">
<table border="1" width="100%" align="center">
<?php
// BELOW ARE SIMILAR TO THOSE CODE IN COURSE.PHP

table_header($SelectedFields);

$total_course = $total_credit = 0;
$SelectedFieldsSQL = column_sql($SelectedFields, split(" ", "cou_cname clsrom cou_code dpt_code class daytime credit"));

if(isset($COU)) {
	$size = sizeof($COU);
	$subquery = makeScheduleQuery($semester, $COU, $SelectedFieldsSQL);
} else
	$size = 0;

if($size > 0) {
	$query=implode(" UNION ALL ", $subquery);
	$result = mysql_query($query, $dbh);
}

for($i = 0; isset($subquery) && $i < $size; ++$i) {
	$tmp = mysql_fetch_assoc($result);
	$row["$tmp[n]"] = $tmp;
}

for($i = 0; isset($subquery) && $i < $size; ++$i) {
	displayRow($row[$i], $row[$i]['t'], false, true, !empty($var['no_link']));
	$course[] = $row[$i]['cou_cname'];
	$time[] = $row[$i]['daytime'];
	$place[] = $row[$i]['clsrom'];
	++$total_course;
	$total_credit += $row[$i]['credit'];
}
?>
</table>
<p align="center">
<?php echo "$total_course 堂課, 共 $total_credit 學分"; ?>
<?php
require_once('include/tablematrix.inc.php');
$class_size = 15;
if(!empty($var['no_zero'])) {
	$class_size -= 1;
	array_shift($ClassTimeName);
}
if(!empty($var['no_nine']))
	$class_size -= $var['no_nine']*5;
elseif(!empty($var['no_night']))
	$class_size -= $var['no_night']*4;
$day_size = 6 - (empty($var['no_sat']) ? 0 : 1);

if($trans)
	$tb = new tablematrix($day_size+1, $class_size+1, false);
else
	$tb = new tablematrix($class_size+1, $day_size+1, false);

$tb->table('border', 1);
$tb->table('width', '100%');

if($trans) {
	$tb->col('width', '5%', 0);
	for($i=1; $i<=$class_size; ++$i)
		$tb->col('width', '10%', $i);
} else {
	$tb->col('width', '3%', 0);
	for($i=1; $i<=$day_size; ++$i)
		$tb->col('width', '10%', $i);
}

if($trans) {
	$tmp_array = $ClassTimeName;
	if($displayTime) {
		foreach($tmp_array as $k => $v) {
			$tmp_array[$k] .= '<br>' . $ClassTimeDetail[$k];
		}
	}
	$tmp_array[0] = '節次<br>星期';
	$tb->addData(array_slice($tmp_array, 0, $class_size+1));
} else {
	$tb->addData($WeekdayName);
}

$size = $trans ? count($WeekdayName)-1 : $class_size;

for($i = 1; $i <= $size; ++$i) {
	if($trans)
		$tb->content($WeekdayName[$i], $i, 0);
	else {
		$tb->content($ClassTimeName[$i] . ($displayTime ? '<br>' . $ClassTimeDetail[$i] : ''), $i, 0);
	}
}

$size = isset($time) ? sizeof($time) : 0;

for($i=0; $i<$size; ++$i) {	// 將時間 & 中文課名填入表格
	foreach(getCourseTime($time[$i]) as $sd) {
		$w = mb_substr($sd, 0, 1);
		$c = strtoupper(mb_substr($sd, 1));
		for($j = 0; $j < strlen($c); ++$j) {
			if(!empty($class["$w$c[$j]"])) {
				$overlap = true;
				break 3;
			} else {
				$class["$w$c[$j]"] = 1;
			}
		}
		fillSection($w, $c, $course[$i], $place[$i], $var["CouClr$i"]);
	}
}
if(!empty($overlap)) {
	echo '<p>課表中有重疊的時段!';
} else {
	$tb->all('style', "color: $var[fg]");
	// 將空白的時段連起來
	for($i=1; $i<=$day_size; ++$i)
		for($j=1; $j<=$class_size; ++$j) {
			if(empty($class["$WeekdayName[$i]$ClassTimeName[$j]"])) {
				for($k=$j+1; $k<=$class_size+1; ++$k) {
					if(@!empty($class["$WeekdayName[$i]$ClassTimeName[$k]"]) || $k > $class_size) {
						if($k - $j > 1) {
							if($trans)
								$tb->td('colspan', $k-$j, $i, $j);
							else
								$tb->td('rowspan', $k-$j, $j, $i);
						}
						if($trans)
							$tb->td('bgcolor', $var['bg'], $i, $j);
						else
							$tb->td('bgcolor', $var['bg'], $j, $i);
						break;
					} else {
						$class["$WeekdayName[$i]$ClassTimeName[$k]"] = 1;
					}
				}
			}
		}
	echo '<p>';
	echo $tb->show();
}
?>
</body>
</html>
<?php
if(empty($var['text'])) {
	ob_end_flush();
} else {
	if(!is_numeric($var['width']) || $var['width'] > 180 || $var['width'] <= 0) {
		ob_clean();
		die("Width out of range.");
	}
	$content = ob_get_contents();
	ob_end_clean();
	$path = "/var/tmp/";
	list($usec, $sec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
	$filename = md5(uniqid(mt_rand(), true)) . ".html";
	if (!$handle = fopen("$path$filename", 'x')) {
		echo "Cannot open file.";
		exit;
	}
	if(is_writable("$path$filename")) {
		if (!fwrite($handle, $content)) {
			echo "Cannot write to file.";
			exit;
		}
	} else {
		die ("I found out you couldn't write to the file!");
	}
	fclose($handle);
	header('Content-Type: text/plain; charset=big5');
	$cmd = "/usr/local/bin/w3m -I utf-8 -O big5 -cols $var[width] $path$filename";
	exec($cmd, $output);
	foreach($output as $line)
		echo $line."\n";
	unlink("$path$filename");
}

// ---------------------------------------------------------------------
function fillSection($weekday, $sec, $cou, $place, $color) {
	global $tb, $ClassTimeName, $WeekdayName, $var, $trans;
	
	while($sec != '') {
	$start = array_search($sec{0}, $ClassTimeName);
	if(!$start || preg_match('/-|,/', $sec))
		return;
		for($i=0;$i<strlen($sec); ++$i) {
			if($ClassTimeName[$i+$start] != substr($sec, $i, 1)) {
				break;
			}
		}
		$row = array_search($sec{0}, $ClassTimeName);
		$col = array_search($weekday, $WeekdayName);
		if($trans) {
			$tmp = $col; $col = $row; $row = $tmp;
		}
		$content = $cou . (!empty($var['display_place']) ? '<br><span style="font-size: 80%;">'.$place : '');
		$span = ($trans ? 'colspan' : 'rowspan');
		$tb->td($span, $i, $row, $col);
		$tb->content($content, $row, $col);
		$tb->td('bgcolor', $color, $row, $col);
		$sec = substr($sec, $i);
	}
}
?>
