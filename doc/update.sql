use ntucourse;
SET NAMES 'utf8';
delete from 95_2;
load data infile "/home/ericyu/95_2.txt" into TABLE 95_2 fields terminated by '\t' escaped by '';
