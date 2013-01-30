<?php

// - --------------------------------------------------------------------------------------------------
// file_lib.php
//
// File services
//
// Revision: 	020806 First Revision
//				042607 added FileGetExtension($filename)
//
// Depends:		../local_info.php
//				db_lib.php
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// - --------------------------------------------------------------------------------------------------------------------------------------

function FileGetExtension($filename)
{
$path_info = pathinfo($filename);
return $path_info['extension'];
}


// take the nastyshite out of a filename
function CleanFileName($dirtyFileName)
{
$cleanFileName	=	"";

// space
$cleanFileName	=	str_replace(" ","-",$dirtyFileName);
$cleanFileName	=	str_replace("/","",$cleanFileName);
$cleanFileName	=	str_replace("!","",$cleanFileName);
$cleanFileName	=	str_replace("#","",$cleanFileName);
$cleanFileName	=	str_replace("$","",$cleanFileName);
$cleanFileName	=	str_replace("%","",$cleanFileName);
$cleanFileName	=	str_replace("^","",$cleanFileName);
$cleanFileName	=	str_replace("&","",$cleanFileName);
$cleanFileName	=	str_replace("*","",$cleanFileName);
$cleanFileName	=	str_replace("(","",$cleanFileName);
$cleanFileName	=	str_replace(")","",$cleanFileName);
$cleanFileName	=	str_replace("~","",$cleanFileName);
$cleanFileName	=	str_replace("`","",$cleanFileName);
$cleanFileName	=	str_replace("+","",$cleanFileName);
$cleanFileName	=	str_replace("=","",$cleanFileName);
$cleanFileName	=	str_replace('"',"",$cleanFileName);
$cleanFileName	=	str_replace("'","",$cleanFileName);

return $cleanFileName;
}


// move_uploaded_file() messages
function FileMoveError($message,$errorNumber)
{
if($errorNumber==1)
	{
	$errorMessage	=	" Error: $errorNumber - The uploaded file exceeds the upload_max_filesize directive";
	}
else if($errorNumber==2)
	{
	$errorMessage	=	" Error: $errorNumber - The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
	}
else if($errorNumber==3)
	{
	$errorMessage	=	" Error: $errorNumber - The uploaded file was only partially uploaded";
	}
else if($errorNumber==4)
	{
	$errorMessage	=	" Error: $errorNumber - No file was uploaded";
	}
else if($errorNumber==6)
	{
	$errorMessage	=	" Error: $errorNumber - Missing a temporary folder";
	}
else if($errorNumber==7)
	{
	$errorMessage	=	" Error: $errorNumber - Failed to write file to disk";
	}
else if($errorNumber==8)
	{
	$errorMessage	=	" Error: $errorNumber - File upload stopped by extension";
	}
else
	{
	$errorMessage	=	" Error: $errorNumber - Unknown error";
	}	

return $message . $errorMessage;
}



// - --------------------------------------------------------------------------------------------------------------------------------------
// function FileRead($fileName)
//
// Reads in the givin file and returns string
//
// Returns The string or FALSE on fail
// - --------------------------------------------------------------------------------------------------------------------------------------

function FileRead($fileName)
{
if(($fp=fopen($fileName, 'r'))==FALSE)
	return FALSE;
	
$theFile 	= 	fread($fp,filesize($fileName));

fclose($fp);

return $theFile;
}

?>
