<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_profile.php
//
// This allows the artist to edit their profile
//
// 2.2.0 022608 changed up to be granular permissions`
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
include "cl_lib.php";
include "html_lib.php";
include 'file_lib.php';
include 'gallery_lib.php';	
include 'gallery_parms.php';	
include 'question_lib.php';	
include 'select_controls.php';	
include 'pw_parms.php';	
include 'pw_lib.php';
include "admin_lib.php";
include 'status_message.php';	
include "appdev_revision.php";

$debug	=	0;

// ---------------------------------------------------------------------------------------------------------------------------------------
// this is the new to 2.2.0 auth section for granular galleries
// ---------------------------------------------------------------------------------------------------------------------------------------

if(AuthorizeGalleryUser($systemUserID,$systemPassword,$gaTable)==false)
	{
	print "Access denied.<br><br>";
	exit(0);
	}

if(AuthorizeAdminUser($systemUserID,$systemPassword)==true)
	{
	$userIsAdmin	=	true;
	if($debug) print "User is: ADMINISTRATOR or SUPER<br>";
	}
else
	if($debug) print "User is: GRANULAR<br>";

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


if($debug) print "gSimpleGalleries: $gSimpleGalleries<br>";

if($debug) print "gaTable: $gaTable<br>";

$gaArtistID	=	str_replace("APPDEV_GALLERY_","",$gaTable); // get admin id from table

LoadArtistProfile($gaArtistID);

// if no profile set to no profile photo
if($gaFileName=="")
	$gaFileName	=	"No300xPhoto.jpg";
	
$fileNameAndPath	=	"$gBaseMountPoint$gGalleryMountPoint/$gaArtistID/300x/$gaFileName";

$backLink				=	"gallery_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword&gaTable=$gaTable";

if($debug) print "fileNameAndPath: $fileNameAndPath<br>";
if($debug) print "gaArtistID: $gaArtistID<br>";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $gaArtistID; ?> - Edit Profile</title>

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
				width:"450px",
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
<p align="center" class="admin18Bold"><?php print $gaArtistID; ?> - Edit Profile</p>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr bgcolor="#EEEEEE"> 
          <td width="30%" height="40">
		  &nbsp;&nbsp;&lt;&nbsp;<a href="<?php print $backLink; ?>" class="galleryUILink">Back</a> </td>
          <td align="center"><a href="gallery_help.php#EditProfile" target="_blank" class="galleryUILink">Help</a> 
          </td>
          <td width="30%" height="40" align="right"></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top"> <p>&nbsp;</p>
      <form action="gallery_services.php" method="post" enctype="multipart/form-data" name="ArtistProfileForm" id="ArtistProfileForm">
        <!-- Note, This tag must be above the file input fields -->
        <p> 
          <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
        </p>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="38%" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="center"> <table width="247" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td width="184" align="center" valign="top"> 
                        <img src="<?php print $fileNameAndPath; ?>"> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="50" align="center" valign="middle" class="admin10Regular"> 
                          <p>Gallery Picture</p></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td class="admin12Regular">&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td><p align="left"><span  class="admin18BoldRed">&nbsp;*</span><span  class="admin15BoldRed"> 
                      Required Fields</span></p>
                    
                    <?php
                    if($gSimpleGalleries==false)
                    	{
                        print 	'
								<table width="200" height="30" border="1" align="left" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
								  <tr> 
									<td bgcolor="#a9d571" class="admin15Bold">Public Information</td>
								  </tr>
								  <tr> 
									<td bgcolor="#fda0a8" class="admin15Bold">Private Information&nbsp;</td>
								  </tr>
								</table>
                        		';
                        }
                     ?>
                    </td>
                </tr>
              </table></td>
            <td width="62%" align="right" valign="top"> <div align="left"> </div>
              <table border="1" cellpadding="5" cellspacing="0" class="admin14Bold">
                <tr> 
                  <td width="21%" align="right" class="admin12Bold">Picture 
                    &gt; 300x </td>
                  <td width="79%" align="left"> <input name="gaFileToSend300x" type="file" id="gaFileToSend300x4" size="42" /> 
                  </td>
                </tr>
                <tr> 
                  <td align="right" class="admin12Bold"><span class="admin18BoldRed">* 
                    </span>Gallery Title</td>
                  <td align="left"> <input name="gaGalleryTitle" type="text" id="gaGalleryTitle4" value="<?php print $gaGalleryTitle; ?>" size="51"></td>
                </tr>
                <tr> 
                  <td align="right" valign="top" class="admin12Bold">Statement</td>
                  <td align="left" valign="top"> 
                    <textarea name="gaStatement" cols="40" rows="6" id="elm1"><?php print $gaStatement; ?></textarea> 
                  </td>
                </tr>



