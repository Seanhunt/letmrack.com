<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include '../local_info.php';
include 'db_lib.php';
include 'cl_lib.php';
include 'file_lib.php';
include 'pw_lib.php';
include 'html_lib.php';
include 'blog_parms.php';
include 'question_lib.php';
include 'blog_lib.php';
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

if($debug) print "blPosterID: $blPosterID<br>";
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
if($blogType=="LIFO" || $blogType=="MUSIC")
	$templateFileName	=	"blog_t_viewer_lifo.html";
else if($blogType=="ORDERED")
	$templateFileName	=	"blog_t_viewer_ordered.html";
else
	$templateFileName	=	"blog_t_viewer_calendar.html";

if($debug) print "templateFileName: $templateFileName<br>";
	
// get the name of the blog
$blogName	=	BlogHeaderGetName($blTable);
	
			
$backLink		=	"blog_home.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $blogName; ?> - View All Posts</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="app_dev_II.css" rel="stylesheet" type="text/css">
<link href="admin.css" rel="stylesheet" type="text/css">
<link href="blog_II.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="40" align="center" class="cts18Bold"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td align="center" class="cts24Bold"><?php print $blogName; ?> - View All Posts</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td align="center" valign="top"> 
            <table width="30%" border="1" cellspacing="0" cellpadding="10">
              <tr>
                <td align="left" class="cts12Bold" valign="top"><?php print $blogDescription; ?></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr bgcolor="#EEEEEE"> 
          <td width="30%" height="40">&nbsp;&nbsp;
		 &lt; <a href="<?php print $backLink; ?>" class="galleryUILink">Back</a> </td>
          <td align="center"><a href="blog_help.php#ViewAllPosts" target="_blank" class="galleryUILink">Help</a> 
          </td>
          <td width="30%" height="40" align="right"> 
          </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="20">&nbsp;</td>
  </tr>
  <tr> 
    <td width="200" height="10" align="center">
<table width="938" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td width="175">&nbsp;</td>
          <td width="560" align="left" valign="top"> 
            <?php
			for($i=0;$i<$numberOfPostings;$i++)
				{
				print BlogGetItem($i,$blTable,$templateFileName);
				}

			if($numberOfPostings==0)
				print "There are no postings to view...";	
			?>
            &nbsp;</td>
          <td width="195" valign="top"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="25" align="center" valign="middle" bgcolor="#CCCCCC" class="cts14Bold">Contents</td>
              </tr>
              <tr> 
                <td align="center" valign="middle" class="cts14Bold">&nbsp;</td>
              </tr>
              <tr> 
                <td align="center" valign="middle" class="cts14Bold"> 
                  <?php

			$templateFileName	=	"blog_template_toc.html";

			for($i=0;$i<$numberOfPostings;$i++)
				{
				print BlogGetItem($i,$blTable,$templateFileName);
				}

			if($numberOfPostings==0)
				print "There are no postings to view...";	
			?>
                </td>
              </tr>
              <tr> 
                <td align="center" valign="middle" class="cts14Bold">&nbsp;</td>
              </tr>
              <tr> 
                <td height="25" align="center" valign="middle" bgcolor="#CCCCCC" class="cts14Bold">Links</td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td align="center" valign="top"> 
                  <?php
			$templateFileName	=	"blog_template_link.html";

			for($i=0;$i<$numberOfLinks;$i++)
				{
				print BlogLinkGetItem($i,$blTable,$templateFileName);
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
      </table> </td>
  </tr>
  <tr> 
    <td height="20" align="center">&nbsp; </td>
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
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>