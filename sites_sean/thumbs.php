<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include 'local_info.php';
include 'RHS/db_lib.php';
include "RHS/cl_lib.php";
include "RHS/html_lib.php";
include 'RHS/file_lib.php';
include 'RHS/gallery_lib.php';
include 'RHS/seo_lib.php';
include 'RHS/gallery_parms.php';
include 'RHS/counter_lib.php';

$debug				=	0;

$galleryTableName	=	"$gaGallery";

// figure out who and get their name
$artistID		=	GetArtistIdFromGallery($galleryTableName);
$galleryName	=	GetGalleryName($artistID);

// get the thumb and image pages
// UPDATE 071507
$thumbsPageName		=	GetGalleryThumbsPage($artistID);
$imagePageName		=	GetGalleryImagePage($artistID);

$templateFileName=	"templates/gallery_t_thumbsUI.html";
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

<link href="images/css/local.css" rel="stylesheet" type="text/css">
</head>
<body>
<!--- Include for Tool Tips ******************************* --->
<script type="text/javascript" src="wz_tooltip.js"></script>

<?php
PrintGalleryUI($galleryTableName,$gaPageNumber,$templateFileName,$thumbsPageName);
?>

<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td>&nbsp;</td>
    <td height="25" align="left" valign="middle" class="rhs12Regular"> &nbsp;Page: 
      <?php
	PrintPageNumberUI($galleryTableName,$gaPageNumber,$thumbsPageName);
	?>
    </td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td height="25" align="left" valign="middle" ><h5>&nbsp;Click 
      on one of the small images to see a larger version...</h5></td>
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
                <td height="20" align="left" valign="middle"> <span class="rhs10Regular"> 
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
                <td height="20" align="left" valign="middle"> <span class="rhs10Regular"> 
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
                <td height="20" align="left" valign="middle"> <span class="rhs10Regular"> 
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
                <td height="20" align="left" valign="middle"> <span class="rhs10Regular"> 
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
                <td height="20" align="left" valign="middle"> <span class="rhs10Regular"> 
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
                <td height="20" align="left" valign="middle"> <span class="rhs10Regular"> 
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
                <td height="20" align="left" valign="middle"> <span class="rhs10Regular"> 
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
                <td height="20" align="left" valign="middle"> <span class="rhs10Regular"> 
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
    <td class="rhs11Regular">All images and captions are 
      &copy; <?php print date(Y); print " - $gSiteName"; ?>, All Rights Reserved</td>
  </tr>
  <tr>
    <td align="center" >
	
		<h5>
		  <?php
		if($showCounter) print "Page Views: "; 
		Counter($pageCounterName,$showCounter);
		?>
		  
        </h5></td>
  </tr>
  <tr>
    <td>
	<h5>
		<?php
		PrintSEODescription(); // display the SEO descriptive text
		?>
	</h5>
	
	</td>
  </tr>
 </table>
</body>
</html>

