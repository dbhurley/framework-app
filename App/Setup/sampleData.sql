DROP TABLE IF EXISTS `#__news`;

CREATE TABLE `#__news` (
  `news_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `body` text,
  PRIMARY KEY (`news_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `#__news` (`news_id`, `title`, `alias`, `body`)
VALUES
	(1,'First Article','first-article','This is the first of many articles in this new application.'),
	(2,'Second Article','second-article','This is the second article in the sample data.'),
	(3,'Third Article','third-article','This is the third article in the sample data series.');
