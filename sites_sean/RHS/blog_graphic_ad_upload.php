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
include 'file_lib.php';
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
$blogName		=	BlogHeaderGetName($blTable);

$buttonName		=	"Upload Graphic Ad";
$helpLink		=	"blog_help.php#GraphicAdUpload";
$backLink		=	"blog_home.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $blogName; ?> - Upload Graphic Ad</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<meta name="robots" content="noindex">
<meta name="robots" content="nofollow">
<meta name="robots" content="none">

<link href="app_dev_II.css" rel="stylesheet" type="text/css">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="955" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr> 
    <td align="center" class="cts18Bold"><?php print $blogName; ?> - Upload Graphic Ad</td>
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
        <input name="blMode" type="hidden" id="blMode" value="GraphicAdUpload">
        <input name="blItemNumber" type="hidden" id="blItemNumber" value="<?php print $blItemNumber; ?>">
        <input name="blTable" type="hidden" id="blTable" value="<?php print $blTable; ?>">
        <input name="blCaller" type="hidden" id="blCaller" value="<?php print $blCaller; ?>">
        <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
        <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print "$systemUserID"; ?>">
        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
       <input name="blItemType" type="hidden" id="blItemType" value="TEXT">

		
		<table width="800" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="15%" height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"> 
              <p><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Ad ID:&nbsp;&nbsp; 
                <br>
                Not Shown&nbsp;&nbsp; </p></td>
            <td width="85%" align="left" valign="middle"> &nbsp; <input name="blHeadline" type="text" id="blHeadline" value="<?php print $blHeadline; ?>" size="80"> 
              &nbsp; </td>
          </tr>
          <tr> 
            <td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold">Headline:&nbsp;&nbsp;</td>
            <td align="left" valign="middle"> &nbsp; <input name="blSubHead" type="text" id="blSubHead" value="<?php print $blSubHead; ?>" size="80"> 
              &nbsp; </td>
          </tr>
          <tr> 
            <td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"> 
              Link Target URL:&nbsp;&nbsp;</td>
            <td align="left" valign="middle" class="cts12Bold">&nbsp;&nbsp;&nbsp;&nbsp;http://&nbsp; 
              <input name="blCopy" type="text" id="blCopy" value="<?php print $blCopy; ?>" size="75"></td>
          </tr>
          <tr> 
            <td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"><span class="admin18BoldRed">*</span>Graphic 
              Ad:&nbsp;&nbsp;</td>
            <td align="left" valign="middle">&nbsp; <input name="blUploadFile" type="file" id="gaFileToSend500x" size="70" /> 
              &nbsp;</td>
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
