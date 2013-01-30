
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// mail_list_console.php
//
// Revision 2.2.0.RC1
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
include 'mail_lib.php';
include 'mail_sub_parms.php';
include 'file_lib.php';
include 'appdev_revision.php';	

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

<html>
<head>
<title>Bulk Mail Console</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW">
<style type="text/css">
<!--
.promptText {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #333333;
}
.headline {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #eeeeee;
}
.options {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
-->
</style>
<style type="text/css">
<!--
.help {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
}
-->
</style>
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div align="center"></div>
  
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle" class="admin18Bold">Bulk Mail 
      Console</td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#BulkMailConsole" target="_blank" class="galleryUILink">Help</a></td>
        <td width="33%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top">
	<form action="mail_sub_direct.php"  enctype="multipart/form-data" method="POST" name="form1">
    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
	
	    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="73%" align="left" valign="top"> 
                    <table width="95%" border="0" cellpadding="5" cellspacing="0" bgcolor="#EFEFEF" class="promptText">
                      <tr> 
                        <td width="31%" height="50" align="right" bgcolor="#CCCCCC" class="promptText"> 
                          Number to Send:&nbsp;&nbsp;</td>
                        <td width="69%" align="left">&nbsp;&nbsp;<input name="NumberToSend" type="text" id="NumberToSend" value="175" size="5"></td>
                      </tr>
                      <tr> 
                        <td height="50" align="right" bgcolor="#CCCCCC" class="promptText">From 
                          Address:&nbsp;&nbsp;</td>
                        <td align="left">&nbsp;&nbsp;<?php print $gMailerFromAddress; ?> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="50" align="right" bgcolor="#CCCCCC" class="promptText">From 
                          Name:&nbsp;&nbsp;</td>
                        <td align="left">&nbsp;&nbsp;<?php print $gMailerFromName; ?></td>
                      </tr>
                      <tr> 
                        <td height="50" align="right" bgcolor="#CCCCCC" class="promptText">Subject:&nbsp;&nbsp;</td>
                        <td align="left">&nbsp;&nbsp;
<input name="Subject" type="text" id="Subject" value="<?php print $subject; ?>" size="50"></td>
                      </tr>
                      <tr> 
                        <td height="50" align="right" bgcolor="#CCCCCC" class="promptText">Write 
                          Enabled:&nbsp;&nbsp;</td>
                        <td align="left">&nbsp;&nbsp;
<select name="WriteEnabled" id="WriteEnabled">
                            <option value="No" selected>No</option>
                            <option value="Yes">Yes</option>
                          </select> </td>
                      </tr>
                      <tr> 
                        <td height="50" align="right" valign="middle" bgcolor="#CCCCCC" class="promptText">Database:&nbsp;&nbsp;</td>
                        <td align="left">&nbsp;&nbsp;
<select name="MailTableName" id="MailTableName">
                            <option value="APPDEV_MailDatabase">Live</option>
                            <option value="APPDEV_TestMailingList" selected>Test</option>
                          </select> </td>
                      </tr>
                      <tr> 
                        <td height="50" align="right" bgcolor="#CCCCCC" class="promptText">Mailing 
                          ID:&nbsp;&nbsp;</td>
                        <td align="left">&nbsp;&nbsp;
<input name="MailingID" type="text" id="MailingID2" value="<?php print $mailingID; ?>" size="50"></td>
                      </tr>
                      <tr> 
                        <td height="50" align="right" bgcolor="#CCCCCC" class="promptText">Message 
                          File Name:&nbsp;&nbsp;</td>
                        <td align="left" valign="middle"> &nbsp;&nbsp;
<input name="MessageFileName" type="text" id="MessageFileName" value="<?php print $messageFileName; ?>" size="50"> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="50" align="right" bgcolor="#CCCCCC" class="promptText">Message 
                          File Upload:&nbsp;&nbsp;</td>
                        <td align="left" valign="middle"> &nbsp;&nbsp;
                            <input name="MessageFileUpload" type="file" id="MessageFileUpload" size="40" />
                         
                          </td>
                      </tr>
                    </table></td>
                  <td width="27%" align="center" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td width="58%" height="100" align="center" valign="top"><table width="100%" border="1" cellspacing="0" cellpadding="10">
                            <tr> 
                              <td height="30" colspan="2" align="center" bgcolor="#CCCCCC" class="promptText">Mail 
                                To...</td>
                            </tr>
                            <tr> 
                              <td width="86%" height="30" class="promptText"><?php print GetMailingListName(1); ?></td>
                              <td width="14%"><input name="Sub1" type="checkbox" id="Sub12" value="1" <?php if($sub1) print "checked"; ?>></td>
                            </tr>
                            <tr> 
                              <td height="30" class="promptText"><?php print GetMailingListName(2); ?></td>
                              <td><input name="Sub2" type="checkbox" id="Sub22" value="1" <?php if($sub2) print " checked"; ?>></td>
                            </tr>
                            <tr> 
                              <td height="30" class="promptText"><?php print GetMailingListName(3); ?></td>
                              <td><input name="Sub3" type="checkbox" id="Sub32" value="1" <?php if($sub3) print "checked"; ?>></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr align="center"> 
                        <td width="50%" height="50"> <input name="Submit1" type="submit" id="Submit12" value="Preview"> 
                        </td>
                        <td> <input type="submit" name="Submit2" value="Send"></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              
            </td>
          </tr>
        </table>
        <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
        <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
        <input name="StartRowIndex" type="hidden" id="StartRowIndex" value="0">
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
</body>
</html>


