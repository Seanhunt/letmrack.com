
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// mail_sub_send.php
//
// Revisions:	062406 	Added field to data and code to make this only send 1 message in a given day to 
// 						the list. That way if the server times out or resets on the process it can be 
//						picked up in the middle 
//
//				070907	Fixed bug where test list was getting the mailing flag. It should not get flagged.
//
// The first name in the test mailing db must also be in the production db for the preview to work correctly.
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
include 'name_value_pair_lib.php';
include 'pw_parms.php';
include 'mail_sub_parms.php';
include 'file_lib.php';
include 'mail_lib.php';
include 'html_lib.php';
include 'numbers_lib.php';
include 'admin_lib.php';
include 'question_lib.php';
include 'status_message.php';	
include 'appdev_revision.php';	

$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";

$debug	=	0;

// if there is no ID fix it up...
if($mailingID=="")
	$mailingID	=	GenerateUniqueID("MAIL_ID_");

// set up the selection query and the display of selected list names
// SELECT * FROM Table WHERE Sub1='Y' AND Sub2='Y' AND Sub3='Y'

$listMessage		=	"";	
$listFilterClause	=	"";

if($sub1)
	{
	$listMessage		.=	GetMailingListName(1);
	$listFilterClause	.=	"subscribed1='Y' ";
	}

if($sub2)
	{
	if(strlen($listMessage))
		$listMessage	.=	", ";
	
	$listMessage	.=	GetMailingListName(2);
	
	if(strlen($listFilterClause) > 0)
		$listFilterClause	.=	"OR subscribed2='Y' "; //  " subscribed1='Y' "
	else
		$listFilterClause	.=	"subscribed2='Y' ";
	}

if($sub3)
	{
	if(strlen($listMessage))
		$listMessage	.=	", ";
	
	$listMessage	.=	GetMailingListName(3);

	if(strlen($listFilterClause) > 0)
		$listFilterClause	.=	"OR subscribed3='Y' "; //  " subscribed1='Y' "
	else
		$listFilterClause	.=	"subscribed3='Y' ";
	}
			
if(strlen($listMessage)==0)
	$listMessage	.=	"&nbsp;";

if(strlen($listFilterClause) > 0)
	$listFilterClause	.=	"AND mailing_flag NOT LIKE '$mailingID' "; //  " subscribed1='Y' "
else
	$listFilterClause	.=	"mailing_flag NOT LIKE '$mailingID' ";



$query	=	"SELECT * FROM $mailTableName WHERE $listFilterClause";	

$numberOfItems	=	GetNumberOfRows($query);

if($debug)	print "query: $query<br>";
if($debug)	print "numberOfItems: $numberOfItems<br>";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Mail List Transmitter Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- Hide from the robots -->		
<meta name="robots" content="noindex,nofollow">
<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
<META NAME="GOOGLEBOT" CONTENT="nofollow">
<META NAME="GOOGLEBOT" CONTENT="noindex">
<!-- End Hide from the robots -->




