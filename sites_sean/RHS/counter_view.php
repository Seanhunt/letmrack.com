<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// counter_view.php
//
// This will display the simple counters for a given site
// 
// Revision:	071506 File Originated
//				080906 Added prior counting 
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
include 'counter_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
include 'pw_parms.php';	
include 'file_lib.php';
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	


// -----------------------------------------------------------------------------------------------
// Authorize the user and check the role
// -----------------------------------------------------------------------------------------------

if(AuthorizeAdminUser($systemUserID,$systemPassword)!=1)
	{
	print "Access Denied<br>";
	exit(0);
	}


$debug	=	0;

$mode	=	GetPassedValue("Mode");

if($mode=="Reset")
	{
	// set up the prior counts
	CounterSetPrior();
	}
	
$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $gSiteName . " "; ?> Page Counts</title>
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
    <td height="30" align="center" valign="middle" class="admin18Bold"><span class="body18Bold"><?php print $gSiteName . " "; ?>Page 
      Counts</span></td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
        <td width="33%" align="center"><a href="admin_help.php#CounterView" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="14%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="47%"> <table width="90%" height="26" border="1" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="admin12Regular">
              <tr bgcolor="#EFEFEF"> 
                <td width="66%" height="25" align="center" bordercolor="#333333"><b>Page 
                  File Name</b></td>
                <td width="18%" align="center"><b>Views</b></td>
                <td width="16%" align="center"><b>Prior</b></td>
              </tr>
              <?php
		$numberOfCounters	=	GetNumberOfRows("SELECT * FROM APPDEV_SimpleCounters");
		
		if($debug) print "numberOfCounters: $numberOfCounter";
		
		for($i=0;$i<$numberOfCounters;$i++)
			{
			$page	=	GetFieldByIndex("APPDEV_SimpleCounters","PageFileName",$i);
			$count	=	GetFieldByIndex("APPDEV_SimpleCounters","Count",$i);
			$prior	=	GetFieldByIndex("APPDEV_SimpleCounters","PriorCount",$i);
			
			print	'
					<tr>
					  <td height="25" align="center">&nbsp;&nbsp;' . $page . '</td>
					  <td align="center">&nbsp;&nbsp;' . $count . '</td>
					  <td align="center">&nbsp;&nbsp;' . $prior . '</td>
					</tr>
					';	
		
			}
		?>
            </table></td>
          <td width="39%" bgcolor="#999999">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td height="50" align="center" valign="middle" bgcolor="#888888"> 
      <form name="form1" method="get" action="counter_view.php">
        <input type="submit" name="Submit" value="Reset Prior Counts">
        <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print "$systemUserID"; ?>">
        <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print "$systemPassword"; ?>">
        <input name="Mode" type="hidden" id="Mode" value="Reset">
      </form></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td height="40" align="left" valign="middle" bgcolor="#EEEEEE">
      <?php
	PrintUserMessage($systemUserID);
	?>
    </td>
  </tr>
  <tr> 
    <td align="left" valign="top"> 
      <?php
	PrintAdminFooter();
	?>
    </td>
  </tr>
</table>




</body>
</html>
