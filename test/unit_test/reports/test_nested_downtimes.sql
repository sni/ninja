DROP TABLE IF EXISTS test_nested_downtimes;
CREATE TABLE test_nested_downtimes (
  timestamp int(11) NOT NULL DEFAULT '0',
  event_type int(11) NOT NULL DEFAULT '0',
  flags int(11) DEFAULT NULL,
  attrib int(11) DEFAULT NULL,
  host_name varchar(160) CHARACTER SET latin1 COLLATE latin1_general_cs DEFAULT '',
  service_description varchar(160) CHARACTER SET latin1 COLLATE latin1_general_cs DEFAULT '',
  state int(2) NOT NULL DEFAULT '0',
  hard int(2) NOT NULL DEFAULT '0',
  retry int(5) NOT NULL DEFAULT '0',
  downtime_depth int(11) DEFAULT NULL,
  output text CHARACTER SET latin1 COLLATE latin1_general_cs
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO test_nested_downtimes VALUES
	(1202684400,100,NULL,NULL,'','',0,0,0,NULL,NULL),
	(1202684400,801,NULL,NULL,'down_dtstart_dtstart_dtend_dtend','',1,1,3,NULL,'Host is DOWN'),
	(1202684400,801,NULL,NULL,'up_down_dtstart_dtend_up','',0,1,1,NULL,'OK - yadayada'),
	(1202684400,801,NULL,NULL,'down_dtstart_up_dtend','',1,1,1,NULL,'BAD - yadayada'),
	(1202684400,801,NULL,NULL,'up_dtstart_down_dtend','',0,1,1,NULL,'OK - yadayada'),
	(1202684400,801,NULL,NULL,'up_dtstart_down_up_down_dtend','',0,1,1,NULL,'OK - yadayada'),
	(1202688000,801,NULL,NULL,'up_down_dtstart_dtend_up','',1,0,1,NULL,'192.168.1.18 is DOWN'),
	(1202688000,1103,NULL,NULL,'down_dtstart_dtstart_dtend_dtend','',0,0,0,1,NULL),
	(1202688000,1103,NULL,NULL,'up_down_dtstart_dtend_up','',0,0,0,1,NULL),
	(1202688000,1103,NULL,NULL,'down_dtstart_up_dtend','',0,0,0,1,NULL),
	(1202688000,1103,NULL,NULL,'up_dtstart_down_dtend','',0,0,0,1,NULL),
	(1202688000,1103,NULL,NULL,'up_dtstart_down_up_down_dtend','',0,0,0,1,NULL),
	(1202689800,801,NULL,NULL,'up_dtstart_down_up_down_dtend','',1,0,1,NULL,'yada'),
	(1202691600,801,NULL,NULL,'down_dtstart_up_dtend','',0,0,1,NULL,'192.168.1.18 is UP'),
	(1202691600,801,NULL,NULL,'up_dtstart_down_dtend','',1,0,1,NULL,'192.168.1.18 is DOWN'),
	(1202691600,801,NULL,NULL,'up_dtstart_down_up_down_dtend','',0,0,1,NULL,'yada'),
	(1202693400,801,NULL,NULL,'up_dtstart_down_up_down_dtend','',1,0,1,NULL,'yada'),
	(1202694000,1103,NULL,NULL,'down_dtstart_dtstart_dtend_dtend','',0,0,0,1,NULL),
	(1202695200,1104,NULL,NULL,'down_dtstart_dtstart_dtend_dtend','',0,0,0,0,NULL),
	(1202695200,1104,NULL,NULL,'down_dtstart_up_dtend','',0,0,0,0,NULL),
	(1202695200,1104,NULL,NULL,'up_down_dtstart_dtend_up','',0,0,0,0,NULL),
	(1202695200,1104,NULL,NULL,'up_dtstart_down_dtend','',0,0,0,0,NULL),
	(1202695200,1104,NULL,NULL,'up_dtstart_down_up_down_dtend','',0,0,0,0,NULL),
	(1202695800,1104,NULL,NULL,'down_dtstart_dtstart_dtend_dtend','',0,0,0,0,NULL),
	(1202698800,801,NULL,NULL,'up_down_dtstart_dtend_up','',0,1,1,NULL,'OK - yadayada');
