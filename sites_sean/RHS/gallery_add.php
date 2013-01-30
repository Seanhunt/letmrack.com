<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_add.php
//
// This will upload two images and insert the title, caption and fn in the DB
//
// Revision 2.2.0.RC1
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
include 'html_lib.php';	
include 'file_lib.php';
include 'gallery_lib.php';	
include 'pw_parms.php';	
include 'pw_lib.php';
include 'select_controls.php';	
include 'status_message.php';	
include "admin_lib.php";
include "appdev_revision.php";

$debug		=	0;

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


$backLink			=	"gallery_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable";


$galleryType		=	GetProfileGalleryType($ownerID);

$gaMode				=	"AddImageAndResize";

if($debug) print "first: $first<br>";
if($debug) print "last: $last<br>";
if($debug) print "gaArtist: $gaArtist<br>";
if($debug) print "galleryType: $galleryType<br>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $ownerID; ?> - Add Image</title>

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
				width:"600px",
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
<p align="center" class="admin18Bold"><?php print $ownerID; ?> - Add Image</p>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top">
<form name="AddAnImageForm" enctype="multipart/form-data" method="post" action="gallery_services.php">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr bgcolor="#EEEEEE"> 
                  <td width="30%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink">
				  Back</a> </td>
                  <td align="center"><a href="gallery_help.php#AddImages" target="_blank" class="galleryUILink">Help</a> 
                  </td>
                  <td width="30%" height="40" align="right"><a target="_blank" href="<?php print $previewLink; ?>" class="galleryUILink">&nbsp;</a></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td align="center"> 
              <!-- Note, This tag must be above the file input fields -->
              <input type="hidden" name="MAX_FILE_SIZE" value="3000000" /> <table width="95%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="27%" valign="top" class="admin12Regular"> <p>This 
                      dialog allows you to add images to your gallery.</p>
                    <p>Select a 500 pixel or larger JPG file from your computer using the browse button.</p>
                    <p> Enter the required text for the title and the optional caption.</p>
                    <p>Your name will be added automatically.</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                  </td>
                  <td width="73%" align="right" valign="top"> <table class="admin14Bold" width="97%" border="1" cellspacing="0" cellpadding="5">
                      <tr> 
                        <td width="26%" class="admin12Bold"><span class="admin18BoldRed">* 
                          </span>
						  
						  
						  Picture</td>
                        <td width="74%"> <input name="gaFileToSend500x" type="file" id="gaFileToSend500x" size="40" />                        </td>
                      </tr>
 
 
                      <tr> 
                        <td class="admin12Bold"><span class="admin18BoldRed">* 
                          </span>Title</td>
                        <td><input name="gaTitle" type="text" id="gaTitle" value="<?php print $gaTitle; ?>" size="50"></td>
                      </tr>
                      <tr> 
                        <td valign="top" class="admin12Bold">Caption</td>
                        <td> <textarea id="elm1" name="gaCaption" cols="50" rows="5"><?php print $gaCaption; ?></textarea>                        </td>
                      </tr>

					<?php
					// no location for simple galleries
					if($gSimpleGalleries==false)
						{
						print '
						  <tr> 
							<td class="admin12Bold">Location</td>
							<td><input name="gaLocation" type="text" id="gaLocation" value="<?php print $gaLocation; ?>" size="50"></td>
						  </tr>
							';
						}
						
					?>
	
                  </table></td>
                </tr>
              </table>
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
              <p> 
                <input name="Submit" type="submit" id="Submit" value="Add Image">
                <input name="gaMode" type="hidden" id="gaMode" value="AddImageAndResize">
                <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
                <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
                <input name="gaArtistID" type="hidden" id="gaArtistID" value="<?php print $systemUserID; ?>">
                <input name="gaTable" type="hidden" id="gaTable" value="<?php print $gaTable; ?>">
              </p>
              </td>
          </tr>
          <tr>
            <td align="left" class="admin15BoldRed">* Required Fields</td>
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