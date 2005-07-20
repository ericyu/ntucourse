use ntucourse;
SET NAMES 'utf8';
delete from 94_1;
load data infile "/home/ericyu/94_1.txt" into TABLE 94_1 fields terminated by '\t' escaped by '';
