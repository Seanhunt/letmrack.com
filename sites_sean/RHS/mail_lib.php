<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// mail_lib.php
//
// Service routines for mail subsystem
// 
// Revision:	020706	Moved and revised to use db module calls
//
//				062406  Added field to AddAddress INSERT to keep up with 1.1 mail table addition of 
//						mailing_date
//
//				111906	Added new fields and func for message generation.
//
//				070907	GenerateMailingListMessageB was calling a high level db call and trashing the handle.
//						I replaced the calls with the low level calls
//
// Dependencies:	file_lib.php, ../local_info.php
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// --------------------------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------
// function IsBlankString($string)
//
// This return true if the string has only whitespace chars in it...
//-------------------------------------------------------------------------------------

function IsBlankString($string)
{
//print "IsBlankString($string)<br>";

//$test	=	str_replace(" \t\n\r\0\x0B","", $string);
$test	=	str_replace(" ","", $string);
$test	=	str_replace("\x0a","", $test);
$test	=	str_replace("\x09","", $test);
$test	=	str_replace("\x00","", $test);
$test	=	str_replace("\x0b","", $test);
$test	=	str_replace("\x0d","", $test);


// print "ASCII codes in string: " .  implode (' ',unpack ('C*', $test)) . "<br>";


if($test=="")
	{
	return true;
	}

return false;
}




// --------------------------------------------------------------------------------------------------------------------------------------
// function GenerateMailingListMessage($messageFileName,$eMailAddress)
//
// This will search and replace the following tags with their respective values.
//
// _SALUTATION
// _TITLE
// _FIRST_NAME
// _LAST_NAME
// _EMAIL
// _PASSWORD
// _CREATION_DATE
// _ADDRESS1
// _ADDRESS2
// _CITY
// _STATE
// _ZIP
// _SUB1
// _SUB2
// _SUB3
// _LIST_1_NAME
// _LIST_2_NAME
// _LIST_3_NAME
// _SITE_NAME
// _SITE_OWNER
// _SITE_URL
// --------------------------------------------------------------------------------------------------------------------------------------

function GenerateMailingListMessage($messageFileName,$eMailAddress,$salutation,$theMessageFile="")
{
$debug	=	0;

global  $gBaseMountPoint;
global	$gSiteOwner;
global	$gSiteName;
global  $gSiteURL;
global	$gMailDatabaseName;

if($debug) print "<hr>function GenerateMailingListMessage($messageFileName,$eMailAddress,$salutation,messageFileData)<br>";


if($debug) print "gMailDatabaseName: $gMailDatabaseName<br>";


// load the file if one is not provided as the defaulting last arg
if($theMessageFile == "")
	{
	if(($file=FileRead($messageFileName))==FALSE)
		{
		print "Failed to Load File: $messageFileName<br>";
		}
	}
else
	{
	$file	=	$theMessageFile;	
	}
		
// set up the mail table name
$tableName				=	"$gMailDatabaseName";

if($debug) print "tableName: $tableName<br>";

// load the values
$siteURL				=	$gSiteURL . $gBaseMountPoint;
$title					=	GetFieldByComparison($tableName,"title","email_address",$eMailAddress);
$firstName				=	GetFieldByComparison($tableName,"first_name","email_address",$eMailAddress);
$lastName				=	GetFieldByComparison($tableName,"last_name","email_address",$eMailAddress);
$address				=	GetFieldByComparison($tableName,"street_address","email_address",$eMailAddress);
$address2				=	GetFieldByComparison($tableName,"street_address_2","email_address",$eMailAddress);
$city					=	GetFieldByComparison($tableName,"city","email_address",$eMailAddress);
$state					=	GetFieldByComparison($tableName,"state","email_address",$eMailAddress);
$zip					=	GetFieldByComparison($tableName,"zip","email_address",$eMailAddress);
$password				=	GetFieldByComparison($tableName,"password","email_address",$eMailAddress);
$creationDate			=	GetFieldByComparison($tableName,"creation_date","email_address",$eMailAddress);
$sub1					=	GetFieldByComparison($tableName,"subscribed1","email_address",$eMailAddress);
$sub2					=	GetFieldByComparison($tableName,"subscribed2","email_address",$eMailAddress);
$sub3					=	GetFieldByComparison($tableName,"subscribed3","email_address",$eMailAddress);
$name1					=	GetMailingListName(1);
$name2					=	GetMailingListName(2);
$name3					=	GetMailingListName(3);

// search and replace the tags
$file	=	str_replace("_SITE_URL",$siteURL,$file);
$file	=	str_replace("_LIST_1_NAME",$name1,$file);
$file	=	str_replace("_LIST_2_NAME",$name2,$file);
$file	=	str_replace("_LIST_3_NAME",$name3,$file);
$file	=	str_replace("_SITE_OWNER",$gSiteOwner,$file);
$file	=	str_replace("_SITE_NAME",$gSiteName,$file);
$file	=	str_replace("_SALUTATION",$salutation,$file);
$file	=	str_replace("_TITLE",$title,$file);
$file	=	str_replace("_FIRST_NAME",$firstName,$file);
$file	=	str_replace("_LAST_NAME",$lastName,$file);
$file	=	str_replace("_EMAIL",$eMailAddress,$file);
$file	=	str_replace("_PASSWORD",$password,$file);
$file	=	str_replace("_CREATION_DATE",$creationDate,$file);
$file	=	str_replace("_ADDRESS1",$address,$file);
$file	=	str_replace("_ADDRESS2",$address2,$file);
$file	=	str_replace("_CITY",$city,$file);
$file	=	str_replace("_STATE",$state,$file);
$file	=	str_replace("_ZIP",$zip,$file);
$file	=	str_replace("_SUB1",$sub1,$file);
$file	=	str_replace("_SUB2",$sub2,$file);
$file	=	str_replace("_SUB3",$sub3,$file);

// debuggin me
if($debug) print "siteURL: $siteURL<br>";
if($debug) print "name1: $name1<br>";
if($debug) print "name2: $name2<br>";
if($debug) print "name3: $name3<br>";
if($debug) print "salutation: $salutation<br>";
if($debug) print "title: $title<br>";
if($debug) print "firstName: $firstName<br>";
if($debug) print "lastName: $lastName<br>";
if($debug) print "address: $address<br>";
if($debug) print "address2: $address2<br>";
if($debug) print "city: $city<br>";
if($debug) print "state: $state<br>";
if($debug) print "zip: $zip<br>";
if($debug) print "eMailAddress: $eMailAddress<br>";
if($debug) print "password: $password<br>";
if($debug) print "creationDate: $creationDate<br>";
if($debug) print "sub1: $sub1<br>";
if($debug) print "sub2: $sub2<br>";
if($debug) print "sub3: $sub3<br>";
if($debug) print "-------------------------------------------------------------------------------------------------<br>";
if($debug) print "file: $file<br>";
if($debug) print "-------------------------------------------------------------------------------------------------<br>";

// return the fixed up file
return $file;
}

