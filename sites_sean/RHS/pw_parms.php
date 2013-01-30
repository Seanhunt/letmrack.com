<?php
// This is the header for the new multi-user granular rights system
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

$pwUserID					=	GetPassedValue("pwUserID");
$pwPassword					=	GetPassedValue("pwPassword");
$pwNewPassword				=	GetPassedValue("pwNewPassword");
$pwRightsID					=	GetPassedValue("pwRightsID");
$pwEMail					=	GetPassedValue("pwEMail");
$pwFirstName				=	GetPassedValue("pwFirstName");
$pwLastName					=	GetPassedValue("pwLastName");
$pwRecordNumber				=	GetPassedValue("pwRecordNumber");
$pwPermission				=	GetPassedValue("pwPermission");
$pwMode						=	GetPassedValue("pwMode");
$pwPasswordConfirm			=	GetPassedValue("pwPasswordConfirm");
$pwGalleryType				=	GetPassedValue("pwGalleryType");
$pwGalleryName				=	GetPassedValue("pwGalleryName");
$pwCaller					=	GetPassedValue("pwCaller");

$targetID					=	GetPassedValue("TargetID");
$systemPassword				=	GetPassedValue("SystemPassword");
$systemUserID				=	GetPassedValue("SystemUserID");

$debug	=	0;

if($debug) print "<hr>pw_parms.php<br><br>";
if($debug) print "pwUserID: $pwUserID<br>";
if($debug) print "pwPassword: $pwPassword<br>";
if($debug) print "pwRightsID: $pwRightsID<br>";
if($debug) print "pwEMail: $pwEMail<br>";
if($debug) print "pwFirstName: $pwFirstName<br>";
if($debug) print "pwLastName: $pwLastName<br>";
if($debug) print "pwRecordNumber: $pwRecordNumber<br>";
if($debug) print "pwPermission: $pwPermission<br>";
if($debug) print "pwMode: $pwMode<br>";
if($debug) print "pwGalleryType: $pwGalleryType<br>";
if($debug) print "pwGalleryName: $pwGalleryName<br>";
if($debug) print "pwCaller: $pwCaller<br>";

if($debug) print "targetID: $targetID<br>";
if($debug) print "systemPassword: $systemPassword<br>";
if($debug) print "systemUserID: $systemUserID<br>";

if($debug) print "<hr><br>";
?>
