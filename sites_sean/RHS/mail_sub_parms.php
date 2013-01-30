<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// mail_sub_parms.php
//
// This page provides all of the args for the subscription pages. 
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// --------------------------------------------------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------------------------------------------------
// argumants
// --------------------------------------------------------------------------------------------------------------------------------------

$mode					=	GetPassedValue("Mode");
$caller					=	GetPassedValue("Caller");
$title					=	GetPassedValue("Title");
$firstName				=	GetPassedValue("FirstName");
$lastName				=	GetPassedValue("LastName");
$address				=	GetPassedValue("Address");
$address2				=	GetPassedValue("Address2");
$city					=	GetPassedValue("City");
$state					=	GetPassedValue("State");
$zip					=	GetPassedValue("Zip");
$eMailAddress			=	GetPassedValue("E-MailAddress");
$eMailAddressConfirm	=	GetPassedValue("E-MailAddressConfirm");
$password				=	GetPassedValue("Password");
$passwordConfirm		=	GetPassedValue("PasswordConfirm");
$confirmed				=	GetPassedValue("Confirmed");
$sub1					=	GetPassedValue("Sub1");
$sub2					=	GetPassedValue("Sub2");
$sub3					=	GetPassedValue("Sub3");
$statusMessage			=	GetPassedValue("StatusMessage");
$itemNumber				=	GetPassedValue("ItemNumber");
$mailTableName			=	GetPassedValue("MailTableName");
$messageFileName		=	GetPassedValue("MessageFileName");
$subject 				=	GetPassedValue("Subject");
$startRowIndex			=	GetPassedValue("StartRowIndex");
$writeEnabled			=	GetPassedValue("WriteEnabled");
$numberToSend			=	GetPassedValue("NumberToSend");
$mailingID				=	GetPassedValue("MailingID");
$nvpFileName			=	GetPassedValue("NvpFileName");	
$messageClass			=	GetPassedValue("MessageClass");

$systemUserID			=	GetPassedValue("SystemUserID");	
$systemPassword			=	GetPassedValue("SystemPassword");


// --------------------------------------------------------------------------------------------------------------------------------------
// function GetMailSubParms()
//
// this will get the list of the names and values for passing on the CL
// it uses global declarations to get the values
// --------------------------------------------------------------------------------------------------------------------------------------

function GetMailSubParms()
{
global $mode;
global $caller;
global $title;
global $firstName;
global $lastName;
global $address;
global $address2;
global $city;
global $state;
global $zip;
global $eMailAddress;
global $eMailAddressConfirm;
global $password;
global $passwordConfirm;
global $confirmed;
global $sub1;
global $sub2;
global $sub3;
global $statusMessage;
global $itemNumber;
global $mailTableName;
global $messageFileName;
global $subject;
global $startRowIndex;
global $writeEnabled;
global $numberToSend;
global $mailingID;
global $nvpFileName;	

$debug		=	0;

if($debug) print "GetMailSubParms()<br>";

$argList	=	"Mode=$mode&Caller=$caller&Title=$title&FirstName=$firstName&LastName=$lastName&Address=$address&Address2=$address2&City=$city&State=$state&Zip=$zip&E-MailAddress=$eMailAddress&E-MailAddressConfirm=$eMailAddressConfirm&Password=$password&PasswordConfirm=$passwordConfirm&Confirmed=$confirmed&Sub1=$sub1&Sub2=$sub2&Sub3=$sub3&ItemNumber=$itemNumber&MailTableName=$mailTableName&MessageFileName=$messageFileName&Subject=$subject&StartRowIndex=$startRowIndex&WriteEnabled=$writeEnabled&NumberToSend=$numberToSend&MailingID=$mailingID&NvpFileName=&$nvpFileName&StatusMessage=$statusMessage";

if($debug) print "argList: $argList<br>";

return $argList;
}

$mailSubArgs = "Title=$title&FirstName=$firstName&LastName=$lastName&Address=$address&Address2=$address2&City=$city&State=$state&Zip=$zip&E-MailAddress=$eMailAddress&E-MailAddressConfirm=$eMailAddressConfirm&Password=$password&PasswordConfirm=$passwordConfirm&Sub1=$sub1&Sub2=$sub2&Sub3=$sub3";

// --------------------------------------------------------------------------------------------------------------------------------------
// debug
// --------------------------------------------------------------------------------------------------------------------------------------

$debug					=	0;


if($debug) print "<br>-------------------------------------------------------------------------------------------------<br>";
if($debug) print "mail_sub_parms.php<br>";
if($debug) print "nvpFileName: $nvpFileName<br>";
if($debug) print "writeEnabled: $writeEnabled<br>";
if($debug) print "numberToSend: $numberToSend<br>";
if($debug) print "mailTableName: $mailTableName<br>";
if($debug) print "messageFilename: $messageFileName<br>";
if($debug) print "subject: $subject<br>";
if($debug) print "startRowIndex: $startRowIndex<br>";
if($debug) print "mode: $mode<br>";
if($debug) print "caller: $caller<br>";
if($debug) print "title: $title<br>";
if($debug) print "firstName: $firstName<br>";
if($debug) print "lastName: $lastName<br>";
if($debug) print "address: $address<br>";
if($debug) print "address2: $address2<br>";
if($debug) print "city: $city<br>";
if($debug) print "state: $state<br>";
if($debug) print "zip: $zip<br>";
if($debug) print "eMailAddress: $eMailAddress<br>";
if($debug) print "eMailAddressConfirm: $eMailAddressConfirm<br>";
if($debug) print "password: $password<br>";
if($debug) print "passwordConfirm: $passwordConfirm<br>";
if($debug) print "confirmed: $confirmed<br>";
if($debug) print "sub1: $sub1<br>";
if($debug) print "sub2: $sub2<br>";
if($debug) print "sub3: $sub3<br>";
if($debug) print "statusMessage: $statusMessage<br>";
if($debug) print "itemNumber: $itemNumber<br>";
if($debug) print "-------------------------------------------------------------------------------------------------<br>";
?>
