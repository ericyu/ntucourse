<?
require_once('HTTP/Request.php');
$INFO = array('http://investea.aca.ntu.edu.tw/course/course_asp/ConQuery.asp',
'http://couweb3.aca.ntu.edu.tw/course/course_asp/ConQuery.asp');
$host = 'localhost';
$user = 'ericyu';
$password = 'CouRRs';
$db = 'course';
$dbh = mysql_pconnect($host, $user, $password) or die("The database system is not ready.");
mysql_select_db($db);

$query = "SELECT DISTINCT cou_code,class,tea_cname FROM 93_2";
$result = mysql_query($query, $dbh);
$count = 0;

$par = array(allowRedirects => false, timeout => 5);
$req = &new HTTP_Request('', $par);

while($row = mysql_fetch_assoc($result)) {
	if($count % 500 == 0)
		echo $count."\n";
	usleep(500000);
	$count++;
	list($c1, $c2) = split(" ", $row[cou_code]);
#	$url = "$INFO?COU_CODE_1=$c1&COU_CODE_2=$c2&CLASS_1=$row[class]"; // old
	$tea = iconv($row[tea_cname], 'UTF-8', 'big5');
	$urlappend = "?tea='%20OR%20TEA_CNAME='".urlencode($tea)."'%20AND%20COU_CODE='$c1%20$c2'%20AND%20CLASS='$row[class]";
	$url = $INFO[array_rand($INFO)].$urlappend;
	$req->setURL($url);

	$err = @$req->sendRequest();
	$errcount = 0;

	while (PEAR::isError($err)) {
		$errcount++;

		if($errcount <= 3) {
			echo "failed in connection... Retrying $errcount\n";
			sleep(30);
			$url = $INFO[array_rand($INFO)].$urlappend;
			$req->setURL($url);
			$err = @$req->sendRequest();
		} else {
			break;
		}
	}

	if(PEAR::isError($err)) {
		echo "failed at $url\n";
		continue;
	}
	if($errcount > 0)
		echo "Retry OK\n";

	$content = $req->getResponseBody();
	if($content == false) {
		echo "Failed at $count: $c1 $c2 $row[class]\n";
		continue;
	}
	if($req->getResponseHeader('Location')) {
		$comment = $req->getResponseHeader('Location');
		$comment = preg_replace("/^%20(http:\/\/)/", "\1", $comment);
	} else {
		$content = preg_replace("/\r/", "", $content);
/* ==
		preg_match("/課程編號：([0-9A-Z]{3} [0-9A-Z]{5}).+\n班次：(..)[\S\s]+授課教師：(.+)<\/th>[\S\s]+<textarea.+>([\S\s]+)<\/textarea>/", $content, $a);
		if($a[4] != "") {
			$comment = "授課教師：".mysql_escape_string($a[3])."\n";
			$comment .= mysql_escape_string($a[4]);
== */
		preg_match("/課程編號：([0-9A-Z]{3} [0-9A-Z]{5}).+\n班次：(..)[\S\s]+<textarea.+>([\S\s]+)<\/textarea>/", $content, $a);
		if($a[3] != "") {
			$comment = mysql_escape_string($a[3]);
		} else {
			unset($comment);
		}
//		echo $url."\n";
//		print_r($a);
	}
	if(!preg_match("/^ *$/", $comment)) {
		$sql = "INSERT into tp values('$row[cou_code]', '$row[class]', '$comment')";
		mysql_query($sql);
	}
}
?>
