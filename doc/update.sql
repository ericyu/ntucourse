use ntucourse;
SET NAMES 'utf8';
delete from 98_2;
load data infile "/home/ericyu/98_2.txt" into TABLE 98_2 fields terminated by '\t' escaped by '';
