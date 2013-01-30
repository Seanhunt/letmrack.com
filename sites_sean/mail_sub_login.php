
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// mail_sub_login.php
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
include 'RHS/chron_lib.php';	
include 'RHS/appdev_revision.php';	

$pageCounterName	=	"page_template.php";	// the name this page will appear as in the counters
$pageTitle			=	"Page Template";	// the title of the page
$showCounter		=	true;				// bool show or hide the counter
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Mail Subscription Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
    <td height="30" align="center" valign="middle">&nbsp; 
      <?php
	PrintBannerUI();
	?>
    </td>
  </tr>
  <tr> 
    <td height="50" align="left" valign="middle"> 
      <!--Start Page Content -->
	  <hr>
<form name="form1" method="get" action="RHS/mail_sub_handle_login.php">
        <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td height="50"><h2>Mail Subscription Log In</h2></td>
          </tr>
          <tr> 
            <td><table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
                <tr> 
                  <td width="19%" height="30" align="left" valign="middle" ><p><strong>E-Mail 
                    Address </strong></p></td>
                  <td width="81%" align="left" valign="middle"> &nbsp; <input name="E-MailAddress" type="text" id="E-MailAddress" value="<?php print "$eMailAddress"; ?>" size="55"> 
                  </td>
                </tr>
                <tr> 
                  <td height="30" align="left" valign="middle" ><p><strong>Password</strong></p></td>
                  <td align="left" valign="middle"> &nbsp; <input name="Password" type="password" id="Password"> 
                  </td>
                </tr>
              </table></td>
          </tr>
        </table>
        <h5 align="center"><a href="mail_sub_lost_pw.php">Lost 
          Password</a></h5>
        <p align="center"> 
    <input type="submit" name="Submit" value="Log In">
  </p>
</form>
	  <hr>
      <!--Start Page Content -->
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







