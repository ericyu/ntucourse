<?
require_once('include/query.inc.php');

require('include/header.inc.php');

$res = mysql_query('SELECT * FROM querylog', $dbh);

echo '<pre>';
while($row = mysql_fetch_assoc($res)) {
	$data = unserialize($row['query']);
	foreach($check1 as $c)
		echo $data[$c]."\n";
}
echo '</pre>';

require('include/footer.inc.php');
?>

