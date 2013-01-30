<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include '../local_info.php';
include 'db_lib.php';
include "cl_lib.php";
include "html_lib.php";
include 'file_lib.php';
include 'gallery_lib.php';
include 'seo_lib.php';
include 'gallery_parms.php';
include 'counter_lib.php';

$debug				=	0;

$galleryTableName	=	"$gaGallery";

// figure out who and get their name
$artistID		=	GetArtistIdFromGallery($galleryTableName);
$galleryName	=	GetGalleryName($artistID);

// change this for custom thumb and image pages
$imagePageName	=	"gallery_image.php";
$thumbsPageName	=	"gallery_thumbs.php";
$templateFileName=	"gallery_t_thumbsUI.html";
// calc start index based on page number
$maxItems		=	GetNumberOfRows("SELECT * FROM $galleryTableName");
$rowsPerPage	=	2;
$itemsPerRow	=	4;
$imageIndex		=	($rowsPerPage * $itemsPerRow) * ($gaPageNumber-1);

// template page stuff
$pageCounterName	=	"GalleryThumbs";	// the name this page will appear as in the counters
$pageTitle			=	"$galleryName";	// the title of the page
$showCounter		=	true;				// bool show or hide the counter

if($debug)	print "pageNumber: $gaPageNumber<br>";
if($debug)	print "galleryTableName: $galleryTableName<br>";
if($debug)	print "imageIndex: $imageIndex<br>";
if($debug)	print "maxItems: $maxItems<br>";
if($debug)	print "galleryName: $galleryName<br>";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
PrintPageTitle($pageTitle); // this will be surrounded by the site and site keywords
$description			=	""; // description for this page else standard
$additionalKeywords		=	""; // these will appear first in the list

PrintMetaTags($description,$additionalKeywords);
?>
<link href="gallery_styles.css" rel="stylesheet" type="text/css">
<link href="app_dev_II.css" rel="stylesheet" type="text/css">
</head>

<?php
//DisplayBodyTag();		// show the body tag
//Show the gallery UI
PrintGalleryUI($galleryTableName,$gaPageNumber,$templateFileName,$thumbsPageName);
?>

