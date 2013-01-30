-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: fatcow
-- Generation Time: Oct 15, 2007 at 09:45 PM
-- Server version: 5.0.45
-- PHP Version: 4.4.4
-- 
-- Database: `cookse`
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


-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_BLOG_`
-- 

DROP TABLE IF EXISTS `APPDEV_BLOG_`;
CREATE TABLE IF NOT EXISTS `APPDEV_BLOG_` (
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
  `blSortType` enum('RANDOM','FIXED') NOT NULL default 'RANDOM' COMMENT 'This is for Ads it holds the sort type - if fixed refer to blSortOrder',
  `blSortOrder` int(2) NOT NULL default '0' COMMENT 'If SortType is fixed this field has ascending order numbers',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `APPDEV_BLOG_`
-- 


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
  `blEventDate` date default NULL COMMENT 'This is the event date if BlogType is CALENDAR',
  `blEventTime` text COMMENT 'This has a text string of the event time range',
  `blItemType` enum('TEXT','HTML') NOT NULL default 'TEXT' COMMENT 'Indicates if the item is text or HTML text has the cr stripped',
  `blVisibility` enum('SHOW','HIDE') NOT NULL default 'SHOW' COMMENT 'This is for moderated blogs',
  `blRecordNumber` int(4) NOT NULL auto_increment COMMENT 'Auto inc record ID',
  `blSortType` enum('RANDOM','FIXED') NOT NULL default 'RANDOM' COMMENT 'This is for Ads it holds the sort type - if fixed refer to blSortOrder',
  `blSortOrder` int(2) NOT NULL default '0' COMMENT 'If SortType is fixed this field has ascending order numbers',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- 
-- Dumping data for table `APPDEV_BLOG_AdOne`
-- 

INSERT INTO `APPDEV_BLOG_AdOne` VALUES ('Random #3', 'Random #3', 'It can contain HTML text and pictures...', 'clark', '2007-03-25 20:25:05', '0000-00-00', '', 'TEXT', 'SHOW', 20, 'RANDOM', 19);
INSERT INTO `APPDEV_BLOG_AdOne` VALUES ('Random #2', 'Random #2', 'It can contain HTML text and pictures...', 'clark', '2007-03-25 20:25:05', '0000-00-00', '', 'TEXT', 'SHOW', 3, 'RANDOM', 13);
INSERT INTO `APPDEV_BLOG_AdOne` VALUES ('Random #1', 'Random #1', 'It can contain HTML text and pictures...', 'clark', '2007-03-25 20:25:05', '0000-00-00', '', 'TEXT', 'SHOW', 5, 'RANDOM', 31);
INSERT INTO `APPDEV_BLOG_AdOne` VALUES ('Fixed #1', 'Fixed #1', 'It can contain HTML text and pictures...', 'clark', '2007-03-28 15:17:23', NULL, NULL, 'TEXT', 'SHOW', 6, 'FIXED', 28);
INSERT INTO `APPDEV_BLOG_AdOne` VALUES ('Fixed #2', 'Fixed #2', 'It can contain HTML text and pictures...', 'clark', '2007-03-28 15:17:23', NULL, NULL, 'TEXT', 'SHOW', 7, 'FIXED', 29);
INSERT INTO `APPDEV_BLOG_AdOne` VALUES ('Graphic Ad', '', '<a target="_blank" href="http://cookseytalbottstudio.com/">\r\n						<img border="0" src="http://www.cookseytalbottgallery.com/Testing/AppDev/images/ads/DisplayAdPlaceholder4.jpg"></a>', 'clark', '2007-03-28 21:19:14', '0000-00-00', '', 'HTML', 'SHOW', 19, 'FIXED', 27);

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
  `blEventDate` date default NULL COMMENT 'This is the event date if BlogType is CALENDAR',
  `blEventTime` text COMMENT 'This has a text string of the event time range',
  `blItemType` enum('TEXT','HTML') NOT NULL default 'TEXT' COMMENT 'Indicates if the item is text or HTML text has the cr stripped',
  `blVisibility` enum('SHOW','HIDE') NOT NULL default 'SHOW' COMMENT 'This is for moderated blogs',
  `blRecordNumber` int(4) NOT NULL auto_increment COMMENT 'Auto inc record ID',
  `blSortType` enum('RANDOM','FIXED') NOT NULL default 'RANDOM' COMMENT 'This is for Ads it holds the sort type - if fixed refer to blSortOrder',
  `blSortOrder` int(2) NOT NULL default '0' COMMENT 'If SortType is fixed this field has ascending order numbers',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

-- 
-- Dumping data for table `APPDEV_BLOG_CalendarBlog`
-- 

INSERT INTO `APPDEV_BLOG_CalendarBlog` VALUES ('The entries in this section are in calendar order...', 'They will disappear a set number of days after the event has expired...', 'They can contain HTML or plain text postings...<br><br>Preformatted test or HTML postings can be uploaded from your local computer using the Posting in File feature.<br>', 'testerc1', '2007-01-21 22:13:47', '2007-09-21', '7:00 PM', 'TEXT', 'SHOW', 14, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_CalendarBlog` VALUES ('My wonderful birthday!!!!', 'Its another year...', 'Did I get any wiser ?', 'testerc2', '2007-01-21 17:33:46', '2007-10-25', 'All Day', 'TEXT', 'SHOW', 11, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_CalendarBlog` VALUES ('The day before my birthday...', 'This post should come before...', 'La la la~', 'testerc2', '2007-01-21 17:41:07', '2007-10-24', '1-6 PM', 'TEXT', 'SHOW', 12, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_CalendarBlog` VALUES ('Day after de brfdays', 'This is the last post...', 'The last in the scroll that is...', 'testerc2', '2007-01-21 17:42:17', '2007-10-26', 'Not too early!!!!', 'TEXT', 'SHOW', 13, 'RANDOM', 0);

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
  `blEventDate` date default NULL COMMENT 'This is the event date if BlogType is CALENDAR',
  `blEventTime` text COMMENT 'This has a text string of the event time range',
  `blItemType` enum('TEXT','HTML') NOT NULL default 'TEXT' COMMENT 'Indicates if the item is text or HTML text has the cr stripped',
  `blVisibility` enum('SHOW','HIDE') NOT NULL default 'SHOW' COMMENT 'This is for moderated blogs',
  `blRecordNumber` int(4) NOT NULL auto_increment COMMENT 'Auto inc record ID',
  `blSortType` enum('RANDOM','FIXED') NOT NULL default 'RANDOM' COMMENT 'This is for Ads it holds the sort type - if fixed refer to blSortOrder',
  `blSortOrder` int(2) NOT NULL default '0' COMMENT 'If SortType is fixed this field has ascending order numbers',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- 
-- Dumping data for table `APPDEV_BLOG_LifoBlog`
-- 

INSERT INTO `APPDEV_BLOG_LifoBlog` VALUES ('I am Clark...', 'I am the super user...', 'I can change anything...', 'clark', '2007-01-21 16:29:43', '0000-00-00', '', 'TEXT', 'SHOW', 1, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_LifoBlog` VALUES ('I am tester 2', 'I can only post and edit my entries...', 'This is how we did it when I was a kid and WE LIKED IT!', 'testerl2', '2007-01-21 16:30:48', '0000-00-00', '', 'TEXT', 'SHOW', 2, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_LifoBlog` VALUES ('I am tester 1', 'Really the other guy is lying...', 'Im telling clark!!!!!', 'testerl1', '2007-01-21 16:32:48', '0000-00-00', '', 'TEXT', 'SHOW', 3, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_LifoBlog` VALUES ('Odd How things work out', 'What Happened to US intelligence (non-governmental question)', 'So how does it work that our (US) actors guild and entertainment producers create and support shows that depict women as mother figures for all the men who never grow up or even have a discerning brain? And these are comedies?  I thought these where sit coms, but theyre actually socio-anthropological statements about our culture.\r\n\r\nA case in point is the recent DVD release of You, Me, and Dupree. It isnt really a bad movie.  What rankles the sensibilities is the premise . . .\r\n\r\nmore blog to follow . . .\r\n\r\nNice spell checker!!!\r\n\r\nEasy to type.\r\n\r\nUsing Firefox 2.0.0.1, XP Home, and no brain what so ever!~\r\n\r\nSo If I continue this from IE 7.0 it looks - exactly the same!! Nice.  So far so good . . .\r\n\r\nYou might consider putting a log out link a little closer to this editor than away out at the beginning.  Dont know if you can do that . . .', 'mikeblog', '2007-01-22 00:01:21', '0000-00-00', '', 'HTML', 'SHOW', 4, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_LifoBlog` VALUES ('But thats not all', 'Theres more', '. . .  from blog 1 . . .<br><br>premise that an architect could complete 5 or 6 years of college and not be smart enough to know that a man who barges into his nuptual bedroom in order to dump a stinky one (talking the audience through it) shouldnt be removed from the house at once - and who is not your friend.<br><br>This wild character has no redeeming features, physical, spiritual, emotional . . .<br><br>but hdkow thre astoij - - -  In Navigator 7.2 I dont have a spell checker anymore. . . dayng<br><br>Everything else looks great!', 'mikeblog', '2007-01-22 00:11:20', '0000-00-00', '', 'TEXT', 'SHOW', 5, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_LifoBlog` VALUES ('But with Opera 9.02', '', 'Well <br><br>Alls well in plain text - sdfk  eoslt  - I dont spell check with Opera either - must be a browser thing.<br><br>Ill try the same with HTML tomorrow - - looks great Cooksey!!<br><br>Nice wk.<br><br>Mike', 'mikeblog', '2007-01-22 00:13:56', '0000-00-00', '', 'TEXT', 'SHOW', 6, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_LifoBlog` VALUES ('Front to back test', 'At the Goat Hall In SF', 'fubarb rubarb rubabrb pie.', 'cookseyt', '2007-03-03 18:14:03', '0000-00-00', '', 'TEXT', 'SHOW', 7, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_LifoBlog` VALUES ('All those people dancing...', 'And everyone is still havin a good time!', 'This is a test of the file share - download posting...\r\n<br>\r\n<br>\r\nMono<br>\r\n<br>\r\n<br>\r\nLake<br>\r\n<br>\r\n<br>\r\nBIGSKY!!!!<br>\r\n<br><br><a TARGET="_blank" href="/Testing/AppDev/shared/177-MonoLake.jpg">Click here to Download 177-MonoLake.jpg</a><br><br>', 'clark', '2007-03-25 18:04:09', '0000-00-00', '', 'HTML', 'SHOW', 12, 'RANDOM', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_BLOG_MusicTest`
-- 

DROP TABLE IF EXISTS `APPDEV_BLOG_MusicTest`;
CREATE TABLE IF NOT EXISTS `APPDEV_BLOG_MusicTest` (
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
  `blSortType` enum('RANDOM','FIXED') NOT NULL default 'RANDOM' COMMENT 'This is for Ads it holds the sort type - if fixed refer to blSortOrder',
  `blSortOrder` int(2) NOT NULL default '0' COMMENT 'If SortType is fixed this field has ascending order numbers',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `APPDEV_BLOG_MusicTest`
-- 

INSERT INTO `APPDEV_BLOG_MusicTest` VALUES ('Ms Incognito', 'Static', 'This was a long march... but it came out in the end...<br><br><a target="_blank" class="blogFileSharingLink" href="/Testing/AppDev/shared/MsIncognito-0305.mp3">Click here to Download the file - MsIncognito-0305.mp3</a><br><br>', 'clark', '2007-04-26 23:43:03', '0000-00-00', 'MsIncognito-0305.mp3', 'HTML', 'SHOW', 1, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_MusicTest` VALUES ('Trash Talkin', 'Fobbert and Goober', 'This is a fakorini!<br><br><a target="_blank" class="blogFileSharingLink" href="/Testing/AppDev/shared/fake.mp3">Click here to Download the file - fake.mp3</a><br><br>', 'clark', '2007-04-27 00:08:34', '0000-00-00', 'fake.mp3', 'HTML', 'SHOW', 2, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_MusicTest` VALUES ('Foobar Goobar', 'Fat Freddie and the GatorBators', 'This is a fako uploado<br><br><a target="_blank" class="blogFileSharingLink" href="/Testing/AppDev/shared/fake.mp3">Play\n							</a>\n							&nbsp;&nbsp;-&nbsp;&nbsp;<a target="_blank" class="blogFileSharingLink" href="../download_asset.php?dlFileName=fake.mp3&dlPath=/shared&dlTitle=Foobar Goobar - Fat Freddie and the GatorBators">Download\n							</a><br><br>', 'clark', '2007-04-27 21:29:13', '0000-00-00', 'fake.mp3', 'HTML', 'SHOW', 5, 'RANDOM', 0);
INSERT INTO `APPDEV_BLOG_MusicTest` VALUES ('Loose Bruce', 'Spruce Goose', 'The fako<br><br><a target="_blank" class="blogFileSharingLink" href="/Testing/AppDev/shared/fake.mp3">Click here to play the file Loose Bruce by Spruce Goose<br>or<br>right click to download...</a><br><br>', 'clark', '2007-04-27 00:17:30', '0000-00-00', 'fake.mp3', 'HTML', 'SHOW', 4, 'RANDOM', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_Blog`
-- 

DROP TABLE IF EXISTS `APPDEV_Blog`;
CREATE TABLE IF NOT EXISTS `APPDEV_Blog` (
  `Headline` text NOT NULL,
  `SubHead` text NOT NULL,
  `Date` text NOT NULL,
  `Copy` text NOT NULL,
  `ItemType` text NOT NULL,
  `ItemNumber` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`ItemNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `APPDEV_Blog`
-- 

INSERT INTO `APPDEV_Blog` VALUES ('The Head is the line', 'Subhead', 'Sunday, February 5, 2006, 23:30:19', 'FOO<B>BAR</B>', 'HTML', 4);
INSERT INTO `APPDEV_Blog` VALUES ('This is the second entry', 'Big number too!', 'Sunday, February 5, 2006, 23:16:25', 'fo fof o foo foo', 'HTML', 2);
INSERT INTO `APPDEV_Blog` VALUES ('Feeble freddie robs old ladies!', 'He took their purses!', 'Monday, February 6, 2006, 1:46:11', 'Wadda Cad!', 'HTML', 6);

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COMMENT='This contains header information for the V2 blogs' AUTO_INCREMENT=13 ;

-- 
-- Dumping data for table `APPDEV_BlogHeaders`
-- 

INSERT INTO `APPDEV_BlogHeaders` VALUES ('Calendar', 'This list will sort in date order with the most recent dates on the top of the list.\r\n<br><br>\r\n', 'APPDEV_BLOG_CalendarBlog', 'CALENDAR', 1);
INSERT INTO `APPDEV_BlogHeaders` VALUES ('LIFO', 'This blog will does not have the date and time info.\r\n<br><br>\r\nIt will sort in LIFO order. The most recent item will be displayed on top.', 'APPDEV_BLOG_LifoBlog', 'LIFO', 2);
INSERT INTO `APPDEV_BlogHeaders` VALUES ('Ads', 'This has advertising group 1 in it...', 'APPDEV_BLOG_AdOne', 'ORDERED', 8);
INSERT INTO `APPDEV_BlogHeaders` VALUES ('', '', 'APPDEV_BLOG_', '', 12);
INSERT INTO `APPDEV_BlogHeaders` VALUES ('Music Test Blog', 'Music Test', 'APPDEV_BLOG_MusicTest', 'MUSIC', 11);

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_BlogLinks`
-- 

DROP TABLE IF EXISTS `APPDEV_BlogLinks`;
CREATE TABLE IF NOT EXISTS `APPDEV_BlogLinks` (
  `LinkURL` text,
  `Name` text,
  `Description` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `APPDEV_BlogLinks`
-- 

INSERT INTO `APPDEV_BlogLinks` VALUES ('http://www.google.com/', 'Google Search Engine', 'The worlds biggest search engine...');
INSERT INTO `APPDEV_BlogLinks` VALUES ('http://www.cookseytalbottstudio.com', 'Cooksey Talbott Studio', 'My Makers Online Joint');

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_BlogPasswords`
-- 

DROP TABLE IF EXISTS `APPDEV_BlogPasswords`;
CREATE TABLE IF NOT EXISTS `APPDEV_BlogPasswords` (
  `TableName` text NOT NULL,
  `Password` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `APPDEV_BlogPasswords`
-- 

INSERT INTO `APPDEV_BlogPasswords` VALUES ('APPDEV_Blog', 'Password');

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

INSERT INTO `APPDEV_ChronProcess` VALUES ('CALENDAR', '2007-04-15 17:10:46', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=latin1 COMMENT='This is the table for debug_lib.php' AUTO_INCREMENT=73 ;

-- 
-- Dumping data for table `APPDEV_Debug`
-- 

INSERT INTO `APPDEV_Debug` VALUES ('ChronProcessHasRun(CALENDAR)', 'chronprocesshasrun()', '66', 'chron_lib.php', '2007-04-07 20:40:37', 1);
INSERT INTO `APPDEV_Debug` VALUES ('query: \n			SELECT * FROM APPDEV_ChronProcess\n			WHERE \n			TO_DAYS(UpdateDate) < \n			TO_DAYS(CURDATE());\n			<br>', 'chronprocesshasrun()', '78', 'chron_lib.php', '2007-04-07 20:40:37', 2);
INSERT INTO `APPDEV_Debug` VALUES ('numberOfRows: 1<br>', 'chronprocesshasrun()', '84', 'chron_lib.php', '2007-04-07 20:40:37', 3);
INSERT INTO `APPDEV_Debug` VALUES ('CALENDAR has NOT already run...<br>', 'chronprocesshasrun()', '90', 'chron_lib.php', '2007-04-07 20:40:37', 4);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR will run...', 'chronmaintaincalendarblogs()', '119', 'chron_lib.php', '2007-04-07 20:40:37', 5);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - numberOfRows: 1', 'chronmaintaincalendarblogs()', '139', 'chron_lib.php', '2007-04-07 20:40:37', 6);
INSERT INTO `APPDEV_Debug` VALUES ('numDeleteQuery: SELECT * FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 0)\n						<br>', 'chronmaintaincalendarblogs()', '156', 'chron_lib.php', '2007-04-07 20:40:37', 7);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - N rows will be deleted: <br>', 'chronmaintaincalendarblogs()', '162', 'chron_lib.php', '2007-04-07 20:40:37', 8);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - deleteQuery: DELETE FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 0)\n						', 'chronmaintaincalendarblogs()', '174', 'chron_lib.php', '2007-04-07 20:40:40', 9);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - RETURNING: ', 'chronmaintaincalendarblogs()', '193', 'chron_lib.php', '2007-04-07 20:40:40', 10);
INSERT INTO `APPDEV_Debug` VALUES ('ChronProcessHasRun(CALENDAR)', 'chronprocesshasrun()', '66', 'chron_lib.php', '2007-04-07 20:42:52', 11);
INSERT INTO `APPDEV_Debug` VALUES ('query: \n			SELECT * FROM APPDEV_ChronProcess\n			WHERE \n			TO_DAYS(UpdateDate) < \n			TO_DAYS(CURDATE());\n			<br>', 'chronprocesshasrun()', '78', 'chron_lib.php', '2007-04-07 20:42:52', 12);
INSERT INTO `APPDEV_Debug` VALUES ('numberOfRows: 1<br>', 'chronprocesshasrun()', '84', 'chron_lib.php', '2007-04-07 20:42:52', 13);
INSERT INTO `APPDEV_Debug` VALUES ('CALENDAR has NOT already run...<br>', 'chronprocesshasrun()', '90', 'chron_lib.php', '2007-04-07 20:42:52', 14);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR will run...', 'chronmaintaincalendarblogs()', '119', 'chron_lib.php', '2007-04-07 20:42:52', 15);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - numberOfRows: 1', 'chronmaintaincalendarblogs()', '139', 'chron_lib.php', '2007-04-07 20:42:52', 16);
INSERT INTO `APPDEV_Debug` VALUES ('numDeleteQuery: SELECT * FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 0)<br>', 'chronmaintaincalendarblogs()', '155', 'chron_lib.php', '2007-04-07 20:42:52', 17);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - N rows will be deleted: <br>', 'chronmaintaincalendarblogs()', '165', 'chron_lib.php', '2007-04-07 20:42:52', 18);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - deleteQuery: DELETE FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 0)', 'chronmaintaincalendarblogs()', '176', 'chron_lib.php', '2007-04-07 20:42:52', 19);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - RETURNING: ', 'chronmaintaincalendarblogs()', '195', 'chron_lib.php', '2007-04-07 20:42:52', 20);
INSERT INTO `APPDEV_Debug` VALUES ('ChronProcessHasRun(CALENDAR)', 'chronprocesshasrun()', '66', 'chron_lib.php', '2007-04-07 20:50:11', 21);
INSERT INTO `APPDEV_Debug` VALUES ('query: \n			SELECT * FROM APPDEV_ChronProcess\n			WHERE \n			TO_DAYS(UpdateDate) < \n			TO_DAYS(CURDATE());\n			<br>', 'chronprocesshasrun()', '78', 'chron_lib.php', '2007-04-07 20:50:11', 22);
INSERT INTO `APPDEV_Debug` VALUES ('numberOfRows: 1<br>', 'chronprocesshasrun()', '84', 'chron_lib.php', '2007-04-07 20:50:11', 23);
INSERT INTO `APPDEV_Debug` VALUES ('CALENDAR has NOT already run...<br>', 'chronprocesshasrun()', '90', 'chron_lib.php', '2007-04-07 20:50:11', 24);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR will run...', 'chronmaintaincalendarblogs()', '119', 'chron_lib.php', '2007-04-07 20:50:11', 25);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - numberOfRows: 1', 'chronmaintaincalendarblogs()', '139', 'chron_lib.php', '2007-04-07 20:50:11', 26);
INSERT INTO `APPDEV_Debug` VALUES ('numDeleteQuery: SELECT * FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 0)<br>', 'chronmaintaincalendarblogs()', '155', 'chron_lib.php', '2007-04-07 20:50:11', 27);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - Number to Delete Query FAIL', 'chronmaintaincalendarblogs()', '163', 'chron_lib.php', '2007-04-07 20:50:11', 28);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - RETURNING: ', 'chronmaintaincalendarblogs()', '205', 'chron_lib.php', '2007-04-07 20:50:11', 29);
INSERT INTO `APPDEV_Debug` VALUES ('ChronProcessHasRun(CALENDAR)', 'chronprocesshasrun()', '66', 'chron_lib.php', '2007-04-07 20:53:28', 30);
INSERT INTO `APPDEV_Debug` VALUES ('query: \n			SELECT * FROM APPDEV_ChronProcess\n			WHERE \n			TO_DAYS(UpdateDate) < \n			TO_DAYS(CURDATE());\n			<br>', 'chronprocesshasrun()', '78', 'chron_lib.php', '2007-04-07 20:53:28', 31);
INSERT INTO `APPDEV_Debug` VALUES ('numberOfRows: 1<br>', 'chronprocesshasrun()', '84', 'chron_lib.php', '2007-04-07 20:53:28', 32);
INSERT INTO `APPDEV_Debug` VALUES ('CALENDAR has NOT already run...<br>', 'chronprocesshasrun()', '90', 'chron_lib.php', '2007-04-07 20:53:28', 33);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR will run...', 'chronmaintaincalendarblogs()', '119', 'chron_lib.php', '2007-04-07 20:53:28', 34);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - numberOfRows: 1', 'chronmaintaincalendarblogs()', '139', 'chron_lib.php', '2007-04-07 20:53:28', 35);
INSERT INTO `APPDEV_Debug` VALUES ('numDeleteQuery: SELECT * FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 0)<br>', 'chronmaintaincalendarblogs()', '155', 'chron_lib.php', '2007-04-07 20:53:28', 36);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - Number to Delete Query FAIL', 'chronmaintaincalendarblogs()', '163', 'chron_lib.php', '2007-04-07 20:53:28', 37);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - RETURNING: ', 'chronmaintaincalendarblogs()', '205', 'chron_lib.php', '2007-04-07 20:53:28', 38);
INSERT INTO `APPDEV_Debug` VALUES ('ChronProcessHasRun(CALENDAR)', 'chronprocesshasrun()', '66', 'chron_lib.php', '2007-04-07 21:06:03', 39);
INSERT INTO `APPDEV_Debug` VALUES ('query: \n			SELECT * FROM APPDEV_ChronProcess\n			WHERE \n			TO_DAYS(UpdateDate) < \n			TO_DAYS(CURDATE());\n			<br>', 'chronprocesshasrun()', '78', 'chron_lib.php', '2007-04-07 21:06:03', 40);
INSERT INTO `APPDEV_Debug` VALUES ('numberOfRows: 1<br>', 'chronprocesshasrun()', '84', 'chron_lib.php', '2007-04-07 21:06:04', 41);
INSERT INTO `APPDEV_Debug` VALUES ('CALENDAR has NOT already run...<br>', 'chronprocesshasrun()', '90', 'chron_lib.php', '2007-04-07 21:06:04', 42);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR will run...', 'chronmaintaincalendarblogs()', '119', 'chron_lib.php', '2007-04-07 21:06:04', 43);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - numberOfRows: 1', 'chronmaintaincalendarblogs()', '135', 'chron_lib.php', '2007-04-07 21:06:04', 44);
INSERT INTO `APPDEV_Debug` VALUES ('numDeleteQuery: SELECT * FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 0)<br>', 'chronmaintaincalendarblogs()', '157', 'chron_lib.php', '2007-04-07 21:06:04', 45);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - N rows will be deleted: 1<br>', 'chronmaintaincalendarblogs()', '164', 'chron_lib.php', '2007-04-07 21:06:04', 46);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - deleteQuery: DELETE FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 0)', 'chronmaintaincalendarblogs()', '173', 'chron_lib.php', '2007-04-07 21:06:04', 47);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - SUCCESS', 'chronmaintaincalendarblogs()', '192', 'chron_lib.php', '2007-04-07 21:06:04', 48);
INSERT INTO `APPDEV_Debug` VALUES ('ChronUpdateProcess(CALENDAR)', 'chronupdateprocess()', '26', 'chron_lib.php', '2007-04-07 21:06:04', 49);
INSERT INTO `APPDEV_Debug` VALUES ('rv: 1<br>', 'chronupdateprocess()', '46', 'chron_lib.php', '2007-04-07 21:06:04', 50);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - RETURNING: 1', 'chronmaintaincalendarblogs()', '202', 'chron_lib.php', '2007-04-07 21:06:04', 51);
INSERT INTO `APPDEV_Debug` VALUES ('ChronProcessHasRun(CALENDAR)', 'chronprocesshasrun()', '66', 'chron_lib.php', '2007-04-07 21:13:36', 52);
INSERT INTO `APPDEV_Debug` VALUES ('query: \n			SELECT * FROM APPDEV_ChronProcess\n			WHERE \n			TO_DAYS(UpdateDate) < \n			TO_DAYS(CURDATE());\n			<br>', 'chronprocesshasrun()', '78', 'chron_lib.php', '2007-04-07 21:13:36', 53);
INSERT INTO `APPDEV_Debug` VALUES ('numberOfRows: 0<br>', 'chronprocesshasrun()', '84', 'chron_lib.php', '2007-04-07 21:13:36', 54);
INSERT INTO `APPDEV_Debug` VALUES ('CALENDAR has already run...<br>', 'chronprocesshasrun()', '98', 'chron_lib.php', '2007-04-07 21:13:36', 55);
INSERT INTO `APPDEV_Debug` VALUES ('ChronProcessHasRun(CALENDAR)', 'chronprocesshasrun()', '66', 'chron_lib.php', '2007-04-07 21:15:21', 56);
INSERT INTO `APPDEV_Debug` VALUES ('query: \n			SELECT * FROM APPDEV_ChronProcess\n			WHERE \n			TO_DAYS(UpdateDate) < \n			TO_DAYS(CURDATE());\n			<br>', 'chronprocesshasrun()', '78', 'chron_lib.php', '2007-04-07 21:15:21', 57);
INSERT INTO `APPDEV_Debug` VALUES ('numberOfRows: 0<br>', 'chronprocesshasrun()', '84', 'chron_lib.php', '2007-04-07 21:15:21', 58);
INSERT INTO `APPDEV_Debug` VALUES ('CALENDAR has already run...<br>', 'chronprocesshasrun()', '98', 'chron_lib.php', '2007-04-07 21:15:21', 59);
INSERT INTO `APPDEV_Debug` VALUES ('ChronProcessHasRun(CALENDAR)', 'chronprocesshasrun()', '66', 'chron_lib.php', '2007-04-07 21:16:31', 60);
INSERT INTO `APPDEV_Debug` VALUES ('query: \n			SELECT * FROM APPDEV_ChronProcess\n			WHERE \n			TO_DAYS(UpdateDate) < \n			TO_DAYS(CURDATE());\n			<br>', 'chronprocesshasrun()', '78', 'chron_lib.php', '2007-04-07 21:16:31', 61);
INSERT INTO `APPDEV_Debug` VALUES ('numberOfRows: 1<br>', 'chronprocesshasrun()', '84', 'chron_lib.php', '2007-04-07 21:16:31', 62);
INSERT INTO `APPDEV_Debug` VALUES ('CALENDAR has NOT already run...<br>', 'chronprocesshasrun()', '90', 'chron_lib.php', '2007-04-07 21:16:31', 63);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR will run...', 'chronmaintaincalendarblogs()', '119', 'chron_lib.php', '2007-04-07 21:16:31', 64);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - numberOfRows: 1', 'chronmaintaincalendarblogs()', '135', 'chron_lib.php', '2007-04-07 21:16:31', 65);
INSERT INTO `APPDEV_Debug` VALUES ('numDeleteQuery: SELECT * FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 2)<br>', 'chronmaintaincalendarblogs()', '157', 'chron_lib.php', '2007-04-07 21:16:31', 66);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - N rows will be deleted: 1<br>', 'chronmaintaincalendarblogs()', '164', 'chron_lib.php', '2007-04-07 21:16:31', 67);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - deleteQuery: DELETE FROM APPDEV_BLOG_CalendarBlog \n						WHERE \n						TO_DAYS(blEventDate) < \n						(TO_DAYS(CURDATE()) - 2)', 'chronmaintaincalendarblogs()', '173', 'chron_lib.php', '2007-04-07 21:16:31', 68);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - SUCCESS', 'chronmaintaincalendarblogs()', '192', 'chron_lib.php', '2007-04-07 21:16:31', 69);
INSERT INTO `APPDEV_Debug` VALUES ('ChronUpdateProcess(CALENDAR)', 'chronupdateprocess()', '26', 'chron_lib.php', '2007-04-07 21:16:31', 70);
INSERT INTO `APPDEV_Debug` VALUES ('rv: 1<br>', 'chronupdateprocess()', '46', 'chron_lib.php', '2007-04-07 21:16:31', 71);
INSERT INTO `APPDEV_Debug` VALUES ('Chron Process CALENDAR - RETURNING: 1', 'chronmaintaincalendarblogs()', '202', 'chron_lib.php', '2007-04-07 21:16:31', 72);

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
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 COMMENT='This is data from file_delivery.php' AUTO_INCREMENT=16 ;