// db open and closed externally
function GenerateMailingListMessageB($mailTableName,$messageFileName,$eMailAddress,$salutation,$theMessageFile="")
{
$debug	=	0;

global	$gSiteOwner;
global	$gSiteName;
global  $gSiteURL;
global $gBaseMountPoint;

$siteURL	=	"$gSiteURL$gBaseMountPoint";

if($debug) print "function GenerateMailingListMessageB($mailTableName,$messageFileName,$eMailAddress,$salutation,messageFileData)<br>";

// load the file if one is not provided as the defaulting last arg
if($theMessageFile == "")
	{
	if(($file=FileRead($messageFileName))==FALSE)
		{
		print "Failed to Load File: $messageFileName<br>";
		}
	}
else
	{
	$file	=	$theMessageFile;	
	}
		
// load the values

// this will go fast...
$query		=	"SELECT * FROM $mailTableName WHERE email_address='$eMailAddress'";

if($debug) print "query: $query<br>";

$rs			=	QueryDatabase($query);

$num		=	mysql_num_rows($rs);

if($debug) print "num: $num<br>";
if($debug) print "query: $query<br>";

if(!rs) 
	if($debug) print "SQL Error: " . mysql_error() . "<br>";

$title			=	mysql_result($rs,0,"title");
$firstName		=	mysql_result($rs,0,"first_name");
$lastName		=	mysql_result($rs,0,"last_name");
$address		=	mysql_result($rs,0,"street_address");
$address2		=	mysql_result($rs,0,"street_address_2");
$city			=	mysql_result($rs,0,"city");
$state			=	mysql_result($rs,0,"state");
$zip			=	mysql_result($rs,0,"zip");
$password		=	mysql_result($rs,0,"password");
$creationDate	=	mysql_result($rs,0,"creation_date");
$sub1			=	mysql_result($rs,0,"subscribed1");
$sub2			=	mysql_result($rs,0,"subscribed2");
$sub3			=	mysql_result($rs,0,"subscribed3");

// set up the list names table name
$lnTableName	=	MangleTableName("MailingListNames");	

$lnRs			=	QueryDatabase("SELECT * FROM $lnTableName");

$name1			=	mysql_result($lnRs,0,"Name");
$name2			=	mysql_result($lnRs,1,"Name");
$name3			=	mysql_result($lnRs,2,"Name");

/*
$name1					=	GetFieldByIndex($lnTableName,"Name",0);
$name2					=	GetFieldByIndex($lnTableName,"Name",1);
$name3					=	GetFieldByIndex($lnTableName,"Name",2);
*/

// search and replace the tags
$file	=	str_replace("_SITE_URL",$siteURL,$file);
$file	=	str_replace("_LIST_1_NAME",$name1,$file);
$file	=	str_replace("_LIST_2_NAME",$name2,$file);
$file	=	str_replace("_LIST_3_NAME",$name3,$file);
$file	=	str_replace("_SITE_OWNER",$gSiteOwner,$file);
$file	=	str_replace("_SITE_NAME",$gSiteName,$file);
$file	=	str_replace("_SALUTATION",$salutation,$file);
$file	=	str_replace("_TITLE",$title,$file);
$file	=	str_replace("_FIRST_NAME",$firstName,$file);
$file	=	str_replace("_LAST_NAME",$lastName,$file);
$file	=	str_replace("_EMAIL",$eMailAddress,$file);
$file	=	str_replace("_PASSWORD",$password,$file);
$file	=	str_replace("_CREATION_DATE",$creationDate,$file);
$file	=	str_replace("_ADDRESS1",$address,$file);
$file	=	str_replace("_ADDRESS2",$address2,$file);
$file	=	str_replace("_CITY",$city,$file);
$file	=	str_replace("_STATE",$state,$file);
$file	=	str_replace("_ZIP",$zip,$file);
$file	=	str_replace("_SUB1",$sub1,$file);
$file	=	str_replace("_SUB2",$sub2,$file);
$file	=	str_replace("_SUB3",$sub3,$file);

// debuggin me
if($debug) print "<br>-------------------------------------------------------------------------------------------------<br>";
if($debug) print "name1: $name1<br>";
if($debug) print "name2: $name2<br>";
if($debug) print "name3: $name3<br>";
if($debug) print "tableName: $tableName<br>";
if($debug) print "salutation: $salutation<br>";
if($debug) print "title: $title<br>";
if($debug) print "firstName: $firstName<br>";
if($debug) print "lastName: $lastName<br>";
if($debug) print "address: $address<br>";
if($debug) print "address2: $address2<br>";
if($debug) print "city: $city<br>";
if($debug) print "state: $state<br>";
if($debug) print "zip: $zip<br>";
if($debug) print "eMailAddress: $eMailAddress<br>";
if($debug) print "password: $password<br>";
if($debug) print "creationDate: $creationDate<br>";
if($debug) print "sub1: $sub1<br>";
if($debug) print "sub2: $sub2<br>";
if($debug) print "sub3: $sub3<br>";
if($debug) print "-------------------------------------------------------------------------------------------------<br>";
if($debug) print "file: $file<br>";
if($debug) print "-------------------------------------------------------------------------------------------------<br>";

// return the fixed up file
return $file;
}


