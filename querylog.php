<?
require_once('include/query.inc.php');

require('include/header.inc.php');

$res = mysql_query('SELECT * FROM querylog', $dbh);

while($row = mysql_fetch_assoc($res)) {
	$data = unserialize($row['query']);
	if(!empty($data['cou_cname'])) {
		foreach(preg_split("/[, ]+/", $data['cou_cname']) as $cur) {
			@$stats[$cur]++;
		}
	}
#	foreach($check1 as $c)
#		if(!empty($data[$c]))
#			echo $data[$c]."\n";
}
arsort($stats);
echo '<table>';
foreach($stats as $k => $c)
	echo "<tr><td>$k<td>$c";
echo '</table>';

require('include/footer.inc.php');
?>

