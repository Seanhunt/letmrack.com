<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// admin_lib.php
//
// depends: appdev_revision.php
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
// function PrintUserMessage($userID)
// ---------------------------------------------------------------------------------------------------------------------------------------

function PrintUserMessage($userID)
{
print "<span class=admin12Regular>&nbsp;&nbsp;Hello $userID</span>";
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// function PrintBlogFooter()
// ---------------------------------------------------------------------------------------------------------------------------------------

function PrintBlogFooter($addlPath="")
{
PrintAdminFooter($addlPath);
}

// ---------------------------------------------------------------------------------------------------------------------------------------
// Gallery database interface
// ---------------------------------------------------------------------------------------------------------------------------------------

function PrintGalleryFooter($addlPath="")
{
PrintAdminFooter($addlPath);
}


// ---------------------------------------------------------------------------------------------------------------------------------------
// function PrintAdminFooter()
// ---------------------------------------------------------------------------------------------------------------------------------------

function PrintAdminFooter($addlPath="")
{
global $gRevisionNumber;

$debug	=	0;

$templateFileName			=	"admin_footer_template.html";
$templateFileNameAndPath	=	"$addlPath$templateFileName";

if($debug) print "PrintAdminFooter($addlPath)<br>";
if($debug) print "templateFileName: $templateFileName<br>";
if($debug) print "templateFileNameAndPath: $templateFileNameAndPath<br>";

// load the template file
$footerTemplate	=	FileRead("$templateFileNameAndPath");

if($footerTemplate=="")
	{
	if($debug) print "Unable to load admin footer template file...<br>";
	}
else
	if($debug) print "<hr><br>$footerTemplate<br><hr>";

// fix it up
$footerTemplate	=	str_replace("__ADDITIONAL_IMAGE_PATH__",$addlPath,$footerTemplate);
$footerTemplate	=	str_replace("__REVISION_NUMBER__",$gRevisionNumber,$footerTemplate);

// cya
print $footerTemplate;
}


?>
