<?php
// Inputs: 	Mode 		= "" or "Edit"
//			UserID
//			Password

include 'local_info.php';
include 'RHS/db_lib.php';
include 'RHS/cl_lib.php';
include 'RHS/file_lib.php';
include 'RHS/html_lib.php';
include 'RHS/blog_parms.php';
include 'RHS/question_lib.php';
include 'RHS/blog_lib.php';
include 'RHS/blog_ads.php';
include 'RHS/status_message.php';
include 'RHS/seo_lib.php';	
include 'RHS/ui_lib.php';	
include 'RHS/counter_lib.php';	
include 'ajt_lib.php';


$debug	=	0;

$blTable			=	"APPDEV_BLOG_TaxTips";

if($debug) print "blTable: $blTable<br>";

$numberOfPostings	=	BlogGetNumberOfPosts($blTable);
$numberOfLinks		=	BlogGetNumberOfLinks($blTable);

//if($debug) print "linkTableName: $linkTableName<br>";
if($debug) print "numberOfLinks: $numberOfLinks<br>";
if($debug) print "numberOfPostings: $numberOfPostings<br>";

$blogName			=	BlogHeaderGetName($blTable);
$blogDescription	=	BlogHeaderGetDescription($blTable);
$blogType			=	BlogHeaderGetType($blTable);

// pick the appropriate template file
// the lifo file does not have date and time of event
$templateFileName		=	"templates/tax_tips_posting2.html";
$linkTemplateFileName	=	"templates/tax_tips_link.html";
$tocTemplateFileName	=	"templates/tax_tips_toc.html";

$pageCounterName	=	"TaxTips";	// the name this page will appear as in the counters
$pageTitle			=	"Tax Tips";	// the title of the page
$showCounter		=	false;				// bool show or hide the counter

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- Start Meta Tags -->
<?php
AJT_PrintPageTitle($pageTitle); // this will be surrounded by the site and site keywords
$description			=	""; // description for this page else standard
$additionalKeywords		=	""; // these will appear first in the list
PrintMetaTags($description,$additionalKeywords);
?>
<!-- End Meta Tags -->

<link href="images/css/cts.css" rel="stylesheet" type="text/css">
<link href="images/css/tax_tips.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <!-- Start Banner -->
      <?php 
PrintBannerUI();
?>
      <!-- End Banner -->
    </td>
  </tr>
  <tr>
    <td> 
      <!-- ***** PAGE CONTENT START *** -->
      <table width="955" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td><table width="955" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="642" valign="top" bgcolor="f8b520">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><img src="images/tax_tips/blog_header.gif" width="642" height="164"></td>
                    </tr>
                    <tr>
                      <td valign="top" background="images/tax_tips/blog_loop.gif">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="10%">&nbsp;</td>
                            <td width="82%" valign="top"> 
                              <p> 
                                <?php
								$numberOfPostings	=	BlogGetNumberOfPosts($blTable);
					
								if($debug) print "numberOfPostings: $numberOfPostings<br>";
								if($debug) print "templateFileName: $templateFileName<br>";
					
								BlogContinuedMode(true);

								for($i=0;$i<$numberOfPostings;$i++)
									{
									print BlogGetItem($i,$blTable,$templateFileName);
									}
					
								BlogContinuedMode(false);

								if($numberOfPostings==0)
									print "There are no postings to view...";	
								?>
                                <font face="Arial, Helvetica, sans-serif"><b> 
                                </b></font></p>
                              </td>
                            <td width="8%">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td><img src="images/tax_tips/blog_bottom.gif" width="642" height="36"></td>
                    </tr>
                  </table>
                </td>
                <td width="313" valign="top" bgcolor="a73e97">
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="a73e97">
                    <tr>
                      <td><img src="images/tax_tips/links_pic.jpg" width="312" height="152"></td>
                    </tr>
                    <tr>
                      <td><img src="images/tax_tips/article_header.gif" width="312" height="37"></td>
                    </tr>
                    <tr>
                      <td background="images/tax_tips/article_loop.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="16%">&nbsp;</td>
                            <td width="67%"> 
                              <!-- Begin Item Anchors Section ---------------------------------------------------------------------->
                              <?php

							for($i=0;$i<$numberOfPostings;$i++)
								{
								print BlogGetItem($i,$blTable,$tocTemplateFileName);
								}
				
							if($numberOfPostings==0)
								print "There are no postings to view...";	
							?>
                            </td>
                            <td width="17%">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td><img src="images/tax_tips/Links_header.gif" width="312" height="67"></td>
                    </tr>
                    <tr>
                      <td background="images/tax_tips/article_loop.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="16%">&nbsp;</td>
                            <td width="68%"> 
                              <!-- Begin Links Section ---------------------------------------------------------------------->
                              <?php

								for($i=0;$i<$numberOfLinks;$i++)
									{
									print BlogLinkGetItem($i,$blTable,$linkTemplateFileName);
									}
									
								if($numberOfLinks==0)
									print "&nbsp;";	
								?>
                            </td>
                            <td width="16%">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td><img src="images/tax_tips/links_bottom.gif" width="312" height="31"></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td><img src="images/tax_tips/bottom.jpg" width="955" height="231"></td>
        </tr>
      </table>
      <!-- ***** PAGE CONTENT END *** -->
    <p>&nbsp;</p></td>
  </tr>
  <!-- Start SEO Footer Info -->
  <tr>
    <td>
	<?php
	// print the SEO information from the SEO_Footer blog 
	print BlogGetItem(0,"APPDEV_BLOG_SEO_Footer","templates/blog_seo_footer.html");
	?></td>
  </tr>
  <!-- End SEO Footer Info -->

  <tr>
    <td>
      <!--Start Counter -->
      <?php
		if($showCounter) print "Page Views: "; 
		Counter($pageCounterName,$showCounter);
		?>
      <!--End Counter -->

    </td>
  </tr>


  <tr>
    <td><?php
	//PrintPageFooter();
	?></td>
  </tr>
</table>
</body>
</html>
