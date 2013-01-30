<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_lib.php
//
// This will upload two images and insert the title, caption and fn in the DB
//
//
// Depends:	local_info
//			db_lib
//			html_lib
//
//	100108		added stripslashes to PrintImageTitle()
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

// ----------------------------------------------------------------------------------------
// Detail Images
//
// If there are detaill images for this image it will fetch and populate the detail template
// file and populate it...
// ----------------------------------------------------------------------------------------

function DisplayDetailImages($fileName, $artistID, $detailTemplateFileName="templates/detail_image_template.html")
{
global $gBaseMountPoint;
global $gGalleryMountPoint;
global $gGalleryDetailPictures;

$debug				=	0;

if($debug) print "DisplayDetailImages($fileName, $artistID, $detailTemplateFileName)<br>\n";

$path200x				=	"$gBaseMountPoint$gGalleryMountPoint/$artistID/200x/";	
$path500x				=	"$gBaseMountPoint$gGalleryMountPoint/$artistID/500x/";	

if($debug) print "path200x: $path200x<br>\n";
if($debug) print "path500x: $path500x<br>\n";

$detailTemplate		=	FileRead($detailTemplateFileName);

if($detailTemplate=="")
	print "Unable to load template file: $detailTemplateFileName - DisplayDetailImages()<br>\n";

if($debug) print "detailTemplate: $detailTemplate<br>\n";

$numberOfDetails	=	GetNumberOfDetailImages($fileName,$artistID);

if($debug) print "numberOfDetails: $numberOfDetails<br>\n";

if($numberOfDetails==0)
	{
	// there are no details for this image
	return;
	}

OpenDatabase();

$query	=	"SELECT * FROM APPDEV_ImageDetails WHERE FileName='$fileName' AND ArtistID='$artistID'";

if($debug) print "query: $query<br>\n";

$rs	=	QueryDatabase($query);

for($i=0;$i<4;$i++)
	{
	$fileName		=	GetDatabaseResult($rs,$i,"DetailFileName");
	
	$largeImagePage		=	"detail_image.php?FileName=$path500x$fileName";

	$width			=	10;
	$height			=	10;

	$windowOpen		=	JavaScriptWindowOpen($largeImagePage,"detailwin",$width,$height,"menubar=1,resizable=0");
		
	$anchorOpen		=	'<a href="#Details" onclick="' . $windowOpen . '">';
	$anchorClose	=	"</a>\n";

	if($fileName=="")
		$cellData	=	"&nbsp;\n";
	else
		{
		$imageTag	=	ImageTag($path200x . $fileName);
		$cellData	=	$anchorOpen . "\n" . $imageTag . "\n" . $anchorClose . "\n";
		}
		
	$tagNumber	=	$i+1;
	$tag		=	"_FILENAME$tagNumber" . "_";
	


	if($debug) print "i: $i<br>\n";
	if($debug) print "fileName: $fileName<br>\n";
	if($debug) print "cellData: $cellData<br>\n";
	if($debug) print "tag: $tag<br>\n";

	$detailTemplate	=	str_replace($tag,$cellData,$detailTemplate);
	}

CloseDatabase();

if($debug) print "<hr><br><br>\n";

print $detailTemplate;
}



function GetNumberOfDetailImages($fileName,$artistID)
{
$debug = 0;

if($debug) print "<hr>GetNumberOfDetailImages($fileName,$artistID)<br>";


if($fileName=="")
	return 0;

$query	=	"SELECT * FROM APPDEV_ImageDetails WHERE FileName='$fileName' AND ArtistID='$artistID'";
	
if($debug) print "query: $query<br>";

return GetNumberOfRows($query);
}

function DeleteDetailImage($fileName,$detailFileName,$artistID)
{
if($debug) print "<hr><br>DeleteDetailImage($fileName,$detailFileName,$artistID)<br><br>";

$rv				=	true;
$writeEnable	=	true;

$deleteQuery	=	"DELETE FROM APPDEV_ImageDetails 
					WHERE 
					FileName='$fileName' AND 
					DetailFileName='$detailFileName' AND 
					ArtistID='$artistID'";

if($debug) print "deleteQuery: $deleteQuery<br>";

if($writeEnable)
	{
	OpenDatabase();

	if(QueryDatabase($deleteQuery)==false)
		{
		if($debug) print "Unable to delete record<br>Return: FAIL<br><hr>";
		$rv		=	false;
		}

	CloseDatabase();
	}

return $rv;
}

function AddDetailImage($fileName,$detailFileName,$artistID)
{
$writeEnable	=	true;
$rv				=	true;
$debug			=	0;

if($debug) print "<hr><br>AddDetailImage($fileName,$detailFileName,$artistID)<br><br>";

OpenDatabase();

$recordExistsQuery	=	"SELECT * FROM APPDEV_ImageDetails WHERE DetailFileName='$detailFileName' AND ArtistID='$artistID'";

// record exists, delete first
if(mysql_num_rows(QueryDatabase($recordExistsQuery)) != 0)
		{
		$deleteQuery	=	"DELETE FROM APPDEV_ImageDetails WHERE DetailFileName='$detailFileName' AND ArtistID='$artistID'";

		if($debug) print "deleteQuery: $deleteQuery<br>";
		
		if($writeEnable)
			{
			if(QueryDatabase($deleteQuery)==false)
				{
				if($debug) print "Unable to delete prior record<br>Return: FAIL<br><hr>";
				$rv		=	false;
				}
			}
		}
	
if($writeEnable && $rv)
	{
	$query 		= 	sprintf("INSERT INTO 
							 APPDEV_ImageDetails 
							 (FileName,DetailFileName, ArtistID) 
							 VALUES 
							 ('%s','%s','%s')",
							mysql_real_escape_string($fileName),	
							mysql_real_escape_string($detailFileName),	
							mysql_real_escape_string($artistID)	
							);
	
	if($debug) print "query: $query<br>";

	if(QueryDatabase($query)==FALSE)
		{
		if($debug) print "Return: FAIL<br><hr>";
		
		$rv		=	false;
		}
	}

CloseDatabase();

if($rv && $debug) 		
		print "Return: SUCCESS<br><hr>";

return $rv;
}




// ---------------------------------------------------------------------------------------------------------------------------------------
// Get page names for thumbs and image.
// ---------------------------------------------------------------------------------------------------------------------------------------

function GetGalleryThumbsPage($artistID)
{
return GetFieldByComparison("APPDEV_GalleryProfiles","ThumbsPageName","ArtistID",$artistID);
}

