<?
error_reporting(E_ALL);
?>
<html>
<head>
<title>Source</title>
</head>

<? $DONT_SHOW = array('source.php', 'db.inc.php', 'send.php',
				'hitStats.php', 'dailyCount.php'); ?>

<body bgcolor="white">
<?
if(!empty($_GET['show']))
	$f = urldecode($_GET['show']);
else
	$f = '.';

if(!strstr(realpath($f).'/', realpath('.').'/') || !file_exists($f))
	die('read error');

if(filetype($f) == 'file') {
	echo '<font size="+3" face="Arial">Source Code of '.$f.'</font><br>';
	if(!in_array($f, $DONT_SHOW))
		show_source($f);
	else
		die('Failed to Open File');
} elseif(filetype($f) == 'dir') {
?>
<center>
<font size="+3">Show Source</font>
<table border="0" cellpadding="3">
	<tr>
	<th align="center" bgcolor="#abcdef">檔案名稱</td>
	<th align="center" bgcolor="#abcdef">大小</td>
	<th align="center" bgcolor="#abcdef">更動日期</td>
	</tr>
<?
$current = $f;
$oper = opendir($current);
while($file = readdir($oper)) {
	switch(filetype($current.'/'.$file)) {
		case 'file':
		if(in_array($file, $DONT_SHOW))
			continue;
		$filename[] = $file;
		break;
		case 'dir':
		if($file != '.' && $file != '..')
			$dirname[] = $file;
		break;
	}
}
closedir($oper);

if(!empty($dirname)) {
	sort($dirname);
	foreach($dirname as $file) {
		$fullpath = $current.'/'.$file;
		$time = strftime("%m/%d/%Y",filemtime($fullpath));
		echo '<tr><td><a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].
		'?show='.$current.'/'.$file.'">'.$file.'/</a><td align="right">--DIR--</td>'.
		'<td align="center">'.$time.'</td></tr>';
	}
}

if(!empty($filename)) {
	sort($filename);
	foreach($filename as $file) {
//		if(!preg_match("/\.(php|pl)$/", $file))
//			continue;
		$fullpath = $current.'/'.$file;
		$size = measure_size(filesize($fullpath));
		$time = strftime("%m/%d/%Y",filemtime($fullpath));
		echo '<tr><td><a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].
			'?show='.$current.'/'.$file.'">'.$file.'</a><td align="right">'.$size.'</td>'.
			'<td align="center">'.$time.'</td></tr>';
	}
}
echo '</table></center>';
}
?>
</body> 
</html> 
<?
function measure_size($size) {
	if($size <= 1)
		$size = "$size byte";
	elseif($size < 1000)
		$size = "$size bytes";
	else {
		$size=$size/1024;
		if ($size < 999)
			$size = round($size,1)." K";
		else
			$size=round($size/1024,1)." M";
	}
	return $size;
}
?>