-- 
-- Dumping data for table `APPDEV_FileDelivery`
-- 

INSERT INTO `APPDEV_FileDelivery` VALUES ('$ipAddress', '$hostName', '$fileName', '2006-10-04 00:41:08', 2);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'test.txt', '2006-10-04 00:51:06', 3);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'test.txt', '2006-10-04 00:55:13', 4);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'test.txt', '2006-10-04 00:57:32', 5);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'test.txt', '2006-10-04 00:58:03', 6);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'test.txt', '2006-10-04 00:58:45', 7);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'test.txt', '2006-10-04 01:00:21', 8);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', '', '2006-10-06 00:29:35', 9);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'test.txt', '2006-10-06 00:33:44', 10);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'Smoop.txt', '2006-10-06 00:55:17', 11);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'Smoop.txt', '2006-10-06 13:02:20', 12);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'test.txt', '2006-10-06 23:36:32', 13);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'admin.css', '2007-01-19 17:31:01', 14);
INSERT INTO `APPDEV_FileDelivery` VALUES ('24.6.197.160', 'c-24-6-197-160.hsd1.ca.comcast.net', 'test.txt', '2007-01-19 17:39:49', 15);

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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1 COMMENT='This matches GUIDS to file locs and names for the FTP delive' AUTO_INCREMENT=24 ;