function GetGalleryImagePage($artistID)
{
return GetFieldByComparison("APPDEV_GalleryProfiles","ImagePageName","ArtistID",$artistID);
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// PROFILES
// ---------------------------------------------------------------------------------------------------------------------------------------

function GalleryIsVisible($artistID)
{
$flag		=	GetFieldByComparison("APPDEV_GalleryProfiles","Flag","ArtistID",$artistID);

if($flag=="SHOW")
	return true;

return false;
}



function NewGalleryProfile($artistID,$galleryType)
{
$debug = 0;
$writeProtect	=	0;

if($debug) print "<hr><br>NewGalleryProfile($artistID,$galleryType)<br><br>";

// does this id exist ?
if(GetNumberOfRows("SELECT * FROM APPDEV_GalleryProfiles WHERE ArtistID='$artistID'"))
	{
	if($debug) print "USER EXIST fail<br>";
	return false;
	}

OpenDatabase();

$query 		= 	sprintf("INSERT INTO 
						 APPDEV_GalleryProfiles 
						 (Title,FileName,ArtistID,Type,Flag) 
						 VALUES 
						 ('New Gallery for %s','No300xPhoto.jpg','%s','%s','HIDE')",
						mysql_real_escape_string($artistID),	
						mysql_real_escape_string($artistID),	
						mysql_real_escape_string($galleryType)	
						);

if($debug) print "<br><br>query: $query<br><br>";

if(!$writeProtect)
	{
	if(QueryDatabase($query)==FALSE)
		{
		if($debug) print "INSERT fail<br>";
		CloseDatabase();
		
		if($debug) print "<br><br>NewGalleryProfile($artistID) returned FAIL<br><br>";
		
		return FALSE;
		}
	}

	
CloseDatabase();
	
if($debug) print "<br><br>NewGalleryProfile($artistID) returned SUCCESS<br><br>";

return TRUE;
}

// this will initialize the globals with the indicated info
// used to set up the profile info on entry to the profile editor

function GetGalleryName($artistID)
{
// get the name of the gallery for the page title
$galleryName		=	GetFieldByComparison("APPDEV_GalleryProfiles","Title","ArtistID",$artistID);

return $galleryName;
}

function GetProfileArtistName($artistID)
{
// get the name of the artist
$name			=	GetFieldByComparison("APPDEV_GalleryProfiles","ArtistName","ArtistID",$artistID);

return $name;
}

// used with GetNumberOfGalleries() to iterate the gallery tables
// this will only get values for visible artists galleries
function GetProfileArtistID($index)
{
$debug	=	0;

if($debug) print "<hr>GetProfileArtistID($index)<br>";

// 2.1.1 BUG FIXED
//$artistID	=	GetFieldByIndexWithSortField("APPDEV_GalleryProfiles","ArtistID","SortOrder",1,$index);

$query		=	"SELECT * FROM APPDEV_GalleryProfiles WHERE FLAG='SHOW' ORDER BY SortOrder";

if($debug) print "query: $query<br>";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

$artistID	=	GetDatabaseResult($rs,$index,"ArtistID");	

if($debug) print "artistID: $artistID<br><hr>";

return $artistID;
}

function GetProfileGalleryType($artistID)
{
return 	GetFieldByComparison("APPDEV_GalleryProfiles","Type","ArtistID",$artistID);
}


// this gets the absolute number of gallery tables
function GetNumberOfGalleryTables()
{
$debug	=	0;

if($debug) print "GetNumberOfGalleryTables()<br>";

$query	=	"SHOW TABLES LIKE 'APPDEV_GALLERY_%'";

if($debug) print "query: $query<br>";

$numberOfRows	= 	GetNumberOfRows($query);

if($debug) print "numberOfRows: $numberOfRows<br>";

return $numberOfRows;
}


// gets all galleries with flags set to SHOW
function GetNumberOfGalleries()
{
return	GetNumberOfRows("SELECT * FROM APPDEV_GalleryProfiles WHERE Flag='SHOW'");
}

function LoadArtistProfile($artistID)
{
global $gaFileName;
global $gaGalleryTitle;
global $gaStatement;
global $gaWebsite;
global $gaArtist;
global $gaStudio;
global $gaEMail;
global $gaAddress;
global $gaCity;
global $gaState;
global $gaZip;
global $gaPhone;

$gaFileName		=	GetFieldByComparison("APPDEV_GalleryProfiles","FileName","ArtistID",$artistID);
$gaGalleryTitle	=	GetFieldByComparison("APPDEV_GalleryProfiles","Title","ArtistID",$artistID);
$gaStatement	=	GetFieldByComparison("APPDEV_GalleryProfiles","Statement","ArtistID",$artistID);
$gaWebsite		=	GetFieldByComparison("APPDEV_GalleryProfiles","Website","ArtistID",$artistID);
$gaArtist		=	GetFieldByComparison("APPDEV_GalleryProfiles","ArtistName","ArtistID",$artistID);
$gaStudio		=	GetFieldByComparison("APPDEV_GalleryProfiles","StudioName","ArtistID",$artistID);
$gaEMail		=	GetFieldByComparison("APPDEV_GalleryProfiles","EMailAddress","ArtistID",$artistID);
$gaAddress		=	GetFieldByComparison("APPDEV_GalleryProfiles","StreetAddress","ArtistID",$artistID);
$gaCity			=	GetFieldByComparison("APPDEV_GalleryProfiles","City","ArtistID",$artistID);
$gaState		=	GetFieldByComparison("APPDEV_GalleryProfiles","State","ArtistID",$artistID);
$gaZip			=	GetFieldByComparison("APPDEV_GalleryProfiles","Zip","ArtistID",$artistID);
$gaPhone		=	GetFieldByComparison("APPDEV_GalleryProfiles","Phone","ArtistID",$artistID);
}


// this will fetch the gallery profile file template with the indicated information in it...
function GetGalleryProfile($artistID,$templateFile="../templates/gallery_t_lobby.html",$thumbsPageName="gallery_thumbs.php")
{
global	$gBaseMountPoint;
global 	$gGalleryMountPoint;

$debug = 0;

if($debug) print "<hr>GetGalleryProfile($artistID,$templateFile,$thumbsPageName)<br>";

// get the profile info
$fileName		=	GetFieldByComparison("APPDEV_GalleryProfiles","FileName","ArtistID",$artistID);
$title			=	GetFieldByComparison("APPDEV_GalleryProfiles","Title","ArtistID",$artistID);
$statement		=	GetFieldByComparison("APPDEV_GalleryProfiles","Statement","ArtistID",$artistID);
$website		=	GetFieldByComparison("APPDEV_GalleryProfiles","Website","ArtistID",$artistID);
$name			=	GetFieldByComparison("APPDEV_GalleryProfiles","ArtistName","ArtistID",$artistID);
$studio			=	GetFieldByComparison("APPDEV_GalleryProfiles","StudioName","ArtistID",$artistID);
$numberOfImages	=	GetNumberOfRows("SELECT * FROM APPDEV_GALLERY_$artistID WHERE 1");
$galleryLink	=	"$thumbsPageName?gaPageNumber=1&gaGallery=APPDEV_GALLERY_$artistID";

if($debug) print "fileName: $fileName<br>";
if($debug) print "title: $title<br>";
if($debug) print "statement: $statement<br>";
if($debug) print "website: $website<br>";
if($debug) print "name: $name<br>";
if($debug) print "studio: $studio<br>";

$galleryPath		=	"$gBaseMountPoint$gGalleryMountPoint/$artistID/300x/";
$fileNameAndPath	=	"$galleryPath$fileName";

if($debug) print "galleryPath: $galleryPath<br>";
if($debug) print "fileNameAndPath: $fileNameAndPath<br>";
if($debug) print "<hr>";

$website		=	CleanURL($website);

// read in the file
$templateFile	=	FileRead($templateFile);

$templateFile	=	str_replace("_WEBSITE_URL_",$website,$templateFile);
$templateFile	=	str_replace("_IMAGE_FILE_AND_PATH_",$fileNameAndPath,$templateFile);
$templateFile	=	str_replace("_GALLERY_TITLE_",$title,$templateFile);
$templateFile	=	str_replace("_ARTIST_NAME_",$name,$templateFile);
$templateFile	=	str_replace("_STUDIO_NAME_",$studio,$templateFile);
$templateFile	=	str_replace("_ARTIST_STATEMENT_",$statement,$templateFile);
$templateFile	=	str_replace("_NUMBER_OF_IMAGES_",$numberOfImages,$templateFile);
$templateFile	=	str_replace("_GALLERY_LINK_",$galleryLink,$templateFile);
$templateFile	=	str_replace("_WEBSITE_TABLE_ROW_",$websiteRow,$templateFile);

return $templateFile;
}




function UpdateArtistProfile($title,$statement,$fileName,$website,$artist,$studio,$eMail,$address,$city,$state,$zip,$phone,$artistID)
{
$debug	=	0;

if($debug) print "<hr>UpdateArtistProfile($title,$statement,$fileName,$website,$artist,$studio,$eMail,$address,$city,$state,$zip,$phone,$artistID)<br>";

// munge any returns out of the statement
$statement = implode("<br>", preg_split("/\r?\n/", $statement));

$format			=	"
					UPDATE APPDEV_GalleryProfiles 
					SET FileName='%s',
					Title='%s',
					Statement='%s',
					Website='%s',
					ArtistName='%s',
					StudioName='%s',
					EMailAddress='%s',
					StreetAddress='%s',
					City='%s',
					State='%s',
					Zip='%s',
					Phone='%s'
					WHERE
					ArtistID='%s'
					";


if($debug) print "<br><br>format: $format<br>";

// open and close as the escape string func
// relies on the connection context for encoding.

OpenDatabase();

$query 		= 	sprintf($format,
						mysql_real_escape_string($fileName),
						mysql_real_escape_string($title),
						mysql_real_escape_string($statement),
						mysql_real_escape_string($website),
						mysql_real_escape_string($artist),
						mysql_real_escape_string($studio),
						mysql_real_escape_string($eMail),
						mysql_real_escape_string($address),
						mysql_real_escape_string($city),
						mysql_real_escape_string($state),
						mysql_real_escape_string($zip),
						mysql_real_escape_string($phone),
						mysql_real_escape_string($artistID)
						);

if($debug) print "<br><br>query: $query<br>";

$rv	=	QueryDatabase($query);

if($debug) print "<br><br>rv: $rv<br><hr>";

CloseDatabase();

return $rv;
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// SERVICE ROUTINES
// ---------------------------------------------------------------------------------------------------------------------------------------

function GetArtistIdFromGallery($galleryTableName)
{
$artistID		=	str_replace("APPDEV_GALLERY_","",$galleryTableName);

return $artistID;
}


function GetArtistName($fileName,$galleryTableName)
{
// figure out who and get their name
$artistID	=	GetArtistIdFromGallery($galleryTableName);

$query		=	"SELECT * FROM APPDEV_ImageLibrary WHERE FileName='$fileName' AND ArtistID='$artistID'";

OpenDatabase();

$rs			=	QueryDatabase($query);	

CloseDatabase();

$artistName	=	GetDatabaseResult($rs,0,"Artist");

return $artistName;
}

// does everything necessary to create a new gallery user except the 
// password user stuff
function NewGalleryUser($userID,$galleryType)
{
global $gServerBaseMountPoint;
global $gBaseMountPoint; 
global $gGalleryMountPoint; 
$writeEnabled	=	true;

$debug = 0;

if($debug) print "<hr>NewGalleryUser($userID,$galleryType)<br>";

if($writeEnabled) 
	if($debug) print "WRITE ENABLED<br>";
else
	if($debug) print "WRITE DISABLED<br>";

// insert profile record
if($writeEnabled)
	$rv	=	NewGalleryProfile($userID,$galleryType);
else
	$rv	=	1;

if($rv==false)
	{
	print "Error in NewGalleryUser($userID,$galleryType) Unable to create gallery profile entry<br>";
	return false;
	}
	
if($debug) print "rv=$rv<br>";

// create gallery table
if($writeEnabled)
	$rv	=	NewGalleryTable($userID);
else
	$rv	=	1;

if($rv==false)
	{
	print "Error in NewGalleryUser($userID,$galleryType) Unable to create gallery table<br>";
	return false;
	}

// make user directories
$pathToSiteRoot				=	$gServerBaseMountPoint;	
$pathToUserDirectoryRoot	=	$pathToSiteRoot . $gGalleryMountPoint;	
$appDevResourceDirectory	=	$pathToSiteRoot . "/images/gallery";
$userDirectory				=	"$pathToUserDirectoryRoot/$userID";

if($debug) print "pathToUserDirectoryRoot: $pathToUserDirectoryRoot<br>";

$user_200x_Dir	=	"$userDirectory/200x";
$user_300x_Dir	=	"$userDirectory/300x";
$user_500x_Dir	=	"$userDirectory/500x";

if($debug) print "userDirectory: $userDirectory<br>";
if($debug) print "user_200x_Dir: $user_200x_Dir<br>";
if($debug) print "user_300x_Dir: $user_300x_Dir<br>";
if($debug) print "user_500x_Dir: $user_500x_Dir<br>";

// make user dir
if($writeEnabled)
	$rv	=	mkdir($userDirectory,0777);
else
	$rv	=	1;

if($rv==false)
	{
	print "Error in NewGalleryUser($userID,$galleryType) Unable to create user root directory<br>";
	return false;
	}
	
if($debug) print "mkdir($userDirectory,0777) returned $rv<br>";

// make 200x subdirectory
if($writeEnabled)
	$rv	=	mkdir($user_200x_Dir,0777);
else
	$rv	=	1;

if($rv==false)
	{
	print "Error in NewGalleryUser($userID,$galleryType) Unable to create user 200x directory<br>";
	return false;
	}

if($debug) print "mkdir($user_200x_Dir,0777) returned $rv<br>";

// make 300x subdirectory
if($writeEnabled)
	$rv	=	mkdir($user_300x_Dir,0777);
else
	$rv	=	1;

if($rv==false)
	{
	print "Error in NewGalleryUser($userID,$galleryType) Unable to create user 300x directory<br>";
	return false;
	}

if($debug) print "mkdir($user_300x_Dir,0777) returned $rv<br>";

// make 500x subdirectory
if($writeEnabled)
	$rv	=	mkdir($user_500x_Dir,0777);
else
	$rv	=	1;

if($rv==false)
	{
	print "Error in NewGalleryUser($userID) Unable to create user 500x directory<br>";
	return false;
	}

if($debug) print "mkdir($user_500x_Dir,0777) returned $rv<br>";

// copy no-photo to 300x

$defaultPictureFileName		=	"No300xPhoto.jpg";	

$source	=	"$appDevResourceDirectory/$defaultPictureFileName";	
$dest	=	"$user_300x_Dir/$defaultPictureFileName";	

if($debug) print "source: $source<br>";
if($debug) print "dest: $dest<br>";

if($writeEnabled)
	$rv	=	copy($source,$dest);
else
	$rv	=	1;

if($rv==false)
	{
	print "Error in NewGalleryUser($userID,$galleryType) Unable to copy default file<br>";
	return false;
	}

if($debug) print "copy($source,$dest) returned: $rv<br>";

return true;
}


// ---------------------------------------------------------------------------------------------------------------------------------------
// these blow apart the 24 x 36 size specification into WH
// ---------------------------------------------------------------------------------------------------------------------------------------

function GalleryGetWidth($size)
{
$components	=	explode(" x ",$size);

return	$components[0];
}

function GalleryGetHeight($size)
{
$components	=	explode(" x ",$size);

return	$components[1];
}


// ---------------------------------------------------------------------------------------------------------------------------------------
// GALLERY ROUTINES
// ---------------------------------------------------------------------------------------------------------------------------------------

// creates a new gallery table called APPDEV_GALLERY_userID
function NewGalleryTable($userID)
{
$debug = 0;
$writeProtect	=	0;

if($debug) print "<hr><br>NewGalleryTable($artistID)<br><br>";

$query	=	"
			CREATE TABLE `APPDEV_GALLERY_$userID` 
			(
			`FileName` text NOT NULL,
			`RecordNumber` int(4) unsigned NOT NULL auto_increment,
			PRIMARY KEY  (`RecordNumber`)
			) 
			ENGINE=MyISAM 
			AUTO_INCREMENT=10 
			DEFAULT CHARSET=latin1 
			COMMENT='This is a gallery table template' 
			AUTO_INCREMENT=1 ;
			";

if($debug) print "<br><br>query: $query<br><br>";

if(!$writeProtect)
	{
	OpenDatabase();

	if(QueryDatabase($query)==FALSE)
		{
		if($debug) print "INSERT fail<br>";
		CloseDatabase();
		
		if($debug) print "<br><br>NewGalleryTable($artistID) returned FAIL<br><br>";
		
		return FALSE;
		}
	
	CloseDatabase();
	}
	
if($debug) print "<br><br>NewGalleryTable($artistID) returned SUCCESS<br><br>";

return TRUE;
}

// cleans ou a gallery
function TruncateGallery($artistID)
{
$debug			=	0;
$writeProtect	=	0;

if($debug) print "<hr><br>TruncateGallery($artistID)<br><br>";

$query	=	"
			TRUNCATE 
			APPDEV_GALLERY_$artistID
			";

if($debug) print "<br><br>query: $query<br><br>";

if(!$writeProtect)
	{
	OpenDatabase();

	if(QueryDatabase($query)==FALSE)
		{
		if($debug) print "TRUNCATE fail<br>";
		CloseDatabase();
		
		if($debug) print "<br><br>TruncateGallery($artistID) returned FAIL<br><br>";
		
		return FALSE;
		}
	
	CloseDatabase();
	}
	
if($debug) print "<br><br>TruncateGallery($artistID) returned SUCCESS<br><br>";

return TRUE;
}


// changed in 2.2.0

function GalleryFileExists($fileName)
{
$debug	=	0;

if($debug) print "<hr><br>GalleryFileExists($fileName)<br>";

// does it exist in the same asset tree/gallery ?
$libraryQuery	=	"	SELECT * FROM APPDEV_ImageLibrary 
						WHERE FileName='$fileName' 
					";

if($debug) print "libraryQuery: $libraryQuery<br>\n";

if(GetNumberOfRows($libraryQuery))
	{
	if($debug) print " == TRUE<br><br>";
	return TRUE;
	}

// does it exist as an e-commerce offering ?
$sizesQuery	=	"SELECT * FROM APPDEV_ImageSizes WHERE FileName='$fileName'";

if(GetNumberOfRows($sizesQuery))
	{
	if($debug) print " == TRUE<br><br>";
	return TRUE;
	}
	
if($debug) print " == FALSE<br><br>";

return FALSE;
}

function AddImageToGallery($fileName,$galleryTableName)
{
$debug			=	0;
$writeProtect	=	0;

// FIX need to delete from galleries as well

if($debug) print "<br><br>AddImageToGallery($fileName,$galleryTableName)<br><br>";

OpenDatabase();

$query 		= 	sprintf("INSERT INTO 
						 %s 
						 VALUES 
						 ( '%s' , '')",
						mysql_real_escape_string($galleryTableName),	
						mysql_real_escape_string($fileName)
						);

if($debug) print "<br><br>query: $query<br><br>";

if(!$writeProtect)
	{
	if(QueryDatabase($query)==FALSE)
		{
		if($debug) print "INSERT fail<br>";
		CloseDatabase();
		
		if($debug) print "<br><br>AddImageToGallery($fileName,$galleryTableName) returned FAIL<br><br>";
		
		return FALSE;
		}
	}
	
CloseDatabase();	

if($debug) print "<br><br>AddImageToGallery($fileName,$galleryTableName) returned SUCCESS<br><br>";

return TRUE;
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// IMAGE LIBRARY ROUTINES
// ---------------------------------------------------------------------------------------------------------------------------------------
// FIX HERE USE RECNO!!!
function UpdateImageLibrary($fileName,$title,$location,$caption,$artist,$media)
{
$debug	=	0;

if($debug) print "<hr><br>UpdateImageLibrary($fileName,$title,$location,$caption,$artist,$media)<br>\n";

$format			=	"
					UPDATE APPDEV_ImageLibrary 
					SET Title='%s',
					Location='%s',
					Caption='%s',
					Artist='%s',
					Media='%s'
					WHERE
					FileName='%s'
					";

if($debug) print "<br><br>format: $format<br>\n";

// open and close as the escape string func
// relies on the connection context for encoding.

OpenDatabase();

$query 		= 	sprintf($format,
						mysql_real_escape_string($title),
						mysql_real_escape_string($location),
						mysql_real_escape_string($caption),
						mysql_real_escape_string($artist),
						mysql_real_escape_string($media),
						mysql_real_escape_string($fileName)
						);

if($debug) print "<br><br>query: $query<br>";

$rv	=	QueryDatabase($query);

if($debug) print "<br><br>rv: $rv<br><hr>";

CloseDatabase();

return $rv;
}

function DeleteImageSizesFromLibrary($fileName)
{
$debug			=	0;
$writeProtect	=	0;

$sizeQuery	=	"
				DELETE FROM 
				APPDEV_ImageSizes 
				WHERE
				FileName = '$fileName'
				";

if($debug) print "<br><br>sizeQuery: $sizeQuery<br><br>";

if(!$writeProtect)
	{
	OpenDatabase();

	if(QueryDatabase($sizeQuery)==FALSE)
		{
		if($debug) print "DELETE from gallery fail<br>";
		
		CloseDatabase();
		
		if($debug) print "<br><br>DeleteImageFromLibrary($fileName) returned FAIL<br><br>";
		
		return FALSE;
		}
	CloseDatabase();
	}
	
if($debug) print "<br><br>DeleteImageFromLibrary($fileName) returned SUCCESS<br><br>";

return TRUE;
}

function DeleteImageFromLibrary($fileName,$artistID,$ownerID)
{
$debug			=	0;
$writeProtect	=	0;

if($debug) print "<hr>DeleteImageFromLibrary($fileName,$artistID)<br><br>";

$libraryQuery	=	"
			DELETE FROM 
			APPDEV_ImageLibrary 
			WHERE
			FileName = '$fileName'
			";

$sizeQuery	=	"
			DELETE FROM 
			APPDEV_ImageSizes 
			WHERE
			FileName = '$fileName'
			";

$galleryQuery	=	"
			DELETE FROM 
			APPDEV_GALLERY_$ownerID 
			WHERE
			FileName = '$fileName'
			";


if($debug) print "libraryQuery: $libraryQuery<br><br>";
if($debug) print "sizeQuery: $sizeQuery<br><br>";
if($debug) print "galleryQuery: $galleryQuery<br><br>";

// is this a FS gallery ?

//$galleryTable	=	"APPDEV_GALLERY_$ownerID";

if(!$writeProtect)
	{
	OpenDatabase();

	if(QueryDatabase($libraryQuery)==FALSE)
		{
		if($debug) print "DELETE from lib fail<br>";
		
		CloseDatabase();
		
		if($debug) print "<br><br>DeleteImageFromLibrary($fileName) returned FAIL<br><br>";
		
		return FALSE;
		}

	if(QueryDatabase($galleryQuery)==FALSE)
		{
		if($debug) print "DELETE from size fail<br>";
		
		CloseDatabase();
		
		if($debug) print "<br><br>DeleteImageFromLibrary($fileName) returned FAIL<br><br>";
		
		return FALSE;
		}

	if(QueryDatabase($sizeQuery)==FALSE)
		{
		if($debug) print "DELETE from gallery fail<br>";
		
		CloseDatabase();
		
		if($debug) print "<br><br>DeleteImageFromLibrary($fileName) returned FAIL<br><br>";
		
		return FALSE;
		}


	
	CloseDatabase();
	}
	

if($debug) print "<br><br>DeleteImageFromLibrary($fileName) returned SUCCESS<br><br>";

return TRUE;
}

// This will load the globals with the data from a lib record
//function LoadImageDataFromLibrary($fileName,$artistID)
function LoadImageDataFromLibrary($recordNumber,$galleryTableName)
{
$debug	=	0;

if($debug) print "<hr><br>LoadImageDataFromLibrary($recordNumber,$galleryTableName)<br><br>";

global $gaTitle;
global $gaCaption;
global $gaLocation;
global $gaArtist;
global $gaMedia;
global $gaStatus;
global $gaPresentation;
global $gaMode;
global $gaWidthOne;
global $gaWidthTwo;
global $gaWidthThree;
global $gaWidthFour;
global $gaHeightOne;
global $gaHeightTwo;
global $gaHeightThree;
global $gaHeightFour;
global $gaPriceOne;
global $gaPriceTwo;
global $gaPriceThree;
global $gaPriceFour;
global $gaEditionTypeOne;
global $gaEditionTypeTwo;
global $gaEditionTypeThree;
global $gaEditionTypeFour;
global $gaDescriptionOne;
global $gaDescriptionTwo;
global $gaDescriptionThree;
global $gaDescriptionFour;

$ownerID		=	str_replace("APPDEV_GALLERY_","",$galleryTableName);

$query			=	"
					SELECT * FROM
					APPDEV_ImageLibrary
					WHERE
					RecordNumber='$recordNumber'
					";

if($debug) print "ownerID: $ownerID<br>";
if($debug) print "query: $query<br>";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

$gaFileName		=	GetDatabaseResult($rs,0,"FileName");
$gaTitle		=	GetDatabaseResult($rs,0,"Title");
$gaCaption		=	GetDatabaseResult($rs,0,"Caption");
$gaLocation		=	GetDatabaseResult($rs,0,"Location");
$gaArtist		=	GetDatabaseResult($rs,0,"Artist");
$gaMedia		=	GetDatabaseResult($rs,0,"Media");

if($debug) print "gaFileName: $gaFileName<br>";
if($debug) print "gaTitle: $gaTitle<br>";
if($debug) print "gaCaption: $gaCaption<br>";
if($debug) print "gaLocation: $gaLocation<br>";
if($debug) print "gaArtist: $gaArtist<br>";
if($debug) print "gaMedia: $gaMedia<br>";


$query			=	"
					SELECT * FROM
					APPDEV_ImageSizes
					WHERE
					ArtistID='$ownerID'
					AND
					FileName='$gaFileName';
					";

if($debug) print "query: $query<br>";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();


$numberOfRows	=	mysql_numrows($rs);

if($debug) print "numberOfRows: $numberOfRows<br>";

// get a size

$gaHeightOne		=	GetDatabaseResult($rs,0,"Size");
$gaHeightTwo		=	GetDatabaseResult($rs,1,"Size");
$gaHeightThree		=	GetDatabaseResult($rs,2,"Size");
$gaHeightFour		=	GetDatabaseResult($rs,3,"Size");

// now that that stupidty is over... dooohhhh!!!

$gaPriceOne			=	GetDatabaseResult($rs,0,"Price");
$gaPriceTwo			=	GetDatabaseResult($rs,1,"Price");
$gaPriceThree		=	GetDatabaseResult($rs,2,"Price");
$gaPriceFour		=	GetDatabaseResult($rs,3,"Price");

$gaEditionTypeOne	=	GetDatabaseResult($rs,0,"DescriptionTwo");
$gaEditionTypeTwo	=	GetDatabaseResult($rs,1,"DescriptionTwo");
$gaEditionTypeThree	=	GetDatabaseResult($rs,2,"DescriptionTwo");
$gaEditionTypeFour	=	GetDatabaseResult($rs,3,"DescriptionTwo");

$gaDescriptionOne	=	GetDatabaseResult($rs,0,"DescriptionOne");
$gaDescriptionTwo	=	GetDatabaseResult($rs,1,"DescriptionOne");
$gaDescriptionThree	=	GetDatabaseResult($rs,2,"DescriptionOne");
$gaDescriptionFour	=	GetDatabaseResult($rs,3,"DescriptionOne");


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


}


function AddImageDataToLibrary($fileName,$title,$location,$caption,$artist,$ownerID,$artistID,$media,$orientation)
{
$debug	=	0;

if($debug) print "<br><br>AddImageDataToLibrary($fileName,$title,$location,$caption,$artist,$ownerID,$artistID,$media,$orientation)<br><br>";

// see if the caption string contains HTML, if it is HTML no further action is necessary
// 2.2.0 BUG FIX
if(IsHtml($caption)==false)
	{
	// If not I will convert the \r\n's to <br>'s

	// munge the message string and make <br>'s from \n's
	$caption = implode("<br>", preg_split("/\r?\n/", $caption));
	if($debug) print "CAPTION is NOT HTML<br>";
	}
else
	if($debug) print "CAPTION is HTML<br>";

OpenDatabase();

$query 		= 	sprintf("INSERT INTO 
						 APPDEV_ImageLibrary 
						 (FileName,Title,Location,Caption,Artist,ArtistID,OwnerID,Media,Orientation) 
						 VALUES 
						 ( '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s' , '%s' )",
						mysql_real_escape_string($fileName),	
						mysql_real_escape_string($title),	
						mysql_real_escape_string($location),
						mysql_real_escape_string($caption),
						mysql_real_escape_string($artist),
						mysql_real_escape_string($artistID),
						mysql_real_escape_string($ownerID),
						mysql_real_escape_string($media),
						mysql_real_escape_string($orientation)
						);

if($debug) print "<br><br>query: $query<br><br>";

if(!$writeProtect)
	{
	if(QueryDatabase($query)==FALSE)
		{
		if($debug) print "Query FAIL!<br>";
		if($debug) print "AddImageDataToLibrary returned FALSE<br>";
		print mysql_error();
		CloseDatabase();
		return FALSE;
		}
	}
	
CloseDatabase();

if($debug) print "AddImageDataToLibrary returned TRUE<br>";


return TRUE;
}

// I remmed the old and am updating this to the new schema
function AddImageSizetoLibrary($fileName, $artistID, $size, $price, $descriptionOne, $descriptionTwo)
{
$debug = 0;

if($debug) print "<br><br>AddImageSizetoLibrary($fileName, $artistID, $size, $price, $descriptionOne, $descriptionTwo)<br><br>";

// is the price invalid input ?
if(is_numeric($price)==FALSE)
	{
	if($debug) print "Price is non numeric: $price<br>";
	return FALSE;
	}
	
// did they use a comma rather then a dot ?
$price	=	str_replace(",",".",$price);

// did they include a $ sign
$price	=	str_replace("$","",$price);

// did they forget to zero extend ?
if(strstr($price,".")==FALSE)
	{
	if($debug) print "Has no dot<br>";

	$price	=	sprintf("%.2f",$price);
	}
else
	{
	if($debug) print "Has a dot<br>";

	$price	=	sprintf("%01.2f",$price);
	}

if($debug) print "Fixed up price: $price<br>";

OpenDatabase();

$query 		= 	sprintf("INSERT INTO 
						 APPDEV_ImageSizes 
						 (FileName, ArtistID, Size, Price, DescriptionOne, DescriptionTwo)
						 VALUES
						 ( '%s' , '%s' , '%s' , '%s' , '%s' , '%s'  )",
						mysql_real_escape_string($fileName),	
						mysql_real_escape_string($artistID),
						mysql_real_escape_string($size),	
						mysql_real_escape_string($price),
						mysql_real_escape_string($descriptionOne),
						mysql_real_escape_string($descriptionTwo)
						);

if($debug) print "<br><br>query: $query<br><br>";

if(QueryDatabase($query)==FALSE)
	{
	if($debug) print "Query FAIL<br>";
	CloseDatabase();
	return FALSE;
	}
	
CloseDatabase();

if($debug) print "SUCCESS<br>";

return TRUE;
}


/*
function AddImageSizetoLibrary($fileName, $artistID, $width, $height, $price, $description, $editionType)
{
$debug	=	0;

if($debug) print "<br><br>AddImageSizetoLibrary($fileName, $artistID, $width, $height, $price, $description, $editionType)<br><br>";

// is the price invalid input ?
if(is_numeric($price)==FALSE)
	{
	if($debug) print "Price is non numeric: $price<br>";
	return FALSE;
	}
	
// did they use a comma rather then a dot ?
$price	=	str_replace(",",".",$price);

// did they include a $ sign
$price	=	str_replace("$","",$price);

// did they forget to zero extend ?
if(strstr($price,".")==FALSE)
	{
	if($debug) print "Has no dot<br>";

	$price	=	sprintf("%.2f",$price);
	}
else
	{
	if($debug) print "Has a dot<br>";
	// this sucks
	$price	=	sprintf("%01.2f",$price);
	}

if($debug) print "Fixed up price: $price<br>";

// set up the size var
if($width == "NA" || $height == "NA")
	{
	$size	=	"";
	}
else
	$size	=	"$width x $height";

OpenDatabase();

$query 		= 	sprintf("INSERT INTO 
						 APPDEV_ImageSizes 
						 (FileName, ArtistID, Size, Price, Description, EditionType)
						 VALUES
						 ( '%s' , '%s' , '%s' , '%s' , '%s' , '%s'  )",
						mysql_real_escape_string($fileName),	
						mysql_real_escape_string($artistID),
						mysql_real_escape_string($size),	
						mysql_real_escape_string($price),
						mysql_real_escape_string($description),
						mysql_real_escape_string($editionType)
						);


if($debug) print "<br><br>query: $query<br><br>";


if(QueryDatabase($query)==FALSE)
	{
	if($debug) print "Query FAIL<br>";
	CloseDatabase();
	return FALSE;
	}
	
CloseDatabase();

if($debug) print "SUCCESS<br>";

return TRUE;
}
*/

// ---------------------------------------------------------------------------------------------------------------------------------------
// function PrintImageLinkOpen($gallery,$imageIndex,$pageNumber,$targetPage="gallery_image.php")
// function PrintImageLinkClose($gallery,$imageIndex)
// ---------------------------------------------------------------------------------------------------------------------------------------

function PrintImageLinkOpen($gallery,$imageIndex,$pageNumber,$imagePageName="gallery_image.php")
{
$maxItems		=	GetNumberOfRows("SELECT * FROM $gallery");

if($imageIndex >= $maxItems)
	{
	print "<!-- No Link Open -->";
	return;
	}

// get the filename, this comes from the gallery table
$fileName		=	GetFieldByIndex("$gallery","FileName",$imageIndex);	

// set up the link open
$linkOpen		=	'<a href="' . "$imagePageName?gaFileName=" . $fileName . '&gaPageNumber=' . $pageNumber . '&gaGallery=' . $gallery . '&gaImageIndex=' . $imageIndex . '">';	

print $linkOpen;
}

function PrintImageLinkClose($gallery,$imageIndex)
{
$maxItems		=	GetNumberOfRows("SELECT * FROM $gallery");

if($imageIndex >= $maxItems)
	{
	print "<!-- No Link Close -->";
	return;
	}

// get the filename, this comes from the gallery table
$fileName		=	GetFieldByIndex("$gallery","FileName",$imageIndex);	

$linkClose		=	'</a>';	

print $linkClose;
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function PrintImageTag($gallery,$imageIndex)
// ---------------------------------------------------------------------------------------------------------------------------------------

function PrintImageTag($gallery,$imageIndex)
{
$maxItems		=	GetNumberOfRows("SELECT * FROM $gallery");
$debug			=	0;
global $gPathToGalleryThumbs;
global $gBaseMountPoint;
global $gGalleryMountPoint;

if($debug)	print "PrintImageFileName($gallery,$imageIndex)<br>";

// if out of range return ""
if($imageIndex >= $maxItems)
	{
	print "<!-- No Image -->";
	return;
	}

// get the filename, this comes from the gallery table
$fileName		=	GetFieldByIndex("$gallery","FileName",$imageIndex);	

if($debug)	print "fileName: $fileName<br>";

// get the artist ID from the table name
$artistID		=	GetArtistIdFromGallery($gallery);

if($debug)	print "artistID: $artistID<br>";

$galleryPath	=	"$gBaseMountPoint$gGalleryMountPoint/$artistID/200x/";

print '<img src="' . $galleryPath . $fileName . '" border="0">';
}


// ---------------------------------------------------------------------------------------------------------------------------------------
// function PrintImageTitle($gallery,$imageIndex)
// ---------------------------------------------------------------------------------------------------------------------------------------

function PrintImageTitle($gallery,$imageIndex)
{
$maxItems		=	GetNumberOfRows("SELECT * FROM $gallery");

if($imageIndex >= $maxItems)
	{
	print "<!-- No Title -->";
	return;
	}

// get the filename, this comes from the gallery table
$fileName		=	GetFieldByIndex("$gallery","FileName",$imageIndex);	

// get the title from the image library
$title			=	GetFieldByComparison("APPDEV_ImageLibrary","Title","FileName",$fileName);

if(strlen($title)==0)
	$title	=	"Untitled";

print stripslashes($title);
}


function PrintImageFilename($gallery,$imageIndex)
{
$maxItems		=	GetNumberOfRows("SELECT * FROM $gallery");

if($imageIndex >= $maxItems)
	{
	print "<!-- No Title -->";
	return;
	}

// get the filename, this comes from the gallery table
$fileName		=	GetFieldByIndex("$gallery","FileName",$imageIndex);	


print $fileName;
}


// ---------------------------------------------------------------------------------------------------------------------------------------
// function PrintPageNumberUI($gaPageNumber)
//
// Used in gallery thumbs to show the pages
// ---------------------------------------------------------------------------------------------------------------------------------------

function PrintPageNumberUI($table,$pageNumber,$thumbsPageName="gallery_thumbs.php")
{
$numberOfPages		=	GetNumberOfPagesInGallery($table,8);


// make it 1 based
for($i=1;$i<($numberOfPages+1);$i++)
	{
	$linkURL	=	"$thumbsPageName?gaPageNumber=$i&gaGallery=$table";
	
	if($i==$pageNumber)
		$linkClass	=	"galleryCurrentPageLink";
	else
		$linkClass	=	"galleryPageLink";
	$linkOpen		=	'<a href="' . $linkURL . '" class="' . $linkClass . '">';
	$linkClose		=	"</a>";		
	
	print "$linkOpen$i$linkClose&nbsp;";
	}

}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function PrintGalleryUI($table,$pageNumber)
//
// ---------------------------------------------------------------------------------------------------------------------------------------

function PrintGalleryUI($table,$pageNumber,$templateFileName="templates/gallery_t_thumbsUI.html",$thumbsPageName="gallery_thumbs.php")
{
print GetGalleryUI($table,$pageNumber,$templateFileName,$thumbsPageName);
}


function GetGalleryUI($table,$pageNumber,$templateFileName="templates/gallery_t_thumbsUI.html",$thumbsPageName="gallery_thumbs.php")
{
$previousEnabled	=	"gallery_UI_previous_en.jpg";	
$previousDisabled	=	"gallery_UI_previous_dis.jpg";	
$nextEnabled		=	"gallery_UI_next_en.jpg";	
$nextDisabled		=	"gallery_UI_next_dis.jpg";	
$numberOfPages		=	GetNumberOfPagesInGallery($table,8);
$nextPage			=	$pageNumber+1;
$previousPage		=	$pageNumber-1;

$debug				=	0;

global $gShowCartString;
global $gHomePage;
global $gContactPage;
global $gSiteName;

if($debug) print "numberOfPages: $numberOfPages<br>\n";

if($debug) print "GetGalleryUI($table,$pageNumber,$templateFileName,$thumbsPageName)<br>\n";

// if its page 1 previous is disabled
// set up the message for the alt tag
if($pageNumber==1)
	{
	$previousAlt	=	"This is the first page, there is no previous page...";
	$previous		=	$previousDisabled;
	$previousLink	=	0;
	}
else
	{
	$previousAlt	=	"Click here to view the previous page of thumbnails...";
	$previous		=	$previousEnabled;
	$previousLinkURL	=	$thumbsPageName . '?gaPageNumber=' . $previousPage . '&gaGallery=' . $table;
	$previousLink	=	1;
	}

// if its the last page next is disabled
// set up the message for the alt tag
if($pageNumber==$numberOfPages)
	{
	$nextAlt		=	"This is the last page, there is no next page...";
	$next			=	$nextDisabled;
	$nextLink		=	0;
	}
else
	{
	$nextAlt		=	"Click here to view the next page of thumbnails...";
	$next			=	$nextEnabled;
	$nextLinkURL	=	$thumbsPageName . '?gaPageNumber=' . $nextPage . '&gaGallery=' . $table;
	$nextLink		=	1;
	}
	
if($previousLink) 
	{
	$previousLinkOpen	=	'<a href="' . $previousLinkURL . '">';
	$previousLinkClose	=	"</a>";
	}
else
	{
	$previousLinkOpen	=	"";
	$previousLinkClose	=	"";
	}


if($nextLink) 
	{
	$nextLinkOpen	=	'<a href="' . $nextLinkURL . '">';
	$nextLinkClose	=	"</a>";
	}
else
	{
	$nextLinkOpen	=	"";
	$nextLinkClose	=	"";
	}

if($debug) print "nextLinkURL: $nextLinkURL<br>\n";
if($debug) print "previousLinkURL: $previousLinkURL<br>\n";
if($debug) print "pageNumber: $pageNumber<br>\n";
if($debug) print "nextPage: $nextPage<br>\n";
if($debug) print "previousPage: $previousPage<br>\n";

if($debug) print "previousLinkOpen: $previousLinkOpen<br>\n";
if($debug) print "PREV<br>\n";
if($debug) print "previousLinkOpen: $previousLinkOpen<br>\n";

if($debug) print "nextLinkOpen: $nextLinkOpen<br>\n";
if($debug) print "NEXT<br>\n";
if($debug) print "nextLinkClose: $nextLinkClose<br>\n";

// load the template file
$templateFile	=	FileRead($templateFileName);

$artistID		=	str_replace("APPDEV_GALLERY_","",$table);

// do the S&R
$templateFile	=	str_replace("_PREVIOUS_LINK_OPEN_",$previousLinkOpen,$templateFile);
$templateFile	=	str_replace("_PREVIOUS_LINK_CLOSE_",$previousLinkClose,$templateFile);
$templateFile	=	str_replace("_NEXT_LINK_OPEN_",$nextLinkOpen,$templateFile);
$templateFile	=	str_replace("_NEXT_LINK_CLOSE_",$nextLinkClose,$templateFile);
$templateFile	=	str_replace("_SITE_NAME_",$gSiteName,$templateFile);
$templateFile	=	str_replace("_HOME_PAGE_",$gHomePage,$templateFile);
$templateFile	=	str_replace("_CONTACT_PAGE_",$gContactPage,$templateFile);
$templateFile	=	str_replace("_SHOWCART_STRING_",$gShowCartString,$templateFile);
$templateFile	=	str_replace("_NEXT_FN_",$next,$templateFile);
$templateFile	=	str_replace("_PREVIOUS_FN_",$previous,$templateFile);
$templateFile	=	str_replace("_PREVIOUS_ALT_TEXT_",$previousAlt,$templateFile);
$templateFile	=	str_replace("_NEXT_ALT_TEXT_",$nextAlt,$templateFile);
$templateFile	=	str_replace("_ARTISTID_",$artistID,$templateFile);

return $templateFile;
}


// ---------------------------------------------------------------------------------------------------------------------------------------
// function GetNumberOfPagesInGallery($table,$imagesPerPage)
//
// ---------------------------------------------------------------------------------------------------------------------------------------

function GetNumberOfPagesInGallery($table,$imagesPerPage)
{
$query					=	"SELECT * FROM $table";
$numberOfImages			=	GetNumberOfRows($query);

$debug					=	0;

if($debug) print "<hr>GetNumberOfPagesInGallery($table)<br>";
if($debug) print "numberOfImages: " . $numberOfImages . "<br>";

// if there is a remainder add its remainder to the image count, I will fill out the count with a blank image...
$remainder				=	$numberOfImages % $imagesPerPage;

if($remainder)
	{
	$numberOfImages	+=	($imagesPerPage - $remainder);
	}

$numberOfPages			=	$numberOfImages / $imagesPerPage;

if($debug) print "numberOfImages: " . $numberOfImages . "<br>";
if($debug) print "numberOfPages: " . $numberOfPages . "<br>";
if($debug) print "remainder: " . $remainder . "<br><hr><br>";

return $numberOfPages;
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function PrintImageUI($fileName,$pageNumber,$imageIndex,$gallery,$imagesPerPage)
// ---------------------------------------------------------------------------------------------------------------------------------------

function PrintImageUI($fileName,$pageNumber,$imageIndex,$gallery,$imagesPerPage,$templateFileName="../templates/gallery_t_imageUI.html",$thumbsPageName="gallery_thumbs.php",$imagePageName="gallery_image.php")
{
print GetImageUI($fileName,$pageNumber,$imageIndex,$gallery,$imagesPerPage,$templateFileName,$thumbsPageName,$imagePageName);
}

function GetImageUI($fileName,$pageNumber,$imageIndex,$gallery,$imagesPerPage,$templateFileName="../templates/gallery_t_imageUI.html",$thumbsPageName="gallery_thumbs.php",$imagePageName="gallery_image.php")
{
$previousEnabled	=	"image_UI_previous_en.jpg";	
$previousDisabled	=	"image_UI_previous_dis.jpg";	
$nextEnabled		=	"image_UI_next_en.jpg";	
$nextDisabled		=	"image_UI_next_dis.jpg";
$numberOfPages		=	GetNumberOfPagesInGallery($gallery,8);
$numberOfImages		=	GetNumberOfRows("SELECT * FROM $gallery");

global $gShowCartString;
global $gHomePage;
global $gContactPage;

$debug				= 	0;

$imagePage			= 	"$imagePageName";
$ecardURL			=	"gallery_ecard_compose.php?gaCaller=$imagePageName&gaFileName=$fileName&gaPageNumber=$pageNumber&gaGallery=$gallery&gaImageIndex=$imageIndex";

if($debug) print "<hr>GetImageUI($fileName,$pageNumber,$imageIndex,$gallery,$imagesPerPage,$templateFileName)<br>";
if($debug)	print "ecardURL: $ecardURL<br>";
if($debug)	print "fileName: $fileName<br>";
if($debug)	print "pageNumber: $pageNumber<br>";
if($debug)	print "imageIndex: $imageIndex<br>";
if($debug)	print "gallery: $gallery<br>";
if($debug)	print "imagesPerPage: $imagesPerPage<br>";
if($debug)	print "numberOfPages: $numberOfPages<br>";
if($debug)	print "numberOfImages: $numberOfImages<br>";

// get the filename of the next image and set up the next link
$nextImageIndex					=	$imageIndex+1;			// zero based
$nextImageNumber				=	$nextImageIndex + 1;	// one based	
$nextFileName					=	GetFieldByIndex($gallery,"FileName",$nextImageIndex);

// get the previous image info if there is one
if($imageIndex > 0)
	{
	$previousImageIndex			=	$imageIndex-1;			// zero based
	$previousImageNumber		=	$previousImageIndex + 1;	// one based	
	$previousFileName			=	GetFieldByIndex($gallery,"FileName",$previousImageIndex);
	$previousLink				=	true;
	
	$foo	=	$previousImageNumber % $imagesPerPage;	// for some reason it doesnt like this in the if, even in parens, always true
	
	if($debug)	print "foo: $foo<br>";
	
	if($foo==0)
		{
		if($debug)	print "Dont Add one...<br>";
		$previousPageNumber		=	(int)($previousImageNumber/$imagesPerPage);	
		}
	else
		{
		if($debug)	print "Add one...<br>";
		$previousPageNumber		=	(int)($previousImageNumber/$imagesPerPage) + 1;	
		}

	$previousLinkURL	= "$imagePageName?gaFileName=$previousFileName&gaImageIndex=$previousImageIndex&gaPageNumber=$previousPageNumber&gaGallery=$gallery";	
	}
else
	{
	$previousLink		=	false;
	$previousLinkURL	= "#";	
	}

// set up the link to the previous image

if($debug)	print "previousImageIndex: $previousImageIndex<br>";
if($debug)	print "previousImageNumber: $previousImageNumber<br>";
if($debug)	print "previousFileName: $previousFileName<br>";
if($debug)	print "previousLinkURL: $previousLinkURL<br>";


if($previousLink) 
	{
	$previousLinkOpen	=	'<a href="' . $previousLinkURL . '">';
	$previousLinkClose	=	"</a>";
	}
else
	{
	$previousLinkOpen	=	"";
	$previousLinkClose	=	"";
	}

// calculate the next page number
// if the divide comes out even all is good else the fraction remainder is truncated by the cast
// and it is necessary to add 1 to get the correct result

$foo	=	$nextImageNumber % $imagesPerPage;	// for some reason it doesnt like this in the if, even in parens, always true

if($debug)	print "foo: $foo<br>";

if($foo==0)
	{
	if($debug)	print "Dont Add one...<br>";
	$nextPageNumber		=	(int)($nextImageNumber/$imagesPerPage);	
	}
else
	{
	if($debug)	print "Add one...<br>";
	$nextPageNumber		=	(int)($nextImageNumber/$imagesPerPage) + 1;	
	}


if($debug)	print "nextImageNumber: $nextImageNumber<br>";
if($debug)	print "nextImageIndex: $nextImageIndex<br>";
if($debug)	print "nextFileName: $nextFileName<br>";
if($debug)	print "nextPageNumber: $nextPageNumber<br>";
if($debug)	print "imagesPerPage: $imagesPerPage<br>";

// set up the thumbs link
$thumbsLinkURL	=	"$thumbsPageName?gaPageNumber=$pageNumber&gaGallery=$gallery";

if($debug)	print "thumbsLinkURL: $thumbsLinkURL<br>";

// set up the link to the next image
if($imageIndex >= $numberOfImages)
	$nextLinkURL	= "#";	
else
	$nextLinkURL	= "$imagePageName?gaFileName=$nextFileName&gaImageIndex=$nextImageIndex&gaPageNumber=$nextPageNumber&gaGallery=$gallery";	

if($debug)	print "nextLinkURL: $nextLinkURL<br>";


if($debug)	print "previousLinkURL: $previousLinkURL<br>";

// if its page 1 previous is disabled
// set up the message for the alt tag
if($imageIndex==0)
	{
	$previousAlt	=	"This is the first image, there is no previous image...";
	$previous		=	$previousDisabled;
	}
else
	{
	$previousAlt	=	"Click here to view the previous image in this gallery...";
	$previous		=	$previousEnabled;
	}

if($debug)	print "previous: $previous<br>";
if($debug)	print "previousAlt: $previousAlt<br>";

// if its the last page next is disabled
// set up the message for the alt tag
// if($imageIndex==$numberOfImages) BUG FIXED 081106

if($imageIndex==($numberOfImages-1))
	{
	$nextAlt	=	"This is the last image, there is no next image...";
	$nextLink	=	false;
	$next		=	$nextDisabled;
	}
else
	{
	$nextAlt	=	"Click here to view the next image in this gallery...";
	$nextLink	=	true;
	$next		=	$nextEnabled;
	}

// fix up the link open and close vals
if($nextLink) 
	{
	$nextLinkOpen	=	'<a href="' . $nextLinkURL . '">';
	$nextLinkClose	=	"</a>";
	}
else
	{
	$nextLinkOpen	=	"";
	$nextLinkClose	=	"";
	}


if($debug)	print "next: $next<br>";
if($debug)	print "nextAlt: $nextAlt<br>";

// load the template file
$templateFile	=	FileRead($templateFileName);

// do the S&R
$templateFile	=	str_replace("_PREVIOUS_LINK_OPEN_",$previousLinkOpen,$templateFile);
$templateFile	=	str_replace("_PREVIOUS_LINK_CLOSE_",$previousLinkClose,$templateFile);
$templateFile	=	str_replace("_NEXT_LINK_OPEN_",$nextLinkOpen,$templateFile);
$templateFile	=	str_replace("_NEXT_LINK_CLOSE_",$nextLinkClose,$templateFile);
$templateFile	=	str_replace("_SITE_NAME_",$gSiteName,$templateFile);
$templateFile	=	str_replace("_HOME_PAGE_",$gHomePage,$templateFile);
$templateFile	=	str_replace("_CONTACT_PAGE_",$gContactPage,$templateFile);
$templateFile	=	str_replace("_SHOWCART_STRING_",$gShowCartString,$templateFile);
$templateFile	=	str_replace("_NEXT_FN_",$next,$templateFile);
$templateFile	=	str_replace("_PREVIOUS_FN_",$previous,$templateFile);
$templateFile	=	str_replace("_PREVIOUS_ALT_TEXT_",$previousAlt,$templateFile);
$templateFile	=	str_replace("_NEXT_ALT_TEXT_",$nextAlt,$templateFile);
$templateFile	=	str_replace("_E_CARD_URL_",$ecardURL,$templateFile);
$templateFile	=	str_replace("_THUMBS_LINK_URL_",$thumbsLinkURL,$templateFile);
$templateFile	=	str_replace("_FILENAME_",$fileName,$templateFile);

return $templateFile;
}


// new in 2.2.0
function SetGalleryTitle($artistID,$galleryTitle)
{
if($debug) print "<hr><br>SetGalleryTitle($artistID,$galleryTitle)<br>";

$format			=	"
					UPDATE APPDEV_GalleryProfiles 
					SET Title='%s'
					WHERE
					ArtistID='%s'
					";

if($debug) print "format: $format<br>";

OpenDatabase();

$query 		= 	sprintf($format,
						mysql_real_escape_string($galleryTitle),
						mysql_real_escape_string($artistID)
						);

if($debug) print "query: $query<br>";

$rv	=	QueryDatabase($query);

if($debug) print "rv: $rv<br><hr>";

CloseDatabase();

return $rv;
}



?>
