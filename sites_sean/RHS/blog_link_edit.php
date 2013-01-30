<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// blog_link_edit.php
//
// UI for blog editing
// 
// Revision:	011807 complete rewrite from 1.5
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// --------------------------------------------------------------------------------------------------------------------------------------

include '../local_info.php';
include 'db_lib.php';
include 'cl_lib.php';
include 'pw_lib.php';
include 'file_lib.php';
include 'html_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'blog_parms.php';
include 'select_controls.php';
include 'status_message.php';
include 'admin_lib.php';
include 'appdev_revision.php';

$debug	=	0;

// ---------------------------------------------------------------------------------------------------------------------------------------
// Authorize the user
// ---------------------------------------------------------------------------------------------------------------------------------------

if(UserIsAdmin($systemUserID) || UserIsSuper($systemUserID))
	{
	if($debug) print "User is ADMIN or SUPER<br>\n";

	if(AuthorizeAdminUser($systemUserID,$systemPassword)==false)
		{
		exit(0);
		}
	}
else
	{
	if($debug) print "User is BLOGGER<br>\n";

	$table		=	$blTable;
	
	if($debug) print "table: $table<br>\n";
	
	// authenticate
	if(AuthorizeBlogUser($systemUserID,$systemPassword,$table)==false)
		{
		print "Access denied.<br>";
		exit(0);
		}
	}	


// get the name of the blog
$blogName	=	BlogHeaderGetName($blTable);

if($blMode=="AddLink")
	{
	$buttonName		=	"Add Link";
	$pageName		=	"Add a Link";
	$helpLink		=	"blog_help.php#AddLink";
	$previewLink	=	"";
	$backLink		=	"blog_home.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	}
else if($blMode=="UpdateItem")
	{
	$buttonName		=	"Modify";
	$pageName		=	"Modify Link";
	$helpLink		=	"blog_help.php#AddLink";
	$previewLink	=	"blog_preview_link.php?blItemNumber=$blItemNumber&blTable=$blTable";
	$backLink		=	"blog_home.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	}
else if($blMode=="Refresh" || $blMode=="LinkPickerUpdateItem")
	{
	// wants itemNum and table
	$buttonName		=	"Modify";
	$pageName		=	"Modify Link";
	$helpLink		=	"blog_help.php#AddLink";
	$previewLink	=	"blog_preview_link.php?blItemNumber=$blItemNumber&blTable=$blTable";
	$backLink		=	"blog_link_picker.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	// call refresh function
	BlogLinkRefreshGlobals($blItemNumber,$blTable);
	$blMode			=	"LinkPickerUpdateItem";			// leav it in update mode	
	}
else if($blMode=="UpdateRefresh")
	{
	// wants itemNum and table
	$buttonName		=	"Modify";
	$pageName		=	"Modify Link";
	$helpLink		=	"blog_help.php#AddLink";
	$previewLink	=	"blog_preview_link.php?blItemNumber=$blItemNumber&blTable=$blTable";
	$backLink		=	"blog_link_picker.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	// call refresh function
	//BlogRefreshGlobals($blItemNumber,$blTable);
	BlogLinkRefreshGlobals($blItemNumber,$blTable);
	$blMode			=	"UpdateLink";			// leav it in update mode	
	}

$blogType	=	BlogHeaderGetType($blTable);

// get the name of the blog
$blogName	=	BlogHeaderGetName($blTable);

$blLinkURL			=	stripslashes($blLinkURL);
$blSiteName			=	stripslashes($blSiteName);
$blLinkDescription	=	stripslashes($blLinkDescription);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print "$blogName - $pageName"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<meta name="robots" content="noindex">
<meta name="robots" content="nofollow">
<meta name="robots" content="none">
<?php
if($gUseTinyMCE)
	{
	// 2.2.0.2 updated this to use a global to describe the props
	// for the editor window
	print	'
			<!-- tinyMCE -->
			<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
			<script language="javascript" type="text/javascript">
				tinyMCE.init({' .
				
				$gTinyMCEProperties
				
				. 'height:"350px",
				width:"600px",
			  });
			</script>
			<!-- /tinyMCE -->
			';
	
	// fix up the copy string
	$blCopy			=	 stripslashes(urldecode(htmlentities($blCopy)));
	
	// need to make type be HTML
	$blItemType		=	"HTML";
	}
?>



<link href="app_dev_II.css" rel="stylesheet" type="text/css">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="955" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr> 
    <td align="center" class="cts18Bold"> <?php print "$blogName - $pageName"; ?></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr bgcolor="#EEEEEE"> 
          <td width="30%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink">Back</a> </td>
          <td align="center"><a href="<?php print $helpLink; ?>" target="_blank" class="galleryUILink">Help</a> 
          </td>
          <td width="30%" height="40" align="right"> </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td> <form name="form1" enctype="application/x-www-form-urlencoded" method="POST" action="blog_services.php">
        <input name="blMode" type="hidden" id="blMode" value="<?php print $blMode; ?>">
        <input name="blItemNumber" type="hidden" id="blItemNumber" value="<?php print $blItemNumber; ?>">
        <input name="blTable" type="hidden" id="blTable" value="<?php print $blTable; ?>">
        <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
        <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print "$systemUserID"; ?>">
        <input name="blPosterID" type="hidden" id="blPosterID" value="<?php print "$systemUserID"; ?>">
        <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="18%" height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"> 
              <div align="right"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Link 
                URL:&nbsp;&nbsp;</div></td>
            <td width="82%" align="left" valign="middle" class="cts12Bold">&nbsp;&nbsp;http://&nbsp; 
              <input name="blLinkURL" type="text" id="blLinkURL" value="<?php print $blLinkURL; ?>" size="75"> 
              &nbsp; </td>
          </tr>
          <tr> 
            <td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"><span class="admin18BoldRed">*</span>&nbsp;Site 
              Name: &nbsp;</td>
            <td align="left" valign="middle"> &nbsp; <input name="blSiteName" type="text" id="blSiteName" value="<?php print $blSiteName; ?>" size="80"> 
              &nbsp; </td>
          </tr>
          <tr> 
            <td align="right" valign="top" bgcolor="#FFFFFF" class="cts12Bold"> 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="right" class="cts12Bold">&nbsp;</td>
                </tr>
                <tr> 
                  <td height="25" align="right" class="cts12Bold">Link Description: 
                    &nbsp;&nbsp;</td>
                </tr>
              </table></td>
            <td align="right" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td align="center" valign="top"><textarea name="blLinkDescription" cols="79" rows="5" id="elm1"><?php print $blLinkDescription; ?></textarea> 
                    &nbsp; </td>
                </tr>
              </table>
              &nbsp; </td>
          </tr>
        </table>
        <p align="center"> 
          <input type="submit" name="Submit" value="<?php print $buttonName; ?>">
        </p>
      </form></td>
  </tr>
  <tr>
    <td><span class="admin18BoldRed">*</span>&nbsp;<span class="admin15BoldRed">Required 
      Fields</span></td>
  </tr>
  <tr> 
    <td height="40" bgcolor="#eeeeee"> 
      <?php
PrintUserMessage($systemUserID);
?>
    </td>
  </tr>
  <tr> 
    <td> 
      <?php
	
	PrintBlogFooter();
	
	?>
    </td>
  </tr>
</table>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>
