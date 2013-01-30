<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// mail_sub_handle_login.php
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------
include '../local_info.php';
include 'db_lib.php';
include 'cl_lib.php';
include 'mail_sub_parms.php';
include 'html_lib.php';	
include "name_value_pair_lib.php";
include 'redirect.php';

$debug	=	0;

// does the user exist
$tableName	=	MangleTableName("MailDatabase");

$query		=	"SELECT * FROM $tableName WHERE email_address=" . "'" . $eMailAddress . "'";	

$userExists	=	GetNumberOfRows($query);

if($debug) print "tableName: $tableName<br>";
if($debug) print "query: $query<br>";
if($debug) print "userExists: $userExists<br>";

if($userExists)
	{
	// test the password
	$tablePassword	=	GetFieldByComparison($tableName,"password","email_address",$eMailAddress);	

	if($debug) print "tablePassword: $tablePassword<br>";

	if(strcmp($tablePassword,$password)==0)
		{
		// good to go
		$targetPage		=	"mail_sub_manage.php";
		$statusMessage	=	"You are logged in to your account...";
		}
	else
		{
		// send to fail target
		$targetPage		=	"mail_sub_login.php";
		$statusMessage	=	"Incorrect Password... Please check your spelling.";
		}
	}
else
	{
	// send to fail target
	$targetPage		=	"mail_sub_login.php";
	$statusMessage	=	"The User: $eMailAddress does not exist. Please check your spelling.";
	}

$args		=	"?StatusMessage=$statusMessage&E-MailAddress=$eMailAddress";
$targetURL	=	"../$targetPage$args";

if($debug) print "targetURL: $targetURL<br>";
if($debug) print "targetPage: $targetPage<br>";
if($debug) print "statusMessage: $statusMessage<br>";
if($debug) print "args: $args<br>";

FormRedirect($targetURL,"POST",$debug);
?>
