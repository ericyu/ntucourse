#!/usr/local/bin/php -q
<?php
/*********************************************************************

I placed the following entry in the crontab to get the daily count:
#mi     hr      md      mo      wd      command
0       0       *       *       *       (/usr/local/bin/php /home/ericyu/public_html/course/dailyCount.php > /dev/null)

*********************************************************************/
// Connect to the database
$host = '';
$user = '';
$password = '';
$db = '';
$dbh = mysql_pconnect($host, $user, $password) or die;
mysql_select_db($db);

// Read the record for yesterday, and reset it.
$day = date("m/d", time()-86400);
$day = date("Y-m-d-H-i-s", time()-86400);
$fp=fopen("/home/ericyu/course/acc.txt","r+");
flock($fp,2);
$count=fgets($fp,1024);
rewind($fp);
fputs($fp,"0");
ftruncate($fp,ftell($fp));
flock($fp,3);
fclose($fp);

mysql_query("INSERT INTO hitlog (count,date) VALUES ($count,'$day')");
?>
