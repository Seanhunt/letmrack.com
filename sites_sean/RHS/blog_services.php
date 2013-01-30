<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// blog_services.php
//
// Blog service module
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
include 'file_lib.php';
include 'pw_lib.php';
include 'html_lib.php';
include 'blog_parms.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'select_controls.php';
include 'redirect.php';
include "name_value_pair_lib.php";

$debug		=	0;
$formMethod	=	"POST";

//if($debug) print phpversion() . "<br>";

// --------------------------------------------------------------------------------------------------------------------------------------
// GRAPHIC AD UPLOAD
// --------------------------------------------------------------------------------------------------------------------------------------

if($blMode=="GraphicAdUpload")
	{
	if($debug) print "Graphic Ad Upload<br>";

	// --------------------------------------------------------------------------------------------------------------------------------------
	// require headline and copy for all
	// --------------------------------------------------------------------------------------------------------------------------------------

	if($blHeadline=="")
		{
		$statusMessage	=	"You must enter a headline";
		$error			=	1;
		if($debug) print "statusMessage: $statusMessage<br>";
		}

	// --------------------------------------------------------------------------------------------------------------------------------------
	// see if there is an upload file
	// --------------------------------------------------------------------------------------------------------------------------------------

	if(!$error)
		{
		$fileName		=	basename($_FILES['blUploadFile']['name']); // get the filename lose the path	
	
		if($debug) print "fileName: $fileName<br>\n";
	
		// if so deal with it
		if($fileName != "")
			{
			$tempFile		=	$_FILES['blUploadFile']['tmp_name'];
			// this is the target
			$fullPath	 	= 	$gBlogAdsLocalPath . "/" . $fileName; // use the clean file name

			// set to save the file info
			$blFileName	=	$fileName;
			$blPath		=	$gBlogAdsLocalPath;
			
			if($debug) print "fileName: $fileName<br>\n";
			if($debug) print "tempFile: $tempFile<br>\n";
			if($debug) print "fullPath: $fullPath<br>\n";
			
			// move the 500x file to the target location
		
			$moveResult	=	move_uploaded_file($_FILES['blUploadFile']['tmp_name'], $fullPath);
	
			if($debug) print "moveResult: $moveResult<br>\n";
		
			if($moveResult==true) 
				{
				$rv = chmod($fullPath, 0755); 	// Read and write for owner, read for everybody else
	
				if($debug) print "chmod returned: $rv<br>\n";
		
				$statusMessage	=	"File uploaded";
		
				if($debug) print "File Successfully Uploaded<br>\n";
				} 
			else 
				{
				$errorNumber	=	$_FILES['blUploadFile']["error"];	
		
				$error			=	1;
		
				if($debug) print "Error: " . $errorNumber . "<br>\n";
				
				// deduce the error and make a message
				$statusMessage	=	FileMoveError("500x File Upload",$errorNumber);	
				}
			}

		if($debug) print "statusMessage: $statusMessage<br>";
		}
		
	// --------------------------------------------------------------------------------------------------------------------------------------
	// file upload is good, insert the item
	// --------------------------------------------------------------------------------------------------------------------------------------


// FIX this is another case of schema abuse :)

	if(!$error)
		{
//		$linkURL	=	"$blCopy";
		$linkURL	=	"http://$blCopy";
		$imageURL	=	"$gSiteURL$gBaseMountPoint$gBlogAdsMountPoint/$fileName";
		
		$imageLink	=	'<a target="_blank" href="' . $linkURL  . '">
						<img border="0" src="' 		. $imageURL . 
						'"></a>';

		if($debug) print "imageURL: $imageURL<br>";
		if($debug) print "linkURL: $linkURL<br>";
		if($debug) print "imageLink: $imageLink<br>";
		
		if($debug) print "blCopy: $blCopy<br>";
		
		$blItemType	=	"HTML";
		
		// add the image link
		$blCopy		=	$imageLink;

		// new to 2.2.0.1
		// put in DB as HTML encoded chars
		$blHeadline	=	htmlentities($blHeadline);
		$blSubHead	=	htmlentities($blSubHead);
	
		// this returns the record number or false
		$rv	=	BlogInsertItem($blTable,$blHeadline,$blSubHead,$blCopy,$blItemType,$blPosterID,$blEventDate,$blEventTime,$blFileName,$blPath);

		// new to 2.2.0.1
		// put in DB as HTML encoded chars
		$blHeadline	=	stripslashes($blHeadline);
		$blSubHead	=	stripslashes($blSubHead);

		if($debug) print "BlogInsertItem returned... $rv<br>\n";
		}
	else
		$rv	=	false;

	if($debug) print "rv: $rv<br>";
		
	if($rv)
		{
		//$statusMessage	=	"Your posting has been saved...";
		$blItemNumber	=	$rv;
		$blArgs			=	"blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable";
		$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_home.php?$args";	
		if($debug) print "statusMessage: $statusMessage<br>";
		}
	else
		{
		$blArgs			=	"blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller";
		$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword&";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_graphic_ad_upload.php?$args";	
		if($debug) print "statusMessage: $statusMessage<br>";
		}
	}

