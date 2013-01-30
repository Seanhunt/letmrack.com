<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_ecard_preview.php
//
// This will allow the luser to preview an email for a card
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
include 'RHS/seo_lib.php';
include 'RHS/file_lib.php';
include 'RHS/captcha_lib.php';
include 'RHS/html_lib.php';
include 'RHS/gallery_lib.php';
include 'RHS/gallery_parms.php';
include 'RHS/gallery_ecard_lib.php';
include 'RHS/status_message.php';

// set up the URLs for edit and send, note the send URL uses the munged message string where \n's have been made into <br>'s

$eCardArgs		=	"gaCaller=$gaCaller&gaTo=$gaTo&gaFrom=$gaFrom&gaSenderName=$gaSenderName&gaSubject=$gaSubject&gaMessage=" . urlencode($gaMessage) . "&gaImageIndex=$gaImageIndex&gaPageNumber=$gaPageNumber&gaGallery=$gaGallery&gaFileName=$gaFileName&gaCaptchaText=$gaCaptchaText&gaCaptchaFileName=$gaCaptchaFileName";	

$editURL		=	"gallery_ecard_compose.php?$eCardArgs";

$debug			=	0;

$captchaFileName	=	GetCaptchaFileName();	

$templateFileName	=	"$gECardTemplate";

// this stuff comes from the images table
$title			=	GetFieldByComparison("APPDEV_ImageLibrary","Title","FileName",$gaFileName);
$caption		=	GetFieldByComparison("APPDEV_ImageLibrary","Caption","FileName",$gaFileName);
$artistName		=	GetArtistName($gaFileName,$gaGallery);
$pageTitle		=	"Free E-Card by $artistName";
$artistID		=	GetArtistIdFromGallery($gaGallery);
$galleryPath	=	"$gBaseMountPoint$gGalleryMountPoint/$artistID/200x/";
$fullURL		=	"$gSiteURL/$galleryPath";

if($debug)	print "gaFileName: $gaFileName<br>";
if($debug)	print "captchaFileName: $captchaFileName<br>";
if($debug)	print "title: $title<br>";
if($debug)	print "caption: $caption<br>";
if($debug)	print "artistName: $artistName<br>";
if($debug)	print "pageTitle: $pageTitle<br>";
if($debug)	print "artistID: $artistID<br>";
if($debug)	print "galleryPath: $galleryPath<br>";
if($debug)	print "fullURL: $fullURL<br>";
if($debug)	print "editURL: $editURL<br>";
?>
<html>
<head>
<?php PrintPageTitle($pageTitle); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
PrintMetaTags($pageTitle,$pageTitle);		// show the meta tags for SEO
?>
<link href="images/css/local.css" rel="stylesheet" type="text/css">
</head>
<form name="SendECardForm" method="get" action="RHS/gallery_services.php">
<body>
<!--- Include for Tool Tips ******************************* --->
<script type="text/javascript" src="wz_tooltip.js"></script>

<?php
PrintECardPreviewUI($editURL,$gEcardPreviewUITemplate);
?>
  
  <table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="20">&nbsp;</td>
      <td> <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="43%" height="30" align="left" valign="top"> <table width="90%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td height="40" align="left" valign="top" ><h2>Preview 
                    Your Message</h2></td>
                </tr>
                <tr> 
                  <td height="75" align="left" valign="top" ><p>You 
                      will see a preview of your message as it will appear in 
                      the recipients mail below. </p>
                    <p>These cards do not need to be &quot;picked up&quot; by 
                      the recipient they are sent directly to the recipient.</p>
                    <p>We do not retain the email addresses from our cards.</p>
                    <p>To send your card please enter this secret word to the 
                      right in the space below and hit the send button...</p>
                    </td>
                </tr>
              </table></td>
            <td width="57%" align="center" valign="top"> 
              <table width="100%" border="1" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td align="center"><img src="images/captcha/<?php print $captchaFileName; ?>" width="300" height="150"></td>
                      </tr>
                      <tr> 
                        <td height="10" align="center">&nbsp; </td>
                      </tr>
                      <tr> 
                        <td height="40" align="center" bgcolor="#CCCCCC" >Enter 
                          Word&nbsp;&nbsp; <input name="gaCaptchaText" type="text" id="gaCaptchaText2" size="40"> 
                          <input name="gaCaptchaFileName" type="hidden" id="gaCaptchaFileName4" value="<?php print $captchaFileName; ?>"></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              
            </td>
          </tr>
        </table></td>
      <td width="20">&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td height="40" align="center" valign="bottom"> 
        <hr></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td align="center" valign="top"> <table width="100%" border="0" cellpadding="0" cellspacing="0" >
          <tr> 
            <td width="8%" height="30" align="right" ><strong>To&nbsp;&nbsp;</strong>&nbsp;</td>
            <td width="92%" align="left" >&nbsp;&nbsp;&nbsp;<?php print $gaTo; ?></td>
          </tr>
          <tr> 
            <td height="30" align="right" ><strong>From&nbsp;&nbsp;&nbsp;</strong></td>
            <td align="left" >&nbsp;&nbsp;&nbsp;<?php print "$gaSenderName&nbsp;&nbsp;&lt;$gaFrom&gt;"; ?></td>
          </tr>
          <tr> 
            <td height="30" align="right" ><strong>Subject&nbsp;&nbsp;&nbsp;</strong></td>
            <td align="left" >&nbsp;&nbsp;&nbsp;<?php print $gaSubject; ?></td>
          </tr>
        </table></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td height="40" align="center" valign="top"><hr></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td  height="40" align="center" valign="middle" bgcolor="#999999">Message 
        Preview </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td align="center" valign="top"> <table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#999999">
          <tr> 
            <td align="center" valign="top"> 
              <?php
			print GetECardMessage($gaFileName,$artistID,$title,$caption,$artistName,$gaMessage,$templateFileName);
			?>
            </td>
          </tr>
        </table> </td>
      <td>&nbsp;</td>
    </tr>
  </table>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><hr></td>
  </tr>
</table>    
	
	
	
  <input name="gaCaller" type="hidden" id="gaCaller" value="<?php print $gaCaller; ?>">
  <input name="gaFileName" type="hidden" id="gaFileName" value="<?php print $gaFileName; ?>">
    <input name="gaImageIndex" type="hidden" id="gaImageIndex" value="<?php print $gaImageIndex; ?>">
    <input name="gaGallery" type="hidden" id="gaGallery" value="<?php print $gaGallery; ?>">
    <input name="gaTo" type="hidden" id="gaTo" value="<?php print $gaTo; ?>">
    <input name="gaFrom" type="hidden" id="gaFrom" value="<?php print $gaFrom; ?>">
    <input name="gaSenderName" type="hidden" id="gaSenderName" value="<?php print $gaSenderName; ?>">
    <input name="gaSubject" type="hidden" id="gaSubject" value="<?php print $gaSubject; ?>">
    <input name="gaMessage" type="hidden" id="gaMessage" value="<?php print urlencode($gaMessage); ?>">
  	<input name="gaMode" type="hidden" id="gaMode" value="SendECard">
  <input name="gaPageNumber" type="hidden" id="gaPageNumber" value="<?php print $gaPageNumber; ?>">
</form>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>