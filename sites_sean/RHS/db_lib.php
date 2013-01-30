<?php
// ----------------------------------------------------------------------------------------------------------------------
// db_lib.php
//
// Contains common db functions.
// 
// Depends: 	../local_info.php
//
// Revision:	01--06	Added FixUpQuery that will toggle a table pre-pension to TEST_
// 				020506	Added debug instrumentation to QueryDatabase
// 				020506 Changed the global symbols to avoid clashing with other modules
//				031906 Fixed bug in GetNumberOfTables which needs further revise due to deprecated fn
//				080906 added functions
//				function GetDatabaseResult($rs,$index,$fieldName)
//				function ShowDatabaseError($result)
//				012707 fixed 	function GetTableNameByIndex($index)
//								function GetNumberOfTables()
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ----------------------------------------------------------------------------------------------------------------------

// this is a bunk piece of cruft as it is hard coded throughout the codebase
// the RHS default table decoration, don't mess with this.
$gDatabaseTableID		=	"APPDEV_";				

// ****************************************************************************************
// Start Patch
// ****************************************************************************************

// ----------------------------------------------------------------------------------------------------------------------
// function OpenDatabase($debugMessage)
//
// this will iteratively try to open a connection with up to 50 retrys to 
// connect and 50 retrys to select.
// Each retry comes with a 250ms pause... sorry.
// 
// A really evil thing but at least it scales page performance rather then fails.
//
// 092107 BUG FIX
// ----------------------------------------------------------------------------------------------------------------------
function OpenDatabase($debugMessage="")
{
static $databaseUserNumber = 0;
global $gDatabaseConnectionID;
global $gDatabaseName;
global $gDatabaseUserName;
global $gDatabasePassword;
global $gHost;

$debug	=	0;

// open
$id	=	mysql_connect($gHost,$gDatabaseUserName,$gDatabasePassword);

if(!$id)
	{
	$timeOut	=	0;
	
	// if fail retry with pause
	
	do	{
		usleep(250000);
		
		if($debug) print "******** RETRY CONNECT #: $timeOut ***************<br>";

		$id	=	RetryDatabaseConnect();	

		++$timeOut;
		
		}while($timeOut < 50 && $id==0);
	
	if(!$id)
		{
		$message		=	 "MySql Error Number: " . mysql_errno() . "<br>Unable to select database in OpenDatabase()<br>Error: " . mysql_error() . "<br>";
		die($message);
		}
	
	
	$gDatabaseConnectionID	=	$id;
	}
else
	$gDatabaseConnectionID	=	$id;

$error	=	mysql_select_db($gDatabaseName);

if(!$error)
	{
	$timeOut	=	0;

	do	{
		usleep(250000);

		if($debug) print "+++++++++ RETRY SELECT #: $timeOut +++++++++++++++++<br>";

		$error	=	mysql_select_db($gDatabaseName);

		++$timeOut;
		
		}while($timeOut < 50 && !error);

	// give up...
	if(!$error)
		{
		$message		=	 "MySql Error Number: " . mysql_errno() . "<br>Unable to select database in OpenDatabase()<br>Error: " . mysql_error() . "<br>";
		die($message);
		}
	}

if($debug) print "OpenDatabase() opened link: $id for user: $gDatabaseUserName<br>";
	
return $id;
}




// ----------------------------------------------------------------------------------------------------------------------
// function RetryDatabaseConnect()
//
// Returns link ID on connect or 0 on fail, ID is never 0
//
// 092107 BUG FIX
// ----------------------------------------------------------------------------------------------------------------------

function RetryDatabaseConnect()
{
global $gDatabaseConnectionID;
global $gDatabaseName;
global $gDatabaseUserName;
global $gDatabasePassword;
global $gHost;

//if($debug) print "RetryDatabaseConnect()";

// open
$id	=	mysql_connect($gHost,$gDatabaseUserName,$gDatabasePassword);

if(!$id)
	{
	$message		=	 "Debug: $debugMessage<br>MySql Error Number: " . mysql_errno() . "<br>Unable to connect to database in OpenDatabase()<br>Error: " . mysql_error() . "<br>";

	if($debug) print " returned FAIL<br>";

	return 0;
	}
else
	{
	$gDatabaseConnectionID	=	$id;

	if($debug) print " returned $id<br>";

	return $id;
	}
}

// ----------------------------------------------------------------------------------------------------------------------
// function CloseDatabase()
//
// 092107 BUG FIX
// ----------------------------------------------------------------------------------------------------------------------

