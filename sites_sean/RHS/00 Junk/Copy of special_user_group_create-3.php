<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include_once 'class.csv_bv.php';

include '../local_info.php';	
include 'cl_lib.php';
include 'db_lib.php';
include 'pw_lib.php';
include 'file_lib.php';
include 'blog_lib.php';
include 'status_message.php';	
include 'mail_sub_parms.php';	
include 'mail_lib.php';	
include 'gallery_lib.php';	
include 'redirect.php';	
include 'name_value_pair_lib.php';
include 'html_lib.php';

$writeEnabled	=	true;

$debug	=	0;

$fileName		=	$_POST["FileName"];
$expeditionName	=	$_POST["ExpeditionName"];
$regDate		=	$_POST["RegularDate"];
$sqlDate		=	$_POST["SQLDate"];

// make a clean table ID
$expeditionID	=	MakeCleanID($expeditionName);
$expeditionID	.=	"-$sqlDate";


if($debug) print "<hr>Load uploaded CSV file...<br>";

if($debug) print "fileName: $fileName<br>";
if($debug) print "regDate: $regDate<br>";
if($debug) print "sqlDate: $sqlDate<br>";
if($debug) print "expeditionName: $expeditionName<br>";

// FIX: need to make a place for these file to go that is like /temp
// FIX should put a upper limit on the table name...

// create parser instance reference
$csv = & new csv_bv($fileName, ',', '"' , '\\');

// make file into array
$userInfoArray = $csv->csv2Array();

// get the number of rows loaded
$numberOfLines = $csv->RowCount();

if($debug) print "numberOfLines: $numberOfLines<br>";

//if($debug) print "<br><pre>" . print_r($userInfoArray) . "</pre><br>";

if($debug2)
	{
	for($i=1;$i<$numberOfLines;$i++)
		{
		$first	=	$userInfoArray[$i][0];
		$last	=	$userInfoArray[$i][1];
		$eMail	=	$userInfoArray[$i][2];	
	
		if($first != "" && $last != "" && $eMail != "")
			{
			print "first: $first<br>";
			print "last: $last<br>";
			print "eMail: $eMail<br>";
			}
		}
	}



if($debug) print "expeditionID: $expeditionID<br>";


// ---------------------------------------------------------------------------------------------------
// make a blog
// ---------------------------------------------------------------------------------------------------

if($debug) print "<hr>Create Blog...<br>";


// set up the blog lib vars
$blName			=	"$expeditionName";
$blDescription	=	"This is the group blog for $expeditionName leaving $regDate";
$blType			=	"LIFO";
$blModerator	=	"moderator"; // this is a default moderator

if(!$error)
	{
	if($debug) print "blDescription: $blDescription<br>";
	if($debug) print "blType: $blType<br>";
	if($debug) print "blName: $blName<br>";
	if($debug) print "blModerator: $blModerator<br>";

	// no links table
	$createLinksTable	=	false;

	if($debug) print "createLinksTable: $createLinksTable<br>";

	// decorate the table names
	$blogTableName		=	"APPDEV_BLOG_$expeditionID";
	$linksTableName		=	"APPDEV_LINKS_$expeditionID";

	if($debug) print "blogTableName: $blogTableName<br>";
	if($debug) print "linksTableName: $linksTableName<br>";

	// make blog table
	if($writeEnabled)
		{
		if($debug) print "CREATE BLOG TABLE<br>";

		if(CreateBlogTable($blogTableName)==false)
			{
			$error	=	1;
			$statusMessage	=	"Unable to create blog table...";
			}
		}
	}
	
	// make blog header entry
	if(!$error && $writeEnabled)
		{
		if($debug) print "CREATE BLOG HEADER RECORD<br>";
		
		if(CreateBlogHeaderRecord($blName,$blDescription,$blogTableName,$blType)==false)
			{
			$error			=	1;
			$statusMessage	=	"Unable to create blog header record...";
			}
		}

	// make the moderator permission
	if(!$error && $writeEnabled)
		{
		if($debug) print "CREATE USER PERMISSION<br>";
	
		NewUserPermission($blModerator,$blogTableName,1,0);
		}
		
	if($debug) print "statusMessage: $statusMessage<br>";
	if($debug) print "error: $error<br>";
	
	// set up the links table
	if(!$error && $createLinksTable && $writeEnabled)
		{
		if($debug) print "CREATE LINKS TABLE<br>";

		if(CreateBlogLinksTable($linksTableName)==false)
			{
			$error	=	1;
			$statusMessage	=	"Unable to create links table...";
			}
		}
		
	if(!$error && $writeEnabled)
		if($debug) print "BLOG INFASTRUCTURE CREATED<br>";
	else
		if($debug) print "ERROR CREATING BLOG INFASTRUCTURE<br>";

