<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include "../local_info.php";
include "db_lib.php";
include "cl_lib.php";
include 'gallery_lib.php';
include 'gallery_parms.php';	
include "html_lib.php";
include 'file_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'select_controls.php';	
include 'status_message.php';	
include "admin_lib.php";
include 'question_lib.php';
include "appdev_revision.php";

$debug	=	0;

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

$backLink				=	"gallery_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable";

$imageOwnerID			=	$systemUserID;
$galleryOwnerID			=	str_replace("APPDEV_GALLERY_","",$gaTable);


$query			=	"SELECT * FROM APPDEV_ImageLibrary 
					WHERE OwnerID='$imageOwnerID'
					AND ArtistID='$galleryOwnerID'";		

if(GetNumberOfRows($query))
	$userPrompt				=	"Click on the image you would like to delete...";
else
	$userPrompt				=	"You have no images in your gallery...";

// -------------------------------------------------------
// Figure out if its owner, moderator or contributor
//
// -------------------------------------------------------

$ownerTable	=	"APPDEV_GALLERY_" . $systemUserID;

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
	
// -------------------------------------------------------
// -------------------------------------------------------

if($debug) print "query: $query<br>";
if($debug) print "userPrompt: $userPrompt<br>";
if($debug) print "imageOwnerID: $imageOwnerID<br>";
if($debug) print "galleryOwnerID: $galleryOwnerID<br>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $galleryOwnerID	; ?> - Delete Image</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF">
<div align="center" class="admin18Bold"><?php print $galleryOwnerID	; ?> - Delete Image</div>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50"><table width="955" border="0" cellpadding="5" cellspacing="0" class="admin12Bold">
        <tr> 
          <td height="10" valign="middle"></td>
        </tr>
        <tr> 
          <td height="40" valign="middle"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#EEEEEE">
              <tr> 
                <td width="30%" height="40">
				&nbsp;&nbsp;&lt;&nbsp;<a href="<?php print $backLink; ?>" class="galleryUILink">Back</a> </td>
                <td align="center"><a href="gallery_help.php#DeleteImage" target="_blank" class="galleryUILink">Help</a> 
                </td>
                <td width="30%" align="right" class="admin12Bold">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="10" valign="top">&nbsp;</td>
        </tr>
        <tr> 
          <td height="40" valign="top"> 
            <p><?php print $userPrompt; ?></p>
            <hr> </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td> 
      <table width="955" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td> 
            <!-- Start rows -->
            <?php

			if($userIsModerator)
				PrintLibraryDeleteRows($galleryOwnerID,$galleryOwnerID);
			else
				PrintLibraryDeleteRows($imageOwnerID,$galleryOwnerID);
			?>
            <!-- End rows -->
          </td>
        </tr>
        <tr>
          <td height="40" align="left" valign="middle" bgcolor="#eeeeee">
            <?php
PrintUserMessage($systemUserID);
?>
          </td>
        </tr>
        <tr> 
          <td> 
            <?php
			// print the CT chop and footer
			PrintGalleryFooter();
			?>
          </td>
        </tr>
      </table> </td>
  </tr>
</table>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>



<?php
// lib functions

