<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// debug_lib.php
//
// debug messages in a seperate window with tod, module, line and function information with each message.
// 
// Revision:	080806 File Originated
//
// Revision 2.2.0.RC1
// 
// Author: 	Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// (c) 2008 Rolling Hills Software, All Rights Reserved. 
//
// Modules that use this library must include the following:
//
// include "debug_lib.php";
//
// <script src="debug_lib.js" language="JavaScript" type="text/JavaScript"> with end tag
//
// DebugMessagePump($debug) in the body tag, this adds the OnLoad tag with the call to the 
// javascript message pump 
// 
// Here is a simple call..
//
// DebugMessage("foo: $foo");
//
// This will always display the time and the message in the message window
//
// DebugMessage("foo: $foo",$debug);
// 
// This will display the message if the debug flag is true
//
// DebugMessage("foo: $foo",$debug,__LINE__,__FILE__,__FUNCTION__);
//
// This will give full information and a message
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------



// This only writes to the DB
function DebugLog($message,$debugFlag=true,$line="",$file="",$function="")
{
$debug	=	0;

if($debugFlag==false) return;

if($function == "")
	$function	=	"main";

$function	.=	"()";

if($debug) print "DebugLog(message,$debugFlag,$line,$file,$function)<br>";

// get the module name without the path info
$path		=	$file;
$components	=	explode("/",$path);
$last		=	count($components);
$fnIndex	=	$last - 1;
$fn			=	$components[$fnIndex];	

if($debug) print "fn: $fn<br>";

// insert message
$query	=	"INSERT INTO APPDEV_Debug ( message, function, line, module, time_stamp, id_number ) VALUES('$message','$function','$line','$fn',NOW(),NULL)";

if($debug) print "\nDebugLog-query: $query\n<br>";

if($debugFlag)
	{
	if($debug) print "Write to db...<br>\n";

	OpenDatabase();
	
	$error	=	QueryDatabase($query);	
	
	CloseDatabase();

	if(!$error) 
		if($debug) print mysql_error();
	}
	
return $error;
}


function DebugMessage($message,$debugFlag=true,$line="",$file="",$function="")
{
$debug	=	false;

if($debug) print "DebugMessage($message,$debugFlag,$line,$file,$function)<br>";

if($debugFlag==false) return;

if($line != "")
	$linePrompt	=	"Line: ";
else
	$linePrompt	=	"";
	
if($file != "")
	$filePrompt	=	"- File: ";
else
	$filePrompt	=	"";

if($function != "")
	$functionPrompt	=	"- Function: ";
else
	$functionPrompt	=	"";

$debug	=	0;
$path	=	$file;
$tod	=date("F j, Y, g:i:s a");                // March 10, 2001, 5:16:34 pm

$components	=	explode("/",$path);
$last		=	count($components);
$fnIndex	=	$last - 1;
$fn			=	$components[$fnIndex];	

if($debug) print "last: $last<br>";
if($debug) print "fn: $fn<br>";

$completeMessage	=	"<span class=debugExtra>$tod $filePrompt" . $fn .  
						" $linePrompt" . $line . " $functionPrompt" . $function . "()</span><br><span class=debugMessage>$message</span><br>";

if($debug) "completeMessage: $completeMessage<br>";

// insert message
$query	=	"INSERT INTO APPDEV_Debug VALUES('$completeMessage')";

if($debug) "query: $query<br>";

OpenDatabase();

$error	=	QueryDatabase($query);	

CloseDatabase();

if(!$error) print mysql_error();
}



// goes in the body tag
function DebugMessagePump($debugFlag)
{
if($debugFlag==false) return;

print 'OnLoad="javascript:DebugRefresh()"';
}
?>
