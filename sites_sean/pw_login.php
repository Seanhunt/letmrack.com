
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// pw_login.php
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

$pwMode	=	GetPassedValue("pwMode");

if($pwMode=="Target")
	{
	$mode	=	"Target";
	}
else
	{
	$mode	=	"LogIn";
	}
	
	
$debug	=	0;	
	
if($debug) print "mode: $mode<br>";
if($debug) print "pwMode: $pwMode<br>";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print "$gSiteName"; ?> Log In</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
    <td height="30" align="center" valign="middle"><h1><?php print "$gSiteName"; ?> 
      Log In</h1></td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;< <a href="#" onClick="javascript:window.close();">Close Window</a></h3></td>
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
          <td width="12%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="4%">&nbsp;</td>
          <td width="35%" valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="37">&nbsp;</td>
              </tr>
              <tr> 
                <td><form name="form1" method="post" action="RHS/password_services.php">
                    <table width="30%" border="1" align="center" cellpadding="5" cellspacing="0">
                      <tr> 
                        <td width="31%" height="30" align="center" valign="middle" class="rhs12Regular"><p><b>User 
                          ID</b></p></td>
                        <td width="69%" align="center" valign="middle"> 
						<input name="pwUserID" type="text" id="pwUserID" value="<?php print $pwUserID; ?>"> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="center" valign="middle" class="rhs12Regular"><p><b>Password</b></p></td>
                        <td align="center" valign="middle"> <input name="pwPassword" type="password" id="pwPassword"> 
                        </td>
                      </tr>
                    </table>
                    <h5 align="center"><a href="password_lost.php" >Lost 
                      Password</a><br>
                      <br>
                      <a href="password_change.php">Change 
                      Password</a></h5>
                    <p align="center"> 
                      <input type="submit" name="Submit" value="Log In">
                      <input name="pwMode" type="hidden" id="Mode" value="<?php print "$mode"; ?>">
                    </p>
                  </form></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
            </table></td>
          <td width="5%">&nbsp; </td>
          <td width="44%" bgcolor="#999999">&nbsp;</td>
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
