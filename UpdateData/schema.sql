## The Structure of a Table ##
## FOR MySQL
CREATE TABLE `1xx_x` (
  `type` varchar(2) NOT NULL default '',
  `ser_no` varchar(5) NOT NULL default '',
  `co_chg` varchar(4) NOT NULL default '',
  `dpt_code` varchar(4) NOT NULL default '',
  `dptname` varchar(12) NOT NULL default '',
  `cou_code` varchar(9) NOT NULL default '',
  `class` varchar(2) NOT NULL default '',
  `year` varchar(30) NOT NULL default '',
  `credit` tinyint(1) NOT NULL default '0',
  `forth` varchar(4) NOT NULL default '',
  `sel_code` varchar(4) NOT NULL default '',
  `cou_cname` varchar(40) NOT NULL default '',
	`cou_ename` varchar(200) NOT NULL default '',
  `tea_cname` varchar(10) NOT NULL default '',
  `clsrom` varchar(20) NOT NULL default '',
  `daytime` varchar(60) NOT NULL default '',
  `mark` varchar(255) NOT NULL default '',
  `co_tp` tinyint(1) NOT NULL default '0',
  `co_gmark` varchar(5) NOT NULL default '',
	`co_select` tinyint(1) NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

##### Now those commands should issue encoding=utf-8 #####

## The Command to Import The File Into Database ##

mysqlimport --fields-escaped-by= --fields-terminated-by='\t' \
--fields-optionally-enclosed-by='"' -u USERNAME \
	-h HOSTNAME -p DATABASE_NAME FILE_NAME
(--local)

## FILE_NAME = TABLE_NAME