<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td height="25" align="left" valign="top" class="galleryPageTitle"> &nbsp;Page: 
      <?php
	PrintPageNumberUI($galleryTableName,$gaPageNumber);
	?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td width="20">&nbsp;</td>
    <td valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="25%" height="225" align="center" valign="top"> <table width="40" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td height="5"> </td>
              </tr>
              <tr> 
                <td align="center" valign="top"> 
                  <?php 
				PrintImageLinkOpen($galleryTableName,$imageIndex,$gaPageNumber,$imagePageName);
				PrintImageTag($galleryTableName,$imageIndex); 
				PrintImageLinkClose($galleryTableName,$imageIndex);
				?>
                </td>
              </tr>
              <tr> 
                <td height="20" align="left" valign="middle"> <span class="galleryThumbnailCaption"> 
                  <?php PrintImageTitle($galleryTableName,$imageIndex++); ?> </span> 
                </td>
              </tr>
            </table></td>
          <td width="25%" align="center" valign="top"><table width="40" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td height="5"> </td>
              </tr>
              <tr> 
                <td align="center" valign="top"> 
                  <?php 
				PrintImageLinkOpen($galleryTableName,$imageIndex,$gaPageNumber,$imagePageName);
				PrintImageTag($galleryTableName,$imageIndex); 
				PrintImageLinkClose($galleryTableName,$imageIndex);
				?>
                </td>
              </tr>
              <tr> 
                <td height="20" align="left" valign="middle"> <span class="galleryThumbnailCaption"> 
                  <?php PrintImageTitle($galleryTableName,$imageIndex++); ?> </span> 
                </td>
              </tr>
            </table></td>
          <td width="25%" align="center" valign="top"><table width="40" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td height="5"> </td>
              </tr>
              <tr> 
                <td align="center" valign="top"> 
                  <?php 
				PrintImageLinkOpen($galleryTableName,$imageIndex,$gaPageNumber,$imagePageName);
				PrintImageTag($galleryTableName,$imageIndex); 
				PrintImageLinkClose($galleryTableName,$imageIndex);
				?>
                </td>
              </tr>
              <tr> 
                <td height="20" align="left" valign="middle"> <span class="galleryThumbnailCaption"> 
                  <?php PrintImageTitle($galleryTableName,$imageIndex++); ?> </span> 
                </td>
              </tr>
            </table></td>
          <td width="25%" align="center" valign="top"><table width="40" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td height="5"> </td>
              </tr>
              <tr> 
                <td align="center" valign="top"> 
                  <?php 
				PrintImageLinkOpen($galleryTableName,$imageIndex,$gaPageNumber,$imagePageName);
				PrintImageTag($galleryTableName,$imageIndex); 
				PrintImageLinkClose($galleryTableName,$imageIndex);
				?>
                </td>
              </tr>
              <tr> 
                <td height="20" align="left" valign="middle"> <span class="galleryThumbnailCaption"> 
                  <?php PrintImageTitle($galleryTableName,$imageIndex++); ?> </span> 
                </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="20">&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="25%" height="225" align="center" valign="top"><table width="40" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td height="5"> </td>
              </tr>
              <tr> 
                <td align="center" valign="top"> 
                  <?php 
				PrintImageLinkOpen($galleryTableName,$imageIndex,$gaPageNumber,$imagePageName);
				PrintImageTag($galleryTableName,$imageIndex); 
				PrintImageLinkClose($galleryTableName,$imageIndex);
				?>
                </td>
              </tr>
              <tr> 
                <td height="20" align="left" valign="middle"> <span class="galleryThumbnailCaption"> 
                  <?php PrintImageTitle($galleryTableName,$imageIndex++); ?> </span> 
                </td>
              </tr>
            </table></td>
          <td width="25%" align="center" valign="top"><table width="40" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td height="5"> </td>
              </tr>
              <tr> 
                <td align="center" valign="top"> 
                  <?php 
				PrintImageLinkOpen($galleryTableName,$imageIndex,$gaPageNumber,$imagePageName);
				PrintImageTag($galleryTableName,$imageIndex); 
				PrintImageLinkClose($galleryTableName,$imageIndex);
				?>
                </td>
              </tr>
              <tr> 
                <td height="20" align="left" valign="middle"> <span class="galleryThumbnailCaption"> 
                  <?php PrintImageTitle($galleryTableName,$imageIndex++); ?> </span> 
                </td>
              </tr>
            </table></td>
          <td width="25%" align="center" valign="top"><table width="40" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td height="5"> </td>
              </tr>
              <tr> 
                <td align="center" valign="top"> 
                  <?php 
				PrintImageLinkOpen($galleryTableName,$imageIndex,$gaPageNumber,$imagePageName);
				PrintImageTag($galleryTableName,$imageIndex); 
				PrintImageLinkClose($galleryTableName,$imageIndex);
				?>
                </td>
              </tr>
              <tr> 
                <td height="20" align="left" valign="middle"> <span class="galleryThumbnailCaption"> 
                  <?php PrintImageTitle($galleryTableName,$imageIndex++); ?> </span> 
                </td>
              </tr>
            </table></td>
          <td width="25%" align="center" valign="top"><table width="40" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td height="5"> </td>
              </tr>
              <tr> 
                <td align="center" valign="top"> 
                  <?php 
				PrintImageLinkOpen($galleryTableName,$imageIndex,$gaPageNumber,$imagePageName);
				PrintImageTag($galleryTableName,$imageIndex); 
				PrintImageLinkClose($galleryTableName,$imageIndex);
				?>
                </td>
              </tr>
              <tr> 
                <td height="20" align="left" valign="middle"> <span class="galleryThumbnailCaption"> 
                  <?php PrintImageTitle($galleryTableName,$imageIndex++); ?> </span> 
                </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><div align="center"> 
        <hr>
      </div></td>
  </tr>
  <tr> 
    <td class="body10"><span class="galleryCopyrightNotice">All images and captions 
      are &copy; <?php print date(Y); print " - $gSiteName"; ?>, All Rights Reserved</span></td>
  </tr>
  <tr>
    <td>
	
		<?php
		PrintSEODescription(); // display the SEO descriptive text
		?>
	
	
	</td>
  </tr>
  <tr>
    <td align="center" class="cts11Bold">
	
		<?php
		if($showCounter) print "Page Views: "; 
		Counter($pageCounterName,$showCounter);
		?>
	
	</td>
  </tr>
</table>
<p class="galleryPageTitle">&nbsp;</p>
</body>
</html>

