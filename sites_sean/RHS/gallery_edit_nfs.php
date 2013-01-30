<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_edit_nfs.php
//
// This allows editing of the text part of the image lib datas
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
include 'gallery_parms.php';	
include "html_lib.php";
include 'file_lib.php';
include 'gallery_lib.php';	
include 'pw_parms.php';	
include 'pw_lib.php';
include 'select_controls.php';	
include 'status_message.php';	

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

$ownerTable	=	"APPDEV_GALLERY_" . $systemUserID;

if($gaTable == $ownerTable)
	{
	$userIsOwner	=	true;

	if($debug) print "User is: OWNER<br>";
	}
else
	{
	if($debug) print "User is: NOT OWNER<br>";
	}	

$ownerID			=	str_replace("APPDEV_GALLERY_","",$gaTable);
$first				=	GetFieldByComparison("APPDEV_UserInfo","pwFirstName","pwUserID",$systemUserID);
$last				=	GetFieldByComparison("APPDEV_UserInfo","pwLastName","pwUserID",$systemUserID);
$gaArtist			=	"$first $last";

// ---------------------------------------------------------------------------------------------------------------------------------------



$debug	=	0;

LoadImageDataFromLibrary($gaRecordNumber,$gaTable);

$fileNameAndPath	=	"$gBaseMountPoint$gGalleryMountPoint/$ownerID/500x/$gaFileName";

$backLink			=	"gallery_library_preview.php?gaTable=$gaTable&gaArtistID=$systemUserID&SystemUserID=$systemUserID&SystemPassword=$systemPassword#$gaAnchor";
$homeLink			=	"gallery_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";

if($debug) print "gaRecordNumber: $gaRecordNumber<br>";
if($debug) print "gaArtistID: $gaArtistID<br>";
if($debug) print "gaFileName: $gaFileName<br>";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $ownerID; ?> - Edit Image Text</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- Hide from the robots -->		
<meta name="robots" content="noindex,nofollow">
<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
<META NAME="GOOGLEBOT" CONTENT="nofollow">
<META NAME="GOOGLEBOT" CONTENT="noindex">
<!-- End Hide from the robots -->



<?php
if($gUseTinyMCE)
	{
	// 2.2.0.2 updated this to use a global to describe the props
	// for the editor window
	print	'
			<!-- tinyMCE -->
			<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
			<script language="javascript" type="text/javascript">
				tinyMCE.init({' .
				
				$gTinyMCEProperties
				
				. 'height:"350px",
				width:"300px",
			  });
			</script>
			<!-- /tinyMCE -->
			';
	
	// fix up the copy string
	$gaCaption			=	 stripslashes(urldecode(htmlentities($gaCaption)));
	}
?>



<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<p align="center" class="admin18Bold"><?php print $ownerID; ?> - Edit Image Text</p>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr bgcolor="#EEEEEE"> 
          <td width="30%" height="40">&nbsp;&nbsp;&lt;<a href="<?php print $backLink; ?>" class="galleryUILink"> 
            Back</a> </td>
          <td align="center">
<a href="gallery_help.php#ModifyLibraryData" target="_blank" class="galleryUILink">Help</a> 
          </td>
          <td width="30%" height="40" align="right"><a href="<?php print $homeLink; ?>" class="galleryUILink">Home</a>&nbsp;&nbsp;</td>
        </tr>
      </table></td>
  </tr>
<tr> 
    <td align="center" valign="top"> 
      <form action="gallery_services.php" method="get" name="ModifyLibraryData" id="ModifyLibraryData">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td height="10" align="center" valign="top">&nbsp;</td>
          </tr>
          <tr> 
            <td align="center" valign="top"> <div align="left"> 
                <!-- Note, This tag must be above the file input fields -->
                <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
              </div>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="center" valign="top"><img src="<?php print $fileNameAndPath; ?>" border="0"></td>
                  <td width="10%" align="center" valign="top"> <table class="admin14Bold" width="60%" border="1" cellspacing="0" cellpadding="5">
                      <tr> 
                        <td width="34%" class="admin12Bold"><span class="admin18BoldRed">* 
                          </span>Title</td>
                        <td width="66%" align="left"> <input name="gaTitle" type="text" id="gaTitle" value="<?php print $gaTitle; ?>" size="30"></td>
                      </tr>
                      <tr> 
                        <td valign="top" class="admin12Bold">Caption</td>
                        <td align="left"> <textarea name="gaCaption" cols="`" rows="5" id="elm1"><?php print $gaCaption; ?></textarea>                        </td>
                      </tr>
                      
                      
                     <?php 
					if($gSimpleGalleries==0)
                    	{
						print '
                      <tr> 
                        <td class="admin12Bold">Location</td>
                        <td align="left"> <input name="gaLocation" type="text" id="gaLocation" value="<?php print $gaLocation; ?>" size="30"></td>
                      </tr>
                      <tr> 
                        <td class="admin12Bold"><span class="admin18BoldRed">* 
                          </span>Artist</td>
                        <td align="left"> <input name="gaArtist" type="text" id="gaArtist" value="<?php print $gaArtist; ?>" size="40"></td>
                      </tr>
                      <tr> 
                        <td class="admin12Bold"><span class="admin18BoldRed">* 
                          </span>Media</td>
                        <td align="left"> ';
						
                          
						  // media types, this may be a pain but will allow sorting later
						  $labels	=	array();
						  $labels[]	=	"Please Pick One...";
						  $labels[]	=	"Painting";
						  $labels[]	=	"Watercolor";
						  $labels[]	=	"Pen and Ink";
						  $labels[]	=	"Mixed Media";
						  $labels[]	=	"Photograph";
						  $labels[]	=	"Monoprint";
						  $labels[]	=	"Lithograph";
						  $labels[]	=	"Glass";
						  $labels[]	=	"Jewlery";
						  $labels[]	=	"Ceramics";
		
							DisplaySelector("gaMedia",$labels,$labels,$gaMedia);
						 
                        print '
						</td>
                      </tr>';

                        }
                           ?>                    
                      
                      
                      
                      
                      
                      
                      
                    </table>
                    <p>
                      <input name="Submit" type="submit" id="Submit" value="Save Changes">
                    </p></td>
                </tr>
              </table>
              <p>
                <input name="gaMode" type="hidden" id="gaMode" value="ModifyImageDataNFS">
                
                <input name="gaTable" type="hidden" id="gaTable" value="<?php print $gaTable; ?>">
                <input name="gaRecordNumber" type="hidden" id="gaRecordNumber" value="<?php print $gaRecordNumber; ?>">
                <input name="gaFileName" type="hidden" id="gaFileName" value="<?php print $gaFileName; ?>">
                <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
                <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
                <input name="gaArtistID" type="hidden" id="gaArtistID" value="<?php print $systemUserID; ?>">
                <input name="gaAnchor" type="hidden" id="gaAnchor" value="<?php print $gaAnchor; ?>">
              </p>
              <p align="left" class="admin15BoldRed">* Required Fields</p></td>
          </tr>
        </table>
        </form></td>
  </tr>
  <tr>
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top">&nbsp;</td>
  </tr>
</table>


<p>&nbsp;</p>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);

?>