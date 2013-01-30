<?php
// ---------------------------------------------------------------------------------------------------------------
// seo_lib.php
//
// This contains the SEO code functionality in a API framework.
//
// (c) 2007 Cooksey-Talbott Studio
//
// Depends: ../local_info.php
//			db_lib.php
//
// a good place to check the results
// http://www.widexl.com/remote/search-engines/metatag-analyzer.html
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------


function PrintPageTitle($title)
{

global $gSiteName;
global $gKeyWords;

print "<title>$gSiteName - $title - $gKeyWords</title>";
}

// ---------------------------------------------------------------------------------------
// function PrintAltTagKeywords()
// function GetAltTagKeywords()
//
// This pair of functions allow the filling up of the unused alt tags in image intensive
// sites with keywords.
// ---------------------------------------------------------------------------------------

function GetAltTagKeywords($index)
{
// must be seperated by ', '
global $gAltTagKeywords;

$keywords	=	$gAltTagKeywords;		

$keyWordArray		=	explode(", ",$keywords);

$numberOfKeywords	=	sizeof($keyWordArray);

if($index >= $numberOfKeywords)
	$index 			= 	0;

return $keyWordArray[$index] . " ";
}


function PrintAltTagKeywords($index)
{
if(isset($index)==false)
	$index	=	0;

print GetAltTagKeywords($index);
}


// --------------------------------------------------------------------------------------------------------------------------------------
// function PrintMetaTags()
//
// Will print either the dev tags or the regular tags based on the value of the global gHideFromRobots which is
// define4d in ../local_info.php
// --------------------------------------------------------------------------------------------------------------------------------------

function PrintMetaTags($description,$additionalKeywords)
{
global $gHideFromRobots;

if($gHideFromRobots)
	{
	PrintDevelopmentTags();
	}
	else
	{
	PrintLiveTags($description,$additionalKeywords);
	}
}


function PrintLiveTags($description,$additionalKeywords)
{
global $gDescription;
global $gKeyWords;
global $gSiteName;
global $gTopic;

if($description == "")
	$description = $gDescription;


print '
<!-- Live Meta tag section  -->

<!-- Robots section  -->
<meta name="Robots" content="All">
<META NAME="ROBOTS" CONTENT="INDEX,FOLLOW">
<META NAME="GOOGLEBOT" CONTENT="ARCHIVE">
<META NAME="GOOGLEBOT" CONTENT="follow">
<META NAME="GOOGLEBOT" CONTENT="index">
<META NAME="Revisit-after" CONTENT="5 days">

<!-- Content tags section  -->

<meta name="description" content="' . $description . '">
<META NAME="KEYWORDS" content="' . "$additionalKeywords$gKeyWords" . '">
<!-- Misc tags section  -->
<META NAME="author" CONTENT="' . $gSiteName . '">
<META NAME="rating" CONTENT="General">
<META NAME="audience" CONTENT="all">
<META HTTP-EQUIV="Content-Language" CONTENT="en-us">
<META NAME="resource-type" CONTENT="document">
<META NAME="clientbase" CONTENT="Global">
<META NAME="distribution" CONTENT="World Wide Web">
<META NAME="location" CONTENT="W.W.W.">
<META NAME="page-topic" CONTENT="' . $gTopic . '">
<meta name="copyright" content="' . $gSiteName . '">
<META NAME="page-type" CONTENT="document">
<META http-equiv=Content-Type content="text/html; charset=windows-1252">
<META NAME="Revisit-after" CONTENT="5 days">
';

print '		
		<!-- Link to shortcut icon -->
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	';
}


function PrintDevelopmentTags()
{
print '
		<!-- Hide from the robots while in development -->		
		<meta name="robots" content="noindex,nofollow">
		<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
		<META NAME="GOOGLEBOT" CONTENT="nofollow">
		<META NAME="GOOGLEBOT" CONTENT="noindex">
		<!-- End Hide from the robots while in development -->
		';

print '		
		<!-- Link to shortcut icon -->
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	';
}

//--------------------------------------------------------------------------------------
// function PrintSEODescription()
//
// if you put in multiple blocks of SEO text then it will give you a random selection
// else it will give you row 0
//--------------------------------------------------------------------------------------

function PrintSEODescription()
{
global $gSEOFooterFileName;

//print	FileRead($gSEOFooterFileName);
// in 2.2.0 on the SEO template file has been deprecated for a SEO Text blog

OpenDatabase();

$rs	=	QueryDatabase("SELECT * FROM APPDEV_BLOG_SEO_Text");

$numberOfRows	=	@mysql_numrows($rs);

// if you put in multiple blocks of SEO text then it will give you a random selection
if($numberOfRows>1)
	{
	$query	=	"SELECT * FROM APPDEV_BLOG_SEO_Text ORDER BY RAND()";	
	
	$rs	=	QueryDatabase($query);
	}

CloseDatabase();

$text	=	GetDatabaseResult($rs,0,"blCopy");

$text	=	stripslashes($text);

print $text;
}
?>