// ---------------------------------------------------------------------------------------------------
// make a gallery
// ---------------------------------------------------------------------------------------------------
if($debug) print "<hr>Create Gallery...<br>";

// set up ga... entrance conditions

if($debug) print "Processing: NewGallery<br>";
if($debug) print "expeditionID: $expeditionID<br>";

$galleryTableName	=	"APPDEV_GALLERY_$expeditionID";

// -----------------------------------------------------------------------------------------
// does this user exist already ?
// -----------------------------------------------------------------------------------------

// fix needs to append number if fail
if(!$error)
	{
	if(GetNumberOfRows("SELECT ArtistID FROM APPDEV_GalleryProfiles WHERE ArtistID='$expeditionID'"))
		{
		// Cant create, must mangle the name some... FIX
		
		$statusMessage	= 	"Duplicate gallery name.";
		$error			=	1;
		
		if($debug) print "ERROR: $statusMessage<br>";
		}
	}
	
// -----------------------------------------------------------------------------------------
// give the moderator permission
// -----------------------------------------------------------------------------------------

if(!$error)
	{
	if($writeEnabled) NewUserPermission("moderator",$galleryTableName,1,0);
	}
		
// -----------------------------------------------------------------------------------------
// create the gallery extras
// -----------------------------------------------------------------------------------------

if(!$error && $writeEnabled) 
	{
	if($debug) print "Create Gallery Extras...<br>";

	// set up the gallery infrastructure for the user
	$rv	=	NewGalleryUser($expeditionID,"NFS");

	if(!$rv)
		{
		$statusMessage	= 	"Unable to create gallery directories or table infrastructure...";

		if($debug) print "ERROR: $statusMessage<br>";

		$error			=	1;
		}
	}

// -----------------------------------------------------------------------------------------
// set the gallery name
// -----------------------------------------------------------------------------------------

if(!$error && $writeEnabled)
	{
	if($debug) print "Set Gallery Name...<br>";

	if(strlen($expeditionName))
		$rv	=	SetGalleryTitle($expeditionID,$expeditionName);

	if($debug) print "expeditionName: $expeditionName<br>";

	if(!$rv)
		{
		$statusMessage	= 	"Unable to set gallery title.";

		if($debug) print "ERROR: $statusMessage<br>";

		$error			=	1;
		}
	}


// ---------------------------------------------------------------------------------------------------
// make new granular rights users 
// ---------------------------------------------------------------------------------------------------


if(!$error)
	{
	if($debug) print "<hr>Creating Users...<br><br>";

	for($i=1;$i<$numberOfLines;$i++)
		{
		$first	=	$userInfoArray[$i][0];
		$last	=	$userInfoArray[$i][1];
		$eMail	=	$userInfoArray[$i][2];	
	
		$userID	=	MakeCleanID("$first$last");
	
		if($first != "" && $last != "" && $eMail != "")
			{
			$password	=	GenerateFriendlyPassword();
	
			if(UserExists($userID)==false)
				{
				// make the user
				if($writeEnabled) 
					{
					if(NewUserInfo($userID,$password,"Blog-Gallery",$eMail,$first,$last)==false)
						{
						$error	=	1;
						}
					}	
				
				if($error)
					print "Cant create User - $userID<br>";
				else
					if($debug) print "Creating: $userID<br>";
				}
			else
				{
				$error	=	1;
				
				// namespace conflict resolve with random string on end
				$guid	=	GeneratePassword(4);
				$userID	=	"$userID-$guid";
				
				print "Mangling User ID: $userID<br>";
	
				// mark it up so the next reff has the guid permutation
				$userInfoArray[$i][1]	=	$userInfoArray[$i][1] . "-$guid";
				
				
				// make the user
				if($writeEnabled) 
					{
					if(NewUserInfo($userID,$password,"Blog-Gallery",$eMail,$first,$last)==false)
						{
						$error	=	1;
						}
					}
				
				if($error)
					print "Cant create User - $userID<br>";
				else
					if($debug) print "Creating: $userID<br>";
				}
	
	
			}
		}
	} // end of if(!$error)

	



