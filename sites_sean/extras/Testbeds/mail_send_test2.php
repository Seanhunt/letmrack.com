<?php
include 'local_info.php';
include 'RHS/db_lib.php';
include 'RHS/cl_lib.php';
include 'RHS/file_lib.php';
include 'RHS/mail_lib.php';
include 'RHS/html_lib.php';	
include "RHS/name_value_pair_lib.php";
include 'RHS/redirect.php';
include 'RHS/pw_lib.php';
include 'RHS/numbers_lib.php';
include 'RHS/pw_parms.php';
include 'RHS/mail_sub_parms.php';
include "RHS/debug_lib.php";


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Send Test 2</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php

$eMailAddress		=	"cooksey@cookseytalbottgallery.com";

$creationDate	=	date("F j, Y, g:i:s a");                // March 10, 2001, 5:16:34 pm

// set up and send the confirmation to the user
$fromName			=	"Message Sender Testbed";
$subject			=	"Subject: $creationDate";
//$messageFileName	=	"messages/message_sub_confirm.html";
//$messageFileName	=	"messages/message_sub_welcome.html";
//$messageFileName	=	"messages/message_sub_optout.html";
$messageFileName	=	"messages/message_sub_lost_pass.html";

$mailMessage		=	GenerateMailingListMessage($messageFileName,$eMailAddress,$gMailerSalutation,"");

//$mailMessage		=	FileRead("messages/message_sub_welcome.html");

if($mailMessage=="")
	print "Unable to load message file: message_sub_confirm.html<BR>";
	
//print $mailMessage;	

$rv=local_email_send($eMailAddress,$gMailerFromAddress,$fromName,"[Send Test]$subject",$mailMessage);			

if($rv==1)
	{
	print "Message Filename: $messageFileName<BR>";
	print "Mail Sent To: $eMailAddress<BR>";
	print "From: $gMailerFromAddress<BR>";
	print "Subject: [Send Test]$subject<BR>";
	print "<HR>";
	print "Message:<BR>$mailMessage";
	print "<HR>";
	}
?>


<?php
function local_email_send($toAddress,$fromAddress,$fromName,$subject,$message)
{
if($debug) print "email_send($toAddress,$fromAddress,$fromName,$subject,$message)<br>";

$headers  		=	"Mime-version: 1.0\n" .
					"Content-Type: text/html; charset=iso-8859-1\n" .
					"From: " . '"' . $fromName . '"' . " <" . $fromAddress . ">\n" .
					"Reply-To: " . '"' . $fromName . '"' . " <" . $fromAddress . ">\n" .
					"X-Mailer: PHP/" . phpversion();

if($debug) print "headers: $headers<br>";

if(mail($toAddress,$subject,$message,$headers)) 
	{
	return	1;
	} 

return 0;
}
?>


</body>
</html>
