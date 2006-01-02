use ntucourse;
SET NAMES 'utf8';
delete from 94_2;
load data infile "/home/ericyu/94_2.txt" into TABLE 94_2 fields terminated by '\t' escaped by '';
