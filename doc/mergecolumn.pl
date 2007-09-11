#!/usr/bin/perl

while(<>) {
	chomp;
	($type, $ser_no, $co_chg, $dpt_code, $dptname,
	$cou_code, $class, $year, $credit, $forh, $sel_code,
	$cou_cname, $tea_cname, $rooms[0], $rooms[1], $daytime,
	$mark, $co_tp, $co_gmark, $rooms[2], $rooms[3],
	$rooms[4], $rooms[5], $co_select) = split /\t/;
	
	my @rms = ();
	for($i = 0; $i < 6; $i++) {
		if($rooms[$i] ne '') {
			push(@rms, $rooms[$i]);
		}
	}

	$size = scalar @rms;
	if($size == 0) {
		$clsrom = '';
	} else {
		my $diff = 0;
		for($j = 1; $j < $#rms + 1; $j++) {
			if($rms[$j] ne $rms[0]) {
				$diff = 1;
				last;
			}
		}
		if($diff == 0) {
			$clsrom = $rms[0];
		} else {
			$clsrom = join('/', @rms);
		}
	}


	print join("\t", $type, $ser_no, $co_chg, $dpt_code, $dptname,
	$cou_code, $class, $year, $credit, $forh, $sel_code,
	$cou_cname, $tea_cname, $clsrom, $daytime, $mark, $co_tp,
	$co_gmark, $co_select) . "\n";
}

