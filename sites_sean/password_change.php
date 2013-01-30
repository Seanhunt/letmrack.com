<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// password_change.php
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

include 'local_info.php';	
include 'RHS/db_lib.php';
include 'RHS/cl_lib.php';
include 'RHS/admin_lib.php';
include 'RHS/question_lib.php';
include 'RHS/file_lib.php';
include 'RHS/blog_lib.php';
include 'RHS/html_lib.php';
include 'RHS/pw_parms.php';	
include 'RHS/pw_lib.php';
include 'RHS/status_message.php';	
include 'RHS/select_controls.php';	
include 'RHS/appdev_revision.php';	

$backLink				=	"pw_login.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Change Your Password</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- Hide from the robots -->		
<meta name="robots" content="noindex,nofollow">
<meta name="robots" content="noarchive">
<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
<META NAME="GOOGLEBOT" CONTENT="nofollow">
<META NAME="GOOGLEBOT" CONTENT="noindex">

<link href="RHS/admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<!--- Include for Tool Tips ******************************* --->
<script type="text/javascript" src="wz_tooltip.js"></script>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle"><h1>Change 
      Password </h1></td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" >Back</a></td>
          <td width="33%" align="center">&nbsp;</td>
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
          <td width="5%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="1%">&nbsp;</td>
          <td width="82%" align="center"><table width="92%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><form name="form1" method="post" action="RHS/password_services.php">
                    <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
                      <tr> 
                        <td width="31%" height="30" align="left" valign="middle" class="rhs12Regular" ><p><strong>User 
                          ID</strong></p></td>
                        <td width="69%" align="center" valign="middle"> <input name="pwUserID" type="text" id="UserID3" value="<?php print $pwUserID; ?>" size="40">                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left" valign="middle" class="rhs12Regular" ><p><strong>Old 
                          Password</strong></p></td>
                        <td align="center" valign="middle"> <input name="pwPassword" type="text" id="pwPassword" value="<?php print $pwPassword; ?>" size="40">                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left" valign="middle" class="rhs12Regular" ><p><strong>New 
                          Password</strong></p></td>
                        <td align="center" valign="middle"><input name="pwNewPassword" type="text" id="pwNewPassword" value="<?php print $pwNewPassword; ?>" size="40"></td>
                      </tr>
                      <tr> 
                        <td height="30" align="left" valign="middle" class="rhs12Regular" ><p><strong>Confirm 
                          New Password</strong></p></td>
                        <td align="center" valign="middle"><input name="pwPasswordConfirm" type="text" id="pwPasswordConfirm" value="<?php print $pwPasswordConfirm; ?>" size="40"></td>
                      </tr>
                    </table>
                    <p align="center"> 
                      <input type="submit" name="Submit" value="Change Password">
                      <input name="pwMode" type="hidden" id="pwMode" value="ChangePassword">
                    </p>
                  </form></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
            </table> </td>
          <td width="2%">&nbsp; </td>
          <td width="10%" bgcolor="#999999">&nbsp;</td>
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
	PrintAdminFooter("RHS/");
	?>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>
