<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// name_value_pair_lib.php
//
// Service routines for handling name value pairs and parsing URI formatted cl strings
// 
// Revision:	112206 	File Originated
//				022708	Fixed bug in nvpURIStringToHiddenTags($uriString)
//						This caused it to break the string at further ? chars
//
// Depends: file_lib.php
//			html_lib.php
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// --------------------------------------------------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------------------------------------------------
// testbed
// There is a test asset in the project called nvp.txt and message_nvp_test.html
// --------------------------------------------------------------------------------------------------------------------------------------
/*
include "file_lib.php";

$nameValuePair	=	"foo=bar";

print $nameValuePair . "<br>";
print nvpGetName($nameValuePair) . "<br>";
print nvpGetValue($nameValuePair) . "<br>";

//nvpLoadFile("nvp.txt");

// load a file

print "theMessageFile: $theMessageFile<br>";

$theMessageFile	=	nvpSearchAndReplace("nvp.txt","message_nvp_test.html");

print "theMessageFile: $theMessageFile<br>";
*/

// --------------------------------------------------------------------------------------------------------
// function nvpGetFileNameFromURIString($uriString)
// 
// Gets the file name from a uri string
//
// Returns the file name or ""
// --------------------------------------------------------------------------------------------------------
		
function nvpGetFileNameFromURIString($uriString)
{
$debug		=	0;

if($debug) print "<hr>nvpGetFileNameFromURIString($uriString)<br>\n";


if(strstr($uriString,"?"))
	{
	$a	=	explode("?",$uriString);
	
	return $a[0];
	}
else
	return $uriString;
}


// --------------------------------------------------------------------------------------------------------
// function nvpURIStringToHiddenTags($uriString)
//
// Converts a URI string iwth or without fn to hidden tags
//
// Depends: 	html_lib
//
// Returns nothing...
//
// converts foo.php?name=value&name=value to hidden form input tags
// or
// converts name=value&name=value to hidden form input tags
// --------------------------------------------------------------------------------------------------------

function nvpURIStringToHiddenTags($uriString)
{
$debug	=	0;

// if the uri ends with a & it throws the parse off, bad food 
if($uriString[(strlen($uriString)-1)]=="&")
	$uriString[(strlen($uriString)-1)]	=	"";

if($debug) print "<hr>URIStringToHiddenTags($uriString)<br>\n";

if(strstr($uriString,"?"))
	{
	if($debug) print "Had ? strip fn<br>\n";
	
	//$array		=	explode("?",$uriString);
	//$nvPairs	=	$array[1];

	// 2.2.0 bug fix, I blew stuff up with the explode!!!
	$pos		=	strpos($uriString,"?") + 1;
	$len		=	strlen($uriString);
	$nvPairs	=	substr($uriString,$pos,$len);
	
	if($debug) print "pos: $pos<br>";
	if($debug) print "len: $len<br>";
	if($debug) print "nvPairs: $nvPairs<br>\n";
	}
else
	{
	if($debug) print "no ?<br>\n";
	$nvPairs	=	$uriString;
	}

$nvpArray		=	explode("&",$nvPairs);

$numberOfPairs	=	nvpGetNumberOfPairs($uriString);

for($i=0;$i<$numberOfPairs;$i++)
	{

	$name	=	nvpGetName($nvpArray[$i]);
	$value	=	nvpGetValue($nvpArray[$i]);

	// my bad
	$value	=	str_replace("?","%3F",$value);

	//if($debug) print $nvpArray[$i] . "<br>\n";	
	if($debug) print "name: $name<br>\n";
	if($debug) print "value: $value<br>\n";

	print HiddenTag($name,$value);
	}
}

// --------------------------------------------------------------------------------------------------------
// function nvpGetNumberOfPairs($uriString)
//
// Counts pairs in a uri string formatted as below
// 
// foo=bar&freddie=dead&lie=die
// foo.php?name=value&name=value to hidden tags
// --------------------------------------------------------------------------------------------------------

