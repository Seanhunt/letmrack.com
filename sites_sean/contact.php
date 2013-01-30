
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// contact.php
//
// This is a skeleton for a page using that appdev lib
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

include 'local_info.php';	
include 'RHS/db_lib.php';
include 'RHS/cl_lib.php';
include 'RHS/file_lib.php';
include 'RHS/counter_lib.php';
include 'RHS/html_lib.php';
include 'RHS/status_message.php';	
include 'RHS/seo_lib.php';	
include 'RHS/ui_lib.php';	
include 'RHS/captcha_lib.php';	
include 'RHS/appdev_revision.php';	

$name						=	GetPassedValue("Name");
$address					=	GetPassedValue("E-MailAddress");
$subject					=	GetPassedValue("Subject");
$mailMessage				=	GetPassedValue("MailMessage");
$error						=	0;	

$fileName					=	GetCaptchaFileName("small");

// set up the fields if they are blank
if($name=="")
	$name	=	"Put your name here...";

if($address=="")
	$address	=	"Put your e-mail address here...";

if($subject=="")
	$subject	=	"I would like some further information...";

if($mailMessage=="")
	$mailMessage	=	"Type your message here...";

// we are coming back in HTML
$mailMessage 				= str_replace("<br>","\r\n",$mailMessage);

//	implode("\r\n", preg_split("<br>", $mailMessage));

$debug						=	0;

if($debug) print "name: $name<br>";
if($debug) print "address: $address<br>";
if($debug) print "subject: $subject<br>";
if($debug) print "mailMessage: $mailMessage<br>";
if($debug) print "fileName: $fileName<br>";

$pageCounterName	=	"Contact";	// the name this page will appear as in the counters
$pageTitle			=	"Contact";	// the title of the page
$showCounter		=	true;				// bool show or hide the counter
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
PrintPageTitle($pageTitle); // this will be surrounded by the site and site keywords
$description			=	""; // description for this page else standard
$additionalKeywords		=	""; // these will appear first in the list

PrintMetaTags($description,$additionalKeywords);
?>
<link href="images/css/local.css" rel="stylesheet" type="text/css">
</head>
<body>
<!--- Include for Tool Tips ******************************* --->
<script type="text/javascript" src="wz_tooltip.js"></script>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle">&nbsp; 
      <?php
	PrintBannerUI();
	?>
    </td>
  </tr>
  <tr>
    <td height="10" align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr> 
    <td height="50" align="left" valign="middle"> 
      <!--Start Page Content -->
      <form name="form1" method="get" action="RHS/mail_send_contact_message.php">
        <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr bgcolor="#666666"> 
            <td height="50" colspan="2" align="left" valign="middle">&nbsp;&nbsp;Contact            </td>
          </tr>
          <tr> 
            <td width="31%" height="40" align="right" valign="middle" bgcolor="#CCCCCC" ><strong>Name&nbsp;&nbsp;</strong></td>
            <td width="69%" align="center" valign="middle" bgcolor="#efefef"> 
              <input name="Name" type="text" id="Name" value="<?php print "$name"; ?>" size="40">            </td>
          </tr>
          <tr> 
            <td height="40" align="right" valign="middle" bgcolor="#CCCCCC" ><strong>E-Mail 
              Address&nbsp;&nbsp;</strong></td>
            <td align="center" valign="middle" bgcolor="#efefef"> <input name="E-MailAddress" type="text" id="E-MailAddress" value="<?php print "$address"; ?>" size="40">            </td>
          </tr>
          <tr> 
            <td height="40" align="right" valign="middle" bgcolor="#CCCCCC" ><strong>Subject&nbsp;&nbsp;</strong></td>
            <td align="center" valign="middle" bgcolor="#efefef"> <input name="Subject" type="text" id="Subject" value="<?php print "$subject"; ?>" size="40"></td>
          </tr>
          <tr>
            <td height="40" align="right" valign="middle" bgcolor="#CCCCCC" ><strong>Copy The Secret Word...&nbsp;&nbsp;</strong></td>
            <td align="center" valign="middle" bgcolor="#efefef"><table width="80%" border="0">
              <tr>
                <td width="30%"><img src="images/captcha/<?php print $fileName; ?>" width="100" height="30"></td>
                <td width="70%" align="center" valign="middle"><input name="cr" type="text" id="cr" size="20"></td>
              </tr>
            </table>
            </td>
          </tr>
          <tr> 
            <td height="30" align="right" valign="top" bgcolor="#CCCCCC" > 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td height="40" align="right" ><strong>Message&nbsp;&nbsp;                  </strong></td>
                </tr>
              </table></td>
            <td align="center" valign="middle" bgcolor="#efefef"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td>&nbsp;</td>
                </tr>
              </table>
              <textarea name="MailMessage" cols="39" rows="25" id="MailMessage"><?php print "$mailMessage"; ?></textarea></td>
          </tr>
          <tr> 
            <td height="50" colspan="2" align="center" valign="middle" bgcolor="#999999" > 
              <input type="submit" name="Submit" value="Send Message"></td>
          </tr>
        </table>
        <p align="center">&nbsp;</p>
        <p align="center"> 
          <input name="Mode" type="hidden" id="Mode" value="Send">
          <input name="Caller" type="hidden" id="Caller" value="contact.php">
          
          <?php
		  // NEW 2.1.9
		  PrintCaptchaHiddenTags($fileName,"small");
		  ?>
          
          
          
        </p>
      </form>
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