// --------------------------------------------------------------------------------------------------------------------------------------
// function GetMailingListName($oneToThree)
// --------------------------------------------------------------------------------------------------------------------------------------

function GetMailingListName($oneToThree)
{
$tableName	=	MangleTableName("MailingListNames");	

return GetFieldByIndex($tableName,"Name",$oneToThree-1);
}

function GetMailingListDescription($oneToThree)
{
$tableName	=	MangleTableName("MailingListNames");	

return GetFieldByIndex($tableName,"Description",$oneToThree-1);
}

function GetMailingListIsChecked($oneToThree)
{
$tableName	=	MangleTableName("MailingListNames");	

$checked	=	GetFieldByIndex($tableName,"Checked",$oneToThree-1);

if(strcmp($checked,"Y")==0)
	return "checked";
else
	return "";
}

function GetNumberOfMailingLists()
{
$tableName	=	MangleTableName("MailingListNames");	

return GetNumberOfRows("SELECT * FROM $tableName");
}

// --------------------------------------------------------------------------------------------------------------------------------------
// function GenerateMessage($recipientAddress,$messageText)
// --------------------------------------------------------------------------------------------------------------------------------------

function GenerateMessage($recipientAddress,$messageText)
{
$debug	=	0;

if($debug)	print "GenerateMessage($recipientAddress,$messageText)<br><br>";

// -----------------------------------------------------------------------------------------------------------------------------------
// add the opt out message
// -----------------------------------------------------------------------------------------------------------------------------------

$messageText	.=	CenterOn();

$messageText	.=	ParagraphOpen();

$messageText	.=	FontHelveticaOpen("1");

$messageText	.=	"$unsubMessage
					<br>If you would like to unsubscribe or alter your mail preferences click ";

$unsubString	=	"$unsubURL/mail_manage_sub.php?E-MailAddress=";

$messageText	.=	AnchorOpen($unsubString . $recipientAddress);

$messageText	.=	"here ";

$messageText	.=	AnchorClose();

//$messageText	.=	"to opt-out of our mailing list.<br><br><br><br><br>";

$messageText	.=	FontClose();

$messageText	.=	ParagraphClose();

$messageText	.=	CenterOff();

$messageText	.=	HtmlPageEnd();

return $messageText;
}

