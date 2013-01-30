<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// blog_edit.php
//
// UI for blog editing
// 
// Revision:	071105	added return to caller page for edit UI
// 				020506 Start 1.5 Revision
//				011707 Start rev 2
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// --------------------------------------------------------------------------------------------------------------------------------------

include '../local_info.php';
include 'db_lib.php';
include 'cl_lib.php';
include 'pw_lib.php';
include 'html_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'blog_parms.php';
include 'select_controls.php';
include 'status_message.php';
include 'admin_lib.php';
include 'file_lib.php';
include 'appdev_revision.php';

$debug	=	0;

// ---------------------------------------------------------------------------------------------------------------------------------------
// Authorize the user
// ---------------------------------------------------------------------------------------------------------------------------------------

if(UserIsAdmin($systemUserID) || UserIsSuper($systemUserID))
	{
	if($debug) print "User is ADMIN or SUPER<br>\n";

	if(AuthorizeAdminUser($systemUserID,$systemPassword)==false)
		{
		exit(0);
		}
	}
else
	{
	if($debug) print "User is BLOGGER<br>\n";

	$table		=	$blTable;
	
	if($debug) print "table: $table<br>\n";
	
	// authenticate
	if(AuthorizeBlogUser($systemUserID,$systemPassword,$table)==false)
		{
		if($debug) print "systemUserID: $systemUserID<br>";
		if($debug) print "systemPassword: $systemPassword<br>";
		print "Access denied.<br>";
		exit(0);
		}
	}	

// deprecate post tinyMCE
if($gSimpleBlogs==0)
	$blItemType	=	"TEXT";


// get the name of the blog
$blogName	=	BlogHeaderGetName($blTable);

// --------------------------------------------------------------------------------------------------------------------------------------
// set up the local crap
// --------------------------------------------------------------------------------------------------------------------------------------

