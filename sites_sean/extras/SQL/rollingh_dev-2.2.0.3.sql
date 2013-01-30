-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 25, 2008 at 02:30 PM
-- Server version: 5.0.45
-- PHP Version: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `rollingh_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_AdminSpecials`
--

DROP TABLE IF EXISTS `APPDEV_AdminSpecials`;
CREATE TABLE IF NOT EXISTS `APPDEV_AdminSpecials` (
  `PageName` text NOT NULL,
  `PageFileName` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='This will hold site specific pages to be displayed on admin_';

--
-- Dumping data for table `APPDEV_AdminSpecials`
--

INSERT INTO `APPDEV_AdminSpecials` VALUES('Create User Group', 'special_user_group_create-1.php');

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_BlogHeaders`
--

DROP TABLE IF EXISTS `APPDEV_BlogHeaders`;
CREATE TABLE IF NOT EXISTS `APPDEV_BlogHeaders` (
  `blName` text NOT NULL COMMENT 'The name of the blog',
  `blDescription` text NOT NULL COMMENT 'A description of the blog',
  `blTable` text NOT NULL COMMENT 'The name of the table this header is for',
  `blBlogType` enum('LIFO','CALENDAR','ORDERED','MUSIC') NOT NULL default 'LIFO' COMMENT 'CALENDAR and ORDERED will display some additional UI widgets in the editor',
  `blRecordNumber` int(4) NOT NULL auto_increment,
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This contains header information for the V2 blogs' AUTO_INCREMENT=67 ;

--
-- Dumping data for table `APPDEV_BlogHeaders`
--

INSERT INTO `APPDEV_BlogHeaders` VALUES('Calendar', 'This list will sort in date order with the most recent dates on the top of the list.\r\n<br><br>\r\n', 'APPDEV_BLOG_CalendarBlog', 'CALENDAR', 1);
INSERT INTO `APPDEV_BlogHeaders` VALUES('LIFO', 'This blog will does not have the date and time info.\r\n<br><br>\r\nIt will sort in LIFO order. The most recent item will be displayed on top.', 'APPDEV_BLOG_LifoBlog', 'LIFO', 2);
INSERT INTO `APPDEV_BlogHeaders` VALUES('Ads', 'This has advertising group 1 in it...', 'APPDEV_BLOG_AdOne', 'ORDERED', 8);
INSERT INTO `APPDEV_BlogHeaders` VALUES('SEO Text', 'The SEO text that is at the bottom of each page', 'APPDEV_BLOG_SEO_Text', 'LIFO', 13);
INSERT INTO `APPDEV_BlogHeaders` VALUES('Music', 'A music test blog', 'APPDEV_BLOG_music', 'MUSIC', 66);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_BLOG_AdOne`
--

DROP TABLE IF EXISTS `APPDEV_BLOG_AdOne`;
CREATE TABLE IF NOT EXISTS `APPDEV_BLOG_AdOne` (
  `blHeadline` text NOT NULL,
  `blSubHead` text NOT NULL,
  `blCopy` text NOT NULL COMMENT 'This contains the body of the post',
  `blPosterID` text NOT NULL COMMENT 'This is the User ID of the poster',
  `blPostingDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `blEventDate` datetime default NULL COMMENT 'This is the event date if BlogType is CALENDAR',
  `blEventTime` text COMMENT 'This has a text string of the event time range',
  `blItemType` enum('TEXT','HTML') NOT NULL default 'TEXT' COMMENT 'Indicates if the item is text or HTML text has the cr stripped',
  `blVisibility` enum('SHOW','HIDE') NOT NULL default 'SHOW' COMMENT 'This is for moderated blogs',
  `blRecordNumber` int(4) NOT NULL auto_increment COMMENT 'Auto inc record ID',
  `blSortType` enum('RANDOM','FIXED','LIFO') NOT NULL default 'RANDOM' COMMENT 'This is for Ads it holds the sort type - if fixed refer to blSortOrder',
  `blSortOrder` int(2) NOT NULL default '0' COMMENT 'If SortType is fixed this field has ascending order numbers',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `APPDEV_BLOG_AdOne`
--

INSERT INTO `APPDEV_BLOG_AdOne` VALUES('Random #3', 'Random #3', 'It can contain HTML text and pictures...', 'clark', '2007-03-25 20:25:05', '0000-00-00 00:00:00', '', 'TEXT', 'SHOW', 20, 'RANDOM', 19);
INSERT INTO `APPDEV_BLOG_AdOne` VALUES('Random #2', 'Random #2', 'It can contain HTML text and pictures...', 'clark', '2007-03-25 20:25:05', '0000-00-00 00:00:00', '', 'TEXT', 'SHOW', 3, 'RANDOM', 13);
INSERT INTO `APPDEV_BLOG_AdOne` VALUES('Random #1', 'Random #1', 'It can contain HTML text and pictures...', 'clark', '2007-03-25 20:25:05', '0000-00-00 00:00:00', '', 'TEXT', 'SHOW', 5, 'RANDOM', 31);
INSERT INTO `APPDEV_BLOG_AdOne` VALUES('Fixed #1', 'Fixed #1', 'It can contain HTML text and pictures...', 'clark', '2007-03-28 15:17:23', NULL, NULL, 'TEXT', 'SHOW', 6, 'FIXED', 28);
INSERT INTO `APPDEV_BLOG_AdOne` VALUES('Fixed #2', 'Fixed #2', 'It can contain HTML text and pictures...', 'clark', '2007-03-28 15:17:23', NULL, NULL, 'TEXT', 'SHOW', 7, 'FIXED', 29);
INSERT INTO `APPDEV_BLOG_AdOne` VALUES('Graphic Ad', '', '<a target="_blank" href="http://cookseytalbottstudio.com/">\r\n						<img border="0" src="http://www.cookseytalbottgallery.com/RHSDev/images/ads/DisplayAdPlaceholder4.jpg"></a>', 'clark', '2007-03-28 21:19:14', '0000-00-00 00:00:00', '', 'HTML', 'SHOW', 19, 'FIXED', 27);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_BLOG_CalendarBlog`
--

DROP TABLE IF EXISTS `APPDEV_BLOG_CalendarBlog`;
CREATE TABLE IF NOT EXISTS `APPDEV_BLOG_CalendarBlog` (
  `blHeadline` text NOT NULL,
  `blSubHead` text NOT NULL,
  `blCopy` text NOT NULL COMMENT 'This contains the body of the post',
  `blPosterID` text NOT NULL COMMENT 'This is the User ID of the poster',
  `blPostingDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `blEventDate` datetime default NULL COMMENT 'This is the event date if BlogType is CALENDAR',
  `blEventTime` text COMMENT 'This has a text string of the event time range',
  `blItemType` enum('TEXT','HTML') NOT NULL default 'TEXT' COMMENT 'Indicates if the item is text or HTML text has the cr stripped',
  `blVisibility` enum('SHOW','HIDE') NOT NULL default 'SHOW' COMMENT 'This is for moderated blogs',
  `blRecordNumber` int(4) NOT NULL auto_increment COMMENT 'Auto inc record ID',
  `blSortType` enum('RANDOM','FIXED','LIFO') NOT NULL default 'RANDOM' COMMENT 'This is for Ads it holds the sort type - if fixed refer to blSortOrder',
  `blSortOrder` int(2) NOT NULL default '0' COMMENT 'If SortType is fixed this field has ascending order numbers',
  `blFileName` text NOT NULL COMMENT 'This is the name of the media file associated with the posting for MUSIC and MOVIE blogs',
  `blPath` text NOT NULL COMMENT 'This is the path to the media file',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Dumping data for table `APPDEV_BLOG_CalendarBlog`
--

INSERT INTO `APPDEV_BLOG_CalendarBlog` VALUES('What is up ?', 'Does this break ?', 'oppsie!						', 'clark', '2008-05-07 14:18:22', '2008-05-07 09:00:00', '01:00', 'HTML', 'SHOW', 50, 'LIFO', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_BLOG_LifoBlog`
--

DROP TABLE IF EXISTS `APPDEV_BLOG_LifoBlog`;
CREATE TABLE IF NOT EXISTS `APPDEV_BLOG_LifoBlog` (
  `blHeadline` text NOT NULL,
  `blSubHead` text NOT NULL,
  `blCopy` text NOT NULL COMMENT 'This contains the body of the post',
  `blPosterID` text NOT NULL COMMENT 'This is the User ID of the poster',
  `blPostingDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `blEventDate` datetime default NULL COMMENT 'This is the event date if BlogType is CALENDAR',
  `blEventTime` text COMMENT 'This has a text string of the event time range',
  `blItemType` enum('TEXT','HTML') NOT NULL default 'TEXT' COMMENT 'Indicates if the item is text or HTML text has the cr stripped',
  `blVisibility` enum('SHOW','HIDE') NOT NULL default 'SHOW' COMMENT 'This is for moderated blogs',
  `blRecordNumber` int(4) NOT NULL auto_increment COMMENT 'Auto inc record ID',
  `blSortType` enum('RANDOM','FIXED','LIFO') NOT NULL default 'RANDOM' COMMENT 'This is for Ads it holds the sort type - if fixed refer to blSortOrder',
  `blSortOrder` int(2) NOT NULL default '0' COMMENT 'If SortType is fixed this field has ascending order numbers',
  `blFileName` text NOT NULL COMMENT 'This is the name of the media file associated with the posting for MUSIC and MOVIE blogs',
  `blPath` text NOT NULL COMMENT 'This is the path to the media file',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `APPDEV_BLOG_LifoBlog`
--

INSERT INTO `APPDEV_BLOG_LifoBlog` VALUES('ggg', 'hhh', 'moo<br><br><a target="_blank" class="blogFileSharingLink" href="/RHSDev/shared/cow.jpg">Click here to Download the file - cow.jpg\n							</a><br><br>', 'clark', '2008-04-27 19:11:05', '0000-00-00 00:00:00', '', 'HTML', 'SHOW', 1, 'FIXED', 1, 'cow.jpg', '/shared');
INSERT INTO `APPDEV_BLOG_LifoBlog` VALUES('now with tiny MCE', 'ggg', 'baaaa<br><br><a target="_blank" class="blogFileSharingLink" href="/RHSDev/shared/goat.gif">Click here to Download the file - goat.gif\n							</a><br><br>', 'clark', '2008-04-27 19:17:57', '0000-00-00 00:00:00', '', 'HTML', 'SHOW', 2, 'LIFO', 0, 'goat.gif', '/shared');
INSERT INTO `APPDEV_BLOG_LifoBlog` VALUES('What is up ?', 'Alison had a bug... ???', '																		And she reported it from the prior rev...						', 'clark', '2008-05-07 13:50:26', '0000-00-00 00:00:00', ':', 'HTML', 'SHOW', 3, 'LIFO', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_BLOG_music`
--

DROP TABLE IF EXISTS `APPDEV_BLOG_music`;
CREATE TABLE IF NOT EXISTS `APPDEV_BLOG_music` (
  `blHeadline` text NOT NULL,
  `blSubHead` text NOT NULL,
  `blCopy` text NOT NULL COMMENT 'This contains the body of the post',
  `blPosterID` text NOT NULL COMMENT 'This is the User ID of the poster',
  `blPostingDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `blEventDate` date default NULL COMMENT 'This is the event date if BlogType is CALENDAR',
  `blEventTime` text COMMENT 'This has a text string of the event time range',
  `blItemType` enum('TEXT','HTML') NOT NULL default 'TEXT' COMMENT 'Indicates if the item is text or HTML text has the cr stripped',
  `blVisibility` enum('SHOW','HIDE') NOT NULL default 'SHOW' COMMENT 'This is for moderated blogs',
  `blRecordNumber` int(4) NOT NULL auto_increment COMMENT 'Auto inc record ID',
  `blSortType` enum('RANDOM','FIXED','LIFO') NOT NULL default 'RANDOM' COMMENT 'This is for Ads it holds the sort type - if fixed refer to blSortOrder',
  `blSortOrder` int(2) NOT NULL default '0' COMMENT 'If SortType is fixed this field has ascending order numbers',
  `blFileName` text NOT NULL COMMENT 'This is the name of the media file associated with the posting for MUSIC and MOVIE blogs',
  `blPath` text NOT NULL COMMENT 'This is the path to the media file',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `APPDEV_BLOG_music`
--

INSERT INTO `APPDEV_BLOG_music` VALUES('t5', 't5', 't5', 'clark', '2008-04-27 18:38:20', '0000-00-00', '', 'HTML', 'SHOW', 12, 'LIFO', 2, 'Static-PushIt-OneShot2.mp3', '/shared');
INSERT INTO `APPDEV_BLOG_music` VALUES('Test 4', '444', 's1', 'clark', '2008-04-27 18:35:54', '0000-00-00', '', 'HTML', 'SHOW', 11, 'LIFO', 4, 'Static-PushIt-Stinger1.mp3', 'shared');
INSERT INTO `APPDEV_BLOG_music` VALUES('test 3', 'joooboo', 'fooboo', 'clark', '2008-04-27 18:32:39', '0000-00-00', '', 'HTML', 'SHOW', 10, 'FIXED', 5, 'Static-PushIt-OneShot1.mp3', '/shared');

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_BLOG_SEO_Text`
--

DROP TABLE IF EXISTS `APPDEV_BLOG_SEO_Text`;
CREATE TABLE IF NOT EXISTS `APPDEV_BLOG_SEO_Text` (
  `blHeadline` text NOT NULL,
  `blSubHead` text NOT NULL,
  `blCopy` text NOT NULL COMMENT 'This contains the body of the post',
  `blPosterID` text NOT NULL COMMENT 'This is the User ID of the poster',
  `blPostingDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `blEventDate` datetime default NULL COMMENT 'This is the event date if BlogType is CALENDAR',
  `blEventTime` text COMMENT 'This has a text string of the event time range',
  `blItemType` enum('TEXT','HTML') NOT NULL default 'TEXT' COMMENT 'Indicates if the item is text or HTML text has the cr stripped',
  `blVisibility` enum('SHOW','HIDE') NOT NULL default 'SHOW' COMMENT 'This is for moderated blogs',
  `blRecordNumber` int(4) NOT NULL auto_increment COMMENT 'Auto inc record ID',
  `blSortType` enum('RANDOM','FIXED','LIFO') NOT NULL default 'RANDOM' COMMENT 'This is for Ads it holds the sort type - if fixed refer to blSortOrder',
  `blSortOrder` int(2) NOT NULL default '0' COMMENT 'If SortType is fixed this field has ascending order numbers',
  `blFileName` text NOT NULL COMMENT 'This is the name of the media file associated with the posting for MUSIC and MOVIE blogs',
  `blPath` text NOT NULL COMMENT 'This is the path to the media file',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `APPDEV_BLOG_SEO_Text`
--

INSERT INTO `APPDEV_BLOG_SEO_Text` VALUES('SEO-Text, This field is unused...', 'SEO-Text, This field is unused...', 'This is the block of SEO info. This whole construct is in the SEO Text blog<br><br>', 'clark', '2008-02-23 17:08:20', '0000-00-00 00:00:00', '', 'TEXT', 'SHOW', 1, 'RANDOM', 0, '', '');
INSERT INTO `APPDEV_BLOG_SEO_Text` VALUES('SEO-Text, This field is unused...', 'SEO-Text, This field is unused...', 'This is an alternate random block of SEO text to beat the similar page hiding behavior. <br><br>You can have a number of text blocks in the blog delivered in random order by the RHS SEO Lib', 'clark', '2008-02-23 17:29:09', '0000-00-00 00:00:00', '', 'TEXT', 'SHOW', 2, 'RANDOM', 0, '', '');
INSERT INTO `APPDEV_BLOG_SEO_Text` VALUES('SEO-Text, This field is unused...', 'SEO-Text, This field is unused...', 'This is a third variation of SEO text from the SEO text blog being delivered in randon order...', 'clark', '2008-02-23 17:46:15', '0000-00-00 00:00:00', '', 'TEXT', 'SHOW', 3, 'RANDOM', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_Captcha`
--

DROP TABLE IF EXISTS `APPDEV_Captcha`;
CREATE TABLE IF NOT EXISTS `APPDEV_Captcha` (
  `FileName` text NOT NULL COMMENT 'The captcha filename',
  `PlainText` text NOT NULL COMMENT 'The text string shown in the file',
  `Type` enum('small','large') NOT NULL COMMENT 'The type ',
  `RecordNumber` int(2) NOT NULL auto_increment COMMENT 'The record number',
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This has info on captcha image files for captcha_lib' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `APPDEV_Captcha`
--

INSERT INTO `APPDEV_Captcha` VALUES('10.gif', 'fnogd', 'small', 1);
INSERT INTO `APPDEV_Captcha` VALUES('11.gif', 'ahkpv', 'small', 2);
INSERT INTO `APPDEV_Captcha` VALUES('12.gif', 'kdwpu', 'small', 3);
INSERT INTO `APPDEV_Captcha` VALUES('14.gif', 'pavo', 'small', 4);
INSERT INTO `APPDEV_Captcha` VALUES('1.gif', 'GIRAFFE', 'large', 5);
INSERT INTO `APPDEV_Captcha` VALUES('2.gif', 'ELEPHANT', 'large', 6);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_ChronProcess`
--

DROP TABLE IF EXISTS `APPDEV_ChronProcess`;
CREATE TABLE IF NOT EXISTS `APPDEV_ChronProcess` (
  `ProcessID` text NOT NULL COMMENT 'The ID of the caller',
  `UpdateDate` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP COMMENT 'The date of the last update',
  `foo` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='This holds update info on faux chron processes';

--
-- Dumping data for table `APPDEV_ChronProcess`
--

INSERT INTO `APPDEV_ChronProcess` VALUES('CALENDAR', '2008-05-07 15:04:52', '');

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_Debug`
--

DROP TABLE IF EXISTS `APPDEV_Debug`;
CREATE TABLE IF NOT EXISTS `APPDEV_Debug` (
  `message` text NOT NULL COMMENT 'One of many lines of debug message',
  `function` text NOT NULL,
  `line` text NOT NULL,
  `module` text NOT NULL,
  `time_stamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `id_number` int(4) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id_number`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This is the table for debug_lib.php' AUTO_INCREMENT=73 ;

--
-- Dumping data for table `APPDEV_Debug`
--

INSERT INTO `APPDEV_Debug` VALUES('query: \n			SELECT * FROM APPDEV_ChronProcess\n			WHERE \n			TO_DAYS(UpdateDate) < \n			TO_DAYS(CURDATE());\n			<br>', 'chronprocesshasrun()', '78', 'chron_lib.php', '2007-04-07 21:16:31', 61);
INSERT INTO `APPDEV_Debug` VALUES('numberOfRows: 1<br>', 'chronprocesshasrun()', '84', 'chron_lib.php', '2007-04-07 21:16:31', 62);
INSERT INTO `APPDEV_Debug` VALUES('CALENDAR has NOT already run...<br>', 'chronprocesshasrun()', '90', 'chron_lib.php', '2007-04-07 21:16:31', 63);
INSERT INTO `APPDEV_Debug` VALUES('Chron Process CALENDAR will run...', 'chronmaintaincalendarblogs()', '119', 'chron_lib.php', '2007-04-07 21:16:31', 64);
INSERT INTO `APPDEV_Debug` VALUES('Chron Process CALENDAR - numberOfRows: 1', 'chronmaintaincalendarblogs()', '135', 'chron_lib.php', '2007-04-07 21:16:31', 65);
INSERT INTO `APPDEV_Debug` VALUES('numDeleteQuery: SELECT * FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 2)<br>', 'chronmaintaincalendarblogs()', '157', 'chron_lib.php', '2007-04-07 21:16:31', 66);
INSERT INTO `APPDEV_Debug` VALUES('Chron Process CALENDAR - N rows will be deleted: 1<br>', 'chronmaintaincalendarblogs()', '164', 'chron_lib.php', '2007-04-07 21:16:31', 67);
INSERT INTO `APPDEV_Debug` VALUES('Chron Process CALENDAR - deleteQuery: DELETE FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 2)', 'chronmaintaincalendarblogs()', '173', 'chron_lib.php', '2007-04-07 21:16:31', 68);
INSERT INTO `APPDEV_Debug` VALUES('Chron Process CALENDAR - SUCCESS', 'chronmaintaincalendarblogs()', '192', 'chron_lib.php', '2007-04-07 21:16:31', 69);
INSERT INTO `APPDEV_Debug` VALUES('ChronUpdateProcess(CALENDAR)', 'chronupdateprocess()', '26', 'chron_lib.php', '2007-04-07 21:16:31', 70);
INSERT INTO `APPDEV_Debug` VALUES('rv: 1<br>', 'chronupdateprocess()', '46', 'chron_lib.php', '2007-04-07 21:16:31', 71);
INSERT INTO `APPDEV_Debug` VALUES('Chron Process CALENDAR - RETURNING: 1', 'chronmaintaincalendarblogs()', '202', 'chron_lib.php', '2007-04-07 21:16:31', 72);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_FileDelivery`
--

DROP TABLE IF EXISTS `APPDEV_FileDelivery`;
CREATE TABLE IF NOT EXISTS `APPDEV_FileDelivery` (
  `IpAddress` text NOT NULL,
  `Domain` text NOT NULL,
  `FileName` text NOT NULL,
  `TimeStamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `Index` int(12) NOT NULL auto_increment,
  PRIMARY KEY  (`Index`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This is data from file_delivery.php' AUTO_INCREMENT=27 ;

--
-- Dumping data for table `APPDEV_FileDelivery`
--


-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_FileLocations`
--

DROP TABLE IF EXISTS `APPDEV_FileLocations`;
CREATE TABLE IF NOT EXISTS `APPDEV_FileLocations` (
  `GUID` text NOT NULL COMMENT 'Unique ID',
  `Location` text NOT NULL COMMENT 'File name and path',
  `Index` int(8) NOT NULL auto_increment,
  KEY `Index` (`Index`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This matches GUIDS to file locs and names for the FTP delive' AUTO_INCREMENT=29 ;

--
-- Dumping data for table `APPDEV_FileLocations`
--


-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_GalleryProfiles`
--

DROP TABLE IF EXISTS `APPDEV_GalleryProfiles`;
CREATE TABLE IF NOT EXISTS `APPDEV_GalleryProfiles` (
  `FileName` text NOT NULL,
  `Title` text NOT NULL,
  `Statement` text NOT NULL,
  `Website` text NOT NULL,
  `ArtistName` text NOT NULL,
  `StudioName` text NOT NULL,
  `EMailAddress` text NOT NULL,
  `StreetAddress` text NOT NULL,
  `City` text NOT NULL,
  `State` text NOT NULL,
  `Zip` text NOT NULL,
  `Phone` text NOT NULL,
  `ArtistID` text NOT NULL,
  `Flag` enum('SHOW','HIDE') NOT NULL default 'SHOW' COMMENT 'This is set by the administrator to hide or show this gallery',
  `Type` enum('FORSALE','NFS') NOT NULL default 'FORSALE' COMMENT 'This will en-dis ecommerce options',
  `ThumbsPageName` varchar(128) NOT NULL default 'thumbs.php' COMMENT 'This is the thumbs page for this gallery',
  `ImagePageName` varchar(128) NOT NULL default 'image.php' COMMENT 'This is the image page for this gallery',
  `SortOrder` int(4) NOT NULL default '0' COMMENT 'This will allow the admin to reorder the galleries in the lobby display',
  `RecordNumber` int(4) NOT NULL auto_increment,
  KEY `RecordNumber` (`RecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This holds all of the gallery descriptions' AUTO_INCREMENT=47 ;

--
-- Dumping data for table `APPDEV_GalleryProfiles`
--

INSERT INTO `APPDEV_GalleryProfiles` VALUES('Cooksey-1104-055.jpg', 'Landscape photos of places I have been...', 'Ralph Cooksey-Talbott Thomas has been working as a photographer since 1972 when he moved to California from Michigan.<br><br>During the 1970’s he studied under Ansel Adams in Yosemite. Ansel published one of his photographs in the portfolio section of his book Polaroid Technique Manual. <br><br>Ansel and Orah Moore, another of Ansel’s students, suggested that he shorten his name to Cooksey-Talbott, and that is the name he has worked under since. <br><br>Cooksey also studied at the San Francisco Art Institute and the San Francisco Academy of Art. He has lectured in photography at the U.C. Berkeley Extension, Studio One in Oakland and at Santa Barbara City College. ', '', '', '', '', '', '', '', '', '', 'cookseytalbott', 'SHOW', 'FORSALE', 'thumbs.php', 'image.php', 0, 1);
INSERT INTO `APPDEV_GalleryProfiles` VALUES('Cataracts-0308-676.jpg', 'Weebers Woozie Waterfall', 'yeppers thats what this is all about!!!!', '', '', '', '', '', '', '', '', '', 'moderator', 'HIDE', 'NFS', 'thumbs.php', 'image.php', 0, 46);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_GALLERY_cookseytalbott`
--

DROP TABLE IF EXISTS `APPDEV_GALLERY_cookseytalbott`;
CREATE TABLE IF NOT EXISTS `APPDEV_GALLERY_cookseytalbott` (
  `FileName` text NOT NULL,
  `RecordNumber` int(4) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This is a gallery table template' AUTO_INCREMENT=15 ;

--
-- Dumping data for table `APPDEV_GALLERY_cookseytalbott`
--

INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('127-DomeInTheFog.jpg', 1);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('031-BlueMountainLake.jpg', 2);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('200-LongRidgeInSunsetB.jpg', 3);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('213-CloudsAndSunlightB.jpg', 4);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('568-Fern.jpg', 5);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('171-RockCreekSunset.jpg', 6);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('177-MonoLake.jpg', 7);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('Cataracts-0308-676.jpg', 8);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('108-WaterOnMossB2.jpg', 9);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('033-GlacierAndMountain.jpg', 10);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('012Hurricane Deck  SB.jpg', 11);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('012Hurricane Deck  SB.jpg', 12);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('286-TreeInMeadow.jpg', 13);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES('126-SantaBarbaraWaterfall.jpg', 14);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_GALLERY_moderator`
--

DROP TABLE IF EXISTS `APPDEV_GALLERY_moderator`;
CREATE TABLE IF NOT EXISTS `APPDEV_GALLERY_moderator` (
  `FileName` text NOT NULL,
  `RecordNumber` int(4) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This is a gallery table template' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `APPDEV_GALLERY_moderator`
--

INSERT INTO `APPDEV_GALLERY_moderator` VALUES('WelshCreek-0108-343-346.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_ImageDetails`
--

DROP TABLE IF EXISTS `APPDEV_ImageDetails`;
CREATE TABLE IF NOT EXISTS `APPDEV_ImageDetails` (
  `FileName` text NOT NULL,
  `DetailFileName` text NOT NULL,
  `ArtistID` text NOT NULL,
  `RecordNumber` int(4) NOT NULL auto_increment,
  KEY `RecordNumber` (`RecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='New in 2.1.8 - Supports detail pics in gallery' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `APPDEV_ImageDetails`
--


-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_ImageLibrary`
--

DROP TABLE IF EXISTS `APPDEV_ImageLibrary`;
CREATE TABLE IF NOT EXISTS `APPDEV_ImageLibrary` (
  `FileName` text COMMENT 'The name of the file',
  `Title` text COMMENT 'The title of the piece',
  `Location` text COMMENT 'This is the location of the picture',
  `Caption` text COMMENT 'The caption and description of the work',
  `Artist` text COMMENT 'The artists name',
  `ArtistID` varchar(128) NOT NULL default '' COMMENT 'This references all of this artists work in the library',
  `OwnerID` varchar(128) NOT NULL COMMENT 'The ID of the user who uploaded the picture',
  `Media` text COMMENT 'The type of media',
  `Orientation` enum('V','H') NOT NULL default 'V' COMMENT 'The orientation of the piece square is called H',
  `RecordNumber` int(4) unsigned NOT NULL auto_increment COMMENT 'the auto inc item number',
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=95 ;

--
-- Dumping data for table `APPDEV_ImageLibrary`
--

INSERT INTO `APPDEV_ImageLibrary` VALUES('108-WaterOnMossB2.jpg', 'Water on Moss', 'Ansel Adams Wilderness', 'How green is green ?<br><br>The saturated color of this print is just wonderful!<br><br>Crystalline water trickles over a bed of incredibly lush green moss at the side of a waterfall.<br><br>I found this near Shadow Lake in the Ansel Adams Wilderness Area.<br><br>This photograph was taken on Kodak VPS film with a 127mm lens using a Mamiya RB67 camera.', 'Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', 'Photograph', 'V', 14);
INSERT INTO `APPDEV_ImageLibrary` VALUES('033-GlacierAndMountain.jpg', 'Small Crevasse', 'Ansel Adams Wilderness', '<p>A small crevasse in a glacier...  This is up near Ediza...  It was nice there!!!</p><p>I will bet this glacier is not there any more.&nbsp;</p>', '', 'cookseytalbott', 'cookseytalbott', '', 'H', 6);
INSERT INTO `APPDEV_ImageLibrary` VALUES('031-BlueMountainLake.jpg', 'Blue Mountain Lake foo', 'Ansel Adams Wilderness', 'Somewhere above lake Ediza in the Ansel Adams Wilderness Area I found this dramatic rocky lake and meadow.<br><br>The contrast of the cool blue water with the verdant green meadow against the black rock mountain really moved me…<br><br>We sat here and had a nice lunch and then walked on.<br><br>This photograph was taken on Kodak VPS film with a 127mm lens using a Mamiya RB67 camera.', 'Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', 'Photograph', 'H', 15);
INSERT INTO `APPDEV_ImageLibrary` VALUES('126-SantaBarbaraWaterfall.jpg', 'Santa Barbara Waterfall', 'Montecito', 'A waterfall on the tunnel trail.', 'Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', 'Photograph', 'H', 10);
INSERT INTO `APPDEV_ImageLibrary` VALUES('286-TreeInMeadow.jpg', 'Yosemite Meadow', 'Yosemite Valley - California', ' Early morning in Yosemite Valley is a time when the landscape comes alive with the first light of day.<br><br>The acute angle of the light trans-illuminates the valley floor and makes the meadows come alive with color, form and texture.<br><br><br><br>I photographed this tree and then sat under it and enjoyed the first warmth of the morning sun.<br><br><br><br>The small 35mm film format creates a very painterly feeling.<br><br><br><br>This photograph was taken on Kodak VPS 160 film with a 50mm lens using a Pentax 35mm camera. ', 'Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', 'Photograph', 'V', 23);
INSERT INTO `APPDEV_ImageLibrary` VALUES('127-DomeInTheFog.jpg', 'Solitary Tree', 'Tuolumne High Country', 'High above Yosemite Valley is the Yosemite high country and Tuolumne, a land of exfoliating granite domes, fragrant meadows and high mountain lakes.Walking across the domes in the fog I came to this tree standing by itself surrounded by soft yellow grasses and lichen encrusted granite.Inclement weather and rapidly changing conditions are an integral part of the high country experience. In this case the atmospheric effect of the clouds served to separate this tree from its companions.This photograph was taken on Kodak 4x5 Super-XX with a 135mm Schneider lens using a Graphic View II camera.', 'Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', 'Photograph', 'V', 13);
INSERT INTO `APPDEV_ImageLibrary` VALUES('200-LongRidgeInSunsetB.jpg', 'Long Ridge in Sunset', 'Trinity Alps', 'Twilight is a magical time of day, I love it when the sky darkens and we pass the boundary from day into night.<br><br>In the mountains with out the ever present light contamination of the city the delicate tones of the night sky become clear.<br><br>This is a long exposure in the late twilight high in the Trinity Alps. Note the trails from the first of the evening stars caused by the earths rotation during the exposure.<br><br>This photograph was taken on Kodak 4x5 Kodak VPS with a 150mm Schneider lens using a Graphic View II camera.', 'Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', 'Photograph', 'H', 16);
INSERT INTO `APPDEV_ImageLibrary` VALUES('213-CloudsAndSunlightB.jpg', 'Clouds and Sunlight', 'Sword Lake - Sonora Pass', 'I ran out to Sword Lake near Dardanelles and sat on a large boulder to rest overlooking the lake.<br><br>When I leaned back on my pack I saw beautiful clouds radiant in the afternoons light. Myriad rays of sunlight streaming from the clouds edge. A sky so blue it was overwhelming.<br><br>I try to always remember to look up.<br><br>This photograph was taken on Kodak Portra VC 160 film with a 127mm lens using a Mamiya RB67 camera. ', 'Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', 'Photograph', 'H', 17);
INSERT INTO `APPDEV_ImageLibrary` VALUES('177-MonoLake.jpg', 'Big Sky', 'Mono Lake', 'At Mono Lake everything is expansive, the lake stretches for miles like a vast inland sea and the sky goes on forever.<br><br>The shoreline is dotted with very delicate tufa formations and the littoral regions of the lake are alive with myriad forms of insect life.<br><br>On this day the clouds were marching across the lake building up towards an afternoon thunder shower.<br><br>This photograph was taken on Kodak VPS 160 film with a 90mm lens using a Mamiya RB67 camera. ', 'Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', 'Photograph', 'H', 18);
INSERT INTO `APPDEV_ImageLibrary` VALUES('568-Fern.jpg', 'Fern', 'Lake Tahoe', 'The early season this year had abundant water and everything was very moist. The extra water was good for the ferns growing in a quiet deeply shaded glade beneath Horsetail Falls. In this image the wonderful complexity of the ferns edge and the verdant green color are caught in stark contrast against the dark loam in the shadows. This was in the Pyramid Creek watershed just outside of Lake Tahoe. This photograph was taken on Kodak VC 160 film with a 90mm lens using a Mamiya RB67 camera. ', 'Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', 'Photograph', 'V', 19);
INSERT INTO `APPDEV_ImageLibrary` VALUES('171-RockCreekSunset.jpg', 'Rock Creek Sunset', 'Toms Place - Little Lakes Valley - California', 'This was a quiet evening at Chickenfoot Lake...', 'Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', 'Photograph', 'H', 24);
INSERT INTO `APPDEV_ImageLibrary` VALUES('WelshCreek-0108-343-346.jpg', 'Welsh Creek Sunset', '', '<p>The end of a nice walk in the Sunol wilderness area.</p><p>We made it up to the top of McCorkle and it was windy...&nbsp;</p>', '', 'moderator', 'moderator', '', '', 94);
INSERT INTO `APPDEV_ImageLibrary` VALUES('Cataracts-0308-676.jpg', 'Cataracts Canyon Waterfalls', 'Fairfax - Marin - California', '<p>Cataracts canyon is a <em>really <strong>really</strong> steep</em> narrow canyon leading down to Alpine lake in Marin.</p><p>I have gone there a number of time and always find it to be glorious!</p><p>The moss is <strong>thick</strong> and green and the air is clean and has the freshness and negative ionization of the base of a waterfall.</p>', 'Ralph Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', 'Please Pick One...', '', 93);
INSERT INTO `APPDEV_ImageLibrary` VALUES('012Hurricane Deck  SB.jpg', 'The Deck Sunset', '', '', 'Ralph Cooksey-Talbott', 'cookseytalbott', 'cookseytalbott', '', '', 75);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_ImageSizes`
--

DROP TABLE IF EXISTS `APPDEV_ImageSizes`;
CREATE TABLE IF NOT EXISTS `APPDEV_ImageSizes` (
  `FileName` text NOT NULL COMMENT 'The name of the sample image file',
  `ArtistID` varchar(128) NOT NULL default '' COMMENT 'The artists system UID',
  `Size` text NOT NULL COMMENT 'The size of the piece',
  `Price` text NOT NULL COMMENT 'The price of the piece',
  `DescriptionOne` text NOT NULL COMMENT 'The description of that size',
  `DescriptionTwo` text NOT NULL COMMENT 'The type of edition',
  `RecordNumber` int(4) NOT NULL auto_increment COMMENT 'The auto recno',
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This holds the sizes for the GALLERY image library' AUTO_INCREMENT=98 ;

--
-- Dumping data for table `APPDEV_ImageSizes`
--

INSERT INTO `APPDEV_ImageSizes` VALUES('568-Fern.jpg', 'cookseytalbott', '24 x 31', '300.00', 'deep wrapped canvas print', 'Limited Edition', 36);
INSERT INTO `APPDEV_ImageSizes` VALUES('177-MonoLake.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 35);
INSERT INTO `APPDEV_ImageSizes` VALUES('213-CloudsAndSunlightB.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 38);
INSERT INTO `APPDEV_ImageSizes` VALUES('108-WaterOnMossB2.jpg', 'cookseytalbott', '24 x 31', '300.00', 'deep wrapped canvas print', 'Limited Edition', 67);
INSERT INTO `APPDEV_ImageSizes` VALUES('031-BlueMountainLake.jpg', 'cookseytalbott', '8 x 10', '35.00', 'print on fine art paper', 'Open Edition', 44);
INSERT INTO `APPDEV_ImageSizes` VALUES('031-BlueMountainLake.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 43);
INSERT INTO `APPDEV_ImageSizes` VALUES('200-LongRidgeInSunsetB.jpg', 'cookseytalbott', '31 x 24', '300.00', 'ready to hang deep gallery wrapped canvas print', 'Limited Edition', 31);
INSERT INTO `APPDEV_ImageSizes` VALUES('200-LongRidgeInSunsetB.jpg', 'cookseytalbott', '22 x 18', '175.00', 'print on fine art paper in 24 x 30 mat', 'Limited Edition', 32);
INSERT INTO `APPDEV_ImageSizes` VALUES('200-LongRidgeInSunsetB.jpg', 'cookseytalbott', '10 x 8', '35.00', 'raw print on fine art paper', 'Open Edition', 33);
INSERT INTO `APPDEV_ImageSizes` VALUES('033-GlacierAndMountain.jpg', 'cookseytalbott', '24 x 31', '300.00', 'ready to hang deep gallery wrapped canvas print', 'Limited Edition', 97);
INSERT INTO `APPDEV_ImageSizes` VALUES('568-Fern.jpg', 'cookseytalbott', '8 x 10', '35.00', 'print on fine art paper', 'Open Edition', 37);
INSERT INTO `APPDEV_ImageSizes` VALUES('171-RockCreekSunset.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 46);
INSERT INTO `APPDEV_ImageSizes` VALUES('286-TreeInMeadow.jpg', 'cookseytalbott', '24 x 31', '300.00', 'deep wrapped canvas print', 'Limited Edition', 45);
INSERT INTO `APPDEV_ImageSizes` VALUES('126-SantaBarbaraWaterfall.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 26);
INSERT INTO `APPDEV_ImageSizes` VALUES('126-SantaBarbaraWaterfall.jpg', 'cookseytalbott', '40 x 30', '600.00', 'deep wrapped canvas print', 'Limited Edition', 28);
INSERT INTO `APPDEV_ImageSizes` VALUES('126-SantaBarbaraWaterfall.jpg', 'cookseytalbott', '10 x 8', '35.00', 'print on fine art paper', 'Limited Edition', 27);
INSERT INTO `APPDEV_ImageSizes` VALUES('095-IronStainedRockFace.jpg', 'cookseytalbott', '', '100.00', 'Matted print', 'Original - One of One', 19);
INSERT INTO `APPDEV_ImageSizes` VALUES('127-DomeInTheFog.jpg', 'cookseytalbott', '24 x 31', '300.00', 'deep wrapped canvas print', 'Limited Edition', 20);
INSERT INTO `APPDEV_ImageSizes` VALUES('133-Granite-Head.jpg', 'cookseytalbott', '18 x 22', '175.00', 'print on fine art paper', 'Limited Edition', 47);
INSERT INTO `APPDEV_ImageSizes` VALUES('133-Granite-Head.jpg', 'cookseytalbott', '24 x 31', '300.00', 'deep wrapped canvas print', 'Limited Edition', 48);
INSERT INTO `APPDEV_ImageSizes` VALUES('285-RyanOnAGlacier.jpg', 'cookseytalbott', '24 x 30', '300.00', 'deep wrapped canvas print', 'Limited Edition', 50);
INSERT INTO `APPDEV_ImageSizes` VALUES('035-FracturedRockWall.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 51);
INSERT INTO `APPDEV_ImageSizes` VALUES('frame-h.jpg', 'cookseytalbott', '13 x 15', '100.00', 'fubar', 'Open Edition', 55);
INSERT INTO `APPDEV_ImageSizes` VALUES('fubar-VcZ5.txt', 'cookseytalbott', '', '110.00', 'DescriptionOneA', 'DescriptionTwoA', 68);
INSERT INTO `APPDEV_ImageSizes` VALUES('fubar-VcZ5.txt', 'cookseytalbott', '', '120.00', 'DescriptionOneB', 'DescriptionTwoB', 69);
INSERT INTO `APPDEV_ImageSizes` VALUES('fubar-VcZ5.txt', 'cookseytalbott', '', '130.00', 'DescriptionOneC', 'DescriptionTwoC', 70);
INSERT INTO `APPDEV_ImageSizes` VALUES('fubar-VcZ5.txt', 'cookseytalbott', '', '140.00', 'DescriptionOneD', 'DescriptionTwoD', 71);
INSERT INTO `APPDEV_ImageSizes` VALUES('favicon.ico', 'cookseytalbott', '', '110.00', 'DescriptionOneA', 'DescriptionTwoA', 72);
INSERT INTO `APPDEV_ImageSizes` VALUES('favicon.ico', 'cookseytalbott', '', '120.00', 'DescriptionOneB', 'DescriptionTwoB', 73);
INSERT INTO `APPDEV_ImageSizes` VALUES('favicon.ico', 'cookseytalbott', '', '130.00', 'DescriptionOneC', 'DescriptionTwoC', 74);
INSERT INTO `APPDEV_ImageSizes` VALUES('favicon.ico', 'cookseytalbott', '', '140.00', 'DescriptionOneD', 'DescriptionTwoD', 75);
INSERT INTO `APPDEV_ImageSizes` VALUES('012Hurricane Deck  SB.jpg', 'cookseytalbott', '', '10.00', 'Framed ready to hang print with fabric wrapped mat', 'FUBAR', 76);
INSERT INTO `APPDEV_ImageSizes` VALUES('Cataracts-0308-676.jpg', 'cookseytalbott', '1.5 x 48', '450.00', 'RTH - Laminated Float Mount - LE', 'Print on Archival Paper', 96);
INSERT INTO `APPDEV_ImageSizes` VALUES('1203-Donnell-Lake.jpg', 'cookseytalbott', '12x15', '10.00', 'Natural wood frame with double mat', 'Limited Edition', 89);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_LINKS_CalendarBlog`
--

DROP TABLE IF EXISTS `APPDEV_LINKS_CalendarBlog`;
CREATE TABLE IF NOT EXISTS `APPDEV_LINKS_CalendarBlog` (
  `blLinkURL` text COMMENT 'The URL of the link',
  `blName` text COMMENT 'The name of the web site or organization',
  `blDescription` text COMMENT 'The description of the site',
  `blPosterID` text NOT NULL COMMENT 'The ID of the poster',
  `blRecordNumber` int(4) NOT NULL auto_increment COMMENT 'This is the item number',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `APPDEV_LINKS_CalendarBlog`
--

INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES('epicurious.com', 'Goon Vittles', 'Hoot goon vittles!!!!', 'clark', 1);
INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES('wtf.com', 'The WTF Channel ?', '&lt;p&gt;Hooomba goomba!!! &lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;?&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;yeppers.&amp;nbsp;&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;p&gt;Its a goner.&amp;nbsp;&lt;/p&gt;', 'clark', 9);
INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES('yahoo.com', 'Yahooooo', 'A bunch of yobbo''s if I ever seed dem dere.', 'cookseyt', 4);
INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES('google.com', 'oogle', 'G', 'clark', 5);
INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES('wtf.com', 'WTF ?', '&lt;p&gt;A programmer whining site... ???&lt;/p&gt;&lt;p&gt;Really ? Programmers WHINE ???&amp;nbsp;&lt;/p&gt;', 'clark', 8);
INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES('www.oddtodd.com', 'Odd Todd', 'He is a funny guy!!!!!', 'clark', 7);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_MailDatabase`
--

DROP TABLE IF EXISTS `APPDEV_MailDatabase`;
CREATE TABLE IF NOT EXISTS `APPDEV_MailDatabase` (
  `title` text COMMENT 'Mr-Ms etc...',
  `first_name` text COMMENT 'First Name',
  `last_name` text COMMENT 'Last Name',
  `street_address` text COMMENT 'Street Address Line 1',
  `street_address_2` text COMMENT 'Street Address Line 2',
  `city` text COMMENT 'City',
  `state` text COMMENT '2 Letter State ID',
  `zip` text COMMENT 'Zip or Zip+4',
  `email_address` text COMMENT 'E-Mail Address',
  `password` varchar(100) NOT NULL default 'password' COMMENT 'Password',
  `subscribed1` enum('Y','N') NOT NULL default 'N' COMMENT 'List Subscription Flag 1',
  `subscribed2` enum('Y','N') NOT NULL default 'N' COMMENT 'List Subscription Flag 2',
  `subscribed3` enum('Y','N') NOT NULL default 'N' COMMENT 'List Subscription Flag 3',
  `ip_address` text COMMENT 'IP of Joiner Station',
  `host_name` text COMMENT 'Host Name of Joiner Station',
  `creation_date` text COMMENT 'Record Creation Time',
  `confirmed` enum('Y','N') NOT NULL default 'Y' COMMENT 'This Y when the user has confirmed their subscription',
  `mailing_flag` text COMMENT 'This is marked up when the message is sent',
  `item_number` int(11) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`item_number`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `APPDEV_MailDatabase`
--

INSERT INTO `APPDEV_MailDatabase` VALUES('', '', '', '', '', '', '--', '', 'cooksey@californiafriends.org', 'pacific18', 'Y', 'N', 'N', 'Added by Admin', 'Added by Admin', '', 'Y', 'MAIL_ID_R1204832035M47d047231f89c', 41);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_MailingListNames`
--

DROP TABLE IF EXISTS `APPDEV_MailingListNames`;
CREATE TABLE IF NOT EXISTS `APPDEV_MailingListNames` (
  `Name` text NOT NULL,
  `Description` text NOT NULL,
  `Checked` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `APPDEV_MailingListNames`
--

INSERT INTO `APPDEV_MailingListNames` VALUES('List 1', 'This is mailing list one...', 'Y');
INSERT INTO `APPDEV_MailingListNames` VALUES('List 2', 'This is mailing list two...', 'N');
INSERT INTO `APPDEV_MailingListNames` VALUES('List 3', 'This is mailing list three...', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_PasswordTargets`
--

DROP TABLE IF EXISTS `APPDEV_PasswordTargets`;
CREATE TABLE IF NOT EXISTS `APPDEV_PasswordTargets` (
  `pwUserID` text NOT NULL COMMENT 'UID for this target',
  `pwPassword` text NOT NULL COMMENT 'Passfor this target',
  `pwTargetGalleryName` text COMMENT 'if target is gallery this is filled if NULL use the page name as the target',
  `pwTargetPageName` text COMMENT 'this is blank if gallery target else goto this page'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `APPDEV_PasswordTargets`
--


-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_PicturePackages`
--

DROP TABLE IF EXISTS `APPDEV_PicturePackages`;
CREATE TABLE IF NOT EXISTS `APPDEV_PicturePackages` (
  `PackageID` text,
  `Description` text,
  `WholesalePrice` text,
  `RetailPrice` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `APPDEV_PicturePackages`
--

INSERT INTO `APPDEV_PicturePackages` VALUES('A', '1 - 5 x 7', '7.50', '15.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('B', '1 - 8 x 10', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('C', '1 - 11 x 14', '14.00', '28.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('D', '1 - 12 x 15', '16.00', '32.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('E', '1 - 16 x 20', '25.00', '50.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('F', '1 - 5 x 7<br>8 - 2 x 2.5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('G', '1 - 5 x 7<br>4 - 2.5 x 3.25''s<br>2 - 1.5 x 2''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('H', '1 - 5 x 7<br>4 - 2.5 x 3.5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('I', '4 - 4 x 5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('J', '2 - 4 x 5''s<br>2 - 2.5 x 3.5''s<br>4 - 2 x 2.5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('K', '2 - 4 x 5''s<br>8 - 2 x 2.5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('L', '2 - 4 x 5''s<br>4 - 2.5 x 3.5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('M', '20 - 2 x 1.5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('N', '16 - 2 x 2.5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('O', '8 - 2.5 x 3.5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('P', '4 - 2.5 x 3.5''s<br>8 - 2 x 2.5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('Q', '9 - 2.5 x 3.5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('R', '2 - 5 x 7''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('S', '1 - 5 x 7<br>2 -  2.5 x 3.5''s<br>4 - 2 x 2.5''s', '10.00', '20.00');
INSERT INTO `APPDEV_PicturePackages` VALUES('T', '1 - 5 x 7<br>2 - 3.5 x 5''s', '10.00', '20.00');

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_PublicVote`
--

DROP TABLE IF EXISTS `APPDEV_PublicVote`;
CREATE TABLE IF NOT EXISTS `APPDEV_PublicVote` (
  `Host` varchar(128) NOT NULL default '',
  `IP` varchar(128) NOT NULL default '',
  `TimeStamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `ElectionName` text NOT NULL,
  `FileName` text NOT NULL,
  `Score` int(4) NOT NULL default '0',
  `RecordNumber` int(8) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This is for 1 vote per IP-Host voting' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `APPDEV_PublicVote`
--

INSERT INTO `APPDEV_PublicVote` VALUES('c-98-207-186-154.hsd1.ca.comcast.net', '', '2008-04-05 18:10:23', 'General', 'Cataracts-0308-676.jpg', 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_PublicVoteElections`
--

DROP TABLE IF EXISTS `APPDEV_PublicVoteElections`;
CREATE TABLE IF NOT EXISTS `APPDEV_PublicVoteElections` (
  `ElectionName` text NOT NULL,
  `Type` enum('VotePerPiece','VotePerIP') NOT NULL default 'VotePerPiece',
  `RecordNumber` int(4) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This is the election header info' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `APPDEV_PublicVoteElections`
--

INSERT INTO `APPDEV_PublicVoteElections` VALUES('General', 'VotePerPiece', 4);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_PublicVoteTabulation`
--

DROP TABLE IF EXISTS `APPDEV_PublicVoteTabulation`;
CREATE TABLE IF NOT EXISTS `APPDEV_PublicVoteTabulation` (
  `ElectionName` text NOT NULL,
  `FileName` text NOT NULL,
  `Count` int(8) NOT NULL default '0',
  `Score` int(8) NOT NULL default '0',
  `RecordNumber` int(8) NOT NULL auto_increment,
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This has a tabulation of the public votes' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `APPDEV_PublicVoteTabulation`
--

INSERT INTO `APPDEV_PublicVoteTabulation` VALUES('General', 'Cataracts-0308-676.jpg', 1, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_SimpleCounters`
--

DROP TABLE IF EXISTS `APPDEV_SimpleCounters`;
CREATE TABLE IF NOT EXISTS `APPDEV_SimpleCounters` (
  `PageFileName` varchar(32) NOT NULL default '',
  `Count` int(4) NOT NULL default '0',
  `PriorCount` int(4) NOT NULL default '0' COMMENT 'This has the prior reading stored in it',
  PRIMARY KEY  (`PageFileName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='This table supports simple page counters';

--
-- Dumping data for table `APPDEV_SimpleCounters`
--

INSERT INTO `APPDEV_SimpleCounters` VALUES('Home', 18, 0);
INSERT INTO `APPDEV_SimpleCounters` VALUES('LIFOBlog', 126, 0);
INSERT INTO `APPDEV_SimpleCounters` VALUES('Calendar', 34, 0);
INSERT INTO `APPDEV_SimpleCounters` VALUES('About', 1, 0);
INSERT INTO `APPDEV_SimpleCounters` VALUES('GalleryLobby', 3, 0);
INSERT INTO `APPDEV_SimpleCounters` VALUES('GalleryThumbs', 15, 0);
INSERT INTO `APPDEV_SimpleCounters` VALUES('Contact', 2, 0);
INSERT INTO `APPDEV_SimpleCounters` VALUES('GalleryImage', 23, 0);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_SystemRights`
--

DROP TABLE IF EXISTS `APPDEV_SystemRights`;
CREATE TABLE IF NOT EXISTS `APPDEV_SystemRights` (
  `pwRightsID` text NOT NULL,
  `pwRecordNumber` int(8) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`pwRecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `APPDEV_SystemRights`
--

INSERT INTO `APPDEV_SystemRights` VALUES('Blog-Gallery', 1);
INSERT INTO `APPDEV_SystemRights` VALUES('Administrator', 2);
INSERT INTO `APPDEV_SystemRights` VALUES('SuperUser', 3);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_TestMailingList`
--

DROP TABLE IF EXISTS `APPDEV_TestMailingList`;
CREATE TABLE IF NOT EXISTS `APPDEV_TestMailingList` (
  `title` text COMMENT 'Mr-Ms etc...',
  `first_name` text COMMENT 'First Name',
  `last_name` text COMMENT 'Last Name',
  `street_address` text COMMENT 'Street Address Line 1',
  `street_address_2` text COMMENT 'Street Address Line 2',
  `city` text COMMENT 'City',
  `state` text COMMENT '2 Letter State ID',
  `zip` text COMMENT 'Zip or Zip+4',
  `email_address` text COMMENT 'E-Mail Address',
  `password` varchar(100) NOT NULL default 'password' COMMENT 'Password',
  `subscribed1` enum('Y','N') NOT NULL default 'Y' COMMENT 'List Subscription Flag 1',
  `subscribed2` enum('Y','N') NOT NULL default 'Y' COMMENT 'List Subscription Flag 2',
  `subscribed3` enum('Y','N') NOT NULL default 'Y' COMMENT 'List Subscription Flag 3',
  `ip_address` text COMMENT 'IP of Joiner Station',
  `host_name` text COMMENT 'Host Name of Joiner Station',
  `creation_date` text COMMENT 'Record Creation Time',
  `confirmed` enum('Y','N') NOT NULL default 'Y' COMMENT 'This Y when the user has confirmed their subscription',
  `mailing_flag` text COMMENT 'This is marked up when the message is sent',
  `item_number` int(11) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`item_number`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `APPDEV_TestMailingList`
--

INSERT INTO `APPDEV_TestMailingList` VALUES('', '', '', '', '', '', '--', '', 'cooksey@cookseytalbottgallery.com', 'months11', 'Y', 'N', 'N', 'Added by Admin', 'Added by Admin', '', 'Y', 'New Record', 19);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_TopDownloads`
--

DROP TABLE IF EXISTS `APPDEV_TopDownloads`;
CREATE TABLE IF NOT EXISTS `APPDEV_TopDownloads` (
  `Title` text NOT NULL,
  `Path` text NOT NULL,
  `FileName` text NOT NULL COMMENT 'The filename of the asset',
  `Type` text NOT NULL,
  `Score` int(8) NOT NULL default '0' COMMENT 'How many times the asset has been downloaded',
  `RecordNumber` int(4) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `APPDEV_TopDownloads`
--

INSERT INTO `APPDEV_TopDownloads` VALUES('test 3', '/shared', 'Static-PushIt-OneShot1.mp3', 'MP3', 3, 9);
INSERT INTO `APPDEV_TopDownloads` VALUES('Push it In - Static', '/RHSDev/shared', 'Static-PushItIn.mp3', 'MP3', 36, 8);
INSERT INTO `APPDEV_TopDownloads` VALUES('', '/RHSDev/shared', 'Static-StickItOldMan.mp3', 'MP3', 6, 7);
INSERT INTO `APPDEV_TopDownloads` VALUES('Test 4', 'shared', 'Static-PushIt-Stinger1.mp3', 'MP3', 1, 10);
INSERT INTO `APPDEV_TopDownloads` VALUES('t5', '/shared', 'Static-PushIt-OneShot2.mp3', 'MP3', 6, 11);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_UserInfo`
--

DROP TABLE IF EXISTS `APPDEV_UserInfo`;
CREATE TABLE IF NOT EXISTS `APPDEV_UserInfo` (
  `pwUserID` text NOT NULL,
  `pwPassword` text NOT NULL,
  `pwRightsID` enum('Administrator','SuperUser','Blog-Gallery') NOT NULL default 'Administrator' COMMENT 'This syncs with the rights table',
  `pwEMail` text NOT NULL,
  `pwFirstName` text NOT NULL,
  `pwLastName` text NOT NULL,
  `pwRecordNumber` int(8) unsigned NOT NULL auto_increment COMMENT 'I have added this in 2.1.x',
  PRIMARY KEY  (`pwRecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=124 ;

--
-- Dumping data for table `APPDEV_UserInfo`
--

INSERT INTO `APPDEV_UserInfo` VALUES('clark', 'foo', 'SuperUser', 'cooksey@cookseytalbottgallery.com', 'Clark', 'Kent', 4);
INSERT INTO `APPDEV_UserInfo` VALUES('cookseytalbott', 'snap', 'Blog-Gallery', 'cooksey@californiafriends.org', 'Ralph', 'Cooksey-Talbott', 6);
INSERT INTO `APPDEV_UserInfo` VALUES('webmaster', 'woo', 'Administrator', 'cooksey@cookseytalbottgallery.com', 'Ralph', 'Cooksey-Talbott', 7);
INSERT INTO `APPDEV_UserInfo` VALUES('guest', 'guest99', 'Administrator', 'cooksey@californiafriends.org', 'Guest', 'Account', 31);
INSERT INTO `APPDEV_UserInfo` VALUES('moderator', '6april', 'Blog-Gallery', 'cooksey@californiafriends.org', 'Joe', 'Moderator', 71);
INSERT INTO `APPDEV_UserInfo` VALUES('sitesecretary', '20pacific', 'Administrator', 'cooksey@californiafriends.org', 'site', 'secretary', 72);

-- --------------------------------------------------------

--
-- Table structure for table `APPDEV_UserPermissions`
--

DROP TABLE IF EXISTS `APPDEV_UserPermissions`;
CREATE TABLE IF NOT EXISTS `APPDEV_UserPermissions` (
  `pwUserID` text NOT NULL COMMENT 'The UserID from the UserInfo table',
  `pwPermission` text NOT NULL COMMENT 'The table or permission name',
  `pwModerator` text NOT NULL,
  `pwContributor` text NOT NULL,
  `pwRecordNumber` int(8) unsigned NOT NULL auto_increment COMMENT 'The record number',
  PRIMARY KEY  (`pwRecordNumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='This holds the users granular permissions' AUTO_INCREMENT=529 ;

--
-- Dumping data for table `APPDEV_UserPermissions`
--

INSERT INTO `APPDEV_UserPermissions` VALUES('moderator', 'APPDEV_GALLERY_moderator', '1', '0', 528);
INSERT INTO `APPDEV_UserPermissions` VALUES('moderator', 'APPDEV_BLOG_music', '1', '0', 526);
INSERT INTO `APPDEV_UserPermissions` VALUES('cookseytalbott', 'APPDEV_GALLERY_cookseytalbott', '1', '0', 527);
INSERT INTO `APPDEV_UserPermissions` VALUES('moderator', 'APPDEV_BLOG_SEO_Text', '1', '0', 423);
INSERT INTO `APPDEV_UserPermissions` VALUES('moderator', 'APPDEV_GALLERY_cookseytalbott', '1', '0', 424);
INSERT INTO `APPDEV_UserPermissions` VALUES('moderator', 'APPDEV_BLOG_AdOne', '1', '0', 422);
INSERT INTO `APPDEV_UserPermissions` VALUES('moderator', 'APPDEV_BLOG_CalendarBlog', '1', '0', 420);
INSERT INTO `APPDEV_UserPermissions` VALUES('moderator', 'APPDEV_BLOG_LifoBlog', '1', '0', 421);
