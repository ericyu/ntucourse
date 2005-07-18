<?
require_once('include/query.inc.php');
require('include/header.inc.php');

$check1[] = 'dpt_choice';
$AllFields['dpt_choice'] = '系所代碼';

$check = array_merge($check1, $check2);

$res = mysql_query('SELECT * FROM querylog WHERE modify > \'2005-02-20\'', $dbh);

while($row = mysql_fetch_assoc($res)) {
	$data = @unserialize($row['query']);
	foreach($check as $c) {
		if(!empty($data[$c])) {
			foreach(mb_split("[，, ]+", $data[$c]) as $cur) {
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
	$stats[$chk] = array_slice($stats[$chk], 0, 50);
	echo "<td style='vertical-align: text-top; background-color: ";
	echo (in_array($chk, $check1) ? '#eeffdd' : '#ddeeff').";'>";
	if(preg_match('/^not_(.+)$/', $chk, $result))
		echo '不包括'.$AllFields["$result[1]"];
	else
		echo '包括'.$AllFields[$chk];
	echo '<table border="1">';
	foreach($stats[$chk] as $k => $c)
		echo "<tr><td>$k<td>$c";
	echo '</table>';
}
echo '</table>';

require('include/footer.inc.php');
?>

