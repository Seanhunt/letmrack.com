
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// blog_lib.php
//
// Has blog functionality with admin functions
//
// 0705 fixed up link in BlogPrintOneItem($table,$index,$withUI) from journal to photo_blog, this needs an input arg
//
// Revision: 	071105 First Revision Released
// 				Added 07/05 ItemType to value list in BlogInsertItem($table,$headline,$subHead,$copy,$itemType)
// 				020506 Start Second Revision
//
// Depends:		../local_info.php
//				db_lib.php
//				file_lib
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------



function BlogGetItemIndex($recordNumber,$tableName)
{
$debug = 0;

if($debug) print "BlogGetItemIndex($recordNumber,$tableName)<br>";

OpenDatabase();

$rs	=	QueryDatabase("SELECT blRecordNumber FROM $tableName");

CloseDatabase();

$numberOfRows	=	mysql_numrows($rs);

if($debug) print "numberOfRows: $numberOfRows<br>";

for($i=0;$i<$numberOfRows;$i++)
	{
	$thisRecNo	=	GetDatabaseResult($rs,$i,"blRecordNumber");

	if($debug) print "thisRecNo: $thisRecNo<br>";
	
	if($thisRecNo==$recordNumber)
		{
		if($debug) print "Returning: $i	<br>";
		
		return $i;
		}
	}

if($debug) print "Item not found<br>";

return 0; // bad juju
}

function BlogGetItem($index,$tableName,$templateFileName)
{
$debug = 0;

if($debug) print "BlogGetItem($index,$tableName,$templateFileName)<br>";

// note, this func will alter the value $index
$rs		=	BlogGetItemRS($index,$tableName);
$item	=	BlogFillItemRS($rs,$index,$templateFileName,$tableName);	

return $item;
}





// gets article by item number
function BlogGetArticle($itemNumber,$tableName,$templateFileName)
{
$debug = 0;

if($debug) print "BlogGetArticle($itemNumber,$tableName,$templateFileName)<br>";

$query	=	"SELECT * FROM $tableName WHERE blRecordNumber='$itemNumber'";

if($debug) print "query: $query<br>";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

if($debug) print "records fetched: " . mysql_numrows($rs) . "<br>";

BlogContinuedMode(false);

$item	=	BlogFillItemRS($rs,0,$templateFileName,$tableName);	

return $item;
}

// -----------------
// 
function BlogGetItemRS(&$index,$tableName)
{
$debug	=	0;

global $gBaseMountPoint;
global $gBlogSharedMountPoint;

if($debug) print "BlogGetItemRS($index,$tableName)<br>";

// see what type of blog it is and set up the sort order
$blogType	=	 BlogHeaderGetType($tableName);

if($blogType=="ORDERED")
	{
	$orderClause	=	"ORDER BY blRecordNumber DESC";
	}
else if($blogType=="CALENDAR")
	{
	$orderClause	=	"ORDER BY blEventDate";
	}

if($blogType=="LIFO" || $blogType=="MUSIC")
	{
	// is index in ordered zone ?
	$numFixedQuery	=	"SELECT * FROM $tableName WHERE blSortType='FIXED'";
	$numberOfFixed	=	GetNumberOfRows($numFixedQuery);


	if($debug2) print "numberOfFixed: $numberOfFixed<br>";
	if($debug2) print "numFixedQuery: $numFixedQuery<br>";
	
	if($index < $numberOfFixed)
		{
		if($debug) print "Load Fixed Position Record<br>";
		// ordered, sort by sort order
		$query	=	"
					SELECT * FROM
					$tableName
					WHERE
					blVisibility='SHOW'
					AND
					blSortType='FIXED'
					ORDER BY
					blSortOrder
					";
		}
	else
		{
		if($debug) print "Load LIFO Position Record<br>";

		// dec the fixed records from the index
		$index	-=	$numberOfFixed;

		// not ordered record sort as LIFO
		$query	=	"
					SELECT * FROM
					$tableName
					WHERE
					blVisibility='SHOW'
					AND
					blSortType='LIFO'
					ORDER BY 
					blRecordNumber DESC
					";
		}
	}
else
	{
	$query	=	"
				SELECT * FROM
				$tableName
				WHERE
				blVisibility='SHOW'
				$orderClause
				";
	}


if($debug) print "blogType: $blogType<br>";
if($debug) print "query: $query<br>";
	
OpenDatabase();

$rs	=	QueryDatabase($query);

if($debug) print "records fetched: " . mysql_numrows($rs) . "<br>";

CloseDatabase();

return $rs;
}


// true means process the continued tag if it exists
// false means remove it if it exists
$blContinuedMode	=	false;

function BlogContinuedMode($mode)
{
global $blContinuedMode;

$blContinuedMode	=	$mode;
}


