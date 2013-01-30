
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// join.php
//
// This is the template for a signup page
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
include 'RHS/mail_sub_parms.php';	
include 'RHS/mail_lib.php';	
include 'RHS/chron_lib.php';	
include 'RHS/appdev_revision.php';	
include 'RHS/select_controls.php';	

$pageCounterName	=	"MailSignUp";	// the name this page will appear as in the counters
$pageTitle			=	"Mailing List";	// the title of the page
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
    <td height="30" align="center" valign="middle" >&nbsp; 
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
      <form action="RHS/mail_sub_services.php" method="get" name="Signup" id="Signup">
        <input name="Caller" type="hidden" id="Caller" value="join.php">
        <input name="Mode" type="hidden" id="Mode" value="Add">
        <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td valign="top"> <table width="100%" border="0" cellpadding="10" cellspacing="0" >
                <tr> 
                  <td bgcolor="#DDDDDD"> <p>Join our wonderful mailing lists!!!</p>
                    <p>Your name and address and e-mail will be kept totally private 
                      and our subscription manager works.</p>
                    <p>All emails contain a link allowing you to change or cancel 
                      your subscriptions at any time</p></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td bgcolor="#dddddd"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="34%" height="40" align="right" valign="middle" bgcolor="#EFEFEF" > 
                    Title&nbsp;&nbsp;</td>
                  <td width="66%" align="left" bgcolor="#999999">&nbsp; 
                    <?php
			$titleArray	=	array();
			$titleArray[]	=	"Mr";	
			$titleArray[]	=	"Ms";	
			$titleArray[]	=	"Mrs";	
			$titleArray[]	=	"Miss";	
			$titleArray[]	=	"Dr";	
			$titleArray[]	=	"Rev";	
			?>
                    <select name="Title" id="Title">
                      <option value="Mr">Mr</option>
                      <option value="Ms">Ms</option>
                      <option value="Mrs">Mrs</option>
                      <option value="Dr">Dr</option>
                      <option selected>Pick One</option>
                    </select></td>
                </tr>
                <tr> 
                  <td height="40" align="right" valign="middle" bgcolor="#EFEFEF" ><span class="rhs18BoldRed">*&nbsp;</span> 
                    First Name&nbsp;&nbsp;</td>
                  <td align="left" bgcolor="#999999">&nbsp; <input name="FirstName" type="text" id="FirstName" value="<?php print "$firstName"; ?>" size="40"></td>
                </tr>
                <tr> 
                  <td height="40" align="right" valign="middle" bgcolor="#EFEFEF" > 
                    <span class="rhs18BoldRed">*&nbsp;</span> Last Name&nbsp;&nbsp;</td>
                  <td align="left" bgcolor="#999999">&nbsp; <input name="LastName" type="text" id="LastName" value="<?php print "$lastName"; ?>" size="40"></td>
                </tr>
                <tr> 
                  <td height="40" align="right" valign="middle" bgcolor="#EFEFEF" >Address&nbsp;&nbsp;</td>
                  <td align="left" bgcolor="#999999">&nbsp; <input name="Address" type="text" id="Address2" value="<?php print "$address"; ?>" size="40"></td>
                </tr>
                <tr> 
                  <td height="40" align="right" valign="middle" bgcolor="#EFEFEF" >Address 
                    Line 2&nbsp;&nbsp;</td>
                  <td align="left" bgcolor="#999999">&nbsp; <input name="Address2" type="text" id="Address3" value="<?php print "$address2"; ?>" size="40">                  </td>
                </tr>
                <tr> 
                  <td height="40" align="right" valign="middle" bgcolor="#EFEFEF" >City&nbsp;&nbsp;</td>
                  <td align="left" bgcolor="#999999">&nbsp; <input name="City" type="text" id="City" value="<?php print "$city"; ?>" size="30"></td>
                </tr>
                <tr> 
                  <td height="40" align="right" valign="middle" bgcolor="#EFEFEF"><span >State</span>&nbsp;&nbsp;</td>
                  <td align="left" bgcolor="#999999"> &nbsp; 
                    <?php
			DisplayStateSelector($state);
			  ?>                  </td>
                </tr>
                <tr> 
                  <td height="40" align="right" valign="middle" bgcolor="#EFEFEF"><span >Zip</span>&nbsp;&nbsp;</td>
                  <td align="left" bgcolor="#999999">&nbsp; <input name="Zip" type="text" id="Zip" value="<?php print "$zip"; ?>" size="9" maxlength="9"></td>
                </tr>
                <tr> 
                  <td height="40" align="right" valign="middle" bgcolor="#EFEFEF" ><span class="rhs18BoldRed">*&nbsp;</span>E-Mail 
                    Address&nbsp;&nbsp;</td>
                  <td align="left" bgcolor="#999999">&nbsp; <input name="E-MailAddress" type="text" id="E-MailAddress" value="<?php print "$eMailAddress"; ?>" size="40"></td>
                </tr>
                <?php
		  
		  $numberOfMailingLists	=	GetNumberOfMailingLists();	
		  
		  for($i=1;$i<=$numberOfMailingLists;$i++)
		  	{
			$name			=	GetMailingListName($i);			
			$description	=	GetMailingListDescription($i);
			$checked		=	GetMailingListIsChecked($i);
			$inputName		=	"Sub" . $i;
			
			print	'
					  <!--- Mailing List Name --->
					  <tr> 
						<td height="30" valign="middle" align="right"><span class=rhs12Bold>' . 
						$name . ':</span>&nbsp;</td>
						
						<td align="left"> 
						
						<input name="' . $inputName . '" type="checkbox" id="' . $inputName . '" value="Y" ' . $checked . '>
						  
						  <span class=rhs10Regular>'. $description . '</span></td>
					  </tr>
					';
			}
		  
		  ?>
              </table>
              <div align="center"> 
                <table width="100%" border="0" cellspacing="0" cellpadding="10">
                  <tr> 
                    <td align="center" bgcolor="#666666" class="rhs11Regular"> 
                      <input name="Add" type="submit" id="Add" value="Subscribe"> 
                      <br> 
                      <br>                      <a href="mail_sub_lost_pw.php" class="whiteLink">Lost 
                        Password ?</a><br>
                      <a href="mail_sub_login.php" class="whiteLink">My 
                      Account...</a> </td>
                  </tr>
                </table>
              </div></td>
          </tr>
          <tr> 
            <td height="30" align="left" valign="middle" class="rhs12BoldRed">&nbsp;&nbsp;* 
              Required Fields</td>
          </tr>
        </table>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
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
