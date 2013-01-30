<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// blog_parms.php
//
// Parms for the blog system
//
//
// Depends:	local_info
//			db_lib
//			html_lib
//			cl_lib
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

$debug				=	0;

$systemUserID		=	GetPassedValue("SystemUserID");
$systemPassword		=	GetPassedValue("SystemPassword");

$blPosterID			=	$systemUserID;
$blLinkCaption		=	GetPassedValue("blLinkCaption");
$blHeadline			=	GetPassedValue("blHeadline");
$blSubHead			=	GetPassedValue("blSubHead");
$blCopy				=	GetPassedValue("blCopy");
$blEventDate		=	GetPassedValue("blEventDate");
$blEventTime		=	GetPassedValue("blEventTime");
$blItemType			=	GetPassedValue("blItemType");
$blItemNumber		=	GetPassedValue("blItemNumber");
$blMode				=	GetPassedValue("blMode");
$blTable			=	GetPassedValue("blTable");
$blCaller			=	GetPassedValue("blCaller");
$blTitle			=	GetPassedValue("blTitle");
$blDescription		=	GetPassedValue("blDescription");
$blLinkURL			=	GetPassedValue("blLinkURL");
$blSiteName			=	GetPassedValue("blSiteName");
$blLinkDescription	=	GetPassedValue("blLinkDescription");
$blSortType			=	GetPassedValue("blSortType");
$blSortOrder		=	GetPassedValue("blSortOrder");
$blRecordNumber		=	GetPassedValue("blRecordNumber");
$blName				=	GetPassedValue("blName");
$blType				=	GetPassedValue("blType");
$blModerator		=	GetPassedValue("blModerator");
$blFileName			=	GetPassedValue("blFileName");
$blPath				=	GetPassedValue("blPath");
$blMonth			=	GetPassedValue("Month");
$blDay				=	GetPassedValue("Day");
$blYear				=	GetPassedValue("Year");
$blEventHour		=	GetPassedValue("blEventHour");
$blEventMinute		=	GetPassedValue("blEventMinute");
$blEventAmPm		=	GetPassedValue("blEventAmPm");
$blDurationHour		=	GetPassedValue("blDurationHour");
$blDurationMinute	=	GetPassedValue("blDurationMinute");


// this gets added to the args string n the server
// blEventHour=$blEventHour&blEventMinute=$blEventMinute&blEventAmPm=$blEventAmPm&blDurationHour=$blDurationHour&blDurationMinute=$blDurationMinute&

$blArgs				=	"blFileName=$blFileName&blPath=$blPath&blEventHour=$blEventHour&blEventMinute=$blEventMinute&blEventAmPm=$blEventAmPm&blDurationHour=$blDurationHour&blDurationMinute=$blDurationMinute&blModerator=$blModerator&blType=$blType&blName=$blName&blRecordNumber=$blRecordNumber&blSortOrder=$blSortOrder&blSortType=$blSortType&blLinkCaption=$blLinkCaption&blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller&blLinkURL=$blLinkURL&blSiteName=$blSiteName&blLinkDescription=$blLinkDescription";

if($debug) print "<hr>";
if($debug) print "blog_parms.php<br><br>";
if($debug) print "blName: $blName<br>";
if($debug) print "blModerator: $blModerator<br>";
if($debug) print "systemUserID:  $systemUserID<br>";
if($debug) print "systemPassword:  $systemPassword<br>";
if($debug) print "blRecordNumber:  $blRecordNumber<br>";
if($debug) print "blSortType:  $blSortType<br>";
if($debug) print "blSortOrder:  $blSortOrder<br>";
if($debug) print "blTitle:  $blTitle<br>";
if($debug) print "blDescription:  $blDescription<br>";
if($debug) print "blLinkCaption:  $blLinkCaption<br>";
if($debug) print "blHeadline:  $blHeadline<br>";
if($debug) print "blSubHead:  $blSubHead<br>";
if($debug) print "blCopy:  $blCopy<br>";
if($debug) print "blPosterID:  $blPosterID<br>";
if($debug) print "year:  $blYear<br>";
if($debug) print "blItemType:  $blItemType<br>";
if($debug) print "blItemNumber:  $blItemNumber<br>";
if($debug) print "blMode:  $blMode<br>";
if($debug) print "blTable:  $blTable<br>";
if($debug) print "blFileName: $blFileName<br>";
if($debug) print "blPath: $blPath<br>";

//if($debug) print "blCaller:  $blCaller<br>";

// link parms
if($debug) print "<hr>";
if($debug) print "blLinkURL:  $blLinkURL<br>";
if($debug) print "blSiteName:  $blSiteName<br>";
if($debug) print "blLinkDescription:  $blLinkDescription<br>";
if($debug) print "<hr>";

if($debug) print "blEventDate:  $blEventDate<br>";
if($debug) print "blEventTime:  $blEventTime<br>";
if($debug) print "blDay:  $blDay<br>";
if($debug) print "blMonth:  $blMonth<br>";
if($debug) print "blYear:  $blYear<br>";
if($debug) print "blEventHour: $blEventHour<br>";
if($debug) print "blEventMinute: $blEventMinute<br>";
if($debug) print "blEventAmPm: $blEventAmPm<br>";
if($debug) print "blDurationHour: $blDurationHour<br>";
if($debug) print "blDurationMinute: $blDurationMinute<br>";
if($debug) print "<hr>";
?>
