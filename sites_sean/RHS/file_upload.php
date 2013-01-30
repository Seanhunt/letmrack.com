<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// file_upload.php
//
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

include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
include 'admin_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	
include 'file_lib.php';	

// -----------------------------------------------------------------------------------------------
// Authorize the user and check the role
// -----------------------------------------------------------------------------------------------

if(AuthorizeAdminUser($systemUserID,$systemPassword)!=1)
	{
	print "Access Denied<br>";
	exit(0);
	}

$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>FTP File Delivery Portal</title>
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
    <td height="30" align="center" valign="middle" class="admin18Bold">Deliver 
      File Via FTP</td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
        <td width="33%" align="center"><a href="admin_help.php#FTPDelivery" target="_blank" class="galleryUILink">Help</a></td>
        <td width="33%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> <form action="file_upload_services.php" method="POST" enctype="multipart/form-data" name="SendFile" id="SendFile">
        <!-- MAX_FILE_SIZE must precede the file input field -->
        <input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
        <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
        <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
        <!-- Name of input element determines name in $_FILES array -->
        <table width="800" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td><table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr> 
                  <td width="168" height="45" align="right" bgcolor="#CCCCCC" class="admin12Bold">Send 
                    this file:&nbsp;&nbsp;</td>
                  <td width="514" align="left" valign="middle" bgcolor="#EFEFEF">&nbsp;&nbsp; 
                    <input name="FileToSend" type="file" id="FileToSend" size="60" /> 
                  </td>
                </tr>
                <tr> 
                  <td height="45" align="right" bgcolor="#CCCCCC" class="admin12Bold">To:&nbsp;&nbsp;</td>
                  <td align="left" valign="middle" bgcolor="#EFEFEF">&nbsp;&nbsp; 
                    <input name="ToAddress" type="text" id="ToAddress" size="60"></td>
                </tr>
                <tr> 
                  <td height="45" align="right" bgcolor="#CCCCCC" class="admin12Bold">Subject:&nbsp;&nbsp;</td>
                  <td align="left" valign="middle" bgcolor="#EFEFEF">&nbsp;&nbsp; 
                    <input name="Subject" type="text" id="Subject" size="60"></td>
                </tr>
                <tr> 
                  <td align="right" valign="top" bgcolor="#CCCCCC" class="admin12Bold"> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td>&nbsp;</td>
                      </tr>
                      <tr> 
                        <td align="right" class="admin12Bold">Message:&nbsp;&nbsp; 
                        </td>
                      </tr>
                    </table></td>
                  <td bgcolor="#EFEFEF"> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td>&nbsp;</td>
                      </tr>
                      <tr> 
                        <td>&nbsp;&nbsp; <textarea name="Message" cols="60" rows="20" id="textarea3"></textarea></td>
                      </tr>
                      <tr> 
                        <td>&nbsp;</td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td height="40" align="center"> 
              <input name="submit" type="submit" value="Send File" />
            </td>
          </tr>
        </table>
      </form></td>
  </tr>
  <tr> 
    <td height="40" align="left" valign="middle" bgcolor="#EEEEEE">
      <?php
	PrintUserMessage($systemUserID);
	?>
    </td>
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