function CloseDatabase()
{
global $gDatabaseConnectionID;

$debug	=	0;

//if($debug) print "CloseDatabase() closing link: $gDatabaseConnectionID<br>";

// close connection
if($gDatabaseConnectionID)
	{
	$error					=	mysql_close($gDatabaseConnectionID);
	$gDatabaseConnectionID	=	0;
	}
else
	$error	=	mysql_close();


if(!$error)
	{
	print "Error in CloseDatabase()<br>error: $error<br>Message:" . mysql_error() . "<br>";
	}
}

// ----------------------------------------------------------------------------------------------------------------------
// function GetTableNameByIndex($index,$rs) 
// 091907 BUG FIX
// ----------------------------------------------------------------------------------------------------------------------

function GetTableNameByIndex($index,$rs) 
{
global $gDatabaseName;
global $gDatabaseUserName;
global $gDatabasePassword;
global $gHost;

$debug	=	0;

if($debug) print "<hr>GetTableNameByIndex($index,$rs)<br>";

// seek
mysql_data_seek($rs,$index);

// fetch
$row = @mysql_fetch_assoc($rs);

//print_r($row);

// get val
$tableName = $row['Tables_in_' . $gDatabaseName]; 

if($debug) print "tableName:  $tableName<br>";

return $tableName;
}

// ****************************************************************************************
// End Patch
// ****************************************************************************************

function QueryDatabase($query)
{
$debug	=	0;

if($debug) print "QueryDatabase($query)<br>";

$rs				=	mysql_query(FixUpTableName($query));

if($debug) print "Raw Query: " . $query . "<br>";
if($debug) print "Fixed Up Quret: " . FixUpTableName($query) . "<br>";


return $rs;
}



/*
// ----------------------------------------------------------------------------------------------------------------------
// function OpenDatabase()
// ----------------------------------------------------------------------------------------------------------------------

function OpenDatabase()
{
global $gDatabaseUserName;
global $gDatabasePassword;
global $gDatabaseName;
global $gHost;

mysql_connect($gHost,$gDatabaseUserName,$gDatabasePassword);

@mysql_select_db($gDatabaseName) or die( "Unable to select database in OpenDatabase()<br>");
}

// ----------------------------------------------------------------------------------------------------------------------
// function CloseDatabase()
// ----------------------------------------------------------------------------------------------------------------------

function CloseDatabase()
{
mysql_close();
}


function GetTableNameByIndex($index)
{

global $gDatabaseName;
$debug	=	0;

if($debug) print "GetTableNameByIndex($index)<br>";

// mysql_num_rows is used to get the number of rows in a result set,
$numberOfTables = GetNumberOfTables();

OpenDatabase();

$rs 			= QueryDatabase("SHOW TABLES");

CloseDatabase();

//if($debug) print "numberOfTables:  $numberOfTables<br>";

if($index == $numberOfTables)
	{
	return "Bounds Error in GetTableNameByIndex($index)";
	}
	
$tableName = GetDatabaseResult($rs,$index,"Tables_in_$gDatabaseName");

if($debug) print "tableName:  $tableName<br>";

return $tableName;
}


*/



// --------------------------------------------------------------------------------------
// function FixUpTableName($queryOrTableName)
//
// Based on the value of the test enable flag in LocalInfo.php this will eiother mangle 
// the table name ID or not.
//
// It expects table names to be prefaced with a table name ID. All table names in the 
// database must have this ID
//
// This works around the necessity of having a seperate db instance $$$ to have a 
// testing database
// 
// 2.1.7 070407 - fixed the table name setting
//
// Returns: The input string with any MARKED table names MANGLED or not based on the flag
// --------------------------------------------------------------------------------------

function FixUpTableName($queryOrTableName)
{
global $gTestingEnabled;
global $gDatabaseTableID;
global $gLiveDatabaseID;				// the decoration of the sites live data tables
global $gTestDatabaseID;				// the decoration of the sites test data tables

$debug	=	0;

if($debug) print "<hr>FixUpTableName($queryOrTableName)<br>";

if($debug) print "Query Pre: $queryOrTableName<br>";


if($gTestingEnabled)
	{
	$query	=	str_replace($gDatabaseTableID,$gTestDatabaseID,$queryOrTableName);
	}
else
	{
	$query	=	str_replace($gDatabaseTableID,$gLiveDatabaseID,$queryOrTableName);
	}

if($debug) print "Query Post: $query<br><hr>";

return $query;
}

// ----------------------------------------------------------------------------------------------------------------------
// function GetFieldByIndex($tableName,$fieldName,$index)
// ----------------------------------------------------------------------------------------------------------------------

