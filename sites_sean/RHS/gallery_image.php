<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include '../local_info.php';
include 'seo_lib.php';
include 'db_lib.php';
include "cl_lib.php";
include "html_lib.php";
include 'file_lib.php';
include 'gallery_lib.php';
include 'gallery_parms.php';
include 'status_message.php';
include 'image_lib.php';
include 'counter_lib.php';

$debug			=	0;
$imagesPerPage	=	8;
$showCounter	=	false;				// bool show or hide the counter

// these get changed if these pages are taken over...
$templateFileName	=	"gallery_t_imageUI.html";
$thumbsPageName		=	"gallery_thumbs.php";
$imagePageName		=	"gallery_image.php";

// this stuff comes from the image lib table
$title			=	GetFieldByComparison("APPDEV_ImageLibrary","Title","FileName",$gaFileName);
$caption		=	GetFieldByComparison("APPDEV_ImageLibrary","Caption","FileName",$gaFileName);
$location		=	GetFieldByComparison("APPDEV_ImageLibrary","Location","FileName",$gaFileName);
$artist			=	GetFieldByComparison("APPDEV_ImageLibrary","Artist","FileName",$gaFileName);
$artistID		=	GetFieldByComparison("APPDEV_ImageLibrary","ArtistID","FileName",$gaFileName);
$media			=	GetFieldByComparison("APPDEV_ImageLibrary","Media","FileName",$gaFileName);


if($debug)	print "gaFileName: $gaFileName<br>";
if($debug)	print "title: $title<br>";
if($debug)	print "caption: $caption<br>";
if($debug)	print "location: $location<br>";
if($debug)	print "artist: $artist<br>";
if($debug)	print "media: $media<br>";

// how many sizes are there ?
$query	=	"SELECT * FROM APPDEV_ImageSizes WHERE FileName='$gaFileName'";

$numberOfSizes	=	GetNumberOfRows($query);

// get the sizes RS
OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

// get the first
$size				= 	GetDatabaseResult($rs,0,"Size");	
$price				= 	GetDatabaseResult($rs,0,"Price");	
$description		= 	GetDatabaseResult($rs,0,"Description");	
$editionType		= 	GetDatabaseResult($rs,0,"EditionType");	

$itemType			=	"$editionType $media";

$itemDescriptions	=	array();
$itemSizes			=	array();
$itemPrices			=	array();

for($i=0;$i<$numberOfSizes;$i++)
	{
	$size				= 	GetDatabaseResult($rs,$i,"Size");	
	$price				= 	GetDatabaseResult($rs,$i,"Price");	
	$description		= 	GetDatabaseResult($rs,$i,"Description");	
	$editionType		= 	GetDatabaseResult($rs,$i,"EditionType");	

	if(strstr($size,"NA")==TRUE)
		$size	=	"--";

	$itemDescriptions[]	=	ucfirst("$description");
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

<link href="gallery_styles.css" rel="stylesheet" type="text/css">
<link href="app_dev_II.css" rel="stylesheet" type="text/css">
</head>

<?php
//DisplayBodyTag();		// show the body tag
PrintImageUI($gaFileName,$gaPageNumber,$gaImageIndex,$gaGallery,$imagesPerPage,$templateFileName,$thumbsPageName,$imagePageName);
?>

<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="20">&nbsp;</td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="400" height="400" align="center" valign="top"> 
		<img src="<?php print "$gPathToGalleryImages/$artistID/500x/$gaFileName"; ?>"></td>
          <td width="515" align="right" valign="top"> 
            <table width="90%" border="0" cellpadding="0" cellspacing="10">
              <tr>
                <td class="body12"><table width="100%" border="0" cellspacing="0" cellpadding="15">
                    <tr> 
                      <td height="20" align="center" valign="top" bgcolor="#EEEEEE" class="galleryImageTitle"> 
                        <?php
						print "$title";
						?>
                      </td>
                    </tr>
                    <tr> 
                      <td height="10" align="center" valign="top" class="galleryImageLocation"> 
                        <?php
						print "$location";
						?>
                      </td>
                    </tr>
                    <tr> 
                      <td align="left" bgcolor="#EEEEEE" class="galleryImageCaption"> 
                        <?php
						print $caption;
						?>
                      </td>
                    </tr>
                     <tr> 
                      <td align="left" valign="top" class="galleryItemType"> 
						
						
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
									  <tr align="center" valign="middle" class="galleryImagePriceHeader"> 
										<td width="17%" bgcolor="#EEEEEE"> 
										  <p>Add to
										  <br>Cart</p></td>
										<td width="15%" height="20" bgcolor="#EEEEEE">Price</td>
										<td width="18%" bgcolor="#EEEEEE">Size</td>
										<td width="50%" bgcolor="#EEEEEE">Description</td>
									  </tr>
									  <tr align="center" valign="middle" class="galleryImagePriceHeader">
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
								  <input type="hidden" name="business" value="cooksey@cookseytalbottgallery.com">
								  <input type="hidden" name="item_name" value="' . $payPalDescription . '">
								  <input type="hidden" name="item_number" value="' . $gaFileName . '">
								  <input type="hidden" name="amount" value="' . $itemPrices[$i] . '">
								  <input type="hidden" name="no_note" value="1">
								  <input type="hidden" name="currency_code" value="USD">
								  <input type="hidden" name="add" value="1">
								  <tr class="galleryImagePrice" height="25"> 
								  <td align="center" valign="top"> 
									<a href="#" onClick="javascript:document.paypal_form' . $i . '.submit()" class="galleryAddCartLink" >
									Add 
									</a>
								  </td>
									<td align="center" valign="top">' . $itemPrices[$i] . '</td>
									<td align="center" valign="top">' . $itemSizes[$i] . '</td>
									<td align="left"   valign="top">' . $itemDescriptions[$i] . '.</td>
								  </tr>
								</form>
								<!-- End row of add to carts -->
										  ';
								} // end of for($i=0;$i<$numberOfSizes;$i++)
						
							print 	'
			                        </table> 
									';
							} // end of if($galleryType=="FORSALE")
											




						?>




                      </td>
                    </tr>
                  </table></td>
              </tr>
            </table>
          </td>
        </tr>
      </table></td>
    <td width="20">&nbsp;</td>
  </tr>
</table>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center" valign="middle"> <hr></td>
  </tr>
  <tr> 
    <td class="body10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="galleryCopyrightNotice">All 
      images and captions are &copy; <?php print date(Y); print " - $gSiteName"; ?>, 
      All Rights Reserved</span></td>
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
</body>
</html>
<?php

DisplayStatusMessage($statusMessage);
?>