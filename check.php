<?
require("include/db.inc.php");
$t = "dpt_code";
$pattern = array("ser_no" => "/^(|\d{5})$/",
				"cou_code" => "/^[0-9A-Z]{3} [0-9A-Z]{5}$/",
				"dpt_code" => "/^(|([0-9A-Z]|[0-9A-Z]{4}))$/",
				"class" => "/^.{0,2}$/");

$tbl = array('93_2');
foreach($tbl as $se) {
	$result = mysql_query("select ".implode(",", array_keys($pattern))." from $se", $dbh);
	while($row = mysql_fetch_assoc($result))
		foreach($pattern as $f => $p)
			if(!preg_match($p, $row[$f]))
				echo $se ."\t".print_r($row,1)."<br>";
}
?>
