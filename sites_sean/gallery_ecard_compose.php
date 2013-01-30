<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_ecard_compose.php
//
// This will allow the luser to compose an email for a card
//
// Rev: 	071805	File Originated
//			011007	Incorporated into the appdev code
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

include 'local_info.php';
include 'RHS/db_lib.php';
include 'RHS/cl_lib.php';
include 'RHS/file_lib.php';
include 'RHS/html_lib.php';
include 'RHS/gallery_ecard_lib.php';
include 'RHS/gallery_lib.php';
include 'RHS/gallery_parms.php';
include 'RHS/seo_lib.php';
include 'RHS/status_message.php';

$debug			=	0;


// this stuff comes from the images table
$title			=	GetFieldByComparison("APPDEV_ImageLibrary","Title","FileName",$gaFileName);
$caption		=	GetFieldByComparison("APPDEV_ImageLibrary","Caption","FileName",$gaFileName);
$artistName		=	GetArtistName($gaFileName,$gaGallery);
$pageTitle		=	"Free E-Card by $artistName";
$artistID		=	GetArtistIdFromGallery($gaGallery);
$galleryPath	=	"$gBaseMountPoint$gGalleryMountPoint/$artistID/200x/";

// 071507
$imagePageName	=	GetGalleryImagePage($artistID);
$backURL		=	"$imagePageName?gaCaller=$gaCaller&gaFileName=$gaFileName&gaPageNumber=$gaPageNumber&gaGallery=$gaGallery&gaImageIndex=$gaImageIndex";


if($debug)	print "title: $title<br>";
if($debug)	print "caption: $caption<br>";
if($debug)	print "artistName: $artistName<br>";
if($debug)	print "pageTitle: $pageTitle<br>";

$pageCounterName	=	"ECardCompose";	// the name this page will appear as in the counters
$pageTitle			=	"Compose Free E-Card";	// the title of the page
$showCounter		=	true;				// bool show or hide the counter
?>

<html>
<head>
<?php
PrintPageTitle($pageTitle); // this will be surrounded by the site and site keywords
$description			=	""; // description for this page else standard
$additionalKeywords		=	""; // these will appear first in the list

PrintMetaTags($description,$additionalKeywords);
?>
<link href="images/css/local.css" rel="stylesheet" type="text/css">
</head>
<body>
<!--- Include for Tool Tips ******************************* --->
<script type="text/javascript" src="wz_tooltip.js"></script>
<form name="form1" method="GET" action="gallery_ecard_preview.php">
<?php
PrintECardComposeUI($backURL,$gEcardComposeUITemplate);
?>
  
  
  <table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="20">&nbsp;</td>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="65%" align="left" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="left" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr valign="top"> 
                        <td height="40" colspan="2" ><h1>Send a 
                          Free E-Card to A Friend</h1></td>
                      </tr>
                      <tr> 
                        <td width="32%"><table width="20%" border="0" align="left" cellpadding="0" cellspacing="0">
                            <tr> 
                              <td height="30" ><img src="<?php print "$galleryPath$gaFileName"; ?>"></td>
                            </tr>
                            <tr> 
                              <td height="25" ><?php print $title; ?></td>
                            </tr>
                          </table></td>
                        <td width="68%" align="left" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="10">
                            <tr> 
                              <td valign="top" > <p>Send 
                                  this image to one of your friends as a free 
                                  eCard!</p>
                                <p>Everyone likes to get a card with some interesting 
                                  artwork.</p>
                                <p>The card arrives in their email as you see 
                                  it in the preview. it is not necessary for the 
                                  recipient to go somewhere and &quot;pick up&quot; 
                                  their card.</p></td>
                            </tr>
                            <tr> 
                              <td valign="top" > <p>We do 
                                  not retain the address info or send spam messages 
                                  to card recipients or senders. We are serious 
                                  about your privacy.</p></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td align="left" valign="top"> <table width="100%" border="1" cellpadding="0" cellspacing="0" >
                            <tr valign="middle"> 
                              <td width="22%" height="40" align="right"><strong>To </strong>&nbsp;&nbsp;</td>
                              <td width="78%" align="center"> <input name="gaTo" type="text" id="To2" value="<?php print $gaTo; ?>" size="42"> 
                              </td>
                            </tr>
                            <tr valign="middle"> 
                              <td height="40" align="right"><strong>From&nbsp;&nbsp;</strong></td>
                              <td align="center"> <input name="gaFrom" type="text" id="From2" value="<?php print $gaFrom; ?>" size="42"> 
                              </td>
                            </tr>
                            <tr valign="middle"> 
                              <td height="40" align="right"><strong>Your Name&nbsp;</strong>&nbsp;</td>
                              <td align="center"> <input name="gaSenderName" type="text" id="Subject" value="<?php print $gaSenderName; ?>" size="42"> 
                              </td>
                            </tr>
                            <tr valign="middle"> 
                              <td height="40" align="right"><strong>Subject&nbsp;&nbsp;</strong></td>
                              <td align="center"> <input name="gaSubject" type="text" id="Subject2" value="<?php print $gaSubject; ?>" size="42"> 
                              </td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
            <td width="35%" align="right" valign="top"> <table width="100%" border="1" cellspacing="0" cellpadding="10">
                <tr> 
                  <td bgcolor="#CCCCCC"> <table width="20%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td height="40" class="rhs18BoldWhite">Your Message...</td>
                      </tr>
                      <tr> 
                        <td><textarea name="gaMessage" cols="45" rows="27" id="gaMessage"><?php print $gaMessage; ?></textarea></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td colspan="2" align="left" valign="top"><hr></td>
          </tr>
        </table></td>
      <td width="20">&nbsp;</td>
    </tr>
  </table>
  <table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td><h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span >All images 
        and captions are &copy; <?php print date(Y); print " - $gSiteName"; ?>, 
        All Rights Reserved</span></h5></td>
    </tr>
  </table>

    <input name="gaFileName" type="hidden" id="gaFileName" value="<?php print $gaFileName; ?>">
    <input name="gaImageIndex" type="hidden" id="gaImageIndex" value="<?php print $gaImageIndex; ?>">
    <input name="gaGallery" type="hidden" id="gaGallery" value="<?php print $gaGallery; ?>">
  	<input name="gaPageNumber" type="hidden" id="gaPageNumber" value="<?php print $gaPageNumber; ?>">
  	<input name="gaCaller" type="hidden" id="gaCaller" value="<?php print $gaCaller; ?>">
</form>

</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>