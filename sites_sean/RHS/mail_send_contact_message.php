<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// mail_send_contact_message.php
//
// Inputs: Mode, Name, E-MailAddress, Subject, MessageText, Caller
//
// This will handle the sending of a message from a website contact page to the website
// secretary.
//
// PATCH .4 102907 on newarkdays.org
//
// 2.1.7.4	Added trap in mail_send_contact_message to make sure the message length is > 0
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
include 'mail_lib.php';
include 'cl_lib.php';
include 'db_lib.php';
include 'captcha_lib.php';
include 'html_lib.php';	
include "name_value_pair_lib.php";
include 'redirect.php';

$mode						=	GetPassedValue("Mode");
$caller						=	GetPassedValue("Caller");
$name						=	GetPassedValue("Name");
$address					=	GetPassedValue("E-MailAddress");
$subject					=	GetPassedValue("Subject");
$mailMessage				=	GetPassedValue("MailMessage");

// 2.1.9 NEW
$fileName					=	GetPassedValue("cfn");
$type						=	GetPassedValue("ct");
$captchaResponse			=	GetPassedValue("cr");
$captchaText				=	GetCaptchaText($fileName,$type);

$toAddress					=	$gSiteSecretaryAddress;
$error						=	0;	
$ipAddress 					= 	gethostbyname($REMOTE_ADDR);
$hostName 					= 	gethostbyaddr($_SERVER['REMOTE_ADDR']);
$senderInformation			=	"<hr>Sender IP: $ipAddress<br>Sender Host Name: $hostName<br>";

$debug						=	0;

// 102907 PATCH 2.1.7.4
if(strlen($mailMessage)==0 || $mailMessage=="Type your message here..." || IsBlankString($mailMessage))
	{
	$statusMessage		=	"You must enter some message text...";
	$error				=	1;
	$addlArgs			=	"&Name=$name&E-MailAddress=$address&Subject=$subject&MailMessage=$mailMessage";
	}
else
	{
	// munge the message string and make <br>'s from \n's
	$mailMessage = implode("<br>", preg_split("/\r?\n/", $mailMessage));
	}

if($debug) print "mode: $mode<br>";
if($debug) print "caller: $caller<br>";
if($debug) print "name: $name<br>";
if($debug) print "address: $address<br>";
if($debug) print "subject: $subject<br>";
if($debug) print "mailMessage: $mailMessage<br>";
if($debug) print "htmlMessage: $htmlMessage<br>";
if($debug) print "toAddress: $toAddress<br>";
if($debug) print "fileName: $fileName<br>";
if($debug) print "type: $type<br>";
if($debug) print "captchaText: $captchaText<br>";
if($debug) print "captchaResponse: $captchaResponse<br>";


if(strcmp($mode,"Send")==0)
	{
	// 2.2.0 NEW
	if($gRequireCaptcha)
		{
		// 2.1.9 NEW
		if($captchaText != $captchaResponse)
			{
			$statusMessage		=	"The secret word is filled out incorrectly, please try again...";
			$error				=	1;
			$addlArgs			=	"&Name=$name&E-MailAddress=$address&Subject=$subject&MailMessage=$mailMessage";
			}
		}

	// Check to be sure a return address was provided
	if(strlen($address)==0)
		{
		$statusMessage		=	"You must enter you email address so that I can reply.";
		$error				=	1;
		$addlArgs			=	"&Name=$name&E-MailAddress=$address&Subject=$subject&MailMessage=$mailMessage";
		}

	// test for valid domain and well formed email address
	if(!$error)
		$rv		=	validate_email($address);
	
	// poorly formed address
	if($rv == -1 && !$error)
		{
		$statusMessage		=	"The email address: $address is incorrectly formed";
		$error				=	1;
		$addlArgs			=	"&Name=$name&E-MailAddress=$address&Subject=$subject&MailMessage=$mailMessage";
		}

	// invalid domain
	if($rv == 0 && !$error)
		{
		$statusMessage		=	"The email address: $address has an invalid domain...";
		$error				=	1;
		$addlArgs			=	"&Name=$name&E-MailAddress=$address&Subject=$subject&MailMessage=$mailMessage";
		}

	// if all is good sent the statusMessage
	if(!$error)
		{
		$statusMessage					=	"Your message was sent!";
		$error						=	0;	

		$completeMessage			=	"$mailMessage<br><br>$senderInformation	";

		$rv	=	email_send($toAddress,$address,$name,"[$gSiteName Contact] " . $subject,$completeMessage);
		}

	// if bounce is not a real address mail() will fail
	if($rv == 0  && $error==0)
		{
		$statusMessage		=	"The mail was not sent due to a system error...";
		$error				=	1;
		$addlArgs			=	"&Name=$name&E-MailAddress=$address&Subject=$subject&MailMessage=$mailMessage";
		}

	if($debug) print "statusMessage: $statusMessage<br>";
	}

$targetURL	=	"../$caller?StatusMessage=$statusMessage$addlArgs";

if($debug) print "targetURL: $targetURL<br>";

//Redirect($targetURL);
FormRedirect($targetURL,"POST",$debug);
?>
