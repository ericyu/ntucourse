<?
session_name('ntucourse_sid');
ini_set('session.use_only_cookies', true);
session_start();
header("Cache-control: private");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta name="robots" content="noindex,nofollow">
    <meta name="author" content="ericyu">
    <link rel="stylesheet" type="text/css" href="css/sinorca-grey.css" title="Grey boxes stylesheet">
    <style type="text/css" media="all">
		@import "css/color-scheme.css";
		@import "dpt/ThemeOffice/theme.css";
    </style>
    <script type="text/javascript" src="js/function.js"></script>
    <script type="text/javascript" src="js/drag.js"></script>
    <title>選課輔助程式</title>
  </head>
  <body>
<!-- BEGIN FLOATING LAYER CODE //-->
<div class="drag" id="theLayer" style="position:absolute;width:220px;left:350px;top:100px;visibility:hidden;z-index:25;">
<table style="width: 240px; background-color: #000000;">
  <tr><td>
  <div style="color: #ffffff; font-size: 100%;">上課節數與時間對照表(可移動)</div>
  </td>
  <td><a href="#" onClick="javascript:hideMe();return false;" style="color: #ffffff; text-decoration: none;">X</a>
  </td></tr>
  <tr><td style="background-color: #ffffff; padding:4px;" colspan="2">

<!-- PLACE YOUR CONTENT HERE //-->  
    <table class="time">
    <tr><th>節次</th><th>上課時間</th>
    <tr><td>0<td>7:10-8:00    <tr><td>1<td>8:10-9:00
    <tr><td>2<td>9:10-10:00    <tr><td>3<td>10:20-11:10
    <tr><td>4<td>11:20-12:10    <tr><th>@<th>12:20-13:10
    <tr><td>5<td>13:20-14:10    <tr><td>6<td>14:20-15:10
    <tr><td>7<td>15:30-16:20    <tr><td>8<td>16:30-17:20
    <tr><td>9<td>17:30-18:20    <tr><th>A<th>18:30-19:20
    <tr><td>B<td>19:25-20:15    <tr><td>C<td>20:25-21:15
    <tr><td>D<td>21:20-22:10    </table>
<!-- END OF CONTENT AREA //-->
  </td>
  </tr>
</table>
</div>
<!-- END FLOATING LAYER CODE //--> 

    <!-- ###### Header ###### -->

    <div class="GetFirefox">
	建議使用瀏覽器<br>
		<a href="http://ericyu.org/click/click.php?id=1"
		style="padding: 2px; margin: 2px;"><img alt="get Firefox" src="images/rediscover.gif" width="178" height="60"></a>
	</div>

    <div id="header">選課輔助程式</div>

    <div id="lowerMenuBar">
      <a href="tutorial.php">使用說明</a>|
      <a href="index.php" class="highlight">選課</a>|
      <a href="schedule.php">課表</a>|
      <a href="serial.php">以流水號查詢</a>|
      <a href="place.php">上課地點對照</a>|
      <a href="javascript:showMe();">節數時間</a>|
      <a href="http://man.ptt.cc/man.pl/NTUcourse/">PTT 課程板</a>|
      <a href="http://ericyu.org/phpBB2/index.php">討論區</a>|
      <a href="mail.php">聯絡</a>
    </div>

    <!-- ###### Body Text ###### -->

    <div id="bodyText">