function nvpGetNumberOfPairs($uriString)
{
$debug	=	0;

if($debug) print "nvpGetNumberOfPairs($uriString)<br>\n";

// if the uri ends with a & it throws the parse off, bad food 
if($uriString[(strlen($uriString)-1)]=="&")
	$uriString[(strlen($uriString)-1)]	=	"";


if(strstr($uriString,"?"))
	{
	if($debug) print "Had ? strip fn<br>\n";
	$array		=	explode("?",$uriString);
	$nvPairs	=	$array[1];
	}
else
	{
	if($debug) print "no ?<br>\n";
	$nvPairs	=	$uriString;
	}

$nvpArray		=	explode("&",$nvPairs);

$numberOfPairs	=	count($nvpArray);

if($debug) print "numberOfPairs: $numberOfPairs<br>\n";

return $numberOfPairs;
}


// --------------------------------------------------------------------------------------------------------------------------------------
// function nvpSearchAndReplace($nvpFileName,$messageFile,$messageFileData="")
//
// This is a case sensitive canonical replacement function it looks for its data in NVP format in the nvpFile 
// and will replace Name with Value on a case sensitive basis.
//
// if srFileData is supplied then the file is not loaded otherwise the function will seek
// to load the file srFileName and proceed to operate on in...
//
// It is not necessary for either file to have a return on the end.
//
// Returns: the search and replaced data
// --------------------------------------------------------------------------------------------------------------------------------------

function nvpSearchAndReplace($nvpFileName,$srFileName,$srFileData="")
{
$debug	=	0;

if($debug) print "nvpSearchAndReplace($nvpFileName,$srFileName,srFileData)<br>";

if($srFileData == "")
	{
	if(($srFileData=FileRead($srFileName))==FALSE)
		{
		print "Failed to Load Message File: $srFileName<br>";
		
		return FALSE;
		}
	}	

if(($nvpArray = nvpLoadFile($nvpFileName))==FALSE)
	{
	print "Failed to Load NVP File: $messageFileName<br>";
	
	return FALSE;
	}

$numberOfItems	=	count($nvpArray);

if($debug) print "numberOfItems: $numberOfItems<br>";
//if($debug) print_r($nvpArray);

// make the indicated replacements, bingo instant canon
for($i=0;$i<$numberOfItems;$i++)
	{
	$name			=	nvpGetName($nvpArray[$i]);	
	$value			=	nvpGetValue($nvpArray[$i]);
		
	if($debug) print "name: $name<br>";
	if($debug) print "value: $value<br>";

	$srFileData	=	str_replace($name,$value,$srFileData);
	}

//if($debug) print "srFileData: $srFileData<br>";


return $srFileData;
}




// --------------------------------------------------------------------------------------------------------------------------------------
// function nvpLoadFile($nvpFileName)
//
// This loads a file of nvp's that are delimited by cr's
// i.e.
// name=value
// name=value
// name=value
// name=value
// ...
// Returns an array with that info 1 line per array element
// --------------------------------------------------------------------------------------------------------------------------------------

function nvpLoadFile($nvpFileName)
{
$debug	=	0;

if($debug) print "nvpLoadFile($nvpFileName)<br>";

//if($debug) print "<br>";

if(($theNvpFile=FileRead($nvpFileName))==FALSE)
	{
	if($debug) print "Failed to Load NVP File: $nvpFileName<br>";
	return FALSE;
	}

$theNvpArray	=	explode("\n",$theNvpFile);

//if($debug) print_r($theNvpArray);

return $theNvpArray;
}




// --------------------------------------------------------------------------------------------------------------------------------------
// function nvpGetName($nameValuePair)
// function nvpGetValue($nameValuePair)
//
// These operate on = sign separated name value pairs
// --------------------------------------------------------------------------------------------------------------------------------------

function nvpGetName($nameValuePair)
{
$array	=	explode("=",$nameValuePair);

$name	=	$array[0];	

return $name;
}

function nvpGetValue($nameValuePair)
{
$array	=	explode("=",$nameValuePair);

$value	=	$array[1];	

return $value;
}







?>