// --------------------------------------------------------------------------------------------------------------------------------------
// FILE SHARING
// --------------------------------------------------------------------------------------------------------------------------------------

else if($blMode=="FileSharing")
	{
	if($debug) print "File Sharing Mode<br>";

	// --------------------------------------------------------------------------------------------------------------------------------------
	// see what the blog type is...
	// if Music I will store the filename in the blEventTime
	// --------------------------------------------------------------------------------------------------------------------------------------
	
	//$blogType	=	BlogHeaderGetType($blTable);

	//if($debug) print "blogType: $blogType<br>";
	//if($debug) print "blTable: $blTable<br>";

	// --------------------------------------------------------------------------------------------------------------------------------------
	// require headline and copy for all
	// --------------------------------------------------------------------------------------------------------------------------------------

	if($blHeadline=="")
		{
		$statusMessage	=	"You must enter a headline";
		$error			=	1;
		if($debug) print "statusMessage: $statusMessage<br>";
		}
	
	if($blCopy=="" && !$error)
		{
		$statusMessage	=	"You must enter some text for the posting body...";
		$error			=	1;
		if($debug) print "statusMessage: $statusMessage<br>";
		}

	// --------------------------------------------------------------------------------------------------------------------------------------
	// see if there is an upload file
	// --------------------------------------------------------------------------------------------------------------------------------------

	if(!$error)
		{
		$fileName		=	basename($_FILES['blUploadFile']['name']); // get the filename lose the path	

		$fileType		=	FileGetExtension($fileName);
		$fileType		=	strtoupper($fileType);	
	
		if($debug) print "fileName: $fileName<br>\n";
	
		// if so deal with it
		if($fileName != "")
			{
			$tempFile		=	$_FILES['blUploadFile']['tmp_name'];
			// this is the target
			$fullPath	 	= 	$gBlogSharedLocalPath . "/" . $fileName; // use the clean file name
	
			// set to save the file info
			$blFileName	=	$fileName;
			
			$blPath		=	$gBlogSharedMountPoint;

			if($debug) print "fileName: $fileName<br>\n";
			if($debug) print "tempFile: $tempFile<br>\n";
			if($debug) print "fullPath: $fullPath<br>\n";
			if($debug) print "blFileName: $blFileName<br>\n";
			if($debug) print "blPath: $blPath<br>\n";
			if($debug) print "gBaseMountPoint: $gBaseMountPoint<br>\n";
			
			// move the 500x file to the target location
			$moveResult	=	move_uploaded_file($_FILES['blUploadFile']['tmp_name'], $fullPath);
	
			if($debug) print "moveResult: $moveResult<br>\n";
		
			if($moveResult==true) 
				{
				$rv = chmod($fullPath, 0755); 	// Read and write for owner, read for everybody else
	
				if($debug) print "chmod returned: $rv<br>\n";
		
				$statusMessage	=	"File uploaded";
		
				if($debug) print "File Successfully Uploaded<br>\n";
				} 
			else 
				{
				$errorNumber	=	$_FILES['blUploadFile']["error"];	
		
				$error			=	1;
		
				if($debug) print "Error: " . $errorNumber . "<br>\n";
				
				// deduce the error and make a message
				$statusMessage	=	FileMoveError("File Upload",$errorNumber);	
				}
			}

		if($debug) print "statusMessage: $statusMessage<br>";
		}


	// file upload is good, insert the item

	$linkURL	=	"$gBaseMountPoint$gBlogSharedMountPoint/$fileName";

	if(!$error)
		{
			// all else is download
		if($fileType != "MP3")
			{
			$linkText	=	"Click here to Download the file - $fileName";

			$link		=	'<p><a target="_blank" href="' . $linkURL . '">' . 
							"$linkText
							</a></p>";

			// add the link
			$blCopy		.=	"$link";
			}

		if($debug) print "linkText: $linkText<br>";
		if($debug) print "linkURL: $linkURL<br>";
		if($debug) print "link: $link<br>";
		if($debug) print "blCopy: $blCopy<br>";
		
		$blItemType	=	"HTML";
		
		// need to make the copy be HTML
		//$blCopy 	= 	implode("<br>\r\n", preg_split("/\r?\n/", $blCopy));
	
		// --------------------------------------------------------------------------------------------------------------------------------------
		// insert the item
		// --------------------------------------------------------------------------------------------------------------------------------------
	
		// new to 2.2.0.1
		// put in DB as HTML encoded chars
		$blHeadline	=	htmlentities($blHeadline);
		$blSubHead	=	htmlentities($blSubHead);
	
		// this returns the record number or false
		$rv	=	BlogInsertItem($blTable,$blHeadline,$blSubHead,$blCopy,$blItemType,$blPosterID,$blEventDate,$blEventTime,$blFileName,$blPath);

		// new to 2.2.0.1
		// put in DB as HTML encoded chars
		$blHeadline	=	stripslashes($blHeadline);
		$blSubHead	=	stripslashes($blSubHead);

		if($debug) print "BlogInsertItem returned... $rv<br>\n";
		}
	else
		$rv	=	false;

	if($debug) print "rv: $rv<br>";
		
	if($rv)
		{
		//$statusMessage	=	"Your posting has been saved...";
		$blItemNumber	=	$rv;
//		$blMode			=	"UpdateRefresh";
		$blArgs			=	"blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable";
		$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_home.php?$args";	
		if($debug) print "statusMessage: $statusMessage<br>";
		}
	else
		{
		//$statusMessage	=	"Error in BlogInsert()";
		$blArgs			=	"blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller";
		$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword&";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_file_upload.php?$args";	
		if($debug) print "statusMessage: $statusMessage<br>";
		}
	}


