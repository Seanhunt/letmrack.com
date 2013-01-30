<?php
// -----------------------------------------------------------------------------------------------
// main_sub_edit.php
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
include 'admin_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'html_lib.php';	
include "name_value_pair_lib.php";
include 'mail_lib.php';
include 'mail_sub_parms.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'redirect.php';
include 'appdev_revision.php';	
include 'file_lib.php';

$debug	=	0;

// -----------------------------------------------------------------------------------------------
// Authorize the user and check the role
// -----------------------------------------------------------------------------------------------

if(AuthorizeAdminUser($systemUserID,$systemPassword)!=1)
	{
	print "Access Denied<br>";
	exit(0);
	}


$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";

if($mailTableName == "")		
	$mailTableName		=	$gMailDatabaseName;

$numberOfAddresses	=	GetNumberOfRows("SELECT * FROM $mailTableName");

if($debug) print "mailTableName: $mailTableName<br>";
if($debug) print "numberOfAddresses: $numberOfAddresses<br>";
if($debug) print "mode: $mode<br>";


if($mode=="Modify")
	{
	if($debug) print "Modify Mode - Update Record<br>";
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
								mysql_real_escape_string($sub1),
								mysql_real_escape_string($sub2),
								mysql_real_escape_string($sub3),
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

	$mode	=	"Go";	// get a refresh of the updated data
	}
else if($mode=="Go")
	{
	if($debug) print "<hr>Edit Mode - Recall Record<br>";
	if($debug) print "Edit Mode - itemNumber: $itemNumber<br>";

	$eMailAddress	=	GetFieldByComparison($mailTableName,"email_address","item_number",$itemNumber);
	$sub1			=	GetFieldByComparison($mailTableName,"subscribed1","item_number",$itemNumber);
	$sub2			=	GetFieldByComparison($mailTableName,"subscribed2","item_number",$itemNumber);
	$sub3			=	GetFieldByComparison($mailTableName,"subscribed3","item_number",$itemNumber);
	$fullName		= 	GetFieldByComparison($mailTableName,"name","item_number",$itemNumber);
	$title			= 	GetFieldByComparison($mailTableName,"title","item_number",$itemNumber);
	$firstName		= 	GetFieldByComparison($mailTableName,"first_name","item_number",$itemNumber);
	$lastName		= 	GetFieldByComparison($mailTableName,"last_name","item_number",$itemNumber);
	$ipAddress		= 	GetFieldByComparison($mailTableName,"ip_address","item_number",$itemNumber);
	$hostName		= 	GetFieldByComparison($mailTableName,"host_name","item_number",$itemNumber);
	$creationDate	= 	GetFieldByComparison($mailTableName,"creation_date","item_number",$itemNumber);
	$mailingFlag	= 	GetFieldByComparison($mailTableName,"mailing_flag","item_number",$itemNumber);
	$address		= 	GetFieldByComparison($mailTableName,"street_address","item_number",$itemNumber);
	$address2		= 	GetFieldByComparison($mailTableName,"street_address_2","item_number",$itemNumber);
	$city			= 	GetFieldByComparison($mailTableName,"city","item_number",$itemNumber);
	$state			= 	GetFieldByComparison($mailTableName,"state","item_number",$itemNumber);
	$zip			= 	GetFieldByComparison($mailTableName,"zip","item_number",$itemNumber);
	$password		= 	GetFieldByComparison($mailTableName,"password","item_number",$itemNumber);
	$confirmed		= 	GetFieldByComparison($mailTableName,"confirmed","item_number",$itemNumber);

	if($debug) print "<hr>";
	if($debug) print "Fetch Record: $itemNumber<br><br>";
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
	if($debug) print "confirmed: $confirmed<br>";
	if($debug) print "sub1: $sub1<br>";
	if($debug) print "sub2: $sub2<br>";
	if($debug) print "sub3: $sub3<br>";
	if($debug) print "itemNumber: $itemNumber<br>";
	if($debug) print "<hr>";
	}