-- 
-- Dumping data for table `APPDEV_FileLocations`
-- 

INSERT INTO `APPDEV_FileLocations` VALUES ('CTSP1160108146CTS4525d8724af8b', '/home/users/web/b2019/moo.cookse/Deliveries/Uploads/test.txt', 1);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSL1160108271CTS4525d8ef42511', '/home/users/web/b2019/moo.cookse/Deliveries/Uploads/test.txt', 2);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSX1160108565CTS4525da154a151', '/home/users/web/b2019/moo.cookse/Deliveries/Uploads/test.txt', 3);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSZ1160108689CTS4525da91f1247', 'http://www.cookseytalbottgallery.com/Deliveries/Uploads//test.txt', 4);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSS1160108719CTS4525daafec1c7', 'http://www.cookseytalbottgallery.com/Deliveries/Uploads/test.txt', 5);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSM1160108740CTS4525dac46f8fe', 'http://www.cookseytalbottgallery.com/Deliveries/Uploads/test.txt', 6);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSV1160108780CTS4525daec9d1fd', 'http://www.cookseytalbottgallery.com/Deliveries/Uploads/test.txt', 7);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSG1160108952CTS4525db98c3bdd', 'http://www.cookseytalbottgallery.com/Deliveries/Uploads/test.txt', 8);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSW1160109600CTS4525de20a0a90', 'http://www.cookseytalbottgallery.com/Deliveries/Uploads/Smoop.txt', 9);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSR1160109720CTS4525de98bb3ee', 'http://www.cookseytalbottgallery.com/Deliveries/Uploads/Smoop.txt', 10);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSW1160191590CTS45271e6694ea4', 'http://www.cookseytalbottgallery.com/Deliveries/Uploads/test.txt', 11);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSF1160192008CTS452720082a556', 'http://www.cookseytalbottgallery.com/Deliveries/Uploads/test.txt', 12);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTST1160192090CTS4527205b01103', 'http://www.cookseytalbottgallery.com/Deliveries/Uploads/test.txt', 13);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSA1167974612C459de0d4cb34b', 'http://www.cookseytalbottgallery.com/Testing/AppDev/Deliveries/Uploads/012-Hurricane Deck - SB.jpg', 14);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSV1167974778C459de17ad2856', 'http://www.cookseytalbottgallery.com/Testing/AppDev/Deliveries/Uploads/012-Hurricane Deck - SB.jpg', 15);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSO1167974809C459de1999bb9f', 'http://www.cookseytalbottgallery.com/Testing/AppDev/Deliveries/Uploads/012-Hurricane Deck - SB.jpg', 16);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSE1167975191C459de31795505', 'http://www.cookseytalbottgallery.com/Testing/AppDev/Deliveries/Uploads/012-Hurricane Deck - SB.jpg', 17);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSC1167975713C459de5210be1a', 'http://www.cookseytalbottgallery.com/Testing/AppDev/Deliveries/Uploads/012-Hurricane Deck - SB.jpg', 18);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSN1169244064C45b13fa08db68', 'http://www.cookseytalbottgallery.com/Deliveries/Uploads/appdev.css', 19);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSZ1169244503C45b14157aa75d', 'http://www.cookseytalbottgallery.com//Deliveries/Uploads/appdev.css', 20);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSV1169244644C45b141e4b8222', 'http://www.cookseytalbottgallery.com//Deliveries/Uploads/blog_II.css', 21);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSK1169245798C45b14666ba90d', 'http://www.cookseytalbottgallery.com//Deliveries/Uploads/admin.css', 22);
INSERT INTO `APPDEV_FileLocations` VALUES ('CTSZ1169246360C45b148986bf87', 'http://www.cookseytalbottgallery.com//Deliveries/Uploads/test.txt', 23);

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_GALLERY_cookseytalbott`
-- 

DROP TABLE IF EXISTS `APPDEV_GALLERY_cookseytalbott`;
CREATE TABLE IF NOT EXISTS `APPDEV_GALLERY_cookseytalbott` (
  `FileName` text NOT NULL,
  `RecordNumber` int(4) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COMMENT='This is a gallery table template' AUTO_INCREMENT=19 ;

-- 
-- Dumping data for table `APPDEV_GALLERY_cookseytalbott`
-- 

INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('568-Fern.jpg', 1);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('108-WaterOnMossB2.jpg', 2);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('213-CloudsAndSunlightB.jpg', 3);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('127-DomeInTheFog.jpg', 4);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('033-GlacierAndMountain.jpg', 5);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('126-SantaBarbaraWaterfall.jpg', 6);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('200-LongRidgeInSunsetB.jpg', 7);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('031-BlueMountainLake.jpg', 8);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('177-MonoLake.jpg', 9);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('171-RockCreekSunset.jpg', 10);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('286-TreeInMeadow.jpg', 11);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('133-Granite-Head.jpg', 12);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('285-RyanOnAGlacier.jpg', 13);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('035-FracturedRockWall.jpg', 14);
INSERT INTO `APPDEV_GALLERY_cookseytalbott` VALUES ('frame-h.jpg', 18);

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COMMENT='This holds all of the gallery descriptions' AUTO_INCREMENT=15 ;

-- 
-- Dumping data for table `APPDEV_GalleryProfiles`
-- 

INSERT INTO `APPDEV_GalleryProfiles` VALUES ('723-CookseyAtHorsetailFallsC.jpg', 'Landscape Photographs of the Sierras', 'Ralph Cooksey-Talbott Thomas has been working as a photographer since 1972 when he moved to California from Michigan.<br><br>During the 1970’s he studied under Ansel Adams in Yosemite. Ansel published one of his photographs in the portfolio section of his book Polaroid Technique Manual. <br><br>Ansel and Orah Moore, another of Ansel’s students, suggested that he shorten his name to Cooksey-Talbott, and that is the name he has worked under since. <br><br>Cooksey also studied at the San Francisco Art Institute and the San Francisco Academy of Art. He has lectured in photography at the U.C. Berkeley Extension, Studio One in Oakland and at Santa Barbara City College. ', 'cookseytalbottgallery.com', 'Cooksey-Talbott', 'Cooksey-Talbott Gallery', 'cooksey@cookseytalbottgallery.com', '34670 Calcutta Dr. #12', 'Fremont', 'CA', '94555', '510-742-0548', 'cookseytalbott', 'SHOW', 'FORSALE', 'thumbs.php', 'image.php', 0, 1);
INSERT INTO `APPDEV_GalleryProfiles` VALUES ('No300xPhoto.jpg', 'Untitled Gallery', '', '', '', '', '', '', '', '', '', '', 'gallerytester2', 'HIDE', 'FORSALE', 'thumbs.php', 'image.php', 0, 14);

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='New in 2.1.8 - Supports detail pics in gallery' AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `APPDEV_ImageDetails`
-- 

INSERT INTO `APPDEV_ImageDetails` VALUES ('031-BlueMountainLake.jpg', '127-DomeInTheFog.jpg', 'cookseytalbott', 6);
INSERT INTO `APPDEV_ImageDetails` VALUES ('031-BlueMountainLake.jpg', '080-SolitarySequoia.jpg', 'cookseytalbott', 5);

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
  `Media` text COMMENT 'The type of media',
  `Orientation` enum('V','H') NOT NULL default 'V' COMMENT 'The orientation of the piece square is called H',
  `RecordNumber` int(4) unsigned NOT NULL auto_increment COMMENT 'the auto inc item number',
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

-- 
-- Dumping data for table `APPDEV_ImageLibrary`
-- 

INSERT INTO `APPDEV_ImageLibrary` VALUES ('108-WaterOnMossB2.jpg', 'Water on Moss', 'Ansel Adams Wilderness', 'How green is green ?<br><br>The saturated color of this print is just wonderful!<br><br>Crystalline water trickles over a bed of incredibly lush green moss at the side of a waterfall.<br><br>I found this near Shadow Lake in the Ansel Adams Wilderness Area.<br><br>This photograph was taken on Kodak VPS film with a 127mm lens using a Mamiya RB67 camera.', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'V', 14);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('033-GlacierAndMountain.jpg', 'Small Crevasse', 'Ansel Adams Wilderness', 'A small crevasse in a glacier...\r\n\r\nThis is up near Ediza...\r\n\r\n', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'H', 6);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('031-BlueMountainLake.jpg', 'Blue Mountain Lake foo', 'Ansel Adams Wilderness', 'Somewhere above lake Ediza in the Ansel Adams Wilderness Area I found this dramatic rocky lake and meadow.<br><br>The contrast of the cool blue water with the verdant green meadow against the black rock mountain really moved me…<br><br>We sat here and had a nice lunch and then walked on.<br><br>This photograph was taken on Kodak VPS film with a 127mm lens using a Mamiya RB67 camera.', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'H', 15);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('126-SantaBarbaraWaterfall.jpg', 'Santa Barbara Waterfall', 'Montecito', 'A waterfall on the tunnel trail.', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'H', 10);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('027-TumblingWaterfall.jpg', 'Hurricane Deck Sunrise', 'Figuroa Mountain', 'This was a wild place... It looks calm here...', 'Cooksey-Talbott', 'testfeebo3', 'Photograph', 'V', 21);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('106-BladesOfGrassC.jpg', 'Blades of grass', 'sdfsdf', 'My wondeful captn', 'Cooksey-Talbott', 'testfeebo3', 'Photograph', 'H', 22);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('286-TreeInMeadow.jpg', 'Yosemite Meadow', 'Yosemite Valley - California', ' Early morning in Yosemite Valley is a time when the landscape comes alive with the first light of day.<br><br>The acute angle of the light trans-illuminates the valley floor and makes the meadows come alive with color, form and texture.<br><br><br><br>I photographed this tree and then sat under it and enjoyed the first warmth of the morning sun.<br><br><br><br>The small 35mm film format creates a very painterly feeling.<br><br><br><br>This photograph was taken on Kodak VPS 160 film with a 50mm lens using a Pentax 35mm camera. ', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'V', 23);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('012-Hurricane-Deck---SB.jpg', 'Hurricane Deck Sunrise', 'Figuroa Mountain', 'This was a wild place... It looks calm here...', 'Cooksey-Talbott', '', 'Photograph', 'V', 20);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('127-DomeInTheFog.jpg', 'Solitary Tree', 'Tuolumne High Country', 'High above Yosemite Valley is the Yosemite high country and Tuolumne, a land of exfoliating granite domes, fragrant meadows and high mountain lakes.Walking across the domes in the fog I came to this tree standing by itself surrounded by soft yellow grasses and lichen encrusted granite.Inclement weather and rapidly changing conditions are an integral part of the high country experience. In this case the atmospheric effect of the clouds served to separate this tree from its companions.This photograph was taken on Kodak 4x5 Super-XX with a 135mm Schneider lens using a Graphic View II camera.', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'V', 13);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('200-LongRidgeInSunsetB.jpg', 'Long Ridge in Sunset', 'Trinity Alps', 'Twilight is a magical time of day, I love it when the sky darkens and we pass the boundary from day into night.<br><br>In the mountains with out the ever present light contamination of the city the delicate tones of the night sky become clear.<br><br>This is a long exposure in the late twilight high in the Trinity Alps. Note the trails from the first of the evening stars caused by the earths rotation during the exposure.<br><br>This photograph was taken on Kodak 4x5 Kodak VPS with a 150mm Schneider lens using a Graphic View II camera.', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'H', 16);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('213-CloudsAndSunlightB.jpg', 'Clouds and Sunlight', 'Sword Lake - Sonora Pass', 'I ran out to Sword Lake near Dardanelles and sat on a large boulder to rest overlooking the lake.<br><br>When I leaned back on my pack I saw beautiful clouds radiant in the afternoons light. Myriad rays of sunlight streaming from the clouds edge. A sky so blue it was overwhelming.<br><br>I try to always remember to look up.<br><br>This photograph was taken on Kodak Portra VC 160 film with a 127mm lens using a Mamiya RB67 camera. ', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'H', 17);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('177-MonoLake.jpg', 'Big Sky', 'Mono Lake', 'At Mono Lake everything is expansive, the lake stretches for miles like a vast inland sea and the sky goes on forever.<br><br>The shoreline is dotted with very delicate tufa formations and the littoral regions of the lake are alive with myriad forms of insect life.<br><br>On this day the clouds were marching across the lake building up towards an afternoon thunder shower.<br><br>This photograph was taken on Kodak VPS 160 film with a 90mm lens using a Mamiya RB67 camera. ', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'H', 18);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('568-Fern.jpg', 'Fern', 'Lake Tahoe', 'The early season this year had abundant water and everything was very moist. The extra water was good for the ferns growing in a quiet deeply shaded glade beneath Horsetail Falls. In this image the wonderful complexity of the ferns edge and the verdant green color are caught in stark contrast against the dark loam in the shadows. This was in the Pyramid Creek watershed just outside of Lake Tahoe. This photograph was taken on Kodak VC 160 film with a 90mm lens using a Mamiya RB67 camera. ', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'V', 19);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('171-RockCreekSunset.jpg', 'Rock Creek Sunset', 'Toms Place - Little Lakes Valley - California', 'This was a quiet evening at Chickenfoot Lake...', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'H', 24);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('133-Granite-Head.jpg', 'Granite Head', 'Tuolumne High Country - Yosemite', 'This is a wonderfully abstract 4x5 shot...', 'Cooksey-Talbott', 'cookseytalbott', 'Painting', 'H', 25);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('285-RyanOnAGlacier.jpg', 'Ryan climbing on a glacier', 'St Mays Pass - Sonora Pass - California', 'Walking in St. Marys pass we made it up to the small glacier in the saddle.<br><br>Ryan had to go to the top and slide down 96 times...', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'V', 26);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('035-FracturedRockWall.jpg', 'Fractured Rock Wall', 'Ansel Adams Wilderness', 'The stress was just too much for it and it cracked up... heh!', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', 'H', 27);
INSERT INTO `APPDEV_ImageLibrary` VALUES ('frame-h.jpg', 'Title foo', '', '', 'Cooksey-Talbott', 'cookseytalbott', 'Photograph', '', 31);

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
  `Description` text NOT NULL COMMENT 'The description of that size',
  `EditionType` text NOT NULL COMMENT 'The type of edition',
  `RecordNumber` int(4) NOT NULL auto_increment COMMENT 'The auto recno',
  PRIMARY KEY  (`RecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=latin1 COMMENT='This holds the sizes for the GALLERY image library' AUTO_INCREMENT=68 ;

