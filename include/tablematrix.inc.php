<?php
/* tablematrix.php (c) 2001 John Dickinson (john@techwise.com.au)
 *
 * TERMS OF USAGE:
 * This file was written and developed by John Dickinson (john@techwise.com.au)
 * for educational and demonstration purposes only.  You are granted the
 * rights to use, modify, and redistribute this file as you wish.  The only
 * requirement is that you must retain this notice, without modifications, at
 * the top of your source code.  No warranties or guarantees are expressed or
 * implied. DO NOT use this code in a production environment without
 * understanding the limitations and weaknesses pretaining to or caused by the
 * use of these scripts, directly or indirectly. USE AT YOUR OWN RISK!
 *
 * If you are looking for someone to code in php, perl, asp, java, 
 * javascript (etc) feel free to email or visit our website at 
 * www.techwise.com.au
 *
 * If you improve upon this script, please send me a copy so i can make it 
 * available
 * If you find any bugs, let me know and I will try to find time to fix them
 */

class tablematrix {
	function tablematrix($numRows=0, $numCols=0, $coordinates=true){
		$this->numRows = $numRows;
		$this->numCols = $numCols;
		$this->coordinates = $coordinates;
		$this->tabletemplate = "<table width='100%' cellpadding='0' cellspacing='0' *>\n";
		$trtemplate = "<tr *>\n";
		$tdtemplate = "<td align='center'  *>\n&nbsp;\n</td>\n";
		for($row=0; $row<$this->numRows; $row++) {
			$this->row[$row] = $trtemplate;
			for($col=0; $col<$numCols; $col++){
				$this->cell[$row][$col] = $tdtemplate;
				$this->cell[$row][$col] = ereg_replace("\&nbsp;", "[$row,$col]", $this->cell[$row][$col]);
				$this->content[$row][$col] = "[$row,$col]";
			}
		}
	}
	function table($name, $value){
		$this->regit($this->tabletemplate, $name, $value);
	}
	function tr($name, $value, $row=0){
		$this->regit($this->row[$row], $name, $value, $row);
	}
	function td($name, $value, $row=0, $col=0){
		$this->regit($this->cell[$row][$col], $name, $value, $row, $col);
	}
	function regit(&$reg, $name, $value, $row=0, $col=0){
		if(!eregi("$name='[^ ]*'",$reg))
			$reg = ereg_replace("\*", " $name='$value' *", $reg);
		$reg = ereg_replace("$name='[^ ]*'", " $name='$value' ", $reg);
		$reg = ereg_replace(" +", " ", $reg);
	}
	function row($name, $value, $row=0, $offset=0){
		for($col=0;$col<$this->numCols; $col++)
			$this->td($name, $value, $row, $col);
	}
	function col($name, $value, $col=0, $offset=0){
		for($row=0; $row<$this->numRows; $row++)
			$this->td($name, $value, $row, $col);
	}
	function all($name, $value){
		for($row=0; $row<$this->numRows; $row++)
			for($col=0; $col<$this->numCols; $col++)
				$this->td($name, $value, $row, $col);
	}
	function altcol($name, $value, $first=1) {
		for($row=0; $row<$this->numRows; $row++)
			for($col=0; $col<$this->numCols; $col++){
				if($first){ if($k%2==0) $ok=true; }
				else{ if($k%2==1) $ok=true; }
				if($ok) $this->td($name, $value, $row, $col);
				$ok=false;
			}
	}
	function altrow($name, $value, $first=1) {
		for($row=0; $row<$this->numRows; $row++)
			for($col=0; $col<$this->numCols; $col++){
				if($first){ if($row%2==0) $ok=true; }
				else{ if($row%2==1) $ok=true; }
				if($ok) $this->td($name, $value, $row, $col);
				$ok=false;
			}
	}
	function checker($name, $value, $first=1) {
		$k=$first;
		for($row=0; $row<$this->numRows; $row++)
			for($col=0; $col<$this->numCols; $col++){
				if(!$col && !($this->numCols%2))  $k=!$k;
				if($k) $this->td($name, $value, $row, $col);
				$k=!$k;
			}
	}
	function content($str, $row=0, $col=0){ $this->content[$row][$col] = $str; }
	function span($row, $col){
		//this is a private function DO NOT CALL IT FROM OUTSIDE!!
		$cell = &$this->cell[$row][$col];
		if(eregi("colspan='[^ ]*'", $cell, $regs)) {
			eregi("'[^ ]*'", $regs[0], $regs);
			$colspan = ereg_replace("'","",$regs[0]);
		}
		if(eregi("rowspan='[^ ]*'", $cell, $regs)) {
			eregi("'[^ ]*'", $regs[0], $regs);
			$rowspan = ereg_replace("'","",$regs[0]);
		}
		if(isset($rowspan) && isset($colspan))
			for($i=1; $i<$colspan; $i++)
				for($j=1; $j<$rowspan; $j++)
					$this->cell[$row+$j][$col+$i]='';
		if(isset($rowspan))
			for($i=1; $i<$rowspan; $i++)
				$this->cell[$row+$i][$col]='';
		if(isset($colspan))
			for($i=1; $i<$colspan; $i++)
				$this->cell[$row][$col+$i]='';
	}
	function addData($arr){
		$k=0;
		for($row=0; $row<$this->numRows; $row++)
			for($col=0; $col<$this->numCols; $col++){
				if(!empty($arr[$k]) && is_Object($arr[$k]))
					$str = $arr[$k]->string;
				else
					$str = (!empty($arr[$k]) ? $arr[$k] : '');
				if($str!="")
					$this->content($str, $row, $col);
				$k++;
			}
	}
	function create(){
		$this->string = '';
		$this->string .= $this->tabletemplate;
		for($row=0; $row<$this->numRows; $row++){
			$this->string = $this->string.$this->row[$row];
			for($col=0; $col<$this->numCols; $col++){
				$cell = &$this->cell[$row][$col];
				$content = &$this->content[$row][$col];
				if(eregi("colspan='[^ ]*'", $cell) or
				  eregi("rowspan='[^ ]*'", $cell))
					$this->span($row,$col);
 				$cell = ereg_replace("[ ]+\*", '', $cell);
				$cell = ereg_replace("\[$row,$col\]", $content, $cell);
				if(!$this->coordinates)
					$cell = ereg_replace("\[$row,$col\]", "&nbsp;", $cell);
				$this->string .= $cell;
			}
			$this->string = $this->string."</tr>\n";
		}
		$this->string .= "</table>";
		$this->create = true;
	}
	function show(){
		$this->create();
		return $this->string;
	}
}
?>
