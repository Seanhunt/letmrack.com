<?php
// fixed big smoking gun bug!!!!041607
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include "../local_info.php";
include "db_lib.php";
include "cl_lib.php";
include "file_lib.php";
include "mail_lib.php";
include "html_lib.php";
include "image_lib.php";
include "captcha_lib.php";
include 'gallery_lib.php';
include 'gallery_ecard_lib.php';
include 'gallery_parms.php';
include "admin_lib.php";
include "appdev_revision.php";
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';


$debug	=	0;


// ---------------------------------------------------------------------------------------------------------------------------------------
// this is the new to 2.2.0 auth section for granular galleries
// ---------------------------------------------------------------------------------------------------------------------------------------

if(AuthorizeAdminUser($systemUserID,$systemPassword)==true)
	{
	$userIsAdmin	=	true;
	if($debug) print "User is: ADMINISTRATOR or SUPER<br>";
	}
else
	{
	if($debug) print "User is: GRANULAR<br>";

	if(AuthorizeGalleryUser($systemUserID,$systemPassword,$gaTable)==false)
		{
		print "Access denied.<br><br>";
		exit(0);
		}
	}

$galleryOwnerID		=	str_replace("APPDEV_GALLERY_","",$gaTable);
$pictureOwnerID		=	$systemUserID;
$first				=	GetFieldByComparison("APPDEV_UserInfo","pwFirstName","pwUserID",$systemUserID);
$last				=	GetFieldByComparison("APPDEV_UserInfo","pwLastName","pwUserID",$systemUserID);
$gaArtist			=	"$first $last";

// -------------------------------------------------------
// Figure out if its owner, moderator or contributor
//
// -------------------------------------------------------

$ownerTable	=	"APPDEV_GALLERY_" . $systemUserID;

if($gaTable == $ownerTable)
	{
	$userIsOwner	=	true; // enable extra menu options

	// owner query
	$query			=	"SELECT * FROM APPDEV_ImageLibrary WHERE ArtistID='$galleryOwnerID' ORDER BY RecordNumber";		

	if($debug) print "User is: OWNER<br>";
	}
else
	{
	if(UserIsModerator($gaTable,$systemUserID))
		{
		$userIsModerator	=	true; // enable extra menu options

		// owner query
		$query			=	"SELECT * FROM APPDEV_ImageLibrary WHERE ArtistID='$galleryOwnerID' ORDER BY RecordNumber";		

		if($debug) print "User is: MODERATOR<br>";
		}
	else
		{
		if($debug) print "User is: CONTRIBUTOR<br>";

		// contrib query
		$query			=	"SELECT * FROM APPDEV_ImageLibrary WHERE OwnerID='$systemUserID' ORDER BY RecordNumber";		
		}
	}
	
// -------------------------------------------------------
// -------------------------------------------------------




// ---------------------------------------------------------------------------------------------------------------------------------------

if($debug) print "gaArtistID: $gaArtistID<br>";

//$query			=	"SELECT * FROM APPDEV_ImageLibrary WHERE ArtistID='$gaArtistID' ORDER BY RecordNumber";		

$numberOfImages	=	GetNumberOfRows($query);

$backLink		=	"gallery_home.php?gaTable=$gaTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $galleryOwnerID; ?> - Edit Image Text</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="app_dev_II.css" rel="stylesheet" type="text/css">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF">
<div align="center" class="admin18Bold"> 
  <p><?php print $galleryOwnerID; ?> - Edit Image Text</p>
</div>
<a name="0"></a>

<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50">
	
	<table width="955" border="0" cellpadding="0" cellspacing="0" class="admin12Bold">
        <tr> 
          <td height="20" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr bgcolor="#EEEEEE"> 
                <td width="30%" height="40">
				&nbsp;&nbsp;&lt;&nbsp;<a href="<?php print $backLink; ?>" class="galleryUILink">Back</a> </td>
                <td align="center"><a href="gallery_help.php#EditLibrary" target="_blank" class="galleryUILink">Help</a> 
                </td>
                <td width="30%" height="40" align="right"><a target="_blank" href="<?php print $previewLink; ?>" class="galleryUILink">&nbsp;</a></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td height="20" valign="middle" bgcolor="#CCCCCC"> <p>You have <?php print "$numberOfImages"; ?> 
              images in the gallery. </p></td>
        </tr>
      </table>
	  
	  </td>
  </tr>
  <tr> 
    <td> 

      <table width="955" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td> 
            <!-- Start rows -->
            <?php
			//if($userIsOwner || $userIsModerator)
				//PrintLibraryPreviewRows($galleryOwnerID,$pictureOwnerID);
			//else
				PrintLibraryPreviewRows($galleryOwnerID,$pictureOwnerID);
			?>
            <!-- End rows -->
          </td>
        </tr>
        <tr>
          <td height="40" align="left" valign="middle" bgcolor="#eeeeee"> 
            <?php
