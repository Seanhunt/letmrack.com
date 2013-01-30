
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// mail_sub_delete.php
//
// 032507 File originated
//
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
include 'status_message.php';	
include 'select_controls.php';	
include 'file_lib.php';	
include 'appdev_revision.php';	
include 'mail_sub_parms.php';	

$backLink	=	"admin_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";

$debug		=	0;

$ynLink		=	quBuildArgs("mail_sub_delete.php","mail_sub_delete.php","Do you really want to delete these name from your mailing list %3F","Callout");

if($debug) print "ynLink: $ynLink<br>";
if($debug) print "mode: $mode<br>";

if($mode=="SetTable")
	{
	$tableName	=	"$mailTableName";
	}

if($tableName=="")
	$tableName	=	"$gMailDatabaseName";

if($mailTableName!="")
	$tableName	=	"$mailTableName";


if($mode=="DeleteMailingListNames")
	{
	if($debug) print "Delete Mode...<br>";

	// get the count of the post array
	$count	=	count($_GET);

	// set internal pointer to element 0
	reset($_GET);
	
	if($debug) print "count: $count<br>";
	
	$deleteQuery		=	"";
	
	$numberOfDeletions	=	0;
		
	for($i=0;$i<$count;$i++)
		{
		list($key, $val) = each($_GET);

		$val	=	urlencode($val);

		if(strstr($key,"checkbox")==true)
			{
				if($debug) print "ADD $key<br>"; 
			
			if($numberOfDeletions)
				$deleteQuery	.=	"OR item_number=$val ";
			else
				$deleteQuery	=	"DELETE FROM $tableName WHERE item_number=$val ";
				
			++$numberOfDeletions;
			}
			
		if($debug) print "$i: $key=$val<br>";
		}

	if($numberOfDeletions)
		{
		if($debug) print "Deleting Records<br>";

		OpenDatabase();
		
		$rv	=	QueryDatabase($deleteQuery);
		
		if(!$rv)
			$statusMessage	=	"SQL ERROR: " . mysql_error();

//			$statusMessage	=	"$numberOfDeletions Records Deleted...";
//		else
				
		CloseDatabase();
		}

	} // end of if($mode=="DeleteMailingListNames")




if($debug) print "statusMessage: $statusMessage<br>";
if($debug) print "deleteQuery: $deleteQuery<br>";
if($debug) print "tableName: $tableName<br>";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Delete From Mailing List</title>
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
    <td height="30" align="center" valign="middle" class="admin18Bold">Delete 
      From Mailing List </td>
  </tr>
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#CleanMailingList" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="10%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="2%" bgcolor="#666666">&nbsp;</td>
          <td width="35%" align="center"> 
            <form action="mail_sub_delete.php" method="post" name="SetTable" id="SetTable">
              <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
                <tr> 
                  <td colspan="2" bgcolor="#CCCCCC">&nbsp;</td>
                </tr>
                <tr align="center"> 
                  <td width="62%" height="40"> 
					
					<?php
					
					$labels	=	array();
					$values	=	array();
					
					$labels[]	=	"Live";
					$labels[]	=	"Test";
					
					$values[]	=	"$gMailDatabaseName";
					$values[]	=	"$gTestMailDatabaseName";
					
					DisplaySelector("MailTableName",$labels,$values,$tableName);
					?>
                  </td>
                  <td width="38%"> <input name="SetTable" type="submit" id="SetTable" value="Set"></td>
                </tr>
                <tr> 
                  <td colspan="2" bgcolor="#CCCCCC">&nbsp;</td>
                </tr>
              </table>
              <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
              <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
              <input name="Mode" type="hidden" id="Mode" value="SetTable">
            </form> 
            <form name="form1" method="POST" action="question_yn.php">
              <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
              <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
              <input name="Mode" type="hidden" id="Mode" value="DeleteMailingListNames">
              <input name="MailTableName" type="hidden" id="MailTableName" value="<?php print $tableName; ?>">
              
              <input name="quTarget" type="hidden" id="Mode" value="mail_sub_delete.php">
              <input name="quCaller" type="hidden" id="Mode" value="mail_sub_delete.php">
              <input name="quMessage" type="hidden" id="Mode" value="Do you want to permanantly delete the following names from your mailing list ?">
              <input name="quCallOut" type="hidden" id="Mode" value="Delete Names">
              
              <table width="90%" border="1" cellpadding="0" cellspacing="0" class="admin12Bold">
                <tr>
				<?php
				$numberOfRows	=	GetNumberOfRows("SELECT email_address, item_number FROM $tableName");
				?>
                  <td height="25" colspan="2" align="center" valign="middle" class="admin12Bold"><?php print $numberOfRows; ?> 
                    Addresses in Database</td>
                </tr>
                
                <tr> 
                  <td height="25" colspan="2" align="center" valign="middle" class="admin14Bold">Check 
                    Addresses to Delete</td>
                </tr>
                <?php
			$query			=	"SELECT email_address, item_number FROM $tableName";

			OpenDatabase();
			
			$rs	=	QueryDatabase($query);
			
			CloseDatabase();
			
			$numberOfRows	=	mysql_numrows($rs);


			if($debug) print "query: $query<br>";
			if($debug) print "tableName: $tableName<br>";
			if($debug) print "numberOfRows: $numberOfRows<br>";

			
			for($i=0;$i<$numberOfRows;$i++)
				{
				$email		=	GetDatabaseResult($rs,$i,"email_address");
				$itemNumber	=	GetDatabaseResult($rs,$i,"item_number");

				//print "$email<br>";

				print '
				<tr> 
                  <td width="40" height="25" align="center" valign="middle">
				  <input type="checkbox" name="checkbox' .$i . '" value="' . $itemNumber . '"></td>
                  <td>&nbsp;&nbsp;&nbsp;' . $email . '</td>
                </tr>
				';
				}
			?>
                <tr> 
                  <td height="40" colspan="2" align="center" valign="middle"> 
                    <input type="submit" name="Submit" value="Delete"> </td>
                </tr>
              </table>
            </form> 
            <p>&nbsp;</p></td>
          <td width="42%" bgcolor="#999999">&nbsp;</td>
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
