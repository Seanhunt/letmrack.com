<?php
// image.php
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
include 'RHS/ui_lib.php';
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
// get the thumbs page name

// 030107 BUG FIX LINE 1 o/2
$artistID		=	str_replace("APPDEV_GALLERY_","",$gaGallery);

// UPDATE 071507
$imagePageName		=	GetGalleryImagePage($artistID);
$thumbsPageName		=	GetGalleryThumbsPage($artistID);

// this stuff comes from the image lib table
$title			=	GetFieldByComparison("APPDEV_ImageLibrary","Title","FileName",$gaFileName);
$caption		=	GetFieldByComparison("APPDEV_ImageLibrary","Caption","FileName",$gaFileName);
$location		=	GetFieldByComparison("APPDEV_ImageLibrary","Location","FileName",$gaFileName);
$artist			=	GetFieldByComparison("APPDEV_ImageLibrary","Artist","FileName",$gaFileName);
$media			=	GetFieldByComparison("APPDEV_ImageLibrary","Media","FileName",$gaFileName);


if($debug)	print "gaFileName: $gaFileName<br>";
if($debug)	print "title: $title<br>";
if($debug)	print "caption: $caption<br>";
if($debug)	print "location: $location<br>";
if($debug)	print "artist: $artist<br>";
if($debug)	print "media: $media<br>";

// how many sizes are there ?
//$query	=	"SELECT * FROM APPDEV_ImageSizes WHERE FileName='$gaFileName'";
// 030107 BUG FIX LINE 2 o/2
$query	=	"SELECT * FROM APPDEV_ImageSizes WHERE FileName='$gaFileName' AND ArtistID='$artistID'";

$numberOfSizes	=	GetNumberOfRows($query);

// get the sizes RS
OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

// get the first
$size				= 	GetDatabaseResult($rs,0,"Size");	
$price				= 	GetDatabaseResult($rs,0,"Price");	
$descriptionOne		= 	GetDatabaseResult($rs,0,"DescriptionOne");	
$descriptionTwo		= 	GetDatabaseResult($rs,0,"DescriptionTwo");	

$itemType			=	"$descriptionTwo $media";

$itemDescriptions	=	array();
$itemSizes			=	array();
$itemPrices			=	array();

for($i=0;$i<$numberOfSizes;$i++)
	{
	$size				= 	GetDatabaseResult($rs,$i,"Size");	
	$price				= 	GetDatabaseResult($rs,$i,"Price");	
	$descriptionOne		= 	GetDatabaseResult($rs,$i,"DescriptionOne");	
	$descriptionTwo		= 	GetDatabaseResult($rs,$i,"DescriptionTwo");	

	if(strstr($size,"NA")==TRUE)
		$size	=	"--";

	$itemDescriptions[]	=	ucfirst("$descriptionOne") . "<br>$descriptionTwo";
	$itemSizes[]		=	"$size";
	$itemPrices[]		=	"$$price";
	}

// template page stuff
$pageCounterName	=	"GalleryImage";	// the name this page will appear as in the counters

if($media != "")
	$pageTitle			=	"Original $media of $title - $location by $artist";
else
	$pageTitle			=	"$title - $location by $artist";

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 
PrintPageTitle($pageTitle); 
PrintMetaTags($pageTitle,$pageTitle);		// show the meta tags for SEO
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
          <td width="400" height="400" align="center" valign="top"> <img src="<?php print "$gPathToGalleryImages/$artistID/500x/$gaFileName"; ?>"></td>
          <td width="515" align="right" valign="top"> <table width="90%" border="0" cellpadding="0" cellspacing="10">
              <tr> 
                <td align="center" valign="top" class="rhs12Regular"> <table width="100%" border="0" cellspacing="0" cellpadding="15">
                    <tr> 
                      <td height="20" align="center" valign="top" bgcolor="#EEEEEE" class="rhs18Bold"> 
                        <?php
						print "$title";
						?>
                      </td>
                    </tr>
                    <tr> 
                      <td height="10" align="center" valign="top" class="rhs14Bold"> 
                        <?php
						print "$location";
						?>
                      </td>
                    </tr>
                    <tr> 
                      <td align="left" bgcolor="#EEEEEE" class="rhs12Regular"> 
                        <?php
						print $caption;
						?>
                      </td>
                    </tr>
                    <tr> 
                      <td align="left" valign="top" class="rhs12Regular"> 
                        <?php
						$galleryType	=	GetProfileGalleryType(GetArtistIdFromGallery($gaGallery));