<?php
				if($gSimpleGalleries==false)
					{
					print	'
					<tr> 
					  <td align="right" bgcolor="#a9d571" class="admin12Bold">Website 
						URL</td>
					  <td align="right">http://
					<input name="gaWebsite" type="text" id="gaWebsite4" value="' . $gaWebsite . '" size="41"></td>
					</tr>';
					
					
					print '
					<tr> 
					  <td align="right" bgcolor="#a9d571" class="admin12Bold"><span class="admin18BoldRed">* 
						</span>Artist Name</td>
					  <td align="right"> <input name="gaArtist" type="text" id="gaArtist" value="' . $gaArtist . '" size="51"></td>
					</tr>
					<tr> 
					  <td align="right" bgcolor="#a9d571" class="admin12Bold">Studio 
						Name</td>
					  <td align="right"> <input name="gaStudio" type="text" id="gaStudio" value="' . $gaStudio . '" size="51"></td>
					</tr>';
					
					
					print '
					<tr> 
					  <td align="right" bgcolor="#fda0a8" class="admin12Bold"><span class="admin18BoldRed">* 
						</span>E-Mail Address</td>
					  <td align="right"> <input name="gaEMail" type="text" id="gaEMail" value="' . $gaEMail . '" size="51"> 
					  </td>
					</tr>
					<tr> 
					  <td align="right" bgcolor="#fda0a8" class="admin12Bold">Address</td>
					  <td align="right"> <input name="gaAddress" type="text" id="gaAddress" value="' . $gaAddress . '" size="51"></td>
					</tr>
					<tr> 
					  <td align="right" bgcolor="#fda0a8" class="admin12Bold">City</td>
					  <td align="right"> <input name="gaCity" type="text" id="gaCity" value="' . $gaCity . '" size="51"></td>
					</tr>';
					
					
					print '
					<tr> 
					  <td align="right" bgcolor="#fda0a8" class="admin12Bold">State</td>
					  <td align="left">&nbsp;&nbsp;'; 
	
					  
					  
					  DisplayStateSelector($gaState,"gaState");
					  
					  
	
					print '
					  </td>
					</tr>';
					
					print '
					<tr> 
					  <td align="right" bgcolor="#fda0a8" class="admin12Bold">Zip</td>
					  <td align="right"> <input name="gaZip" type="text" id="gaZip" value="' . $gaZip . '" size="51"></td>
					</tr>
					<tr> 
					  <td align="right" bgcolor="#fda0a8" class="admin12Bold"><span class="admin18BoldRed">* 
						</span>Phone</td>
					  <td align="right"> <input name="gaPhone" type="text" id="gaPhone" value="' . $gaPhone . '" size="51"></td>
					</tr>
					';
					}
					
?>

              
              </table></td>
          </tr>
        </table>
        <div align="left"></div>
        <p> 
          <input name="Submit" type="submit" id="Submit" value="Update Profile">
          <input name="gaMode" type="hidden" id="gaMode" value="UpdateProfile">
          <input name="SystemPassword" type="hidden" id="SystemPassword2" value="<?php print $systemPassword; ?>">
          <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
          <input name="gaArtistID" type="hidden" id="gaArtistID" value="<?php print $systemUserID; ?>">
          <input name="gaTable" type="hidden" id="gaTable" value="<?php print $gaTable; ?>">
        </p>
      </form></td>
  </tr>
  <tr>
    <td height="40" align="left" valign="middle" bgcolor="#eeeeee"> 
      <?php
PrintUserMessage($systemUserID);
?>
    </td>
  </tr>
  <tr> 
    <td align="left" valign="top"> 
      <?php
		  // print the CT chop and footer
		  PrintGalleryFooter();
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