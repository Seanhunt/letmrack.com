<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_add.php
//
// This will upload two images and insert the title, caption and fn in the DB
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

include "../local_info.php";
include "db_lib.php";
include "cl_lib.php";
include 'html_lib.php';	
include 'file_lib.php';
include 'gallery_parms.php';	
include 'gallery_lib.php';	
include 'pw_parms.php';	
include 'pw_lib.php';
include 'select_controls.php';	
include 'status_message.php';	
include "admin_lib.php";
include "appdev_revision.php";

$debug	=	0;

if(AuthorizeGalleryUser($systemUserID,$systemPassword)==FALSE)
	{
	print "Access denied.<br>$systemUserID is not a valid user<br>";
	exit(0);
	}

if($gaArtist=="")
	$gaArtist			=	GetFieldByComparison("APPDEV_GalleryProfiles","ArtistName","ArtistID",$systemUserID);

$numberOfDetailImages	=	GetNumberOfDetailImages($gaFileName,$systemUserID);
$backLink				=	"gallery_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";
$gaMode					=	"AddDetail";

$detailImages			=	array();
$deleteLink				=	array();

$deleteLink[0]			=	"";
$deleteLink[1]			=	"";
$deleteLink[2]			=	"";
$deleteLink[3]			=	"";

$detailImages[0]		=	"";
$detailImages[1]		=	"";
$detailImages[2]		=	"";
$detailImages[3]		=	"";

for($i=0;$i<$numberOfDetailImages;$i++)
	{
	$path				=	"$gBaseMountPoint$gGalleryMountPoint/$gaArtistID/200x/";
	$detailFileName		=	GetFieldByIndex("APPDEV_ImageDetails","DetailFileName",$i);
	$theImageURL		=	"$path$detailFileName";
	$deleteURL			=	"gallery_services.php?gaDetailFileName=$detailFileName&gaMode=DeleteDetail&gaArtistID=$gaArtistID&gaFileName=$gaFileName&SystemUserID=$systemUserID&SystemPassword=$systemPassword";

	$detailImages[$i]	=	ImageTag($theImageURL);
	$deleteLink[$i]		=	'<a class="boldLink10" href="' . $deleteURL . '">Delete</a>';
	}

// set up the root image
if($gaFileName != "")
	{
	$rootImage	=	"$gBaseMountPoint$gGalleryMountPoint/$gaArtistID/200x/$gaFileName";	
	}

