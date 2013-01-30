
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_home.php
//
// This is the entry point of the user module for the gallery admin section
//
//
// Revision 2.2.0.2
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
include 'file_lib.php';
include 'blog_lib.php';
include 'gallery_parms.php';	
include 'html_lib.php';	
include 'gallery_lib.php';	
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include "admin_lib.php";
include 'appdev_revision.php';	

$debug	=	0;


// there is a shading of the rights here in that when you make a gallery
// the OWNERS name in embedded in the table name.


// ---------------------------------------------------------------------------------------------------------------------------------------
// this is the new to 2.2.0 auth section for granular galleries
// ---------------------------------------------------------------------------------------------------------------------------------------


if(AuthorizeAdminUser($systemUserID,$systemPassword)==true)
	{
	$userIsAdmin	=	true;
	if($debug) print "User is: ADMINISTRATOR or SUPER<br>";
	}
else
	{
	if($debug) print "User is: GRANULAR<br>";

	if(AuthorizeGalleryUser($systemUserID,$systemPassword,$gaTable)==false)
		{
		print "Access denied.<br><br>";
		exit(0);
		}
	}

$ownerTable			=	"APPDEV_GALLERY_" . $systemUserID;



if($gaTable == $ownerTable)
	{
	$userIsOwner	=	true; // enable extra menu options

	if($debug) print "User is: OWNER<br>";
	}
else
	{
	if(UserIsModerator($gaTable,$systemUserID))
		{
		$userIsModerator	=	true; // enable extra menu options

		if($debug) print "User is: MODERATOR<br>";
		}
	else
		{
		if($debug) print "User is: CONTRIBUTOR<br>";
		}
	}	

$ownerID			=	str_replace("APPDEV_GALLERY_","",$gaTable);

$thumbsPageName		=	GetGalleryThumbsPage($ownerID);


// ---------------------------------------------------------------------------------------------------------------------------------------

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $ownerID; ?> - Gallery Home</title>
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
<p align="center" class="admin18Bold"><?php print $ownerID; ?> - Gallery Home</p>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;< <a class="boldLink14" href="#" onClick="javascript:window.close();">Close 
            Window</a></td>
          <td width="33%" align="center"><a href="gallery_help.php#Overview" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="10%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="2%">&nbsp;</td>
          <td width="35%"> 
            <?php

			$truncateGalleryQuestionArgs	=	quBuildArgs("gallery_services.php","gallery_home.php","This will clear your existing gallery","Continue ?");
			$reorganizeGalleryQuestionArgs	=	quBuildArgs("gallery_services.php","gallery_home.php","Do you really want to reorganize your gallery ?","In the next step you will re-add all of the pictures in your preferred order.<br><br><b>Continue ?</b>");
				
			// edit gallery profile
			if($userIsOwner || $userIsModerator)
				{
				print '<p><a href="gallery_profile.php?' . "SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable" . '" class="boldLink14">Edit Gallery Profile</a></p>';
				}
			
			print '<p><a href="gallery_add.php?' . "SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable" . '" class="boldLink14">Add Image</a></p>';

			
			// 072307
			if($gGalleryDetailPictures)
				{
				print '<p><a href="gallery_detail.php?' . "SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable" . '" class="boldLink14">Add Detail Images</a></p>';
				}

			print '<p><a href="gallery_delete.php?' . "gaArtistID=$systemUserID&SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable" . '" class="boldLink14">Delete Image</a></p>';

			print '<p><a href="gallery_library_preview.php?' . "gaArtistID=$systemUserID&SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable" . '" class="boldLink14">Edit Image Text</a></p>';


			if($userIsOwner || $userIsModerator)
				{
				//print '<p><a href="gallery_build.php?' . "&gaArtistID=$systemUserID&SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable" . '" class="boldLink14">Reorder Gallery</a></p>';
	
				//print '<p><a href="question_yn.php?' . $truncateGalleryQuestionArgs . "&gaMode=TruncateGallery&gaArtistID=$systemUserID&SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable" . '" class="boldLink14">Clear Gallery</a></p>';


				print '<p><a href="question_yn.php?' . $reorganizeGalleryQuestionArgs . "&gaMode=TruncateGallery&gaArtistID=$systemUserID&SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable" . '" class="boldLink14">Reorganize Gallery</a></p>';


	
//				print '<p><a target="_galleryPreview_" href="gallery_preview.php?' . "SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaArtistID=$systemUserID&gaTable=$gaTable" . '" class="boldLink14">View Profile</a></p>';
//	
				}

			print '<p><a target="_galleryPreview_" href="../' .$thumbsPageName. '?' . "gaGallery=$gaTable&gaPageNumber=1&SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable" . '" class="boldLink14">View Gallery</a></p>';

		  ?>
          </td>
          <td width="42%" bgcolor="#999999">&nbsp;</td>
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
	PrintGalleryFooter();
	?>
    </td>
  </tr>
</table>
<p>&nbsp;</p>


			  
	  
	  

  <input name="UserID" type="hidden" id="UserID" value="<?php print $userID; ?>">
  <input name="Password" type="hidden" id="Password" value="<?php print $password; ?>">
</form>


<?php
?>


</body>
</html>



<?php
DisplayStatusMessage($statusMessage);
?>
