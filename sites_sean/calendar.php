<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include 'local_info.php';
include 'RHS/db_lib.php';
include 'RHS/cl_lib.php';
include 'RHS/file_lib.php';
include 'RHS/pw_lib.php';
include 'RHS/html_lib.php';
include 'RHS/blog_parms.php';
include 'RHS/question_lib.php';
include 'RHS/blog_lib.php';
include 'RHS/select_controls.php';
include 'RHS/status_message.php';
include 'RHS/seo_lib.php';	
include 'RHS/ui_lib.php';	
include 'RHS/chron_lib.php';	
include 'RHS/debug_lib.php';	
include 'RHS/counter_lib.php';
include 'RHS/blog_ads.php';

$debug	=	0;

$blTable	=	"APPDEV_BLOG_CalendarBlog";
$blogAdTableName	=	"APPDEV_BLOG_AdOne";

if($debug) print "blTable: $blTable<br>";

// maintain the calendar blogs if necessary
ChronMaintainCalendarBlogs(2);

$numberOfPostings	=	BlogGetNumberOfPosts($blTable);
$numberOfLinks		=	BlogGetNumberOfLinks($blTable);

//if($debug) print "linkTableName: $linkTableName<br>";
if($debug) print "numberOfLinks: $numberOfLinks<br>";
if($debug) print "numberOfPostings: $numberOfPostings<br>";

$blogName			=	BlogHeaderGetName($blTable);
$blogDescription	=	BlogHeaderGetDescription($blTable);
$blogType			=	BlogHeaderGetType($blTable);

// pick the appropriate template file
$templateFileName		=	"templates/blog_t_viewer_calendar.html";
$linkTemplateFileName	=	"templates/blog_t_link.html";
$tocTemplateFileName	=	"templates/blog_t_toc.html";
$adTemplateFileName		=	"templates/blog_t_ad.html";

$pageCounterName	=	"Calendar";	// the name this page will appear as in the counters
$pageTitle			=	"Calendar";	// the title of the page
$showCounter		=	true;				// bool show or hide the counter
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php
PrintPageTitle($pageTitle); // this will be surrounded by the site and site keywords
$description			=	""; // description for this page else standard
$additionalKeywords		=	""; // these will appear first in the list

PrintMetaTags($description,$additionalKeywords);
?>

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
    <td height="30" align="center" valign="middle"> 
      <?php
	PrintBannerUI();
	?>
    </td>
  </tr>
  <tr> 
    <td height="50" align="left" valign="top"> 
      <!--Start Page Content -->
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><table width="938" border="1" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="175" align="left" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td height="25" align="center" bgcolor="#CCCCCC" >Advertise 
                        Here</td>
                    </tr>
                    <tr> 
                      <td> 
                        <?php
			// display the ads column with fixed and ROP positions
			$randomRS		=	BlogOpenAds($blogAdTableName);	
			
			$numberOfAds	=	BlogGetNumberOfAds($blogAdTableName,$randomRS);

			for($i=0;$i<$numberOfAds;$i++)
				{
				print BlogGetAdItem($i,$blogAdTableName,$randomRS,$adTemplateFileName);
				}
			?>
                      </td>
                    </tr>
                  </table></td>
                <td width="560" align="left" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td height="50" align="center" valign="middle" bgcolor="#666666" class="rhs24BoldWhite">
                        <?php
				
				print $blogName;
				
				?>
                      </td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td> 
                        <?php
			for($i=0;$i<$numberOfPostings;$i++)
				{
				print BlogGetItem($i,$blTable,$templateFileName);
				}

			if($numberOfPostings==0)
				print "There are no postings to view...";	
			?>
                      </td>
                    </tr>
                  </table></td>
                <td width="195" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td height="25" align="center" valign="middle" bgcolor="#CCCCCC" >Contents</td>
                    </tr>
                    <tr> 
                      <td align="center" valign="middle" >&nbsp;</td>
                    </tr>
                    <tr> 
                      <td align="center" valign="middle" > 
                        <?php
					for($i=0;$i<$numberOfPostings;$i++)
						{
						print BlogGetItem($i,$blTable,$tocTemplateFileName);
						}
		
					if($numberOfPostings==0)
						print "There are no postings to view...";	
					?>
                      </td>
                    </tr>
                    <tr> 
                      <td align="center" valign="middle" >&nbsp;</td>
                    </tr>
                    <tr> 
                      <td height="25" align="center" valign="middle" bgcolor="#CCCCCC" >Links</td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td align="center" valign="top"> 
                        <?php

					for($i=0;$i<$numberOfLinks;$i++)
						{
						print BlogLinkGetItem($i,$blTable,$linkTemplateFileName);
						}
						
					if($numberOfLinks==0)
						print "&nbsp;";	
					?>
                      </td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <hr> 
      <!--END Page Content -->
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
DisplayStatusMessage($statusMessage);
?>