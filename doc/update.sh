#!/bin/sh
mv 94_1.txt old.txt
cd t
./fetchdata.pl
mv 94_1.txt ..
cd ..
mysql -u ntucourseupdate -p ntucourse < course/doc/update.sql

course/doc/diff.pl > course/diffs/`date "+%m-%d"`.out

