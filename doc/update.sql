use ntucourse;
SET NAMES 'utf8';
delete from 93_2;
load data infile "/home/ericyu/93_2.txt" into TABLE 93_2 fields terminated by '\t' escaped by '';