if($debug) print "numberOfDetailImages: $numberOfDetailImages<br>";
if($debug) print "gaGallery: $gaGallery<br>";
if($debug) print "gaArtist: $gaArtist<br>";
if($debug) print "gaArtistID: $gaArtistID<br>";
if($debug) print "gaFileName: $gaFileName<br>";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Add Detail Image</title>

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
<p align="center" class="admin18Bold">Add Detail to Image</p>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top">
<form name="AddAnImageForm" enctype="multipart/form-data" method="POST" action="gallery_services.php">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr bgcolor="#EEEEEE"> 
                  <td width="30%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink"> 
                    Back</a> </td>
                  <td align="center"><a href="gallery_help.php#AddImages" target="_blank" class="galleryUILink">Help</a> 
                  </td>
                  <td width="30%" height="40" align="right">&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td align="center"> 
              <!-- Note, This tag must be above the file input fields -->
              <input type="hidden" name="MAX_FILE_SIZE" value="3000000" /> 
              <table width="95%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td colspan="2" valign="top" class="admin12Regular"><p>This 
                      dialog allows you to add detail images for the images in 
                      your gallery.</p>
                    <p>Detail images can be used to show presentation, color or 
                      style variations for your gallery offering.</p></td>
                </tr>
                <tr>
                  <td height="10" colspan="2" valign="top" class="admin12Regular">&nbsp;</td>
                </tr>
                <tr> 
                  <td width="31%" height="200" align="center" valign="top" class="admin12Regular"><table width="260" height="260" border="1" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center" valign="middle"> 
                          <?php
					if($rootImage != "")
						{
						print	'
								<table width="200" border="0" cellspacing="0" cellpadding="0">
								  <tr> 
									<td height="30" class="admin10Regular">Set Detail Pictures 
									  For This Image </td>
								  </tr>
								  <tr> 
									<td><img src="' . $rootImage . '"></td>
								  </tr>
								  <tr>
									<td height="30" class="admin10Regular">' . $gaFileName . '</td>
								  </tr>
								</table>
								';
						}
					
					?>
                        </td>
                      </tr>
                    </table> 
                    <p>&nbsp;</p></td>
                  <td width="69%" align="right" valign="top"> <table class="admin14Bold" width="97%" border="1" cellspacing="0" cellpadding="5">
                      <tr> 
                        <td height="86" class="admin12Bold"> <span class="admin18BoldRed">&nbsp;&nbsp;*</span> 
                          Add Detail To...</td>
                        <td> 
                          <?php
							// --------------------------------------------------------------------------------
							// print the user ID's
							// --------------------------------------------------------------------------------
							$galleryTableName	=	"APPDEV_GALLERY_$systemUserID";
							$query				=	"SELECT * FROM $galleryTableName ORDER BY FileName";
							OpenDatabase();

							$rs	=	QueryDatabase($query);
							
							$numberOfImages		=	mysql_num_rows($rs);
							
							if($debug2) print "query: $query<br>";
							if($debug2) print "galleryTableName: $galleryTableName<br>";
							if($debug2) print "numberOfImages: $numberOfImages<br>";
							if($debug2) print "gaFileName: $gaFileName<br>";

							CloseDatabase();
							
							$galleryArray		=	array();
							
							$galleryArray[]		=	"Please Pick One...";
								
							for($i=0;$i<$numberOfImages;$i++)
								{
								$fileName	=	GetDatabaseResult($rs,$i,"FileName");
								
								$galleryArray[]	=	$fileName;
								
								if($debug2) print "fileName: $fileName<br>";
								}
				
								DisplaySelector("gaFileName",$galleryArray,$galleryArray,$gaFileName);				
								?>
                          <input name="Submit" type="submit" id="Submit" value="Preview"> 
                        </td>
                      </tr>
                      <tr> 
                        <td width="29%" height="86" class="admin12Bold"> <span class="admin18BoldRed">&nbsp;&nbsp;*</span> 
                          500x Picture </td>
                        <td width="71%"> <input name="gaFileToSend500x" type="file" id="gaFileToSend500x" size="30" /> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="87" colspan="2" align="center" class="admin12Bold"> 
                          <input name="Submit" type="submit" id="Submit" value="Add"> 
                          <input name="gaMode" type="hidden" id="gaMode2" value="AddDetail"> 
                          <input name="SystemPassword" type="hidden" id="SystemPassword2" value="<?php print $systemPassword; ?>"> 
                          <input name="SystemUserID" type="hidden" id="SystemUserID2" value="<?php print $systemUserID; ?>"> 
                          <input name="gaArtistID" type="hidden" id="gaArtistID2" value="<?php print $systemUserID; ?>"> 
                        </td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              
            </td>
          </tr>
          <tr>
            <td height="50" align="left">






<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr align="center"> 
                  <td width="200"> 
                    <table width="10" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><?php print $detailImages[0]; ?></td>
                      </tr>
                      <tr>
                        <td height="25"><?php print $deleteLink[0]; ?></td>
                      </tr>
                    </table></td>
                  <td width="200"> 
                    <table width="10" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><?php print $detailImages[1]; ?></td>
                      </tr>
                      <tr>
                        <td height="25"><?php print $deleteLink[1]; ?></td>
                      </tr>
                    </table></td>
                  <td width="200"> 
                    <table width="10" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><?php print $detailImages[2]; ?></td>
                      </tr>
                      <tr>
                        <td height="25"><?php print $deleteLink[2]; ?></td>
                      </tr>
                    </table></td>
                  <td width="200"> 
                    <table width="10" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><?php print $detailImages[3]; ?></td>
                      </tr>
                      <tr>
                        <td height="25"><?php print $deleteLink[3]; ?></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              <hr></td>
          </tr>
          <tr> 
            <td height="50" align="left" class="admin15BoldRed">&nbsp;&nbsp;* 
              Required Fields to Add Detail Image</td>
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
        </table>
        </form> 
      
    </td>
  </tr>
</table>


<p>&nbsp;</p>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);

?>