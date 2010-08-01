use ntucourse;
SET NAMES 'utf8';
delete from 99_1;
load data infile "/home/ericyu/99_1.txt" into TABLE 99_1 fields terminated by '\t' escaped by '';
