
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// blog_create.php
//
// This will create a blog table, it is a super user function
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
include 'file_lib.php';
include 'blog_lib.php';
include 'blog_parms.php';
include 'html_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	

$debug	=	0;
$error	=	0;

// -----------------------------------------------------------------------------------------------
// Authorize the user and check the role
// -----------------------------------------------------------------------------------------------
if(AuthorizeAdminUser($systemUserID,$systemPassword)!=1)
	{
	print "Access Denied<br>";
	exit(0);
	}


$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";

if($blMode=="CREATE")
	{
	if($debug) print "Create Election...<br>";

	$electionName	=	GetPassedValue("ElectionName");
	$electionType	=	GetPassedValue("Type");

	if($debug) print "electionName: $electionName<br>";
	if($debug) print "electionType: $electionType<br>";

	$query	=	"INSERT INTO 
				APPDEV_PublicVoteElections 
				(ElectionName, Type) 
				VALUES
				('$electionName','$electionType')
				";

	OpenDatabase();

	ShowDatabaseError(QueryDatabase($query));	
	
	CloseDatabase();

	if($debug) print "End Create Election...<br>";
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>New Election</title>
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
    <td height="30" align="center" valign="middle" class="admin18Bold">New 
      Election</td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#NewElection" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="77%" align="center" valign="top"> 
		  <form name="form1" method="post" action="public_vote_create_election.php">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top">&nbsp;</td>
                </tr>
                <tr> 
                  <td align="center" valign="top"> <table width="66%" border="1" cellspacing="0" cellpadding="0">
                      <tr valign="middle"> 
                        <td width="27%" height="35" align="right" class="admin14Bold">ElectionID:&nbsp;&nbsp;</td>
                        <td width="73%" height="35" align="left">&nbsp;&nbsp; 
                          <input name="ElectionName" type="text" id="ElectionName" size="36"></td>
                      </tr>
                      <tr valign="middle"> 
                        <td height="35" align="right" class="admin14Bold">Type:&nbsp;&nbsp;</td>
                        <td height="35" align="left">&nbsp;&nbsp; 
						<select name="Type" id="select">
                            <option value="VotePerPiece" selected>VotePerPiece</option>
                            <option value="VotePerIP">VotePerIP</option>
                          </select></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td height="50" align="center"> <input name="blMode" type="hidden" id="blMode" value="CREATE"> 
                    <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print "$systemUserID"; ?>"> 
                    <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print "$systemPassword"; ?>"> 
                    <input type="submit" name="Submit" value="Create"> </td>
                </tr>
              </table>
              </form>
            
          </td>
          <td width="20%" bgcolor="#999999">&nbsp;</td>
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
