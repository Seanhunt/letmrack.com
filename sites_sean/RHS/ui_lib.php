<?php
// ---------------------------------------------------------------------------------------------------------------
// ui_lib.php
//
// This contains site specific banner UI and footer functionality
//
// (c) 2007 Cooksey-Talbott Studio
//
// Depends: ../local_info.php
//			file_lib
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------


function PrintBannerUI()
{
global $gBannerUIFileName;

print	FileRead($gBannerUIFileName);
}

function PrintFooterUI($additionalPath="")
{
global $gFooterFileName;

if($additionalPath != "")
	{
	$fn	=	"$additionalPath$gFooterFileName";
	}
else
	$fn	=	"$gFooterFileName";


print	FileRead($fn);
}



?>
