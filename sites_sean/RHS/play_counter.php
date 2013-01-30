<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include '../local_info.php';
include 'db_lib.php';
include 'cl_lib.php';
include 'file_lib.php';
include 'html_lib.php';
include 'blog_parms.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'blog_ads.php';
include 'status_message.php';
include 'seo_lib.php';	
include 'ui_lib.php';	
include 'counter_lib.php';	
include 'redirect.php';	
include 'download_lib.php';	
include 'name_value_pair_lib.php';	

$songFileURI	=	"$gSiteURL$dlPath/$dlFileName";
//$trimmedURI		=	substr($songFileURI,1);
$debug			=	0;
$noRobots		=	true;
$method			=	"GET";

if($debug) print "songFileURI: $songFileURI<br>";
if($debug) print "dlTitle: $dlTitle<br>";
if($debug) print "dlPath: $dlPath<br>";
if($debug) print "dlFileName: $dlFileName<br>";

// mark up the database for counters
UpdateDownloadStats($dlFileName,$dlTitle,$dlPath);

FormRedirect($songFileURI,$method,$debug,$noRobots);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Playing <?php print $dlTitle; ?></title>
<SCRIPT LANGUAGE="JavaScript"> 
<!--
window.resizeTo(400,150);
-->
</SCRIPT>

</head>

<body>
</body>
</html>
