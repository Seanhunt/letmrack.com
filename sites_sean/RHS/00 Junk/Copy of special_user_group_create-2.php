<?php
// this will upload and successfully parse a PC Excel CSV file

include_once 'class.csv_bv.php';
include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'file_lib.php';
include 'counter_lib.php';
include 'html_lib.php';
include 'status_message.php';	
include 'seo_lib.php';	
include 'ui_lib.php';	
include 'mail_sub_parms.php';	
include 'mail_lib.php';	
include 'chron_lib.php';	
include 'appdev_revision.php';	
include 'select_controls.php';	

$debug	=	0;

$mode			=	$_POST["Mode"];
$expeditionName	=	$_POST["ExpeditionName"];
$day			=	$_POST["Day"];
$month			=	$_POST["Month"];
$year			=	$_POST["Year"];

$regDate		=	"$month/$day/$year";
$sqlDate		=	"$year-$month-$day";

if($debug) print "regDate: $regDate<br>";
if($debug) print "sqlDate: $sqlDate<br>";
if($debug) print "expeditionName: $expeditionName<br>";


if($mode=="DoUpload")
	{
	if($debug) print "Do Upload...<br>";

	$localPath			=	"$gServerBaseMountPoint$gBaseMountPoint/temp/";

	$fileName			=	basename($_FILES['userfile']['name']);

	$fileNameAndPath 	= 	"$localPath$fileName";

	if($debug) print "fileNameAndPath: $fileNameAndPath<br>";
	if($debug) print "Temp File Name: " . $_FILES['userfile']['tmp_name'];
	
	if($debug) echo '<pre>';

	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $fileNameAndPath)) 
		{
		if($debug) echo "File is valid, and was successfully uploaded.\n";
		} 

	if($debug) print_r($_FILES);
	
	if($debug) print "</pre>";
	}
//else // test only...
//	$fileName	=	"myTestPC.csv";

// create parser instance
$csv = & new csv_bv($fileNameAndPath, ',', '"' , '\\');

// make file into array
$userInfoArray = $csv->csv2Array();

if($debug2) print "<br><pre>" . print_r($userInfoArray) . "</pre><br>";

// get the number of rows loaded
$numberOfLines = $csv->RowCount();

if($debug) print "numberOfLines: $numberOfLines<br>";

for($i=1;$i<$numberOfLines;$i++)
	{
	$first	=	$userInfoArray[$i][0];
	$last	=	$userInfoArray[$i][1];
	$eMail	=	$userInfoArray[$i][2];	

	if($debug) print "first: $first<br>";
	if($debug) print "last: $last<br>";
	if($debug) print "eMail: $eMail<br>";
	
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>New User Group - Step 2</title>
<link href="../images/css/local.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table width="50%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50" colspan="3" align="center" valign="middle" bgcolor="#333333" class="rhs18BoldWhite">Please check the upload carefully before approving...</td>
  </tr>
  <tr>
    <td width="30%" height="30" align="center" valign="middle"><strong>First</strong></td>
    <td width="30%" align="center" valign="middle"><strong>Last</strong></td>
    <td width="40%" height="30" align="center" valign="middle"><strong>E-Mail</strong></td>
  </tr>


<?php

$rowTemplate	=	'
<tr>
    <td height="30" align="center" valign="middle">_FIRST_</td>
    <td  align="center" valign="middle">_LAST_</td>
    <td  align="center" valign="middle">_EMAIL_</td>
</tr>';

$numberOfLines = $csv->RowCount();

if($debug) print "numberOfLines: $numberOfLines<br>";

for($i=1;$i<$numberOfLines;$i++)
	{
	$first	=	$userInfoArray[$i][0];
	$last	=	$userInfoArray[$i][1];
	$eMail	=	$userInfoArray[$i][2];	

	if($first != "" && $last != "" && $eMail != "")
		{
		$row	=	str_replace("_FIRST_",$first,$rowTemplate);
		$row	=	str_replace("_LAST_",$last,$row);
		$row	=	str_replace("_EMAIL_",$eMail,$row);
		
		print $row;
		}
	}
?>
	  
  <tr>
    <td height="30" align="center" valign="middle" bgcolor="#999999">&nbsp;</td>
    <td  align="center" valign="middle" bgcolor="#999999">&nbsp;</td>
    <td  height="30" align="center" valign="middle" bgcolor="#999999">&nbsp;</td>
  </tr>
  <tr>
    <td height="50" colspan="3" align="center" valign="middle"><form id="form1" name="form1" method="post" action="special_user_group_create-3.php">
      	<input type="submit" name="CreateUserGroup" id="CreateUserGroup" value="Approve" />
        <input name="FileName" type="hidden" id="FileName" value="<? print $fileNameAndPath; ?>" />
        <input name="ExpeditionName" type="hidden" id="ExpeditionName" value="<? print $expeditionName; ?>" />
        <input name="RegularDate" type="hidden" id="RegularDate" value="<? print $regDate; ?>" />
        <input name="SQLDate" type="hidden" id="SQLDate" value="<? print $sqlDate; ?>" />
    </form></td>
  </tr>
</table>
</body>
</html>
