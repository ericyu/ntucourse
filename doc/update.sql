use ntucourse;
SET NAMES 'utf8';
delete from 97_2;
load data infile "/home/ericyu/97_2.txt" into TABLE 97_2 fields terminated by '\t' escaped by '';
