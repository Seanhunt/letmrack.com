<?php
// ----------------------------------------------------------------------------------------------------------------------
// image_lib.php
//
// Contains common image gestalt functions.
// 
// Depends: 	none
//
// Revision:	081406	File Originated
//						Added
// 						function IsHorizontal($file)
// 						function IsVertical($file)
// 						function IsSquare($file)
// 						function ImageInfo($file = null, $out = null) 
//
// Thanks: 	egingell@sisna.com for the well done info routine
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ----------------------------------------------------------------------------------------------------------------------

// These constants are used by ImageInfo(), below.
define ('IMAGE_WIDTH', 'width');
define ('IMAGE_HEIGHT', 'height');
define ('IMAGE_TYPE', 'type');
define ('IMAGE_ATTR', 'attr');
define ('IMAGE_BITS', 'bits');
define ('IMAGE_CHANNELS', 'channels');
define ('IMAGE_MIME', 'mime');


// ----------------------------------------------------------------------------------------------------------------------
// testbed
// ----------------------------------------------------------------------------------------------------------------------
/*
include "../local_info.php";

$fn	=	"../../Images/Library/300xLF/1153-Hills-0406-252-254.jpg";
//$fn	=	"images/gallery/Gallery_UI_01.gif";

$info	=	ImageInfo($fn);

print "fn = " . $fn . "<br>";
print "width = " . $info["width"] . "<br>";
print "height = " . $info["height"] . "<br>";

if(IsVertical($fn)==true)
	print "Vertical<br>";
else
	print "Horizontal<br>";

//$file	=	"../../Testing/AppDev/Images/Galleries/cookseytalbott/500x/031-BlueMountainLake.jpg";
$file	=	"$gServerBaseMountPoint/Testing/AppDev/Images/Galleries/cookseytalbott/500x/031-BlueMountainLake.jpg";
//$file	=	"$gPathToGalleryImages/$artistID/500x/$gaFileName";	
$info	=	ImageInfo($file);

print "file = " . $file . "<br>";

print 	"height" . $info["height"] . "<br>";
print "width" . 	$info["width"] . "<br>";
*/

// ----------------------------------------------------------------------------------------------------------------------
// function GetWidth($file)
// ----------------------------------------------------------------------------------------------------------------------

function GetWidth($file)
{
$info	=	ImageInfo($file);

return $info["width"];
}

// ----------------------------------------------------------------------------------------------------------------------
// function GetWidth($file)
// ----------------------------------------------------------------------------------------------------------------------

function GetHeight($file)
{
$info	=	ImageInfo($file);

return $info["height"];
}


// ----------------------------------------------------------------------------------------------------------------------
// function IsSquare($file)
// ----------------------------------------------------------------------------------------------------------------------

function IsSquare($file)
{
$info	=	ImageInfo($file);

$h		=	$info["height"];
$w		=	$info["width"];

if($w == $h)
	return true;

return false;
}

// ----------------------------------------------------------------------------------------------------------------------
// function IsHorizontal($file)
// ----------------------------------------------------------------------------------------------------------------------

function IsHorizontal($file)
{
$info	=	ImageInfo($file);

$h		=	$info["height"];
$w		=	$info["width"];

if($w > $h)
	return true;

return false;
}
// function IsHorizontal($file)
// function IsVertical($file)

// ----------------------------------------------------------------------------------------------------------------------
// function IsVertical($file)
// ----------------------------------------------------------------------------------------------------------------------

function IsVertical($file)
{
if(IsHorizontal($file)==false)
	return true;

return false;
}



// ----------------------------------------------------------------------------------------------------------------------
// mixed ImageInfo( file $file [, string $out] )
//
// Returns information about $file.
//
// If the second argument is supplied, a string representing that information will be returned.
//
// Valid values for the second argument are IMAGE_WIDTH, 'width', IMAGE_HEIGHT, 'height', IMAGE_TYPE, 'type',
// IMAGE_ATTR, 'attr', IMAGE_BITS, 'bits', IMAGE_CHANNELS, 'channels', IMAGE_MIME, and 'mime'.
//
// If only the first argument is supplied an array containing all the information is returned,
// which will look like the following:
//
//    [width] => int (width),
//    [height] => int (height),
//    [type] => string (type),
//    [attr] => string (attributes formatted for IMG tags),
//    [bits] => int (bits),
//    [channels] => int (channels),
//    [mime] => string (mime-type)
//
// Returns false if $file is not a file, no arguments are supplied, $file is not an image, or otherwise fails.
//
// ----------------------------------------------------------------------------------------------------------------------

function ImageInfo($file = null, $out = null) 
{
$debug	=	0;

if($debug) print "ImageInfo($file, $out)<br>";

   // If $file is not supplied or is not a file, warn the user and return false.
   if (is_null($file) || !is_file($file)) 
   		{
       	echo '<p><b>Warning:</b> ImageInfo() => first argument must be a file.</p>';
       	return false;
   		}

   // Defines the keys we want instead of 0, 1, 2, 3, 'bits', 'channels', and 'mime'.
   $redefine_keys = array(
       'width',
       'height',
       'type',
       'attr',
       'bits',
       'channels',
       'mime',
   );

   // If $out is supplied, but is not a valid key, nullify it.
   if (!is_null($out) && !in_array($out, $redefine_keys)) $out = null;

   // Assign usefull values for the third index.
   $types = array(
       1 => 'GIF',
       2 => 'JPG',
       3 => 'PNG',
       4 => 'SWF',
       5 => 'PSD',
       6 => 'BMP',
       7 => 'TIFF(intel byte order)',
       8 => 'TIFF(motorola byte order)',
       9 => 'JPC',
       10 => 'JP2',
       11 => 'JPX',
       12 => 'JB2',
       13 => 'SWC',
       14 => 'IFF',
       15 => 'WBMP',
       16 => 'XBM'
   );
   $temp = array();
   $data = array();

   // Get the image info using getimagesize().
   // If $temp fails to populate, warn the user and return false.
   if (!$temp = getimagesize($file)) 
   		{
       	echo '<p><b>Warning:</b> ImageInfo() => first argument must be an image.</p>';
      	return false;
   		}

   // Get the values returned by getimagesize()
   $temp = array_values($temp);

   // Make an array using values from $redefine_keys as keys and values from $temp as values.
   foreach ($temp AS $k => $v) 
   		{
       	$data[$redefine_keys[$k]] = $v;
   		}

   // Make 'type' useful.
   $data['type'] = $types[$data['type']];

   // Return the desired information.
   return !is_null($out) ? $data[$out] : $data;   
}



?>
