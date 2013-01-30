
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// download_asset.php? dlFileName dlTitle and dlPath
//
// 
// I am moving the asset server code into here and if you come here I am going to count it as
// a download. Chumpy but something for now...
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
include 'RHS/admin_lib.php';
include 'RHS/question_lib.php';
include 'RHS/file_lib.php';
include 'RHS/blog_lib.php';
include 'RHS/html_lib.php';
include 'RHS/pw_parms.php';	
include 'RHS/pw_lib.php';
include 'RHS/status_message.php';	
include 'RHS/select_controls.php';	
include 'RHS/download_lib.php';	

$score			=	UpdateDownloadStats($dlFileName,$dlTitle,$dlPath);

$downloadPath	=	"$gSiteURL$gBaseMountPoint/download_asset.php?dlFileName=$dlFileName&dlTitle=" . urlencode($dlTitle) . "&dlPath=$dlPath";
$playPath		=	"$gSiteURL$gBaseMountPoint/RHS/play_counter.php?dlFileName=$dlFileName&dlTitle=" . urlencode($dlTitle) . "&dlPath=$dlPath";

$assetLink		=	"$gSiteURL$gBaseMountPoint$dlPath/$dlFileName";
$downloadLink	=	'&lt;a href=&quot;' . $downloadPath . '&quot;&gt;' . $dlTitle . '&lt/a&gt;';
$playLink		=	'&lt;a href=&quot;' . $playPath . '&quot;&gt;' . $dlTitle . '&lt/a&gt;';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print "$gSiteName Download $dlTitle"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="robots" content="noindex,nofollow">
<meta name="robots" content="noarchive">
<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
<META NAME="GOOGLEBOT" CONTENT="nofollow">
<META NAME="GOOGLEBOT" CONTENT="noindex">

<SCRIPT LANGUAGE="JavaScript"> 
<!--
window.resizeTo(600,750);
-->
</SCRIPT>


<link href="images/css/local.css" rel="stylesheet" type="text/css">
</head>

<body>
<!--- Include for Tool Tips ******************************* --->
<script type="text/javascript" src="wz_tooltip.js"></script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle"> <h1>Download<br>
    </h1>
    <?php print "$dlTitle"; ?></td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40"> <h4>&nbsp;&nbsp;< <a href="#" onClick="javascript:window.close();">Close 
            Window</a></h4></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="12%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="4%">&nbsp;</td>
          <td width="51%" valign="top">
<table width="91%" border="0" cellpadding="0" cellspacing="0" >
              <tr> 
                <td height="10" align="left"><strong>Title:</strong> <?php print "$dlTitle"; ?></td>
              </tr>
              <tr> 
                <td height="10" align="left"><strong>Filename:</strong> <?php print "$dlFileName"; ?></td>
              </tr>
              <tr> 
                <td height="10" align="left"><strong>Downloads:</strong> <?php print "$score"; ?></td>
              </tr>
              <tr> 
                <td height="50" align="center" valign="middle"><h3><a href="<?php print "$assetLink"; ?>" target="_blank">Right 
                Click and Select Save to Download</a></h3></td>
              </tr>
              <tr > 
                <td align="left"> <p class="rhs14Bold"><em><strong>Deep link 
                to this asset - file sharing is good!!!!</strong></em></p></td>
              </tr>
              <tr> 
                <td align="left" valign="middle">
                
                  <p class="rhs10Regular">&nbsp;</p>
                  <p class="rhs10Regular">Download Link                  </p>
                  <p>
                    <textarea name="textArea" id="textArea" cols="45" rows="5"><?php print "$downloadLink"; ?>
                    </textarea>
                  </p>
                <p class="rhs10Regular">Play Link</p>
                <p>
                  <textarea name="textArea2" id="textArea2" cols="45" rows="5"><?php print "$playLink"; ?>
                    </textarea>
</p>
                <p align="center" class="rhs12Bold">Visit us at <a href="<?php print $gSiteURL; ?>"><?php print $gSiteName; ?></a></p></td>
              </tr>
            </table></td>
          <td width="2%">&nbsp; </td>
          <td width="31%" bgcolor="#999999">&nbsp;</td>
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
    <td align="left" valign="top">&nbsp; </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>
