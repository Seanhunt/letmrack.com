
<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// mail_sub_manage.php
//
// Subscription Management for mail subsystem
// 
// Revision:	
//				111906	Added new fields 
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// --------------------------------------------------------------------------------------------------------------------------------------

include 'local_info.php';	
include 'RHS/db_lib.php';
include 'RHS/cl_lib.php';
include 'RHS/file_lib.php';
include 'RHS/counter_lib.php';
include 'RHS/html_lib.php';
include 'RHS/status_message.php';	
include 'RHS/seo_lib.php';	
include 'RHS/ui_lib.php';	
include 'RHS/chron_lib.php';	
include 'RHS/appdev_revision.php';	
include 'RHS/mail_lib.php';
include 'RHS/pw_parms.php';
include 'RHS/pw_lib.php';
include "RHS/mail_sub_parms.php"; 
include "RHS/select_controls.php"; 



$debug	=	0;
$error	=	0;

$mailTableName			=	"$gMailDatabaseName";

$modifyAction			=	$_GET["BMA_2"]; 

if($debug) print "modifyAction: $modifyAction<br>";
if($debug) print "mailTableName: $mailTableName<br>";
if($debug) print "eMailAddress: $eMailAddress<br>";

$query					=	"SELECT * FROM $mailTableName WHERE email_address=" . "'" . $eMailAddress . "'";

if($debug) print "query: $query<br>";
if($debug) print "modifyAction: $modifyAction<br>";

if(!GetNumberOfRows($query))
	{
	if($debug) print "USER DOES NOT EXIST<br>";

	$error	=	1;
	$statusMessage			=	"Subscription info for $eMailAddress not found.";
	}
else
	if($debug) print "USER EXISTS<br>";
	
// if user exista and it is not call to mod record
if(!$error && strlen($modifyAction)==0)
	{
	if($debug) print "GET USER DATA...<br>";
	// get record number
	$itemNumber		=	GetFieldByComparison($mailTableName,"item_number","email_address",$eMailAddress);
	// get the rest by recnum
	$eMailAddress	=	GetFieldByComparison($mailTableName,"email_address","item_number",$itemNumber);
	$sub1			=	GetFieldByComparison($mailTableName,"subscribed1","item_number",$itemNumber);
	$sub2			=	GetFieldByComparison($mailTableName,"subscribed2","item_number",$itemNumber);
	$sub3			=	GetFieldByComparison($mailTableName,"subscribed3","item_number",$itemNumber);

	$title			= 	GetFieldByComparison($mailTableName,"title","item_number",$itemNumber);

	$firstName		= 	GetFieldByComparison($mailTableName,"first_name","item_number",$itemNumber);
	$lastName		= 	GetFieldByComparison($mailTableName,"last_name","item_number",$itemNumber);

	$address		= 	GetFieldByComparison($mailTableName,"street_address","item_number",$itemNumber);
	$address2		= 	GetFieldByComparison($mailTableName,"street_address_2","item_number",$itemNumber);

	$city			= 	GetFieldByComparison($mailTableName,"city","item_number",$itemNumber);
	$state			= 	GetFieldByComparison($mailTableName,"state","item_number",$itemNumber);
	$zip			= 	GetFieldByComparison($mailTableName,"zip","item_number",$itemNumber);
	$password		= 	GetFieldByComparison($mailTableName,"password","item_number",$itemNumber);
	$confirmed		= 	GetFieldByComparison($mailTableName,"confirmed","item_number",$itemNumber);

	if($debug) print "itemNumber: $itemNumber<br>";
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
	if($debug) print "confirmed: $confirmed<br>";
	if($debug) print "sub1: $sub1<br>";
	if($debug) print "sub2: $sub2<br>";
	if($debug) print "sub3: $sub3<br>";

	// set up the subscription messages
	if(strcmp($sub1,"Y")==0)
		$messageOne	=	"You are currently subscribed to this list.";
	else
		$messageOne	=	"You are not subscribed to this list.";
			
	if(strcmp($sub2,"Y")==0)
		$messageTwo	=	"You are currently subscribed to this list.";
	else
		$messageTwo	=	"You are not subscribed to this list.";

	if(strcmp($sub3,"Y")==0)
		$messageThree	=	"You are currently subscribed to this list.";
	else
		$messageThree	=	"You are not subscribed to this list.";
	}




$pageCounterName	=	"MailSubManager";	// the name this page will appear as in the counters
$pageTitle			=	"My Mail Account";	// the title of the page
$showCounter		=	true;				// bool show or hide the counter
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Manage My Subscription</title>
<meta name="robots" content="noindex,nofollow">
<meta name="robots" content="noarchive">
<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
<META NAME="GOOGLEBOT" CONTENT="nofollow">
<META NAME="GOOGLEBOT" CONTENT="noindex">
<link href="images/css/local.css" rel="stylesheet" type="text/css">
</head>
<body>
<!--- Include for Tool Tips ******************************* --->
<script type="text/javascript" src="wz_tooltip.js"></script>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle">
      <?php
	PrintBannerUI();
	?>
    </td>
  </tr>
  <tr> 
    <td height="50" align="left" valign="middle"> 
      <!--Start Page Content -->
	  <hr>
      <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="50" class="rhs18Bold">Modify Your Subscription Preferences</td>
        </tr>
        <tr>
          <td>
