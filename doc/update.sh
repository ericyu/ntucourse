#!/bin/sh
mv 95_2.txt old.txt
cd t
./fetchdata.pl
mv 95_2.txt ..
cd ..
mysql -u ntucourseupdate -p ntucourse < course/doc/update.sql

course/doc/diff.pl > public_html/dw/diffs/`date "+%m-%d"`.out
course/doc/diff.pl > course/diffs/`date "+%m-%d"`.out


