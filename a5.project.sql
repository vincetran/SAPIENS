--
-- Database: `term_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `cell_providers`
--

CREATE TABLE IF NOT EXISTS `cell_providers` (
  `cp_id` int(11) NOT NULL AUTO_INCREMENT,
  `cp_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `cp_template` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`cp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Information taken from http://www.makeuseof.com/tag/email-to' AUTO_INCREMENT=10 ;

--
-- Dumping data for table `cell_providers`
--

INSERT INTO `cell_providers` (`cp_id`, `cp_name`, `cp_template`) VALUES
(1, 'Alltel', '@message.alltel.com'),
(2, 'AT&T (formerly Cingular)', '@txt.att.net'),
(3, 'Boost Mobile', '@myboostmobile.com'),
(4, 'Nextel (now Sprint Nextel', '@messaging.nextel.com'),
(5, 'Sprint PCS (now Sprint Ne', '@messaging.sprintpcs.com'),
(6, 'T-Mobile', '@tmomail.net'),
(7, 'US Cellular', '@email.uscc.net'),
(8, 'Verizon', '@vtext.com'),
(9, 'Virgin Mobile USA', '@vmobl.com');

-- --------------------------------------------------------

--
-- Table structure for table `dynamic_subscriptions`
--

CREATE TABLE IF NOT EXISTS `dynamic_subscriptions` (
  `dyn_sub_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `min_severity_web` enum('1','2','3','4') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `min_severity_email` enum('1','2','3','4') COLLATE utf8_unicode_ci NOT NULL DEFAULT '2',
  `min_severity_text` enum('1','2','3','4') COLLATE utf8_unicode_ci NOT NULL DEFAULT '3',
  PRIMARY KEY (`dyn_sub_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_id` int(11) NOT NULL,
  `event_severity` enum('1','2','3') COLLATE utf8_unicode_ci NOT NULL,
  `event_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `event_description` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `loc_id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `loc_description` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `loc_latitude` double NOT NULL,
  `loc_longitude` double NOT NULL,
  `parent_loc_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`loc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`loc_id`, `loc_name`, `loc_description`, `loc_latitude`, `loc_longitude`, `parent_loc_id`) VALUES
(1, 'Pittsburgh', 'City of Pittsburgh', 40.4477, -79.997, NULL),
(2, 'University of Pittsburgh', 'University of Pittsburgh Campus', 40.4438, -79.95573, 1),
(3, 'SENSQ', 'Sennott Square Building', 40.4416, -79.9564, 2),
(4, 'CS Dept', 'Department of Computer Science', 40.4416, -79.9564, 3),
(5, 'CL', 'Cathedral of Learning', 40.44419, -79.95319, 2),
(6, 'CBA', 'College of Business Administration', 40.4416, -79.9564, 3),
(7, 'Hillman', 'Hillman Library', 40.44255, -79.95418, 2),
(8, 'CMU', 'Carnegie Mellon University', 40.4431, -79.9431, 1),
(9, 'GATES', 'Gates Center for Computer Science', 40.44349, -79.94456, 8);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `sub_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `loc_id` int(11) NOT NULL,
  `min_severity_web` enum('1','2','3','4') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `min_severity_email` enum('1','2','3','4') COLLATE utf8_unicode_ci NOT NULL DEFAULT '2',
  `min_severity_text` enum('1','2','3','4') COLLATE utf8_unicode_ci NOT NULL DEFAULT '3',
  PRIMARY KEY (`sub_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_firstname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_lastname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `user_login_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_login_pass` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_cell_phone` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `user_cell_provider` int(11) NOT NULL,
  `user_cell_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_login_ts` timestamp NULL DEFAULT NULL,
  `current_login_ts` timestamp NULL DEFAULT NULL,
  `last_loc_id` int(11) DEFAULT NULL,
  `last_loc_checkin_ts` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;