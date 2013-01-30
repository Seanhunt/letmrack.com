
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
include 'file_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
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


// get number of blogs
$numberOfBlogs	=	GetNumberOfRows("SELECT blTable FROM APPDEV_BlogHeaders");

// get number of blogs
$numberOfGalleries	=	GetNumberOfRows("SELECT Title FROM APPDEV_GalleryProfiles");

if($debug) print "numberOfBlogs: $numberOfBlogs<br>";
if($debug) print "numberOfGalleries: $numberOfGalleries<br>";



if($debug) print "submit: $submit<br>";

if($submit=="Modify User")
	{
	if($debug) print "Modify Mode<br><hr><br>";
	if($debug) print "pwPassword: $pwPassword<br>";
	if($debug) print "pwFirstName: $pwFirstName<br>";
	if($debug) print "pwLastName: $pwLastName<br>";
	if($debug) print "pwRightsID: $pwRightsID<br>";
	if($debug) print "pwEMail: $pwEMail<hr><br>";


	// -----------------------------------------------------------------------------------------
	// gotta have the goop or gank
	// -----------------------------------------------------------------------------------------

	if(($pwEMail =="") && !$error)
		{
		// Cant create
		$statusMessage	= 	"Please enter an email address...";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}

	if(($pwFirstName ==""))
		{
		// Cant create
		$statusMessage	= 	"Please enter a first name...";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}

	if(($pwLastName =="") && !$error)
		{
		// Cant create
		$statusMessage	= 	"Please enter a last name...";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}

	if(($pwPassword =="") && !$error)
		{
		// Cant create
		$statusMessage	= 	"Please enter a last name...";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}


	// mod the user record
	if(!$error)
		{
		// change up the user info
		$rv	= ModifyUserInfo($pwUserID,$pwPassword,$pwRightsID,$pwEMail,$pwFirstName,$pwLastName,$pwRecordNumber);
	
		if($rv)
			$statusMessage	=	"User records modified";
		else
			{
			$statusMessage	=	"Unable to modify user records";
			$error			=	1;
			}
		}
			
	// set up the permissions
	if(!$error)
		{
		// clean up any old permissions
		DeleteUserPermissions($pwUserID,"Blog");
	
		// go through the blog table and see what is checked
		$numberOfBlogs	=	GetNumberOfRows("SELECT * FROM APPDEV_BlogHeaders");
	
		if($debug) print "numberOfBlogs: $numberOfBlogs<br>";

		// -----------------------------------------------------------------------------------------
		// Iterate the 0-n... checkboxes from the dialog and grant rights as indicated
		// -----------------------------------------------------------------------------------------
	
		for($i=0;$i<$numberOfBlogs;$i++)
			{
			$moderatorCheckBox		=	"checkbox_bm$i";
			$contributorCheckBox	=	"checkbox_bc$i";

			$m	=	GetPassedValue($moderatorCheckBox);
			$c	=	GetPassedValue($contributorCheckBox);

			if($debug) print "checkbox_bm$i: $m<br>";
			if($debug) print "checkbox_bc$i: $c<br>";
	
			$blogTable		=	GetFieldByIndex("APPDEV_BlogHeaders","blTable",$i);

			// set up the permission
			if($m && $c)
				{
				if($debug) print "MODERATOR - $i: $blogTable<br>";

				NewUserPermission($pwUserID,$blogTable,1,0);
				}
			else if(!$m && $c)
				{
				if($debug) print "CONTRIBUTOR - $i: $blogTable<br>";
				NewUserPermission($pwUserID,$blogTable,0,1);
				}
			else if($m && !$c)
				{
				if($debug) print "MODERATOR - $i: $blogTable<br>";
				NewUserPermission($pwUserID,$blogTable,1,0);
				}
			else
				{
				if($debug) print "NO RIGHTS - $i: $blogTable<br>";
				//NewUserPermission($pwUserID,$blogTable,0,0);
				}			
			}		

		// go through the galleries
		$numberOfGalleries	=	GetNumberOfRows("SELECT * FROM APPDEV_GalleryProfiles");
	
		if($debug) print "numberOfGalleries: $numberOfGalleries<br>";
		
		// -----------------------------------------------------------------------------------------
		// Iterate the 0-n... checkboxes from the dialog and grant rights as indicated
		// -----------------------------------------------------------------------------------------
	
		DeleteUserPermissions($pwUserID,"Gallery");	
	
		for($i=0;$i<$numberOfGalleries;$i++)
			{
			$moderatorCheckBox		=	"checkbox_gm$i";
			$contributorCheckBox	=	"checkbox_gc$i";

			$m	=	GetPassedValue($moderatorCheckBox);
			$c	=	GetPassedValue($contributorCheckBox);

			if($debug) print "checkbox_gm$i: $m<br>";
			if($debug) print "checkbox_gc$i: $c<br>";
	
			$galleryTable		=	"APPDEV_GALLERY_" . GetFieldByIndex("APPDEV_GalleryProfiles","ArtistID",$i);

			// set up the permission
			if($m && $c)
				{
				if($debug) print "MODERATOR X X - $i: $galleryTable<br>";

				NewUserPermission($pwUserID,$galleryTable,1,0);
				}
			else if(!$m && $c)
				{
				if($debug) print "CONTRIBUTOR - $i: $galleryTable<br>";
				NewUserPermission($pwUserID,$galleryTable,0,1);
				}
			else if($m && !$c)
				{
				if($debug) print "MODERATOR  X - $i: $galleryTable<br>";
				NewUserPermission($pwUserID,$galleryTable,1,0);
				}
			else
				{
				if($debug) print "NO RIGHTS - $i: $galleryTable<br>";
				}			
			
			} // end of for($i=0;$i<$numberOfGalleries;$i++)		
		} // end of if(!$error)
		
	$submit	=	"View";
	}

