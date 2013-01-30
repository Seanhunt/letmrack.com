<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

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
include 'file_lib.php';	
include 'select_controls.php';	
include 'redirect.php';
include 'appdev_revision.php';	

$debug	=	0;

// -----------------------------------------------------------------------------------------------
// Authorize the user 
// -----------------------------------------------------------------------------------------------

if(AuthorizeAdminUser($systemUserID,$systemPassword)!=1)
	{
	print "Access Denied<br>";
	exit(0);
	}
	
$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";


if($tableName=="")
	$tableName	=	"$gMailDatabaseName";

if($mailTableName!="")
	$tableName	=	"$mailTableName";


if($debug) print "mailTableName: $mailTableName<br>";
if($debug) print "tableName: $tableName<br>";

$creationDate	=	date("Y-d-m");

if($password == "")
	$password	=	GenerateFriendlyPassword();

if($sub1=="")
	$sub1	=	GetMailingListIsChecked(1)?"Y":"N";

if($sub2=="")
	$sub2	=	GetMailingListIsChecked(2)?"Y":"N";

if($sub3=="")
	$sub3	=	GetMailingListIsChecked(3)?"Y":"N";
?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Add to Mailing List</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle" class="admin18Bold">Add To 
      Mailing List </td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#MailListAdd" target="_blank" class="galleryUILink">Help</a></td>
        <td width="33%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr> 
    <td height="40" align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"><form name="form1" method="GET" action="mail_sub_services.php">
        <table width="90%" border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
          <tr> 
            <td width="26%" height="30" valign="middle" bgcolor="#CCCCCC" class="admin12Bold">&nbsp;&nbsp;Table</td>
            <td width="74%" class="<?php print $messageClass ?>"> &nbsp;&nbsp; 
              <?php
			$labels	=	array();
			$values	=	array();
			
			$values[]	=	$gMailDatabaseName;
			$values[]	=	$gTestMailDatabaseName;
				
			$labels[]	=	"Live";
			$labels[]	=	"Test";
			
			DisplaySelector("MailTableName",$labels,$values,$tableName);
			?>
              &nbsp; </td>
          </tr>
        </table>
        <table width="90%" border="1" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
          <tr> 
            <td colspan="2" bgcolor="#999999" class="admin12Bold">&nbsp;</td>
          </tr>
          <tr> 
            <td width="25%" class="admin12Bold">E-Mail Address</td>
            <td width="73%" class="admin12Regular"> <input name="E-MailAddress" type="text" id="E-MailAddress3" value="<?php print $eMailAddress; ?>" size="60"></td>
          </tr>
          <tr> 
            <td class="admin12Bold"> 
              <?php
				  print GetMailingListName(1);
				  ?>
            </td>
            <td class="admin12Regular"> <input name="Sub1" type="checkbox" id="Sub13" value="Y" <?php if(strcmp($sub1,"Y")==0) print " checked"; ?>> 
              <?php
				  print GetMailingListDescription(1);
				  ?>
            </td>
          </tr>
          <tr> 
            <td class="admin12Bold"> 
              <?php
				  print GetMailingListName(2);
				  ?>
            </td>
            <td class="admin12Regular"> <input name="Sub2" type="checkbox" id="Sub23" value="Y"<?php if(strcmp($sub2,"Y")==0) print " checked"; ?>> 
              <?php
				  print GetMailingListDescription(2);
				  ?>
            </td>
          </tr>
          <tr> 
            <td class="admin12Bold"> 
              <?php
				  print GetMailingListName(3);
				  ?>
            </td>
            <td class="admin12Regular"> <input name="Sub3" type="checkbox" id="Sub33" value="Y"<?php if(strcmp($sub3,"Y")==0) print " checked"; ?>> 
              <?php
				  print GetMailingListDescription(3);
				  ?>
            </td>
          </tr>
          <tr> 
            <td class="admin12Bold">Title:</td>
            <td class="admin12Regular"> <input name="Title" type="text" id="Title2" value="<?php print $title; ?>" size="60"></td>
          </tr>
          <tr> 
            <td class="admin12Bold">First Name:</td>
            <td class="admin12Regular"> <input name="FirstName" type="text" id="FirstName3" value="<?php print $firstName; ?>" size="60"></td>
          </tr>
          <tr> 
            <td class="admin12Bold">Last Name:</td>
            <td class="admin12Regular"> <input name="LastName" type="text" id="LastName2" value="<?php print $lastName; ?>" size="60"></td>
          </tr>
          <tr> 
            <td class="admin12Bold">Street Address:</td>
            <td class="admin12Regular"> <input name="Address" type="text" id="Address4" value="<?php print $address; ?>" size="60"></td>
          </tr>
          <tr> 
            <td class="admin12Bold">Street Address 2:</td>
            <td class="admin12Regular"> <input name="Address2" type="text" id="Address23" value="<?php print $address2; ?>" size="60"></td>
          </tr>
          <tr> 
            <td class="admin12Bold">City:</td>
            <td class="admin12Regular"> <input name="City" type="text" id="City2" value="<?php print $city; ?>" size="60"></td>
          </tr>
          <tr> 
            <td class="admin12Bold">State:</td>
            <td class="admin12Regular"> 
              <?php
				  DisplayStateSelector($state);
				  ?>
            </td>
          </tr>
          <tr> 
            <td class="admin12Bold">Zip:</td>
            <td class="admin12Regular"> <input name="Zip" type="text" id="Zip3" value="<?php print $zip; ?>" size="10" maxlength="10"></td>
          </tr>
          <tr> 
            <td class="admin12Bold">Password:</td>
            <td class="admin12Regular"> <input name="Password" type="text" id="Password3" value="<?php print $password; ?>" size="40"></td>
          </tr>
          <tr> 
            <td class="admin12Bold">Creation date:</td>
            <td class="admin12Regular"><?php print $creationDate; ?></td>
          </tr>
          <tr align="center"> 
            <td height="50" colspan="2" class="admin12Bold"> &nbsp; <input name="AddAddressButton" type="submit" id="AddAddressButton" value="Add Address"> 
              <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>"> 
              <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>"> 
              <input name="Mode" type="hidden" id="Mode" value="AdminAdd"> <input name="Caller" type="hidden" id="Caller" value="mail_sub_add.php"> 
            </td>
          </tr>
        </table>
        <br>
        
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
