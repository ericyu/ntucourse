use ntucourse;
SET NAMES 'utf8';
delete from 100_1;
load data infile "/home/ericyu/100_1.txt" into TABLE 100_1 fields terminated by '\t' escaped by '';
