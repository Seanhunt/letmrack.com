
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// mail_sub_preview.php
//
// The first name in the test mailing db must also be in the production db for the preview to work correctly.
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
include 'appdev_revision.php';	
include 'mail_lib.php';
include 'file_lib.php';
include 'pw_parms.php';
include 'mail_sub_parms.php';
include "name_value_pair_lib.php";
include 'redirect.php';

$debug	=	0;

$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";

// get the first address in the table, this is for preview only...
$eMailAddress		=	GetFieldByIndex($mailTableName,"email_address",0);

// load the outgoing message asset once this will be munged n times in the loop
if(($theMessageFile=FileRead($messageFileName))==FALSE)
	{
	if($debug) print "Failed to Load File: $messageFileName<br>";
	return FALSE;
	}

//if($debug) print "theMessageFile: $theMessageFile<br>";

// if a NVP file is specified do those search and replaces

if($nvpFileName != "")
	{
	if(($theMessageFile=nvpSearchAndReplace($nvpFileName,"",$theMessageFile))==FALSE)
		{
		if($debug) print "Preview: Failed to NVP Search and Replace<br>$theMessageFile";
		return FALSE;
		}
	}


// then do the DB search and replace
$theMessage			=	GenerateMailingListMessage("",$eMailAddress,$gMailerSalutation,$theMessageFile);

if($debug) print "messageFileName: $messageFileName<br>";
if($debug) print "subject: $subject<br>";
if($debug) print "eMailAddress: $eMailAddress<br>";
if($debug) print "mailTableName: $mailTableName<br>";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Bulk Mail Preview</title>
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
    <td height="30" align="center" valign="middle" class="admin18Bold">Preview 
      Message </td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;</td>
          <td width="33%" align="center"><a href="admin_help.php#PreviewMessage" target="_blank" class="galleryUILink">Help</a></td>
          <td width="33%">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> <table width="840" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="100" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="600" bgcolor="#666666"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="75" align="center" valign="middle" bgcolor="#666666"> 
                  <form name="form1" method="get" action="mail_sub_console.php">
                    <input type="submit" name="Submit" value="Return To Mailer Console">
                    <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
                    <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
                    <input name="MessageFileName" type="hidden" id="MessageFileName" value="<?php print $messageFileName; ?>">
                    <input name="WriteEnabled" type="hidden" id="WriteEnabled" value="<?php print $writeEnabled; ?>">
                    <input name="NumberToSend" type="hidden" id="NumberToSend" value="<?php print $numberToSend; ?>">
                    <input name="MailingID" type="hidden" id="MailingID" value="<?php print $mailingID; ?>">
                    <input name="Subject" type="hidden" id="Subject" value="<?php print $subject; ?>">
                    <input name="MailTableName" type="hidden" id="MailTableName" value="<?php print $mailTableName; ?>">
                    <input name="Sub1" type="hidden" id="Sub1" value="<?php print $sub1; ?>">
                    <input name="Sub2" type="hidden" id="Sub2" value="<?php print $sub2; ?>">
                    <input name="Sub3" type="hidden" id="Sub3" value="<?php print $sub3; ?>">
                  </form></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td bgcolor="#FFFFFF"> 
                  <table width="100%" border="0" cellspacing="0" cellpadding="10">
                    <tr>
                      <td>
                        <?php
print $theMessage;
?>
                      </td>
                    </tr>
                  </table> </td>
              </tr>
              <tr>
                <td bgcolor="#FFFFFF">&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#666666">&nbsp;</td>
              </tr>
            </table>
            <p>&nbsp;</p></td>
          <td width="255" bgcolor="#999999">&nbsp;</td>
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
