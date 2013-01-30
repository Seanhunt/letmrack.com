<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// counter_lib.php
//
// Simple counter for page views
// 
// Revision:	071806	File originated
//				080906 Added prior counting 
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

function Counter($pageFileName,$showOrHide)
{
if($showOrHide==true)
	CounterPrint($pageFileName);
else
	CounterHidden($pageFileName);
}


// --------------------------------------------------------------------------------------------------------
// function CounterPrint($pageFileName)
//
// Manages a counter and prints the count
// --------------------------------------------------------------------------------------------------------

function CounterPrint($pageFileName)
{
$debug		=	0;

if($debug) print "CounterPrint($pageFileName)<br>";

CounterInitPageName($pageFileName);

CounterIncrement($pageFileName);

$count	=	CounterGetValue($pageFileName);

print $count;
}

// --------------------------------------------------------------------------------------------------------
// function CounterHidden($pageFileName)
//
// Manages a counter but does not print the count
// --------------------------------------------------------------------------------------------------------

function CounterHidden($pageFileName)
{
$debug		=	0;

if($debug) print "CounterHidden($pageFileName)<br>";

CounterInitPageName($pageFileName);

CounterIncrement($pageFileName);
}

// --------------------------------------------------------------------------------------------------------
// function CounterSetPrior()
//
// Sets the prior count registers, used in viewing the counters in counter_view.php
// --------------------------------------------------------------------------------------------------------

function CounterSetPrior()
{

$debug	=	0;

if($debug) print "CounterSetPrior()<br>";

$query	= "SELECT * FROM APPDEV_SimpleCounters";

$numberOfRows	=	GetNumberOfRows($query);

OpenDatabase();

$rs				=	QueryDatabase($query);

for($i=0;$i<$numberOfRows;$i++)
	{
	$pageFileName	=	GetDatabaseResult($rs,$i,"PageFileName");		
	
	$update			=	"UPDATE APPDEV_SimpleCounters SET PriorCount=Count WHERE PageFileName='$pageFileName'";

	if($debug) print "update: $update<br>";
	
	// do update and show errors
	ShowDatabaseError(QueryDatabase($update));
	}
	

CloseDatabase();

}
// GetDatabaseResult($rs,$index,$fieldName)


// --------------------------------------------------------------------------------------------------------
// service routines, don't call directly
// --------------------------------------------------------------------------------------------------------

function CounterGetValue($pageFileName)
{
$debug		=	0;

if($debug) print "CounterGetValue($pageFileName)<br>";

$count	=	GetFieldByComparison("APPDEV_SimpleCounters","Count","PageFileName",$pageFileName);

return $count;
}


function CounterInitPageName($pageFileName)
{
$debug		=	0;
$rv			=	1;

if($debug) print "CounterInitPageName($pageFileName)<br>";

$pageExistsQuery	=	"SELECT * FROM APPDEV_SimpleCounters WHERE PageFileName='$pageFileName'";

if($debug) print "pageExistsQuery: $pageExistsQuery<br>";
	
// does this page row exist ?
if(GetNumberOfRows($pageExistsQuery)==0)
	{
	// does not exist, inset a row
	if($debug) print "PAGE NAME CREATED<br>";

	$query		=	"INSERT INTO APPDEV_SimpleCounters VALUES ('$pageFileName',0,0)";

	if($debug) print "query: $query<br>";
	
	OpenDatabase();
	
	$rv	=	QueryDatabase($query);
	
	if(!$rv) print  "ERROR:" . mysql_error() . "<br>File: " . __FILE__ . "<br>Line: " . __LINE__;
	
	CloseDatabase();
	}
else
	if($debug) print "PAGE NAME EXISTS<br>";

return $rv;
}

function CounterIncrement($pageFileName)
{
$debug		=	0;

if($debug) print "CounterIncrement($pageFileName)<br>";

$query		=	"UPDATE APPDEV_SimpleCounters SET Count=Count+1 WHERE PageFileName='$pageFileName'";

if($debug) print "query: $query<br>";

OpenDatabase();

$rv	=	QueryDatabase($query);

if(!$rv) print  "ERROR:" . mysql_error() . "<br>File: " . __FILE__ . "<br>Line: " . __LINE__ . "<br>";

CloseDatabase();

return $rv;
}


?>

