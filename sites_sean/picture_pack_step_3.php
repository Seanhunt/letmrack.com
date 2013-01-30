<?php
// Key			= A price code, currently Cajun or blank
// FileName		= The name of the file being ordered
// ProofPath	= A relative path to the proofs 
// ReturnURL	= The full URL of the image that was being browsed
// Package		= The PackageID
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

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

// get the galleryID
$artistID				=	str_replace("APPDEV_GALLERY_","",$gaGallery);
// get the path to the gallery from the gallery default mount point
$largeImagePath			=	"$gGalleryMountPoint/$artistID/500x/";
// strip the leading slash
$largeImagePath			=	substr($largeImagePath, 1);

$key					=	GetPassedValue("Key");
$package				=	GetPassedValue("Package");
$shippingDescription	=	GetPassedValue("ShippingDescription");


$pickUpMessage			=	"Pick up at studio&nbsp;&nbsp;"; 
$fileName				=	$gaFileName;


// this trap appears in both the select and ok pages
// if it is added to both instances must be updated.
if(strcmp($key,"Cajun")==0)
	{
	$priceLevel	=	"WholesalePrice";
	$pickUpMessage	=	"Pick up at dance&nbsp;&nbsp;"; 
	}
else
	{
	$priceLevel	=	"RetailPrice";
	$pickUpMessage	=	"Pick up at studio&nbsp;&nbsp;"; 
	}


$productPrice		=	GetFieldByComparison("CTG_PicturePackages",$priceLevel,"PackageID",$package);	
$packageDescription	=	GetFieldByComparison("CTG_PicturePackages","Description","PackageID",$package);	

$sku			=	$fileName;

$payPalDescription	=	str_replace("<br>"," and ",$packageDescription);

$productName	=	"Package $package - " . $payPalDescription;


if(strcmp($shippingDescription,"Ship")==0)
	{
	$productName		.=	" with shipping.";
	$handlingCart	=	"6.00";
	}
else
	{
	$productName	.=	" to be picked up.";
	$handlingCart	=	"0.00";
	}


$debug			=	0;

if($debug)  print "productName: $productName<br>";
if($debug)  print "handlingCart: $handlingCart<br>";
if($debug)  print "key: $key<br>";
if($debug)	print "gaGallery: $gaGallery<br>";
if($debug)	print "gaFileName: $gaFileName<br>";
if($debug)	print "gaPageNumber: $gaPageNumber<br>";
if($debug)	print "gaImageIndex: $gaImageIndex<br>";
if($debug)	print "shippingDescription: $shippingDescription<br>";
if($debug)	print "largeImagePath: $largeImagePath<br>";
if($debug) print "productName: $productName<br>";
if($debug) print "sku: $sku<br>";
if($debug) print "priceLevel: $priceLevel<br>";
if($debug) print "productPrice: $productPrice<br>";
if($debug) print "payPalDescription: $payPalDescription<br>";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Confirm Print Size Selection</title>

<SCRIPT LANGUAGE="JavaScript"> 
function CloseWindow()
	{
	window.close();
	}
