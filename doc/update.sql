use ntucourse;
SET NAMES 'utf8';
delete from 95_1;
load data infile "/home/ericyu/95_1.txt" into TABLE 95_1 fields terminated by '\t' escaped by '';