<link href="app_dev_II.css" rel="stylesheet" type="text/css">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" align="center" valign="middle" class="admin18Bold">Bulk Mail 
      Transmitter </td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;</td>
          <td width="33%" align="center"><a href="admin_help.php#MailTransmitter" target="_blank" class="galleryUILink">Help</a></td>
          <td width="33%">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="8%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="74%"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="50" align="center" valign="middle"> <form name="form1" method="get" action="mail_sub_console.php">
                    <input type="submit" name="Submit" value="Return To Mailer Console">
                    <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
                    <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
                    <input name="MessageFileName" type="hidden" id="MessageFileName" value="<?php print $messageFileName; ?>">
                    <input name="WriteEnabled" type="hidden" id="WriteEnabled" value="<?php print $writeEnabled; ?>">
                    <input name="NumberToSend" type="hidden" id="NumberToSend" value="<?php print $numberToSend; ?>">
                    <input name="MailingID" type="hidden" id="MailingID" value="<?php print $mailingID; ?>">
                    <input name="Subject" type="hidden" id="Subject" value="<?php print $subject; ?>">
                    <input name="MailTableName" type="hidden" id="MailTableName" value="<?php print $mailTableName; ?>">
                    <input name="Sub1" type="hidden" id="Sub1" value="<?php print $sub1; ?>">
                    <input name="Sub2" type="hidden" id="Sub2" value="<?php print $sub2; ?>">
                    <input name="Sub3" type="hidden" id="Sub3" value="<?php print $sub3; ?>">
                    <input name="NvpFileName" type="hidden" id="NvpFileName" value="<?php print $nvpFileName; ?>">
                  </form></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><table width="75%" border="1" align="center" cellpadding="5" cellspacing="0">
                    <tr class="cts12Bold"> 
                      <td>Write Enabled:</td>
                      <td><?php print $writeEnabled; ?></td>
                    </tr>
                    <tr class="cts12Bold"> 
                      <td>Mailing ID:</td>
                      <td><?php print $mailingID; ?></td>
                    </tr>
                    <tr class="cts12Bold"> 
                      <td width="26%">Date:</td>
                      <td width="74%"><?php print date("r"); ?></td>
                    </tr>
                    <tr class="cts12Bold"> 
                      <td>Total Items:</td>
                      <td><?php print $numberOfItems; ?></td>
                    </tr>
                    <tr class="cts12Bold"> 
                      <td>Mail Table:</td>
                      <td><?php print $mailTableName; ?></td>
                    </tr>
                    <tr class="cts12Bold"> 
                      <td>FileName:</td>
                      <td><?php print $messageFileName; ?></td>
                    </tr>
                    <tr class="cts12Bold"> 
                      <td>Start Index:</td>
                      <td><?php print $startRowIndex; ?></td>
                    </tr>
                    <tr class="cts12Bold"> 
                      <td>Number to Send:</td>
                      <td><?php print $numberToSend; ?></td>
                    </tr>
                    <tr class="cts12Bold"> 
                      <td>Sending To:</td>
                      <td> 
                        <?php 
			print $listMessage;
			?>
                      </td>
                    </tr>
                    <tr class="cts12Bold"> 
                      <td>Subject:</td>
                      <td><?php print $subject; ?></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><table width="75%" border="1" align="center" cellpadding="10" cellspacing="0" class="body12">
                    <tr> 
                      <td class="cts12Regular"> <p>Run Start Time: <?php print date("r"); ?>
                        <hr>
                        <p></p>
                        <p> 
                          <?php
			// do the indicated deed
			MailToList($subject,$startRowIndex,$mailTableName,$gMailerFromName,$gMailerFromAddress,$gMailerBounceAddress,$messageFileName,$sub1,$sub2,$sub3,$nvpFileName,$gMailerSalutation,$numberToSend,$writeEnabled,$mailingID,$query);

			?>
                        </p>
                        <p>
                        <hr>
                        Run End Time: <?php print date("r"); ?>
                        <p></p></td>
                    </tr>
                  </table></td>
              </tr>
            </table>
            <p>&nbsp;</p></td>
          <td width="18%" bgcolor="#999999">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE">&nbsp;</td>
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


<?php


function MailToList(	$subject,	
						$startRowIndex,
						$mailTableName,
						$fromName,
						$fromAddress,
						$bounceAddress,
						$messageFileName,
						$sub1,
						$sub2,
						$sub3,
						$nvpFileName,
						$salutation,
						$numberToSend,
						$writeEnabled,
						$mailingID,
						$query)
{
$debug					=	0;


	

if($debug) print "function MailToList(	$subject,	
						$startRowIndex,
						$mailTableName,
						$fromName,
						$fromAddress,
						$bounceAddress,
						$messageFileName,
						$sub1,
						$sub2,
						$sub3,
						$nvpFileName,
						$salutation,
						$numberToSend,
						$writeEnabled,
						$mailingID,
						$query)
<br>";