if($blMode=="NewItem")
	{
	$buttonName		=	"Save";
	$pageName		=	"New Posting";
	$helpLink		=	"blog_help.php#NewItem";
	$previewLink	=	"";
	$backLink		=	"blog_home.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	}
else if($blMode=="UpdateItem")
	{
	$buttonName		=	"Modify";
	$pageName		=	"Edit Posting";
	$helpLink		=	"blog_help.php#UpdateItem";
	$previewLink	=	"blog_preview_item.php?blItemNumber=$blItemNumber&blTable=$blTable";
	$backLink		=	"blog_home.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	}
else if($blMode=="Refresh" || $blMode=="PickerUpdateItem")
	{
	// wants itemNum and table
	$buttonName		=	"Modify";
	$pageName		=	"Edit Posting";
	$helpLink		=	"blog_help.php#UpdateItem";
	$previewLink	=	"blog_preview_item.php?blItemNumber=$blItemNumber&blTable=$blTable";
	$backLink		=	"blog_edit_picker.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	// call refresh function
	BlogRefreshGlobals($blItemNumber,$blTable);
	$blMode			=	"PickerUpdateItem";			// leav it in update mode	
	}
else if($blMode=="UpdateRefresh")
	{
	// wants itemNum and table
	$buttonName		=	"Modify";
	$pageName		=	"Edit Posting";
	$helpLink		=	"blog_help.php#UpdateItem";
	$previewLink	=	"blog_preview_item.php?blItemNumber=$blItemNumber&blTable=$blTable";
	$backLink		=	"blog_home.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	// call refresh function
	BlogRefreshGlobals($blItemNumber,$blTable);
	$blMode			=	"UpdateItem";			// leav it in update mode	
	}

$blogType	=	BlogHeaderGetType($blTable);

if($blogType=="ORDERED")
	{
	$headlinePrompt	=	"Ad ID:&nbsp;&nbsp;<br>Not Shown&nbsp;&nbsp;";
	$subHeadPrompt	=	"Headline:&nbsp;&nbsp;";
	}
else
	{
	$headlinePrompt	=	"Headline:&nbsp;&nbsp;";
	$subHeadPrompt	=	"Sub Head:&nbsp;&nbsp;";
	}
	
if($debug) print "blogType: $blogType<br>";


$blCopy		=	stripslashes($blCopy);
$blHeadline	=	stripslashes($blHeadline);
$blSubHead	=	stripslashes($blSubHead);


?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print "$blogName - $pageName"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="robots" content="noindex">
<meta name="robots" content="nofollow">
<meta name="robots" content="none">

<?php
if($gUseTinyMCE)
	{
	// 2.2.0.2 updated this to use a global to describe the props
	// for the editor window
	print	'
			<!-- tinyMCE -->
			<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
			<script language="javascript" type="text/javascript">
				tinyMCE.init({' .
				
				$gTinyMCEProperties
				
				. 'height:"350px",
				width:"600px",
			  });
			</script>
			<!-- /tinyMCE -->
			';
	
	// fix up the copy string
	$blCopy			=	 stripslashes(urldecode(htmlentities($blCopy)));
	
	// need to make type be HTML
	$blItemType		=	"HTML";
	}

?>

<link href="app_dev_II.css" rel="stylesheet" type="text/css">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="955" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr> 
    <td align="center" class="cts18Bold"> 
	<?php print "$blogName - $pageName"; ?></td>
  </tr>
  <tr> 
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr bgcolor="#EEEEEE"> 
          <td width="30%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink">
            Back</a> </td>
          <td align="center"><a href="<?php print $helpLink; ?>" target="_blank" class="galleryUILink">Help</a> 
          </td>
          <td width="30%" height="40" align="right"> 
            <?php
		  
		  if($previewLink != "")
		  	{
			  print 	'
					  <a target="_blank" href="' . $previewLink . '" class="galleryUILink">
					  Preview
						</a>&nbsp;&nbsp;
						';
			}
		  ?>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td> <form name="form1" enctype="multipart/form-data" method="POST" action="blog_services.php">
        <input name="blMode" type="hidden" id="blMode" value="<?php print $blMode; ?>">
        <input name="blItemNumber" type="hidden" id="blItemNumber" value="<?php print $blItemNumber; ?>">
        <input name="blTable" type="hidden" id="blTable" value="<?php print $blTable; ?>">
        <input name="blCaller" type="hidden" id="blCaller" value="<?php print $blCaller; ?>">
        <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
        <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print "$systemUserID"; ?>">

		<?php
        if($gSimpleBlogs==0)
            {
			print '<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />';
            
			//print '<input name="blItemType" type="hidden" id="blType" value="' . $blItemType . '">';
            
			}
		?>    

        <table width="770" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="12%" height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"> 
              <span class="admin18BoldRed">*</span><?php print 	$headlinePrompt; ?> 
            </td>
            <td width="88%" align="left" valign="middle">&nbsp;&nbsp;<input name="blHeadline" type="text" id="blHeadline" value="<?php print $blHeadline; ?>" size="80"> 
              &nbsp; </td>
          </tr>
          <tr> 
            <td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"><?php print $subHeadPrompt; ?></td>
            <td align="left" valign="middle">&nbsp;&nbsp;<input name="blSubHead" type="text" id="blSubHead" value="<?php print $blSubHead; ?>" size="80"> 
              &nbsp; </td>
          </tr>
          <?php
		if($blogType=="CALENDAR")
			{
			print	'
				  <tr> 
					<td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold">
					<span class="admin18BoldRed">*</span>&nbsp;&nbsp;Event Date&nbsp;&nbsp;</td>
					<td align="left" valign="middle">&nbsp;&nbsp;
					';

			  	//	DisplayDateSelector($blEventDate);
				
				
				if($blYear != "" && $blMonth != "" && $blDay != "")
					{
					$date	=	"$blYear-$blMonth-$blDay";
					}
				else
					{
					$date	=	"";
					}
					
							
				if($debug) print "date: $date<br>\n";
				
				
				DisplayFutureDateSelector($date,2);
				
			print	'
					  &nbsp; </td>
					  </tr>
					  <tr> 
						<td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Event 
						  Time &nbsp;</td>
						<td align="left" valign="middle"  class="cts12Bold">&nbsp;&nbsp;
						';
						

					DisplayTimeSelector($blEventHour,$blEventMinute,$blEventAmPm);
					
					print "&nbsp;&nbsp&nbsp;&nbsp;Duration: ";
					
					$duration	=	explode(":",$blEventTime);
					
					DisplayDurationSelector($duration[0],$duration[1]);
					
			print	'						 
						 </td>
					  </tr>
					';
			
			}

		
          if($gSimpleBlogs==0)
          	{
          	print 	'
					  <tr> 
						<td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold">Posting 
						  Type: &nbsp;&nbsp;</td>
						<td align="left" valign="middle" class="cts14Bold"> 
					';
					
			  
			$valuesArray	=	array();
			$labelsArray	=	array();
			
			$valuesArray[]	=	"TEXT";
			$valuesArray[]	=	"HTML";
			
			$labelsArray[]	=	"Plain Text";
			$labelsArray[]	=	"HTML";
			
			if($blItemType=="")
				$itemType	=	"TEXT";
			else
				$itemType	=	$blItemType;
				
			DisplayRadioButtonArray("blItemType",$itemType,$valuesArray,$labelsArray);
			  
			print 	'
					</td>
				  </tr>
					';			  

			print 	'
			
					  <tr> 
						<td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold">
						Posting in File:&nbsp;&nbsp;</td>
						<td align="right" valign="middle">&nbsp;&nbsp;
						<input name="blUploadFile" type="file" id="blUploadFile" size="70" /> 
						&nbsp;</td>
					  </tr>
			
			
					';

            }
		else
			{
			print '<input name="blItemType" type="hidden" id="blItemType" value="' . $blItemType . '">';

			}
			
      	?>    
          
          
          
          
          <tr> 
            <td align="right" valign="top" bgcolor="#FFFFFF" class="cts12Bold"> 
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="right" class="cts12Bold">&nbsp;</td>
                </tr>
                <tr> 
                  <td height="25" align="right" class="cts12Bold"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Copy:&nbsp;&nbsp;</td>
                </tr>
              </table></td>
            <td height="330" align="right" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td>
                    <table width="674" border="0" align="center">
                      <tr>
                        <td width="10">&nbsp;</td>
                        <td width="654">
                        <textarea name="blCopy" cols="80" rows="20" id="elm1">
						<?php print $blCopy; ?></textarea></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              &nbsp; </td>
          </tr>
        </table>
        <p align="center"> 
          <input type="submit" name="Submit" value="<?php print $buttonName; ?>">
        </p>
      </form></td>
  </tr>
  <tr>
    <td><span class="admin18BoldRed">*</span>&nbsp;<span class="admin15BoldRed">Required 
      Fields</span></td>
  </tr>
  <tr> 
    <td height="40" bgcolor="#eeeeee"> 
      <?php
PrintUserMessage($systemUserID);
?>
    </td>
  </tr>
  <tr> 
    <td> 
      <?php
	
	PrintBlogFooter();
	
	?>
    </td>
  </tr>
</table>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>