</SCRIPT>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="images/css/local.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="images/picture_package/banner/picture_package_banner.gif" alt="Picture Package Seller Banner" width="955" height="90" border="0" usemap="#picture_package_banner_map">
      <map name="picture_package_banner_map">
        <area shape="rect" coords="748,45,945,84" href="<?php print $gShowCartString; ?>" alt="View Card Checkout Link">
        <area shape="rect" coords="5,3,356,67" href="index.php" alt="Cooksey-Talbott Studio">
      </map>      </td>
  </tr>
  <tr>
    <td align="center"><img src="images/picture_package/steps_graphic/picture_package_step_3.gif" alt="Select Shipping Method" width="900" height="100"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td> 
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
            <td width="50%" align="center" valign="top"> 
              <table width="5%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td><img src="<?php print $largeImagePath . $fileName; ?>"></td>
              </tr>
              <tr>
                  <td height="25" align="left" valign="bottom" class="rhs11Regular"><?php print $fileName; ?></td>
              </tr>
              <tr>
                <td height="10" align="left" valign="bottom" class="rhs14Regular">&nbsp;</td>
              </tr>
          </table>            </td>
          <td align="center" valign="top"><table width="100%" border="0">
              <tr>
                <td align="center">
                
                
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" enctype="application/x-www-form-urlencoded" name="step3" id="step3">
                
                
                  <table width="95%" border="0" cellspacing="0" cellpadding="0">

                    <tr>
                      <td height="30" align="left" valign="top" class="rhs12Regular"><table width="100%" border="0">
                        <tr>
                          <td width="6" align="center" valign="top" background="images/picture_package/banner/picture_pack_v_div.gif">&nbsp;</td>
                          <td width="144" align="center" valign="top"><table width="10" border="0">
                            <tr>
                              <td><img src="images/picture_package/<?php print $package; ?>.jpg"></td>
                            </tr>
                            <tr>
                              <td class="rhs12Bold"><?php print "Package $package"; ?></span>
                            <tr>
                              <td class="rhs11Regular"><?php print "$productPrice"; ?></td>
                            </tr>
                            <tr>
                              <td class="rhs11Regular"><?php print $payPalDescription; ?></td>
                            </tr>
                          </table></td>
                          <td width="239" align="center" valign="top"><table width="100%" border="0">
                            <tr>
                              <td width="6" rowspan="8" align="center" background="images/picture_package/banner/picture_pack_v_div.gif" class="rhs12Bold">&nbsp;</td>
                              <td height="30" colspan="2" align="center" class="rhs12Bold">You Selected</td>
                              <td width="7" rowspan="8" align="center" background="images/picture_package/banner/picture_pack_v_div.gif" class="rhs12Bold">&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="20" colspan="2" align="center" class="rhs12Regular"><img src="images/picture_package/banner/picture_pack_h_div.gif" width="200" height="6"></td>
                              </tr>
                            <tr>
                              <td width="120" align="right" valign="top" class="rhs12Regular">Package:&nbsp;&nbsp;</td>
                              <td width="88" align="left" valign="top" class="rhs12Regular"><span class="rhs11Bold"><?php print "$package"; ?></span></td>
                            </tr>
                            <tr>
                              <td align="right" valign="top" class="rhs12Regular">Description:&nbsp;&nbsp;</td>
                              <td align="left" valign="top" class="rhs12Regular"><span class="rhs11Regular"><?php print $payPalDescription; ?></span></td>
                            </tr>
                            <tr>
                              <td align="right" valign="top" class="rhs12Regular">Quantity:&nbsp;&nbsp;</td>
                              <td align="left" valign="top" class="rhs12Regular"><span class="rhs11Bold"><?php print "$quantity"; ?></span></td>
                            </tr>
                            <tr>
                              <td align="right" valign="top" class="rhs12Regular">Shipping:&nbsp;&nbsp;</td>
                              <td align="left" valign="top" class="rhs12Regular"><?php print "$shippingDescription"; ?></td>
                            </tr>
                            <tr>
                              <td align="right" valign="top" class="rhs12Regular">Price:&nbsp;&nbsp;</td>
                              <td align="left" valign="top" class="rhs12Regular"><?php print "$$productPrice"; ?></td>
                            </tr>
                            <tr>
                              <td align="right" valign="top" class="rhs12Regular">Sub-Total:&nbsp;&nbsp;</td>
                              <td align="left" valign="top" class="rhs12Regular">
                              
							  
							  <?php 
							  $subTotal	=	$productPrice * $quantity;
							  $subTotal = sprintf("$%01.2f", $subTotal);

							  print "$subTotal"; ?></td>
                            </tr>
                            
                          </table></td>
                        </tr>
                      </table>
                        <p>&nbsp;</p>
                        </td>
                    </tr>
                    <tr>
                      <td height="50" align="center" valign="middle" class="rhs12Regular"><table width="50%" border="0">
                        <tr>
                          <td height="35" align="center" bordercolor="#0098ca" bgcolor="#ef4151"><input type="submit" name="Submit" value="Add to Cart"></td>
                        </tr>
                      </table>
                        </td>
                    </tr>
                  </table>

                    <input type="hidden" name="cmd" value="_cart">
                    <input type="hidden" name="business" value="<?php print $gPayPalAccountID; ?>">
                    <input type="hidden" name="item_name" value="<?php print $productName; ?>">
                    <input type="hidden" name="item_number" value="<?php print $sku; ?>">
                    <input type="hidden" name="amount" value="<?php print $productPrice; ?>">
                    <input type="hidden" name="no_note" value="0">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="hidden" name="add" value="1">
                    <input type="hidden" name="handling" value="<?php print $handling; ?>">
                    <input type="hidden" name="quantity" value="<?php print $quantity; ?>">
                    <input type="hidden" name="image_url" value="<?php print $gPayPalLogoURL; ?>">
                    
                    <input type="hidden" name="handling_cart" value="<?php print $handlingCart; ?>">
                    <input type="hidden" name="shipping" value="0.00">
                    <input type="hidden" name="shipping2" value="0.00">
                
                </form>
                
                </td>
              </tr>
              <tr>
                <td align="center" class="rhs11Regular">Use your browsers back button to change your order...</td>
              </tr>
              <tr>
                <td height="40" align="center"><a href="javascript:window.close();" class="boldLink14">Close Window</a></td>
              </tr>
            </table>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td align="center"><img src="images/picture_package/banner/picture_pack_h_div.gif" width="900" height="6"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>



</body>
</html>
