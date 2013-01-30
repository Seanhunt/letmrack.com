<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

//---------------------------------------------------------------------
// This is the event handler for the mail list console program
//---------------------------------------------------------------------

include '../local_info.php';
include 'db_lib.php';
include 'cl_lib.php';
include 'html_lib.php';	
include "name_value_pair_lib.php";
include 'mail_lib.php';
include 'pw_parms.php';
include 'mail_sub_parms.php';
include 'redirect.php';
include 'numbers_lib.php';

$debug	=	0;

$action1		=	GetPassedValue("Submit1");
$action2		=	GetPassedValue("Submit2");
$action3		=	GetPassedValue("Submit3");

if($debug)	print "action1: $action1<br><br>";
if($debug)	print "action2: $action2<br><br>";
if($debug)	print "action3: $action3<br><br>";

//---------------------------------------------------------------------
// if there is no ID fix it up...
//---------------------------------------------------------------------

if($mailingID=="")
	$mailingID	=	GenerateUniqueID("MAIL_ID_");

if($debug)	print "mailingID: $mailingID<br><br>";

//---------------------------------------------------------------------
// if there is an upload file get it and reset the MessageFileName
//---------------------------------------------------------------------

$fileName		=	basename($_FILES['MessageFileUpload']['name']); // get the filename lose the path	

if($debug) print "fileName: $fileName<br>\n";

if($fileName != "")
	{
	$tempFile		=	$_FILES['MessageFileUpload']['tmp_name'];
	// this is the target
	$fullPath	 	= 	$gMailLocalPath . "/" . $fileName; // use the clean file name

	if($debug) print "fileName: $fileName<br>\n";
	if($debug) print "tempFile: $tempFile<br>\n";
	if($debug) print "fullPath: $fullPath<br>\n";
	
	// move the 500x file to the target location

	$moveResult	=	move_uploaded_file($_FILES['MessageFileUpload']['tmp_name'], $fullPath);

	if($debug) print "moveResult: $moveResult<br>\n";

	if($moveResult==true) 
		{
		$rv = chmod($fullPath, 0755); 	// Read and write for owner, read for everybody else

		if($debug) print "chmod returned: $rv<br>\n";

		$statusMessage	=	"File uploaded";

		if($debug) print "File Successfully Uploaded<br>\n";
		} 
	else 
		{
		$errorNumber	=	$_FILES['MessageFileUpload']["error"];	

		$error			=	1;

		if($debug) print "Error: " . $errorNumber . "<br>\n";
		
		// deduce the error and make a message
		$statusMessage	=	FileMoveError("File Upload",$errorNumber);	
		}

	if(!$error)
		{
		$messageFileName	=	"..$gMailSharedMountPoint/$fileName";

		if($debug) print "messageFileName: $messageFileName<br>\n";
		}		
	}
//---------------------------------------------------------------------
// do the right thing..
//---------------------------------------------------------------------

if($debug) print "statusMessage: $statusMessage<br>";

if($action1=="Preview")
	{
	$targetURL	=	"mail_sub_preview.php?NvpFileName=$nvpFileName&MailingID=$mailingID&WriteEnabled=$writeEnabled&NumberToSend=$numberToSend&StartRowIndex=$startRowIndex&Sub1=$sub1&Sub2=$sub2&Sub3=$sub3&MessageFileName=$messageFileName&Subject=$subject&MailTableName=$mailTableName&SystemPassword=$systemPassword&SystemUserID=$systemUserID";
	}

if($action2=="Send")
	{
	$targetURL	=	"mail_sub_send.php?NvpFileName=$nvpFileName&MailingID=$mailingID&WriteEnabled=$writeEnabled&NumberToSend=$numberToSend&StartRowIndex=$startRowIndex&Sub1=$sub1&Sub2=$sub2&Sub3=$sub3&MessageFileName=$messageFileName&Subject=$subject&MailTableName=$mailTableName&SystemPassword=$systemPassword&SystemUserID=$systemUserID";
	}

FormRedirect($targetURL,"POST",$debug);
?>
