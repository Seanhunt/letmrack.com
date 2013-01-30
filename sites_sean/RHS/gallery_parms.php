<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

$debug				=	0;

$systemUserID		=	GetPassedValue("SystemUserID");
$systemPassword		=	GetPassedValue("SystemPassword");
$gaAdminArtistID	=	GetPassedValue("gaAdminArtistID");
$gaGalleryTitle		=	GetPassedValue("gaGalleryTitle");
$gaStatement		=	GetPassedValue("gaStatement");
$gaFileToSend300x	=	GetPassedValue("gaFileToSend300x");
$gaWebsite			=	GetPassedValue("gaWebsite");
$gaArtist			=	GetPassedValue("gaArtist");
$gaStudio			=	GetPassedValue("gaStudio");
$gaEMail			=	GetPassedValue("gaEMail");
$gaAddress			=	GetPassedValue("gaAddress");
$gaCity				=	GetPassedValue("gaCity");
$gaState			=	GetPassedValue("gaState");
$gaZip				=	GetPassedValue("gaZip");
$gaPhone			=	GetPassedValue("gaPhone");
$gaTable			=	GetPassedValue("gaTable");

if($debug) print "<hr>profile<br><br>";
if($debug) print "gaAdminArtistID: $gaAdminArtistID<br>";
if($debug) print "gaGalleryTitle: $gaGalleryTitle<br>";
if($debug) print "gaFileToSend300x: $gaFileToSend300x<br>";
if($debug) print "gaWebsite: $gaWebsite<br>";
if($debug) print "gaArtist: $gaArtist<br>";
if($debug) print "gaStudio: $gaStudio<br>";
if($debug) print "gaEMail: $gaEMail<br>";
if($debug) print "gaAddress: $gaAddress<br>";
if($debug) print "gaCity: $gaCity<br>";
if($debug) print "gaState: $gaState<br>";
if($debug) print "gaZip: $gaZip<br>";
if($debug) print "gaPhone: $gaPhone<br>";
if($debug) print "gaStatement: $gaStatement<br>";
if($debug) print "<hr>";

// add files
$gaArtistID			=	GetPassedValue("gaArtistID");
$gaFileToSend500x	=	GetPassedValue("gaFileToSend500x");
$gaFileToSend200x	=	GetPassedValue("gaFileToSend200x");
$gaFileName			=	GetPassedValue("gaFileName");
$gaDetailFileName	=	GetPassedValue("gaDetailFileName");
$gaAnchor			=	GetPassedValue("gaAnchor");

// gallery page control parms
$gaPageNumber	=	GetPassedValue("gaPageNumber");
$gaPageName		=	GetPassedValue("gaPageName");
$gaGallery		=	GetPassedValue("gaGallery");
$gaImageIndex	=	GetPassedValue("gaImageIndex");

if($debug) print "<hr>gallery<br><br>";
if($debug) print "gaPageNumber: $gaPageNumber<br>";
if($debug) print "gaPageName: $gaPageName<br>";
if($debug) print "gaGallery: $gaGallery<br>";
if($debug) print "gaImageIndex: $gaImageIndex<br>";
if($debug) print "gaFileName: $gaFileName<br>";
if($debug) print "gaDetailFileName: $gaDetailFileName<br>";
if($debug) print "gaAnchor: $gaAnchor<br>";
if($debug) print "<hr>";

$gaTitle		=	GetPassedValue("gaTitle");
$gaCaption		=	GetPassedValue("gaCaption");
$gaLocation		=	GetPassedValue("gaLocation");
$gaArtist		=	GetPassedValue("gaArtist");
$gaMedia		=	GetPassedValue("gaMedia");
$gaStatus		=	GetPassedValue("gaStatus");
$gaPresentation	=	GetPassedValue("gaPresentation");
$gaMode			=	GetPassedValue("gaMode");
$gaRecordNumber	=	GetPassedValue("gaRecordNumber");

$gaWidthOne		=	GetPassedValue("gaWidthOne");
$gaWidthTwo		=	GetPassedValue("gaWidthTwo");
$gaWidthThree	=	GetPassedValue("gaWidthThree");
$gaWidthFour	=	GetPassedValue("gaWidthFour");

$gaHeightOne	=	GetPassedValue("gaHeightOne");
$gaHeightTwo	=	GetPassedValue("gaHeightTwo");
$gaHeightThree	=	GetPassedValue("gaHeightThree");
$gaHeightFour	=	GetPassedValue("gaHeightFour");

$gaPriceOne		=	GetPassedValue("gaPriceOne");
$gaPriceTwo		=	GetPassedValue("gaPriceTwo");
$gaPriceThree	=	GetPassedValue("gaPriceThree");
$gaPriceFour	=	GetPassedValue("gaPriceFour");

$gaEditionTypeOne	=	GetPassedValue("gaEditionTypeOne");
$gaEditionTypeTwo	=	GetPassedValue("gaEditionTypeTwo");
$gaEditionTypeThree	=	GetPassedValue("gaEditionTypeThree");
$gaEditionTypeFour	=	GetPassedValue("gaEditionTypeFour");

$gaDescriptionOne	=	GetPassedValue("gaDescriptionOne");	
$gaDescriptionTwo	=	GetPassedValue("gaDescriptionTwo");	
$gaDescriptionThree	=	GetPassedValue("gaDescriptionThree");	
$gaDescriptionFour	=	GetPassedValue("gaDescriptionFour");	

if($debug) print "<hr>FS gallery parms<br><br>";