// --------------------------------------------------------------------------------------------------------------------------------------
// DELETE LINK
// --------------------------------------------------------------------------------------------------------------------------------------

else if($blMode=="DeleteLink")
	{
	if($debug) print "DeleteItem Mode<br>";

	// do a delete by item number here
	$rv	=	BlogLinkDeleteItem($blTable,$blItemNumber);
		
	if($rv)
		{
		//$statusMessage	=	"Your link has been deleted...";
		$passedArgs		=	"blPosterID=$systemUserID&blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_link_picker.php?$args";	
		}
	else
		{
		$statusMessage	=	"Database error in BlogLinkDeleteItem()";
		$blArgs			=	"blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller";
		$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_home.php?$args";	
		}

	}

// --------------------------------------------------------------------------------------------------------------------------------------
// UPDATE LINK
// --------------------------------------------------------------------------------------------------------------------------------------

else if($blMode=="UpdateLink" || $blMode=="LinkPickerUpdateItem")
	{
	if($debug) print "UpdateLink Mode<br>";

	// new to 2.2.0.1
	// put in DB as HTML encoded chars
	$blSiteName	=	htmlentities($blSiteName);
	$blLinkDescription	=	htmlentities($blLinkDescription);

	// this returns the record number or false
	$rv	=	BlogLinkUpdateItem($blTable,$blLinkURL,$blSiteName,$blLinkDescription,$blItemNumber);

	// new to 2.2.0.1
	// put in DB as HTML encoded chars
	$blSiteName	=	stripslashes($blSiteName);
	$blLinkDescription	=	stripslashes($blLinkDescription);
				
	if($rv)
		{
		//$statusMessage	=	"Your link for $blSiteName has been updated...";
		$blMode			=	"UpdateRefresh";
		$blArgs			=	"blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable";
		$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_link_edit.php?$args";	
		}
	else
		{
		$statusMessage	=	"Database error in BlogLinkUpdateItem()";
		$blArgs			=	"blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller";
		$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword&";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_home.php?$args";	
		}
	}

// --------------------------------------------------------------------------------------------------------------------------------------
// ADD LINK
// --------------------------------------------------------------------------------------------------------------------------------------

