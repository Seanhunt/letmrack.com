<?php
// --------------------------------------------------------------------------------------------------------------
// local_info.php - RHS STOCK
//
// Contains local information about the web site app and its databases
//
// 1.0.x	020506 Changed the global symbols to avoid clashing with other modules
// 2.0.0	012607 Made RC1 
// 2.0.1	012706 Deployed and fixed the bugs I found in the deployment
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// --------------------------------------------------------------------------------------------------------------

$debug					=	0;

$gHomePage				=	"index.php";					// name of the index page
$gContactPage			=	"contact.php";					// name of the mail contact page
$gServerBaseMountPoint	=	"/home/rollingh/public_html/sites_sean"; 	// the local path to the root of the site no slash on end
$gBaseMountPoint		=	"/sites_sean";					// the root of the site if not / with no slash on end
$gGalleryMountPoint		=	"/images/galleries";			// root of the galleries tree with no slash on end	
$gFTPDeliveryMountPoint	=	"/deliveries/uploads";			// ftp delivery dir with no slash on end
$gBlogTempMountPoint	=	"/temp";						// temp dir with no slash on end
$gBlogSharedMountPoint	=	"/shared";						// shared dir with no slash on end
$gMailSharedMountPoint	=	"/mailings";					// mailings dir with no slash on end
$gBlogAdsMountPoint		=	"/images/ads";					// ads dir with no slash on end

// these have $gBaseMountPoint removed for 2.2.0.2
$gMailLocalPath			=	$gServerBaseMountPoint .  $gMailSharedMountPoint; // the local path for file operations	
$gGalleryLocalPath		=	$gServerBaseMountPoint .  $gGalleryMountPoint; // the local path for file operations	
$gBlogAdsLocalPath		=	$gServerBaseMountPoint .  $gBlogAdsMountPoint; // the local path for file operations	
$gBlogTempLocalPath		=	$gServerBaseMountPoint .  $gBlogTempMountPoint; // the local path for file operations	
$gBlogSharedLocalPath	=	$gServerBaseMountPoint .  $gBlogSharedMountPoint; // the local path for file operations	

$gSiteURL				=	"http://www.letmrack.com"; // root of the site with no slash
$gSiteAdminAddress		=	"sean@rollinghillssoftware.com";
$gSiteSecretaryAddress	=	"sean@rollinghillssoftware.com"; // contact e-mails are directed to this address
$gSiteName				=	"My Stupid stinking Little Dog";
$gDatabaseUserName 		= 	"letmrack_user";
$gDatabasePassword 		= 	"e2ThsbpGc%00";
$gDatabaseName 			= 	"letmrack_sean";
$gHost					=	"localhost";

$gTestingEnabled		=	0;						// true sets tables decoration to gTestDatabaseID else gLiveDatabaseID also prints dev robot tags
$gLiveDatabaseID		=	"APPDEV_";				// the decoration of the sites live data tables
$gTestDatabaseID		=	"TEST_";				// the decoration of the sites test data tables


$gMailerFromAddress		=	"sean@rollinghillssoftware.com";		
$gMailerBounceAddress	=	"bounce@letmrack.com";
$gMailerFromName		=	"Sean Hunt";	
$gMailerSalutation		=	"Dear";
$gSiteOwner				=	"Sean Hunt";
$gMailTableName			=	"MailDatabase";
$gTestMailTableName		=	"TestMailingList";
$gMailDatabaseName		=	"APPDEV_$gMailTableName";	
$gTestMailDatabaseName	=	"APPDEV_$gTestMailTableName";	
// seo 
// see seo_text.doc for specifications for the seo text

$gKeyWords				=	"Put Keywords Here"; // keywords for the site
$gDescription			=	"Global Description of the site";
$gTopic					=	"The topic of the site";
$gAltTagKeywords		=	"Put alt tag keywords and phrases here delimited with a , no limit"; // a longer list of keywords for the alt tag sprayer

// -------------------------------------------------------------------------------------------------------------
// template file names
// -------------------------------------------------------------------------------------------------------------

