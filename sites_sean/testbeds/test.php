<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// test.php
//
//
//
// Author: 	Ralph Cooksey-Talbott
// Contact: cooksey@cookseytalbottstudio.com
// (c) 2007 Cooksey-Talbott Studio, All Rights Reserved. 
// ---------------------------------------------------------------------------------------------------------------------------------------

include 'local_info.php';	
include 'RHS/db_lib.php';
include 'RHS/cl_lib.php';
include 'RHS/admin_lib.php';
include 'RHS/question_lib.php';
include 'RHS/file_lib.php';
include 'RHS/blog_lib.php';
include 'RHS/html_lib.php';
include 'RHS/pw_parms.php';	
include 'RHS/pw_lib.php';
include 'RHS/status_message.php';	
include 'RHS/select_controls.php';	
include 'RHS/download_lib.php';	
include 'RHS/public_vote_lib.php';
include 'RHS/gallery_lib.php';

$detailTemplateFileName	=	"templates/detail_image_template.html";
$fileName				=	"031-BlueMountainLake.jpg";
$artistID				=	"cookseytalbott";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Generic Testbed</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>

<hr>
<?php
// $gGalleryDetailPictures
DisplayDetailImages($fileName, $artistID, $detailTemplateFileName);
?>

<p>&nbsp;</p>



<hr>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>