if($debug) print "gaPriceOne: $gaPriceOne<br>";
if($debug) print "gaPriceTwo: $gaPriceTwo<br>";
if($debug) print "gaPriceThree: $gaPriceThree<br>";
if($debug) print "gaPriceFour: $gaPriceFour<br>";

if($debug) print "gaHeightOne: $gaHeightOne<br>";
if($debug) print "gaPriceTwo: $gaPriceTwo<br>";
if($debug) print "gaHeightThree: $gaHeightThree<br>";
if($debug) print "gaHeightFour: $gaHeightFour<br>";

if($debug) print "gaDescriptionOne: $gaDescriptionOne<br>";
if($debug) print "gaDescriptionTwo: $gaDescriptionTwo<br>";
if($debug) print "gaDescriptionThree: $gaDescriptionThree<br>";
if($debug) print "gaDescriptionFour: $gaDescriptionFour<br>";

if($debug) print "gaEditionTypeOne: $gaEditionTypeOne<br>";
if($debug) print "gaEditionTypeTwo: $gaEditionTypeTwo<br>";
if($debug) print "gaEditionTypeThree: $gaEditionTypeThree<br>";
if($debug) print "gaEditionTypeFour: $gaEditionTypeFour<br>";


if($debug) print "<hr>";



// these are for the ecard subsystem
$gaTo				=	GetPassedValue("gaTo");		
$gaFrom				=	GetPassedValue("gaFrom");		
$gaSenderName		=	GetPassedValue("gaSenderName");		
$gaSubject			=	GetPassedValue("gaSubject");		
$gaMessage			=	GetPassedValue("gaMessage");
$gaCaptchaFileName	=	GetPassedValue("gaCaptchaFileName");
$gaCaptchaText		=	GetPassedValue("gaCaptchaText");
$gaCaller			=	GetPassedValue("gaCaller");		

if($debug) print "<hr>gallery_ecard_parms<br><br>";
if($debug) print "gaTo: $gaTo<br>";
if($debug) print "gaFrom: $gaFrom<br>";
if($debug) print "gaSenderName: $gaSenderName<br>";
if($debug) print "gaSubject: $gaSubject<br>";
if($debug) print "gaMessage: $gaMessage<br>";
if($debug) print "gaCaptchaFileName: $gaCaptchaFileName<br>";
if($debug) print "gaCaptchaText: $gaCaptchaText<br>";
if($debug) print "<hr>";

// for ecard subsystem
$gaECardArgs		=	"gaCaller=$gaCaller&gaTo=$gaTo&gaFrom=$gaFrom&gaSenderName=$gaSenderName&gaSubject=$gaSubject&gaMessage=$gaMessage&gaImageIndex=$gaImageIndex&gaPageNumber=$gaPageNumber&gaGallery=$gaGallery&gaFileName=$gaFileName&gaCaptchaText=$gaCaptchaText&gaCaptchaFileName=$gaCaptchaFileName";	

// these dont have the ecard args
$gaArgs			=	"gaRecordNumber=$gaRecordNumber&gaAnchor=$gaAnchor&gaArtistID=$gaArtistID&gaPageName=$gaPageName&gaImageIndex=$gaImageIndex&gaPageNumber=$gaPageNumber&gaGallery=$gaGallery&gaFileName=$gaFileName&gaTitle=$gaTitle&gaCaption=$gaCaption&gaLocation=$gaLocation&gaArtist=$gaArtist&gaMedia=$gaMedia&gaStatus=$gaStatus&gaPresentation=$gaPresentation&gaWidthOne=$gaWidthOne&gaWidthTwo=$gaWidthTwo&gaWidthThree=$gaWidthThree&gaWidthFour=$gaWidthFour&gaHeightOne=$gaHeightOne&gaHeightTwo=$gaHeightTwo&gaHeightThree=$gaHeightThree&gaHeightFour=$gaHeightFour&gaPriceOne=$gaPriceOne&gaPriceTwo=$gaPriceTwo&gaPriceThree=$gaPriceThree&gaPriceFour=$gaPriceFour&gaEditionTypeOne=$gaEditionTypeOne&gaEditionTypeTwo=$gaEditionTypeTwo&gaEditionTypeThree=$gaEditionTypeThree&gaEditionTypeFour=$gaEditionTypeFour&gaFileToSend500x=$gaFileToSend500x&gaFileToSend200x=$gaFileToSend200x&gaDescriptionOne=$gaDescriptionOne&gaDescriptionTwo=$gaDescriptionTwo&gaDescriptionThree=$gaDescriptionThree&gaDescriptionFour=$gaDescriptionFour";

if($debug) print "<hr>gallery_parms<br><br>";
if($debug) print "gaTitle: $gaTitle<br>";
if($debug) print "gaCaption: $gaCaption<br>";
if($debug) print "gaLocation: $gaLocation<br>";
if($debug) print "gaArtist: $gaArtist<br>";
if($debug) print "gaMedia: $gaMedia<br>";
if($debug) print "gaStatus: $gaStatus<br>";
if($debug) print "gaPresentation: $gaPresentation<br>";
if($debug) print "gaFileName: $gaFileName<br>";
if($debug) print "gaMode: $gaMode<br>";
if($debug) print "gaTable: $gaTable<br>";
if($debug) print "<hr>";
if($debug) print "gaArgs: $gaArgs<br>";
if($debug) print "<hr>";
if($debug) print "systemUserID: $systemUserID<br>";
if($debug) print "systemPassword: $systemPassword<br>";
if($debug) print "<hr>";

?>