PrintUserMessage($systemUserID);
?>
          </td>
        </tr>
        <tr> 
          <td> 
            <?php
		  // print the CT chop and footer
		  PrintGalleryFooter();
		  ?>
          </td>
        </tr>
      </table> 
	  
	  </td>
  </tr>
</table>


</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>



<?php
// lib functions

function PrintLibPreviewPageNumberUI($numberOfImages,$currentPageNumber=0)		  
{
print GetLibPreviewPageNumberUI($numberOfImages,$currentPageNumber);
}

function GetLibPreviewPageNumberUI($numberOfImages,$currentPageNumber)		  
{
$result	=	"<span class=rhs12Regular>Page: </span>";

// make 1 based
for($i=1;$i<=$numberOfImages;$i++)
	{
	if($i==$currentPageNumber)
		$class	=	"selectedPageLink12";
	else
		$class	=	"pageLink12";
		
	$result	.= '<a class="' . $class . '" href="#' . $i . '">';
	$result	.=  $i;
	$result	.=  "</a> ";
	}
	
return $result;
}


function PrintLibraryPreviewRows($galleryOwnerID,$pictureOwnerID)
{
global	$gGalleryMountPoint;
global	$gBaseMountPoint;
global 	$gSiteURL;
global 	$systemPassword;
global	$systemUserID;
global	$gaArtistID;
global	$gaTable;
global	$ownerTable;
global 	$gSimpleGalleries;

$debug			=	0;

if($debug) print "PrintLibraryPreviewRows($galleryOwnerID,$pictureOwnerID)<br>";


$userIsModerator	=	UserIsModerator($gaTable,$pictureOwnerID);

if($debug) print "userIsModerator: $userIsModerator<br>";


if($gaTable == $ownerTable || $userIsModerator)
	{
	$userIsOwner	=	true;

	if($debug) print "User is: OWNER<br>";

	$libraryQuery			=	"SELECT * FROM APPDEV_ImageLibrary 
								WHERE ArtistID='$galleryOwnerID' 
								ORDER BY RecordNumber";		
	}
else
	{
	if($debug) print "User is: NOT OWNER<br>";
	
	$libraryQuery			=	"SELECT * FROM APPDEV_ImageLibrary 
								WHERE OwnerID='$pictureOwnerID' 
								ORDER BY RecordNumber";		
	}	

$numberOfImages	=	GetNumberOfRows($libraryQuery);
$galleryType	=	GetProfileGalleryType($galleryOwnerID);




if($debug) print "numberOfImages: $numberOfImages<br>";
if($debug) print "libraryQuery: $libraryQuery<br>";

// fetch up a recordset
OpenDatabase();

$libraryRS	=	QueryDatabase($libraryQuery);


$galleryPath_500x	=	"$gBaseMountPoint$gGalleryMountPoint/$galleryOwnerID/500x/";
$galleryPath_200x	=	"$gBaseMountPoint$gGalleryMountPoint/$galleryOwnerID/200x/";

for($row=0;$row<$numberOfImages;$row++)
	{
	$currentPageNumber		=	$row + 1;
	
	$fileName				=	GetDatabaseResult($libraryRS,$row,"FileName");					
	$gaRecordNumber			=	GetDatabaseResult($libraryRS,$row,"RecordNumber");					

	if($debug) print "$galleryPath_500x$fileName<br>";

	// set up the edit link
	// FIX get gallery type
	
	if($debug) print "galleryType: $galleryType<br>";
	
	if($galleryType=="NFS")
		{
		if($debug) print "Send to NFS Editor<br>";

		$dataEditLink		=	"gallery_edit_nfs.php?gaRecordNumber=$gaRecordNumber&gaTable=$gaTable&gaAnchor=$currentPageNumber&gaFileName=$fileName&gaArtistID=$gaArtistID&SystemUserID=$systemUserID&SystemPassword=$systemPassword";	
		}
	else
		{
		if($debug) print "Send to FS Editor<br>";

		$dataEditLink		=	"gallery_edit.php?gaRecordNumber=$gaRecordNumber&gaTable=$gaTable&gaAnchor=$currentPageNumber&gaFileName=$fileName&gaArtistID=$gaArtistID&SystemUserID=$systemUserID&SystemPassword=$systemPassword";	
		}
	
	// if we got something fill the cell else NOP
	// get the poop on the entry
	$title				=	GetDatabaseResult($libraryRS,$row,"Title");					
	$location			=	GetDatabaseResult($libraryRS,$row,"Location");					
	$caption			=	GetDatabaseResult($libraryRS,$row,"Caption");					
	$artist				=	GetDatabaseResult($libraryRS,$row,"Artist");					
	$media				=	GetDatabaseResult($libraryRS,$row,"Media");					

	if($title=="") 		$title		=	"&nbsp";
	if($location=="") 	$location	=	"&nbsp";
	if($caption=="") 	$caption	=	"&nbsp";
	if($artist=="") 	$artist		=	"&nbsp";
	if($media=="") 		$media		=	"&nbsp";

	// set to get the sizes

	$sizeQuery			=	"SELECT * FROM APPDEV_ImageSizes WHERE FileName='$fileName' ORDER BY RecordNumber";		

	$sizeRS				=	QueryDatabase($sizeQuery);

	$price1				=	GetDatabaseResult($sizeRS,0,"Price");	
	$price2				=	GetDatabaseResult($sizeRS,1,"Price");	
	$price3				=	GetDatabaseResult($sizeRS,2,"Price");	
	$price4				=	GetDatabaseResult($sizeRS,3,"Price");	

	$size1				=	GetDatabaseResult($sizeRS,0,"Size");	
	$size2				=	GetDatabaseResult($sizeRS,1,"Size");	
	$size3				=	GetDatabaseResult($sizeRS,2,"Size");	
	$size4				=	GetDatabaseResult($sizeRS,3,"Size");	

	$description1		=	GetDatabaseResult($sizeRS,0,"Description");	
	$description2		=	GetDatabaseResult($sizeRS,1,"Description");	
	$description3		=	GetDatabaseResult($sizeRS,2,"Description");	
	$description4		=	GetDatabaseResult($sizeRS,3,"Description");	

	$editionType1		=	GetDatabaseResult($sizeRS,0,"EditionType");	
	$editionType2		=	GetDatabaseResult($sizeRS,1,"EditionType");	
	$editionType3		=	GetDatabaseResult($sizeRS,2,"EditionType");	
	$editionType4		=	GetDatabaseResult($sizeRS,3,"EditionType");	

	if($price1 != "")
		$size1Message		=	"The $size1 $editionType1 $description1 is $$price1."; 	
	else
		$size1Message		=	"&nbsp;"; 	


	if($price2 != "")
		$size2Message		=	"The $size2 $editionType2 $description2 is $$price2."; 	
	else
		$size2Message		=	"&nbsp;"; 	

	if($price3 != "")
		$size3Message		=	"The $size3 $editionType3 $description3 is $$price3."; 	
	else
		$size3Message		=	"&nbsp;"; 	

	if($price4 != "")
		$size4Message		=	"The $size4 $editionType4 $description4 is $$price4."; 	
	else
		$size4Message		=	"&nbsp;"; 	

	$imageTag500x	=	'
						<img src="' . "$galleryPath_500x$fileName" . '" border="0">
						';
	
	$imageTag200x	=	'
						<img src="' . "$galleryPath_200x$fileName" . '" border="0">
						';
						



if($galleryType=="FORSALE")
	{
	print	'	
	<!--Start One Library Entry -->
	<a name="' . ($row+1) . '"></a> 
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr> 
			  <td height="10" align="left" valign="top" class="admin12Bold">&nbsp;</td>
			</tr>
			<tr> 
			  <td align="left" valign="middle" bgcolor="#CCCCCC" class="admin12Bold"> 
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr> 
					<td width="78%" class="admin12Regular">&nbsp;&nbsp;' . GetLibPreviewPageNumberUI($numberOfImages,$currentPageNumber) 
					  . '</td>
					<td width="22%" height="30" align="right">&nbsp;&nbsp;<a href="' . $dataEditLink . '" class="galleryUILink">Edit 
					  </a>&nbsp;&nbsp;</td>
				  </tr>
				</table></td>
			</tr>
			<tr> 
			  <td height="10" valign="middle">&nbsp; </td>
			</tr>
			<tr> 
			  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr> 
					<td width="50%" valign="top"> <table width="2" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr> 
						  <td><table width="10%" border="0" align="center" cellpadding="0" cellspacing="0">
							  <tr> 
								<td>' . $imageTag500x . '</td>
							  </tr>
							  
							</table></td>
						</tr>
					  </table></td>
					<td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						
						<tr> 
						  <td align="right"> <table width="90%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
							  <tr> 
								<td height="25" class="rhs18Bold">' . $title . '</td>
							  </tr>
							  <tr>
							    <td height="25" class="rhs15Regular">' . $location . '</td>
						    </tr>
							  <tr> 
								<td height="25" class="rhs12Regular">' . $caption . '</td>
							  </tr>';
							  
							  
								if($gSimpleGalleries == 0)
							  	{
								print '
								<tr> 
								<td height="25" class="rhs10Regular">' . $media . '</td>
								</tr>';

								print '
								<tr> 
								<td height="25" class="rhs10Regular">' . $artist . '</td>
								</tr>
								<tr> 
								<td height="25" class="rhs10Regular">' . $fileName . '</td>
								</tr>';
								}
								
							  
							  
							  
							  print '
							  <tr> 
								<td height="25" class="rhs10Regular">' . $size1Message . '</td>
							  </tr>
							  <tr> 
								<td height="25" class="rhs10Regular">' . $size2Message . '</td>
							  </tr>
							  <tr> 
								<td height="25" class="rhs10Regular">' . $size3Message . '</td>
							  </tr>
							  <tr> 
								<td height="25" class="rhs10Regular">' . $size4Message . '</td>
							  </tr>
							</table></td>
						</tr>
					  </table></td>
				  </tr>
				</table></td>
			</tr>
			<tr> 
			  <td height="10">&nbsp;</td>
			</tr>
		  </table></td>
		  </tr>
		</table>
		
		
	<!--End One Library Entry -->
	
	
	
		';
	} // end of if($galleryType=="FORSALE")
else	// NFS
	{
	print	'	
	<!--Start One Library Entry -->
	<a name="' . ($row+1) . '"></a> 
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr> 
			  <td height="10" align="left" valign="top" class="admin12Bold">&nbsp;</td>
			</tr>
			<tr> 
			  <td align="left" valign="middle" bgcolor="#CCCCCC" class="admin12Bold"> 
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr> 
					<td width="78%" class="admin12Regular">&nbsp;&nbsp;' . GetLibPreviewPageNumberUI($numberOfImages,$currentPageNumber) 
					  . '</td>
					<td width="22%" height="30" align="right">&nbsp;&nbsp;<a href="' . $dataEditLink . '" class="galleryUILink">Edit 
					  </a>&nbsp;&nbsp;</td>
				  </tr>
				</table></td>
			</tr>
			<tr> 
			  <td height="10" valign="middle">&nbsp; </td>
			</tr>
			<tr> 
			  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr> 
					<td width="50%" valign="top"> <table width="2" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr> 
						  <td><table width="10%" border="0" align="center" cellpadding="0" cellspacing="0">
							  <tr> 
								<td>' . $imageTag500x . '</td>
							  </tr>
							  
							</table></td>
						</tr>
					  </table></td>
					<td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
						
						<tr> 
						  <td align="right"> <table width="90%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
							  <tr> 
								<td height="25" class="rhs18Bold">' . $title . '</td>
							  </tr>
							  <tr> 
								<td height="25" class="rhs14Regular">' . $caption . '</td>
							  </tr>';
							  
							  
								if($gSimpleGalleries == 0)
									{
									print '
									';

									print '
									';
									}
								
							  
							  
							  print '
							</table></td>
						</tr>
					  </table></td>
				  </tr>
				</table></td>
			</tr>
			<tr> 
			  <td height="10">&nbsp;</td>
			</tr>
		  </table></td>
		  </tr>
		</table>
		
		
	<!--End One Library Entry -->

	
	
	
		';
	} // end of else if($galleryType=="FORSALE")
	
	} // for($row=0;$row<$numberOfRows;$row++)

}




?>
