
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

$debug = 0;
$error			=	0;
$writeEnabled	=	true;

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
	if($debug) print "blMode: $blMode<br>";
	if($debug) print "blTable: $blTable<br>";
	if($debug) print "blDescription: $blDescription<br>";
	if($debug) print "blType: $blType<br>";
	if($debug) print "blName: $blName<br>";
	if($debug) print "blModerator: $blModerator<br>";

	$createLinksTable	=	GetPassedValue("radio");

	if($debug) print "createLinksTable: $createLinksTable<br>";

	// require a title
	if($blName=="")
		{
		$error			=	1;
		$statusMessage	=	"Please enter a title...";
		}
	else
		{
		// make the table name from the first n chars of the title
		$strippedName	=	str_replace(" ","_",$blName);
		$strippedName	=	strtolower($strippedName);
		
		if($debug) print "strippedName: $strippedName<br>";
		}	
	// decorate the table names
	$decoratedTableName			=	"APPDEV_BLOG_$strippedName";
	$decoratedLinksTableName	=	"APPDEV_LINKS_$strippedName";

	if($debug) print "decoratedTableName: $decoratedTableName<br>";
	if($debug) print "decoratedLinksTableName: $decoratedLinksTableName<br>";

	if(!$error)
		{
		// make blog table
		if($debug) print "CREATE BLOG TABLE<br>";

		if(CreateBlogTable($decoratedTableName)==false)
			{
			$error	=	1;
			$statusMessage	=	"Unable to create blog table...";
			}
		}	

	if(!$error && $writeEnabled)
		{
		// make blog header entry
		if($debug) print "CREATE BLOG HEADER RECORD<br>";
		
		if(CreateBlogHeaderRecord($blName,$blDescription,$decoratedTableName,$blType)==false)
			{
			$error			=	1;
			$statusMessage	=	"Unable to create blog header record...";
			}
		}

	if(!$error && $writeEnabled)
		{
		if($debug) print "CREATE USER PERMISSION<br>";
	
		NewUserPermission($blModerator,$decoratedTableName,1,0);
		}
		
	if($debug) print "statusMessage: $statusMessage<br>";
	if($debug) print "error: $error<br>";
	
	// set up the links table
	
	if(!$error && $createLinksTable && $writeEnabled)
		{
		if($debug) print "CREATE LINKS TABLE<br>";

		if(CreateBlogLinksTable($decoratedLinksTableName)==false)
			{
			$error	=	1;
			$statusMessage	=	"Unable to create links table...";
			}
		}
		
	if(!$error)
		$statusMessage	=	"New Blog Created";	
	} // end if($blMode=="CREATE")



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>New Blog</title>
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
      Blog </td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#BlogCreate" target="_blank" class="galleryUILink">Help</a></td>
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
		  <form name="form1" method="post" action="blog_create.php">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top">&nbsp;</td>
                </tr>
                <tr> 
                  <td align="center" valign="top"> <table width="66%" border="1" cellspacing="0" cellpadding="0">
                      <tr valign="middle">
                        <td height="35" align="right" class="admin14Bold">Moderator:&nbsp;&nbsp;</td>
                        <td height="35" align="left"><span class="admin12Bold">&nbsp;&nbsp;
                          <?php

						// print the list of users
			
						$query			=	"SELECT pwUserID, pwFirstName, pwLastName FROM APPDEV_UserInfo";

						OpenDatabase();
						
						$rs				=	QueryDatabase($query);
						
						$numberOfUsers	=	mysql_numrows($rs);
						
						if($debug2) print "query: $query<br>";
						if($debug2) print "numberOfUsers: $numberOfUsers<br>";
						
						CloseDatabase();
			
						$labels		=	array();
						$values		=	array();
						
						$labels[]	=	"Pick one...";
						$values[]	=	"Pick";
						
						for($i=0;$i<$numberOfUsers;$i++)
							{
							$uid	=	GetDatabaseResult($rs,$i,"pwUserID");
							$first	=	GetDatabaseResult($rs,$i,"pwFirstName");
							$last	=	GetDatabaseResult($rs,$i,"pwLastName");
							
							$labels[]	=	"$first $last - $uid";
							$values[]	=	"$uid";
							}

						DisplaySelector("blModerator",$labels,$values,"moderator");				
						?>
                        </span></td>
                      </tr>
                      <tr valign="middle"> 
                        <td width="27%" height="35" align="right" class="admin14Bold"><span class="admin15BoldRed">*</span>&nbsp;Title:&nbsp;&nbsp;</td>
                        <td width="73%" height="35" align="left">&nbsp;&nbsp; 
                          <input name="blName" type="text" id="blName2" size="36"></td>
                      </tr>
                      <tr valign="middle"> 
                        <td height="35" align="right" class="admin14Bold">Description:&nbsp;&nbsp;</td>
                        <td height="35" align="left">&nbsp;&nbsp; <input name="blDescription" type="text" id="blDescription2" size="36"></td>
                      </tr>
                      <tr valign="middle">
                        <td height="35" align="right" class="admin14Bold">Create Links:&nbsp;</td>
                        <td height="35" align="left" valign="middle" class="rhs12Regular">&nbsp;&nbsp; Yes 
                        <input type="radio" name="radio" id="radio" value="1">
                        &nbsp;&nbsp; No 
                        <input name="radio" type="radio" id="radio2" value="0" checked></td>
                      </tr>
                      <tr valign="middle"> 
                        <td height="35" align="right" class="admin14Bold">Type:&nbsp;&nbsp;</td>
                        <td height="35" align="left">&nbsp;&nbsp; <select name="blType" id="select">
                            <option value="LIFO" selected>LIFO - Regular</option>
                            <option value="CALENDAR">Calendar</option>
                            <option value="ORDERED">Ordered-Random</option>
                            <option value="MUSIC">Music</option>
                          </select></td>
                      </tr>
                  </table></td>
                </tr>
                <tr> 
                  <td height="50" align="center"> <input name="blMode" type="hidden" id="blMode" value="CREATE"> 
                    <input name="First" type="hidden" id="First" value="<?php print "$first"; ?>"> 
                    <input name="Last" type="hidden" id="Last" value="<?php print "$last"; ?>"> 
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
    <td height="40" align="left" valign="middle"><span class="admin15BoldRed">&nbsp;&nbsp;* Required Field</span></td>
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
