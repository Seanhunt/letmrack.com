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
include "mail_lib.php";

$debug				=	0;

$guid		=	GetPassedValue("GUID");

// get the loc from the GUID
$location	=	GetFieldByComparison("APPDEV_FileLocations","Location","GUID",$guid);

$ipAddress 	= 	gethostbyname($REMOTE_ADDR);
$hostName 	= 	gethostbyaddr($_SERVER['REMOTE_ADDR']);
$fileName	=	basename($location);		

if($debug) print "guid: $guid<br>";
if($debug) print "location: $location<br>";

// record the users information
$query		=	"
INSERT INTO `APPDEV_FileDelivery` ( `IpAddress` , `Domain`, `FileName`  , `TimeStamp` , `Index` )
VALUES ('$ipAddress','$hostName','$fileName',CURRENT_TIMESTAMP , NULL);
";

OpenDatabase();
$rv			=	QueryDatabase($query);
CloseDatabase();

// get the timestamp back
$date	=	date("m/d/Y");
$time	=	date("g:i:s");

// send a message to the site admin that a pickup is being done
$message	=	"$ipAddress at $hostName is picking up $fileName on $date at $time";
$subject	=	"[$gSiteName] FTP Pickup Notice";

// send delivery memo mail to site secretary
email_send($gSiteSecretaryAddress,$gSiteSecretaryAddress,"FTP Manager",$subject,$message);


if($debug) print "rv: $rv<br>";
if($debug) print "fileURL: $fileURL<br>";
if($debug) print "ipAddress: $ipAddress<br>";
if($debug) print "hostName: $hostName<br>";
if($debug) print "fileName: $fileName<br><hr><br><br>";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $gSiteName; ?> FTP File Delivery</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

	<!-- Hide from the robots -->		
	<meta name="robots" content="noindex,nofollow">
	<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
	<META NAME="GOOGLEBOT" CONTENT="nofollow">
	<META NAME="GOOGLEBOT" CONTENT="noindex">
	<!-- End Hide from the robots -->


<link href="app_dev_II.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="70" align="center" valign="top" class="cts24Bold"><?php print $gSiteName; ?> 
      FTP Delivery</td>
  </tr>
  <tr>
    <td><table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td width="46%"><p class="cts15Regular">Here is the private link to 
              your file:</p>
            <p class="cts15Regular"><a class="boldLink14" href="<?php print $location; ?>"><?php print $fileName; ?></a></p>
            <p class="cts15Regular">Right - Click on PC or Option - Click on Apple 
              Macintosh and select the save option from the context menu that 
              appears. </p>
            <p class="cts15Regular">The file will be downloaded to your local 
              system.</p>
            <p class="cts15Regular">Thanks for using <?php print $gSiteName; ?></p>
            <p class="cts15Regular">&nbsp;</p></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="60" align="center"><span class="cts14Regular">Visit</span> <a class="boldLink14" href="<?php print $gSiteURL; ?>"><?php print $gSiteName; ?></a></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