-- 
-- Dumping data for table `APPDEV_ImageSizes`
-- 

INSERT INTO `APPDEV_ImageSizes` VALUES ('568-Fern.jpg', 'cookseytalbott', '24 x 31', '300.00', 'deep wrapped canvas print', 'Limited Edition', 36);
INSERT INTO `APPDEV_ImageSizes` VALUES ('177-MonoLake.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 35);
INSERT INTO `APPDEV_ImageSizes` VALUES ('213-CloudsAndSunlightB.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 38);
INSERT INTO `APPDEV_ImageSizes` VALUES ('108-WaterOnMossB2.jpg', 'cookseytalbott', '24 x 31', '300.00', 'deep wrapped canvas print', 'Limited Edition', 67);
INSERT INTO `APPDEV_ImageSizes` VALUES ('031-BlueMountainLake.jpg', 'cookseytalbott', '8 x 10', '35.00', 'print on fine art paper', 'Open Edition', 44);
INSERT INTO `APPDEV_ImageSizes` VALUES ('031-BlueMountainLake.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 43);
INSERT INTO `APPDEV_ImageSizes` VALUES ('200-LongRidgeInSunsetB.jpg', 'cookseytalbott', '31 x 24', '300.00', 'ready to hang deep gallery wrapped canvas print', 'Limited Edition', 31);
INSERT INTO `APPDEV_ImageSizes` VALUES ('200-LongRidgeInSunsetB.jpg', 'cookseytalbott', '22 x 18', '175.00', 'print on fine art paper in 24 x 30 mat', 'Limited Edition', 32);
INSERT INTO `APPDEV_ImageSizes` VALUES ('200-LongRidgeInSunsetB.jpg', 'cookseytalbott', '10 x 8', '35.00', 'raw print on fine art paper', 'Open Edition', 33);
INSERT INTO `APPDEV_ImageSizes` VALUES ('033-GlacierAndMountain.jpg', 'cookseytalbott', '24 x 31', '300.00', 'ready to hang deep gallery wrapped canvas print', 'Limited Edition', 56);
INSERT INTO `APPDEV_ImageSizes` VALUES ('568-Fern.jpg', 'cookseytalbott', '8 x 10', '35.00', 'print on fine art paper', 'Open Edition', 37);
INSERT INTO `APPDEV_ImageSizes` VALUES ('171-RockCreekSunset.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 46);
INSERT INTO `APPDEV_ImageSizes` VALUES ('286-TreeInMeadow.jpg', 'cookseytalbott', '24 x 31', '300.00', 'deep wrapped canvas print', 'Limited Edition', 45);
INSERT INTO `APPDEV_ImageSizes` VALUES ('126-SantaBarbaraWaterfall.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 26);
INSERT INTO `APPDEV_ImageSizes` VALUES ('126-SantaBarbaraWaterfall.jpg', 'cookseytalbott', '40 x 30', '600.00', 'deep wrapped canvas print', 'Limited Edition', 28);
INSERT INTO `APPDEV_ImageSizes` VALUES ('126-SantaBarbaraWaterfall.jpg', 'cookseytalbott', '10 x 8', '35.00', 'print on fine art paper', 'Limited Edition', 27);
INSERT INTO `APPDEV_ImageSizes` VALUES ('095-IronStainedRockFace.jpg', 'cookseytalbott', '', '100.00', 'Matted print', 'Original - One of One', 19);
INSERT INTO `APPDEV_ImageSizes` VALUES ('127-DomeInTheFog.jpg', 'cookseytalbott', '24 x 31', '300.00', 'deep wrapped canvas print', 'Limited Edition', 20);
INSERT INTO `APPDEV_ImageSizes` VALUES ('133-Granite-Head.jpg', 'cookseytalbott', '18 x 22', '175.00', 'print on fine art paper', 'Limited Edition', 47);
INSERT INTO `APPDEV_ImageSizes` VALUES ('133-Granite-Head.jpg', 'cookseytalbott', '24 x 31', '300.00', 'deep wrapped canvas print', 'Limited Edition', 48);
INSERT INTO `APPDEV_ImageSizes` VALUES ('285-RyanOnAGlacier.jpg', 'cookseytalbott', '24 x 30', '300.00', 'deep wrapped canvas print', 'Limited Edition', 50);
INSERT INTO `APPDEV_ImageSizes` VALUES ('035-FracturedRockWall.jpg', 'cookseytalbott', '31 x 24', '300.00', 'deep wrapped canvas print', 'Limited Edition', 51);
INSERT INTO `APPDEV_ImageSizes` VALUES ('frame-h.jpg', 'cookseytalbott', '13 x 15', '100.00', 'fubar', 'Open Edition', 55);

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `APPDEV_LINKS_CalendarBlog`
-- 

INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES ('epicurious.com', 'Goon Vittles', 'Hoot goon vittles!!!!', 'clark', 1);
INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES ('www.rdvproductions.com', 'Wyans Webby Site!', 'Most eggsalad!!!!!', 'clark', 2);
INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES ('www.fox.com', 'Those People', 'The gang of 500 is ruining the place... <br><br>Tirebiters! LEROY!!!!!!', 'clark', 3);
INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES ('yahoo.com', 'Yahooooo', 'A bunch of yobbo''s if I ever seed dem dere.', 'cookseyt', 4);
INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES ('google.com', 'oogle', 'G', 'clark', 5);
INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES ('dailykos.com', 'Danger Will Robinson DANGER!!!!', 'oppps!!! errrr~', 'testerc1', 6);
INSERT INTO `APPDEV_LINKS_CalendarBlog` VALUES ('www.oddtodd.com', 'Odd Todd', 'His is a funny guy!!!!!', 'clark', 7);

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_LINKS_LifoBlog`
-- 

DROP TABLE IF EXISTS `APPDEV_LINKS_LifoBlog`;
CREATE TABLE IF NOT EXISTS `APPDEV_LINKS_LifoBlog` (
  `blLinkURL` text COMMENT 'The URL of the link',
  `blName` text COMMENT 'The name of the web site or organization',
  `blDescription` text COMMENT 'The description of the site',
  `blPosterID` text NOT NULL COMMENT 'The ID of the poster',
  `blRecordNumber` int(4) NOT NULL auto_increment COMMENT 'This is the item number',
  PRIMARY KEY  (`blRecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- 
-- Dumping data for table `APPDEV_LINKS_LifoBlog`
-- 

INSERT INTO `APPDEV_LINKS_LifoBlog` VALUES ('google.com', 'The Grand Googinal', 'What do they do and why ?\r\n\r\nFilm at 11...', 'testerl2', 1);
INSERT INTO `APPDEV_LINKS_LifoBlog` VALUES ('cnn.com', 'CNN', 'Blues, all blues...', 'clark', 6);
INSERT INTO `APPDEV_LINKS_LifoBlog` VALUES ('fox.com', 'FOX', 'arf snap!', 'clark', 7);
INSERT INTO `APPDEV_LINKS_LifoBlog` VALUES ('www.happidog.com', 'Happi Dog ', 'Dog bone jewlery with birthstones!!!!!', 'clark', 8);
INSERT INTO `APPDEV_LINKS_LifoBlog` VALUES ('fatcow.com/', 'Fat Cow Web Hosting', 'The web host I use and recommend. Nice folks with great support.', 'clark', 4);
INSERT INTO `APPDEV_LINKS_LifoBlog` VALUES ('dev.mysql.com/tech-resources/', 'MySQL Tech Resources Center', 'A geeky place to hang out...', 'clark', 5);
INSERT INTO `APPDEV_LINKS_LifoBlog` VALUES ('thestikman.com', 'The Stikman', 'Author of many great songs...', 'clark', 9);
INSERT INTO `APPDEV_LINKS_LifoBlog` VALUES ('dccomics.com', 'DC Comics', 'boobar', 'clark', 10);

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_LuserInfo`
-- 

DROP TABLE IF EXISTS `APPDEV_LuserInfo`;
CREATE TABLE IF NOT EXISTS `APPDEV_LuserInfo` (
  `pwUserID` text NOT NULL,
  `pwPassword` text NOT NULL,
  `pwRightsID` enum('Administrator','Blog','SuperUser','Gallery') NOT NULL default 'Administrator' COMMENT 'This syncs with the rights table',
  `pwEMail` text NOT NULL,
  `pwFirstName` text NOT NULL,
  `pwLastName` text NOT NULL,
  `pwRecordNumber` int(8) unsigned NOT NULL auto_increment COMMENT 'I have added this in 2.1.x',
  PRIMARY KEY  (`pwRecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

-- 
-- Dumping data for table `APPDEV_LuserInfo`
-- 

INSERT INTO `APPDEV_LuserInfo` VALUES ('break', 'break', 'Administrator', 'cooksey@californiafriends.org', 'John', 'Q &#?breakage', 1);
INSERT INTO `APPDEV_LuserInfo` VALUES ('testuser1', 'testuser1', 'Administrator', 'cooksey@californiafriends.org', 'Test', 'User 1', 2);
INSERT INTO `APPDEV_LuserInfo` VALUES ('Webmaster', 'foo', 'Administrator', 'cooksey@cookseytalbottgallery.com', 'Ralph', 'Cooksey-Talbott', 3);
INSERT INTO `APPDEV_LuserInfo` VALUES ('clark', 'foo', 'SuperUser', 'cooksey@cookseytalbottgallery.com', 'Clark', 'Kent', 4);
INSERT INTO `APPDEV_LuserInfo` VALUES ('gallery', 'gallery', 'Gallery', 'cooksey@cookseytalbottgallery.com', 'Gallery', 'User', 5);
INSERT INTO `APPDEV_LuserInfo` VALUES ('cookseyt', 'bowwow', 'Blog', 'cooksey@californiafriends.org', 'Ralph', 'Cooksey-Talbott', 6);
INSERT INTO `APPDEV_LuserInfo` VALUES ('luthorthudd', 'thud', 'Blog', 'cooksey@californiafriends.com', 'Luthor', 'Thudd', 7);
INSERT INTO `APPDEV_LuserInfo` VALUES ('cookseytalbott', 'foo', 'Gallery', 'cooksey@californiafriends.org', 'Ralph', 'Cooksey-Talbott', 8);
INSERT INTO `APPDEV_LuserInfo` VALUES ('barbara_admin', 'redboots', 'Administrator', 'barbara@yaley.org', 'Barbara', 'Yaley', 9);
INSERT INTO `APPDEV_LuserInfo` VALUES ('barbara_lifo', '', 'Blog', '', '', '', 10);
INSERT INTO `APPDEV_LuserInfo` VALUES ('barbara_calendar', 'redboots', 'Blog', 'barbara@yaley.org', 'Barbara', 'Yaley', 11);
INSERT INTO `APPDEV_LuserInfo` VALUES ('testfeebo', 'foo', 'Gallery', 'cooksey@californiafriends.org', 'Test', 'Feebo', 12);
INSERT INTO `APPDEV_LuserInfo` VALUES ('testerc1', 'goober', 'Blog', 'cooksey@californiafriends.org', 'Calendar Tester 1', 't1', 13);
INSERT INTO `APPDEV_LuserInfo` VALUES ('testerc2', 'testerc2', 'Blog', 'cooksey@californiafriends.org', 'Calendar Tester 2', 't2', 14);
INSERT INTO `APPDEV_LuserInfo` VALUES ('testerl1', 'testerl1', 'Blog', 'cooksey@californiafriends.org', 'LIFO Tester 1', 't-lifo-1', 15);
INSERT INTO `APPDEV_LuserInfo` VALUES ('testerl2', 'testerl2', 'Blog', 'cooksey@californiafriends.org', 'LIFO Tester 2', 't-lifo-2', 16);
INSERT INTO `APPDEV_LuserInfo` VALUES ('tuenam', 'tuenam', 'Administrator', 'tue_nam@tntpictures.com', 'Tue Nam', 'Don', 17);
INSERT INTO `APPDEV_LuserInfo` VALUES ('rkeller', 'psi', 'Administrator', 'rkeller@presol.com', 'Robert', 'Keller', 18);
INSERT INTO `APPDEV_LuserInfo` VALUES ('testpodunk667', 'testpodunk667', 'Gallery', 'cooksey@californiafriends.com', 'test', 'podunk', 19);
INSERT INTO `APPDEV_LuserInfo` VALUES ('testpodunk666', 'testpodunk666', 'Gallery', 'cooksey@californiafriends.com', 'test', 'podunk', 20);
INSERT INTO `APPDEV_LuserInfo` VALUES ('testblogger1', 'testblogger1', 'Administrator', 'cooksey@californiafriends.org', 'Test', 'Blogger 1', 21);
INSERT INTO `APPDEV_LuserInfo` VALUES ('madmonkey7', 'mike', 'Blog', 'cooksey@californiafriends.org', 'Mad', 'Monkey', 22);
INSERT INTO `APPDEV_LuserInfo` VALUES ('mikeblog', 'mike', 'Blog', 'mike@mcrane.net', 'Mike', 'Crane', 23);
INSERT INTO `APPDEV_LuserInfo` VALUES ('mikecalendar', 'mike', 'Blog', 'mike@mcrane.net', 'Mike', 'Crane', 24);
INSERT INTO `APPDEV_LuserInfo` VALUES ('mikesuperuser', 'mike', 'SuperUser', 'mike@mcrane.net', 'Mike', 'Crane', 25);
INSERT INTO `APPDEV_LuserInfo` VALUES ('testfeebo3', 'foo', 'Gallery', 'cooksey@californiafriends.org', 'Test', 'Feebo3', 26);
INSERT INTO `APPDEV_LuserInfo` VALUES ('testfeebo2', 'foo', 'Gallery', 'cooksey@californiafriends.org', 'Test', 'Feebo2', 27);
INSERT INTO `APPDEV_LuserInfo` VALUES ('cookseyt', 'bowwow', 'Blog', 'cooksey@californiafriends.org', 'Ralph', 'Cooksey-Talbott', 28);
INSERT INTO `APPDEV_LuserInfo` VALUES ('guest', 'guest99', 'Administrator', 'cooksey@californiafriends.org', 'Guest', 'User', 29);

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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- 
-- Dumping data for table `APPDEV_MailDatabase`
-- 

INSERT INTO `APPDEV_MailDatabase` VALUES ('', 'Fido', 'Wido', '1234 Whobar Ct.', 'Bumblefordshire', 'Woofsville', '--', '123456', 'cooksey@cookseytalbottgallery.com', '12oregano', '', '', 'Y', 'Added by Admin', 'Added by Admin', '', 'Y', 'New Record', 23);

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

INSERT INTO `APPDEV_MailingListNames` VALUES ('Desktops', 'A weekly link to a nature photo desktop.', 'Y');
INSERT INTO `APPDEV_MailingListNames` VALUES ('Newsletter', 'A quarterly newsletter of photographic technique.', 'N');
INSERT INTO `APPDEV_MailingListNames` VALUES ('Events', 'Information about Cooksey''s shows and openings in the SF Bay area.', 'N');

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_PasswordTargets`
-- 

DROP TABLE IF EXISTS `APPDEV_PasswordTargets`;
CREATE TABLE IF NOT EXISTS `APPDEV_PasswordTargets` (
  `TargetID` text NOT NULL,
  `TargetPageName` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `APPDEV_PasswordTargets`
-- 

INSERT INTO `APPDEV_PasswordTargets` VALUES ('Admin', 'admin_home.php');
INSERT INTO `APPDEV_PasswordTargets` VALUES ('Gallery', 'gallery_home.php');
INSERT INTO `APPDEV_PasswordTargets` VALUES ('Blog', 'blog_home.php');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='This is for 1 vote per IP-Host voting' AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `APPDEV_PublicVote`
-- 

INSERT INTO `APPDEV_PublicVote` VALUES ('c-24-6-197-160.hsd1.ca.comcast.net', '24.6.197.160', '2007-06-04 14:14:49', 'General', '177-MonoLake.jpg', 5, 1);
INSERT INTO `APPDEV_PublicVote` VALUES ('c-24-6-197-160.hsd1.ca.comcast.net', '24.6.197.160', '2007-07-25 15:24:17', 'General', '031-BlueMountainLake.jpg', 1, 2);

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='This is the election header info' AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `APPDEV_PublicVoteElections`
-- 

INSERT INTO `APPDEV_PublicVoteElections` VALUES ('Test001', 'VotePerIP', 1);
INSERT INTO `APPDEV_PublicVoteElections` VALUES ('General', 'VotePerPiece', 4);

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='This has a tabulation of the public votes' AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `APPDEV_PublicVoteTabulation`
-- 

INSERT INTO `APPDEV_PublicVoteTabulation` VALUES ('General', '177-MonoLake.jpg', 1, 5, 1);
INSERT INTO `APPDEV_PublicVoteTabulation` VALUES ('General', '031-BlueMountainLake.jpg', 1, 1, 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_Rights`
-- 

DROP TABLE IF EXISTS `APPDEV_Rights`;
CREATE TABLE IF NOT EXISTS `APPDEV_Rights` (
  `RightsID` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `APPDEV_Rights`
-- 

INSERT INTO `APPDEV_Rights` VALUES ('Blog');
INSERT INTO `APPDEV_Rights` VALUES ('Administrator');
INSERT INTO `APPDEV_Rights` VALUES ('SuperUser');
INSERT INTO `APPDEV_Rights` VALUES ('Gallery');

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

INSERT INTO `APPDEV_SimpleCounters` VALUES ('index.php', 13035, 9337);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('galleries.php', 3581, 2467);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('about.php', 1981, 1073);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('subscription_signup.php', 787, 477);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('prices.php', 1218, 838);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('potw.php', 1440, 992);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('photo_blog.php', 2264, 1603);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('desktops.php', 4860, 3688);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('free_desktop.php', 6629, 4063);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('counter_test.php', 6, 6);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('page_template.php', 111, 111);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('GalleryThumbs', 109, 94);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('GalleryImage', 266, 101);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('Home', 78, 48);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('Events', 3, 3);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('GalleryLobby', 90, 74);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('Contact', 26, 26);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('MailSignUp', 38, 38);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('About', 15, 15);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('MailSubManager', 10, 10);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('Calendar', 52, 52);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('Blog', 32, 21);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('Sale', 15, 0);
INSERT INTO `APPDEV_SimpleCounters` VALUES ('DetailImage', 38, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_SystemRights`
-- 

DROP TABLE IF EXISTS `APPDEV_SystemRights`;
CREATE TABLE IF NOT EXISTS `APPDEV_SystemRights` (
  `pwRightsID` text NOT NULL,
  `pwRecordNumber` int(8) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`pwRecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `APPDEV_SystemRights`
-- 

INSERT INTO `APPDEV_SystemRights` VALUES ('Blog', 1);
INSERT INTO `APPDEV_SystemRights` VALUES ('Administrator', 2);
INSERT INTO `APPDEV_SystemRights` VALUES ('SuperUser', 3);
INSERT INTO `APPDEV_SystemRights` VALUES ('Gallery', 4);

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_TempGalleryGlobals`
-- 

DROP TABLE IF EXISTS `APPDEV_TempGalleryGlobals`;
CREATE TABLE IF NOT EXISTS `APPDEV_TempGalleryGlobals` (
  `CurrentItemNumber` varchar(8) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `APPDEV_TempGalleryGlobals`
-- 

INSERT INTO `APPDEV_TempGalleryGlobals` VALUES ('0');

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_TempGallery_1`
-- 

DROP TABLE IF EXISTS `APPDEV_TempGallery_1`;
CREATE TABLE IF NOT EXISTS `APPDEV_TempGallery_1` (
  `FileName` text,
  `SortOrder` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `APPDEV_TempGallery_1`
-- 

INSERT INTO `APPDEV_TempGallery_1` VALUES ('Ephemeral-0706-270-BW.jpg', '0');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Ephemeral-0706-379-BW.jpg', '1');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Ephemeral-0706-383-BW.jpg', '2');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Ephemeral-0706-387-BW.jpg', '3');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Falls-0706-235.jpg', '4');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Flowers-0706-118.jpg', '5');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Flowers-0706-173.jpg', '6');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Flowers-0706-175.jpg', '7');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Flowers-0706-186.jpg', '8');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Flowers-0706-190.jpg', '9');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Flowers-0706-248-P.jpg', '10');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Flowers-0706-336.jpg', '11');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Flowers-0706-337.jpg', '12');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Flowers-0706-347.jpg', '13');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Levitt-Falls-0706-279.jpg', '14');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Levitt-Falls-0706-284.jpg', '15');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Levitt-Falls-0706-295.jpg', '16');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Levitt-Falls-0706-304BW.jpg', '17');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Lichin-0706-141.jpg', '18');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Lupine-0706-232-P.jpg', '19');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('MorningLight-0706-10.jpg', '20');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Moss-0706-361.jpg', '21');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Moss-0706-367.jpg', '22');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Moss-0706-371.jpg', '23');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Our-Kids-0706-130.jpg', '24');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('RootsFalls-0706-62.jpg', '25');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Sardine-0706-163.jpg', '26');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Sardine-0706-242-45.jpg', '27');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Sardine-Falls-0706-212.jpg', '28');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('SardineFalls-0706-208BW.jpg', '29');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('SlotCyn-0706-096.jpg', '30');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('SlotCyn-0706-103.jpg', '31');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('SlotCyn-0706-20.jpg', '32');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('SlotFalls-0706-60.jpg', '33');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Sonora-Pass-0706-355.jpg', '34');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Stuart-0706-146.jpg', '35');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Stuart-0706-220.jpg', '36');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Water-0706-42.jpg', '37');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Water-Rock-0706-094.jpg', '38');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Water-Rock-0706-107.jpg', '39');
INSERT INTO `APPDEV_TempGallery_1` VALUES ('Water-Rock-0706-50.jpg', '40');

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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- 
-- Dumping data for table `APPDEV_TestMailingList`
-- 

