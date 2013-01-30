<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// admin_home.php
//
//
// Revision 2.2.0.2
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

// FIX the load time of this page could be improved by putting the enumberation of all the 
// tables for show table in its own target _blank page

include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'question_lib.php';
include 'file_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'gallery_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	
include 'admin_lib.php';
include 'public_vote_lib.php';

$debug2	=	0;

// -----------------------------------------------------------------------------------------------
// Authorize the user and check the role
// -----------------------------------------------------------------------------------------------
if(AuthorizeAdminUser($systemUserID,$systemPassword)!=1)
	{
	print "Access Denied<br>";
	exit(0);
	}

// ------------------------------------------------------------------------------
// Init the table selector for gallery users
// ------------------------------------------------------------------------------

$numberOfGalleries	=	GetNumberOfRows("SELECT * FROM APPDEV_GalleryProfiles WHERE 1");

if($debug) print "numberOfGalleries: $numberOfGalleries<br>";

$galleryArray	=	array();
$galleryLinks	=	array();
$galleryNames	=	array();

// 040907 BUG gallery selection problems, we need to see the hidden galleries here
// to allow moderation

OpenDatabase();

$query	=	"SELECT * FROM APPDEV_GalleryProfiles WHERE 1";

$rs	=	QueryDatabase($query);

CloseDatabase();

// in 2.2.0 on I spoof the Admin as the owner
for($i=0;$i<$numberOfGalleries;$i++)
	{
	$ownerID			=	GetDatabaseResult($rs,$i,"ArtistID");
	$artistName			=	GetDatabaseResult($rs,$i,"ArtistName");
	$tableName			=	"APPDEV_GALLERY_" . $ownerID;

	// get the owners pass
	$ownerPass			=	GetFieldByComparison("APPDEV_UserInfo","pwPassword","pwUserID",$ownerID);
	$galleryName		=	GetDatabaseResult($rs,$i,"Title");
	
	if($debug) print "ownerPass: $ownerPass<br>";
	if($debug) print "tableName: $tableName<br>";
	if($debug) print "artistID: $artistID<br>";
	if($debug) print "artistName: $artistName<br>";

	$galleryArray[]		=	$ownerID;
	$galleryLinks[]		=	"gallery_home.php?Caller=close&gaTable=$tableName&SystemPassword=$ownerPass&SystemUserID=$ownerID";
	$galleryNames[]		=	$galleryName;		
	}


// ------------------------------------------------------------------------------
// Init the table selector for blog Tables
// ------------------------------------------------------------------------------

// print the links to the blogs that user has access to...
$numberOfBlogs	=	BlogGetNumberOfTables();

if($debug2) print "numberOfBlogs: $numberOfBlogs<br>";

$blogLinks	=	array();
$blogNames	=	array();

for($i=0;$i<$numberOfBlogs;$i++)
	{
	$blogTableName		=	BlogGetTableNameByIndex($i);
	$description		=	BlogHeaderGetDescription($blogTableName);
	$blogName			=	BlogHeaderGetName($blogTableName);

if($debug2) print "blogTableName: $blogTableName<br>";
if($debug2) print "description: $description<br>";
if($debug2) print "blogName: $blogName<br>";
	
	$blogLinks[]		=	'<a  class="boldLink14" href="' . 
"blog_home.php?Caller=admin_home.php&blTable=$blogTableName&SystemPassword=$systemPassword&SystemUserID=$systemUserID" . 
'"' . " onMouseOver=" . '"' . "Tip('" . $description . "')" . '">' . 
$blogName . '</a><br><br>' . "\n";
	}


// *************************************************************************************
// END patch 092207
// *************************************************************************************
	
	
// ------------------------------------------------------------------------------
// init the election names for the ratings system
// ------------------------------------------------------------------------------

$numberOfElections	=	PublicVoteGetNumberOfElections();

$electionNameArray		=	array();

for($i=0;$i<$numberOfElections;$i++)
	{
	$electionName		=	PublicVoteGetElectionName($i);
	$electionNameArray[]=	$electionName;		
	}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print "$gSiteName "; ?>Admin Home</title>
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
<!--- Include for Tool Tips ******************************* --->
<script type="text/javascript" src="wz_tooltip.js"></script>