<form name="form1" method="GET" action="RHS/mail_sub_services.php">
                    <br>
                    <table width="100%" border="2" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF">
                      <tr> 
                        <td align="center" valign="top" bgcolor="#FFFFFF" class="rhs12Bold"> 
                          <br> <table width="90%" border="1" cellpadding="5" cellspacing="0">
                            <tr> 
                              <td width="25%" class="rhs12Bold">E-Mail Address</td>
                              <td width="73%" class="rhs12Bold"> <input name="E-MailAddress" type="text" id="E-MailAddress" value="<?php print $eMailAddress; ?>" size="60"></td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold"> 
                                <?php
				  print GetMailingListName(1);
				  ?>
                              </td>
                              <td class="rhs12Bold"> <input name="Sub1" type="checkbox" id="Sub1" value="Y" <?php if(strcmp($sub1,"Y")==0) print " checked"; ?>> 
                                <?php
				  print GetMailingListDescription(1);
				  ?>
                              </td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold"> 
                                <?php
				  print GetMailingListName(2);
				  ?>
                              </td>
                              <td class="rhs12Bold"> <input name="Sub2" type="checkbox" id="SubTwo" value="Y"<?php if(strcmp($sub2,"Y")==0) print " checked"; ?>> 
                                <?php
				  print GetMailingListDescription(2);
				  ?>
                              </td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold"> 
                                <?php
				  print GetMailingListName(3);
				  ?>
                              </td>
                              <td class="rhs12Bold"> <input name="Sub3" type="checkbox" id="Sub3" value="Y"<?php if(strcmp($sub3,"Y")==0) print " checked"; ?>> 
                                <?php
				  print GetMailingListDescription(3);
				  ?>
                              </td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold">Title</td>
                              <td class="rhs12Bold"> 
                                <?php
				  
				  DisplayTitleSelector($title);
				  ?>
                              </td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold">First Name</td>
                              <td class="rhs12Bold"> <input name="FirstName" type="text" id="AddAddress4" value="<?php print $firstName; ?>" size="60"></td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold">Last Name</td>
                              <td class="rhs12Bold"> <input name="LastName" type="text" id="Name" value="<?php print $lastName; ?>" size="60"></td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold">Street Address</td>
                              <td class="rhs12Bold"> <input name="Address" type="text" id="Address" value="<?php print $address; ?>" size="60"></td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold">Address Line 2</td>
                              <td class="rhs12Bold"> <input name="Address2" type="text" id="Address2" value="<?php print $address2; ?>" size="60"></td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold">City</td>
                              <td class="rhs12Bold"> <input name="City" type="text" id="City" value="<?php print $city; ?>" size="60"></td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold">State</td>
                              <td class="rhs12Bold"> 
                                <?php
				  DisplayStateSelector($state);
				  ?>
                              </td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold">Zip</td>
                              <td class="rhs12Bold"> <input name="Zip" type="text" id="Zip" value="<?php print $zip; ?>" size="10" maxlength="10"></td>
                            </tr>
                            <tr> 
                              <td class="rhs12Bold">Password</td>
                              <td class="rhs12Bold"> <input name="Password" type="text" id="Password2" value="<?php print $password; ?>"></td>
                            </tr>
                          </table>
                          <p> 
                            <input name="Mode" type="hidden" id="Mode" value="Modify">
                            <input name="ItemNumber" type="hidden" id="ItemNumber" value="<?php print $itemNumber; ?>">
                            <input name="BMA_2" type="submit" id="BMA_2" value="Modify Subscription">
                          </p></td>
                      </tr>
                    </table>
                  </form>
                  
            <div align="center"><a class="boldLink14" href="<?php print "$gHomePage"; ?>">Visit 
              <?php print "$gSiteName"; ?></a></div>
            </td>
        </tr>
      </table>
      <hr>
      <!--End Page Content -->
    </td>
  </tr>
  <tr> 
    <td height="40" align="center" valign="middle" > 
      <h5>
        <!--Start Counter -->
        
        <?php
		if($showCounter) print "Page Views: "; 
		Counter($pageCounterName,$showCounter);
		?>
        <!--End Counter -->
      </h5></td>
  </tr>
  <tr>
    <td height="40" align="center" valign="middle" > 
      <!--Start Footer Content -->
      <?php
		PrintFooterUI();	// show the site specific footer or footer UI
		?>
      <!--End Footer Content -->
    </td>
  </tr>
  <tr> 
    <td height="40" align="left" valign="middle" > 
      <!--Start SEO Text -->
      <h5>
	  <?php
		PrintSEODescription(); // display the SEO descriptive text
		?>
      <!--End SEO Text -->
      </h5>
    </td>
  </tr>
</table>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage); // show a ststus message alert if called for
?>




