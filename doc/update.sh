#!/bin/sh
FILE=100_2
mv ${FILE}.txt old.txt
cd t
./fetchdata.pl
mv ${FILE}.txt ..
cd ..
mysql -u ntucourseupdate -p ntucourse < course/doc/update.sql

course/doc/diff.pl > public_html/dw/diffs/`date "+%Y-%m-%d"`.out
course/doc/diff.pl > course/diffs/`date "+%Y-%m-%d"`.out


