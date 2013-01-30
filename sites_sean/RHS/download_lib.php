<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// download_lib.php
//
// Download stats and top download fetch utils
//
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

$dlFileName		=		GetPassedValue("dlFileName");
$dlTitle		=		GetPassedValue("dlTitle");
$dlPath			=		GetPassedValue("dlPath");

$debug	=	0;

if($debug) print "dlFileName: $dlFileName<br>";
if($debug) print "dlTitle: $dlTitle<br>";
if($debug) print "dlPath: $dlPath<br>";

// prints unordered list of up to n td's
function TopDownloadsList($numberOfRows)
{
$numberInList	=	GetNumberOfTopDownloads();

$n	=	($numberOfRows>$numberInList)?$numberInList:$numberOfRows;

print 	"<ul>
		";

for($i=0;$i<$n;$i++)
	{
	$td		=	GetTopDownload($i);
	
	$link	=	GetTopDownloadLink($td);
	$title	=	GetTopDownloadTitle($td);
	
	$anchorTag	=	'<a target="_blank" href="' . $link . '">' . $title . '</a>';
	
	print 	"    
			<li>$anchorTag</li>
			";
	}
	

print 	"</ul>
		";
}

function GetTopDownloadLink($td)
{
return "download_asset.php?dlPath=" . $td["dlPath"] . "&dlFileName=" . $td["dlFileName"] . "&dlTitle=" . $td["dlTitle"];
}

function GetTopDownloadTitle($td)
{
return $td["dlTitle"];
}


function GetNumberOfTopDownloads()
{
return GetNumberOfRows("SELECT * FROM APPDEV_TopDownloads WHERE 1");
}

function GetTopDownload($index)
{
if($debug) print "GetTopDownload($index)<br>";

// make a new record
OpenDatabase();

$query 		= 	"SELECT FileName, Title, Path, Type, Score FROM APPDEV_TopDownloads WHERE 1 ORDER BY Score DESC";

if($debug) print "query: $query<br>";

$rs	=	QueryDatabase($query);

if(!$rs) print "SQL Error: " . mysql_error() . "<br>";

CloseDatabase();

$td	=	array();

$td["dlTitle"]		=	GetDatabaseResult($rs,$index,"Title");
$td["dlFileName"]	=	GetDatabaseResult($rs,$index,"FileName");
$td["dlType"]		=	GetDatabaseResult($rs,$index,"Type");
$td["dlPath"]		=	GetDatabaseResult($rs,$index,"Path");
$td["dlScore"]		=	GetDatabaseResult($rs,$index,"Score");

return $td;
}

function ShowTopDownload($td)
{
print "<hr>";
print "dlFileName: " . $td["dlFileName"] . "<br>";
print "dlTitle: " . $td["dlTitle"] . "<br>";
print "dlPath: " . $td["dlPath"] . "<br>";
print "dlType: " . $td["dlType"] . "<br>";
print "dlScore: " . $td["dlScore"] . "<br>";
print "<hr>";
}

// returns the current score
function UpdateDownloadStats($dlFileName,$dlTitle,$dlPath)
{
$debug	=	0;

if($debug) print "DownloadUpdateStats($dlFileName,$dlTitle,$dlPath)<br>";

if(GetNumberOfRows("SELECT * FROM APPDEV_TopDownloads WHERE FileName='$dlFileName'")==0)
	{
	if($debug) print "Score does not exist...<br>";
	
	// get the file type
	$type	=	FileGetExtension($dlFileName);
	$type	=	strtoupper($type);
	
	if($debug) print "type: $type<br>";

	// make a new record
	OpenDatabase();

	$query 		= 	sprintf("INSERT INTO 
					APPDEV_TopDownloads
					(Title, Path, FileName, Type, Score)
					VALUES 
					( '%s' , '%s' , '%s' , '%s','1')",
					mysql_real_escape_string($dlTitle),	
					mysql_real_escape_string($dlPath),	
					mysql_real_escape_string($dlFileName),
					mysql_real_escape_string($type)
					);

	if($debug) print "query: $query<br>";


	$rv	=	QueryDatabase($query);

	if($debug && !$rv) print "Unable to insert new record<br>SQL Error: " . mysql_error() . "<br>";

	CloseDatabase();
	
	$score	=	1; // used in the page
	}
else
	{
	if($debug) print "Score exists...<br>";
	
	// else load and inc the Score for that filename

	$query	=	"SELECT Score FROM APPDEV_TopDownloads 
				WHERE
				FileName='$dlFileName'";

	if($debug) print "query: $query<br>";

	OpenDatabase();

	$rs	=	QueryDatabase($query);

	if($debug && !$rs) print "Unable to select Score<br>SQL Error: " . mysql_error() . "<br>";

	$score	=	GetDatabaseResult($rs,0,"Score");

	++$score;	// inc it...


	$query	=	"
				UPDATE APPDEV_TopDownloads
				SET Score='$score'
				WHERE 
				FileName='$dlFileName'
				";

	if($debug) print "query: $query<br>";

	$rv	=	QueryDatabase($query);

	if($debug && !$rv) print "Unable to update Score<br>SQL Error: " . mysql_error() . "<br>";

	CloseDatabase();
	}	
		
return $score;
}

?>
