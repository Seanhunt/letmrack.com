<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_edit.php
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
include "admin_lib.php";
include "appdev_revision.php";

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
$homeLink			=	"gallery_home.php?gaTable=$gaTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
$galleryType		=	GetProfileGalleryType($ownerID);

if($debug) print "galleryType: $galleryType<br>";
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
                  <td width="10%" align="right"> <table class="admin14Bold" width="30%" border="1" cellspacing="0" cellpadding="5">
                      <tr> 
                        <td width="34%" class="admin12Bold"><span class="admin18BoldRed">* 
                          </span>Title</td>
                        <td width="66%" align="left"> <input name="gaTitle" type="text" id="gaTitle" value="<?php print $gaTitle; ?>" size="35"></td>
                      </tr>
                      <tr> 
                        <td valign="top" class="admin12Bold">Caption</td>
                        <td align="left"> <textarea name="gaCaption" cols="20" rows="5" id="elm1"><?php print $gaCaption; ?></textarea>                        </td>
                      </tr>
                      <tr> 
                        <td class="admin12Bold">Location</td>
                        <td align="left"> <input name="gaLocation" type="text" id="gaLocation" value="<?php print $gaLocation; ?>" size="35"></td>
                      </tr>
                  </table></td>
                </tr>
              </table>
              <p class="admin15Bold">
                <?php
if($galleryType=="FORSALE")
	{
	// gaHeightOne, gaPriceOne, gaDescriptionOne, gaEditionTypeOne
	print	'
			  <!-- Start FORSALE Data -->
              <p class="admin15Bold">What purchase options are available ? You must list 
                at least one price and description per item.</p>
              <table width="95%" border="1" cellspacing="0" cellpadding="5">
                <tr align="center" class="admin12Bold"> 
                  <td width="5%" class="admin12Bold">#</td>
                  <td width="10%">Size</td>
                  <td width="15%">Price</td>
                  <td>Description One</td>
                  <td>Description Two</td>
                </tr>
                <tr align="center"> 
                  <td class="admin12Bold">1</td>
                  <td>             
<input name="gaHeightOne" type="text" id="gaHeightOne" value="' .$gaHeightOne . '" size="10" />                  </td>
                  <td><span class="admin18BoldRed">*</span>$ 
					  <input name="gaPriceOne" type="text" id="gaPriceOne" value="' .$gaPriceOne . '" size="9" maxlength="8"></td>
						  <td><span class="admin18BoldRed">*</span> 
						  <input name="gaDescriptionOne" type="text" id="gaDescriptionOne" value="' .$gaDescriptionOne . '" size="30"></td>
						  <td>
                          
<input name="gaEditionTypeOne" type="text" id="gaEditionTypeOne" value="' .$gaEditionTypeOne . '" size="30"></td>
							</tr>
								';
					
					
					print	'
							<tr align="center"> 
							  <td class="admin12Bold">2</td>
							  <td><input name="gaHeightTwo" type="text" id="gaHeightTwo" value="' .$gaHeightTwo . '" size="10" /></td>
							  <td><span class="admin18BoldRed">&nbsp;</span>$ 
							<input name="gaPriceTwo" type="text" id="PriceOne8" value="' . $gaPriceTwo . '" size="9" maxlength="8"></td>
						  <td>&nbsp;&nbsp; 
						  
						  <input name="gaDescriptionTwo" type="text" id="gaDescriptionTwo" value="' . $gaDescriptionTwo . '" size="30"></td>
						  
						  
						  <td> 
							
<input name="gaEditionTypeTwo" type="text" id="gaEditionTypeTwo" value="' .$gaEditionTypeTwo . '" size="30">                                   </td>
                </tr>
                <tr align="center"> 
                  <td class="admin12Bold">3</td>
                  <td><input name="gaHeightThree" type="text" id="gaHeightThree" value="' .$gaHeightThree . '" size="10" /></td>
                  <td><span class="admin18BoldRed">&nbsp;</span>$ 
                    <input name="gaPriceThree" type="text" id="PriceOne9" value="' . $gaPriceThree . '" size="9" maxlength="8"></td>
                  <td>&nbsp;&nbsp; <input name="gaDescriptionThree" type="text" id="gaDescriptionThree" value="' . $gaDescriptionThree . '" size="30"></td>
                  <td> 
				  

<input name="gaEditionTypeThree" type="text" id="gaEditionTypeThree" value="' .$gaEditionTypeThree . '" size="30">                                    </td>
                </tr>
                <tr align="center"> 
                  <td class="admin12Bold">4</td>
                  <td><input name="gaHeightFour" type="text" id="gaHeightFour" value="' .$gaHeightFour . '" size="10" /></td>
                  <td><span class="admin18BoldRed">&nbsp;</span>$ 
                    <input name="gaPriceFour" type="text" id="gaPriceFour" value="' .$gaPriceFour . '" size="9" maxlength="8"></td>
                  <td>&nbsp;&nbsp; <input name="gaDescriptionFour" type="text" id="gaDescriptionFour" value="' . $gaDescriptionFour . '" size="30"></td>
                  <td>
<input name="gaEditionTypeFour" type="text" id="gaEditionTypeFour" value="' .$gaEditionTypeFour . '" size="30" /></td>
				</tr>
						  </table>
						  <!-- End FORSALE Data -->
							';
	} // end of if($galleryType=="FORSALE")

	

?>
              </p>
              
              
              
              
              
              
              
              
              <p> 
                <input name="Submit" type="submit" id="Submit" value="Save Changes">
                <input name="gaMode" type="hidden" id="gaMode" value="ModifyImageData">
                <input name="gaTable" type="hidden" id="gaTable" value="<?php print $gaTable; ?>">
                <input name="gaRecordNumber" type="hidden" id="gaTable" value="<?php print $gaRecordNumber; ?>">
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
            <td> 
              <?php
			// print the CT chop and footer
			PrintGalleryFooter();
			?>
            </td>
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