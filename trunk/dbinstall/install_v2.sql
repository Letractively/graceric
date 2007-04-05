CREATE TABLE gcdb_spams (
  spam_ID bigint(20) NOT NULL auto_increment,
  spam_value varchar(200) NOT NULL default '',
  spam_type enum('name','email','text','ip') NOT NULL default 'text',  
  PRIMARY KEY  (spam_ID)
);
