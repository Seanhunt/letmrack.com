<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// pw_new_gallery.php
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
<title>New Gallery User</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="robots" content="noindex,nofollow">
<meta name="robots" content="noarchive">
<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
<META NAME="GOOGLEBOT" CONTENT="nofollow">
<META NAME="GOOGLEBOT" CONTENT="noindex">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle" class="admin18Bold">New Gallery</td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#NewGalleryUser" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="70%" height="150">
		  
		  <form name="form1" method="get" action="password_services.php">

              <table width="90%" border="1" align="center" cellpadding="5" cellspacing="0">
                <tr> 
                  <td width="23%" height="30" align="right" valign="middle" class="admin12Bold">Moderator:&nbsp;&nbsp;</td>
                  <td width="77%" align="left" valign="middle">
                  
                  
                  <?php

						// print the list of users
			
						$query			=	"SELECT pwUserID, pwFirstName, pwLastName FROM APPDEV_UserInfo";

						OpenDatabase();
						
						$rs				=	QueryDatabase($query);
						
						$numberOfUsers	=	mysql_numrows($rs);
						
						if($debug2) print "query: $query<br>";
						if($debug2) print "numberOfUsers: $numberOfUsers<br>";
						
						CloseDatabase();
			
						$labels		=	array();
						$values		=	array();
						
						$labels[]	=	"Pick one...";
						$values[]	=	"Pick";
						
						for($i=0;$i<$numberOfUsers;$i++)
							{
							$uid	=	GetDatabaseResult($rs,$i,"pwUserID");
							$first	=	GetDatabaseResult($rs,$i,"pwFirstName");
							$last	=	GetDatabaseResult($rs,$i,"pwLastName");
							
							$labels[]	=	"$first $last - $uid";
							$values[]	=	"$uid";
							}

						DisplaySelector("pwUserID",$labels,$values,"moderator");				
						
						?>                  </td>
                </tr>
                <tr> 
                  <td height="30" align="right" valign="middle" class="admin12Bold">
                  <span class="admin15BoldRed">*</span>&nbsp;Gallery Name:&nbsp;&nbsp;</td>
                  <td align="left" valign="middle"><input name="pwGalleryName" type="text" id="pwGalleryName" value="<?php print $pwGalleryName; ?>" size="40"></td>
                </tr>
                <tr> 
                  <td height="30" align="right" valign="middle" class="admin12Bold">Gallery 
                    Type: &nbsp;&nbsp;</td>
                  <td align="left" valign="middle"><select name="pwGalleryType" id="pwGalleryType">
                      <option value="FORSALE" selected>For Sale</option>
                      <option value="NFS">Not for Sale</option>
                    </select> </td>
                </tr>
              </table>
              <p align="center">
                <input type="submit" name="Submit" value="Create New Gallery">
                <input name="pwCaller" type="hidden" id="pwCaller" value="pw_new_gallery.php">
                <input name="pwMode" type="hidden" id="pwMode" value="NewGallery">
                <input name="TargetID" type="hidden" id="TargetID" value="<?php print $targetID; ?>">
                <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
                <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
                <input name="pwRightsID" type="hidden" id="pwRightsID" value="Blog-Gallery">
              </p>
          </form> </td>
          <td width="6%" bgcolor="#999999">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="50" align="left" valign="middle" class="admin15BoldRed">
&nbsp;&nbsp;* Required Fields
    &nbsp;</td>
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