function PrintLibraryDeleteRows($imageOwnerID,$galleryOwnerID)
{
$debug			=	0;

// show either a sub-set or all the images
if($imageOwnerID == $galleryOwnerID)
	{
	// its the owner show all
	$query			=	"SELECT * FROM APPDEV_ImageLibrary 
						WHERE ArtistID='$galleryOwnerID'
						ORDER BY RecordNumber";		
	}
else
	{
	// show the granular users assets
	$query			=	"SELECT * FROM APPDEV_ImageLibrary 
						WHERE OwnerID='$imageOwnerID' AND ArtistID='$galleryOwnerID'
						ORDER BY RecordNumber";		
	}
	


$numberOfImages	=	GetNumberOfRows($query);

global	$gGalleryMountPoint;
global	$gBaseMountPoint;
global 	$gSiteURL;
global 	$systemPassword;
global	$systemUserID;
global	$gaTable;

if($debug) print "PrintLibraryDeleteRows($imageOwnerID,$galleryOwnerID)<br>";
if($debug) print "numberOfImages: $numberOfImages<br>";
if($debug) print "query: $query<br>";

if($numberOfImages % 4)
	{
	// odd
	if($debug) print "ODD<br>";
	$numberOfRows	=	((int)($numberOfImages/4)) + 1;		
	}
else
	{
	// even
	if($debug) print "EVEN<br>";
	$numberOfRows	=	($numberOfImages/4);		
	}
	
if($debug) print "numberOfRows: $numberOfRows<br>";

// fetch up a recordset
OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

$imageIndex		=	0;
$galleryPath	=	"$gBaseMountPoint$gGalleryMountPoint/$galleryOwnerID/200x/";

if($debug) print "galleryPath: $galleryPath<br>";


for($row=0;$row<$numberOfRows;$row++)
	{
	$imageLink			=	array();
	$imageFileName		=	array();

	// get the next 4 links or blank
	for($i=0;$i<4;$i++)
		{
		if($imageIndex < $numberOfImages)
			{
			$fn				=	GetDatabaseResult($rs,$imageIndex++,"FileName");					

			
			if($debug) print "$galleryPath$fn<br>";
			}
		else
			$fn	=	"";

		if($fn=="")
			{
			$imageLink[]		=	"&nbsp; <!-- Empty Cell -->";
			$imageFileName[]	=	"&nbsp; <!-- Empty Cell -->";
			}
		else
			{
			if($debug) print "<hr>";
			if($debug) print "systemUserID: $systemUserID<br>";
			if($debug) print "systemPassword: $systemPassword<br>";
			if($debug) print "<hr>";
			
			$imageFnAndPath	=	"$galleryPath$fn";
			$imageTag		=	'<img src="' . "$galleryPath$fn" . '" Border=0>';
			$questionArgs	=	quBuildArgs("gallery_services.php","gallery_delete.php","Delete this image from your library and gallery ?","$imageFnAndPath");	
			
			$imageLink[] =	'<a href="question_yn.php?' .
			"$questionArgs&gaTable=$gaTable&gaMode=DeleteImage&gaArtistID=$imageOwnerID&gaFileName=$fn&SystemPassword=$systemPassword&SystemUserID=$systemUserID" . 
			'">' .
			$imageTag .
			'</a>';

			$imageFileName[] =	'
							<span class="admin10Regular"> ' . $fn . ' </span>
							';
			}
		} // for($i=0;$i<4;$i++)
		
	// print the row
	$i	=	0;
	print	'
	<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
	  <tr> 
		<td valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr> 
				<!-- Start One Cell -->
			  <td width="25%" height="225" align="center" valign="top"> 
			  <table width="40" border="0" cellpadding="0" cellspacing="0">
				  <tr> 
					<td height="5"> </td>
				  </tr>
				  <tr> 
					<td align="center" valign="top"> 
					' . $imageLink[$i]. '
					</td>
				  </tr>
				  <tr> 
					<td height="20" align="left" valign="middle"> 
					' . $imageFileName[$i++] . ' </td>
				  </tr>
				</table>
				</td>
				<!-- End One Cell -->
	
				<!-- Start One Cell -->
			  <td width="25%" height="225" align="center" valign="top"> 
			  <table width="40" border="0" cellpadding="0" cellspacing="0">
				  <tr> 
					<td height="5"> </td>
				  </tr>
				  <tr> 
					<td align="center" valign="top"> 
					' . $imageLink[$i]. '
					</td>
				  </tr>
				  <tr> 
					<td height="20" align="left" valign="middle"> 
					' . $imageFileName[$i++] . ' </td>
				  </tr>
				</table>
				</td>
				<!-- End One Cell -->
				
				
				<!-- Start One Cell -->
			  <td width="25%" height="225" align="center" valign="top"> 
			  <table width="40" border="0" cellpadding="0" cellspacing="0">
				  <tr> 
					<td height="5"> </td>
				  </tr>
				  <tr> 
					<td align="center" valign="top"> 
					' . $imageLink[$i]. '
					</td>
				  </tr>
				  <tr> 
					<td height="20" align="left" valign="middle"> 
					' . $imageFileName[$i++] . ' </td>
				  </tr>
				</table>
				</td>
				<!-- End One Cell -->
				
				<!-- Start One Cell -->
			  <td width="25%" height="225" align="center" valign="top"> 
			  <table width="40" border="0" cellpadding="0" cellspacing="0">
				  <tr> 
					<td height="5"> </td>
				  </tr>
				  <tr> 
					<td align="center" valign="top"> 
					' . $imageLink[$i]. '
					</td>
				  </tr>
				  <tr> 
					<td height="20" align="left" valign="middle"> 
					' . $imageFileName[$i++] . ' </td>
				  </tr>
				</table>
				</td>
				<!-- End One Cell -->
				
			</tr>
		  </table></td>
	  </tr>
	</table><br><hr><br>
			';
	} // for($row=0;$row<$numberOfRows;$row++)


	
}




?>
