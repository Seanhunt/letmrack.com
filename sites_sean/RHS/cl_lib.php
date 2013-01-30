<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// cl_lib.php
//
// Contains stuff for passing args on the browser cl client side validation does not work across
// the board with javascript. Idiots!
//
// Rev: 	011207	File Originated
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------


// creates a properly formatted javascript window open call
function JavaScriptWindowOpen($targetURI,$windowName,$width,$height,$args="menubar=1,resizable=1")
{
if($args != "")
	$args	.=	",width=$width,height=$height";
else
	$args	=	"width=$width,height=$height";

$windowOpen	=	"javascript:window.open('$targetURI','$windowName','$args');";

return $windowOpen;
}


// kills whitespace and toxic chars...
function MakeCleanID($dirtyID)
{
$cleanID	=	str_replace(" ","",$dirtyID);
$cleanID	=	str_replace("'","",$cleanID);
$cleanID	=	str_replace('"',"",$cleanID);
$cleanID	=	str_replace(';',"",$cleanID);
$cleanID	=	str_replace('?',"",$cleanID);
$cleanID	=	str_replace('!',"",$cleanID);
$cleanID	=	str_replace('@',"",$cleanID);
$cleanID	=	str_replace('#',"",$cleanID);
$cleanID	=	str_replace('$',"",$cleanID);
$cleanID	=	str_replace('%',"",$cleanID);
$cleanID	=	str_replace('^',"",$cleanID);
$cleanID	=	str_replace('&',"",$cleanID);
$cleanID	=	str_replace('*',"",$cleanID);
$cleanID	=	str_replace('(',"",$cleanID);
$cleanID	=	str_replace(')',"",$cleanID);
$cleanID	=	str_replace('+',"",$cleanID);
$cleanID	=	str_replace('=',"",$cleanID);
$cleanID	=	str_replace('/',"",$cleanID);
$cleanID	=	str_replace("\\","",$cleanID);
$cleanID	=	str_replace("-","",$cleanID);
$cleanID	=	strtolower($cleanID);

return $cleanID;
}

// gets the name (fn.ext) of the current page
function GetCurrentPageName()
{
$currentFile = $_SERVER["SCRIPT_NAME"];

$fn = array_pop(explode("/", $currentFile));

return $fn;
}


//print urldecode(CleanInput('I have # 2 pencil called "woody" & fido is my dog'));
function CleanURL($dirtyURL)
{
// I want www.whatever.com
// it could be a domain like froogle.google.com so I can't just willy nilly 
// patch it up...

$cleanURL	=	str_replace("http://","",$dirtyURL);

return 	"http://" . $cleanURL;
}

function GetPassedValue($valueTag)
{
$result	=	"";

$debug	=	0;

if($debug) print "GetPassedValue($valueTag)<br>";

// first attempt to get a value from the _GET array
$result	=	$_GET["$valueTag"];

if($debug) print "GET result: $result<br>";

// if that didnt produce a result try to get one from the _POST array
if($result=="")
	{
	$result	=	$_POST["$valueTag"];
	
	if($debug) print "POST result: $result<br>";
	}

// return whatever I got	
return $result;
}

// returns an undecorated string with the current cl args
// this may need to have the chars trapped as in CleanInput()
function MakeURIString()
{
$debug	=	0;

$args	=	"";

if($debug) print "MakeURIString()<br>";

if(count($_GET) > 0)
	{
	reset($_GET);

	if($debug) print "GET array: " . count($_GET) . "<br>";

	for($i=0;$i < count($_GET);$i++)
		{
		list($key, $val) = each($_GET);

		$val	=	urlencode($val);

		if($debug) print "$key=$val<br>";
		
		$args	.= 	"$key=$val";

		if($i < count($_GET)-1)
			$args	.= 	"&";
		}
	}
else
	{
	reset($_POST);

	if($debug) print "POST array: " . count($_POST) . "<br>";
	
	for($i=0;$i < count($_POST);$i++)
		{
		list($key, $val) = each($_POST);

		$val	=	urlencode($val);

		if($debug) print "$key=$val<br>";
		
		$args	.= 	"$key=$val";

		if($i < count($_POST)-1)
			$args	.= 	"&";
		}
	}

if($debug) print "args: $args<br>";

return $args;
}

?>