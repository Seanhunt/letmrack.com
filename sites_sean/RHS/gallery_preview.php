<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include "../local_info.php";
include "db_lib.php";
include "cl_lib.php";
include "admin_lib.php";
include "file_lib.php";
include "html_lib.php";
include 'gallery_lib.php';
include 'gallery_parms.php';
include "appdev_revision.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Preview Your Gallery Profile</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="../images/css/local.css" rel="stylesheet" type="text/css">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="955" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="40" bgcolor="#CCCCCC"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr bgcolor="#EEEEEE"> 
          <td width="30%" height="40">
		  &nbsp;&nbsp;<a href="#" class="galleryUILink"  onClick="javascript:window.close();">Close</a> 
          </td>
          <td align="center"> <a href="gallery_help.php#ModifyLibraryData" target="_blank" class="galleryUILink">Help</a> 
          </td>
          <td width="30%">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td><table width="955" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="15%" bgcolor="#666666">&nbsp;</td>
          <td valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="10">&nbsp;</td>
              </tr>
              <tr> 
                <td> 
                  <?php
				  print GetGalleryProfile($gaArtistID);
				  ?>
                </td>
              </tr>
              <tr> 
                <td height="10">&nbsp;</td>
              </tr>
            </table></td>
          <td width="10%" bgcolor="#999999">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="40" align="left" valign="middle" bgcolor="#eeeeee"> 
      <?php
PrintUserMessage($systemUserID);
?>
    </td>
  </tr>
  <tr> 
    <td>
	
			<?php
			// print the CT chop and footer
			PrintGalleryFooter();
			?>			
	
	
	</td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>
