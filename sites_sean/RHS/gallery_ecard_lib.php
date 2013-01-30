
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_ecard_lib.php
//
// This has the ecard sharde functionality
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

// ---------------------------------------------------------------------------------------------------------------------------------------
// ECard banner functions
// ---------------------------------------------------------------------------------------------------------------------------------------

function PrintECardComposeUI($backURL,$templateFileName="ecard_compose_banner.html")
{
$ui	=	FileRead($templateFileName);

$ui	=	str_replace("_BACKURL_",$backURL,$ui);

print $ui;
}

function PrintECardPreviewUI($editURL,$templateFileName="ecard_preview_banner.html")
{
$ui	=	FileRead($templateFileName);

$ui	=	str_replace("_EDITURL_",$editURL,$ui);

print $ui;
}

function GetECardMessage($fileName,$artistID,$title,$caption,$artist,$message,$templateFileName)
{
global	$gBaseMountPoint;
global 	$gGalleryMountPoint;
global	$gSiteURL;
global	$gSiteName;
global	$gSiteURL;

// get the thumbs page name
// UPDATE 071507
$thumbsPageName		=	GetGalleryThumbsPage($artistID);

$debug	=	0;


$galleryPath		=	"$gBaseMountPoint$gGalleryMountPoint/$artistID/200x/";
$fullURL			=	"$gSiteURL$galleryPath";
$fileNameAndPath	=	"$fullURL$fileName";
$galleryLink		=	"$gSiteURL$gBaseMountPoint/$thumbsPageName?gaGallery=APPDEV_GALLERY_$artistID&gaPageNumber=1";
$siteLink			=	"$gSiteURL$gBaseMountPoint/";

// make the message into HTML
$message 	= 	implode("<br>\r\n", preg_split("/\r?\n/", $message));


if($debug) print "GetECardMessage($fileName,$artistID,$title,$caption,$artist,$message,$templateFileName)<br>";
if($debug) print "fileNameAndPath: $fileNameAndPath<br>";
if($debug) print "templateFileName: $templateFileName<br>";
// read in the file
$templateFile	=	FileRead($templateFileName);

if($debug) if($templateFile=="")print "ERROR: TEMPLATE FILE NOT FOUND...<br>";

$templateFile	=	str_replace("_ECARD_FILENAME_AND_PATH_",$fileNameAndPath,$templateFile);
$templateFile	=	str_replace("_ECARD_PICTURE_TITLE_",$title,$templateFile);
$templateFile	=	str_replace("_ECARD_PICTURE_CAPTION_",$caption,$templateFile);
$templateFile	=	str_replace("_ECARD_ARTIST_NAME_",$artist,$templateFile);
$templateFile	=	str_replace("_ECARD_MESSAGE_",$message,$templateFile);

$templateFile	=	str_replace("_SITE_NAME_",$gSiteName,$templateFile);
$templateFile	=	str_replace("_SITE_LINK_",$siteLink,$templateFile);
$templateFile	=	str_replace("_GALLERY_LINK_",$galleryLink,$templateFile);


return $templateFile;
}

?>