INSERT INTO `APPDEV_TestMailingList` VALUES ('', '', '', '', '', '', '--', '', 'cooksey@cookseytalbottgallery.com', 'months11', 'Y', 'N', 'N', 'Added by Admin', 'Added by Admin', '', 'Y', 'New Record', 19);
INSERT INTO `APPDEV_TestMailingList` VALUES ('', '', '', '', '', '', '--', '', 'cooksey@californiafriends.org', '3snow', 'Y', 'N', 'N', 'Added by Admin', 'Added by Admin', '', 'Y', 'New Record', 20);

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `APPDEV_TopDownloads`
-- 

INSERT INTO `APPDEV_TopDownloads` VALUES ('Baby Bag', 'shared', 'foo.bar', 'FIX', 7, 2);
INSERT INTO `APPDEV_TopDownloads` VALUES ('Baby Bag', '/shared/', 'foo.bat', 'FIX', 2, 3);
INSERT INTO `APPDEV_TopDownloads` VALUES ('Baby Bag', '/shared/', 'MsIncognito-0305.mp3', 'FIX', 87, 4);
INSERT INTO `APPDEV_TopDownloads` VALUES ('bar', '/shared/', 'foo', 'FIX', 5, 5);
INSERT INTO `APPDEV_TopDownloads` VALUES ('Foobar Goobar - Fat Freddie and the GatorBators', '/shared', 'fake.mp3', 'MP3', 15, 6);

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_UserInfo`
-- 

DROP TABLE IF EXISTS `APPDEV_UserInfo`;
CREATE TABLE IF NOT EXISTS `APPDEV_UserInfo` (
  `pwUserID` text NOT NULL,
  `pwPassword` text NOT NULL,
  `pwRightsID` enum('Administrator','Blog','SuperUser','Gallery') NOT NULL default 'Administrator' COMMENT 'This syncs with the rights table',
  `pwEMail` text NOT NULL,
  `pwFirstName` text NOT NULL,
  `pwLastName` text NOT NULL,
  `pwRecordNumber` int(8) unsigned NOT NULL auto_increment COMMENT 'I have added this in 2.1.x',
  PRIMARY KEY  (`pwRecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

