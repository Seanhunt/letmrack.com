<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// question_yn.php
//
// for user response and warn pages 
//
// Rev:		011507 FILE ORIGINATED 
//
// Depends: cl_lib.php
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
include 'question_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'file_lib.php';	
include 'appdev_revision.php';	
include 'mail_sub_parms.php';	

$inputArgs	=	MakeURIString();	// get whatever was passed	
$mode		=	GetPassedValue("Mode");
$debug		=	0;

if($debug) print "inputArgs: $inputArgs<br>";

// if the call out contains an image file name make it into a complete image tag
if(strstr($quCallOut,".jpg") || strstr($quCallOut,".gif") || strstr($quCallOut,".png"))
	{
	// the call out is an image make a image tag
	$callOut	=	'<img src="' . $quCallOut . '" border=0>';

	if($debug) print "Callout is file...<br>$callOut<br>";
	}
else if($mode=="DeleteMailingListNames")
	{
	if($debug) print "Callout is list of names to be deleted...<br>";

	// make a list of the names to be deleted for the callout...
	$mailTableName	=	GetPassedValue("MailTableName");
	$numberOfRows	=	GetNumberOfRows("SELECT * FROM $mailTableName");
	
	if($debug) print "numberOfRows: $numberOfRows<br>";
	
	$callOut		=	"<span class=rhs18BoldRed>\n";
	
	for($i=0;$i<$numberOfRows;$i++)
		{
		$boxId		=	"checkbox$i";
		$boxValue	=	GetPassedValue($boxId);
		
		if($boxValue != "")
			{
			// get the email address
			$address	=	GetFieldByComparison($mailTableName,"email_address","item_number",$boxValue);
			
			$callOut	.=	"$address<br>\n";
			}
		}

	$callOut		.=	"</span>\n";
	}
else
	{
	$callOut	=	$quCallOut;

	if($debug) print "Callout is NOT file...<br>";
	}



$yesLink	=	"$quTarget?$inputArgs";
$noLink		=	"$quCaller?$inputArgs";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $quMessage; ?></title>
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
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#fda0a8">
        <tr> 
          <td height="100" align="center" valign="middle" class="rhs24BoldWhite"> 
            <?php print $quMessage; ?> </td>
        </tr>
        <tr>
          <td height="10" align="center" valign="middle" bgcolor="#FFFFFF" class="cts18BoldWhite">&nbsp;</td>
        </tr>
        <tr> 
          <td height="80" align="center" valign="middle" bgcolor="#FFFFFF" class="cts18Bold"><?php print $callOut; ?></td>
        </tr>
        <tr> 
          <td height="10" align="center" valign="middle" bgcolor="#FFFFFF" class="cts18BoldWhite">&nbsp;</td>
        </tr>
        <tr> 
          <td height="100" align="center" valign="middle"> <table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr align="center" valign="middle"> 
                <td width="50%"><a href="<?php print $yesLink; ?>" class="boldLink18White">Yes</a></td>
                <td><a href="<?php print $noLink; ?>" class="boldLink18White">No</a></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
