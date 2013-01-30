<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_show_hide.php
//
// Revision 2.2.0.RC1
// 
// Bug fix: 2.2.0.2 rev was missing include 'gallery_parms.php' so this is a bug that is out there
// lots of folks have 2.2.0.2
//
//
// Revision 2.2.0.8
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
include 'question_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'gallery_lib.php';
include 'gallery_parms.php';
include 'file_lib.php';	
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	

$debug	=	0;

// -----------------------------------------------------------------------------------------------
// Authorize the user and check the role
// -----------------------------------------------------------------------------------------------
if(AuthorizeAdminUser($systemUserID,$systemPassword)!=1)
	{
	print "Access Denied<br>";
	exit(0);
	}

// -----------------------------------------------------------------------------------------------
// Handle clicks
// -----------------------------------------------------------------------------------------------

if($gaMode=="HIDE")
	{
	OpenDatabase();

	$query	=	"UPDATE APPDEV_GalleryProfiles SET Flag='HIDE' WHERE ArtistID='$gaArtistID'";

	if(QueryDatabase($query)==false)
		print "SQL ERROR 1: " . mysql_error() . "<br>";

	CloseDatabase();

	if($debug) print "query: $query<br>";
	}
else if($gaMode=="SHOW")
	{
	OpenDatabase();

	$query	=	"UPDATE APPDEV_GalleryProfiles SET Flag='SHOW' WHERE ArtistID='$gaArtistID'";

	if(QueryDatabase($query)==false)
		print "SQL ERROR 2: " . mysql_error() . "<br>";

	CloseDatabase();

	if($debug) print "query: $query<br>";
	}

if($debug) print "gaMode: $gaMode<br>";



$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";
$addlArgs	=	"SystemUserID=$systemUserID&SystemPassword=$systemPassword";

// -----------------------------------------------------------------------------------------------
// build display lists
// -----------------------------------------------------------------------------------------------

$visible	=	array();
$invisible	=	array();
$record		=	array();

OpenDatabase();

$query	=	"SELECT Title, ArtistID, Flag FROM APPDEV_GalleryProfiles";

$rs	=	QueryDatabase($query);

$numberOfGalleries	=	mysql_numrows($rs);

if($debug) print "numberOfGalleries: $numberOfGalleries<br>";

$numberOfHidden	=	0;

// iterate the galleries
for($i=0;$i<$numberOfGalleries;$i++)
	{
	$title			=	GetDatabaseResult($rs,$i,"Title");	
	$artistID		=	GetDatabaseResult($rs,$i,"ArtistID");	
	$flag			=	GetDatabaseResult($rs,$i,"Flag");	
	
	$galleryTable	=	"APPDEV_GALLERY_$artistID";
	$galleryQuery	=	"SELECT * FROM $galleryTable";

	$hprs			=	QueryDatabase($galleryQuery);
	$galleryHasPics	=	mysql_numrows($hprs);
	
	if($debug) print "<hr>title: $title<br>";
	if($debug) print "artistID: $artistID<br>";
	if($debug) print "flag: $flag<br>";
	if($debug) print "galleryQuery: $galleryQuery<br>";
	if($debug) print "galleryHasPics: $galleryHasPics<br>";
	if($debug) print "galleryTable: $galleryTable<br>";
	
	
	// if SHOW add to visible
	if($flag=="SHOW" && $galleryHasPics)
		{
		$record["Title"]			=	$title;
		$record["ArtistID"]		=	$artistID;
		$visible[]				=	$record;
		
		if($debug) print "*** CAN HIDE ***<br>";
		}
	else if($flag=="HIDE" && $galleryHasPics)	// else add to invisible
		{
		$record["Title"]			=	$title;
		$record["ArtistID"]		=	$artistID;
		$invisible[]			=	$record;
	
		if($debug) print "*** CAN SHOW ***<br>";
		}	
	else if(($flag=="HIDE") && ($galleryHasPics==0))	// else add to invisible
		{
		++$numberOfHidden;
	
		if($debug) print "*** INVISIBLE ***<br>";
		}	
	
	if($debug) print "<hr>";
	}

if($debug) print "numberOfHidden: $numberOfHidden<br>";

CloseDatabase();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Show or Hide Galleries</title>
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
    <td height="30" align="center" valign="middle" class="admin18Bold">Show or 
      Hide Galleries</td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#GalleryShowHide" target="_blank" class="galleryUILink">Help</a></td>
          <td width="33%">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td class="admin12Bold" height="40" align="left" valign="middle">&nbsp;&nbsp;
	<?php
	if($numberOfHidden==1)
		print "$numberOfHidden gallery is not displayed because it has 0 images, you can't show a gallery with 0 images.";
	else if($numberOfHidden>1)
		print "$numberOfHidden galleries are not displayed because they have 0 images, you can't show a gallery with 0 images.";
	?>
	
	
	</td>
  </tr>
  <tr> 
    <td align="center" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="13%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="65%" align="center">
<table width="95%" border="0" cellspacing="0" cellpadding="0">
              <tr align="center" bgcolor="#666666" class="whiteHeader24"> 
                <td height="50">Visible</td>
                <td height="30">Hidden</td>
              </tr>
              <tr align="center" bgcolor="#efefef" class="admin14Bold"> 
                <td width="51%" height="30">Click to Hide</td>
                <td width="49%" height="30">Click to Show</td>
              </tr>
              <tr align="center"> 
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr align="center"> 
                <td valign="top"> 
                  <?php
				$n	=	count($visible);
				
				for($i=0;$i<$n;$i++)
					{
					$title		=	$visible[$i]["Title"];
					$artistID	=	$visible[$i]["ArtistID"];
					$url		=	"gallery_show_hide.php?gaArtistID=$artistID&gaMode=HIDE&$addlArgs";
					print '<a class=boldLink12 href=' . $url . '>' . "$title - $artistID</a><br><br>";
					}
				?>
                </td>
                <td valign="top"> 
                  <?php
				$n	=	count($invisible);
				
				for($i=0;$i<$n;$i++)
					{
					$title		=	$invisible[$i]["Title"];
					$artistID	=	$invisible[$i]["ArtistID"];
					$url		=	"gallery_show_hide.php?gaArtistID=$artistID&gaMode=SHOW&$addlArgs";
					print '<a class=boldLink12 href=' . $url . '>' . "$title - $artistID</a><br><br>";
					}
				?>
                </td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td bgcolor="#666666">&nbsp;</td>
                <td bgcolor="#666666">&nbsp;</td>
              </tr>
            </table>
          </td>
          <td width="17%" bgcolor="#999999">&nbsp;</td>
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
