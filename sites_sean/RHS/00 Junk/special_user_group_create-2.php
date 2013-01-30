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
include 'admin_lib.php';
include 'appdev_revision.php';	

$debug	=	0;

$backLink				=	"special_user_group_create-1.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";


$mode			=	$_POST["Mode"];
$expeditionName	=	$_POST["ExpeditionName"];
$day			=	$_POST["Day"];
$month			=	$_POST["Month"];
$year			=	$_POST["Year"];

$regDate		=	"$month/$day/$year";
$sqlDate		=	"$year$month$day";

if($debug) print "regDate: $regDate<br>";
if($debug) print "sqlDate: $sqlDate<br>";
if($debug) print "expeditionName: $expeditionName<br>";


if($mode=="DoUpload")
	{
	if($debug) print "Do Upload...<br>";

	$localPath			=	"$gServerBaseMountPoint/temp/";

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





<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Admin Home</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- Hide from the robots -->		
<meta name="robots" content="noindex,nofollow">
<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
<META NAME="GOOGLEBOT" CONTENT="nofollow">
<META NAME="GOOGLEBOT" CONTENT="noindex">
<!-- End Hide from the robots -->
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" align="center" valign="middle" class="admin18Bold">Create User Group - Step 2</td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#SectionName" target="_blank" class="galleryUILink">Help</a></td>
          <td width="33%">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="20%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="72%">
          
          
          
          

<table width="90100%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50" colspan="3" align="left" valign="middle" bgcolor="#333333" class="rhs18BoldWhite"><p>Please check the upload carefully before approving...</p>
      </td>
  </tr>
  <tr>
    <td width="25%" height="30" align="center" valign="middle"><strong>First</strong></td>
    <td width="25%" align="center" valign="middle"><strong>Last</strong></td>
    <td width="50%" height="30" align="center" valign="middle"><strong>E-Mail</strong></td>
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
        <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>"> 
        <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
    </form></td>
  </tr>
</table>



          
          
          
          </td>
          <td width="8%" bgcolor="#999999">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE">&nbsp;</td>
  </tr>
  <tr> 
    <td align="left" valign="top"> 
      <?php
	PrintAdminFooter();
	?>
    </td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>



<?php
DisplayStatusMessage($statusMessage);
?>


