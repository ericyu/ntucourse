use ntucourse;
delete from 93_1;
load data infile "/home/ericyu/93_1.txt" into TABLE 93_1 fields terminated by '\t' escaped by '';