function BlogFillItemRS($rs,$index,$templateFileName,$tableName)
{
global $gStoryPageFileName;
global $blContinuedMode;

// see what type of blog it is and set up the sort order
$debug	=	0;

if($debug) print "BlogFillItemRS(rs,$index,$templateFileName,$tableName)<br>";

// round up the data
$headline	=	GetDatabaseResult($rs,$index,"blHeadline");
$subhead	=	GetDatabaseResult($rs,$index,"blSubHead");
$copy		=	GetDatabaseResult($rs,$index,"blCopy");

$path		=	GetDatabaseResult($rs,$index,"blPath");
$fileName	=	GetDatabaseResult($rs,$index,"blFileName");

$sqlDate	=	GetDatabaseResult($rs,$index,"blEventDate");
$eventTime	=	GetDatabaseResult($rs,$index,"blEventTime"); // duration

$postingDate=	GetDatabaseResult($rs,$index,"blPostingDate");
$posterID	=	GetDatabaseResult($rs,$index,"blPosterID");
$itemNumber	=	GetDatabaseResult($rs,$index,"blRecordNumber");

global $gBaseMountPoint;

$musicFilePath				=	"$gBaseMountPoint$path";

// the fn is in the event time
$musicFileName			=	$fileName;

$musicFileTitle			=	$headline;

if($debug) print "fileName: $fileName<br>";
if($debug) print "path: $path<br>";

// build the path
$musicFileNameAndPath	=	"$musicFilePath/$musicFileName";

// break apart the date and start time
$ts			=	BreakSQLTimeStamp($sqlDate);
$d			=	explode(":",$eventTime);

$durationHours		=	$d[0];
$durationMinutes	=	$d[1];

if($debug) print "durationHours: $durationHours<br>";
if($debug) print "durationMinutes: $durationMinutes<br>";

$eventDuration		=	"$durationHours:$durationMinutes";
$eventDate			=	$ts["Month"] . "/" . $ts["Day"] . "/" . $ts["Year"];

//remove zero prepension
if($ts["Hour"] < 10)
	{
	$tempHour	=	(int)$ts["Hour"];
	if($debug) print "REMOVE LEADING 0 - tempHour: $tempHour<br>";
	}
else
	{
	$tempHour	=	$ts["Hour"];
	if($debug) print "KEEP LEADING 0 - tempHour: $tempHour<br>";
	}
	
if($debug) print "tempHour: $tempHour<br>";
	
$eventTime			=	$tempHour . ":" . $ts["Minute"];

if($debug) print "eventTime: $eventTime<br>";

// do the end time

$endMinutes	=	$ts["Minute"] + $durationMinutes;

if($debug) print "endMinutes: $endMinutes<br>";
	
if($endMinutes > 59)
	{
	if($debug) print "Roll over hour...<br>";

	$endHour	=	(int)($tempHour + 1 + $durationHours);
	$endMinutes -=	60;
	}
else
	{
	$endHour	=	$tempHour + $durationHours;
	if($debug) print "Fractional hour...<br>";
	}
	
if($debug) print "endHour: $endHour<br>";

// Do the AM PM

if(($ts["Minute"] + $durationMinutes) > 59)
	{
	if($debug) print "AMPM Roll over hour<br>";
	// roll over an hour
	$militaryEndHour	=	$durationHours + $ts["MilitaryHour"] + 1;
	}
else
	{
	if($debug) print "AMPM Fractional<br>";
	$militaryEndHour	=	$durationHours + $ts["MilitaryHour"];
	}
	
if($militaryEndHour > 12)
	{
	$endAmPm	=	"PM";
	}
else
	{
	$endAmPm	=	"AM";
	}

if($endHour > 12)
	{
	$endHour	-= 12;	
	}

if($debug) print "militaryEndHour: $militaryEndHour<br>";
if($debug) print "endAmPm: $endAmPm<br>";
if($debug) print "endHour: $endHour<br>";

$eventAmPm		=	$ts["AmPm"];

$eventEndTime	=	sprintf("%d:%02d",$endHour,$endMinutes);

if($debug) print "Time Stamp:: $sqlDate<br>";
if($debug) print "eventDate: $eventDate<br>";
if($debug) print "eventTime: $eventTime<br>";
if($debug) print "eventDuration: $eventDuration<br>";
if($debug) print "eventEndTime: $eventEndTime<br>";
if($debug) print "endAmPm: $endAmPm<br>";
if($debug) print "eventAmPm: $eventAmPm<br>";

if($debug) print "headline: $headline<br>";
if($debug) print "subhead: $subhead<br>";
if($debug) print "copy: $copy<br>";
if($debug) print "postingDate: $postingDate<br>";
if($debug) print "posterID: $posterID<br>";
// END FIX *******************************

if($headline=="") 		$headline		= "&nbsp;";
if($subhead=="") 		$subhead		= "&nbsp;";
if($copy=="") 			$copy			= "&nbsp;";
if($eventDate=="") 		$eventDate		= "&nbsp;";
if($eventTime=="") 		$eventTime		= "&nbsp;";
if($postingDate=="") 	$postingDate	= "&nbsp;";
if($posterID=="") 		$posterID		= "&nbsp;";

// lose string literals
$headline	=	stripslashes($headline);
$subhead	=	stripslashes($subhead);
$copy		=	stripslashes($copy);

// read in the template file
$templateFile	=	FileRead($templateFileName);

// does a _CONTINUED_ tag exist ?
// if continued mode is on fix it up else remove it
// if so just get the lede and fix up the _CONTINUEDLINK_ tag
// if the site uses this the template must have a _CONTINUEDLINK_ tag

if($blContinuedMode)
	{
	if(BlogContinueTagExists($copy)==true)
		{
		$copy	=	BlogGetLede($copy);
		
		// set up the link to the article page
		$continuedLinkURL	=	"$gStoryPageFileName?blRecordNumber=$itemNumber";
	
		$continuedTemplateFileName	=	str_replace(".html","_continued.html",$templateFileName);
	
		$continuedTemplate	=	FileRead($continuedTemplateFileName);	
	
		// fix up the link in the link template
		$continuedTemplate	=	str_replace("_CONTINUEDLINKURL_",$continuedLinkURL,$continuedTemplate);
		
		// fix up the _CONTINUED_ tag in the copy with the fixed up continuedTemplate
		$templateFile		=	str_replace("_CONTINUED_",$continuedTemplate,$templateFile);
		}
	}

// if the _CONTINUED_ tag is still here the story is one page only so 
// clean out the tag, this post does not go over the bump
$templateFile					=	str_replace("_CONTINUED_","",$templateFile);

$copy							=	StripContinueTag($copy);
	

//if($debug) print "BEFORE SR - templateFile: $templateFile<br>";

// set up the edit and delete links for the record

// EDIT LINK
$blArgs			=	"blItemNumber=$itemNumber&blTable=$tableName&blMode=Refresh";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs";
$editLink		=	"blog_edit.php?$args";	

// DELETE LINK
$deleteMessage	=	"<br>This will delete the posting...<br><br>$headline<br><br>";
$questArgs		=	quBuildArgs("blog_services.php","blog_edit_picker.php",$deleteMessage,"Continue ?");
$blArgs			=	"blItemNumber=$itemNumber&blMode=DeleteItem&blTable=$tableName";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs&$questArgs";
$deleteLink		=	"question_yn.php?$args";

//
$templateFile	=	str_replace("_EVENTDATE_",$eventDate,$templateFile);
$templateFile	=	str_replace("_EVENTTIME_",$eventTime,$templateFile);
$templateFile	=	str_replace("_EVENTDURATION_",$eventDuration,$templateFile);
$templateFile	=	str_replace("_EVENTENDTIME_",$eventEndTime,$templateFile);
$templateFile	=	str_replace("_EVENTENDAMPM_",$endAmPm,$templateFile);
$templateFile	=	str_replace("_EVENTAMPM_",$eventAmPm,$templateFile);

$templateFile	=	str_replace("_EDITLINK_",$editLink,$templateFile);
$templateFile	=	str_replace("_DELETELINK_",$deleteLink,$templateFile);
$templateFile	=	str_replace("_HEADLINE_",$headline,$templateFile);
$templateFile	=	str_replace("_SUBHEAD_",$subhead,$templateFile);
$templateFile	=	str_replace("_COPY_",$copy,$templateFile);
$templateFile	=	str_replace("_POSTINGDATE_",$postingDate,$templateFile);
$templateFile	=	str_replace("_POSTERID_",$posterID,$templateFile);
$templateFile	=	str_replace("_ITEMNUMBER_",$itemNumber,$templateFile);

$templateFile	=	str_replace("_MUSICFILEPATH_",$musicFilePath,$templateFile);
$templateFile	=	str_replace("_MUSICFILENAME_",$musicFileName,$templateFile);
$templateFile	=	str_replace("_MUSICFILETITLE_",$musicFileTitle,$templateFile);

//if($debug) print "AFTER SR - templateFile: $templateFile<br>";

return $templateFile;
}

/*
this had ts problems...

function BlogFillItemRS($rs,$index,$templateFileName,$tableName)
{

// see what type of blog it is and set up the sort order
$debug	=	0;

if($debug) print "BlogFillItemRS(rs,$index,$templateFileName,$tableName)<br>";

// round up the data
$headline	=	GetDatabaseResult($rs,$index,"blHeadline");
$subhead	=	GetDatabaseResult($rs,$index,"blSubHead");
$copy		=	GetDatabaseResult($rs,$index,"blCopy");

$path		=	GetDatabaseResult($rs,$index,"blPath");
$fileName	=	GetDatabaseResult($rs,$index,"blFileName");

$sqlDate	=	GetDatabaseResult($rs,$index,"blEventDate");
$eventTime	=	GetDatabaseResult($rs,$index,"blEventTime"); // duration

$postingDate=	GetDatabaseResult($rs,$index,"blPostingDate");
$posterID	=	GetDatabaseResult($rs,$index,"blPosterID");
$itemNumber	=	GetDatabaseResult($rs,$index,"blRecordNumber");

global $gBaseMountPoint;

$musicFilePath				=	"$gBaseMountPoint$path";

// the fn is in the event time
$musicFileName			=	$fileName;

$musicFileTitle			=	$headline;

if($debug) print "fileName: $fileName<br>";
if($debug) print "path: $path<br>";

// build the path
$musicFileNameAndPath	=	"$musicFilePath/$musicFileName";
	
// break apart the date and start time
$ts			=	BreakSQLTimeStamp($sqlDate);
$d			=	explode(":",$eventTime);

$durationHours		=	$d[0];
$durationMinutes	=	$d[1];

$eventDuration		=	"$durationHours:$durationMinutes";
$eventDate			=	$ts["Month"] . "/" . $ts["Day"] . "/" . $ts["Year"];

if($ts["Hour"] < 10)
	$tempHour	=	(int)$ts["Hour"];
else
	$tempHour	=	$ts["Hour"];
	
$eventTime			=	$tempHour . ":" . $ts["Minute"];

// do the end time

$endMinutes	=	$ts["Minute"] + $durationMinutes;

if($endMinutes > 59)
	{
	$endHour	=	(int)($tempHour + 1 + $durationHours);
	$endMinutes -=	60;
	}
else
	$endHour	=	$tempHour + $durationHours;

if($endHour > 12)
	{
	$endHour	-= 12;
	
	// flip the ampm
	if($ts["AmPm"]=="PM")
		$endAmPm	=	"AM";
	else
		$endAmPm	=	"PM";
	}
else if($endHour == 12)
	{
	// flip the ampm
	if($ts["AmPm"]=="PM")
		$endAmPm	=	"AM";
	else
		$endAmPm	=	"PM";
	}
else
	{
	$endAmPm	=	$ts["AmPm"];
	}

$eventAmPm		=	$ts["AmPm"];

$eventEndTime	=	sprintf("%d:%02d",$endHour,$endMinutes);

if($debug) print "eventDate: $eventDate<br>";
if($debug) print "eventTime: $eventTime<br>";
if($debug) print "eventDuration: $eventDuration<br>";
if($debug) print "eventEndTime: $eventEndTime<br>";
if($debug) print "endAmPm: $endAmPm<br>";
if($debug) print "eventAmPm: $eventAmPm<br>";

if($debug) print "headline: $headline<br>";
if($debug) print "subhead: $subhead<br>";
if($debug) print "copy: $copy<br>";
if($debug) print "postingDate: $postingDate<br>";
if($debug) print "posterID: $posterID<br>";

if($headline=="") 		$headline		= "&nbsp;";
if($subhead=="") 		$subhead		= "&nbsp;";
if($copy=="") 			$copy			= "&nbsp;";
if($eventDate=="") 		$eventDate		= "&nbsp;";
if($eventTime=="") 		$eventTime		= "&nbsp;";
if($postingDate=="") 	$postingDate	= "&nbsp;";
if($posterID=="") 		$posterID		= "&nbsp;";

// lose string literals
$headline	=	stripslashes($headline);
$subhead	=	stripslashes($subhead);
$copy		=	stripslashes($copy);

// read in the file
$templateFile	=	FileRead($templateFileName);

//if($debug) print "BEFORE SR - templateFile: $templateFile<br>";

// set up the edit and delete links for the record

// EDIT LINK
$blArgs			=	"blItemNumber=$itemNumber&blTable=$tableName&blMode=Refresh";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs";
$editLink		=	"blog_edit.php?$args";	

// DELETE LINK
$deleteMessage	=	"<br>This will delete the posting...<br><br>$headline<br><br>";
$questArgs		=	quBuildArgs("blog_services.php","blog_edit_picker.php",$deleteMessage,"Continue ?");
$blArgs			=	"blItemNumber=$itemNumber&blMode=DeleteItem&blTable=$tableName";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs&$questArgs";
$deleteLink		=	"question_yn.php?$args";

//
$templateFile	=	str_replace("_EVENTDATE_",$eventDate,$templateFile);
$templateFile	=	str_replace("_EVENTTIME_",$eventTime,$templateFile);
$templateFile	=	str_replace("_EVENTDURATION_",$eventDuration,$templateFile);
$templateFile	=	str_replace("_EVENTENDTIME_",$eventEndTime,$templateFile);
$templateFile	=	str_replace("_EVENTENDAMPM_",$endAmPm,$templateFile);
$templateFile	=	str_replace("_EVENTAMPM_",$eventAmPm,$templateFile);

$templateFile	=	str_replace("_EDITLINK_",$editLink,$templateFile);
$templateFile	=	str_replace("_DELETELINK_",$deleteLink,$templateFile);
$templateFile	=	str_replace("_HEADLINE_",$headline,$templateFile);
$templateFile	=	str_replace("_SUBHEAD_",$subhead,$templateFile);
$templateFile	=	str_replace("_COPY_",$copy,$templateFile);
$templateFile	=	str_replace("_POSTINGDATE_",$postingDate,$templateFile);
$templateFile	=	str_replace("_POSTERID_",$posterID,$templateFile);
$templateFile	=	str_replace("_ITEMNUMBER_",$itemNumber,$templateFile);

$templateFile	=	str_replace("_MUSICFILEPATH_",$musicFilePath,$templateFile);
$templateFile	=	str_replace("_MUSICFILENAME_",$musicFileName,$templateFile);
$templateFile	=	str_replace("_MUSICFILETITLE_",$musicFileTitle,$templateFile);

//if($debug) print "AFTER SR - templateFile: $templateFile<br>";

return $templateFile;

}
*/

