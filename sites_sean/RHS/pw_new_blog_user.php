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
<title>New Blog or Gallery User</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle" class="admin18Bold">New Blog or Gallery
      User</td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#NewBlogUser" target="_blank" class="galleryUILink">Help</a></td>
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
                  <td height="30" colspan="2" align="right" valign="middle" class="admin12Bold">First 
                    Name:&nbsp;&nbsp;</td>
                  <td width="76%" align="left" valign="middle"> <input name="pwFirstName" type="text" id="pwFirstName" value="<?php print $pwFirstName; ?>" size="40"></td>
                </tr>
                <tr> 
                  <td height="30" colspan="2" align="right" valign="middle" class="admin12Bold">Last 
                    Name:&nbsp;&nbsp;</td>
                  <td align="left" valign="middle"><input name="pwLastName" type="text" id="pwLastName2" value="<?php print $pwLastName; ?>" size="40"></td>
                </tr>
                
                <tr>
                  <td height="30" colspan="2" align="right" valign="middle" class="admin12Bold">E-Mail:&nbsp;&nbsp;</td>
                  <td align="left" valign="middle"><input name="pwEMail" type="text" id="pwEMail" value="<?php print $pwEMail; ?>" size="40"></td>
                </tr>
                				
                <?php
				// first enumerate the blogs
				
				// get number of blogs
				$numberOfBlogs	=	GetNumberOfRows("SELECT blTable FROM APPDEV_BlogHeaders");


				if($numberOfBlogs)
					{
					print	'
					<tr> 
						<td width="18%" height="30" align="center" valign="middle" class="admin10Regular">Moderator</td>
						<td width="16%" align="center" valign="middle" class="admin10Regular">Contributor</td>
						<td class="admin10Regular" align="left" valign="middle" >&nbsp;&nbsp;Gallery Name</td>
					</tr>
					';

						for($i=0;$i<$numberOfBlogs;$i++)
							{
							$blogName		=	GetFieldByIndex("APPDEV_BlogHeaders","blName",$i);
							$blogTable		=	GetFieldByIndex("APPDEV_BlogHeaders","blTable",$i);
							
							// if a user has been selected
							// is pwUserID good for that table ?
							


							$moderatorQuery	=	"SELECT * FROM APPDEV_UserPermissions 
												 WHERE pwUserID='$pwUserID' 
												 AND pwPermission='$blogTable'
												 AND pwModerator='1'";
		
		
							$authorQuery	=	"SELECT * FROM APPDEV_UserPermissions 
												 WHERE pwUserID='$pwUserID' 
												 AND pwPermission='$blogTable'
												 AND pwContributor='1'";
				
		
							if($debug3) print "authorQuery: $authorQuery<br><br>";
							if($debug3) print "moderatorQuery: $moderatorQuery<br><br>";
	

							$author			=	GetNumberOfRows($authorQuery);	
							$moderator		=	GetNumberOfRows($moderatorQuery);

							if($debug3) print "author: $author<br><br>";
							if($debug3) print "moderator: $moderator<br><br>";
							if($debug3) print "blogTable: $blogTable<br><br>";

					
							if($author && !$moderator)
								{
								if($debug3) print "AUTHOR<br><br>";
								$moderatorState	=	"";
								$authorState	=	"checked";
								}
							else if($moderator && !$author)
								{
								if($debug3) print "MODERATOR<br><br>";
								$moderatorState	=	"checked";
								$authorState	=	"";
								}
							else if($moderator && $author)
								{
								if($debug3) print "BOTH<br><br>";
								$moderatorState	=	"checked";
								$authorState	=	"checked";
								}
							else 	// no rights
								{
								if($debug3) print "NEITHER<br><br>";
								$moderatorState	=	"";
								$authorState	=	"";
								}
							

print 	'
		<tr> 
			<td width="18%" height="30" align="center" valign="middle" class="admin12Bold">
			
			
			<input name="checkbox_bm' . $i . '" type="checkbox" id="checkbox' . $i . '" value="1" ' . $moderatorState . '>
			
			</td>
			
			
			
			<td width="16%" align="center" valign="middle" class="admin12Bold">
			
			<input name="checkbox_bc' . $i . '" type="checkbox" id="checkbox' . $i . '" value="1" ' . $authorState . '>
			
			
			</td>
			<td class="admin12Bold" align="left" valign="middle">&nbsp;&nbsp;&nbsp;' . $blogName . '</td>
		</tr>
		';




							} // end of for($i=0;$i<$numberOfBlogs;$i++)
							
					} //  end if($numberOfBlogs)
					
				// then do the galleries
				$numberOfGalleries	=	GetNumberOfRows("SELECT Flag FROM APPDEV_GalleryProfiles");
				
				if($numberOfGalleries)
					{
					
					
					
						print	'
<tr> 
    <td width="18%" height="30" align="center" valign="middle" class="admin10Regular">Moderator</td>
    <td width="16%" align="center" valign="middle" class="admin10Regular">Contributor</td>
    <td class="admin10Regular" align="left" valign="middle" >&nbsp;&nbsp;Gallery Name</td>
</tr>
';					
					

						for($i=0;$i<$numberOfGalleries;$i++)
							{
							$galleryName	=	GetFieldByIndex("APPDEV_GalleryProfiles","Title",$i);
							$galleryTable	=	"APPDEV_GALLERY_" . GetFieldByIndex("APPDEV_GalleryProfiles","ArtistID",$i);
							
							// if a user has been selected
							// is pwUserID good for that table ?
							/*
							if(AuthorizeUserTable($pwUserID,$galleryTable)==true)
								$state	=	" checked";
							else
								$state	=	"";
							*/

							$galleryOwner	=	str_replace("APPDEV_GALLERY_","",$galleryTable);
							

					$moderatorQuery	=	"SELECT * FROM APPDEV_UserPermissions 
										 WHERE pwUserID='$pwUserID' 
										 AND pwPermission='$galleryTable'
										 AND pwModerator='1'";


					$authorQuery	=	"SELECT * FROM APPDEV_UserPermissions 
										 WHERE pwUserID='$pwUserID' 
										 AND pwPermission='$galleryTable'
										 AND pwContributor='1'";
		
					if($debug3) print "authorQuery: $authorQuery<br><br>";
					if($debug3) print "moderatorQuery: $moderatorQuery<br><br>";

					$author			=	GetNumberOfRows($authorQuery);	
					$moderator		=	GetNumberOfRows($moderatorQuery);

					// owner is special case
					if($galleryOwner == $pwUserID)
						{
						$moderator	=	1;
						}

					if($debug3) print "author: $author<br><br>";
					if($debug3) print "moderator: $moderator<br><br>";
					if($debug3) print "blogTable: $blogTable<br><br>";

					if($author && !$moderator)
						{
						if($debug3) print "AUTHOR<br><br>";
						$moderatorState	=	"";
						$authorState	=	"checked";
						}
					else if($moderator && !$author)
						{
						if($debug3) print "MODERATOR<br><br>";
						$moderatorState	=	"checked";
						$authorState	=	"";
						}
					else if($moderator && $author)
						{
						if($debug3) print "BOTH<br><br>";
						$moderatorState	=	"checked";
						$authorState	=	"checked";
						}
					else 	// no rights
						{
						if($debug3) print "NEITHER<br><br>";
						$moderatorState	=	"";
						$authorState	=	"";
						}




print 	'
		<tr> 
			<td width="18%" height="30" align="center" valign="middle" class="admin12Bold">
			
			
			<input name="checkbox_gm' . $i . '" type="checkbox" id="checkbox_gm' . $i . '" value="1" ' . $moderatorState . '>
			
			</td>
			
			
			
			<td width="16%" align="center" valign="middle" class="admin12Bold">
			
			<input name="checkbox_gc' . $i . '" type="checkbox" id="checkbox_gc' . $i . '" value="1" ' . $authorState . '>
			
			
			</td>
			<td class="admin12Bold" align="left" valign="middle">&nbsp;&nbsp;&nbsp;' . $galleryName . '</td>
		</tr>
		';

	
	

							}
					}
				?>
              </table>
              <p align="center"> 
                <input type="submit" name="Submit" value="Create New User">
                <input name="pwCaller" type="hidden" id="pwCaller" value="pw_new_blog_user.php">
                <input name="pwMode" type="hidden" id="pwMode" value="NewGranularRightsUser">
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
