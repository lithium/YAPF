DROP TABLE IF EXISTS crew;
CREATE TABLE `crew` (
  id int(11) AUTO_INCREMENT,
  first varchar(255) NOT NULL,
  last varchar(255) NOT NULL,
  birth_date date,
  death_date date,
  PRIMARY KEY (id)
) ENGINE=INNOdb;

DROP TABLE IF EXISTS job;
CREATE TABLE `job` (
  id int(11) AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  UNIQUE KEY (name),
  PRIMARY KEY (id)
) ENGINE=INNODB;

DROP TABLE IF EXISTS publisher;
CREATE TABLE `publisher` (
  id int(11) AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  UNIQUE (name),
  PRIMARY KEY (id)
) ENGINE=INNODB;

DROP TABLE IF EXISTS series;
CREATE TABLE `series` (
  id int(11) AUTO_INCREMENT,
  publisher_id int(11) NOT NULL,
  name varchar(255) NOT NULL,
  version varchar(32),
  start_year date,
  UNIQUE (name,version),
  FOREIGN KEY (publisher_id) REFERENCES publisher(id),
  PRIMARY KEY (id)
) ENGINE=INNODB;

DROP TABLE IF EXISTS issue;
CREATE TABLE `issue` (
  id int(11) AUTO_INCREMENT,
  series_id int(11) NOT NULL,
  issue_no varchar(32) NOT NULL,
  print_date date,
  print_run varchar(32),
  cover varchar(32),
  story_arc varchar(255),
  arc_no varchar(32),
  `condition` varchar(255),
  FOREIGN KEY (series_id) REFERENCES series(id),
  PRIMARY KEY (id)
) ENGINE=INNODB;

DROP TABLE IF EXISTS issue_crew;
CREATE TABLE `issue_crew` (
  issue_id int(11) NOT NULL,
  crew_id int(11) NOT NULL,
  job_id int(11) NOT NULL,
  FOREIGN KEY (issue_id) REFERENCES issue(id),
  FOREIGN KEY (crew_id) REFERENCES crew(id),
  FOREIGN KEY (job_id) REFERENCES job(id),
  PRIMARY KEY (issue_id,crew_id,job_id)
);