if($submit=="View")
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

if($submit=="Delete User")
	{
	if($debug) print "Delete Mode<br><hr><br>";
	if($debug) print "pwUserID: $pwUserID<br>";

	$rv	=	DeleteUser($pwUserID);

	$pwUserID		=	"";
	$pwPassword		=	"";
	$pwFirstName	=	"";
	$pwLastName		=	"";
	$pwRightsID		=	"";
	$pwEMail		=	"";

	if($rv)
		$statusMessage	=	"User record deleted";
	else
		$statusMessage	=	"Unable to delete user record";
	}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Edit User</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle" class="admin18Bold">Edit or 
      Delete User</td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#EditSystemUser" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="7%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="77%" valign="top"> 
            <table width="84%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>
                <form name="form1" method="get" action="password_edit_user.php">
                    <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
                      <tr> 
                        <td height="35" colspan="2" align="right" valign="middle" class="admin12Bold" >Pick 
                          User ID:&nbsp;&nbsp;</td>
                        <td width="73%" align="left" valign="middle">&nbsp;&nbsp; 
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
						
						SubmitButton("Submit","View");
						?>                      
                      </tr>
                      <tr> 
                        <td height="35" colspan="2" align="right" valign="middle" class="admin12Bold" >First 
                          Name:&nbsp;&nbsp;</td>
                        <td align="left" valign="middle">&nbsp;&nbsp; <input name="pwFirstName" type="text" id="pwFirstName" value="<?php print $pwFirstName; ?>" size="40"></td>
                      </tr>
                      <tr> 
                        <td height="35" colspan="2" align="right" valign="middle" class="admin12Bold" >Last 
                          Name:&nbsp;&nbsp;</td>
                        <td align="left" valign="middle">&nbsp;&nbsp; <input name="pwLastName" type="text" id="pwLastName" value="<?php print $pwLastName; ?>" size="40"></td>
                      </tr>
                      <tr> 
                        <td height="35" colspan="2" align="right" valign="middle" class="admin12Bold" >Rights:&nbsp;&nbsp;</td>
                        <td align="left" valign="middle"> &nbsp;&nbsp; 
                          <?php
							$numberOfRights		=	GetNumberOfRights();
						
							$debug	=	0;
							
							if($debug) print "rightsTableName: $rightsTableName<br>";
							if($debug) print "numberOfRights: $numberOfRights<br>";
							if($debug) print "userType: $userType<br>";
					
							$rightsArray	=	array();
						
						
							for($i=0;$i<$numberOfRights;$i++)
								{
								$right			=	GetFieldByIndex("APPDEV_SystemRights","pwRightsID",$i);
								$rightsArray[]	=	$right;
					
								if($debug) print "right: $right<br>";
								}
						
							DisplaySelector("pwRightsID",$rightsArray,$rightsArray,$pwRightsID);				
							?>                        </td>
                      </tr>
                      <tr> 
                        <td height="35" colspan="2" align="right" valign="middle" class="admin12Bold" >E-Mail:&nbsp;&nbsp;</td>
                        <td align="left" valign="middle">&nbsp;&nbsp; 
						<input name="pwEMail" type="text" id="pwEMail" value="<?php print $pwEMail; ?>" size="40"></td>
                      </tr>
                      <tr>
                        <td height="35" colspan="2" align="right" valign="middle" class="admin12Bold" >Password:&nbsp;&nbsp;</td>
                        <td align="left" valign="middle">&nbsp;&nbsp;
                          <input name="pwPassword" type="text" id="pwPassword" value="<?php print $pwPassword; ?>" size="40"></td>
                      </tr>
                    
					
					

					



					<?php
					// only show if applies
					if($pwRightsID=="Blog-Gallery")
						{
						// ---------------------------------------------------------------
						// get number of blogs
						// ---------------------------------------------------------------



						print	'
<tr> 
    <td width="18%" height="30" align="center" valign="middle" class="admin10Regular">Moderator</td>
    <td width="16%" align="center" valign="middle" class="admin10Regular">Contributor</td>
    <td class="admin10Regular" align="left" valign="middle" >&nbsp;&nbsp;Blog Name</td>
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
						if($debug) print "AUTHOR<br><br>";
						$moderatorState	=	"";
						$authorState	=	"checked";
						}
					else if($moderator && !$author)
						{
						if($debug) print "MODERATOR<br><br>";
						$moderatorState	=	"checked";
						$authorState	=	"";
						}
					else if($moderator && $author)
						{
						if($debug) print "BOTH<br><br>";
						$moderatorState	=	"checked";
						$authorState	=	"checked";
						}
					else 	// no rights
						{
						if($debug) print "NEITHER<br><br>";
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
                    <br>
                    <table width="30%" border="0" align="center" cellpadding="10" cellspacing="0">
                      <tr> 
                        <td width="50%" align="center"> 
                          <?php if($pwUserID != "") SubmitButton("Submit","Modify User"); ?>
                        </td>
                        <td align="center"> 
                          <?php if($pwUserID != "") SubmitButton("Submit","Delete User"); ?>
                        </td>
                      </tr>
                    </table>
                    <p align="center"> 
                      <input name="TargetID" type="hidden" id="TargetID" value="<?php print $targetID; ?>">
                      <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
                      <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
					<input name="pwRecordNumber" type="hidden" id="pwRecordNumber" value="<?php print $pwRecordNumber; ?>">                    
					</p>
                </form></td>
              </tr>
            </table>
            
          </td>
          <td width="16%" bgcolor="#999999">&nbsp;</td>
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
