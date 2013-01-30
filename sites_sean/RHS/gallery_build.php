
<?php

// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_build.php
//
// This allows building a gallery from the library
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
include "admin_lib.php";
include 'question_lib.php';
include 'blog_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include "html_lib.php";
include 'file_lib.php';
include 'gallery_lib.php';
include 'gallery_parms.php';
include "appdev_revision.php";
include "redirect.php";
include "name_value_pair_lib.php";

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

// -------------------------------------------------------
// Figure out if its owner, moderator or contributor
//
// -------------------------------------------------------

$galleryOwnerID			=	str_replace("APPDEV_GALLERY_","",$gaTable);
$ownerTable				=	"APPDEV_GALLERY_" . $systemUserID;

if($gaTable == $ownerTable)
	{
	$userIsOwner	=	true; // enable extra menu options

	$gaArtistID		=	$galleryOwnerID; // moderator or owner

	// owner query
	$query			=	"SELECT * FROM APPDEV_ImageLibrary WHERE ArtistID='$galleryOwnerID' ORDER BY RecordNumber";		

	if($debug) print "User is: OWNER<br>";
	}
else
	{
	if(UserIsModerator($gaTable,$systemUserID))
		{
		$userIsModerator	=	true; // enable extra menu options

		$gaArtistID			=	$galleryOwnerID; // moderator or owner

		// owner query
		$query			=	"SELECT * FROM APPDEV_ImageLibrary WHERE ArtistID='$galleryOwnerID' ORDER BY RecordNumber";		

		if($debug) print "User is: MODERATOR<br>";
		}
	else
		{
		if($debug) print "User is: CONTRIBUTOR<br>";

		$gaArtistID			=	$systemUserID;

		// contrib query
		$query			=	"SELECT * FROM APPDEV_ImageLibrary WHERE OwnerID='$systemUserID' ORDER BY RecordNumber";		
		}
	}
	
// -------------------------------------------------------
// -------------------------------------------------------

$ownerID			=	str_replace("APPDEV_GALLERY_","",$gaTable);
$first				=	GetFieldByComparison("APPDEV_UserInfo","pwFirstName","pwUserID",$systemUserID);
$last				=	GetFieldByComparison("APPDEV_UserInfo","pwLastName","pwUserID",$systemUserID);
$gaArtist			=	"$first $last";

// ---------------------------------------------------------------------------------------------------------------------------------------

$backLink				=	"gallery_home.php?gaTable=$gaTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";



$galleryTableName		=	"APPDEV_GALLERY_$systemUserID";
$galleryQuery			=	"SELECT * FROM APPDEV_GALLERY_$ownerID WHERE 1";		
$libraryQuery			=	"SELECT * FROM APPDEV_ImageLibrary WHERE ArtistID='$ownerID' ORDER BY RecordNumber";		

$numberOfImages	=	GetNumberOfRows($libraryQuery);


if($debug) print "numberOfImages: $numberOfImages<br>";
if($debug) print "galleryTableName: $galleryTableName<br>";
if($debug) print "galleryQuery: $galleryQuery<br>";
if($debug) print "libraryQuery: $libraryQuery<br>";

// get the gallery table fn's
OpenDatabase();

$libraryRS	=	QueryDatabase($libraryQuery);
$galleryRS	=	QueryDatabase($galleryQuery);

CloseDatabase();

$gFileNameArray		=	array();

// make an array of lib fn's that don't include the fn's in the gallery
$numberOfAvailableImages	=	0;

for($i=0;$i<$numberOfImages;$i++)
	{
	$libFn		=		GetDatabaseResult($libraryRS,$i,"FileName");	

	if($debug) print "libFn: $libFn<br>";

	$query			=	"SELECT * FROM APPDEV_GALLERY_$galleryOwnerID WHERE FileName='$libFn'";		

	//if($debug) print "query: $query<br>";
	
	// if this fn is not in the gallery include it in the list
	if(GetNumberOfRows($query)==0)
		{
		//if($debug) print "ADDED<br>";
		++$numberOfAvailableImages;
		$gFileNameArray[]	=	$libFn;
		}
	else
		{
		//if($debug) print "NOT ADDED<br>";
		}
	}

