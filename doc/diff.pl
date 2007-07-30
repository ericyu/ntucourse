#!/usr/bin/perl
use encoding 'utf8', STDOUT => 'utf8';
open(DIFF, "diff -U0 ~/old.txt ~/96_1.txt|");
@set=(0,1,1,0,1,0,1,1,1,1,1,1,1,1,1,1,0,1);
$old_color="#FFEFC0";
$new_color="#89D8FE";
$add_color="#FF9966";

$TABLE_HEAD = "<table border='1'><tr><th>流水號</th><th>異動</th>".
"<th>院系</th><th>班次</th><th>年級</th><th>學分</th><th>期間</th>".
"<th>選/必</th><th>課名</th><th>教師</th><th>教室</th>".
"<th>時間</th><th>備註</th><th>通識</th>\n";

@type = qw(1 2 3 4 5 6 7 8 9 10 B 11 12 13 14 15 16 17 18 19 20);
%title = qw(
1 文學院
2 理學院
3 社會科學院
4 醫學院
5 工學院
6 生物資源暨農學院
7 管理學院
8 公共衛生學院
9 電機資訊學院
10 法學院
B 生命科學院
11 共同必修
12 通識課程
13 分班編組課程
14 體育課程
15 軍訓課程
16 遠距教學
17 各學程
18 進修學士班課程
19 進修學士班共同必修課程
20 進修學士班通識課程);

@tmp = <DIFF>;
chomp(@tmp);

foreach $i (@type) {
	@cur = grep(/^.$i\t/, @tmp);
	@cur = sort compare @cur;
	if(@cur == ()) {
		next;
	}
	$link .= "<a href=\"#$i\">$title{$i}</a> ";
	for(@cur) {
		$output{$i} .= print_out($_);
	}
}

print $link;

foreach $i (@type) {
	if($output{$i} eq "") {
		next;
	}
	print "<a name=\"$i\"><p><h1>".$title{$i}."</h1>";
	print "<a href=\"#top\">top</a>";
	print $TABLE_HEAD;
	print $output{$i};
	print "</table>";
	print "<a href=\"#top\">top</a>\n";
}

sub print_out {
	my @array = split(/\t/);
	my $output = "";
	if($array[0] =~ /^-/) {
		$output .= "<tr bgcolor='$old_color'>";
	} else {
		if($array[2] =~ /加開/) {
			$output .= "<tr bgcolor='$add_color'>";
		} else {
			$output .= "<tr bgcolor='$new_color'>";
		}
	}
	for($c=0; $c<=$#set; ++$c) {
		if($set[$c]) {
			$output .= "<td>";
			if($array[$c]) {
				$array[$c] =~ s/^\"(.+)\"$/\1/;
				$output .= $array[$c];
			} else {
				$output .= "&nbsp;";
			}
		}
	}
	$output .= "\n";
	return $output;
}

sub compare {
	@x = split(/\t/, $a);
	@y = split(/\t/, $b);

	   $x[3] <=> $y[3]
	or $x[7] cmp $y[7]
	or $x[11] cmp $y[11]
	or $x[6] <=> $y[6]
	or $x[1] <=> $y[1]
	or $y[0] cmp $x[0]
#	or $x[2] cmp $y[2]
}
