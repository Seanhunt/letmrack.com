<?php
/*
include "../local_info.php";
include "db_lib.php";
include "cl_lib.php";
include 'gallery_parms.php';	
include 'file_lib.php';	
include 'html_lib.php';	
include 'gallery_lib.php';	
include 'pw_parms.php';	
include 'pw_lib.php';
include 'select_controls.php';	
include 'status_message.php';	
include "admin_lib.php";
//include "chron_lib.php";
*/

$test	=	"test.php?caption=I%20am%20a%20goat%20or%20a%20god%20?%20Or is it WORSE???";

$pos	=	strpos($test,"?") + 1;
$len	=	strlen($test);
$uri	=	substr($test,$pos,$len);

print "pos: $pos<br>";
print "len: $len<br>";
print "uri: $uri<br>";


/*
$fileName	=	"FooBeR";
$width		=	60;
$height		=	12;
$artistID	=	"gOOber";

$rv	=	AddDetailImage($fileName,$width,$height,$artistID);
*/

// BlogMaintainCalendar(2);
//ChronProcessHasRun("CALENDAR");
//ChronUpdateProcess("CALENDAR");

//print_r($_SERVER); print '<br>';

//print GetCurrentPageFileName();

//print "This gallery is: " . GalleryIsVisible("testpodunk667");

/*
$templateFileName="gallery_UI_template.html";

//PrintGalleryUI("APPDEV_GALLERY_cookseytalbott",1,$templateFileName);
//print "<hr>";
//PrintGalleryUI("APPDEV_GALLERY_cookseytalbott",2,$templateFileName);
//print "<hr>";
$templateFileName	=	"image_UI_en.html";
print GetImageUI("177-MonoLake.jpg",2,8,"APPDEV_GALLERY_cookseytalbott",8,$templateFileName);
*/

?>