// if done send home with message
if($numberOfAvailableImages==0)
	{
	$message	=	"Your gallery is complete!";
	$uriString	=	"$backLink&StatusMessage=$message";
	FormRedirect($uriString,"POST",$debug);
	}

?>

<?php if($numberOfAvailableImages) { // funky but it neatens up the draw when it redirects to home  ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $galleryOwnerID; ?> - Reorganize Gallery</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF">
<div align="center" class="admin18Bold">
  <p><?php print $galleryOwnerID; ?> - Reorganize Gallery</p>
</div>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50"><table width="955" border="0" cellpadding="5" cellspacing="0" class="admin12Bold">
        <tr>
          <td height="40" valign="middle" bgcolor="#EEEEEE"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="30%" height="40">
				&nbsp;</td>
                <td align="center"><a href="gallery_help.php#BuildGallery" target="_blank" class="galleryUILink">Help</a> 
                </td>
                <td width="30%" align="right" class="admin12Bold">You have <?php print $numberOfAvailableImages;?> images remaining to add &nbsp;</td>
              </tr>
            </table> </td>
        </tr>
        <tr> 
          <td height="100" valign="middle"> 
          
            <p>Click on the images in the order you would like to add to your gallery.</p>
             
			<p>The display will refresh and that image will disappear from the list of pictures to be added.</p>
			<p>When you have added all of the images you will be automatically redirected home.</p>
		  <hr> </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td> 
      <table width="955" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="40" align="center" class="admin18Bold">
          
         Images Remaining to be Added
          
          
          </td>
        </tr>
        <tr> 
          <td> 
            <!-- Start rows -->
            <?php
			PrintGalleryAddRows($gaArtistID);
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
<?php } // funky but it neatens up the draw when it redirects to home ?>



<?php
// lib functions



function PrintGalleryAddRows($artistID)
{
$query			=	"SELECT * FROM APPDEV_ImageLibrary WHERE ArtistID='$artistID' ORDER BY RecordNumber";		
$numberOfImages	=	GetNumberOfRows($query);
$debug			=	0;

global	$gGalleryMountPoint;
global	$gBaseMountPoint;
global 	$gSiteURL;
global 	$systemPassword;
global	$systemUserID;
global 	$gFileNameArray; // from above
global 	$gaTable; // from above

if($debug) print "PrintLibraryDeleteRows($artistID)<br>";
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
$galleryPath	=	"$gBaseMountPoint$gGalleryMountPoint/$artistID/200x/";

for($row=0;$row<$numberOfRows;$row++)
	{
	$imageLink		=	array();
	$textLink		=	array();

	
	// get the next 4 links or blank
	for($i=0;$i<4;$i++)
		{
		if($imageIndex < $numberOfImages)
			{
//			$fn				=	GetDatabaseResult($rs,$imageIndex++,"FileName");					
			$fn				=	$gFileNameArray[$imageIndex++];
			
			if($debug) print "$galleryPath$fn<br>";
			}
		else
			$fn	=	"";

		if($fn=="")
			{
			$imageLink[]	=	"&nbsp; <!-- Empty Cell -->";
			$textLink[]		=	"&nbsp; <!-- Empty Cell -->";
			
			}
		else
			{
			
if($debug) print "<hr>";
if($debug) print "systemUserID: $systemUserID<br>";
if($debug) print "systemPassword: $systemPassword<br>";
if($debug) print "<hr>";
			
			
			$imageLink[] =	'<a href="gallery_services.php?gaTable=' . $gaTable . '&gaMode=AddImageToGallery&gaFileName=' 
			. $fn . "&gaTable=$gaTable&SystemPassword=$systemPassword&SystemUserID=$systemUserID" . 
			'">
			<img src="' . "$galleryPath$fn" . '" border="0"> 
			</a>
			';

			$textLink[] =	'
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
					' . $textLink[$i++] . ' </td>
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
					' . $textLink[$i++] . ' </td>
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
					' . $textLink[$i++] . ' </td>
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
					' . $textLink[$i++] . ' </td>
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