-- 
-- Dumping data for table `APPDEV_UserInfo`
-- 

INSERT INTO `APPDEV_UserInfo` VALUES ('clark', 'foo', 'SuperUser', 'cooksey@cookseytalbottgallery.com', 'Clark', 'Kent', 4);
INSERT INTO `APPDEV_UserInfo` VALUES ('blogtester', 'blogtester', 'Blog', 'cooksey@californiafriends.org', 'Ralph X.', 'Cooksey-Talbott', 5);
INSERT INTO `APPDEV_UserInfo` VALUES ('cookseytalbott', 'snap', 'Gallery', 'cooksey@californiafriends.org', 'Ralph', 'Cooksey-Talbott', 6);
INSERT INTO `APPDEV_UserInfo` VALUES ('webmaster', 'woo', 'Administrator', 'cooksey@cookseytalbottgallery.com', 'Ralph', 'Cooksey-Talbott', 7);
INSERT INTO `APPDEV_UserInfo` VALUES ('guest', 'guest99', 'Administrator', 'cooksey@californiafriends.org', 'Guest', 'Account', 31);
INSERT INTO `APPDEV_UserInfo` VALUES ('twoblog', 'twoblog', 'Blog', 'cooksey@cookseytalbottgallery.com', 'Two', 'BlogUser', 30);
INSERT INTO `APPDEV_UserInfo` VALUES ('oneblog', '1blog', 'Blog', 'cooksey@cookseytalbottgallery.com', 'One', 'BlogUser', 29);