/*
// gets ALL of the items
// old ver
function BlogGetItemOLD($index,$tableName,$templateFileName)
{
$debug			=	0;

global $gBaseMountPoint;
global $gBlogSharedMountPoint;

if($debug2) print "BlogGetItem($index,$tableName,$templateFileName)<br>";

// see what type of blog it is and set up the sort order
$blogType	=	 BlogHeaderGetType($tableName);

if($blogType=="ORDERED" || $blogType=="MUSIC")
	{
	$orderClause	=	"ORDER BY blRecordNumber DESC";
	}
else if($blogType=="CALENDAR")
	{
	$orderClause	=	"ORDER BY blEventDate";
	}

if($blogType=="LIFO")
	{
	// is index in ordered zone ?
	$numFixedQuery	=	"SELECT * FROM $tableName WHERE blSortType='FIXED'";
	$numberOfFixed	=	GetNumberOfRows($numFixedQuery);


	if($debug) print "numberOfFixed: $numberOfFixed<br>";
	if($debug) print "numFixedQuery: $numFixedQuery<br>";
	
	if($index < $numberOfFixed)
		{
		if($debug2) print "Load Fixed Position Record<br>";
		// ordered, sort by sort order
		$query	=	"
					SELECT * FROM
					$tableName
					WHERE
					blVisibility='SHOW'
					AND
					blSortType='FIXED'
					ORDER BY
					blSortOrder
					";
		}
	else
		{
		if($debug2) print "Load LIFO Position Record<br>";

		// dec the fixed records from the index
		$index	-=	$numberOfFixed;

		// not ordered record sort as LIFO
		$query	=	"
					SELECT * FROM
					$tableName
					WHERE
					blVisibility='SHOW'
					AND
					blSortType='LIFO'
					ORDER BY 
					blRecordNumber DESC
					";
		}
	}
else
	{
	$query	=	"
				SELECT * FROM
				$tableName
				WHERE
				blVisibility='SHOW'
				$orderClause
				";
	}


if($debug) print "blogType: $blogType<br>";
if($debug2) print "query: $query<br>";
	
OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

// --------------------

// round up the data
$headline	=	GetDatabaseResult($rs,$index,"blHeadline");
$subhead	=	GetDatabaseResult($rs,$index,"blSubHead");
$copy		=	GetDatabaseResult($rs,$index,"blCopy");

$sqlDate	=	GetDatabaseResult($rs,$index,"blEventDate");
$eventTime	=	GetDatabaseResult($rs,$index,"blEventTime"); // duration

$postingDate=	GetDatabaseResult($rs,$index,"blPostingDate");
$posterID	=	GetDatabaseResult($rs,$index,"blPosterID");
$itemNumber	=	GetDatabaseResult($rs,$index,"blRecordNumber");

// ADDING DLPATH ETC...
if($blogType == "MUSIC")
	{
	$musicFilePath				=	"$gBaseMountPoint$gBlogSharedMountPoint";
	// the fn is in the event time
	$musicFileName			=	$eventTime;

	$musicFileTitle			=	$headline;

	// build the path
	$musicFileNameAndPath	=	"$dlPath/$dlFileName";
	}
	
// break apart the date and start time
$ts			=	BreakSQLTimeStamp($sqlDate);
$d			=	explode(":",$eventTime);

$durationHours		=	$d[0];
$durationMinutes	=	$d[1];

$eventDuration		=	"$durationHours:$durationMinutes";



$eventDate			=	$ts["Month"] . "/" . $ts["Day"] . "/" . $ts["Year"];

if($ts["Hour"] < 10)
	$tempHour	=	(int)$ts["Hour"];
else
	$tempHour	=	$ts["Hour"];
	
$eventTime			=	$tempHour . ":" . $ts["Minute"];

// do the end time

$endMinutes	=	$ts["Minute"] + $durationMinutes;

if($endMinutes > 59)
	{
	$endHour	=	(int)($tempHour + 1 + $durationHours);
	$endMinutes -=	60;
	}
else
	$endHour	=	$tempHour + $durationHours;

if($endHour > 12)
	{
	$endHour	-= 12;
	
	// flip the ampm
	if($ts["AmPm"]=="PM")
		$endAmPm	=	"AM";
	else
		$endAmPm	=	"PM";
	}
else if($endHour == 12)
	{
	// flip the ampm
	if($ts["AmPm"]=="PM")
		$endAmPm	=	"AM";
	else
		$endAmPm	=	"PM";
	}
else
	{
	$endAmPm	=	$ts["AmPm"];
	}

$eventAmPm		=	$ts["AmPm"];

$eventEndTime	=	sprintf("%d:%02d",$endHour,$endMinutes);

if($debug) print "eventDate: $eventDate<br>";
if($debug) print "eventTime: $eventTime<br>";
if($debug) print "eventDuration: $eventDuration<br>";
if($debug) print "eventEndTime: $eventEndTime<br>";
if($debug) print "endAmPm: $endAmPm<br>";
if($debug) print "eventAmPm: $eventAmPm<br>";

if($debug) print "headline: $headline<br>";
if($debug) print "subhead: $subhead<br>";
if($debug) print "copy: $copy<br>";
if($debug) print "postingDate: $postingDate<br>";
if($debug) print "posterID: $posterID<br>";

if($headline=="") 		$headline		= "&nbsp;";
if($subhead=="") 		$subhead		= "&nbsp;";
if($copy=="") 			$copy			= "&nbsp;";
if($eventDate=="") 		$eventDate		= "&nbsp;";
if($eventTime=="") 		$eventTime		= "&nbsp;";
if($postingDate=="") 	$postingDate	= "&nbsp;";
if($posterID=="") 		$posterID		= "&nbsp;";

// lose string literals
$headline	=	stripslashes($headline);
$subhead	=	stripslashes($subhead);
$copy		=	stripslashes($copy);

// read in the file
$templateFile	=	FileRead($templateFileName);

if($debug) print "BEFORE SR - templateFile: $templateFile<br>";

// set up the edit and delete links for the record

// EDIT LINK
$blArgs			=	"blItemNumber=$itemNumber&blTable=$tableName&blMode=Refresh";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs";
$editLink		=	"blog_edit.php?$args";	

// DELETE LINK
$deleteMessage	=	"<br>This will delete the posting...<br><br>$headline<br><br>";
$questArgs		=	quBuildArgs("blog_services.php","blog_edit_picker.php",$deleteMessage,"Continue ?");
$blArgs			=	"blItemNumber=$itemNumber&blMode=DeleteItem&blTable=$tableName";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs&$questArgs";
$deleteLink		=	"question_yn.php?$args";

//
$templateFile	=	str_replace("_EVENTDATE_",$eventDate,$templateFile);
$templateFile	=	str_replace("_EVENTTIME_",$eventTime,$templateFile);
$templateFile	=	str_replace("_EVENTDURATION_",$eventDuration,$templateFile);
$templateFile	=	str_replace("_EVENTENDTIME_",$eventEndTime,$templateFile);
$templateFile	=	str_replace("_EVENTENDAMPM_",$endAmPm,$templateFile);
$templateFile	=	str_replace("_EVENTAMPM_",$eventAmPm,$templateFile);

$templateFile	=	str_replace("_EDITLINK_",$editLink,$templateFile);
$templateFile	=	str_replace("_DELETELINK_",$deleteLink,$templateFile);
$templateFile	=	str_replace("_HEADLINE_",$headline,$templateFile);
$templateFile	=	str_replace("_SUBHEAD_",$subhead,$templateFile);
$templateFile	=	str_replace("_COPY_",$copy,$templateFile);
$templateFile	=	str_replace("_POSTINGDATE_",$postingDate,$templateFile);
$templateFile	=	str_replace("_POSTERID_",$posterID,$templateFile);
$templateFile	=	str_replace("_ITEMNUMBER_",$itemNumber,$templateFile);
$templateFile	=	str_replace("_MUSICFILEPATH_",$musicFilePath,$templateFile);
$templateFile	=	str_replace("_MUSICFILENAME_",$musicFileName,$templateFile);
$templateFile	=	str_replace("_MUSICFILETITLE_",$musicFileTitle,$templateFile);

if($debug) print "AFTER SR - templateFile: $templateFile<br>";

return $templateFile;
}
*/

