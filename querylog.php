<?
require_once('include/query.inc.php');

require('include/header.inc.php');

$res = mysql_query('SELECT * FROM querylog', $dbh);

$check = array_merge($check1, $check2);
while($row = mysql_fetch_assoc($res)) {
	$data = unserialize($row['query']);
	foreach($check as $c) {
		if(!empty($data[$c])) {
			foreach(preg_split("/[, ]+/", $data[$c]) as $cur) {
				@$stats[$c][$cur]++;
			}
		}
	}
}
echo '<table>';
foreach($check as $chk) {
	if(empty($stats[$chk]))
		continue;
	arsort($stats[$chk]);
	echo "<td style='vertical-align: text-top;'>$chk<table border>";
	foreach($stats[$chk] as $k => $c)
		echo "<tr><td>$k<td>$c";
	echo '</table>';
}
echo '</table>';

require('include/footer.inc.php');
?>

