<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// debug_window.php
//
// This is the display window for the debug messages
// 
// Revision:	080806 File Originated
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'admin_lib.php';
include 'counter_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	

$mode		=	GetPassedValue("Mode");
$message	=	GetPassedValue("Message");
$debug		=	0;

if($debug) "mode: $mode<br>";
if($debug) "message: $message<br>";

if($mode=="Clear")
	{
	// truncate APPDEV_Debug
	$query	=	"TRUNCATE APPDEV_Debug";
	if($debug) "query: $query<br>";
	
	OpenDatabase();
	$error	=	QueryDatabase($query);	
	CloseDatabase();
	
	if(!$error) print mysql_error();
	}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>APPDEV Debug Window</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
window.resizeTo(650,450);
</script>
<style type="text/css">
<!--
.debugMessage {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #333333;
}
.debugExtra {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #999999;
}

-->
</style>
</head>

<body>

<table width="25%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td height="50" align="center" valign="middle"> 
      <form name="form1" method="get" action="debug_window.php">
        <input name="Mode" type="submit" id="Mode" value="Clear">
      </form></td>
  </tr>
</table>
<hr>
<table width="80%" border="4" align="center" cellpadding="10" cellspacing="15" bordercolor="#666666" bgcolor="#EEEEEE">
  <tr>
    <td> 
      <table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td>
            <?php

// Display the message scroll
$numberOfMessages	=	GetNumberOfRows("SELECT * FROM APPDEV_Debug");

if($debug) "numberOfMessages: $numberOfMessages<br>";

OpenDatabase();

$rs	=	QueryDatabase("SELECT * FROM APPDEV_Debug");	

CloseDatabase();

if(!$rs) print mysql_error();

for($i=0;$i<$numberOfMessages;$i++)
	{
	print mysql_result($rs,$i,"Message");
	}

?>
          </td>
        </tr>
      </table> </td>
  </tr>
</table><a name="end"><hr></a>
</body>
</html>