/*
// This is prior to hacking the sort order to FIXED-LIFO as the standard LIFO behavior
function BlogGetItem($index,$tableName,$templateFileName)
{
$debug			=	0;

global $gBaseMountPoint;
global $gBlogSharedMountPoint;

if($debug) print "BlogGetItem($index,$tableName,$templateFileName)<br>";

// see what type of blog it is and set up the sort order
$blogType	=	 BlogHeaderGetType($tableName);

if($blogType=="LIFO" || $blogType=="ORDERED" || $blogType=="MUSIC")
	{
	$orderClause	=	"ORDER BY blRecordNumber DESC";
	}
else if($blogType=="CALENDAR")
	{
	$orderClause	=	"ORDER BY blEventDate";
	}
else
	return false;

$query	=	"
			SELECT * FROM
			$tableName
			WHERE
			blVisibility='SHOW'
			$orderClause
			";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

// round up the data
$headline	=	GetDatabaseResult($rs,$index,"blHeadline");
$subhead	=	GetDatabaseResult($rs,$index,"blSubHead");
$copy		=	GetDatabaseResult($rs,$index,"blCopy");

$sqlDate	=	GetDatabaseResult($rs,$index,"blEventDate");
$eventTime	=	GetDatabaseResult($rs,$index,"blEventTime"); // duration

$postingDate=	GetDatabaseResult($rs,$index,"blPostingDate");
$posterID	=	GetDatabaseResult($rs,$index,"blPosterID");
$itemNumber	=	GetDatabaseResult($rs,$index,"blRecordNumber");

// ADDING DLPATH ETC...
if($blogType == "MUSIC")
	{
	$musicFilePath				=	"$gBaseMountPoint$gBlogSharedMountPoint";
	// the fn is in the event time
	$musicFileName			=	$eventTime;

	$musicFileTitle			=	$headline;

	// build the path
	$musicFileNameAndPath	=	"$dlPath/$dlFileName";
	}
	
// break apart the date and start time
$ts			=	BreakSQLTimeStamp($sqlDate);
$d			=	explode(":",$eventTime);

$durationHours		=	$d[0];
$durationMinutes	=	$d[1];

$eventDate			=	$ts["Month"] . "/" . $ts["Day"] . "/" . $ts["Year"];

if($ts["Hour"] < 10)
	$tempHour	=	(int)$ts["Hour"];
else
	$tempHour	=	$ts["Hour"];
	

$eventTime			=	$tempHour . ":" . $ts["Minute"] . " " . $ts["AmPm"];

if($debug) print "headline: $headline<br>";
if($debug) print "subhead: $subhead<br>";
if($debug) print "copy: $copy<br>";
if($debug) print "eventDate: $eventDate<br>";
if($debug) print "eventTime: $eventTime<br>";
if($debug) print "postingDate: $postingDate<br>";
if($debug) print "posterID: $posterID<br>";

if($headline=="") 		$headline		= "&nbsp;";
if($subhead=="") 		$subhead		= "&nbsp;";
if($copy=="") 			$copy			= "&nbsp;";
if($eventDate=="") 		$eventDate		= "&nbsp;";
if($eventTime=="") 		$eventTime		= "&nbsp;";
if($postingDate=="") 	$postingDate	= "&nbsp;";
if($posterID=="") 		$posterID		= "&nbsp;";

// lose string literals
$headline	=	stripslashes($headline);
$subhead	=	stripslashes($subhead);
$copy		=	stripslashes($copy);

// read in the file
$templateFile	=	FileRead($templateFileName);

if($debug) print "BEFORE SR - templateFile: $templateFile<br>";

// set up the edit and delete links for the record

// EDIT LINK
$blArgs			=	"blItemNumber=$itemNumber&blTable=$tableName&blMode=Refresh";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs";
$editLink		=	"blog_edit.php?$args";	

// DELETE LINK
$deleteMessage	=	"<br>This will delete the posting...<br><br>$headline<br><br>";
$questArgs		=	quBuildArgs("blog_services.php","blog_edit_picker.php",$deleteMessage,"Continue ?");
$blArgs			=	"blItemNumber=$itemNumber&blMode=DeleteItem&blTable=$tableName";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs&$questArgs";
$deleteLink		=	"question_yn.php?$args";

//

$templateFile	=	str_replace("_EDITLINK_",$editLink,$templateFile);
$templateFile	=	str_replace("_DELETELINK_",$deleteLink,$templateFile);
$templateFile	=	str_replace("_HEADLINE_",$headline,$templateFile);
$templateFile	=	str_replace("_SUBHEAD_",$subhead,$templateFile);
$templateFile	=	str_replace("_COPY_",$copy,$templateFile);
$templateFile	=	str_replace("_EVENTDATE_",$eventDate,$templateFile);
$templateFile	=	str_replace("_EVENTTIME_",$eventTime,$templateFile);
$templateFile	=	str_replace("_POSTINGDATE_",$postingDate,$templateFile);
$templateFile	=	str_replace("_POSTERID_",$posterID,$templateFile);
$templateFile	=	str_replace("_ITEMNUMBER_",$itemNumber,$templateFile);
$templateFile	=	str_replace("_EVENTAMPM_",$ts["AmPm"],$templateFile);
$templateFile	=	str_replace("_MUSICFILEPATH_",$musicFilePath,$templateFile);
$templateFile	=	str_replace("_MUSICFILENAME_",$musicFileName,$templateFile);
$templateFile	=	str_replace("_MUSICFILETITLE_",$musicFileTitle,$templateFile);

if($debug) print "AFTER SR - templateFile: $templateFile<br>";

return $templateFile;
}



*/
function BreakSQLTimeStamp($timeStamp)
{
$rv	=	array();

$ts	=	str_replace(" ","-",$timeStamp);
$ts	=	str_replace(":","-",$ts);

$ts	=	explode("-",$ts);

$debug	=	0;

if($debug) print "BreakSQLTimeStamp($timeStamp)<br>";

// YYYY-MM-DD HH:MM:SS
// mil time to normal time
if($ts[3] > 12)
	{
	if($debug) print "PM<br>";

	$amPm	=	"PM";
	$hour	=	sprintf("%02d",$ts[3] - 12);
	}
else if($ts[3] == 12)
	{
	if($debug) print "NOON<br>";

	$amPm	=	"PM";
	$hour	=	sprintf("%02d",$ts[3]);
	}
else
	{
	if($debug) print "AM<br>";

	$amPm	=	"AM";
	$hour	=	sprintf("%02d",$ts[3]);
	}

$rv["Year"]				=	$ts[0];
$rv["Month"]			=	$ts[1];
$rv["Day"]				=	$ts[2];
$rv["Hour"]				=	$hour;
$rv["AmPm"]				=	$amPm;
$rv["MilitaryHour"]		=	$ts[3];
$rv["Minute"]			=	$ts[4];
$rv["Second"]			=	$ts[5];

return $rv;
}


