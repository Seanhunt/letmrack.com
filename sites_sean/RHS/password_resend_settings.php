
<?php
// requires UserID and Password as it will authenticate again.
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'admin_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'file_lib.php';
include 'html_lib.php';
include 'mail_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	

$debug	=	0;

// -----------------------------------------------------------------------------------------------
// Authorize the user and check the role
// -----------------------------------------------------------------------------------------------

if(AuthorizeSuperUser($systemUserID,$systemPassword)!=1)
	{
	print "Access Denied<br>";
	exit(0);
	}

$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";

$submit			=		GetPassedValue("Submit");


if($debug) print "submit: $submit<br>";

if($submit=="View" || $submit=="Resend Settings")
	{
	// get the user info based on userID
	$pwPassword		=	GetFieldByComparison("APPDEV_UserInfo","pwPassword","pwUserID",$pwUserID);
	$pwFirstName	=	GetFieldByComparison("APPDEV_UserInfo","pwFirstName","pwUserID",$pwUserID);
	$pwLastName		=	GetFieldByComparison("APPDEV_UserInfo","pwLastName","pwUserID",$pwUserID);
	$pwRightsID		=	GetFieldByComparison("APPDEV_UserInfo","pwRightsID","pwUserID",$pwUserID);
	$pwEMail		=	GetFieldByComparison("APPDEV_UserInfo","pwEMail","pwUserID",$pwUserID);
	$pwRecordNumber	=	GetFieldByComparison("APPDEV_UserInfo","pwRecordNumber","pwUserID",$pwUserID);

	if($debug) print "<hr>View Mode<br>";
	if($debug) print "pwPassword: $pwPassword<br>";
	if($debug) print "pwFirstName: $pwFirstName<br>";
	if($debug) print "pwLastName: $pwLastName<br>";
	if($debug) print "pwRightsID: $pwRightsID<br>";
	if($debug) print "pwEMail: $pwEMail<hr><br>";
	}

if($submit=="Resend Settings")
	{
	if($debug) print "<hr>Resend Mode<br>";

	$toAddress	=	GetEMail($pwUserID);
	$fromAddress=	GetEMail("webmaster");
	$subject	=	"[$gSiteName User Information] Your User ID and Password for $gSiteName";  
	$fromName	=	"$gSiteName Admin";

	// select the appropriate message template
	
	
	if($pwRightsID=="Blog-Gallery")
		{
		$templateFileName	=	"../messages/user_new_gallery.html";
		}
		
	else if($pwRightsID=="Administrator" || $pwRightsID=="SuperUser")
		{
		$templateFileName	=	"../messages/user_new_admin.html";
		}
	else
		{
		$statusMessage	=	"Unknown pwRightsID";
		}

	if($debug) print "toAddress: $toAddress<br>";
	if($debug) print "fromAddress: $fromAddress<br>";
	if($debug) print "subject: $subject<br>";
	if($debug) print "fromName: $fromName<br>";
	if($debug) print "templateFileName: $templateFileName<br>";

	// get personalized message to user
	$message	=	PasswordGetTemplateFile($pwUserID,$templateFileName);

	if(email_send($toAddress,$fromAddress,$fromName,$subject,$message)!=1)
		print "Error - Unable to Send Mail, Please Contact System Administrator";
	else
		{
		$pwPassword		=	"";
		$pwFirstName	=	"";
		$pwLastName		=	"";
		$pwRightsID		=	"";
		$pwEMail		=	"";
		$pwRecordNumber	=	"";
		$pwUserID		=	"Pick";
		}

	if($debug) print "Settings Resent To User<br>";
	}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Resend User Information</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle" class="admin18Bold">Resend 
      User Information</td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#ResendSettings" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="8%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="70%" valign="top"> 
            <table width="89%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><form name="form1" method="get" action="password_resend_settings.php">
                    <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
                      <tr> 
                        <td width="19%" height="35" align="right" valign="middle" class="admin12Bold" >Pick 
                          User ID:&nbsp;&nbsp;</td>
                        <td width="81%" align="left" valign="middle" class="admin12Regular">&nbsp; 
                      <?php

						// print the links to the blogs that user has access to...
			
						$query			=	"SELECT pwUserID, pwFirstName, pwLastName FROM APPDEV_UserInfo";
						OpenDatabase();
						
						$rs				=	QueryDatabase($query);
						
						$numberOfUsers	=	mysql_numrows($rs);
						
						if($debug2) print "query: $query<br>";
						if($debug2) print "numberOfUsers: $numberOfUsers<br>";
						
						CloseDatabase();
			
						$labels		=	array();
						$values		=	array();
						
						$labels[]	=	"Pick one and hit view...";
						$values[]	=	"Pick";
						
						for($i=0;$i<$numberOfUsers;$i++)
							{
							$uid	=	GetDatabaseResult($rs,$i,"pwUserID");
							$first	=	GetDatabaseResult($rs,$i,"pwFirstName");
							$last	=	GetDatabaseResult($rs,$i,"pwLastName");
							
							$labels[]	=	"$first $last - $uid";
							$values[]	=	"$uid";
							}

						DisplaySelector("pwUserID",$labels,$values,$pwUserID);				
						
						print "&nbsp;&nbsp;&nbsp;";
						
						SubmitButton("Submit","View");

						?>                      
                      </tr>
                      <tr> 
                        <td height="35" align="right" valign="middle" class="admin12Bold" >First 
                          Name:&nbsp;&nbsp;</td>
                        <td align="left" valign="middle" class="admin12Regular">&nbsp;&nbsp;<?php print $pwFirstName; ?></td>
                      </tr>
                      <tr> 
                        <td height="35" align="right" valign="middle" class="admin12Bold" >Last 
                          Name:&nbsp;&nbsp;</td>
                        <td align="left" valign="middle" class="admin12Regular">&nbsp;&nbsp;<?php print $pwLastName; ?></td>
                      </tr>
                      <tr> 
                        <td height="35" align="right" valign="middle" class="admin12Bold" >E-Mail:&nbsp;&nbsp;</td>
                        <td align="left" valign="middle" class="admin12Regular">&nbsp;&nbsp;<?php print $pwEMail; ?></td>
                      </tr>
                      <tr> 
                        <td height="35" align="right" valign="middle" class="admin12Bold" >Password:&nbsp;&nbsp;</td>
                        <td align="left" valign="middle" class="admin12Regular">&nbsp;&nbsp;
						<?php print $pwPassword; ?></td>
                      </tr>
                    </table>
                    <br>
                    <table width="30%" border="0" align="center" cellpadding="10" cellspacing="0">
                      <tr> 
                        <td width="50%" align="center"> 
                          <?php if($pwUserID != "") SubmitButton("Submit","Resend Settings"); ?>
                        </td>
                      </tr>
                    </table>
                    <p align="center"> 
                      <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
                      <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
					<input name="pwRecordNumber" type="hidden" id="pwRecordNumber" value="<?php print $pwRecordNumber; ?>">                    
					</p>
                  </form></td>
              </tr>
            </table>
            
          </td>
          <td width="22%" bgcolor="#999999">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
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
<?php
DisplayStatusMessage($statusMessage);
?>
