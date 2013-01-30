<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// captcha_lib.php
//
// "Completely Automated Public Turing test to tell Computers and Humans Apart"
//
// This will manage a set of captchas as numbered files 1.gif - ...
//
// 112507 Am making this better as there is now a contact page spambot
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------
/*
include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'file_lib.php';
include 'counter_lib.php';
include 'html_lib.php';
include 'status_message.php';	
include 'seo_lib.php';	
include 'ui_lib.php';	

for($i=0;$i<10;$i++)
	{
	$type	=	"small";
	
	$fileName	= 	GetCaptchaFileName($type);
	$text		=	GetCaptchaText($fileName,$type);
	
	print "fileName: $fileName<br>";
	print "text: $text<br>";
	
	PrintCaptchaHiddenTags($fileName,$type);
	}


// testbed stuff

print GetCaptchaText("1.gif") . "<br>";
print GetCaptchaText("2.gif") . "<br>";

for($i=0;$i<20;$i++)
	{
	print GetCaptchaFileName() . "<br>";
	}


*/


// this goes in a form that calls with a captcha
function PrintCaptchaHiddenTags($fileName,$type)
{
print 	'
		<input name="cfn" type="hidden" id="cfn" value="' . $fileName . '" />
		<input name="ct" type="hidden" id="ct" value="' . $type . '" />
		';
}


function GetCaptchaFileName($type="large")
{
$debug	=	0;

// type is small or large

if($debug) print "GetCaptchaFileName($type)<br>";

$numberOfRowsQuery	=	"SELECT * FROM APPDEV_Captcha WHERE Type='$type'";

if($debug) print "numberOfRowsQuery: $numberOfRowsQuery<br>";

$numberOfFiles	=	GetNumberOfRows($numberOfRowsQuery);

if($debug) print "numberOfFiles: $numberOfFiles<br>";

OpenDatabase();

$rs	=	QueryDatabase("SELECT * FROM APPDEV_Captcha WHERE Type='$type' ORDER BY RAND()");

$fileName	=	GetDatabaseResult($rs,0,"FileName");

CloseDatabase();

if($debug) print "fileName: $fileName<br>";

return $fileName;
}


function GetCaptchaText($fileName,$type="large")
{
//$debug	=	0;

if($debug) print "GetCaptchaText($fileName,$type)<br>";

$query	=	"SELECT * FROM APPDEV_Captcha WHERE Type='$type' AND FileName='$fileName'";

if($debug) print "query: $query<br>";

OpenDatabase();

$rs	=	QueryDatabase($query);

$text	=	GetDatabaseResult($rs,0,"PlainText");

CloseDatabase();

if($debug) print "text: $text<br>";

return $text;
}




/*
function GetCaptchaFileName()
{
$numberOfFiles	=	2;

$i	=	rand(1,$numberOfFiles);

$fileName	=	"$i.gif";

return $fileName;
}


function GetCaptchaText($fileName)
{
$textStrings	=	array();

$textStrings["1.gif"]	=	"GIRAFFE";	
$textStrings["2.gif"]	=	"ELEPHANT";	

return $textStrings[$fileName];
}
*/

?>