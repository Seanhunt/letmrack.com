<?php
// requires UserID and Password as it will authenticate again.
// ---------------------------------------------------------------------------------------------------------------------------------------
// password_admin_user.php
//
// Revision:	Fixed missing header
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
include 'mail_lib.php';
include 'file_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	


$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	
$debug	=	0;

// -----------------------------------------------------------------------------------------------
// Authorize the user and check the role
// -----------------------------------------------------------------------------------------------

if(AuthorizeSuperUser($systemUserID,$systemPassword)!=1)
	{
	print "Access Denied<br>";
	exit(0);
	}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>New Admin User</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle" class="admin18Bold">New Admin 
      or Super User</td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#NewAdminUser" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="24%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="70%">
		  
		  
		  
		  <form name="form1" method="get" action="password_services.php">
              <table width="90%" border="1" align="center" cellpadding="5" cellspacing="0">
                <tr> 
                  <td width="23%" height="30" align="right" valign="middle" class="admin12Bold">First 
                    Name:&nbsp;&nbsp;</td>
                  <td width="77%" align="left" valign="middle"> <input name="pwFirstName" type="text" id="pwFirstName" value="<?php print $pwFirstName; ?>" size="40"></td>
                </tr>
                <tr> 
                  <td height="30" align="right" valign="middle" class="admin12Bold">Last 
                    Name:&nbsp;&nbsp;</td>
                  <td align="left" valign="middle"> <input name="pwLastName" type="text" id="pwLastName" value="<?php print $pwLastName; ?>" size="40"></td>
                </tr>
                <tr> 
                  <td height="30" align="right" valign="middle" class="admin12Bold">Rights:&nbsp;&nbsp;</td>
                  <td align="left" valign="middle"> 
                    
					<?php
					$rightsArray	=	array();
					$rightsArray[]	=	"SuperUser";
					$rightsArray[]	=	"Administrator";
					
					if($userType=="") 
						$userType	=	"Administrator";
					
					DisplaySelector("pwRightsID",$rightsArray,$rightsArray,$userType);
					?>                  </td>
                </tr>
                <tr> 
                  <td height="30" align="right" valign="middle" class="admin12Bold">E-Mail:&nbsp;&nbsp;</td>
                  <td align="left" valign="middle"> <input name="pwEMail" type="text" id="pwEMail" value="<?php print $pwEMail; ?>" size="40"></td>
                </tr>
              </table>
              <p align="center"> 
                <input type="submit" name="Submit" value="Create New User">
                <input name="pwCaller" type="hidden" id="pwCaller" value="pw_new_admin_user.php">
                <input name="pwMode" type="hidden" id="pwMode" value="NewAdminUser">
                <input name="TargetID" type="hidden" id="TargetID" value="<?php print $targetID; ?>">
                <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
                <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
              </p>
            </form> </td>
          <td width="6%" bgcolor="#999999">&nbsp;</td>
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
<p>&nbsp;</p>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>