-- --------------------------------------------------------

-- 
-- Table structure for table `APPDEV_UserPermissions`
-- 

DROP TABLE IF EXISTS `APPDEV_UserPermissions`;
CREATE TABLE IF NOT EXISTS `APPDEV_UserPermissions` (
  `pwUserID` text NOT NULL COMMENT 'The UserID from the UserInfo table',
  `pwPermission` text NOT NULL COMMENT 'The table or permission name',
  `pwRecordNumber` int(8) unsigned NOT NULL auto_increment COMMENT 'The record number',
  PRIMARY KEY  (`pwRecordNumber`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1 COMMENT='This holds the users granular permissions' AUTO_INCREMENT=39 ;

-- 
-- Dumping data for table `APPDEV_UserPermissions`
-- 

INSERT INTO `APPDEV_UserPermissions` VALUES ('blogtester', 'APPDEV_BLOG_LifoBlog', 31);
INSERT INTO `APPDEV_UserPermissions` VALUES ('blogtester', 'APPDEV_BLOG_CalendarBlog', 30);
INSERT INTO `APPDEV_UserPermissions` VALUES ('geber43', 'APPDEV_BLOG_CalendarBlog', 9);
INSERT INTO `APPDEV_UserPermissions` VALUES ('geber43', 'APPDEV_BLOG_AdOne', 10);
INSERT INTO `APPDEV_UserPermissions` VALUES ('feeb', 'APPDEV_BLOG_LifoBlog', 11);
INSERT INTO `APPDEV_UserPermissions` VALUES ('feeb', 'APPDEV_BLOG_AdOne', 12);
INSERT INTO `APPDEV_UserPermissions` VALUES ('almira', 'APPDEV_BLOG_CalendarBlog', 13);
INSERT INTO `APPDEV_UserPermissions` VALUES ('almira', 'APPDEV_BLOG_LifoBlog', 14);
INSERT INTO `APPDEV_UserPermissions` VALUES ('almira', 'APPDEV_BLOG_AdOne', 15);
INSERT INTO `APPDEV_UserPermissions` VALUES ('blearrrgh', 'APPDEV_BLOG_CalendarBlog', 16);
INSERT INTO `APPDEV_UserPermissions` VALUES ('blearrrgh', 'APPDEV_BLOG_AdOne', 17);
INSERT INTO `APPDEV_UserPermissions` VALUES ('oneblog', 'APPDEV_BLOG_CalendarBlog', 35);
INSERT INTO `APPDEV_UserPermissions` VALUES ('twoblog', 'APPDEV_BLOG_CalendarBlog', 38);
INSERT INTO `APPDEV_UserPermissions` VALUES ('oneblog', 'APPDEV_BLOG_LifoBlog', 36);
INSERT INTO `APPDEV_UserPermissions` VALUES ('oneblog', 'APPDEV_BLOG_AdOne', 37);

