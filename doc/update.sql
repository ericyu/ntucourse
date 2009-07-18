use ntucourse;
SET NAMES 'utf8';
delete from 98_1;
load data infile "/home/ericyu/98_1.txt" into TABLE 98_1 fields terminated by '\t' escaped by '';
