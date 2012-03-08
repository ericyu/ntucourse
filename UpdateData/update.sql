use ntucourse;
SET NAMES 'utf8';
delete from 100_2;
load data infile "/home/ericyu/100_2.txt" into TABLE 100_2 fields terminated by '\t' escaped by '';