else if($blMode=="AddLink")
	{
	if($debug) print "AddLink Mode<br>";

	// require Name and URL
	if($blLinkURL=="")
		{
		$statusMessage	=	"You must enter a link URL";
		$error			=	1;
		}

	if($blSiteName=="" && !$error)
		{
		$statusMessage	=	"You must enter a site name";
		$error			=	1;
		}
		
	// clean that URL!!!	
	if(!$error)
		{
			
		// new to 2.2.0.1
		// put in DB as HTML encoded chars
		$blSiteName			=	htmlentities($blSiteName);
		$blLinkDescription	=	htmlentities($blLinkDescription);
	
		// do the insert
		$rv	=	BlogLinkInsertItem($blTable,$blLinkURL,$blSiteName,$blLinkDescription,$blPosterID);

		// new to 2.2.0.1
		// put in DB as HTML encoded chars
		$blLinkDescription	=	stripslashes($blLinkDescription);
		$blSiteName			=	stripslashes($blSiteName);
		$blLinkDescription	=	stripslashes($blLinkDescription);
		
	
		if($rv)
			{
			//$statusMessage	=	"Your link for $blSiteName has been saved...";
			$blArgs			=	"blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable";
			$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
			$args			=	"StatusMessage=$statusMessage&$passedArgs&";	

			// 042607 $targetURL		=	"blog_home.php?$args";	
			$targetURL		=	"blog_link_edit.php?$args";	
			}
		else
			{
			$statusMessage	=	"Database error in BlogLinkInsertItem()";
			$blArgs				=	"blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller&blLinkURL=$blLinkURL&blSiteName=$blSiteName&blLinkDescription=$blLinkDescription";
			$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword&";
			$args			=	"StatusMessage=$statusMessage&$passedArgs";	
			$targetURL		=	"blog_home.php?$args";	
			}
		}
	else
		{
		// status message from above...
		$blArgs			=	"blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller&blLinkURL=$blLinkURL&blSiteName=$blSiteName&blLinkDescription=$blLinkDescription";
		$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword&";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_link_edit.php?$args";	
		}
	}

// --------------------------------------------------------------------------------------------------------------------------------------
// DELETE ITEM
// --------------------------------------------------------------------------------------------------------------------------------------

else if($blMode=="DeleteItem")
	{
	if($debug) print "DeleteItem Mode<br>";

	// do a delete by item number here
	$rv	=	BlogDeleteItem($blTable,$blItemNumber);
	
	if($rv)
		{
		//$statusMessage	=	"Your posting has been deleted...";
		$passedArgs		=	"blPosterID=$systemUserID&blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_edit_picker.php?$args";	
		}
	else
		{
		$statusMessage	=	"Database error in BlogDeleteItem()";
		$blArgs			=	"blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller";
		$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_home.php?$args";	
		}

	}







// --------------------------------------------------------------------------------------------------------------------------------------
// NEW ITEM
// --------------------------------------------------------------------------------------------------------------------------------------

