use ntucourse;
SET NAMES 'utf8';
delete from 97_1;
load data infile "/home/ericyu/97_1.txt" into TABLE 97_1 fields terminated by '\t' escaped by '';
