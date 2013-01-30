
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// admin_new_pw_target.php
//
// This will create a new password target entry in the database.
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

$debug				=	0;

// -----------------------------------------------------------------------------------------------
// Authorize the user and check the role
// -----------------------------------------------------------------------------------------------
if(AuthorizeAdminUser($systemUserID,$systemPassword)!=1)
	{
	print "Access Denied<br>";
	exit(0);
	}


$backLink			=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";
$mode				=	GetPassedValue("Mode");
$userID				=	GetPassedValue("UserID");
$password			=	GetPassedValue("Password");
$targetPageName		=	GetPassedValue("TargetPageName");
$targetGalleryName	=	GetPassedValue("TargetGalleryName");
$radioButton		=	GetPassedValue("radio");

if($mode != "")
	{
	if($debug) print "mode: $mode<br>";
	if($debug) print "userID: $userID<br>";
	if($debug) print "password: $password<br>";
	if($debug) print "targetPageName: $targetPageName<br>";
	if($debug) print "targetGalleryName: $targetGalleryName<br>";
	if($debug) print "radioButton: $radioButton<br>";

	if($radioButton == "PageName")
		{
		$rv	=	AddPasswordTarget($targetPageName,"",$userID,$password);
		
		if($rv==false)
			$statusMessage	=	"Database Error - Cannot create new target";
		}
	else // == GalleryName
		{
		$rv	=	AddPasswordTarget("",$targetGalleryName,$userID,$password);
		
		if($rv==false)
			$statusMessage	=	"Database Error - Cannot create new target";
		}

	if($statusMessage == "")
		$statusMessage = "Target Created";
	}
	
// function AddPasswordTarget($targetPageName,$userID,$password)

function AddPasswordTarget($targetPageName,$targetGalleryName,$userID,$password)
{
$writeEnabled	=	true;
$debug			=	0;

if($debug) print "<hr>AddPasswordTarget($targetPageName,$targetGalleryName,$userID,$password)<br>";


$query	=	"SELECT * FROM APPDEV_PasswordTargets 
			WHERE pwUserID='$userID' 
			AND pwPassword='$password'
			";

if($debug) print "query: $query<br>";

// if this target exists return false
if(GetNumberOfRows($query))
	{
	if($debug) print "AddPasswordTarget - returned FALSE - UID-PW Exists...<br>";
	return true;	
	}
	
OpenDatabase();

$query 		= 	sprintf("INSERT INTO APPDEV_PasswordTargets 
						 (pwUserID, pwPassword, pwTargetGalleryName, pwTargetPageName) 
						 VALUES 
						 ( '%s' , '%s' , '%s' , '%s' )",
						mysql_real_escape_string($userID),	
						mysql_real_escape_string($password),	
						mysql_real_escape_string($targetGalleryName),	
						mysql_real_escape_string($targetPageName)
						);

if($debug) print "query: $query<br>";

if($writeEnabled)
	{
	if($debug) print "AddPasswordTarget - WRITE ENABLED<br>";

	if(($rv=QueryDatabase($query))==false)
		{
		print "SQL Error in AddPasswordTarget() - returned FALSE " . mysql_error() . "<br>";
		}
	}
else
	{
	if($debug) print "AddPasswordTarget - WRITE DISABLED<br>";
	}

if($debug) print "AddPasswordTarget() - returned TRUE<br>";

CloseDatabase();

return true;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>New Password Target</title>
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
    <td height="30" align="center" valign="middle" class="admin18Bold">New Password Target</td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#PasswordTarget" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="86%"><form name="form1" method="post" action="">
            <table width="60%" border="1" align="center" cellpadding="0" cellspacing="0">
              
              <tr>
                <td width="31%" height="30" align="right" class="rhs12Bold">Target Page Name:&nbsp;&nbsp;</td>
                <td width="69%" align="left" class="rhs12Regular">
                  &nbsp;&nbsp;
                  <input name="TargetPageName" type="text" id="TargetPageName" value="<?php print $targetPageName; ?>" size="35"></td>
              </tr>
              <tr>
                <td height="30" align="right" class="rhs12Bold">Target Gallery:&nbsp;&nbsp;</td>
                <td align="left" class="rhs12Regular"> &nbsp;&nbsp;
                
                <?php
				$labels	=	array();
				$values	=	array();
				
				
				$query	=	"SELECT ArtistID FROM APPDEV_GalleryProfiles";
				
				OpenDatabase();
				
				$rs	=	QueryDatabase($query);
				
				CloseDatabase();
				
				$numberOfRows	=	GetNumberOfRows($query);
				
				for($i=0;$i<$numberOfRows;$i++)
					{
					$labels[]	=	GetDatabaseResult($rs,$i,"ArtistID");
					$values[]	=	GetDatabaseResult($rs,$i,"ArtistID");
					}
				
				DisplaySelector("TargetGalleryName",$labels,$values,$targetGalleryName);
				?>                </td>
              </tr>
              <tr>
                <td height="30" align="right" class="rhs12Bold">Mode:&nbsp;&nbsp;</td>
                <td align="left" valign="middle" class="rhs12Regular">
                    &nbsp;&nbsp;Page Name 
                    <input name="radio" type="radio" id="RB" value="PageName" checked> 
                    &nbsp;&nbsp;Gallery 
                    <input type="radio" name="radio" id="RB2" value="GalleryName"></td>
              </tr>
              <tr>
                <td height="30" align="right" class="rhs12Bold">User ID:&nbsp;&nbsp;</td>
                <td align="left" class="rhs12Regular">&nbsp;&nbsp;<input name="UserID" type="text" id="UserID" value="<?php print $userID; ?>" size="35"></td>
              </tr>
              <tr>
                <td height="30" align="right" class="rhs12Bold">Password:&nbsp;&nbsp;</td>
                <td align="left" class="rhs12Regular">&nbsp;&nbsp;<input name="Password" type="text" id="Password" value="<?php print $password; ?>" size="35"></td>
              </tr>

              <tr>
                <td height="40" colspan="2" align="center" class="rhs12Bold">
                  <input type="submit" name="Mode" id="button" value="Create New Target"></td>
                </tr>
            </table>
            
          <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>"> 
          <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>"> 
            
          </form>
          </td>
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
