use ntucourse;
SET NAMES 'utf8';
delete from 96_2;
load data infile "/home/ericyu/96_2.txt" into TABLE 96_2 fields terminated by '\t' escaped by '';
