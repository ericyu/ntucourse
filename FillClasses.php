<?
require_once('include/query.inc.php');
require_once('include/query_form.inc.php');
// output compression
ob_start('ob_gzhandler');

if(isset($_POST['sch_no']) && !preg_match('/(^sc([123]?)$)/', $_POST['sch_no']))
	header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

if(isset($_POST['sch_no'])) {
	$sch = $_POST['sch_no'];
	if(!empty($_COOKIE[$sch])) {
  	list($semester, $C) = explode('|', $_COOKIE[$sch]);
		$COU = explode(';', $C);
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<meta name="robots" content="noindex,nofollow">
</style>
<title>課表</title>
</head>
<body bgcolor="#FFFFFF"<? echo (isset($_POST['sch_no'])?'onLoad=\'setTimeout("window.close()", 1000);\'':''); ?>>
<?
if(!isset($_POST['sch_no'])) {
	echo '<form action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'" method="post">';
	$sch = array('sc1'=>'課表一', 'sc2'=>'課表二', 'sc3'=>'課表三');
	formSelect('sch_no', $sch);
	echo '<br><input type="submit">';
} else {
for($i=0; isset($COU) && $i<sizeof($COU); ++$i) {
	list($s, $c, $d, $cls) = split(',', $COU[$i]);
	$query = "SELECT daytime FROM $semester WHERE ser_no='$s' AND cou_code='$c' AND dpt_code='$d' AND class='$cls' LIMIT 0,1";
	$row = mysql_fetch_assoc(mysql_query($query, $dbh));

	$daytime = getCourseTime($row["daytime"]);
	foreach($daytime as $sd) {	// 將時間填入表格
		$w = mb_substr($sd, 0, 1);
		$c = mb_substr($sd, 1);
		for($j = 0; $j < strlen($c); ++$j) {
				$class["$w$c[$j]"] = 1;
		}
	}
}
$occupied = '';
for($c = 1; $c < 16; ++$c) {	// $c = sizeof($ClassTimeName)
	for($week = 1; $week < 7; ++$week)
		$occupied .= !empty($class["$WeekdayName[$week]$ClassTimeName[$c]"]) ? '1':'0';
}
?>
<script type="text/javascript">
var day = new Array("1", "2", "3", "4", "5", "6");
var classname = new Array("0","1","2","3","4","@","5","6","7","8","9","A","B","C","D");
var occupied = "<? echo $occupied ?>";
for(var i=0; i<classname.length; ++i) {
	for(var j=0; j<day.length; ++j) {
		var x = "class["+ day[j]+classname[i] + "]";
		window.opener.document.ThisForm.elements[x].checked = (occupied.charAt(i*6+j) == "0");
	}
}
</script>
完成.
<? } ?>
</body>
</html>
