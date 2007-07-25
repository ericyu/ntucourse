use ntucourse;
SET NAMES 'utf8';
delete from 96_1;
load data infile "/home/ericyu/96_1.txt" into TABLE 96_1 fields terminated by '\t' escaped by '';
