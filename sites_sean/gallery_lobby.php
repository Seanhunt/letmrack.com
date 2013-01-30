<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_lobby.php
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
include 'RHS/gallery_lib.php';

$debug	=	0;

$templateFileName	=	"templates/gallery_t_lobby.html";

$pageCounterName	=	"GalleryLobby";		// the name this page will appear as in the counters
$pageTitle			=	"Gallery Lobby";			// the title of the page
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
    <td height="50" align="left" valign="top"> 
      <!--Start Page Content -->
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="20%" bgcolor="#CCCCCC">&nbsp;</td>
                <td> <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr> 
                      <td height="10">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="10">
                          <tr> 
                            <td> 
                              <?php
						$numberOfGalleries	=	GetNumberOfGalleries();

						if($debug) print "numberOfGalleries: $numberOfGalleries<br>";
						
						// iterate the galleries
						for($i=0;$i<$numberOfGalleries;$i++)
							{
							// get the ID
							$artistID	=	GetProfileArtistID($i);

							if($debug) print "artistID: $artistID<br>";

							// get the thumbs page name
							// UPDATE 071507
							$thumbsPageName		=	GetGalleryThumbsPage($artistID);

							if($debug) print "thumbsPageName: $thumbsPageName<br>";
							
							// fetch, configure and display the profile template file
							print GetGalleryProfile($artistID,$templateFileName,$thumbsPageName);
							}		  
						?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr> 
                      <td height="10">&nbsp;</td>
                    </tr>
                  </table></td>
                <td width="20%" bgcolor="#CCCCCC">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table> 
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
