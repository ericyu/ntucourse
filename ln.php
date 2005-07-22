<?
require_once('include/query.inc.php');
require_once('include/query_form.inc.php');
require_once('include/schedule.inc.php');
// output compression
ob_start();
$var = &$_POST;
$SelectedFields = $DefaultSelection;
$trans = empty($var['trans']) ? false : true;

if(isset($_GET['sch_no']))
	$sch_no = $_GET['sch_no'];
else
	$sch_no = 'sc1';

if(!empty($_COOKIE[$sch_no])) {
	list($semester, $C) = explode('|', $_COOKIE[$sch_no]);
	$COU = explode(';', $C);
}
		
#formOutColSelect(0);
#您的課表 
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
您的課表 URL 是:<textarea id="url" cols="" style="width: 100%;" wrap="virtual">
<?

$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'/'.
	str_replace('=','',base64_encode(gzdeflate($_COOKIE[$sch_no])));
echo $url;
?>
</textarea>
<script type="text/javascript">
document.getElementById("url").select();
</script>
<p><table border="1" width="100%" align="center">
<?
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
	displayRow($row[$i], $row[$i]['t'], 0, 1, !empty($var['no_link']));
	$course[] = $row[$i]['cou_cname'];
	$time[] = $row[$i]['daytime'];
	$place[] = $row[$i]['clsrom'];
	++$total_course;
	$total_credit += $row[$i]['credit'];
}
?>
</table>
<p align="center">
<? echo "$total_course 堂課, 共 $total_credit 學分"; ?>
<?
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
	array_shift($tmp_array);
	$tmp_array = array_merge('星期<br>節次', $tmp_array);
	$tb->addData(array_slice($tmp_array, 0, $class_size+1));
} else
	$tb->addData($WeekdayName);

$size = $trans ? count($WeekdayName)-1 : $class_size;

for($i = 1; $i <= $size; ++$i) {
	if($trans)
		$tb->content($WeekdayName[$i], $i, 0);
	else
		$tb->content($ClassTimeName[$i], $i, 0);
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
	}
}
?>
</body>
</html>
<?
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


// Decimal > Custom
function dec2any($num, $base=62, $index=false) {
   if (! $base ) {
       $base = strlen( $index );
   } else if (! $index ) {
       $index = substr( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,0 ,$base );
   }
   $out = "";
   for ( $t = floor( log10( $num ) / log10( $base ) ); $t >= 0; $t-- ) {
       $a = floor( $num / pow( $base, $t ) );
       $out = $out . substr( $index, $a, 1 );
       $num = $num - ( $a * pow( $base, $t ) );
   }
   return $out;
}


?>
