<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// chron_lib.php
//
// Gives some flexible cron process functions
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------


// ---------------------------------------------------------------------------------------------------------------------------------------
// function ChronUpdateProcess($pid)
//
// Marks the given process as having been run today
// ---------------------------------------------------------------------------------------------------------------------------------------

function ChronUpdateProcess($pid)
{
$debug	=	0;
$debugLog	=	0;

if($debug) print "ChronUpdateProcess($pid)<br>";

DebugLog("ChronUpdateProcess($pid)",$debugLog,__LINE__,__FILE__,__FUNCTION__);

// if this returs an empty set the process has run today
$query	=	"
			UPDATE APPDEV_ChronProcess
			Set UpdateDate=NOW()
			WHERE 
			ProcessID='$pid';
			";

if($debug) print "query: $query<br>";

DebugLog("query: $query<br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

OpenDatabase();

$rv	=	QueryDatabase($query);

if($debug) print "rv: $rv<br>";

DebugLog("rv: $rv<br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

CloseDatabase();

return $rv;
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function ChronProcessHasRun($pid)
//
// Returns true if the given process has run today
// ---------------------------------------------------------------------------------------------------------------------------------------

function ChronProcessHasRun($pid)
{
$debug	=	0;
$debugLog	=	0;

if($debug) print "ChronProcessHasRun($pid)<br>";

DebugLog("ChronProcessHasRun($pid)",$debugLog,__LINE__,__FILE__,__FUNCTION__);

// if this returs an empty set the process has run today
$query	=	"
			SELECT * FROM APPDEV_ChronProcess
			WHERE 
			TO_DAYS(UpdateDate) < 
			TO_DAYS(CURDATE());
			";

if($debug) print "query: $query<br>";

DebugLog("query: $query<br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

$numberOfRows	=	GetNumberOfRows($query);

if($debug) print "numberOfRows: $numberOfRows<br>";

DebugLog("numberOfRows: $numberOfRows<br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

if($numberOfRows)
	{
	if($debug) print "$pid has NOT already run...<br>";

	DebugLog("$pid has NOT already run...<br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

	return false;
	}
else
	{
	if($debug) print "$pid has already run...<br>";
	
	DebugLog("$pid has already run...<br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);
	
	return true;
	}
}

function ChronMaintainCalendarBlogs($gracePeriod)
{
$debug	=	0;
$debugLog	=	0;

if($debug) print "BlogMaintainCalendar($gracePeriod)<br>";

if(ChronProcessHasRun("CALENDAR"))
	{
	if($debug) print "PROCESS HAS ALREADY RUN<br>";
	//DebugLog("Chron Process CALENDAR HAS ALREADY RUN",$debugLog,__LINE__,__FILE__,__FUNCTION__);
	return true;
	}
else
	{
	DebugLog("Chron Process CALENDAR will run...",$debugLog,__LINE__,__FILE__,__FUNCTION__);
	}
	
// get all blog tables that are calendar type
$tableNamesQuery	=	"
			SELECT * FROM APPDEV_BlogHeaders
			WHERE 
			blBlogType='CALENDAR';
			";

if($debug) print "tableNamesQuery: $tableNamesQuery<br>";

DebugLog("Chron Process CALENDAR - tableNamesQuery: $tableNamesQuery",$debugLog,__LINE__,__FILE__,__FUNCTION__);

$numberOfRows	=	GetNumberOfRows($tableNamesQuery);

DebugLog("Chron Process CALENDAR - numberOfRows: $numberOfRows",$debugLog,__LINE__,__FILE__,__FUNCTION__);

if($debug) print "numberOfRows: $numberOfRows<br>";

OpenDatabase();

$tableNamesRs	=	QueryDatabase($tableNamesQuery);

CloseDatabase();

// iterate the calendar blogs
for($i=0;$i<$numberOfRows;$i++)
	{
	$tableName	=	GetDatabaseResult($tableNamesRs,$i,"blTable");

	if($debug) print "tableName: $tableName<br>";

	$numDeleteQuery	=	"SELECT * FROM $tableName 
						WHERE 
						TO_DAYS(blEventDate) < 
						(TO_DAYS(CURDATE()) - $gracePeriod)";

	DebugLog("numDeleteQuery: $numDeleteQuery<br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

	$n	=	GetNumberOfRows($numDeleteQuery);

	if($debug) print "numDeleteQuery: $numDeleteQuery<br>";
	if($debug) print "N rows will be deleted: $n<br>";

	DebugLog("Chron Process CALENDAR - N rows will be deleted: $n<br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

	$deleteQuery	=	"DELETE FROM $tableName 
						WHERE 
						TO_DAYS(blEventDate) < 
						(TO_DAYS(CURDATE()) - $gracePeriod)";

	if($debug) print "deleteQuery: $deleteQuery<br>";

	DebugLog("Chron Process CALENDAR - deleteQuery: $deleteQuery",$debugLog,__LINE__,__FILE__,__FUNCTION__);

	OpenDatabase();

	$rv	=	QueryDatabase($deleteQuery);

	CloseDatabase();
	
	if(!$rv)
		{
		DebugLog("Chron Process CALENDAR - Delete Query FAIL",$debugLog,__LINE__,__FILE__,__FUNCTION__);
		break;
		}
	}


// if all went well set the flag so it wont happen again today
if($rv)
	{
	DebugLog("Chron Process CALENDAR - SUCCESS",$debugLog,__LINE__,__FILE__,__FUNCTION__);
	ChronUpdateProcess("CALENDAR");
	}
else
	{
	$message	=	mysql_error();	
	DebugLog("Chron Process CALENDAR - FAIL: $message",$debugLog,__LINE__,__FILE__,__FUNCTION__);
	}


DebugLog("Chron Process CALENDAR - RETURNING: $rv",$debugLog,__LINE__,__FILE__,__FUNCTION__);

return $rv;
}
?>