// ---------------------------------------------------------------------------------------------------
// make users with contributor rights for blog and gallery
// ---------------------------------------------------------------------------------------------------



// make permissions
if(!$error)
	{
	if($debug) print "<hr>Creating Rights for Users...<br><br>";

	for($i=1;$i<$numberOfLines;$i++)
		{
		$first	=	$userInfoArray[$i][0];
		$last	=	$userInfoArray[$i][1];
		$eMail	=	$userInfoArray[$i][2];	
	
		$userID	=	MakeCleanID("$first$last");
	
		if($first != "" && $last != "" && $eMail != "")
			{
			// make new, this function will not make duplicates...
			if($writeEnabled) NewUserPermission($userID,$galleryTableName,0,1);	// gallery contributor
			if($writeEnabled) NewUserPermission($userID,$blogTableName,0,1);			// blog contributor
	
			if($debug) print "Rights Created for User: $userID<br>";
			}
		}
	}
	
// ---------------------------------------------------------------------------------------------------
// send mail
// ---------------------------------------------------------------------------------------------------


if(!$error)
	{
	if($debug) print "<hr>Sending Mail to Users...<br><br>";
	
	// set up the stuff that dosent change...
	$fromAddress	=	GetEMail("sitesecretary");
	$subject		=	"User ID and Password for your Canyon REO expedition blog and gallery";  
	$fromName		=	"Donny at Canyon REO";

	// select the appropriate message template
	$templateFileName	=	"$gServerBaseMountPoint$gBaseMountPoint/messages/creo_expedition_user_welcome.html";

	if($debug) print "toAddress: $toAddress<br>";
	if($debug) print "fromAddress: $fromAddress<br>";
	if($debug) print "subject: $subject<br>";
	if($debug) print "fromName: $fromName<br>";
	if($debug) print "templateFileName: $templateFileName<br>";
	if($debug) print "numberOfLines: $numberOfLines<br>";

	for($i=1;$i<$numberOfLines;$i++)
		{
		$first	=	$userInfoArray[$i][0];
		$last	=	$userInfoArray[$i][1];
		$eMail	=	$userInfoArray[$i][2];	
		
		$userID	=	MakeCleanID("$first$last");

		$personalizedMessage	=	PasswordGetTemplateFile($userID,$templateFileName);
		$toAddress				=	$userInfoArray[$i][2];

		if($first != "" && $last != "" && $eMail != "")
			{
			if($debug) print "Send Mail To User $i: $userID<br>";

			if(!$error) //  && $writeEnabled
				{
				if(email_send($toAddress,$fromAddress,$fromName,$subject,$personalizedMessage)!=1)
					print "Error - Unable to Send Mail, Please Contact System Administrator";
				}
			// if($debug) print "<hr>personalizedMessage: $personalizedMessage<br>";
			}
		}	
	}

// ---------------------------------------------------------------------------------------------------
// announce success
// ---------------------------------------------------------------------------------------------------

if(!$error)
	$statusMessage	=	"User Group Created...";
else
	if($debug) print "Error: $statusMessage<br>";

$uriString	=	"special_user_group_create-1.php?StatusMessage=$statusMessage";

FormRedirect($uriString,"GET",$debug);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>New User Group - Create</title>
<link href="../images/css/local.css" rel="stylesheet" type="text/css" />
</head>

<body>
</body>
</html>
