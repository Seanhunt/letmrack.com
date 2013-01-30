
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// admin_template.php
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
include 'file_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	
include 'public_vote_lib.php';

$debug			=	0;

$backLink		=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";
$electionName	=	GetPassedValue("ElectionName");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>View Public Vote Tabulation</title>
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
    <td height="30" align="center" valign="middle" class="admin18Bold">Public 
      Voting Tabulation</td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
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
          <td width="12%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="65%">
		  
		  
		  
		  <table width="92%" border="1" align="center" cellpadding="0" cellspacing="0">
              	<?php

				//$electionName	=	"Test001";
				
				$numberOfRows	=	GetNumberOfRows("SELECT * FROM APPDEV_PublicVoteTabulation WHERE ElectionName='$electionName'");

				if($numberOfRows==0)
					print 	'
							  <tr align="center" class="admin15Bold"> 
								<td width="82%" height="50">The election "' . $electionName . '" has no votes...</td>
							  </tr>
							';
				else
					{
					// print the header row
					print	'
							  <tr align="center" class="admin15Bold"> 
								<td width="9%" height="50">Score</td>
								<td width="9%" height="50">Votes</td>
								<td width="82%" height="50">Filename</td>
							  </tr>
							';
					}
					
				if($debug) print "electionName: $electionName<br>";
				if($debug) print "numberOfRows: $numberOfRows<br>";

				OpenDatabase();
				
				$rs	=	QueryDatabase("SELECT * FROM APPDEV_PublicVoteTabulation WHERE ElectionName='$electionName' ORDER BY Count DESC");
					
				CloseDatabase();
				
				for($i=0;$i<$numberOfRows;$i++)
					{
					$score		=	GetDatabaseResult($rs,$i,"Score");	
					$votes		=	GetDatabaseResult($rs,$i,"Count");	
					$fileName	=	GetDatabaseResult($rs,$i,"FileName");	
				
					print	"
							<tr height=30 class=admin12Bold> 
								<td align=center>$score</td>
								<td align=center>$votes</td>
								<td align=center>$fileName</td>
							</tr>
							";
					}
?>
            </table>
            <p>&nbsp;</p></td>
          <td width="23%" bgcolor="#999999">&nbsp;</td>
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