$headers  		=	"Mime-version: 1.0\n" .
					"Content-Type: text/html; charset=iso-8859-1\n" .
					"From: " . '"' . $fromName . '"' . " <" . $fromAddress . ">\n" .
					"Reply-To: " . '"' . $fromName . '"' . " <" . $fromAddress . ">\n" .
					"X-Mailer: PHP/" . phpversion();

$query			.=	"ORDER BY email_address LIMIT 0, $numberToSend";

$numberOfRows	=	GetNumberOfRows($query);

if($debug) print "query: $query<br>";
if($debug) print "numberOfRows: $numberOfRows<br>";
if($debug) print "headers: $headers<br>";

// load the outgoing message asset once this will be munged n times in the loop
if(($theMessageFile=FileRead($messageFileName))==FALSE)
	{
	print "Failed to Load Message File: $messageFileName NO MAIL SENT...<br>";
	return FALSE;
	}

// let them know its a wash, no list selected...
if(!$sub1 && !$sub2 && !$sub3)
	print "You did not select a list to mail on the console, NO MAIL SENT...";

// hey ho lets go!
OpenDatabase();

$rs				=	QueryDatabase($query);

for($i=$startRowIndex;$i<$numberOfRows;$i++)
	{
	$address		=	mysql_result($rs,$i,"email_address");
	$mailingFlag	=	mysql_result($rs,$i,"mailing_flag");
	
	// this should never happen as the query selects on this criteria, this is legacy crap
	if($mailingFlag==$mailingID)							
		{
		print "Row: $i - Already Sent<br>";
		continue;
		}
		
	if($debug)	print "address: $address<br>";
	if($debug)	print "mailingFlag: $mailingFlag<br>";

	// gen a message with the user data in the unsub parm
	// B vers does not open and close the db
	$message	=	GenerateMailingListMessageB($mailTableName,$messageFileName,$address,$salutation,$theMessageFile);
	
	// munge the message string and make UNIX newline pairs
	$message = implode("\r\n", preg_split("/\r?\n/", $message));

	// if the write flag is set send it otherwise dry pump the result to the screen
	if($writeEnabled=="Yes")
		$rv	=	mail($address,$subject,$message,$headers,"-f$bounceAddress");
	else
		{
		$rv	=	1;	// write protected, fake success...
		print "WRITE-PROTECTED Row: $i - $address<br>";
		}
		
	if($writeEnabled=="Yes")
		{
		if($rv) 
			{
			// mail was sent successfully...
	
			++$numberOfMessagesSent;
	
			print "Row: $i Sent - $address<br>";
	
			global $gTestMailTableName;

			$test	=	($mailTableName == MangleTableName($gTestMailTableName))?true:false;
	
			if($debug && $test) print "TEST MAILING<br>";
	
			// if it is not a test...
			// set the mailing flag
			if($test==false)
				{
				// set the mark on the record to this mailings mark
				// all records that have been sent are marked and screened out of successive runs
				// by the SQL query
				$query = 	'UPDATE ' . $mailTableName . 
							' SET mailing_flag ="' . $mailingID . 
							'" WHERE email_address ="' . $address . '"';
		
				QueryDatabase($query);
		
				if($debug)	print "RECORD MARKED - query: $query<br>";
				}
			} 
		else 
			{
			print "Row: $i - Mail Error - Not sent: $address<br>";
			} // if($rv)
		
		// wait for .25 seconds
		usleep(250000);

		} // if($writeEnabled=="Yes")
	else
		{
		++$numberOfMessagesSent;
		}
				
		
	
	
	
	} // end of for loop

CloseDatabase();

print "<br><hr>Run Completed<br>";
print "$numberOfMessagesSent Messages Sent<br>";

//GenerateMailingListMessage($messageFileName,$eMailAddress,$salutation,"")

//$theMessageFile	=	LoadMessage($messageFileName);


//$query			=	"SELECT * FROM $mailTableName ORDER BY email_address";

}

?>