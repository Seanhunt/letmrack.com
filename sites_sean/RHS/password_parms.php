<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// pw_parms.php
//
// This is system user stuff for the user modules
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

$mode					=	GetPassedValue("Mode");
$statusMessage			=	GetPassedValue("StatusMessage");
$userID					=	GetPassedValue("UserID");
$newUserID				=	GetPassedValue("NewUserID");

$systemPassword			=	GetPassedValue("SystemPassword");
$systemUserID			=	GetPassedValue("SystemUserID");
$password				=	GetPassedValue("Password");
$newPassword			=	GetPassedValue("NewPassword");
$newPasswordConfirm		=	GetPassedValue("NewPasswordConfirm");
$targetID				=	GetPassedValue("TargetID");
$firstName				=	GetPassedValue("FirstName");
$lastName				=	GetPassedValue("LastName");
$userType				=	GetPassedValue("UserType");
$galleryType			=	GetPassedValue("GalleryType");
$blogTable				=	GetPassedValue("BlogTable");
$eMail					=	GetPassedValue("EMail");
$caller					=	GetPassedValue("Caller");
$recordNumber			=	GetPassedValue("RecordNumber");

$pwParms	=	"RecordNumber=$recordNumber&Mode=$mode&StatusMessage=$statusMessage&UserID=$userID&NewUserID=$newUserID&SystemPassword=$systemPassword&SystemUserID=$systemUserID&NewPassword=$newPassword&NewPasswordConfirm=$newPasswordConfirm&TargetID=$targetID&FirstName=$firstName&LastName=$lastName&UserType=$userType&BlogTable=$blogTable&EMail=$eMail&Caller=$caller";

$debug					=	0;

if($debug) print "<br><hr>pw_parms.php<br><br>";
if($debug) print "mode: $mode<br>";
if($debug) print "caller: $caller<br>";
if($debug) print "statusMessage: $statusMessage<br>";
if($debug) print "userID: $userID<br>";
if($debug) print "newUserID: $newUserID<br>";
//if($debug) print "password: $password<br>";
if($debug) print "newPassword: $newPassword<br>";
if($debug) print "newPasswordConfirm: $newPasswordConfirm<br>";
if($debug) print "target: $targetID<br>";
if($debug) print "firstName: $firstName<br>";
if($debug) print "lastName: $lastName<br>";
if($debug) print "userType: $userType<br>";
if($debug) print "galleryType: $galleryType<br>";
if($debug) print "blogTable: $blogTable<br>";
if($debug) print "eMail: $eMail<br>";

if($debug) print "systemPassword: $systemPassword<br>";
if($debug) print "systemUserID: $systemUserID<br>";
if($debug) print "<br><hr><br><br>";
?>

