<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// blog_file_upload.php
//
// UI for uploading a file for sharing
// 
// Revision:	071105	added return to caller page for edit UI
// 				020506 Start 1.5 Revision
//				011707 Start rev 2
//				032507 Changed for file share
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
include 'html_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'blog_parms.php';
include 'select_controls.php';
include 'status_message.php';
include 'admin_lib.php';
include 'appdev_revision.php';
include 'file_lib.php';

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

$blCopy		=	stripslashes($blCopy);
$blHeadline	=	stripslashes($blHeadline);
$blSubHead	=	stripslashes($blSubHead);

$buttonName		=	"Save Post";
$helpLink		=	"blog_help.php#MusicSharingPost";
$backLink		=	"blog_home.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
$pageName		=	"New Music Sharing Post";
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $blogName; ?> - New Music Sharing Post</title>
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
				width:"640px",
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
    <td align="center" class="cts18Bold"> <?php print $pageName; ?></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr bgcolor="#EEEEEE"> 
          <td width="30%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink">Back</a> </td>
          <td align="center"><a href="<?php print $helpLink; ?>" target="_blank" class="galleryUILink">Help</a> 
          </td>
          <td width="30%" height="40" align="right">&nbsp; </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td> <form name="form1" enctype="multipart/form-data" method="POST" action="blog_services.php">
        <input name="blMode" type="hidden" id="blMode" value="FileSharing">
        <input name="blItemNumber" type="hidden" id="blItemNumber" value="<?php print $blItemNumber; ?>">
        <input name="blTable" type="hidden" id="blTable" value="<?php print $blTable; ?>">
        <input name="blCaller" type="hidden" id="blCaller" value="<?php print $blCaller; ?>">
        <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
        <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print "$systemUserID"; ?>">
        <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
       <input name="blItemType" type="hidden" id="blItemType" value="TEXT">

		
		<table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="24%" height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"> 
              <span class="admin18BoldRed">*</span>&nbsp;Song Title:&nbsp;&nbsp; 
            </td>
            <td width="76%" align="center" valign="middle">&nbsp;
              <input name="blHeadline" type="text" id="blHeadline" value="<?php print $blHeadline; ?>" size="80"> 
              &nbsp; </td>
          </tr>
          <tr> 
            <td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold">Sub Heading:&nbsp;&nbsp;</td>
            <td align="center" valign="middle">&nbsp;
              <input name="blSubHead" type="text" id="blSubHead" value="<?php print $blSubHead; ?>" size="80"> 
              &nbsp; </td>
          </tr>
          <tr> 
            <td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold">File 
              to Share:&nbsp;&nbsp;</td>
            <td align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
              <input name="blUploadFile" type="file" id="gaFileToSend500x3" size="70" /> 
              &nbsp;</td>
          </tr>
          <tr> 
            <td align="right" valign="top" bgcolor="#FFFFFF" class="cts12Bold"> 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td height="20" align="right" class="cts12Bold">&nbsp;</td>
                </tr>
                <tr> 
                  <td height="25" align="right" class="cts12Bold"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Credits 
                    and copy:&nbsp;&nbsp;</td>
                </tr>
              </table></td>
            <td height="330" align="right" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td height="20" valign="middle"> 
                    <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td height="20" valign="middle" class="cts11Regular">&nbsp;</td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td align="center"> 
                    <textarea name="blCopy" cols="79" rows="20" id="elm1"><?php print $blCopy; ?></textarea> 
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
