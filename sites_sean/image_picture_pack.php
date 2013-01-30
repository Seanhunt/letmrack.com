<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include 'local_info.php';
include 'RHS/seo_lib.php';
include 'RHS/db_lib.php';
include "RHS/cl_lib.php";
include "RHS/html_lib.php";
include 'RHS/file_lib.php';
include 'RHS/gallery_lib.php';
include 'RHS/gallery_parms.php';
include 'RHS/status_message.php';
include 'RHS/image_lib.php';
include 'RHS/counter_lib.php';
include 'RHS/public_vote_lib.php';

$debug			=	0;
$imagesPerPage	=	8;
$showCounter	=	false;				// bool show or hide the counter
$showRatingUI	=	true;
$electionName	=	"General";

// these get changed if these pages are taken over...
$detailTemplateFileName	=	"templates/detail_image_template.html";
$templateFileName		=	"templates/gallery_t_imageUI.html";

$artistID		=	str_replace("APPDEV_GALLERY_","",$gaGallery);

// UPDATE 071507
// get the thumbs page name
$imagePageName		=	GetGalleryImagePage($artistID);
$thumbsPageName		=	GetGalleryThumbsPage($artistID);

// set up the title
$pageTitle		=	$gaFileName;

// set up the discount key
$key			=	"Cajun";

$buyLinkURL		=	"picture_pack_step_1.php?Key=$key&gaGallery=$gaGallery&gaPageNumber=$gaPageNumber&gaImageIndex=$gaImageIndex&gaFileName=$gaFileName";

if($debug)	print "key: $key<br>";
if($debug)	print "gaGallery: $gaGallery<br>";
if($debug)	print "gaFileName: $gaFileName<br>";
if($debug)	print "gaPageNumber: $gaPageNumber<br>";
if($debug)	print "gaImageIndex: $gaImageIndex<br>";
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 
PrintPageTitle($pageTitle); 
//PrintMetaTags($pageTitle,$pageTitle);		// show the meta tags for SEO
PrintDevelopmentTags();						// go away private page
?>
<!-- Load the Ajax Lib -->
<script src="RHS/ajax_lib.js"></script>
<!-- Load the public voting Lib -->
<script src="RHS/public_vote_lib.js"></script>

<link href="images/css/local.css" rel="stylesheet" type="text/css">
<link href="RHS/public_vote.css" rel="stylesheet" type="text/css">
</head>
<body>
<!--- Include for Tool Tips ******************************* --->
<script type="text/javascript" src="wz_tooltip.js"></script>
<?php
PrintImageUI($gaFileName,$gaPageNumber,$gaImageIndex,$gaGallery,$imagesPerPage,$templateFileName,$thumbsPageName,$imagePageName);
?>

<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="20">&nbsp;</td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="622" height="400" align="center" valign="top"> <img src="<?php print "$gPathToGalleryImages/$artistID/500x/$gaFileName"; ?>"></td>
          <td width="293" align="right" valign="top"><table width="100%" border="0">
            <tr>
              <td class="rhs14Bold"><p>You can get a print of this image in a wide variety of sizes. </p>
                <p>We also offer packages that include several different sizes of prints.</p>
                <p>All our prints are made in-house so we achieve a level of quality far above your typical BigBox store print job.</p>
                <p>Mild retouching is included with all prints.</p>
                <p>Share some with your friends and family!</p></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="center">
              
              
              <a href="<?php print "$buyLinkURL"; ?>" target="paypal" class="boldLink18">Buy A Print of This Image</a>              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr> 
          <td colspan="2" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td align="center"><a name="Details"></a> 
                  <?php
	DisplayDetailImages($gaFileName, $artistID, $detailTemplateFileName);
	?>
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td class="rhs10Regular">All images and captions are &copy; 
                  <?php print date(Y); print " - $gSiteName"; ?>, All Rights Reserved</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="20">&nbsp;</td>
  </tr>
</table>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td class="rhs10Regular">&nbsp;</td>
  </tr>
  <tr> 
    <td> 
      <?php
		PrintSEODescription(); // display the SEO descriptive text
		?>
    </td>
  </tr>
  <tr> 
    <td align="center" class="rhs11Bold"> 
      <h5>
        <?php
		if($showCounter) print "Page Views: "; 
		Counter($pageCounterName,$showCounter);
		?>
      </h5></td>
  </tr>
</table>
</body>
</html>
<?php

DisplayStatusMessage($statusMessage);
?>