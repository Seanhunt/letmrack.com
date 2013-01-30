<?php

// ----------------------------------------------------------------------------------------------------------
// picture_pack_select.php
//
// Key			= A price code, currently Cajun or blank
// FileName		= The name of the file being ordered
// ProofPath	= A relative path to the proofs 
// ReturnURL	= The full URL of the image that was being browsed
//
// Here is a test entry point...
// 
// picture_pack_select.php?Key=Cajun&FileName=106-BladesOfGrassC.jpg&ProofPath=images/galleries/cookseytalbott/&ReturnURL=index.php
//
// 11/2007 I am rewriting this to hang in an RHS gallery.
//
// Inputs:	galleryID
//			pageNumber
//
// gaFileName
// gaPageNumber
// gaGallery	=	APPDEV_GALLERY_sierra
// gaImageIndex
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ----------------------------------------------------------------------------------------------------------
include 'local_info.php';	
include 'RHS/db_lib.php';
include 'RHS/cl_lib.php';
include 'RHS/file_lib.php';
include 'RHS/counter_lib.php';
include 'RHS/html_lib.php';
include 'RHS/status_message.php';	
include 'RHS/seo_lib.php';	
include 'RHS/ui_lib.php';	

include 'RHS/gallery_lib.php';
include 'RHS/gallery_parms.php';



$key				=	$_GET["Key"];
$fileName			=	$_GET["FileName"];
$proofPath			=	$_GET["ProofPath"];
$returnURL			=	$_GET["ReturnURL"];

// this trap appears in both the select and ok pages
// if it is added to both instances must be updated.
if(strcmp($key,"Cajun")==0)
	$priceLevel		=	"WholesalePrice";
else
	$priceLevel		=	"RetailPrice";


//$passArgs			=	"Key=$key&FileName=$fileName&ProofPath=$proofPath&ReturnURL=$returnURL";
// gaFileName
// gaPageNumber
// gaGallery	=	APPDEV_GALLERY_sierra
// gaImageIndex

$passArgs			=	"Key=$key&gaGallery=$gaGallery&gaPageNumber=$gaPageNumber&gaImageIndex=$gaImageIndex&gaFileName=$gaFileName";


$debug				=	0;

if($debug)  print "key: $key<br>";
if($debug)	print "gaGallery: $gaGallery<br>";
if($debug)	print "gaFileName: $gaFileName<br>";
if($debug)	print "gaPageNumber: $gaPageNumber<br>";
if($debug)	print "gaImageIndex: $gaImageIndex<br>";
if($debug) print "passArgs: $passArgs<br>";
if($debug) print "priceLevel: $priceLevel<br>";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Select Print Size or Picture Package</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="images/css/local.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--

.borderit img{
border: 4px solid #fff;
}

.borderit:hover img{
border: 4px solid #ef4151;
}

.borderit:hover{
color: #ef4151; /* irrelevant definition to overcome IE bug */
}

-->
</style>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top"><img src="images/picture_package/banner/picture_package_banner.gif" alt="Picture Package Seller Banner" width="955" height="90" border="0" usemap="#picture_package_banner_map"></td>
  </tr>
  <tr>
    <td align="center" valign="top"><img src="images/picture_package/steps_graphic/picture_package_step_1.gif" alt="Step One - Select a print size or package" width="900" height="100"></td>
  </tr>
  <tr>
    <td height="30" align="left" valign="middle" class="rhs14Bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Click on the desired print package to continue...</td>
  </tr>
  <tr>
    <td align="left" valign="top"><table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td width="10%" align="center" valign="top"> 
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">A</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">
				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"A";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
                
                <img src="images/picture_package/100x/A.jpg" alt="" width="80" height="100" border="0" class="borderit">                </a></td>
              </tr>
              <tr> 
                <td align="left" valign="top"><?php 
				  Price("A",$priceLevel); 
				  Description("A");
				  ?>                </td>
              </tr>
            </table>           </td>
          <td width="10%" align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">B</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">
				
								
				<?php 
				// set up the URL to go to the next page...
				$package	=	"B";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				

				
				<img src="images/picture_package/100x/B.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td width="10%" align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">C</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">
				
								
				<?php 
				// set up the URL to go to the next page...
				$package	=	"C";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				

				
				 <img src="images/picture_package/100x/C.jpg" width="78" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td width="10%" align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">D</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"D";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/D.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td width="10%" align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">E</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"E";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/E.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td width="10%" align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">F</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"F";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/F.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td width="10%" align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">G</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"G";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/G.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td width="10%" align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">H</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"H";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/H.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td width="10%" align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">I</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"I";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/I.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td width="10%" align="center" valign="top"> 
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">J</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"J";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/J.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
        </tr>
        <tr> 
          <td height="20">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">K</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"K";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/K.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">L</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"L";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/L.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">M</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"M";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/M.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">N</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"N";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/N.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">O</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"O";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/O.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">P</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"P";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/P.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">Q</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"Q";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/Q.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td align="center" valign="top"> 
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">R</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"R";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/R.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">S</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"S";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/S.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
          <td align="center" valign="top">
            <table width="2%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="20" align="center" class="rhs14Bold">T</td>
              </tr>
              <tr> 
                <td align="left" class="rhs11Regular">				
				<?php 
				// set up the URL to go to the next page...
				$package	=	"T";
				
				$linkURL	=	"picture_pack_step_2.php?Package=$package&" . $passArgs;
				print '<a class="borderit"  href="'; 
				print $linkURL;
				print '">';
				?>
				
<img src="images/picture_package/100x/T.jpg" width="80" height="100" border="0"></a></td>
              </tr>
              <tr> 
                <td height="50" align="left" valign="top">
				  <?php 
				  Price($package,$priceLevel); 
				  Description($package);
				  ?>                </td>
              </tr>
            </table>          </td>
        </tr>
    </table>    </td>
  </tr>
  <tr>
    <td height="20" align="center"><img src="images/picture_package/banner/picture_pack_h_div.gif" width="900" height="6"></td>
  </tr>
</table>

<map name="picture_package_banner_map"><area shape="rect" coords="748,45,945,84" href="<?php print $gShowCartString; ?>" alt="View Card Checkout Link">
<area shape="rect" coords="5,3,356,67" href="index.php" alt="Cooksey-Talbott Studio">
</map></body>
</html>
<?php
function Price($packageID,$priceLevel)
{
$rv	=	'<span class="rhs11Bold">' . "$" . GetFieldByComparison("CTG_PicturePackages",$priceLevel,"PackageID",$packageID) . "</span>";

print "$rv<br>";
}

function Description($packageID)
{
$rv	=	'<span class="rhs11Regular">' . GetFieldByComparison("CTG_PicturePackages","Description","PackageID",$packageID) . "</span>";


print $rv;
}



?>