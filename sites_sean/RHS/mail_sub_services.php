<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// mail_sub_services.php
//
// This page provides all of the functionality used by the subscription group of pages based on the Mode 
// argument.
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// --------------------------------------------------------------------------------------------------------------------------------------

include '../local_info.php';
include 'db_lib.php';
include 'cl_lib.php';
include 'file_lib.php';
include 'mail_lib.php';
include 'html_lib.php';	
include "name_value_pair_lib.php";
include 'redirect.php';
include 'pw_lib.php';
include 'numbers_lib.php';
include 'pw_parms.php';
include 'mail_sub_parms.php';
include "debug_lib.php";

$debug			=	0;
$debugLog		=	0;		// writes to debug log table
$debugMails		=	0;		// this makes it echo selected end user mails and it sends some diagnostic mails to the admin
$formMethod		=	"GET";
$debugAdd		=	0;		// messages in ADD trap

// --------------------------------------------------------------------------------------------------------------------------------------
// globals
// --------------------------------------------------------------------------------------------------------------------------------------

$error					=	0;
$writeEnabled			=	1;				// set to 1 to enable database writes
$statusMessage			=	"No Error";
$returnPage				=	"";

DebugMessage("<br><br>CALL TO SUB-SERVICES<br><br>Status: $statusMessage<br><br>Mode: $mode<br><br>User: $eMailAddress<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

// --------------------------------------------------------------------------------------------------------------------------------------
// This is for mailing of notifications
// --------------------------------------------------------------------------------------------------------------------------------------

// $tableName	=		$gMailDatabaseName;

// fix up the table name
$tableName	=		$gMailDatabaseName;

if($debug) print "tableName: $tableName<br>";
if($debug) print "gMailDatabaseName: $gMailDatabaseName<br>";


// bad dog...
// list 1
if(strcmp($sub1,"Y")==0)			// S1
	$s1	=	"Y";
else
	$s1	=	"N";

// list 2
if(strcmp($sub2,"Y")==0)		// S2
	$s2	=	"Y";
else
	$s2	=	"N";

// list 3
if(strcmp($sub3,"Y")==0)			// S3
	$s3	=	"Y";
else
	$s3	=	"N";

// --------------------------------------------------------------------------------------------------------------------------------------
// Execute appropriate function and return to the calling page with a message
// --------------------------------------------------------------------------------------------------------------------------------------

// ****************************************************************************************************
// ****************************************************************************************************
// NEW MODE
// This has been deprecated...
// ****************************************************************************************************
// ****************************************************************************************************


if($mode=="New")
	{
	if($debug) print "New Mode...<br>";


	$recordID	=	GenerateUniqueID("New_Record_");
	
	if($debug) print "recordID: $recordID<br>";

	$creationDate	=	date("F j, Y, g:i:s a");                // March 10, 2001, 5:16:34 pm
	$ipAddress 		= 	gethostbyname($REMOTE_ADDR);
	$hostName 		= 	gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$mailingFlag	=	"New Record";
	$confirmed		=	"Y";
	$password		=	GenerateFriendlyPassword();
	
	if($debug) print "mailingFlag: $mailingFlag<br>";
	if($debug) print "creationDate: $creationDate<br>";
	if($debug) print "ipAddress: $ipAddress<br>";
	if($debug) print "hostName: $hostName<br>";
	if($debug) print "password: $password<br>";
	if($debug) print "mailingFlag: $mailingFlag<br>";
	
	// set up the query to insert a new record for editing
	
	$format			=	"
					INSERT INTO %s 
					(
					email_address,
					password,
					ip_address,
					host_name,
					creation_date,
					confirmed,
					mailing_flag
					)  
					VALUES 
					( 	
					'%s', 
					'%s', 
					'%s', 
					'%s', 
					'%s', 
					'%s', 
					'%s' 
					)"
					;


	if($debug) print "format: $format<br>";

	// open and close as the escape string func
	// relies on the connection context for encoding.

	OpenDatabase();

	$query 		= 	sprintf($format,
							$mailTableName,
							mysql_real_escape_string($recordID),
							mysql_real_escape_string($password),
							mysql_real_escape_string($ipAddress),
							mysql_real_escape_string($hostName),
							mysql_real_escape_string($creationDate),
							mysql_real_escape_string($confirmed),
							mysql_real_escape_string($mailingFlag)
							);

	if($debug) print "query: $query<br>";

	if(QueryDatabase($query)==0)
		{
		print "Error: " . mysql_error();
		}
		
	CloseDatabase();
	
	// now I have a new record, lets get it...
	$itemNumber 	=	GetFieldByComparison($mailTableName,"item_number","email_address",$recordID);

	// send control back to the editor page
	$targetURL	=	"mail_sub_edit.php?Mode=Go&ItemNumber=$itemNumber&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	
	FormRedirect($targetURL,$formMethod,$debug);
	}


// ****************************************************************************************************
// ****************************************************************************************************
// UPDATE MODE
// 
// This comes from the administrator console
// ****************************************************************************************************
// ****************************************************************************************************

else if($mode=="Update")
	{
	if($debug) print "Update MODE<br>";

	// set up the query to insert a new record
	$format			=	"
					UPDATE %s 
					SET
					title='%s',
					first_name='%s',
					last_name='%s',
					street_address='%s',
					street_address_2='%s',
					city='%s',
					state='%s',
					zip='%s',
					email_address='%s',
					password='%s',
					subscribed1='%s',
					subscribed2='%s',
					subscribed3='%s'
					WHERE
					item_number='%s'
					";

		// open and close as the escape string func
		// relies on the connection context for encoding.

		OpenDatabase();
		// this is a huggermugger ;/		
		$query 		= 	sprintf($format,
								$mailTableName,
								mysql_real_escape_string($title),	
								mysql_real_escape_string($firstName),	
								mysql_real_escape_string($lastName),	
								mysql_real_escape_string($address),	
								mysql_real_escape_string($address2),	
								mysql_real_escape_string($city),
								mysql_real_escape_string($state),
								mysql_real_escape_string($zip),
								mysql_real_escape_string($eMailAddress),
								mysql_real_escape_string($password),
								mysql_real_escape_string($s1),
								mysql_real_escape_string($s2),
								mysql_real_escape_string($s3),
								mysql_real_escape_string($itemNumber)
								);

		$rv	=	QueryDatabase($query);

		CloseDatabase();

		if($debug) print "format: $format<br>";
		if($debug) print "query: $query<br>";

	if($rv == 1)
		{
		$statusMessage		=	"";
		}
	else
		{
		$statusMessage		=	"Address $eMailAddress NOT modified.";
		print "Update Error: " . mysql_error() . "<br>";
		}	

	if($debug) print "rv: $rv<br>";

	$returnPage	=	"mail_sub_edit.php";

	// really this just has to call for a refresh...
	$args		=	"?Mode=Go&MailTableName=$mailTableName&ItemNumber=$itemNumber&StatusMessage=$statusMessage&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
			
	$location	=	"$returnPage$args";		

	FormRedirect($location,$formMethod,$debug);
	}

// ****************************************************************************************************
// ****************************************************************************************************
// ADD MODE
//
// REQUIRED
//
// first
// last
// email
// email confirm
// pass
// pass confirm
//
// TESTS
//
// MATCH - email & email confirm
// MATCH - pass & pass confirm
// MINIMUM ONE CHECKED - sub1, sub2, sub3
// VALID DOMAIN 
// WELL FORMED EMAIL ADDRESS
// ****************************************************************************************************
// ****************************************************************************************************

else if($mode=="Add")
	{
	DebugMessage("<br><br>ADD MODE<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

	$statusMessage		=	"You have been added to our mailing list and will receive an E-Mail that will allow you to confirm your subscription. The confirmation E-Mail may end up in your junk folder. Add my e-mail address to your address book to stop that from happening.";

	if($debug) print "Add Mode...<br>";
	
	if($debugAdd) print "1 - Add Mode...<br>";

	if($title=="Pick One")	// if they didnt pick a title lets not put it into the db
		$title	=	"";
	
	// Test to see if a required field was left blank, eMailAddress, eMailAddressConfirm, password, passwordConfirm
	// 1
	if(strlen($eMailAddress)==0)
		{
		$statusMessage		=	"Please enter an e-mail address";
		$error				=	1;
		}

	// 2
	if((strlen($firstName)==0) && !$error)
		{
		$statusMessage		=	"Please enter a first name";
		$error				=	1;
		}

	// 3
	if((strlen($lastName)==0) && !$error)
		{
		$statusMessage		=	"Please enter a last name";
		$error				=	1;
		}
/*
	// 4
	if($eMailAddressConfirm=="" && !$error)
		{
		$statusMessage		=	"Please enter an e-mail confirmation address";
		$error				=	1;
		}
	
	
		

	// 5
	if($password=="" && !$error)
		{
		$statusMessage		=	"Please enter a password";
		$error				=	1;
		}

	// 6
	if($passwordConfirm=="" && !$error)
		{
		$statusMessage		=	"Please enter a confirmation password";
		$error				=	1;
		}
*/
	if($debug) print "statusMessage: $statusMessage<br>";
	if($debug) print "error: $error<br>";


$password	=	GenerateFriendlyPassword();

if($debug) print "password: $password<br>";

	// Test checkboxes, one of the boxes must be checked to execute an Add operation
	// 7
	if(!$error)
		{
		if($sub1=="" && $sub2=="" && $sub3=="")
			{
			if($debug) print "sub1: $sub1<br>";
			if($debug) print "sub2: $sub2<br>";
			if($debug) print "sub3: $sub3<br>";
	
			$statusMessage		=	"Please check a list to join";
			$error				=	1;
			}
		}
	
	/*	
	// test email address and confirmation for a match
	// 8
	if(($eMailAddress != $eMailAddressConfirm) && !$error)
		{
		$statusMessage		=	"Please re-enter your e-mail addresses, they don't match";
		$error				=	1;
		}

	// 9
	if(strcmp($password,$passwordConfirm)!=0 && !$error)
		{
		$statusMessage		=	"Please re-enter your passwords, they don't match";
		$error				=	1;
		}
	*/
	
	// test for valid domain and well formed email address
	if(!$error)
		$rv		=	validate_email($eMailAddress);
	
	// poorly formed address
	// 10
	if($rv == -1 && !$error)
		{
		$statusMessage		=	"The email address $eMailAddress is incorrectly formed";
		$error				=	1;
		}

	// invalid domain
	// 11
	if($rv == 0 && !$error)
		{
		$statusMessage		=	"The email address $eMailAddress has an invalid domain...";
		$error				=	1;
		}

	if($debug) print "Add Mode - Post Input Error Traps <br>error=$error<br>statusMessage=$statusMessage<br>";
	if($debugAdd) print "2 - Add Mode...<br>";

	// -----------------------------------------------
	// 
	// -----------------------------------------------

	DebugMessage("<br><br>ADD MODE - Post Input Error Traps<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

	if(!$error)
		{
		// does it already exist ?

		// fix up the table name
		//$tableName	=		"APPDEV_$gMailTableName";
		
		$query 	=	"SELECT * FROM " . $tableName . " WHERE email_address='" . $eMailAddress . "'";	

		if($debug) print "query: $query<br>";

		$userExists	=	GetNumberOfRows($query);

		// -----------------------------------------------
		// Debug Instrumentation 
		// -----------------------------------------------
	
		DebugMessage("<br><br>ADD MODE - Post-Escape Query<br><br>User: $eMailAddress<br><br>User Exists: $userExists<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

		if($debugAdd) print "3 - Add Mode...<br>";

		if($debug) print "userExists: $userExists<br>";

		// I want mo info
		
		$creationDate	=	date("F j, Y, g:i:s a");                // March 10, 2001, 5:16:34 pm
		$ipAddress 		= 	gethostbyname($REMOTE_ADDR);
		$hostName 		= 	gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$mailingFlag	=	"New Record";
		$confirmed		=	"N";
		
		if($debug) print "mailingFlag: $mailingFlag<br>";
		if($debug) print "creationDate: $creationDate<br>";
		if($debug) print "ipAddress: $ipAddress<br>";
		if($debug) print "hostName: $hostName<br>";
		
		// set up the query to insert a new record
		$format			=	"
						INSERT INTO %s 
						(
						title,
						first_name,
						last_name,
						street_address,
						street_address_2,
						city,
						state,
						zip,
						email_address,
						password,
						subscribed1,
						subscribed2,
						subscribed3,
						ip_address,
						host_name,
						creation_date,
						confirmed,
						mailing_flag
						)  
						VALUES 
						( 	
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s'
						)"
						;


		if($debug) print "<br>-------------------------------------------------------------------------------------------------<br>";
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
		if($debug) print "sub1: $sub1<br>";
		if($debug) print "sub2: $sub2<br>";
		if($debug) print "sub3: $sub3<br>";
		if($debug) print "statusMessage: $statusMessage<br>";
		if($debug) print "itemNumber: $itemNumber<br>";
		if($debug) print "-------------------------------------------------------------------------------------------------<br>";
		
		// open and close as the escape string func
		// relies on the connection context for encoding.

		// -----------------------------------------------
		// Debug Instrumentation 
		// -----------------------------------------------
	
		DebugMessage("<br><br>ADD MODE - Pre-Escape Query<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);


		OpenDatabase();
		// this is a huggermugger ;/		
		$query 		= 	sprintf($format,
								$tableName,
								mysql_real_escape_string($title),	
								mysql_real_escape_string($firstName),	
								mysql_real_escape_string($lastName),	
								mysql_real_escape_string($address),	
								mysql_real_escape_string($address2),	
								mysql_real_escape_string($city),
								mysql_real_escape_string($state),
								mysql_real_escape_string($zip),
								mysql_real_escape_string($eMailAddress),
								mysql_real_escape_string($password),
								mysql_real_escape_string($s1),
								mysql_real_escape_string($s2),
								mysql_real_escape_string($s3),
								mysql_real_escape_string($ipAddress),
								mysql_real_escape_string($hostName),
								mysql_real_escape_string($creationDate),
								mysql_real_escape_string($confirmed),
								mysql_real_escape_string($mailingFlag)
								);
		CloseDatabase();

		if($debug) print "format: $format<br>";
		if($debug) print "query: $query<br>";

		// -----------------------------------------------
		// Debug Instrumentation 
		// -----------------------------------------------
	
		DebugMessage("<br><br>ADD MODE - Post-Escape Query<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

		// if the user is not in the database and the write enable flag is true
		if(!$userExists && $writeEnabled)
			{
			// -----------------------------------------------
			// Debug Instrumentation 
			// -----------------------------------------------
		
			DebugMessage("<br><br>ADD MODE - Pre Add Query<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

			OpenDatabase();

			$rv				=	1;
			
			$rv				=	QueryDatabase($query);
			
			if (!$rv) 
				{
				$statusMessage		=	mysql_error() . "<br>Query: $query";
				$error				=	1;
				}
			
			// success
			CloseDatabase();

			// -----------------------------------------------
			// Debug Instrumentation 
			// -----------------------------------------------
			
			$mysqlError		=	mysql_error();
			
			DebugMessage("<br><br>ADD MODE - Post Add Query<br><br>User: $eMailAddress<br><br>Query Result: $mysqlError<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

			// send a notification out to the sysadmin first
			$fromName			=	"[Website] " . $gSiteName . " Subscription Page";
			$subject			=	"$firstName $lastName has joined your mailing list";

			$mailMessage		=	GenerateMailingListMessage("../messages/message_sub_admin.html",$eMailAddress,$gMailerSalutation,"");

			if($debug) print "fromName: $fromName<br>";
			if($debug) print "subject: $subject<br>";
			if($debug) print "query: $query<br>";

			// signup step 1
			// send a terse message to the system admin
			// email_send($gSiteAdminAddress,$gMailerFromAddress,$fromName,$subject,$mailMessage);			
			// send the same to the site secretary
			email_send($gSiteSecretaryAddress,$gMailerFromAddress,$fromName,$subject,$mailMessage);			

			// -----------------------------------------------
			// Debug Instrumentation 
			// -----------------------------------------------
		
			DebugMessage("<br><br>ADD MODE - Post Send Admin Messages<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

			// set up and send the confirmation to the user
			$fromName			=	$gSiteName . " Subscription Page";
			$subject			=	"Please Confirm Your $gSiteName Subscription";
			// use the new file loader version
			$mailMessage		=	GenerateMailingListMessage("../messages/message_sub_confirm.html",$eMailAddress,$gMailerSalutation,"");
			
			// send the formatted message to the user
			email_send($eMailAddress,$gMailerFromAddress,$fromName,$subject,$mailMessage);			

			// echo the user message to the admin with the error and status messages
			if($debugMails)
				email_send($gSiteAdminAddress,$gMailerFromAddress,$fromName,"[DB] $subject","Error: $error<br>Message: $statusMessage<br>$mailMessage");			

			// -----------------------------------------------
			// Debug Instrumentation 
			// -----------------------------------------------
		
			DebugMessage("<br><br>ADD MODE - Post Send User Messages<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);
			} 						// 	if(GetNumberOfRows($query)==0)	// addr does not exist add it
		else	
			{
			$statusMessage		=	"This address already exists";
			$error				=	1;
			}
		
		if($debugAdd) print "4 - Add Mode...<br>";
		
		} // end of if ! error

	if(!$error)
		{
		// send back the status message only
		$statusMessage		=	"You have been added to our mailing list and will receive an E-Mail that will allow you to confirm your subscription. The confirmation E-Mail may end up in your junk folder. Add the email address to your address book to stop that from happening.";
		$args				=	"StatusMessage=$statusMessage";
		$caller				=	$gHomePage; // send home with success message
		}
	else
		{
		// Redirect back to the calling page with all args intact
		$args	=	GetMailSubParms();
		}
			
	// return to caller
	$returnPage					=	"$caller";
	$location					=	"../$returnPage?$args";		

	// -----------------------------------------------
	// Debug Instrumentation 
	// -----------------------------------------------

	DebugMessage("<br><br>ADD MODE - End<br><br>Redirects to: $location<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

	FormRedirect($location,$formMethod,$debug);
	} // End of Add Mode functionality

// ****************************************************************************************************
// ****************************************************************************************************
// ADMIN ADD MODE
//
// This is much more slutty then the one for the general public, it will be happy with a valid email 
// address
// 
// TESTS:
// VALID DOMAIN 
// WELL FORMED EMAIL ADDRESS
// ****************************************************************************************************
// ****************************************************************************************************

else if($mode=="AdminAdd")
	{
	if($debug) print "Admin Add Mode...<br>";

	$statusMessage	=	"";
	//$tableName		=	$mailTableName;

	if($debug) print "tableName: $tableName<br>";
	
	// Test to see if a required field was left blank, eMailAddress, eMailAddressConfirm, password, passwordConfirm
	// 1
	if(strlen($eMailAddress)==0)
		{
		$statusMessage		=	"Please enter an e-mail address";
		$error				=	1;
		}

	if($debug) print "statusMessage: $statusMessage<br>";
	if($debug) print "error: $error<br>";

	// Test checkboxes, one of the boxes must be checked to execute an Add operation
	// 7
	if(!$error)
		{
		if($sub1=="" && $sub2=="" && $sub3=="")
			{
			if($debug) print "sub1: $sub1<br>";
			if($debug) print "sub2: $sub2<br>";
			if($debug) print "sub3: $sub3<br>";
	
			$statusMessage		=	"Please check a list to join";
			$error				=	1;
			}
		}

	// test for valid domain and well formed email address
	if(!$error)
		$rv		=	validate_email($eMailAddress);

	
	// poorly formed address
	// 10
	if($rv == -1 && !$error)
		{
		$statusMessage		=	"The email address $eMailAddress is incorrectly formed";
		$error				=	1;
		}

	// invalid domain
	// 11
	if($rv == 0 && !$error)
		{
		$statusMessage		=	"The email address $eMailAddress has an invalid domain...";
		$error				=	1;
		}

	if($debug) print "Add Mode - Post Input Error Traps <br>error=$error<br>statusMessage=$statusMessage<br>";

	if(!$error)
		{
		// does it already exist ?

		// fix up the table name
		$query 	=	"SELECT * FROM " . $tableName . " WHERE email_address='" . $eMailAddress . "'";	

		if($debug) print "query: $query<br>";

		$userExists	=	GetNumberOfRows($query);

		if($debug) print "userExists: $userExists<br>";

		// I want mo info
		
		//$creationDate	=	date("F j, Y, g:i:s a");                // March 10, 2001, 5:16:34 pm
		$ipAddress 		= 	"Added by Admin";
		$hostName 		= 	"Added by Admin";
		$mailingFlag	=	"New Record";
		$confirmed		=	"Y";
		
		if($debug) print "mailingFlag: $mailingFlag<br>";
		if($debug) print "creationDate: $creationDate<br>";
		if($debug) print "ipAddress: $ipAddress<br>";
		if($debug) print "hostName: $hostName<br>";
		
		// set up the query to insert a new record
		$format			=	"
						INSERT INTO %s 
						(
						title,
						first_name,
						last_name,
						street_address,
						street_address_2,
						city,
						state,
						zip,
						email_address,
						password,
						subscribed1,
						subscribed2,
						subscribed3,
						ip_address,
						host_name,
						creation_date,
						confirmed,
						mailing_flag
						)  
						VALUES 
						( 	
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s'
						)"
						;


		if($debug) print "<br>-------------------------------------------------------------------------------------------------<br>";
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
		if($debug) print "sub1: $sub1<br>";
		if($debug) print "sub2: $sub2<br>";
		if($debug) print "sub3: $sub3<br>";
		if($debug) print "statusMessage: $statusMessage<br>";
		if($debug) print "itemNumber: $itemNumber<br>";
		if($debug) print "-------------------------------------------------------------------------------------------------<br>";
		
		// open and close as the escape string func
		// relies on the connection context for encoding.

		OpenDatabase();
		// this is a huggermugger ;/		
		$query 		= 	sprintf($format,
								$tableName,
								mysql_real_escape_string($title),	
								mysql_real_escape_string($firstName),	
								mysql_real_escape_string($lastName),	
								mysql_real_escape_string($address),	
								mysql_real_escape_string($address2),	
								mysql_real_escape_string($city),
								mysql_real_escape_string($state),
								mysql_real_escape_string($zip),
								mysql_real_escape_string($eMailAddress),
								mysql_real_escape_string($password),
								mysql_real_escape_string($s1),
								mysql_real_escape_string($s2),
								mysql_real_escape_string($s3),
								mysql_real_escape_string($ipAddress),
								mysql_real_escape_string($hostName),
								mysql_real_escape_string($creationDate),
								mysql_real_escape_string($confirmed),
								mysql_real_escape_string($mailingFlag)
								);
		CloseDatabase();

		if($debug) print "format: $format<br>";
		if($debug) print "query: $query<br>";

		// -----------------------------------------------
		// Debug Instrumentation 
		// -----------------------------------------------
	
		DebugMessage("<br><br>ADMIN ADD MODE - Post-Escape Query<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

		// if the user is not in the database and the write enable flag is true
		if(!$userExists && $writeEnabled)
			{
			OpenDatabase();

			$rv				=	1;
			
			$rv				=	QueryDatabase($query);
			
			if (!$rv) 
				{
				$statusMessage		=	mysql_error() . "<br>Query: $query";
				$error				=	1;
				}
			
			// success
			CloseDatabase();

			$mysqlError		=	mysql_error();
			
			if($debug) print "query: $query<br>";

			} 						// 	if(GetNumberOfRows($query)==0)	// addr does not exist add it
		else	
			{
			$statusMessage		=	"This address already exists";
			$error				=	1;
			}
		} // end of if ! error

	if(!$error)
		{
		// send back the login info only
		$args				=	"MailTableName=$mailTableName&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		}
	else
		{
		// Redirect back to the calling page with all args intact
		$args	=	GetMailSubParms();
		$args	.=	"&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		}
			
	// return to caller
	$returnPage					=	"$caller";
	$location					=	"$returnPage?$args";		// only called by admin add page

	FormRedirect($location,$formMethod,$debug);
	} // End of Admin Add Mode functionality

// ****************************************************************************************************
// ****************************************************************************************************
// MODIFY MODE
//
// This is coming from an end user interface that is exposed to the public
// ****************************************************************************************************
// ****************************************************************************************************

else if($mode=="Modify")
	{
	if($debug) print "Modify MODE<br>";

	// this will trap someone from claiming another records UID

	// fix up the table name
	//$tableName	=		"APPDEV_$gMailTableName";
	
	$query 	=	"SELECT * FROM " . $tableName . " WHERE email_address='" . $eMailAddress . "'";	

	if($debug) print "query: $query<br>";

	$userExists	=	GetNumberOfRows($query);

	if($debug) print "userExists: $userExists<br>";

	if($userExists)
		{
		// does someone else own this handle
		// if so the recno of the handle will be different from this one
		$recNum	=	GetFieldByComparison($tableName,"item_number","email_address",$eMailAddress);
		
		if($recNum != $itemNumber)
			{
			// there is another record with that email address
			$error				=	1;
			$statusMessage		=	"Cannot change to that e-mail address. There is another subscriber currently using it...";
			$returnPage			=	$gHomePage;
			}		
		}

	// if all is good modify the record		

	if(!$error)
		{
		$format			=	"
						UPDATE %s 
						SET
						title='%s',
						first_name='%s',
						last_name='%s',
						street_address='%s',
						street_address_2='%s',
						city='%s',
						state='%s',
						zip='%s',
						email_address='%s',
						password='%s',
						subscribed1='%s',
						subscribed2='%s',
						subscribed3='%s'
						WHERE
						item_number='%s'
						";
		
		// open and close as the escape string func
		// relies on the connection context for encoding.
	
		OpenDatabase();
		// this is a huggermugger ;/		
	
		$query 		= 	sprintf($format,
								$tableName,
								mysql_real_escape_string($title),	
								mysql_real_escape_string($firstName),	
								mysql_real_escape_string($lastName),	
								mysql_real_escape_string($address),	
								mysql_real_escape_string($address2),	
								mysql_real_escape_string($city),
								mysql_real_escape_string($state),
								mysql_real_escape_string($zip),
								mysql_real_escape_string($eMailAddress),
								mysql_real_escape_string($password),
								mysql_real_escape_string($s1),
								mysql_real_escape_string($s2),
								mysql_real_escape_string($s3),
								mysql_real_escape_string($itemNumber)
								);
	
		$rv	=	QueryDatabase($query);
	
		if($rv == 1)
			{
			$statusMessage		=	"Subscription for $eMailAddress modified.";
			}
		else
			{
			$statusMessage		=	"Subscription for $eMailAddress NOT modified.";
			$error				=	1;
			}	
	
		CloseDatabase();
	
		if(!$error)
			{
			// send the sys admin a mail regarding the update
			$fromName			=	"[Website] " . $gSiteName . " Subscription Page";
			$subject			=	"[Subscription Update]$title $firstName $lastName has updated their information";
			$mailMessage		=	GenerateMailingListMessage("../messages/message_sub_modify.html",$eMailAddress,$gMailerSalutation,"");
	
			if($debug) print "fromName: $fromName<br>";
			if($debug) print "subject: $subject<br>";
			if($debug) print "query: $query<br>";
	
			// send a terse message to the system admin
			email_send($gSiteAdminAddress,$gMailerFromAddress,$fromName,$subject,$mailMessage);			
			email_send($gSiteSecretaryAddress,$gMailerFromAddress,$fromName,$subject,$mailMessage);			
			}


		$returnPage					=	"mail_sub_manage.php";
		} // end of if(!$error) from far above
	
	if($debug) print "format: $format<br><br>";
	if($debug) print "query: $query<br>";
	if($debug) print "rv: $rv<br>";
	if($debug) print "statusMessage: $statusMessage<br>";

	$args						=	"?Password=$password&E-MailAddress=$eMailAddress&StatusMessage=$statusMessage";
	$location					=	"../$returnPage$args";		

	FormRedirect($location,$formMethod,$debug);
	}

// ****************************************************************************************************
// ****************************************************************************************************
// LOST PASSWORD MODE
// ****************************************************************************************************
// ****************************************************************************************************

else if($mode=="LostPassword")
	{
	if($debug) print "Lost Password...<br>";
	// does the user exist
	// get the pass
	// send the message

	$error	=	0;	// 0 if no error 1 if error

	$query 		=	"SELECT * FROM " . $tableName . " WHERE email_address='" . $eMailAddress . "'";	

	if($debug) print "query: $query<br>";

	$userExists	=	GetNumberOfRows($query);

	// does user exist ?
	if($userExists==0)
		{
		// User does not exist
		$statusMessage	= 	"The user $eMailAddress does not exist.";
		$error			=	1;
		}
	else
		{
		// get the password
		$password	=	GetFieldByComparison($tableName,"password","email_address",$eMailAddress);
		$sub1		=	GetFieldByComparison($gMailTableName,"subscribed1","email_address",$eMailAddress);
		$sub2		=	GetFieldByComparison($gMailTableName,"subscribed2","email_address",$eMailAddress);
		$sub3		=	GetFieldByComparison($gMailTableName,"subscribed3","email_address",$eMailAddress);
		$name		=	GetFieldByComparison($gMailTableName,"name","email_address",$eMailAddress);
		
		// get the email
		$toAddress	=	$eMailAddress;
	
		$subject	=	"[Password Recovery] Your Password for $gSiteName";  
	
		$fromName	=	"$gSiteName Website";
	
		// jank up a url for the sub manager	
		$subManagerPage	=	$gSiteURL . $gBaseMountPoint . "mail_login.php?E-MailAddress=$eMailAddress";

		$message		=	GenerateMailingListMessage("../messages/message_sub_lost_pass.html",$eMailAddress,$gMailerSalutation,"");

		if(email_send($toAddress,$gMailerFromAddress,$fromName,$subject,$message)==1)
			{
			$error			=	0;
			$statusMessage	=	"The password for this subscription has been sent to: $toAddress";
			}
		else
			{
			$error	=	1;
			$statusMessage	=	"Error - Unable to Send Mail, Please Contact System Administrator";
			}
		} // if(strcmp($mode,"LostPassword")==0)

	$targetURL		=	"../" . $gHomePage . '?StatusMessage=' . $statusMessage;

	if($debug) print "returnPage: $returnPage<br>";
	if($debug) print "tableName: $tableName<br>";
	if($debug) print "password: $password<br>";
	if($debug) print "toAddress: $toAddress<br>";
	if($debug) print "gMailerFromAddress: $gMailerFromAddress<br>";
	if($debug) print "subject: $subject<br>";
	if($debug) print "message: $message<br>";
	if($debug) print "error: $error<br>";
	if($debug) print "statusMessage: $statusMessage<br>";
	if($debug) print "targetURL: $targetURL<br>";
		
	FormRedirect($targetURL,$formMethod,$debug);
	}

// ****************************************************************************************************
// ****************************************************************************************************
// CONFIRM MODE
// ****************************************************************************************************
// ****************************************************************************************************

else if($mode=="Confirm")
	{
	if($debug) print "Confirm<br>";

	// -----------------------------------------------
	// Debug Instrumentation 
	// -----------------------------------------------

	DebugMessage("<br><br>CONFIRM MODE - Start<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

	// does user exist, this will only happen if someone is hacking the interface
	$query			=	"SELECT * FROM $tableName WHERE email_address='$eMailAddress'";

	if($debug) print "query: $query<br>";

	if(GetNumberOfRows($query)==0)
		{
		$statusMessage	=	"The user $eMailAddress does not exist";
		$error			=	1;
		}

	// -----------------------------------------------
	// Debug Instrumentation 
	// -----------------------------------------------

	DebugMessage("<br><br>CONFIRM MODE - Post Test User Exist<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

	if(!$error)
		{
		$query			=	'UPDATE ' . $tableName . ' SET confirmed ="Y"' . '  WHERE email_address ="' . $eMailAddress . '"';
	
		if($debug) print "query: $query<br>";
	
		$rv				=	1;
		
		// -----------------------------------------------
		// Debug Instrumentation 
		// -----------------------------------------------
	
		DebugMessage("<br><br>CONFIRM MODE - Pre Confirm Query<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

		OpenDatabase();
	
		$rv				=	QueryDatabase($query);
	
		CloseDatabase();
		
		if (!$rv) 
			{
			$statusMessage		=	mysql_error() . "Error-Subscription Confirmation<br>Query: $query";
			$error				=	1;
			}
		else
			{
			$error				=	0;
			$statusMessage		=	"Thanks for signing up for a subscription";
			}
		}

		// -----------------------------------------------
		// Debug Instrumentation 
		// -----------------------------------------------
	
		DebugMessage("<br><br>CONFIRM MODE - Post Confirm Query<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);
		
		if($debug) print "error: $error<br>";
		if($debug) print "statusMessage: $statusMessage<br>";

	
	// if all good send welcome email
	if(!$error)
		{
		// send a notification out
		$fromName			=	"$gSiteName";
		$subject			=	"Welcome to  Your $gSiteName Subscription";
		$mailMessage		=	GenerateMailingListMessage("../messages/message_sub_welcome.html",$eMailAddress,$gMailerSalutation,"");

		if($debug) print "fromName: $fromName<br>";
		if($debug) print "subject: $subject<br>";
		if($debug) print "mailMessage: $mailMessage<br>";

		email_send($eMailAddress,$gMailerFromAddress,$fromName,$subject,$mailMessage);			

		// -----------------------------------------------
		// Debug Instrumentation 
		// -----------------------------------------------
	
		DebugMessage("<br><br>CONFIRM MODE - Post Send Welcome Message<br><br>User: $eMailAddress<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

		if($debugMails)
			{
			// echo the users mail to the admin
			email_send($gSiteAdminAddress,$gMailerFromAddress,$fromName,"[DB] $subject",$mailMessage);			

			// send a notification out to the sysadmin first
			$fromName			=	"[Website] " . $gSiteName . " Subscription Page";
			$firstName		=	GetFieldByComparison($tableName,"first_name","email_address",$eMailAddress);
			$lastName		=	GetFieldByComparison($tableName,"last_name","email_address",$eMailAddress);
	
			if(!$error)
				$subject			=	"[DB]$firstName $lastName has confirmed";
			else
				$subject			=	"[DB]$firstName $lastName has confirm error";
				
			$mailMessage		=	"Error Status: $error<br>Message: $statusMessage";
			
			if($debug) print "fromName: $fromName<br>";
			if($debug) print "subject: $subject<br>";
			if($debug) print "query: $query<br>";
	
			// signup step 1
			// send a terse message to the system admin
			email_send($gSiteAdminAddress,$gMailerFromAddress,$fromName,$subject,$mailMessage);			
			}
		}	

	$targetURL		=	"../" . $gHomePage . '?StatusMessage=' . $statusMessage;

	// -----------------------------------------------
	// Debug Instrumentation 
	// -----------------------------------------------

	DebugMessage("<br><br>CONFIRM MODE - End<br><br>User: $eMailAddress<br><br>Redirects to: $targetURL<br><br>Status: $statusMessage<br><br>Error: $error<br><br>$mailSubArgs<br><br>",$debugLog,__LINE__,__FILE__,__FUNCTION__);

	FormRedirect($targetURL,$formMethod,$debug);
	}

// ****************************************************************************************************
// ****************************************************************************************************
// OPT OUT MODE
// ****************************************************************************************************
// ****************************************************************************************************

else if($mode=="OptOut")
	{
	if($debug) print "Opt Out Mode...<br>";

	// does user exist, this will only happen if someone is hacing the interface

	$query			=	"SELECT * FROM $tableName WHERE email_address=" .  '"' . $eMailAddress . '"';

	if($debug) print "query: $query<br>";

	if(GetNumberOfRows($query)==0)
		{
		$statusMessage	=	"The user $eMailAddress does not have a subscription";
		$error			=	1;
		}

	if(!$error)
		{
		$query			=	'UPDATE ' . $tableName . ' SET subscribed1 ="N", subscribed2 ="N", subscribed3 ="N"' . '  WHERE email_address ="' . $eMailAddress . '"';
	
		if($debug) print "query: $query<br>";
	
		$rv				=	1;
		
		OpenDatabase();
	
		$rv				=	QueryDatabase($query);
	
		CloseDatabase();
		
		if (!$rv) 
			{
			$statusMessage		=	mysql_error() . "Error-Subscription Opt-Out<br>Query: $query";
			$error				=	1;
			}
		else
			{
			$error				=	0;
			$statusMessage		=	"Your subscription has been cancelled. You will receive an e-mail confirmation. We are sorry to see you go...";
			}


		// if all good send a kiss off message
		if(!$error)
			{
			if($debug) print "Sending User Mail...<br>";

			// send a notification out
			$fromName			=	"$gSiteName";
			$subject			=	"OptOut from $gSiteName Subscription(s)";
			$mailMessage		=	GenerateMailingListMessage("../messages/message_sub_optout.html",$eMailAddress,$gMailerSalutation,"");
	
			if($debug) print "fromName: $fromName<br>";
			if($debug) print "subject: $subject<br>";
			if($debug) print "mailMessage: $mailMessage<br>";
	
			email_send($eMailAddress,$gMailerFromAddress,$fromName,$subject,$mailMessage);			
			}	
		else
			{
			if($debug) print "error: $error<br>";
			if($debug) print "statusMessage: $statusMessage<br>";
			}
				
		$targetURL		=	"../" . $gHomePage . '?StatusMessage=' . $statusMessage;
	
		if($debug) print "targetURL: $targetURL<br>";
			
		FormRedirect($targetURL,$formMethod,$debug);
		}
	}
else
	exit("Incorrect Argument for Mode: " . $mode);	

// --------------------------------------------------------------------------------------------------------------------------------------
// ending condition
// --------------------------------------------------------------------------------------------------------------------------------------


if($debug2) print "Args: $args<br>";
if($debug2) print "Message: $statusMessage<br>";
if($debug2) print "Error: $error<br>";
if($debug2) print "location: $location<br>";
?> 