else if($blMode=="NewItem")
	{
	if($debug) print "NewItem Mode<br>";

	// --------------------------------------------------------------------------------------------------------------------------------------
	// set up the event date from its components
	// --------------------------------------------------------------------------------------------------------------------------------------

//	$blEventDate		=	"$blYear-$blMonth-$blDay $blEventHour:$blEventMinute:00";

	$blEventDate		=	MakeSQLTimeStamp($blMonth,$blDay,$blYear,$blEventHour,$blEventMinute,$blEventAmPm);

	if($debug) print "blEventDate: $blEventDate<br>";

	// --------------------------------------------------------------------------------------------------------------------------------------
	// require headline and copy for all
	// --------------------------------------------------------------------------------------------------------------------------------------

	if($blHeadline=="")
		{
		$statusMessage	=	"You must enter a headline";
		$error			=	1;
		if($debug) print "statusMessage: $statusMessage<br>";
		}
	
	if($blCopy=="" && !$error)
		{
		$statusMessage	=	"You must enter some text for the posting body...";
		$error			=	1;
		if($debug) print "statusMessage: $statusMessage<br>";
		}

	
	// --------------------------------------------------------------------------------------------------------------------------------------
	// see if there is an upload file
	// --------------------------------------------------------------------------------------------------------------------------------------

	if(!$error)
		{
		$fileName		=	basename($_FILES['blUploadFile']['name']); // get the filename lose the path	
	
		if($debug) print "fileName: $fileName<br>\n";
	
		// if so deal with it
		if($fileName != "")
			{
			$tempFile		=	$_FILES['blUploadFile']['tmp_name'];
			// this is the target
			$fullPath	 	= 	$gBlogTempLocalPath . "/" . $fileName; // use the clean file name
	
			if($debug) print "fileName: $fileName<br>\n";
			if($debug) print "tempFile: $tempFile<br>\n";
			if($debug) print "fullPath: $fullPath<br>\n";
			
			// move the 500x file to the target location
		
			$moveResult	=	move_uploaded_file($_FILES['blUploadFile']['tmp_name'], $fullPath);
	
			if($debug) print "moveResult: $moveResult<br>\n";
		
			if($moveResult==true) 
				{
				$rv = chmod($fullPath, 0755); 	// Read and write for owner, read for everybody else
	
				if($debug) print "chmod returned: $rv<br>\n";
	
				$blCopy	=	FileRead($fullPath);
	
				if($debug) print "LOADED from local blCopy: $blCopy<br>\n";
	
				$rv		=	unlink($fullPath);
	
				if($debug) print "unlink returned: $rv<br>\n";
	
				$statusMessage	=	"File uploaded";
		
				if($debug) print "File Successfully Uploaded<br>\n";
				} 
			else 
				{
				$errorNumber	=	$_FILES['blUploadFile']["error"];	
		
				$error			=	1;
		
				if($debug) print "Error: " . $errorNumber . "<br>\n";
				
				// deduce the error and make a message
				$statusMessage	=	FileMoveError("500x File Upload",$errorNumber);	
				}
			}

		if($debug) print "statusMessage: $statusMessage<br>";
		}

	// --------------------------------------------------------------------------------------------------------------------------------------
	// insert the item
	// --------------------------------------------------------------------------------------------------------------------------------------


	if(!$error)
		{
		// new to 2.2.0.1
		// put in DB as HTML encoded chars
		$blHeadline	=	htmlentities($blHeadline);
		$blSubHead	=	htmlentities($blSubHead);
	
		// make the duration field
		$blEventTime	=	"$blDurationHour:$blDurationMinute";
	
		// this returns the record number or false
		$rv	=	BlogInsertItem($blTable,$blHeadline,$blSubHead,$blCopy,$blItemType,$blPosterID,$blEventDate,$blEventTime,$blFileName,$blPath);

		if($debug) print "BlogInsertItem returned... $rv<br>\n";

		// new to 2.2.0.1
		// put in DB as HTML encoded chars
		$blHeadline	=	stripslashes($blHeadline);
		$blSubHead	=	stripslashes($blSubHead);
		}
	else
		{
		$rv	=	false;
		$statusMessage	=	"Error in BlogInsertItem()";
		}

	if($debug) print "rv: $rv<br>";
		
	if($rv)
		{
		//$statusMessage	=	"Your posting has been saved...";
		$blItemNumber	=	$rv;
		$blMode			=	"UpdateRefresh";
		$blArgs			=	"blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable";
		$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_home.php?$args";	
		if($debug) print "statusMessage: $statusMessage<br>";
		}
	else
		{
//		$statusMessage	=	"Error in BlogInsert()";
		$blArgs			=	"blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller";
		$passedArgs		=	"$blArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword&";
		$args			=	"StatusMessage=$statusMessage&$passedArgs";	
		$targetURL		=	"blog_edit.php?$args";	
		if($debug) print "statusMessage: $statusMessage<br>";
		}
	}
// --------------------------------------------------------------------------------------------------------------------------------------
// UPDATE ITEM
// --------------------------------------------------------------------------------------------------------------------------------------