function GetFieldByIndex($tableName,$fieldName,$index)
{
$debug	=	0;

if($debug) print "GetFieldByIndex($tableName,$fieldName,$index)<br>";

OpenDatabase();

$query			=	"SELECT * FROM $tableName";

$rs				=	QueryDatabase($query);

$value		=	@mysql_result($rs,$index,$fieldName);

if($debug) print "value: $value<br>";

CloseDatabase();

return $value;
}

function GetFieldByIndexWithSortField($tableName,$fieldName,$sortField,$ascending,$index)
{
$debug	=	0;

if($ascending)
	$ascendingClause	=	"";
else
	$ascendingClause	=	"DESC";

if($debug) print "GetFieldByIndexWithSortField($tableName,$fieldName,$sortField,$index)<br>";

OpenDatabase();

$query			=	"SELECT * FROM $tableName ORDER BY $sortField $ascendingClause";

$rs				=	QueryDatabase($query);

$value		=	@mysql_result($rs,$index,$fieldName);

if($debug) print "value: $value<br>";

CloseDatabase();

return $value;
}

function ShowDatabaseError($result)
{
if(!$value) print mysql_error();
}


function GetDatabaseResult($rs,$index,$fieldName)
{
$value		=	@mysql_result($rs,$index,$fieldName);

if(!$value) print mysql_error();

return $value;
}

function GetFieldByIndexBySortOrder($tableName,$fieldName,$index)
{
$debug	=	0;

if($debug) print "GetFieldByIndexBySortOrder($tableName,$fieldName,$index)<br>";

OpenDatabase();

$query			=	"SELECT * FROM $tableName ORDER BY SortOrder";

$rs				=	QueryDatabase($query);

$value		=	@mysql_result($rs,$index,$fieldName);

if($debug) print "value: $value<br>";

CloseDatabase();

return $value;
}


function GetFieldByComparison($tableName,$fieldName,$hayStackName,$needle)
{
$debug	=	0;

if($debug) print "GetFieldByComparison($tableName,$fieldName,$hayStackName,$needle)<br>";

OpenDatabase();

$query		=	"SELECT * FROM $tableName WHERE $hayStackName="  . '"' . $needle . '"' ;

if($debug) print "$query<br>";

$rs			=	QueryDatabase($query);

// 2.2.0 added the @ to squelch the warn on row 0
$value		=	@mysql_result($rs,0,$fieldName);

if($debug) print "value:  $value<br>";

CloseDatabase();

return $value;
}

function GetFieldByComparisonAndIndex($tableName,$fieldName,$hayStackName,$needle,$index)
{
$debug	=	0;

if($debug) print "GetFieldByComparison($tableName,$fieldName,$hayStackName,$needle)<br>";

OpenDatabase();

$query		=	"SELECT * FROM $tableName WHERE $hayStackName="  . '"' . $needle . '"' ;

if($debug) print "$query<br>";

$rs			=	QueryDatabase($query);

$value		=	@mysql_result($rs,$index,$fieldName);

if($debug) print "value:  $value<br>";

CloseDatabase();

return $value;
}



// ----------------------------------------------------------------------------------------------------------------------
// function GetNumberOfRows($query)
// ----------------------------------------------------------------------------------------------------------------------

function GetNumberOfRows($query)
{
$debug	=	0;

if($debug) print "GetNumberOfRows($query)<br>";

OpenDatabase();

if($debug) print "query: " . $query . "<br>";

$rs				=	QueryDatabase($query);

if (!$rs) 
	{
	$message		=	"GetNumberOfRows() - MySQL Error...<br>Message: " .	mysql_error() . "<br>Query: $query";
	
	$debug	=	0;
	if($debug) print $message;
	
	return 0;
	}

$numberOfRows	=	mysql_numrows($rs);

if($debug) print "numberOfRows: " . $numberOfRows . "<br>";

CloseDatabase();

return $numberOfRows;
}

// ---------------------------------------------------------------------------------------------------
// function MangleTableName($tableName)
//
// Returns the local mangled version of the given table name
// ---------------------------------------------------------------------------------------------------

function MangleTableName($tableName)
{
$debug	=	0;

global $gDatabaseTableID;

if($debug) print "MangleTableName($tableName)<br>";

// fix up the table name
$tableName	=	$gDatabaseTableID . $tableName;

if($debug) print "tableName: $tableName<br>";

return $tableName;
}


function GetNumberOfTables()
{
$debug	=	0;

if($debug) print "GetNumberOfTables()<br>";

$query	=	"SHOW TABLES";

if($debug) print "query: $query<br>";

$numberOfRows	= 	GetNumberOfRows($query);

if($debug) print "numberOfRows: $numberOfRows<br>";

return $numberOfRows;
}



?>