
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// table_home.php
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

$backLink				=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";

// ------------------------------------------------------------------------------
// Init the table selector for view Table
// ------------------------------------------------------------------------------

$superUser		=	UserIsSuper($systemUserID);
$userTableName	=	GetUserTableName();

$numberOfTables	=	GetNumberOfTables();
 
$tableArray		=	array();

OpenDatabase();


// PATCH .3 100407 - tables were not showing up in table selector
$rs 			= QueryDatabase("SHOW TABLES");

for($i=0;$i<$numberOfTables;$i++)
	{
	$tableName		=	GetTableNameByIndex($i,$rs);
	
	
	if($debug) print "tableName: $tableName<br>";
	
	//  show the user table to  the super user
	if(strcmp($userTableName,$tableName)==0 && $superUser)
		{
		$tableArray[]	=	$tableName;
		}

	// dont show the user table to anyone but the super user
	if(strcmp($userTableName,$tableName)!=0)
		{
		$tableArray[]	=	$tableName;
		}
	}


CloseDatabase();	

//print_r($tableArray);


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>View and Export Data</title>
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
    <td height="30" align="center" valign="middle" class="rhs18Bold">View and Export Data</td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="javascript:window.close();" class="galleryUILink" >Close Window</a></td>
          <td width="33%" align="center"><a href="admin_help.php#TableHome" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="86%" align="center">
          
          
          
          
                <form name="ViewTables" method="POST" action="table_show.php">
                    <table width="80%" border="1" cellspacing="0" cellpadding="0">
                      <tr align="center" valign="middle"> 
                        <td width="71%" align="center"> 
                          <?php
						DisplaySelector("TableName",$tableArray,$tableArray,"");
						?>                        </td>
                        <td width="29%" height="35"> <input type="submit" name="Submit2" value="Go"> 
                          &nbsp;&nbsp; <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>"> 
                          <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>"> 
                        </td>
                      </tr>
                    </table>
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
