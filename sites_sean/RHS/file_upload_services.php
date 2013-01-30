<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// file_uploader.php
//
// This will upload and send delivery memos
//
// Depends:	cl_lib
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

include "../local_info.php";
include 'cl_lib.php';
include "mail_lib.php";
include "file_lib.php";
include 'db_lib.php';
include 'pw_lib.php';
include 'numbers_lib.php';
include 'html_lib.php';	
include "name_value_pair_lib.php";
include "redirect.php";

$debug				=	0;
$error				=	0;

// gen up the ID
$guid				= GenerateUniqueID("CTS");

// get parms
$toAddress			=	GetPassedValue("ToAddress");
$message			=	GetPassedValue("Message");
$subject			=	GetPassedValue("Subject");
$systemPassword		=	GetPassedValue("SystemPassword");
$systemUserID		=	GetPassedValue("SystemUserID");
$fileName			=	basename($_FILES['FileToSend']['name']); // get the filename lose the path	
$fileToSend			=	$_FILES['FileToSend']['tmp_name'];
$targetDir			= 	$gFTPDeliveryMountPoint;
$uploadFileAndPath 	= 	$gServerBaseMountPoint .  "/" . $targetDir . "/" . $fileName;
$fileLocation		=	$gSiteURL  . $gBaseMountPoint .  "/" . $targetDir . "/"  . $fileName;	
// New using GUID
$deliveryLink		= 	$gSiteURL . $gBaseMountPoint . "/file_delivery.php?GUID=" . $guid; 
$deliveryLinkText	=	$fileName;

// fix the retirns in the message to be br's
$message 		= 	implode("<br>", preg_split("/\r?\n/", $message));

// load the message template
if(($deliveryMemo = FileRead("file_upload_delivery_memo.html"))=="")
	{
	// should never happen
	$error	=	1;
	$errorMessage	=	"Failed to load message template";
	}

if($debug) print "guid: $guid<br>";
if($debug) print "fileLocation: $fileLocation<br>";
if($debug) print "toAddress: $toAddress<br>";
if($debug) print "message: $message<br>";
if($debug) print "subject: $subject<br>";
if($debug) print "fileName: $fileName<br>";
if($debug) print "targetDir: $targetDir<br>";
if($debug) print "uploadFileAndPath: $uploadFileAndPath<br>";
if($debug) print "fileToSend: $fileToSend<br>";
if($debug) print "deliveryLink: $deliveryLink<br>";


//if($debug) print "DEBUG-EXIT<br>";
//exit(0);


// At this point the file has been uploaded and is in a tmp file on the server
// move it to the target location
if(move_uploaded_file($_FILES['FileToSend']['tmp_name'], $uploadFileAndPath) && !$error) 
	{
   	// now fix up the rights...
	$rv = chmod($uploadFileAndPath, 0755); 	// Read and write for owner, read for everybody else

	if($debug) print "chmod on $uploadFileAndPath returned: $rv<br>\n";
	
	if($debug) print "File Successfully Uploaded<br>";
	} 
else 
	{
   	if($debug) print "Error<br>";
	$error	=	1;
	$errorMessage	=	"Failed to write uploaded file!";
	}

// log the location of the file
OpenDatabase();
$query	=	"INSERT INTO APPDEV_FileLocations VALUES ('$guid','$fileLocation','')";

$rv	=	QueryDatabase($query);

if(!$rv)
	{
	if($debug) print "Error<br>";
	$error	=	1;
	$errorMessage	=	"Failed to insert location and GUID!";
	}

CloseDatabase();

// fix up the message which has these tags
// MESSAGE_TEXT, SITE_NAME, DELIVERY_PAGE_LINK
if(!$error)
	{
	$deliveryMemo	=	str_replace("MESSAGE_TEXT",$message,$deliveryMemo);
	$deliveryMemo	=	str_replace("SITE_NAME",$gSiteName,$deliveryMemo);
	$deliveryMemo	=	str_replace("DELIVERY_PAGE_LINK_TEXT",$deliveryLinkText,$deliveryMemo);
	$deliveryMemo	=	str_replace("DELIVERY_PAGE_LINK",$deliveryLink,$deliveryMemo);
	
	if($debug) print "deliveryMemo: $deliveryMemo<br>";
	
	// send delivery memo mail to reciepient and site secretary
	//$toAddress	.= ", $gSiteSecretaryAddress";

	email_send($toAddress,$gSiteSecretaryAddress,$gSiteName,$subject,$deliveryMemo);
	
	// send delivery memo mail to site secretary
	email_send($gSiteSecretaryAddress,$gSiteSecretaryAddress,$gSiteName,$subject,$deliveryMemo);
	}
	
// check the error and report
if($_FILE["error"]==0 && $error==0)
	$errorMessage	=	"The file has been uploaded and the delivery memo sent...";

if($debug) print '_FILE array values:<br>';
if($debug) print_r($_FILES);

$targetURL	=	"file_upload.php?StatusMessage=$errorMessage&SystemPassword=$systemPassword&SystemUserID=$systemUserID";

// pump it back to the upload page
FormRedirect($targetURL,"POST",$debug);
?>
