<?php
require('include/header.inc.php');
?>
<h1>寄信給作者</h1>
任何問題及建議或是可以新增的功能，歡迎寄給下面的作者群：
<ul>
<li>目前的維護者: ericyu</li>
<li>本程式原創作者: kcwu</li>
<li>(新)網站樣式設計: <a href="http://www.csie.ntu.edu.tw/~piaip/">piaip</a></li>
<li>感謝協助: <a href="http://www.csie.ntu.edu.tw/~b88039/">tkirby</a></li>
</ul>
<hr>
<form action="send.php" method="post">
<table>
<tr><td>寄件者</td><td><input type="text" size="16" name="name"> 請填入您的名字</td></tr>
<tr><td>Email</td><td><input type="text" size="30" name="from"> 請填入您的 Email address</td></tr>
<tr><td>收件者</td><td>
<select name="recipient">
<option value="ericyu">ericyu</option>
<option value="kcwu">kcwu</option>
<option value="piaip">piaip</option>
</select> 為了避免廣告信, 這裡不公布真實 email</td></tr>
<tr><td>內容:</td><td><textarea name="contents" rows="8" cols="64"></textarea></td></tr>
</table>
<input type="submit" class="submit" value="送出"> <input type="reset" class="submit" value="重填">
</form>
<?php
require('include/footer.inc.php');
?>