<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" align="center" valign="middle" class="admin18Bold"><?php print "$gSiteName "; ?>Admin Home</td>
  </tr>
  <tr> 
  <tr> 
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="33%" height="40">&nbsp;&nbsp;< <a class="boldLink14" href="#" onClick="javascript:window.close();">Close 
            Window</a></td>
          <td width="33%" align="center"><a href="admin_help.php#AdminMainUI" target="_blank" class="galleryUILink">Help</a></td>
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
          <td width="8%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="1%">&nbsp;</td>
          <td width="38%" valign="top" class="admin15Bold"> 
            <?php
			// -----------------------------------------------------------------------------------------------
			// show the super user pages
			// -----------------------------------------------------------------------------------------------
			if(UserIsSuper($systemUserID))
				{
				print "<hr><center>User Management</center><br>";
				// create new admin user
				print  '<a href="pw_new_admin_user.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . 
				$systemUserID . '" class="boldLink14">New Admin User</a><br><br>';

				// create new blog user
				print  '<a href="pw_new_blog_user.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . 
				$systemUserID . '" class="boldLink14">New Blog or Gallery User</a><br><br>';

				// user edit and delete
				print  '<a href="password_edit_user.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . 
				$systemUserID . '" class="boldLink14">Edit or Delete User</a><br><br>';
				
				// user edit and delete
				print  '<a href="password_resend_settings.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . 
				$systemUserID . '" class="boldLink14">Resend User Information</a><br><br>';

				// create new gallery and gallery admin user
				print  '<a href="pw_new_gallery.php?SystemPassword=' . $systemPassword . '&SystemUserID=' 
				. $systemUserID . '" class="boldLink14">New Gallery</a><br><br>';

				// blog create
				print  '<a href="blog_create.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . 
				$systemUserID . '" class="boldLink14">New Blog</a><br><br>';

				// election create
				print  '<a href="public_vote_create_election.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . 
				$systemUserID . '" class="boldLink14">New Election</a><br><br>';

				// new password target
				print  '<a href="admin_new_pw_target.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . 
				$systemUserID . '" class="boldLink14">New Password Target</a><br><br>';

				// view debug messages
				if($debug) print  '<a href="debug_show.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . 
				$systemUserID . '" class="boldLink14">Show Debug Log</a><br><br>';



				}
		 
			// -----------------------------------------------------------------------------------------------
			// show the admin user pages
			// -----------------------------------------------------------------------------------------------

			print "<hr><center>Mailing List</center>";

			// mail list add
			print  '<a href="mail_sub_add.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . $systemUserID . '" class="boldLink14">Add</a><br><br>';

			// mail list clean
			print  '<a href="mail_sub_delete.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . $systemUserID . '" class="boldLink14">Delete</a><br><br>';

			// mail list edit
			print  '<a href="mail_sub_edit.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . $systemUserID . '" class="boldLink14">Edit</a><br><br>';

			// -----------------------------------------------------------------------------------------------
			// comms
			// -----------------------------------------------------------------------------------------------

			print "<hr><center>Communications</center><br>";

			// mail console
			print  '<a href="mail_sub_console.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . $systemUserID . '" class="boldLink14">Bulk Mail Console</a><br><br>';

			// FTP transmitter
			print  '<a href="file_upload.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . $systemUserID . '" class="boldLink14">Send Via FTP</a><br><br>';

			// -----------------------------------------------------------------------------------------------
			// misc
			// -----------------------------------------------------------------------------------------------

			print "<hr><center>Misc</center><br>";

			// counters
			print  '<a href="counter_view.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . $systemUserID . '" class="boldLink14">Page Counters</a><br><br>';

			// top downloads
			print  '<a href="download_view.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . $systemUserID . '" class="boldLink14">Top Downloads</a><br><br>';

		  	?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              
			<?php  
			// -----------------------------------------------------------------------------------------------
			// show the site specific admin user pages
			// -----------------------------------------------------------------------------------------------
			$numberOfSpecials	=	GetNumberOfRows("SELECT * FROM APPDEV_AdminSpecials");
			
			// if specials print a header row
			if($numberOfSpecials)
				{
				print 	'
						  <tr> 
							<td align="center" class="admin15Bold"> <hr>
							  Specials<br><br>
							  </td>
						  </tr>
				
						';


				// print the list of specials
				print '<tr> 
							<td>';

				for($i=0;$i<$numberOfSpecials;$i++)
					{
					$pageName		=	GetFieldByIndex("APPDEV_AdminSpecials","PageName",$i);
					$pageFileName	=	GetFieldByIndex("APPDEV_AdminSpecials","PageFileName",$i);
					$formName		=	"special_form_$i";
					
					/*
					print  '<a href="' . $pageFileName . '?SystemPassword=' . $systemPassword . 
					'&SystemUserID=' . $systemUserID . 
					'" class="boldLink14">' . $pageName . '</a><br><br>';
					*/
					
					// security update
					// this needs to be proliferated to all text links in the admin section
					print	'
					<form action="' . $pageFileName . '" method="post" name="' . $formName . '" id="' . $formName . '">
					  	<a  class="boldLink14" onclick="' . $formName . '.submit();" href="#">' . $pageName . '</a>
					  <input name="SystemUserID" type="hidden" value="' . $systemUserID . '" />
					  <input name="SystemPassword" type="hidden" value="' . $systemPassword . '" />
					</form>
							';
					}





				print	'
							  </td>
						  </tr>
						';
				}
				?>			  
			  
			  
			  <tr> 
                <td height="50" align="center" valign="middle" class="admin15Bold"> 
                  <hr>
                  Blogs</td>
              </tr>
              <tr> 
                <td> 
                  <?php
				  // blog links
					for($i=0;$i<$numberOfBlogs;$i++)
						{
						//print '<a target="_blank" class="boldLink14" href="' . $blogLinks[$i] . 
						//'">' . $blogNames[$i] . '</a><br><br>';
						
						print $blogLinks[$i];
						}
				  ?>
                </td>
              </tr>
              <tr> 
                <td height="50" align="center" valign="middle" class="admin15Bold"> 
                  <hr>
                  Galleries </td>
              </tr>
              <tr> 
                <td> 
                  <?php
			
					// gallery visibility
					print  '<a  href="gallery_show_hide.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . $systemUserID . '" class="boldLink14">Show or Hide Galleries</a><br><br>';
				  
					for($i=0;$i<$numberOfGalleries;$i++)
						{
						print '<a target="_blank" class="boldLink14" href="' . $galleryLinks[$i] . 
						'">' . $galleryNames[$i] . '</a><br><br>';
						}
				?>
                </td>
              </tr>
              <tr> 
                <td height="50" align="center" valign="top" class="admin15Bold"> 
                  <hr>
                  View Public Voting Tabulations</td>
              </tr>
              <tr> 
                <td> <form name="ViewVoteRatings" method="POST" action="public_vote_view.php">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr align="center" valign="middle"> 
                        <td width="71%" align="left"> 
                          <?php
						DisplaySelector("ElectionName",$electionNameArray,$electionNameArray,"");
						?>
                        </td>
                        <td width="29%" height="35"> <input type="submit" name="Submit" value="Go"> 
                          &nbsp;&nbsp; <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>"> 
                          <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>"> 
                        </td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr> 
                <td height="50" align="center" valign="top" class="admin15Bold"> 
                  <hr>
                  Database</td>
              </tr>
              <tr> 
                <td> 
                
<?php                
				// export data
				print  '<a target="_blank" href="table_home.php?SystemPassword=' . $systemPassword . '&SystemUserID=' . 
				$systemUserID . '" class="boldLink14">View and Export Data</a><br><br>';
?>                
                  
                  
                  
                  </td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td> </td>
              </tr>
              <tr> 
                <td><hr></td>
              </tr>
            </table>
            
          </td>
          <td width="1%">&nbsp;</td>
          <td width="52%" bgcolor="#999999">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td height="40" align="left" valign="middle" bgcolor="#EEEEEE" class="admin12Regular">
	
<?php
PrintUserMessage($systemUserID);
?>	
	
	  
	  </td>
  </tr>
    <td align="left" valign="top"> 
      <?php
	PrintAdminFooter();
	?>
    </td>
  </tr>
</table>
<p> 
  
</p>
</body>
</html>



<?php
DisplayStatusMessage($statusMessage);
?>