else if($blMode=="UpdateItem" || $blMode=="PickerUpdateItem")
	{
	// set up the event date from its components
	$blEventDate		=	"$blYear-$blMonth-$blDay";

	if($debug) print "blEventDate: $blEventDate<br>";

	// enforce the required fields
	// --------------------------------------------------------------------------------------------------------------------------------------
	// require headline and copy for all
	// --------------------------------------------------------------------------------------------------------------------------------------

	if($blHeadline=="")
		{
		$statusMessage	=	"You must enter a headline";
		$error			=	1;
		if($debug) print "statusMessage: $statusMessage<br>";
		}
	
	if($blCopy=="" && !$error)
		{
		$statusMessage	=	"You must enter some text for the posting body...";
		$error			=	1;
		if($debug) print "statusMessage: $statusMessage<br>";
		}


	// --------------------------------------------------------------------------------------------------------------------------------------
	// see if there is an upload file
	// --------------------------------------------------------------------------------------------------------------------------------------

	if(!$error)
		{
		$fileName		=	basename($_FILES['blUploadFile']['name']); // get the filename lose the path	
	
		if($debug) print "fileName: $fileName<br>\n";
	
		// if so deal with it
		if($fileName != "")
			{
			$tempFile		=	$_FILES['blUploadFile']['tmp_name'];
			// this is the target
			$fullPath	 	= 	$gBlogTempLocalPath . "/" . $fileName; // use the clean file name
	
			if($debug) print "fileName: $fileName<br>\n";
			if($debug) print "tempFile: $tempFile<br>\n";
			if($debug) print "fullPath: $fullPath<br>\n";
			
			// move the 500x file to the target location
		
			$moveResult	=	move_uploaded_file($_FILES['blUploadFile']['tmp_name'], $fullPath);
	
			if($debug) print "moveResult: $moveResult<br>\n";
		
			if($moveResult==true) 
				{
				$rv = chmod($fullPath, 0755); 	// Read and write for owner, read for everybody else
	
				if($debug) print "chmod returned: $rv<br>\n";
	
				$blCopy	=	FileRead($fullPath);
	
				if($debug) print "LOADED from local blCopy: $blCopy<br>\n";
	
				$rv		=	unlink($fullPath);
	
				if($debug) print "unlink returned: $rv<br>\n";
	
				$statusMessage	=	"File uploaded";
		
				if($debug) print "File Successfully Uploaded<br>\n";
				} 
			else 
				{
				$errorNumber	=	$_FILES['blUploadFile']["error"];	
		
				$error			=	1;
		
				if($debug) print "Error: " . $errorNumber . "<br>\n";
				
				// deduce the error and make a message
				$statusMessage	=	FileMoveError("500x File Upload",$errorNumber);	
				}
			}

		if($debug) print "statusMessage: $statusMessage<br>";
		}

	// --------------------------------------------------------------------------------------------------------------------------------------
	// insert the item
	// --------------------------------------------------------------------------------------------------------------------------------------



	if(!$error)
		{
		// new to 2.2.0.1
		// put in DB as HTML encoded chars
		$blHeadline	=	htmlentities($blHeadline);
		$blSubHead	=	htmlentities($blSubHead);

		// make the duration field
		$blEventTime		=	"$blDurationHour:$blDurationMinute";
		$blEventDate		=	MakeSQLTimeStamp($blMonth,$blDay,$blYear,$blEventHour,$blEventMinute,$blEventAmPm);

		if($debug) print "blEventTime: $blEventTime<br>";
		if($debug) print "blEventDate: $blEventDate<br>";

		// do de deed
		$rv	=	BlogUpdateItem($blTable,$blHeadline,$blSubHead,$blCopy,$blItemType,$blEventDate,$blEventTime,$blItemNumber);

		// new to 2.2.0.1
		// put in DB as HTML encoded chars
		$blHeadline	=	stripslashes($blHeadline);
		$blSubHead	=	stripslashes($blSubHead);
		
		if($debug) print "BlogUpdateItem returned... $rv<br>\n";
		}
	else
		$rv	=	false;
		
	$blCopy				=	urlencode($blCopy);

	if($rv)
		{
		//$statusMessage	=	"Your posting has been updated...";

		// ? bug 050708
		// on return a question mark in the headline or subhead breaks it...
		// I am STILL chopping at this bug...
		// I dont return the data on the CL as the edit page does a fetch 
		// based on the itemNumber
		// This solves the problem...
		$blArgs			=	"blPosterID=$blPosterID&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller";


//		$blArgs			=	"blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller";
		$args			=	"StatusMessage=$statusMessage&$blArgs";	
		$targetURL		=	"blog_edit.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword&$args";	
		if($debug) print "targetURL: $targetURL<br>";
		}
	else
		{
		//$statusMessage	=	"Database error in BlogUpdateItem()";
		
		// ? bug 050708
		// I chopped the head subhead and copy out of this as well		
		$blArgs			=	"blPosterID=$blPosterID&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller";

		$args			=	"StatusMessage=$statusMessage&$blArgs";	
		$targetURL		=	"blog_edit.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword&$args";	
		if($debug) print "ERROR: targetURL: $targetURL<br>";
		}
	}

// ---------------------------------------------------------------------------------------------------------------------------------------
// THE END
// ---------------------------------------------------------------------------------------------------------------------------------------

else
	// all bad
	$targetURL	=	"index.php?StatusMessage=Unknown%20Value%20for%20Mode: $blMode";	
	
// ---------------------------------------------------------------------------------------------------------------------------------------
// do the redirection
// ---------------------------------------------------------------------------------------------------------------------------------------

FormRedirect($targetURL,$formMethod,$debug);
?>