// --------------------------------------------------------------------------------------------------------------------------------------
// function LoadMessage($fileName)
//
// Loads the given file and erases the /body and /html tags off the end
// --------------------------------------------------------------------------------------------------------------------------------------

function LoadMessage($fileName)
{
$debug	=	0;

if($debug) print "function LoadMessage($fileName)<br>";

if(($theFile=FileRead($fileName))==FALSE)
	{
	if($debug) print "FAIL<br>";
	return FALSE;
	}

// clean the end tags off of the file
$theFile = str_replace("</body>", "", $theFile);
$theFile = str_replace("</BODY>", "", $theFile);

$theFile = str_replace("</html>", "", $theFile);
$theFile = str_replace("</HTML>", "", $theFile);

return $theFile;
}

function DeleteAddress($address,$tableName)
{
$debug	=	0;

if($debug) print "DeleteAddress($address,$tableName)<br>";

OpenDatabase();

$query	=	"DELETE FROM $tableName WHERE address=" . '"' . $address . '"';

$rv				=	QueryDatabase($query);

CloseDatabase();

return $rv;
}

/*
//
// testbed for function email_send($toAddress,$fromAddress,$fromName,$subject,$message)
//

$toAddress		=	"cooksey@cookseytalbottstudio.com";
$fromAddress	=	"cooksey@cookseytalbottstudio.com";
$fromName		=	"Ralph Cooksey-Talbott";
$subject		=	"foo";
$message		=	"bar";

print "toAddress: $toAddress<br>";
print "fromAddress: $fromAddress<br>";
print "fromName: $fromName<br>";
print "subject: $subject<br>";
print "message: $message<br>";

if(email_send($toAddress,$fromAddress,$fromName,$subject,$message)==1)
	print "Mail Sent...<br>";
else
	print "Mail Not Sent...<br>";
*/

// -----------------------------------------------------------------------------------------------
// function email_send($toAddress,$fromAddress,$fromName,$subject,$message)
//
// This is a basic mail sending function, it sends in HTML mode
//
// Return: 	1 on success
//			0 On Fail 
// -----------------------------------------------------------------------------------------------

function email_send($toAddress,$fromAddress,$fromName,$subject,$message)
{
global $gMailerBounceAddress;
$debug	=	0;

if($debug) print "email_send($toAddress,$fromAddress,$fromName,$subject,$message)<br>";

$headers  		=	"Mime-version: 1.0\n" .
					"Content-Type: text/html; charset=iso-8859-1\n" .
					"From: " . '"' . $fromName . '"' . " <" . $fromAddress . ">\n" .
					"Reply-To: " . '"' . $fromName . '"' . " <" . $fromAddress . ">\n" .
					"X-Mailer: PHP/" . phpversion();

if($debug) print "headers: $headers<br>";

if(mail($toAddress,$subject,$message,$headers,"-f $gMailerBounceAddress")) 
	{
	return	1;
	} 

return 0;
}



// --------------------------------------------------------------------------------------------------------------------------------------
// mail_validate_address.php
// 
// tests for well formed address and it retrieves the mx rec for validation of domain
//
// Returns: 	1 if it is a well formed e-mail address and it has a valid domain
// 				0 if invalid domain
// 				-1 if poorly formed address
//
// Rev: 062805	-	File originated
//		022106	-	Moved into new application skeleton
// --------------------------------------------------------------------------------------------------------------------------------------

function validate_email($email)
{

   // Create the syntactical validation regular expression
   $regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";

   // Presume that the email is invalid
   $valid = 0;

   // Validate the syntax
	if (eregi($regexp, $email))
		{
		list($username,$domaintld) = split("@",$email);
		// Validate the domain
		if (getmxrr($domaintld,$mxrecords))
			$valid = 1;
		else
			$valid = 0;
		} 
	else 
		{
		$valid = -1;
		}

   return $valid;
}


?>
