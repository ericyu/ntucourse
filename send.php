<?php
require('include/header.inc.php');
$EMAIL = trim(strip_tags($_POST['from']));
if (preg_match("/^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$/", $EMAIL, $check)) {
	if(!(getmxrr(substr(strstr($check[0], '@'), 1), $validate_email_temp) || checkdnsrr(substr(strstr($check[0], '@'), 1),'ANY'))) {
		print '<p>E-mail 主機位址不存在, 請重新輸入.</p>';
		$error = 1;
	}
} else {
	print '<p>E-mail 格式錯誤, 請重新輸入.</p>';
	$error = 1;
}
$recipients = array(
'ericyu'=>'ericyu'.'@'.'gmail.com',
				'kcwu'=>'kcwu'.'@'.'ck.tp.edu.tw',
				'piaip'=>'piaip'.'@'.'csie.ntu.edu.tw');

if(in_array($_POST['recipient'], array_keys($recipients)))
	$recipient = $recipients["$_POST[recipient]"];
else {
	echo '<p>收件者錯誤.</p>';
	$error = 1;
}
$name = stripslashes($_POST['name']);
$contents = stripslashes($_POST['contents']);

$subject = "=?UTF-8?B?" . base64_encode('來自 ntucourse.ericyu.org 的信')."?=";
$headers = 'From: =?UTF-8?B?'.base64_encode($name)."?="."<$_POST[from]>\r\n";
$headers .= 'Content-type: text/plain; charset=utf-8';
if(empty($error) && mail($recipient, $subject, $contents, $headers))
	echo '<p>您的信件成功送出!</p>';
else
	echo '<p>寄信失敗!</p>';

require('include/footer.inc.php');
?>