$gBannerUIFileName	=	"templates/page_banner_template.html";
$gFooterFileName	=	"templates/page_footer_template.html";
$gSEOFooterFileName	=	"templates/seo_footer_template.html";
							
// -------------------------------------------------------------------------------------------------------------
// used by gallery_image and gallery_thumbs
// -------------------------------------------------------------------------------------------------------------

$gPathToGalleryImages	=	"$gBaseMountPoint$gGalleryMountPoint";	// must have a slash on the end

// used by gallery_add to choose between 1 upload file > 500px 
// or 2 individual files that will not be rescaled
// in the simple option both files are resampled to hit 500 and 200

$gSimpleFileUploads		=	1;

// used in ecard template loader so far...
// these are being deprecated...
$gThumbsPageName		=	"thumbs.php";
$gImagePageName			=	"image.php";

// -------------------------------------------------------------------------------------------------------------
// Paypal info
// -------------------------------------------------------------------------------------------------------------

$gPayPalAccountID		=	"cooksey@cookseytalbottgallery.com";
$gShowCartString		=	"https://www.paypal.com/cgi-bin/webscr?cmd=_cart&business=$gPayPalAccountID&display=1";
// new 11/1/07
$gPayPalLogoURL			=	"https://protected.fatcow.com/cookse/images/paypal/paypal_logo.gif";

if($debug) print "<br><hr><br>local_info.php<br>";
if($debug) print "gGalleryLocalPath: $gGalleryLocalPath<br>";
if($debug) print "<hr><br><br>";

// -------------------------------------------------------------------------------------------------------------
// New additions
// -------------------------------------------------------------------------------------------------------------

$gEcardComposeUITemplate	=	"templates/ecard_compose_banner.html";
$gEcardPreviewUITemplate	=	"templates/ecard_preview_banner.html";
$gECardTemplate				=	"templates/ecard_template.html";

// -------------------------------------------------------------------------------------------------------------
// 2.1.8
// -------------------------------------------------------------------------------------------------------------

// enables adding detail pics to a gallery in the gallery editor
// They will appear automatically in the image page template
$gGalleryDetailPictures		=	false;	
$gHideFromRobots			=	true;	// enables no robots tags in page headers

// -------------------------------------------------------------------------------------------------------------
// 2.2.0
// -------------------------------------------------------------------------------------------------------------

$gSimpleGalleries			=	1;	// hides stuff in the gallery UI
$gSimpleBlogs				=	1;	// hides stuff from users in the blog UI
$gRequireCaptcha			=	1; 	// requires captcha on contact page else captcha ignored

// used in pw_lib.php to gen up friendly passwords
// this allows them to be customized to the site
$gFriendlyPasswords	=	array(	'daisy','tulip','rose','california','fremont','sourdough','boots','snow',
					'summer','trumpet','macaroni','spicy','dancing','taxes','basil',
					'oregano','wagon','icecream','months','violin','april','alaska','music',
					'river','marin','balloon','pacific','sierra','yosemite','sonora',
					'beach','sand','coconut','tiger','direction','airplane',
					'raincoat','chocolate','goldengate','surfing','skier','alameda','circus',
					'friends','visit','helpful');

// -------------------------------------------------------------------------------------------------------------
// 2.2.1
// -------------------------------------------------------------------------------------------------------------

$gUseTinyMCE				=	1;	// use the tiny MCE editor for blog postings

// props, must end with a comma
$gTinyMCEProperties			=	'
	theme : "advanced",
	mode: "exact",
	elements : "elm1",
	theme_advanced_toolbar_location : "top",
	theme_advanced_buttons1 : "bold,italic,underline,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,help",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_align : "left",	
	';
	
// -------------------------------------------------------------------------------------------------------------
// 2.2.0.4
// -------------------------------------------------------------------------------------------------------------
	
// for continued blog posts
define("kContinuedTag","_CONTINUED_");

// blog continuation page name
$gStoryPageFileName	=	"blog_article.php";

?>