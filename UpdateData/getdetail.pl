#!/usr/bin/perl
use strict;
use utf8;
use Encode;
use WWW::Mechanize;
use Time::HiRes qw( usleep );
use DBI;

my $host = 'localhost';
my $user = 'ntucourseupdate';
my $password = 'courseupdate';
my $db = 'ntucourse';
my $fromTable = '94_2';
my $outTable = 'tp';

my @mech;
for(0 ... 1) {
	$mech[$_] = WWW::Mechanize->new();
	$mech[$_]->requests_redirectable([]);
}

$mech[0]->get('http://investea.aca.ntu.edu.tw/course/11.htm');
$mech[1]->get('http://couweb3.aca.ntu.edu.tw/course/11.htm');

my $dbh = DBI->connect("DBI:mysql:database=$db;host=$host",
	$user, $password, {RaiseError => 1} );
$dbh->do("SET NAMES 'utf8'");

my $sth = $dbh->prepare("SELECT DISTINCT cou_code,class,MIN(ser_no) AS ".
	"serial FROM $fromTable WHERE ser_no != '' GROUP BY cou_code,class")
	or die "Couldn't prepare statement: " . $dbh->errstr;
$sth->execute;

my $count = 0;
while(my $res = $sth->fetchrow_hashref) {
	print ($count."\n") if ($count % 100 == 0);
	$count++;
	my $r = int(rand(2));

	$mech[$r]->submit_form( fields => { number => $res->{'serial'} } );
	my $response = $mech[$r]->follow_link( url_regex => qr/ConQuery.asp/ );
	my $url = $response->base;
	my $redirect = $response->header('Location');
	my $text;

	if($redirect ne '') {
		$redirect =~ s/^%20(http(s?):\/\/)/\1/g;
		$text = $redirect;
	} else {
		($text) = ( decode("big5", $mech[$r]->content()) =~
			/<textarea rows=12 cols=80 wrap=physical>(.+)<\/textarea>/s );
		$text =~ s/\r//g;
		$text = '' if $text =~ /^\b+$/;
	}

	my ($tea) = ( $url =~ /CLASS_1=.*&tea=(\d*)/ );
	my $ins = $dbh->prepare("INSERT INTO $outTable VALUES (?, ?, ?, ?)");
	$ins->execute($res->{'cou_code'}, $res->{'class'}, $tea, $text);
	$mech[$r]->back();
	$mech[$r]->back();
	usleep(500);
}

$dbh->disconnect;

