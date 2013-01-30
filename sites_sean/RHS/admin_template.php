
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// admin_template.php
//
// Revision 2.2.0.RC1
// 
//
// Revision 2.2.0.2
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'admin_lib.php';
include 'file_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	

include 'admin_lib.php';
include 'appdev_revision.php';	

$backLink				=	"admin_home.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Admin Home</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- Hide from the robots -->		
<meta name="robots" content="noindex,nofollow">
<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
<META NAME="GOOGLEBOT" CONTENT="nofollow">
<META NAME="GOOGLEBOT" CONTENT="noindex">
<!-- End Hide from the robots -->
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" align="center" valign="middle" class="admin18Bold">Admin Template</td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">          
            <div class="adminUIHeader">
            <p>
            <form action="<?php print $backLink; ?>" method="post" name="back_link" id="back_link">
            &nbsp;&nbsp;&lt; <a onClick="back_link.submit()" a href="#">Back</a>
            <input name="SystemUserID" type="hidden" value="<?php print $systemUserID; ?>" />
            <input name="SystemPassword" type="hidden" value="<?php print $systemPassword; ?>" />
            </form>
            </p>
            </div>

</td>
          <td width="33%" align="center"><a href="admin_help.php#SectionName" target="_blank" class="galleryUILink">Help</a></td>
          <td width="33%">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="3%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="86%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr align="center" class="admin15Bold"> 
                <td width="8%" height="50">Score</td>
                <td width="45%" height="50">Title</td>
                <td width="36%" height="50">Filename</td>
                <td width="11%" height="50">Type</td>
              </tr>
              <tr> 
                <td>SCORE</td>
                <td>TITLE</td>
                <td>FILENAME</td>
                <td>TYPE</td>
              </tr>
            </table></td>
          <td width="5%" bgcolor="#999999">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE">&nbsp;</td>
  </tr>
  <tr> 
    <td align="left" valign="top"> 
      <?php
	PrintAdminFooter();
	?>
    </td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>



<?php
DisplayStatusMessage($statusMessage);
?>