?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Edit Mailing List</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle" class="admin18Bold">Edit Mailing 
      List </td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#MailListEdit" target="_blank" class="galleryUILink">Help</a></td>
        <td width="33%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <form action="mail_sub_edit.php" method="GET" name="SetTable" id="SetTable">
        <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td colspan="2" bgcolor="#CCCCCC">&nbsp;</td>
          </tr>
          <tr align="center"> 
            <td width="62%" height="40" class="admin12Bold"> Select a Mailing 
              List to Edit - 
              <?php
					
					$labels	=	array();
					$values	=	array();
					
					$labels[]	=	"Live";
					$labels[]	=	"Test";
					
					$values[]	=	$gMailDatabaseName;
					$values[]	=	$gTestMailDatabaseName;
					
					DisplaySelector("MailTableName",$labels,$values,$mailTableName);
					?>
            </td>
            <td width="38%"> <input name="SetTable" type="submit" id="SetTable" value="Set"></td>
          </tr>
          <tr> 
            <td colspan="2" bgcolor="#CCCCCC">&nbsp;</td>
          </tr>
        </table>
        <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
        <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
        <input name="Mode" type="hidden" id="Mode" value="SetTable">
      </form> 
      <form name="form1" method="GET" action="mail_sub_edit.php">
        <table width="90%" border="1" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
          <tr align="center"> 
            <td height="40" colspan="2" bgcolor="#666666" class="admin12Bold"> 
              <input name="Mode" type="submit" id="BMA_23" value="Modify">
            </td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Address To Edit</td>
            <td class="admin12Regular"> 
              <?php
					$numberOfItems	=	GetNumberOfRows("SELECT * FROM $mailTableName");
					
					if($debug) print "numberOfItems: $numberOfItems<br>";
					if($debug) print "mailTableName: $mailTableName<br>";

					OpenDatabase();
					
					$query			=	"SELECT email_address, item_number FROM $mailTableName ORDER BY email_address";
	
					$rs				=	QueryDatabase($query);

					CloseDatabase();

					$values	=	array();
					$labels	=	array();

					$values[]		=	"None";
					$labels[]		=	"Pick an Address and Hit Go...";

					for($i=0;$i<$numberOfItems;$i++)
						{
						$values[]		=	mysql_result($rs,$i,"item_number");
						$labels[]		=	mysql_result($rs,$i,"email_address");
						}

					if($itemNumber == "")
						$itemNumber	=	"New";

					DisplaySelector("ItemNumber",$labels,$values,$itemNumber);
					?>
              &nbsp; 
              <input name="Mode" type="submit" id="Mode" value="Go"> </td>
          </tr>
          <tr valign="middle"> 
            <td width="25%" height="40" class="admin12Bold">E-Mail Address</td>
            <td width="73%" class="admin12Regular"> 
              <input name="E-MailAddress" type="text" id="E-MailAddress2" value="<?php print $eMailAddress; ?>" size="60"></td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold"> 
              <?php
				  print GetMailingListName(1);
				  ?>
            </td>
            <td class="admin12Regular"> 
              <input name="Sub1" type="checkbox" id="Sub12" value="Y" <?php if(strcmp($sub1,"Y")==0) print " checked"; ?>> 
              <?php
				  print GetMailingListDescription(1);
				  ?>
            </td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold"> 
              <?php
				  print GetMailingListName(2);
				  ?>
            </td>
            <td class="admin12Regular"> 
              <input name="Sub2" type="checkbox" id="Sub22" value="Y"<?php if(strcmp($sub2,"Y")==0) print " checked"; ?>> 
              <?php
				  print GetMailingListDescription(2);
				  ?>
            </td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold"> 
              <?php
				  print GetMailingListName(3);
				  ?>
            </td>
            <td class="admin12Regular"> 
              <input name="Sub3" type="checkbox" id="Sub32" value="Y"<?php if(strcmp($sub3,"Y")==0) print " checked"; ?>> 
              <?php
				  print GetMailingListDescription(3);
				  ?>
            </td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Title:</td>
            <td class="admin12Regular"> 
              <input name="Title" type="text" id="Title" value="<?php print $title; ?>" size="60"></td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">First Name:</td>
            <td class="admin12Regular"> 
              <input name="FirstName" type="text" id="FirstName2" value="<?php print $firstName; ?>" size="60"></td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Last Name:</td>
            <td class="admin12Regular"> 
              <input name="LastName" type="text" id="LastName" value="<?php print $lastName; ?>" size="60"></td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Street Address:</td>
            <td class="admin12Regular"> 
              <input name="Address" type="text" id="Address3" value="<?php print $address; ?>" size="60"></td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Street Address 2:</td>
            <td class="admin12Regular"> 
              <input name="Address2" type="text" id="Address22" value="<?php print $address2; ?>" size="60"></td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">City:</td>
            <td class="admin12Regular"> 
              <input name="City" type="text" id="City" value="<?php print $city; ?>" size="60"></td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">State:</td>
            <td class="admin12Regular"> 
              <input name="State" type="text" id="State2" value="<?php print $state; ?>" size="2" maxlength="2"></td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Zip:</td>
            <td class="admin12Regular"> 
              <input name="Zip" type="text" id="Zip2" value="<?php print $zip; ?>" size="10" maxlength="10"></td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Password:</td>
            <td class="admin12Regular"> 
              <input name="Password" type="text" id="Password2" value="<?php print $password; ?>" size="40"></td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Confirmed:</td>
            <td class="admin12Regular"> 
              <input name="Confirmed" type="text" id="Confirmed2" value="<?php print $confirmed; ?>" size="1" maxlength="1"></td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">IP Address:</td>
            <td class="admin12Regular"><?php print $ipAddress; ?> &nbsp;</td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Host Name:</td>
            <td class="admin12Regular"><?php print $hostName; ?> &nbsp;</td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Creation date:</td>
            <td class="admin12Regular"><?php print $creationDate; ?> &nbsp;</td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Mailing Flag:</td>
            <td class="admin12Regular"><?php print $mailingFlag; ?> &nbsp;</td>
          </tr>
          <tr valign="middle"> 
            <td height="40" class="admin12Bold">Item Number</td>
            <td class="admin12Regular"><?php print $itemNumber; ?> &nbsp;</td>
          </tr>
          <tr align="center"> 
            <td height="35" colspan="2" class="admin12Bold"> 
              <input name="SystemPassword" type="hidden" id="SystemPassword4" value="<?php print $systemPassword; ?>"> 
              <input name="SystemUserID" type="hidden" id="SystemUserID4" value="<?php print $systemUserID; ?>"> 
              <input name="MailTableName" type="hidden" id="SystemPassword5" value="<?php print $mailTableName; ?>"> 
            </td>
          </tr>
        </table>
        </form> </td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td height="40" align="left" valign="middle" bgcolor="#EEEEEE">
      <?php
	PrintUserMessage($systemUserID);
	?>
    </td>
  </tr>
  <tr> 
    <td align="left" valign="top"> 
      <?php
	PrintAdminFooter();
	?>
    </td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>