if($debug)	print "galleryType: $galleryType<br>";
if($debug)	print "gaArtistID: $gaArtistID<br>";

						if($galleryType=="FORSALE")
							{


							if(count($itemDescriptions) == 1)
								{
								$tout	=	"$itemType.<hr>";
								}
							else
								{
								$tout	=	"This $itemType is available in $numberOfSizes variations.<hr>";
								}
	
								print "<br>$tout<br>"; 

							print	'
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
									  <tr align="center" valign="middle" class="rhs12Regular"> 
										<td width="17%" bgcolor="#EEEEEE"> 
										  <p>Add to
										  <br>Cart</p></td>
										<td width="15%" height="20" bgcolor="#EEEEEE">Price</td>
										<td width="18%" bgcolor="#EEEEEE">Size</td>
										<td width="50%" bgcolor="#EEEEEE">Description</td>
									  </tr>
									  <tr align="center" valign="middle" class="rhs12Regular">
										<td>&nbsp;</td>
										<td height="5">&nbsp;</td>
										<td height="5">&nbsp;</td>
										<td height="5">&nbsp;</td>
									  </tr>
									';
							
							for($i=0;$i<$numberOfSizes;$i++)
								{
								$payPalDescription = "$itemSizes[$i] $itemDescriptions[$i] of $title";
								
								print '
								<!-- Start row of add to carts -->
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypal_form' . $i . '" target="paypal" id="paypal_form">
								  <input type="hidden" name="cmd" value="_cart">
								  <input type="hidden" name="business" value="' . $gPayPalAccountID . '">
								  <input type="hidden" name="item_name" value="' . $payPalDescription . '">
								  <input type="hidden" name="item_number" value="' . $gaFileName . '">
								  <input type="hidden" name="amount" value="' . $itemPrices[$i] . '">
								  <input type="hidden" name="no_note" value="1">
								  <input type="hidden" name="currency_code" value="USD">
								  <input type="hidden" name="add" value="1">
								  <tr class="rhs12Regular" height="25"> 
								  <td align="center" valign="top"> 
<a href="#" onClick="javascript:document.paypal_form' . $i . '.submit()" class="galleryAddCartLink" >
									Add 
									</a>
								  </td>
									<td align="center" valign="top" class="rhs12Regular">' . $itemPrices[$i] . '</td>
									<td align="center" valign="top" class="rhs12Regular">' . $itemSizes[$i] . '</td>
									<td align="left"   valign="top" class="rhs12Regular">' . $itemDescriptions[$i] . '.</td>
								  </tr>
								</form>
								<!-- End row of add to carts -->
										  ';
								print '<tr><td colspan="4"><div class="horizontalRule1">&nbsp;</div></td></tr>';
								
						
								} // end of for($i=0;$i<$numberOfSizes;$i++)
						
							print 	'
			                        </table> 
									';
							} // end of if($galleryType=="FORSALE")
						?>
                      </td>
                    </tr>
                  </table>
                  <?php
					print 	'
							  <!-- Start Ratings UI -->
							  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
								  <td><hr></td>
								</tr>
								<tr>
								  <td>
							';

					if($showRatingUI)
						print PublicVoteShowUI($electionName,$gaFileName);

					print	'
								  </td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								</tr>
							  </table>
							  <!-- End Ratings UI -->
							';
					?>
                </td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td colspan="2" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">


<?php
// this template has rows like this page with its table in them...
DisplayDetailImages($gaFileName, $artistID, $detailTemplateFileName);
?>



              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><hr></td>
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
    <td height="40" align="center" valign="middle" > 
      <h5>
        <!--Start Counter -->
        
        <?php
		if($showCounter) print "Page Views: "; 
		Counter($pageCounterName,$showCounter);
		?>
        <!--End Counter -->
      </h5></td>
  </tr>
  <tr>
    <td height="40" align="center" valign="middle" > 
      <!--Start Footer Content -->
      <?php
		PrintFooterUI();	// show the site specific footer or footer UI
		?>
      <!--End Footer Content -->
    </td>
  </tr>
  <tr> 
    <td height="40" align="left" valign="middle" > 
      <!--Start SEO Text -->
      <h5>
	  <?php
		PrintSEODescription(); // display the SEO descriptive text
		?>
      <!--End SEO Text -->
      </h5>
    </td>
  </tr>
</table>
</body>
</html>
<?php

DisplayStatusMessage($statusMessage);
?>