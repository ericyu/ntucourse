#!/usr/bin/perl
@type = qw(1 2 3 4 5 6 7 8 9 10 B 11 12 13 14 15 16 17 18 19 20);

foreach $i (@type) {
	open(OP, "$i.txt");
	open(WR, "> /var/tmp/$i.txt");
	while(<OP>) {
		if(! /(\t|([1-4]+\*?))\r$/) {
			$_ =~ s/\r\n//g;
		}
		$_ =~ s/¢I/@/g;
		print WR;
	}
	close(OP);
	close(WR);
	system("mv -f /var/tmp/$i.txt ./$i.txt");
}

open(PE, "14.txt");
open(WR, "> 14.txt.tmp");
while(<PE>) {
	if(!/®Õ¶¤/) {
		print WR;
	}
}
close(PE);
close(WR);
system("mv -f ./14.txt.tmp ./14.txt");

open(TTL, "> 92_2.txt");
foreach $i (@type) {
	open(R, "$i.txt");
	while(<R>) {
		s/\r\n/\n/g;
		s/\t\"(.+?)\"\t/\t\1\t/g;
		print TTL;
	}
	close(R);
}