function MakeSQLTimeStamp($month,$day,$year,$hour,$minute,$amPm)
{
$debug = 0;

if($debug) print "MakeSQLTimeStamp($month,$day,$year,$hour,$minute,$amPm)<br>";

if($amPm=="PM" && $hour != 12)
	{
	if($debug) print "PM hour: $hour<br>";
	
	$hour	=	$hour + 12;
	}
else if($amPm=="PM" && $hour == 12)
	{
	if($debug) print "NOON: $hour<br>";
	}
else
	if($debug) print "AM hour: $hour<br>";


$month	=	sprintf("%02d",$month);
$day	=	sprintf("%02d",$day);
$hour	=	sprintf("%02d",$hour);
$minute	=	sprintf("%02d",$minute);

$rv	=	"$year-$month-$day $hour:$minute:00";

return $rv;
}




/*
this has ts problems
function BreakSQLTimeStamp($timeStamp)
{
$rv	=	array();

$ts	=	str_replace(" ","-",$timeStamp);
$ts	=	str_replace(":","-",$ts);

$ts	=	explode("-",$ts);

// YYYY-MM-DD HH:MM:SS

if($ts[3] > 12)
	{
	$amPm	=	"PM";
	$hour	=	sprintf("%02d",$ts[3] - 12);
	}
else
	{
	$amPm	=	"AM";
	$hour	=	sprintf("%02d",$ts[3]);
	}

$rv["Year"]				=	$ts[0];
$rv["Month"]			=	$ts[1];
$rv["Day"]				=	$ts[2];
$rv["Hour"]				=	$hour;
$rv["AmPm"]				=	$amPm;
$rv["MilitaryHour"]		=	$ts[3];
$rv["Minute"]			=	$ts[4];
$rv["Second"]			=	$ts[5];

return $rv;
}


function MakeSQLTimeStamp($month,$day,$year,$hour,$minute,$amPm)
{
if($amPm=="PM")
	{
	$hour	=	$hour + 12;
	}

$month	=	sprintf("%02d",$month);
$day	=	sprintf("%02d",$day);
$hour	=	sprintf("%02d",$hour);
$minute	=	sprintf("%02d",$minute);

$rv	=	"$year-$month-$day $hour:$minute:00";

return $rv;
}




*/

function BlogIsModerator($userId,$tableName)
{
$debug			=	0;

if($debug) print "BlogIsModerator($userId,$tableName)<br>";

$query	=	"SELECT * FROM APPDEV_UserPermissions 
			 WHERE pwUserID='$userId'
			 AND pwPermission='$tableName'
			 AND pwModerator='1'";

if($debug) print "query: $query<br>";

if(GetNumberOfRows($query))
	return true;
	
return false;
}

// gets ALL of the items
function BlogLinkGetItem($index,$tableName,$templateFileName)
{
$debug			=	0;

if($debug) print "BlogLinkGetItem($index,$tableName,$templateFileName)<br>";

// fix the blog table to be the links table name
$linkTableName	=	BlogLinkTableName($tableName);

// for now I will order by LIFO
$orderClause	=	"ORDER BY blRecordNumber DESC";

$query	=	"
			SELECT * FROM
			$linkTableName
			WHERE 1
			$orderClause
			";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

// round up the data
$linkURL			=	GetDatabaseResult($rs,$index,"blLinkURL");
$linkURL			=	CleanURL($linkURL);

$linkName			=	GetDatabaseResult($rs,$index,"blName");
$linkDescription	=	GetDatabaseResult($rs,$index,"blDescription");
$posterID			=	GetDatabaseResult($rs,$index,"blPosterID");
$itemNumber			=	GetDatabaseResult($rs,$index,"blRecordNumber");

if($debug) print "headline: $headline<br>";
if($debug) print "subhead: $subhead<br>";
if($debug) print "copy: $copy<br>";
if($debug) print "eventDate: $eventDate<br>";
if($debug) print "eventTime: $eventTime<br>";
if($debug) print "postingDate: $postingDate<br>";
if($debug) print "posterID: $posterID<br>";

if($linkURL=="") 			$linkURL			= "&nbsp;";
if($linkName=="") 			$linkName			= "&nbsp;";
if($linkDescription=="") 	$linkDescription	= "&nbsp;";

// lose string literals
$linkName			=	stripslashes($linkName);
$linkDescription	=	stripslashes($linkDescription);

$linkDescription	=	html_entity_decode($linkDescription);

// read in the file
$templateFile	=	FileRead($templateFileName);

if($debug) print "BEFORE SR - templateFile: $templateFile<br>";

// set up the edit and delete links for the record

// EDIT LINK
$blArgs			=	"blItemNumber=$itemNumber&blTable=$tableName&blMode=Refresh";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs";
$editLink		=	"blog_link_edit.php?$args";	

// DELETE LINK
$deleteMessage	=	"<br>This will delete the link...<br><br>$linkName<br><br>";
$questArgs		=	quBuildArgs("blog_services.php","blog_link_picker.php",$deleteMessage,"Continue ?");
$blArgs			=	"blItemNumber=$itemNumber&blMode=DeleteLink&blTable=$tableName";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs&$questArgs";
$deleteLink		=	"question_yn.php?$args";

$templateFile	=	str_replace("_EDITLINK_",$editLink,$templateFile);
$templateFile	=	str_replace("_DELETELINK_",$deleteLink,$templateFile);
$templateFile	=	str_replace("_ITEMNUMBER_",$itemNumber,$templateFile);
$templateFile	=	str_replace("_LINKNAME_",$linkName,$templateFile);
$templateFile	=	str_replace("_LINKDESCRIPTION_",$linkDescription,$templateFile);
$templateFile	=	str_replace("_POSTERID_",$posterID,$templateFile);
$templateFile	=	str_replace("_LINKURL_",$linkURL,$templateFile);

if($debug) print "AFTER SR - templateFile: $templateFile<br>";

return $templateFile;
}


// Blog tables must have a BLOG_ on the head of the blog data table only...
function BlogGetNumberOfTables()
{
$debug	=	0;
/*
if($debug) print "BlogGetNumberOfTables()<br>";

$query	=	"SHOW TABLES LIKE 'APPDEV_BLOG_%'";

if($debug) print "query: $query<br>";
*/
$query	=	"SELECT blName FROM APPDEV_BlogHeaders";

$numberOfRows	= 	GetNumberOfRows($query);

if($debug) print "numberOfRows: $numberOfRows<br>";

return $numberOfRows;
}

