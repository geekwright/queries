#
# Table structure for table 'queries_query'
#

CREATE TABLE queries_query (
  id int(8) unsigned NOT NULL auto_increment,
  uid int(8) unsigned NOT NULL default '0',
  name varchar(60) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  querytext text NOT NULL,
  comment_count smallint(5) unsigned NOT NULL default '0',
  approved tinyint(1) unsigned NOT NULL default '0',
  posted int(10) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM;
