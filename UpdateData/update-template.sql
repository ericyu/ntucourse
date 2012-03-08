USE ntucourse;
SET NAMES 'utf8';
DELETE from {CURRENT_SEMESTER};
LOAD DATA LOCAL INFILE "./data/{CURRENT_SEMESTER}.txt" into TABLE {CURRENT_SEMESTER} fields terminated by '\t' escaped by '';