// used in create new user to enum the blog tables
function BlogGetTableNameByIndex($index)
{
$debug			=	0;

if($debug) print "BlogGetTableNameByIndex($index)<br>";

$tableName	=	GetFieldByIndex("APPDEV_BlogHeaders","blTable",$index);

// clean my internal tag of CtGb off the name...
//$cleanTableName = BlogCleanTableName($rsTableName);

return $tableName;
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogLinkTableName($tableName);
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogLinkTableName($blogTableName)
{
// fix the blog table to be the links table name
return	str_replace("_BLOG_","_LINKS_",$blogTableName);
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogLinkDeleteItem($tableName,$itemNumber)
// ---------------------------------------------------------------------------------------------------------------------------------------


function BlogLinkDeleteItem($tableName,$itemNumber)
{
OpenDatabase();

// fix the blog table to be the links table name
$linkTableName	=	BlogLinkTableName($tableName);

$query 			=	"DELETE FROM $linkTableName WHERE blRecordNumber=" . "'" . $itemNumber . "'"; 	

$rv				=	QueryDatabase($query);

if (!$rv) 
	{
	print 'Database Error: ' . mysql_error() . "<br>\n" . 'Query: ' . $query . "<br>";
	}
	
if($debug) print "rv: $rv<br>";

CloseDatabase();

return $rv;
}


// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogLinkGetPickerItem($index,$tableName,$posterID,$password)
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogLinkGetPickerItem($index,$tableName,$posterID,$password)
{
$debug = 0;

if($debug) print "BlogLinkGetPickerItem($index,$tableName,$posterID,$password)<br>";

// fix the blog table to be the links table name
$linkTableName	=	BlogLinkTableName($tableName);

// for now I will order by LIFO
$orderClause	=	"ORDER BY blRecordNumber DESC";

if(UserIsAdmin($posterID) || UserIsSuper($posterID))
	{
	$whereClause	=	" 1
						";
	}
else
	$whereClause	=	"blPosterID='$posterID'
						";

$query	=	"
			SELECT * FROM
			$linkTableName
			WHERE
			$whereClause
			$orderClause
			";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

// round up the data
$linkURL			=	GetDatabaseResult($rs,$index,"blLinkURL");
$linkURL			=	CleanURL($linkURL);

$linkName			=	GetDatabaseResult($rs,$index,"blName");
$linkDescription	=	GetDatabaseResult($rs,$index,"blDescription");
$pid				=	GetDatabaseResult($rs,$index,"blPosterID");
$itemNumber			=	GetDatabaseResult($rs,$index,"blRecordNumber");

$linkName			=	stripslashes($linkName);
$linkDescription	=	stripslashes($linkDescription);

if($debug) print "headline: $headline<br>";
if($debug) print "subhead: $subhead<br>";
if($debug) print "copy: $copy<br>";
if($debug) print "eventDate: $eventDate<br>";
if($debug) print "eventTime: $eventTime<br>";
if($debug) print "postingDate: $postingDate<br>";
if($debug) print "posterID: $posterID<br>";

if($linkURL=="") 			$linkURL			= "&nbsp;";
if($linkName=="") 			$linkName			= "&nbsp;";
if($linkDescription=="") 	$linkDescription	= "&nbsp;";

// read in the file
$templateFile	=	FileRead("blog_template_link_picker.html");

if($debug) print "BEFORE SR - templateFile: $templateFile<br>";

// set up the edit and delete links for the record

// EDIT LINK
$blArgs			=	"blItemNumber=$itemNumber&blTable=$tableName&blMode=Refresh";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs";
$editLink		=	"blog_link_edit.php?$args";	

// DELETE LINK
$deleteMessage	=	"Delete Link";
$questArgs		=	quBuildArgs("blog_services.php","blog_link_picker.php",$deleteMessage,"$linkName?");
$blArgs			=	"blItemNumber=$itemNumber&blMode=DeleteLink&blTable=$tableName";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs&$questArgs";
$deleteLink		=	"question_yn.php?$args";

if($debug) print "editLink: $editLink<br>";
if($debug) print "deleteLink: $deleteLink<br>";

$linkDescription	=	html_entity_decode($linkDescription);
$linkDescription	=	stripslashes($linkDescription);

$templateFile	=	str_replace("_EDITLINK_",$editLink,$templateFile);
$templateFile	=	str_replace("_DELETELINK_",$deleteLink,$templateFile);
$templateFile	=	str_replace("_LINKNAME_",$linkName,$templateFile);
$templateFile	=	str_replace("_LINKDESCRIPTION_",$linkDescription,$templateFile);
$templateFile	=	str_replace("_POSTERID_",$pid,$templateFile);
$templateFile	=	str_replace("_LINKURL_",$linkURL,$templateFile);

if($debug) print "AFTER SR - templateFile: $templateFile<br>";

return $templateFile;
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogGetNumberOfLinks($table,$posterID)
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogGetNumberOfLinks($tableName,$posterID="")
{
// fix the blog table to be the links table name
$linkTableName	=	BlogLinkTableName($tableName);

if($posterID=="")
	{
	$whereClause	=	" 1
						";
	}
else
	{
	if(UserIsAdmin($posterID) || UserIsSuper($posterID))
		{
		$whereClause	=	" 1
							";
		}
	else
		$whereClause	=	" blPosterID='$posterID'
							";
	}

$query	=	"SELECT * FROM $linkTableName WHERE $whereClause";

return GetNumberOfRows($query);
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogUpdateItem($table,$headline,$subHead,$copy,$itemType,$eventDate,$eventTime,$itemNumber)
//
// Returns true or false
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogLinkUpdateItem($tableName,$linkURL,$linkName,$linkDescription,$itemNumber)
{
$debug			=	0;
$writeEnabled	=	true;

if($debug) print "<hr>BlogLinkUpdateItem($tableName,$linkURL,$linkName,$linkDescription,$itemNumber)<br>";

// fix the blog table to be the links table name
$linkTableName	=	BlogLinkTableName($tableName);

if($debug) print "linkTableName: $linkTableName<br>";

// convert cr to <br>
$linkDescription		=	str_replace("\r\n","<br>",$linkDescription);

if($debug) print "Post Translation - linkDescription: $linkDescription<br>";

OpenDatabase();

$query 		= 	sprintf("UPDATE 
						 %s 
						 SET
						 blLinkURL='%s',
						 blName='%s',
						 blDescription='%s'
						 WHERE
						 blRecordNumber='%s'",
						mysql_real_escape_string($linkTableName),	
						mysql_real_escape_string($linkURL),	
						mysql_real_escape_string($linkName),
						mysql_real_escape_string($linkDescription),
						mysql_real_escape_string($itemNumber)
						);

if($debug) print "query: $query<br>";

// if write disabled fake success
if($writeEnabled)
	$rv			=	QueryDatabase($query);
else
	$rv			=	true; // fake success
	
if (!$rv) 
	{
	print 'Database Error: ' . mysql_error() . "<br>\n" . 'Query: ' . $query . "<br>";
	}
	
if($debug) print "rv: $rv<br>";

CloseDatabase();

return $rv;
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogLinkRefreshGlobals($itemNumber,$tableName)
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogLinkRefreshGlobals($itemNumber,$tableName)
{
$debug		=	0;

global $blLinkURL;
global $blSiteName;
global $blLinkDescription;

// fix the blog table to be the links table name
$linkTableName	=	BlogLinkTableName($tableName);

// round up the data
$blLinkURL			=	GetFieldByComparison($linkTableName,"blLinkURL","blRecordNumber",$itemNumber);
$blSiteName			=	GetFieldByComparison($linkTableName,"blName","blRecordNumber",$itemNumber);
$blLinkDescription	=	GetFieldByComparison($linkTableName,"blDescription","blRecordNumber",$itemNumber);

// replace the br's with \r\n's
$blLinkDescription	=	str_replace("<br>","\r\n",$blLinkDescription);

if($debug) print "<hr>";
if($debug) print "BlogLinkRefreshGlobals($itemNumber,$tableName)<br><br>";
if($debug) print "blLinkURL:  $blLinkURL<br>";
if($debug) print "blLinkDescription:  $blLinkDescription<br>";
if($debug) print "<hr>";
}


// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogLinkInsertItem($tableName,$linkURL,$linkName,$linkDescription,$posterID)
// ---------------------------------------------------------------------------------------------------------------------------------------
function BlogLinkInsertItem($tableName,$linkURL,$linkName,$linkDescription,$posterID)
{
$debug = 0;
$writeEnabled	=	true;

if($debug) print "BlogLinkInsertItem($tableName,$linkURL,$linkName,$linkDescription,$posterID)<br>";

// fix the blog table to be the links table name
$linkTableName	=	BlogLinkTableName($tableName);

if($debug) print "linkTableName: $linkTableName<br>";

// clean it off if possible...
$linkURL	=	str_replace("http://","",$linkURL);

OpenDatabase();

$query 		= 	sprintf("INSERT INTO 
						 %s 
						 (blLinkURL,blName,blDescription,blPosterID) 
						 VALUES 
						 ( '%s' , '%s' , '%s' , '%s'  )",
						mysql_real_escape_string($linkTableName),	
						mysql_real_escape_string($linkURL),	
						mysql_real_escape_string($linkName),
						mysql_real_escape_string($linkDescription),
						mysql_real_escape_string($posterID)
						);

if($debug) print "query: $query<br>";

// if write disabled fake success
$rv			=	QueryDatabase($query);
	
if (!$rv) 
	{
	print 'Database Error: ' . mysql_error() . "<br>\n" . 'Query: ' . $query . "<br>";
	}
else
	{
	// get the rec no of the last operation
	$rv			=	mysql_insert_id();
	}
	
if($debug) print "rv: $rv<br>";

CloseDatabase();

return $rv;
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogLinkTableExists($blogTableName)
//
// Returns: false if not exist
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogLinkTableExists($blogTableName)
{
$debug = 0;

if($debug) print "BlogLinkTableExists($blogTableName)<br>";

// fix the blog table to be the links table name
$linkTableName	=	BlogLinkTableName($blogTableName);

if($debug) print "linkTableName: $linkTableName<br>";

$query	=	"SHOW TABLES LIKE '$linkTableName'";

if($debug) print "query: $query<br>";

return GetNumberOfRows($query);
}




// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogDeleteItem($tableName,$itemNumber)
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogDeleteItem($tableName,$itemNumber)
{
OpenDatabase();

$query 			=	"DELETE FROM $tableName WHERE blRecordNumber=" . "'" . $itemNumber . "'"; 	

$rv				=	1;

$rv				=	QueryDatabase($query);

if (!$rv) 
	{
	print 'Database Error: ' . mysql_error() . "<br>\n" . 'Query: ' . $query . "<br>";
	}
	
if($debug) print "rv: $rv<br>";

CloseDatabase();

return $rv;
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogRefreshGlobals($itemNumber,$tableName)
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogRefreshGlobals($itemNumber,$tableName)
{
$debug		=	0;


global $blHeadline;
global $blSubHead;
global $blCopy;
global $blItemType;

global $blEventDate;
global $blEventTime;
global $blMonth;
global $blDay;
global $blYear;
global $blEventHour;
global $blEventMinute;
global $blEventAmPm;
global $blDurationHour;
global $blDurationMinute;

// round up the data
$blHeadline		=	GetFieldByComparison($tableName,"blHeadline","blRecordNumber",$itemNumber);
$blSubHead		=	GetFieldByComparison($tableName,"blSubHead","blRecordNumber",$itemNumber);
$blCopy			=	GetFieldByComparison($tableName,"blCopy","blRecordNumber",$itemNumber);
$blItemType		=	GetFieldByComparison($tableName,"blItemType","blRecordNumber",$itemNumber);

$blEventDate	=	GetFieldByComparison($tableName,"blEventDate","blRecordNumber",$itemNumber); // timedate
$blEventTime	=	GetFieldByComparison($tableName,"blEventTime","blRecordNumber",$itemNumber); // duration

$ts	=	BreakSQLTimeStamp($blEventDate);

$blMonth		=	$ts["Month"];
$blDay			=	$ts["Day"];
$blYear			=	$ts["Year"];
$blEventHour	=	$ts["Hour"];
$blEventMinute	=	$ts["Minute"];
$blEventAmPm	=	$ts["AmPm"];


// FUBAR need to add duration stuff

// if the item is plain text treat the br's I pegged it with...
if($blItemType=="TEXT")
	{
	if($debug) print "TEXT Item - br stripped<br>";

	$blCopy	=	str_replace("<br>","\r\n",$blCopy);
	}
	
if($debug) print "<hr>";
if($debug) print "BlogRefreshGlobals($itemNumber,$tableName)<br><br>";
if($debug) print "blHeadline:  $blHeadline<br>";
if($debug) print "blSubHead:  $blSubHead<br>";
if($debug) print "blCopy:  $blCopy<br>";
if($debug) print "blItemType:  $blItemType<br>";
if($debug) print "blEventDate:  $blEventDate<br>";
if($debug) print "blEventTime:  $blEventTime<br>";

if($debug) print "blMonth:  $blMonth<br>";
if($debug) print "blDay:  $blDay<br>";
if($debug) print "blYear:  $blYear<br>";
if($debug) print "blEventHour:  $blEventHour<br>";
if($debug) print "blEventMinute:  $blEventMinute<br>";
if($debug) print "blEventAmPm:  $blEventAmPm<br>";
if($debug) print "<hr>";




if($debug) print "<hr>";
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogGetNumberOfPosts($table,$posterID)
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogGetNumberOfPosts($table,$posterID="")
{
$debug			=	0;
$isModerator	=	false;

if($debug) print "BlogGetNumberOfPosts($table,$posterID)<br>";

if(BlogIsModerator($posterID,$table)==true)
	{
	$isModerator	=	true;
	}

if($posterID=="")
	{
	$whereClause	=	" 1
						";
	}
else
	{
	if(UserIsAdmin($posterID) || UserIsSuper($posterID) || $isModerator==true)
		{
		$whereClause	=	" 1
							";
		}
	else
		$whereClause	=	" blPosterID='$posterID'
							";
	}

if($debug) print "whereClause:  $whereClause<br>";

$query	=	"SELECT * FROM $table WHERE $whereClause";

if($debug) print "query:  $query<br>";

return GetNumberOfRows($query);
}



// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogHeaderGetType($tableName)
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogHeaderGetType($tableName)
{
return GetFieldByComparison("APPDEV_BlogHeaders","blBlogType","blTable",$tableName);
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogHeaderGetName($tableName)
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogHeaderGetName($tableName)
{
return GetFieldByComparison("APPDEV_BlogHeaders","blName","blTable",$tableName);
}

function BlogHeaderGetDescription($tableName)
{
return GetFieldByComparison("APPDEV_BlogHeaders","blDescription","blTable",$tableName);
}


// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogGetPickerItem($index,$tableName,$posterID,$password)
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogGetPickerItem($index,$tableName,$posterID,$password)
{
$debug 			= 	0;
$isModerator	=	false;

if($debug) print "BlogGetPickerItem($index,$tableName,$posterID,$password)<br>";

if(BlogIsModerator($posterID,$tableName)==true)
	{
	$isModerator	=	true;
	}

// see what type of blog it is and set up the sort order
$blogType	=	 BlogHeaderGetType($tableName);

if($blogType=="LIFO" || $blogType=="ORDERED" || $blogType=="MUSIC")
	{
	$orderClause		=	"ORDER BY blRecordNumber DESC";
	$templateFileName	=	"../templates/blog_t_picker_lifo.html";
	}
else if($blogType=="CALENDAR")
	{
	$orderClause		=	"ORDER BY blEventDate";
	$templateFileName	=	"../templates/blog_t_picker_calendar.html";
	}
else
	return false;

if(UserIsAdmin($posterID) || UserIsSuper($posterID) || $isModerator==true)
	{
	$whereClause	=	" 1
						";
	}
else
	$whereClause	=	"blPosterID='$posterID'
						";

$query	=	"
			SELECT * FROM
			$tableName
			WHERE
			$whereClause
			$orderClause
			";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

// GetDatabaseResult($rs,$index,$fieldName)


// blog_template_preview.html
// round up the data
$headline	=	GetDatabaseResult($rs,$index,"blHeadline");
$subhead	=	GetDatabaseResult($rs,$index,"blSubHead");
$copy		=	GetDatabaseResult($rs,$index,"blCopy");
$eventDate	=	GetDatabaseResult($rs,$index,"blEventDate");
$eventTime	=	GetDatabaseResult($rs,$index,"blEventTime");
$postingDate=	GetDatabaseResult($rs,$index,"blPostingDate");
$pid		=	GetDatabaseResult($rs,$index,"blPosterID");
$itemNumber	=	GetDatabaseResult($rs,$index,"blRecordNumber");

$copy		=	stripslashes($copy);
$headline	=	stripslashes($headline);
$subhead	=	stripslashes($subhead);

if($debug) print "headline: $headline<br>";
if($debug) print "subhead: $subhead<br>";
if($debug) print "copy: $copy<br>";
if($debug) print "eventDate: $eventDate<br>";
if($debug) print "eventTime: $eventTime<br>";
if($debug) print "postingDate: $postingDate<br>";
if($debug) print "posterID: $posterID<br>";

if($headline=="") 		$headline		= "&nbsp;";
if($subhead=="") 		$subhead		= "&nbsp;";
if($copy=="") 			$copy			= "&nbsp;";
if($eventDate=="") 		$eventDate		= "&nbsp;";
if($eventTime=="") 		$eventTime		= "&nbsp;";
if($postingDate=="") 	$postingDate	= "&nbsp;";
if($posterID=="") 		$posterID		= "&nbsp;";

// read in the file
$templateFile	=	FileRead($templateFileName);

if($debug) print "BEFORE SR - templateFile: $templateFile<br>";

// set up the edit and delete links for the record

// EDIT LINK
$blArgs			=	"blItemNumber=$itemNumber&blTable=$tableName&blMode=Refresh";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs";
$editLink		=	"blog_edit.php?$args";	

// DELETE LINK
$deleteMessage	=	"<br>This will delete the posting...<br><br>$headline<br><br>";
$questArgs		=	quBuildArgs("blog_services.php","blog_edit_picker.php",$deleteMessage,"Continue ?");
$blArgs			=	"blItemNumber=$itemNumber&blMode=DeleteItem&blTable=$tableName";
$args			=	"SystemUserID=$posterID&SystemPassword=$password&$blArgs&$questArgs";
$deleteLink		=	"question_yn.php?$args";

if($debug) print "editLink: $editLink<br>";
if($debug) print "deleteLink: $deleteLink<br>";

$templateFile	=	str_replace("_EDITLINK_",$editLink,$templateFile);
$templateFile	=	str_replace("_DELETELINK_",$deleteLink,$templateFile);
$templateFile	=	str_replace("_HEADLINE_",$headline,$templateFile);
$templateFile	=	str_replace("_SUBHEAD_",$subhead,$templateFile);
$templateFile	=	str_replace("_COPY_",$copy,$templateFile);
$templateFile	=	str_replace("_EVENTDATE_",$eventDate,$templateFile);
$templateFile	=	str_replace("_EVENTTIME_",$eventTime,$templateFile);
$templateFile	=	str_replace("_POSTINGDATE_",$postingDate,$templateFile);
$templateFile	=	str_replace("_POSTERID_",$pid,$templateFile);
$templateFile	=	str_replace("_ITEMNUMBER_",$itemNumber,$templateFile);

if($debug) print "AFTER SR - templateFile: $templateFile<br>";

return $templateFile;
}


// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogInsertItem($table,$headline,$subHead,$copy,$itemType,$posterID,$eventDate,$eventTime)
//
// Returns: false or the auto_inc record number of the inserted item
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogInsertItem($table,$headline,$subHead,$copy,$itemType,$posterID,$eventDate,$eventTime,$fileName,$path)
{
$debug	=	0;
$writeEnabled	=	true;

if($debug) print "BlogInsertItem($table,$headline,$subHead,$copy,$itemType,$posterID,$eventDate,$eventTime)<br>";

// if it is plain text convert cr to <br>
if($itemType=="TEXT")
	{
	if($debug) "Item is PlainText<br>";
	$copy 		= 	implode("<br>", preg_split("/\r?\n/", $copy));
	}

// see what type of blog it is and set up the sort order
$blogType	=	 BlogHeaderGetType($table);

if($debug) print "blogType: $blogType<br>";

if($blogType != "ORDERED")
	{
	$sortType	=	"LIFO";
	}
else
	{
	$sortType	=	"RANDOM";
	}
if($debug) print "sortType: $sortType<br>";


OpenDatabase();

$query 		= 	sprintf("INSERT INTO 
						 %s 
						 (blHeadline,blSubHead,blCopy,blPosterID,blEventDate,blEventTime,blItemType,blFileName,blPath,blSortType) 
						 VALUES 
						 ( '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s', '%s' , '%s')",
						mysql_real_escape_string($table),	
						mysql_real_escape_string($headline),	
						mysql_real_escape_string($subHead),
						mysql_real_escape_string($copy),
						mysql_real_escape_string($posterID),
						mysql_real_escape_string($eventDate),
						mysql_real_escape_string($eventTime),
						mysql_real_escape_string($itemType),
						mysql_real_escape_string($fileName),
						mysql_real_escape_string($path),
						mysql_real_escape_string($sortType)
						);

if($debug) print "copy: $copy<br>";
if($debug) print "query: $query<br>";

// if write disabled fake success
$rv			=	QueryDatabase($query);
	
if (!$rv) 
	{
	print 'Database Error: ' . mysql_error() . "<br>\n" . 'Query: ' . $query . "<br>";
	}
else
	{
	// get the rec no of the last operation
	$rv			=	mysql_insert_id();
	}
	
if($debug) print "rv: $rv<br>";

CloseDatabase();

return $rv;
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function BlogUpdateItem($table,$headline,$subHead,$copy,$itemType,$eventDate,$eventTime,$itemNumber)
//
// Returns true or false
// ---------------------------------------------------------------------------------------------------------------------------------------

function BlogUpdateItem($table,$headline,$subHead,$copy,$itemType,$eventDate,$eventTime,$itemNumber)
{
$debug			=	0;
$writeEnabled	=	true;

if($debug) print "BlogUpdateItem($table,$headline,$subHead,$copy,$itemType,$eventDate,$eventTime,$itemNumber)<br>";

// if it is plain text convert cr to <br>
if($itemType=="TEXT")
	{
	if($debug) "Item is PlainText<br>";
	$copy 		= 	implode("<br>", preg_split("/\r?\n/", $copy));
	}


if($debug) print "Post Translation - copy: $copy<br>";


OpenDatabase();

// dig a hole

if($eventTime=="")
	{
	$query 		= 	sprintf("UPDATE 
							 %s 
							 SET
							 blHeadline='%s',
							 blSubHead='%s',
							 blCopy='%s',
							 blEventDate='%s',
							 blItemType='%s'
							 WHERE
							 blRecordNumber='%s'",
							mysql_real_escape_string($table),	
							mysql_real_escape_string($headline),	
							mysql_real_escape_string($subHead),
							mysql_real_escape_string($copy),
							mysql_real_escape_string($eventDate),
							mysql_real_escape_string($itemType),
							mysql_real_escape_string($itemNumber)
							);
	}
else
	{
	$query 		= 	sprintf("UPDATE 
							 %s 
							 SET
							 blHeadline='%s',
							 blSubHead='%s',
							 blCopy='%s',
							 blEventDate='%s',
							 blEventTime='%s',
							 blItemType='%s'
							 WHERE
							 blRecordNumber='%s'",
							mysql_real_escape_string($table),	
							mysql_real_escape_string($headline),	
							mysql_real_escape_string($subHead),
							mysql_real_escape_string($copy),
							mysql_real_escape_string($eventDate),
							mysql_real_escape_string($eventTime),
							mysql_real_escape_string($itemType),
							mysql_real_escape_string($itemNumber)
							);
	}
	


if($debug) print "query: $query<br>";

// if write disabled fake success
if($writeEnabled)
	$rv			=	QueryDatabase($query);
else
	$rv			=	true; // fake success
	
if (!$rv) 
	{
	print 'Database Error: ' . mysql_error() . "<br>\n" . 'Query: ' . $query . "<br>";
	}
	
if($debug) print "rv: $rv<br>";

CloseDatabase();

return $rv;
}


function CreateBlogHeaderRecord($title,$description,$tableName,$type)
{
$debug = 0;

if($debug) print "CreateBlogHeaderRecord($title,$description,$tableName,$type)<br>";

OpenDatabase();

$query 		= 	sprintf("INSERT INTO 
						 APPDEV_BlogHeaders
						 (blName, blDescription, blTable, blBlogType, blRecordNumber) 				
						 VALUES 
						 ('%s','%s','%s','%s','')",
						mysql_real_escape_string($title),	
						mysql_real_escape_string($description),	
						mysql_real_escape_string($tableName),	
						mysql_real_escape_string($type)	
						);

if($debug) print "query: $query<br>";

$rv	=	QueryDatabase($query);

if(!$rv) print "Error in CreateBlogHeaderRecord($tableName) SQL ERROR: " . mysql_error();

CloseDatabase();

return $rv;
}

// expects a fully decorated blog table name
function CreateBlogTable($tableName)
{
$blogTableDef	=	FileRead("blog_table_definition.sql.txt");

if($blogTableDef=="")
	{
	print "ERROR: Unable to load table def in CreateBlogTable($tableName)<br>";
	return;
	}

// fix up the table name
$blogTableDef	=	str_replace("__TABLENAME__",$tableName,$blogTableDef);		
	
OpenDatabase();

$rv	=	QueryDatabase($blogTableDef);

if(!$rv) print "Error in CreateBlogTable($tableName) SQL ERROR: " . mysql_error();

CloseDatabase();

return $rv;
}


function CreateBlogLinksTable($tableName)
{
$blogTableDef	=	FileRead("blog_link_definition.sql.txt");

if($blogTableDef=="")
	{
	print "ERROR: Unable to load table def in CreateBlogLinksTable($tableName)<br>";
	return;
	}

// fix up the table name
$blogTableDef	=	str_replace("__TABLENAME__",$tableName,$blogTableDef);		
	
OpenDatabase();

$rv	=	QueryDatabase($blogTableDef);

if(!$rv) print "Error in CreateBlogLinksTable($tableName) SQL ERROR: " . mysql_error();

CloseDatabase();

return $rv;
}

// ----------------------------------------------------------------------------------------------
// BlogContinueTagExists($posting)
//
// Returns true if _CONTINUED_ exists in the posting
// ----------------------------------------------------------------------------------------------

function BlogContinueTagExists($posting)
{
$rv	=	strstr  ($posting,kContinuedTag);

if($rv===false)
	return false;

return true;
}

// ----------------------------------------------------------------------------------------------
// BlogContinueTagExists($posting)
//
// Returns The complete posting with the _CONTINUED_tag removed
// ----------------------------------------------------------------------------------------------

function StripContinueTag($posting)
{
// this covers all bases...
// something fishey here the variable <p> block remains so 
// I devolved it to three dot journalism
//
// Lede
// ...
// and so on...

/*
$p	= str_replace('<p align="left">' . kContinuedTag . "&nbsp;</p>","",$posting);
$p	= str_replace("<p>" . kContinuedTag . "</p>","",$posting);
$p	= str_replace("<p>" . kContinuedTag . "&nbsp;</p>","",$posting);
$p	= str_replace("<p>&nbsp;" . kContinuedTag . "</p>","",$posting);
$p	= str_replace(kContinuedTag,"",$p);
*/

$p	= str_replace(kContinuedTag,"...",$posting);

return $p;
}

// ----------------------------------------------------------------------------------------------
// BlogGetLede($posting)
//
// Returns: The portion of the posting before the _CONTINUED_ tag
// ----------------------------------------------------------------------------------------------

function BlogGetLede($posting)
{
$p	=	explode(kContinuedTag,$posting);

return $p[0];
}





?>
