#!/bin/sh
mv 97_1.txt old.txt
cd t
./fetchdata.pl
mv 97_1.txt ..
cd ..
mysql -u ntucourseupdate -p ntucourse < course/doc/update.sql

course/doc/diff.pl > public_html/dw/diffs/`date "+%m-%d"`.out
course/doc/diff.pl > course/diffs/`date "+%m-%d"`.out


