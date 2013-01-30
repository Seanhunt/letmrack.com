<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// blog_ads.php
//
// Has blog ad functionality
//
// Depends:		../local_info.php
//				db_lib.php
//				file_lib
//
//
// Revision 2.2.0.8
//
// 122008 Added stripslashes to BlogGetAdItem()
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

// returns ads randomRs
function BlogOpenAds($tableName)
{
// get the random records with a random order

$query	=	"SELECT * FROM $tableName WHERE blSortType='RANDOM' ORDER BY RAND()";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

return $rs;
}

function BlogGetNumberOfRandomAds($tableName)
{
$debug	=	0;

if($debug) print "BlogGetNumberOfAds($tableName,randomRs)<br>";

$query	=	"SELECT * FROM $tableName WHERE blSortType='RANDOM'";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

$numberOfRows	=	mysql_numrows($rs);

if($debug) print "numberOfRows: $numberOfRows<br>";

return $numberOfRows;
}

function BlogGetNumberOfFixedAds($tableName)
{
$debug	=	0;

if($debug) print "BlogGetNumberOfAds($tableName,randomRs)<br>";

$query	=	"SELECT * FROM $tableName WHERE blSortType='FIXED' ORDER BY blSortOrder ASC";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

$numberOfRows	=	mysql_numrows($rs);

if($debug) print "numberOfRows: $numberOfRows<br>";

return $numberOfRows;
}


function BlogGetNumberOfAds($tableName,$randomRs)
{
$debug	=	0;

if($debug) print "BlogGetNumberOfAds($tableName,randomRs)<br>";

$query	=	"SELECT * FROM $tableName WHERE blSortType='FIXED' ORDER BY blSortOrder ASC";

OpenDatabase();

$orderedRs	=	QueryDatabase($query);

CloseDatabase();

$numberOfOrderedRows	=	mysql_numrows($orderedRs);
$numberOfRandomRows		=	mysql_numrows($randomRs);
$numberOfRows			=	$numberOfRandomRows + $numberOfOrderedRows;	

if($debug) print "numberOfOrderedRows: $numberOfOrderedRows<br>";
if($debug) print "numberOfRandomRows: $numberOfRandomRows<br>";
if($debug) print "numberOfRows: $numberOfRows<br>";

return $numberOfRows;
}


function BlogGetAdItem($index,$tableName,$randomRs,$templateFileName)
{
$query	=	"SELECT * FROM $tableName WHERE blSortType='FIXED' ORDER BY blSortOrder ASC";

OpenDatabase();

$orderedRs	=	QueryDatabase($query);

CloseDatabase();

$numberOfOrderedRows	=	mysql_numrows($orderedRs);
$numberOfRandomRows		=	mysql_numrows($randomRs);
$numberOfRows			=	$numberOfRandomRows + $numberOfOrderedRows;	
	
// first iterate the ordered rs then the random one
if($index >= $numberOfRows)
	return "";							// fail silently
else if($index < $numberOfOrderedRows)
	{
	if($debug) print "ORDERED ROW<br>";
	$item	=	BlogGetAdTemplate($orderedRs,$index,$templateFileName);	// get the formatted item from ORDERED
	}
else	// try for a random row
	{
	$index	-=	$numberOfOrderedRows;	// fix up for the rs of randoms which come after any ordered records	

	if($debug) print "RANDOM ROW<br>";

	$item	=	BlogGetAdTemplate($randomRs,$index,$templateFileName);	// get the formatted item from RAND
	}

return stripslashes($item);
}

function BlogGetAdTemplate($rs,$index,$templateFileName)
{
if($debug) print "BlogGetAdTemplate(rs,$index,$templateFileName)<br>";

// round up the data
$headline	=	GetDatabaseResult($rs,$index,"blHeadline");
$subhead	=	GetDatabaseResult($rs,$index,"blSubHead");
$copy		=	GetDatabaseResult($rs,$index,"blCopy");
$postingDate=	GetDatabaseResult($rs,$index,"blPostingDate");
$posterID	=	GetDatabaseResult($rs,$index,"blPosterID");
$itemNumber	=	GetDatabaseResult($rs,$index,"blRecordNumber");

if($debug) print "headline: $headline<br>";
if($debug) print "subhead: $subhead<br>";
if($debug) print "copy: $copy<br>";
if($debug) print "postingDate: $postingDate<br>";
if($debug) print "posterID: $posterID<br>";
/*
if($headline=="") 		$headline		= "&nbsp;";
if($subhead=="") 		$subhead		= "&nbsp;";
if($copy=="") 			$copy			= "&nbsp;";
if($postingDate=="") 	$postingDate	= "&nbsp;";
if($posterID=="") 		$posterID		= "&nbsp;";
*/
// read in the file
$templateFile	=	FileRead($templateFileName);

if($debug) print "BEFORE SR - templateFile: $templateFile<br>";

// set up the edit and delete links for the record

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

if($debug) print "AFTER SR - templateFile: $templateFile<br>";

return $templateFile;
}

?>
