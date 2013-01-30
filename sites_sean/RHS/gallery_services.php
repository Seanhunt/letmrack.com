<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// gallery_services.php
//
// This is the handler for the gallery system
//
//	011406	Added file pixel dimension tests on uploads
//	100608 	Added CleanFileName() to move and resize mode as illegal chars were breaking things
//
// 	2.2.0.6 		Added large and small image size to the gallery profile table, 
//					these are referred to when the image is resampled.
//					The image dirs are still called 500x and 200x as these are referred
//					to specifically throughout the line code of sites.
//					Now that simply means thumb image and large image as changing that
//					will create work when updating sites. ERG!
//
//  2.2.0.7			Fixed a bug in the forsale galleries where it made all sizes be size 1.
//
// Revision 2.2.0.2
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

include "../local_info.php";
include "db_lib.php";
include "cl_lib.php";
include "file_lib.php";
include "mail_lib.php";
include "image_lib.php";
include "captcha_lib.php";
include "redirect.php";
include 'html_lib.php';	
include "name_value_pair_lib.php";
include 'gallery_lib.php';
include 'gallery_ecard_lib.php';
include 'gallery_parms.php';
include 'pw_lib.php';

$fileUploadEnabled		=	true; 	// FIX: TEST ONLY!!! 
									// for bluehost testing - file upload is failing and so is their support...

$debug = 0;

$error					=	0;	// general error state

$imageSizeFudgeFactor	=	24; // the amount of slack in the file dimension allowed

// ---------------------------------------------------------------------------------------------------------------------------------------
// ADD RESIZE MODE
// ---------------------------------------------------------------------------------------------------------------------------------------

if($gaMode == "AddImageAndResize")
	{
	if($debug) print "ADD IMAGE RESIZE MODE<br>\n";

	// get the owners id
	$galleryOwnerID		=	str_replace("APPDEV_GALLERY_","",$gaTable);

	// get the large and small image sizes
	$largeImageSize		=	GetFieldByComparison("APPDEV_GalleryProfiles","LargeImageSize","ArtistID",$galleryOwnerID);
	$smallImageSize		=	GetFieldByComparison("APPDEV_GalleryProfiles","SmallImageSize","ArtistID",$galleryOwnerID);

	// if the site has not been properly updated handle it...
	if($largeImageSize=="")
		$largeImageSize	=	"500";

	if($smallImageSize=="")
		$smallImageSize	=	"200";

	if($debug) print "largeImageSize: $largeImageSize<br>\n";
	if($debug) print "smallImageSize: $smallImageSize<br>\n";

	// get the type of the gallery FORSALE or NFS
	$galleryType	=	GetProfileGalleryType($galleryOwnerID);

	if($debug) print "galleryType: $galleryType<br>\n";

	// see if the critical data is here
	if($gaTitle == "")
		{
		$errorMessage	=	"Please enter a title for the piece...";
		$error			=	1;
		}

	if($gaPriceOne == "" && $galleryType=="FORSALE")
		{
		$errorMessage	=	"Please enter at least one price for the piece...";
		$error			=	1;
		}

	if($gaEditionTypeOne == "Please Pick One..."  && $galleryType=="FORSALE")
		{
		$errorMessage	=	"Please enter an edition type for the piece...";
		$error			=	1;
		}

	$fileName		=	basename($_FILES['gaFileToSend500x']['name']); // get the filename lose the path	
		
	if($debug) print "fileName: $fileName<br>\n";

	$uniqueFileName			=	$fileName;

	// 100608 A filename with a single quote breaks the editor maybe other stuff as well
	// I will meataxe the bad juju
	$uniqueFileName			=	CleanFileName($uniqueFileName);
	$fileName				=	CleanFileName($fileName);
	
	if(!$error)
		{
		// handle namespace conflicts by postpended 4 char guid
		do	{
			if(GalleryFileExists($uniqueFileName)==true)
				{
				$guid			=	GeneratePassword(4);
				$a				=	explode(".",$fileName);
				$uniqueFileName	=	$a[0] . "-$guid" . ".$a[1]";
				if($debug) print "uniqueFileName: $uniqueFileName<br>\n";
				}
			else
				break;
				
			}while(1);
		}
		

	if($debug) print "FINAL - uniqueFileName: $uniqueFileName<br>\n";
	
	// upload and move the file
	if(!$error && $fileUploadEnabled)
		{
		// get parms for 500x
		$tempFile_500x		=	$_FILES['gaFileToSend500x']['tmp_name'];

		$fullPath_500x	 	= 	$gGalleryLocalPath . "/$galleryOwnerID/500x/" . $uniqueFileName; // use the clean file name
		$fullPath_200x	 	= 	$gGalleryLocalPath . "/$galleryOwnerID/200x/" . $uniqueFileName; // use the clean file name

		if($debug) print "galleryOwnerID: $galleryOwnerID<br>\n";
		if($debug) print "tempFile_500x: $tempFile_500x<br>\n";
		if($debug) print "fullPath_500x: $fullPath_500x<br>\n";
		if($debug) print "fullPath_200x: $fullPath_200x<br>\n";
		
		// move the  file to the target location
	
		$moveResult	=	move_uploaded_file($_FILES['gaFileToSend500x']['tmp_name'], $fullPath_500x);

		if($debug) print "moveResult: $moveResult<br>\n";
	
		if($moveResult==TRUE) 
			{
			// test for minimum size
			$width_orig			=	GetWidth($fullPath_500x);
			$height_orig		=	GetHeight($fullPath_500x);

			if($debug) print "width_orig: $width_orig<br>\n";
			if($debug) print "height_orig: $height_orig<br>\n";
			
			if(($width_orig < 500) && ($height_orig < 500))
				{
				if($debug) print "*** IMAGE SIZE ERROR ***<br>\n";
				$errorMessage	=	"Your image file needs to be bigger the 500 pixels in the long dimension";
				$error	=	1;
				}
			}		
		}
		
	if(!$error)
		{
		// resize the file
		// make a 500x and a 200x
		// copy to the proper directories
		if($moveResult==TRUE) 
			{
			if($debug) print "------- RESAMPLE IMAGES -----<br>\n";

			$src500 = imagecreatefromjpeg($fullPath_500x);

			if($debug) print "src500: $src500<br>\n";

			// scale the h & w
			// Set a maximum height and width

			// do 200 px
			$width 	= $smallImageSize;
			$height = $smallImageSize;
			
			$ratio_orig = $width_orig/$height_orig;
			
			if ($width/$height > $ratio_orig) 
				{
			   	$width = $height*$ratio_orig;
				} 
			else 
				{
			   	$height = $width/$ratio_orig;
				}

			if($debug) print "width: $width<br>\n";
			if($debug) print "height: $height<br>\n";
			
			// do the 200
			
			$tmp200 	=	imagecreatetruecolor($width,$height);

			if($debug) print "tmp200: $tmp200<br>\n";

			// this line actually does the image resizing, copying from the original
			// image into the $tmp image
			$rv	=	imagecopyresampled($tmp200,$src500,0,0,0,0,$width,$height,$width_orig,$height_orig);

			if($debug) print "200x imagecopyresampled: $rv<br>\n";

			// do 500 px
			$width 	= $largeImageSize;
			$height = $largeImageSize;
			
			$ratio_orig = $width_orig/$height_orig;
			
			if ($width/$height > $ratio_orig) 
				{
			   	$width = $height*$ratio_orig;
				} 
			else 
				{
			   	$height = $width/$ratio_orig;
				}

			if($debug) print "width: $width<br>\n";
			if($debug) print "height: $height<br>\n";

			$tmp500 	=	imagecreatetruecolor($width,$height);

			$rv	=	imagecopyresampled($tmp500,$src500,0,0,0,0,$width,$height,$width_orig,$height_orig);

			if($debug) print "500x imagecopyresampled: $rv<br>\n";

			// make resource into file
			$rv	=	imagejpeg($tmp500,$fullPath_500x,100);

			if($debug) print "500x File Creation: $rv<br>\n";

			$rv	=	imagejpeg($tmp200,$fullPath_200x,100);

			if($debug) print "200x File Creation: $rv<br>\n";

			// clean up the landscape
			imagedestroy($src500);
			imagedestroy($tmp200);
			imagedestroy($tmp500);

			$rv = chmod($fullPath_500x, 0755); 	// Read and write for owner, read for everybody else

			if($debug) print "chmod 500 returned - $rv<br>\n";

			$rv = chmod($fullPath_200x, 0755); 	// Read and write for owner, read for everybody else

			if($debug) print "chmod 200 returned - $rv<br>\n";

			$errorMessage	=	"File uploaded";
	
			if($debug) print "File Successfully Uploaded<br>\n";
			} 
		else 
			{
			$errorNumber	=	$_FILES['gaFileToSend500x']["error"];	
	
			$error			=	1;
	
			if($debug) print "Error: " . $errorNumber . "<br>\n";
			
			// deduce the error and make a message
			$errorMessage	=	FileMoveError("500x File Upload",$errorNumber);	
			}

		} //  end if(!$error)

	// we have the data and the files

	// now for the database part...

	// add the data to the image library table		
	if(!$error  && $fileUploadEnabled)
		{

		$first				=	GetFieldByComparison("APPDEV_UserInfo","pwFirstName","pwUserID",$systemUserID);
		$last				=	GetFieldByComparison("APPDEV_UserInfo","pwLastName","pwUserID",$systemUserID);

		$pictureOwnerName 	= 	"$first $last";
		$galleryOwner		=	str_replace("APPDEV_GALLERY_","",$gaTable);
		$pictureOwner		=	$systemUserID;
		
		$gaArtist			=	"$first $last";
		
		if($debug) print "*** ADD TO DB...<br>\n";
		if($debug) print "fileName: $fileName<br>\n";
		if($debug) print "gaTitle: $gaTitle<br>\n";
		if($debug) print "gaLocation: $gaLocation<br>\n";
		if($debug) print "gaCaption: $gaCaption<br>\n";
		if($debug) print "gaArtist: $gaArtist<br>\n";
		if($debug) print "systemUserID: $systemUserID<br>\n";
		if($debug) print "gaMedia: $gaMedia<br>\n";
		if($debug) print "orientation: $orientation<br>\n";
		if($debug) print "galleryType: $galleryType<br>\n";

		// add the image data
		$gaTitle	=	htmlentities($gaTitle);
		$gaLocation	=	htmlentities($gaLocation);

		$rv	=	AddImageDataToLibrary($uniqueFileName, $gaTitle, $gaLocation, $gaCaption,$pictureOwnerName, $pictureOwner,$galleryOwner,$gaMedia,$orientation);
	
		$gaTitle	=	stripslashes($gaTitle);
		$gaLocation	=	stripslashes($gaLocation);
		
		if($rv == FALSE)
			{
			$error			=	1;	
			$errorMessage	=	"Database Error, Unable to add library data.";	
			}
		}

	if(!$error  && $fileUploadEnabled)
		{
		// add the image to the end of the users lib
		$rv	=	AddImageToGallery($uniqueFileName,"APPDEV_GALLERY_$galleryOwnerID");
		
		if($rv == FALSE)
			{
			$error			=	1;	
			$errorMessage	=	"Database Error, Unable to add image to gallery.";	
			}
		}	


	if(!$error  && $galleryType=="FORSALE")
		{
		if($debug) print "PROCESSING FORSALE DATA ROWS<br>\n";
		if($debug) print "<hr>FS gallery parms<br><br>";
		
		if($debug) print "gaPriceOne: $gaPriceOne<br>";
		if($debug) print "gaPriceTwo: $gaPriceTwo<br>";
		if($debug) print "gaPriceThree: $gaPriceThree<br>";
		if($debug) print "gaPriceFour: $gaPriceFour<br>";
		
		if($debug) print "gaHeightOne: $gaHeightOne<br>";
		if($debug) print "gaPriceTwo: $gaPriceTwo<br>";
		if($debug) print "gaHeightThree: $gaHeightThree<br>";
		if($debug) print "gaHeightFour: $gaHeightFour<br>";
		
		if($debug) print "gaDescriptionOne: $gaDescriptionOne<br>";
		if($debug) print "gaDescriptionTwo: $gaDescriptionTwo<br>";
		if($debug) print "gaDescriptionThree: $gaDescriptionThree<br>";
		if($debug) print "gaDescriptionFour: $gaDescriptionFour<br>";
		
		if($debug) print "gaEditionTypeOne: $gaEditionTypeOne<br>";
		if($debug) print "gaEditionTypeTwo: $gaEditionTypeTwo<br>";
		if($debug) print "gaEditionTypeThree: $gaEditionTypeThree<br>";
		if($debug) print "gaEditionTypeFour: $gaEditionTypeFour<br>";
		
		
		if($debug) print "<hr>";



		if($debug) print "error: $error<br>\n";
		if($debug) print "errorMessage: $errorMessage<br>\n";

		// add the size and price data
		// if there is a price we will add the line

		if($gaPriceOne != "" && !$error)
			{
			if($debug) print "ADD Price Line One <br>";

			$rv	=	AddImageSizetoLibrary($uniqueFileName, $galleryOwnerID, $gaHeightOne, $gaPriceOne, $gaDescriptionOne, $gaEditionTypeOne);

			if($rv == FALSE)
				{
				$error			=	1;	
				$errorMessage	=	"Database Error, Unable to add size one";	
				}
			}
			
		if($gaPriceTwo != "" && !$error)
			{
			if($debug) print "ADD Price Line Two<br>";

			$rv	=	AddImageSizetoLibrary($uniqueFileName, $galleryOwnerID, $gaHeightTwo, $gaPriceTwo, $gaDescriptionTwo, $gaEditionTypeTwo);
			
			if($rv == FALSE)
				{
				$error			=	1;	
				$errorMessage	=	"Database Error, Unable to add size two";	
				}
			}
			
		if($gaPriceThree != "" && !$error)
			{
			if($debug) print "ADD Price Line Three<br>";

			$rv	=	AddImageSizetoLibrary($uniqueFileName, $galleryOwnerID, $gaHeightThree, $gaPriceThree, $gaDescriptionThree, $gaEditionTypeThree);
			
			if($rv == FALSE)
				{
				$error			=	1;	
				$errorMessage	=	"Database Error, Unable to add size three";	
				}
			}
	
		if($gaPriceFour != "" && !$error)
			{
			if($debug) print "ADD Price Line Four<br>";

			$rv	=	AddImageSizetoLibrary($uniqueFileName, $galleryOwnerID, $gaHeightFour, $gaPriceFour, $gaDescriptionFour, $gaEditionTypeFour);
			
			if($rv == FALSE)
				{
				$error			=	1;	
				$errorMessage	=	"Database Error, Unable to add size four";	
				}
			}
		} // end of if(!$error  && $galleryType=="FORSALE")

					
	
	// if error add $gaArgs to not lose data
	// if not dont add so the form will clear for the next entry	
	$args		=	"StatusMessage=$errorMessage&SystemPassword=$systemPassword&SystemUserID=$systemUserID";	
	$gaArgs		=	"gaTable=$gaTable&gaArtistID=$gaArtistID&gaPageName=$gaPageName&gaImageIndex=$gaImageIndex&gaPageNumber=$gaPageNumber&gaGallery=$gaGallery&gaFileName=$gaFileName&gaTitle=$gaTitle&gaCaption=$gaCaption&gaLocation=$gaLocation&gaArtist=$gaArtist&gaMedia=$gaMedia&gaStatus=$gaStatus&gaPresentation=$gaPresentation&gaWidthOne=$gaWidthOne&gaWidthTwo=$gaWidthTwo&gaWidthThree=$gaWidthThree&gaWidthFour=$gaWidthFour&gaHeightOne=$gaHeightOne&gaHeightTwo=$gaHeightTwo&gaHeightThree=$gaHeightThree&gaHeightFour=$gaHeightFour&gaPriceOne=$gaPriceOne&gaPriceTwo=$gaPriceTwo&gaPriceThree=$gaPriceThree&gaPriceFour=$gaPriceFour&gaEditionTypeOne=$gaEditionTypeOne&gaEditionTypeTwo=$gaEditionTypeTwo&gaEditionTypeThree=$gaEditionTypeThree&gaEditionTypeFour=$gaEditionTypeFour&gaFileToSend500x=$gaFileToSend500x&gaFileToSend200x=$gaFileToSend200x&gaDescriptionOne=$gaDescriptionOne&gaDescriptionTwo=$gaDescriptionTwo&gaDescriptionThree=$gaDescriptionThree&gaDescriptionFour=$gaDescriptionFour";
	
	// did all go well, set up to take the appropriate action
	if($error)
		{
		if($debug) print "errorMessage: $errorMessage<br>";
		
		$targetURL	=	"gallery_add.php?$args&$gaArgs";	
		}
	else
		$targetURL	=	"gallery_add.php?$args&gaTable=$gaTable";	
	}

// ---------------------------------------------------------------------------------------------------------------------------------------
// DELETE MODE
//
// input gaFileName
// ---------------------------------------------------------------------------------------------------------------------------------------

else if($gaMode == "DeleteImage")
	{
	if($debug) print "DELETE IMAGE MODE<br>\n";

	// get the owners id
	$galleryOwnerID		=	str_replace("APPDEV_GALLERY_","",$gaTable);

	if(DeleteImageFromLibrary($gaFileName,$systemUserID,$galleryOwnerID)==FALSE)
		{
		$error	=	1;
		$errorMessage	=	"$gaFileName has NOT been deleted, please inform the system administrator";
		}
	else
		{
		//$errorMessage	=	"$gaFileName has been deleted";
		}
				
	// return the user info as delete has no text inputs
	$args		=	"gaTable=$gaTable&gaArtistID=$systemUserID&StatusMessage=$errorMessage&SystemPassword=$systemPassword&SystemUserID=$systemUserID";	
	
	// did all go well, set up to take the appropriate action
	$targetURL	=	"gallery_delete.php?$args";	
	}
	
// ---------------------------------------------------------------------------------------------------------------------------------------
// ADD TO GALLERY MODE - caLLED FROM REORDER GALLERY
// ---------------------------------------------------------------------------------------------------------------------------------------

else if($gaMode == "AddImageToGallery")
	{
	if($debug) print "ADD IMAGE TO GALLERY MODE<br>\n";
	
	$galleryOwnerID		=	str_replace("APPDEV_GALLERY_","",$gaTable);
	
	$galleryTableName		=	"APPDEV_GALLERY_$galleryOwnerID";
	
	$rv						=	AddImageToGallery($gaFileName,$galleryTableName);

	if($debug) print "galleryOwnerID: $galleryOwnerID<br>\n";
	if($debug) print "galleryTableName: $galleryTableName<br>\n";
	if($debug) print "rv: $rv<br>\n";


	// no message on success
	if($rv==FALSE)
		$errorMessage	=	"Error: Image $gaFileName Not Added";	
	
	$args		=	"gaTable=$galleryTableName&StatusMessage=$errorMessage&SystemPassword=$systemPassword&SystemUserID=$systemUserID";	

	$targetURL	=	"gallery_build.php?$args";	
	}
// ---------------------------------------------------------------------------------------------------------------------------------------
// SEND ECARD
// ---------------------------------------------------------------------------------------------------------------------------------------
else if($gaMode == "SendECard")
	{
	if($debug) print "SEND E-CARD MODE<br>\n";

	// ---------------------------------------------------------------------------------------------------------------------------------------
	// If exploited only send 1 mail per second
	// ---------------------------------------------------------------------------------------------------------------------------------------

	if(!$debug) sleep(1);	// sleep so it wont send much if exploited

	// ---------------------------------------------------------------------------------------------------------------------------------------
	// Check the captcha word, if wrong send back to the preview page for corrections
	// ---------------------------------------------------------------------------------------------------------------------------------------

	$captchaTest	=	GetCaptchaText($gaCaptchaFileName);

	if($debug) print "captchaTest: $captchaTest<br>\n";
	if($debug) print "gaCaptchaText: $gaCaptchaText<br>\n";
	if($debug) print "gaCaptchaFileName: $gaCaptchaFileName<br>\n";

	if($captchaTest != $gaCaptchaText)
		{
		// entered wrong word...
		if($debug) print "Captcha FAIL<br>\n";

		$errorMessage	=	"You entered the word from the graphic incorrectly, please try again...";
		$error			=	1;

		// return message
		$addlArgs	=	"&StatusMessage=$errorMessage";	

		// all went well
		$targetURL	=	"../gallery_ecard_preview.php?$gaECardArgs$addlArgs";	
		}
		
	// ---------------------------------------------------------------------------------------------------------------------------------------
	// Check the email addresses, if bad send back to the compose page with error msg for luser correctioin
	// ---------------------------------------------------------------------------------------------------------------------------------------

	// validate to address
	if(!$error)
		{
		$rv		=	validate_email($gaTo);

		// poorly formed address
		if($rv == -1 && !$error)
			{
			if($debug) print "poorly formed to address FAIL<br>\n";

			$errorMessage		=	"The to email address: $gaTo is incorrectly formed";
			$error				=	1;
			}
	
		// invalid domain
		if($rv == 0 && !$error)
			{
			if($debug) print "to address has an invalid domainFAIL<br>\n";

			$errorMessage		=	"The to email address: $gaTo has an invalid domain...";
			$error				=	1;
			}
		
		// return the error status
		if($error)
			{
			// return message
			$addlArgs	=	"&StatusMessage=$errorMessage";	
	
			// back to re-enter address
			$targetURL	=	"../gallery_ecard_compose.php?$gaECardArgs$addlArgs";	
			}
		}

	// validate from address
	if(!$error)
		{
		$rv		=	validate_email($gaFrom);

		// poorly formed address
		if($rv == -1 && !$error)
			{
			if($debug) print "poorly formed to address FAIL<br>\n";

			$errorMessage		=	"The from email address: $gaFrom is incorrectly formed";
			$error				=	1;
			}
	
		// invalid domain
		if($rv == 0 && !$error)
			{
			if($debug) print "to address has an invalid domainFAIL<br>\n";

			$errorMessage		=	"The from email address: $gaFrom has an invalid domain...";
			$error				=	1;
			}

		// return the error status
		if($error)
			{
			// return message
			$addlArgs	=	"&StatusMessage=$errorMessage";	
	
			// back to re-enter address
			$targetURL	=	"../gallery_ecard_compose.php?$gaECardArgs$addlArgs";	
			}
		}

	// ---------------------------------------------------------------------------------------------------------------------------------------
	// Build the message from the template
	//
	// Error should never happen, return to image page with error if it does.
	// ---------------------------------------------------------------------------------------------------------------------------------------

	if(!$error)
		{
		// get the info
		$title			=	GetFieldByComparison("APPDEV_ImageLibrary","Title","FileName",$gaFileName);
		$caption		=	GetFieldByComparison("APPDEV_ImageLibrary","Caption","FileName",$gaFileName);
		$artistName		=	GetArtistName($gaFileName,$gaGallery);
		$templateFileName	=	"../$gECardTemplate";

		// load and process the template
		$message	=	GetECardMessage($gaFileName,GetArtistIdFromGallery($gaGallery),$title,$caption,$artistName,urldecode($gaMessage),$templateFileName);
		
		// should never happen
		if($message=="")
			{
			$errorMessage		=	"The template file was not found...";
			$error				=	1;

			// return message
			$addlArgs	=	"&StatusMessage=$errorMessage";	
			// all went well
			$targetURL	=	"../$gImagePageName?$gaECardArgs$addlArgs";	
			}
		}	

	// ---------------------------------------------------------------------------------------------------------------------------------------
	// if all is good sent the mail
	// ---------------------------------------------------------------------------------------------------------------------------------------

	if(!$error)
		{
		$rv	=	email_send($gaTo,$gaFrom,$gaSenderName,$gaSubject,$message);

		if($rv)
			{
			$errorMessage		=	"Your message was sent!";
			$error				=	0;	
			}
		else
			{
			$errorMessage		=	"We are sorry, your message not was sent due to a server error.";
			$error				=	0;	
			}

		// set up the standard with the fixed up values	
		$gaECardArgs		=	"gaCaller=$gaCaller&gaTo=$gaTo&gaFrom=$gaFrom&gaSenderName=$gaSenderName&gaSubject=$gaSubject&gaMessage=$gaMessage&gaImageIndex=$gaImageIndex&gaPageNumber=$gaPageNumber&gaGallery=$gaGallery&gaFileName=$gaFileName&gaCaptchaText=$gaCaptchaText&gaCaptchaFileName=$gaCaptchaFileName";	

		// return message
		$addlArgs	=	"&StatusMessage=$errorMessage";	

		// all went well
		$targetURL	=	"../$gaCaller?$gaECardArgs$addlArgs";	
		}

	} // END else if($gaMode == "SendECard")


// ---------------------------------------------------------------------------------------------------------------------------------------
// UPDATE ARTIST PROFILE
// ---------------------------------------------------------------------------------------------------------------------------------------
else if($gaMode == "UpdateProfile")
	{
	$error	=	0;
	
	// check the required args
	if($gaGalleryTitle == "")
		{
		$errorMessage	=	"Please enter a title for the gallery...";
		$error			=	1;
		}

	// require more stuff
	if($gSimpleGalleries==false)
		{
		if($gaArtistName == "Please enter the artist name..." && !$error)
			{
			$errorMessage	=	"Please enter the artist name...";
			$error			=	1;
			}
	
		if($gaEMail == "" && !$error)
			{
			$errorMessage	=	"Please enter your email address...";
			$error			=	1;
			}
	
		if($gaPhone == "" && !$error)
			{
			$errorMessage	=	"Please enter your phone number...";
			$error			=	1;
			}
		}
	

	// get the owner ID
	$galleryOwner			=	str_replace("APPDEV_GALLERY_","",$gaTable);

	// get the large and small image sizes
	$profileImageSize		=	GetFieldByComparison("APPDEV_GalleryProfiles","ProfileImageSize","ArtistID",$galleryOwner);

	// if the site has not been properly updated handle it...
	if($profileImageSize=="")
		$profileImageSize	=	"300";

	if($debug) print "profileImageSize: $profileImageSize<br>\n";

	// ---------------------------------------------------------------------------------------------------------------------------------------
	// If there is one... Upload the 300x file
	// ---------------------------------------------------------------------------------------------------------------------------------------

	$hasFile			=	false;
	$fileName_300x	=	basename($_FILES['gaFileToSend300x']['name']); // get the filename lose the path	
	$fileName		=	CleanFileName($fileName_300x); // get rid of bad vibes

	// allow for no file
	if($fileName != "")	$hasFile	=	true;

	if($debug) print "<br><hr>";
	if($debug) print "UPDATE PROFILE<br>\n";
	if($debug) print "fileName: $fileName<br>\n";
	if($debug) print "hasFile: $hasFile<br>\n";
	if($debug) print "<br><hr>";
		
	if(!$error && $hasFile)
		{
		// get parms for 300x
		$galleryOwner			=	str_replace("APPDEV_GALLERY_","",$gaTable);
		$tempFile_300x		=	$_FILES['gaFileToSend300x']['tmp_name'];
		$fullPath_300x	 	= 	$gGalleryLocalPath . "/$galleryOwner/300x/" . $fileName; // use the clean file name

		// move the 300x file to the target location
	
		$moveResult	=	move_uploaded_file($_FILES['gaFileToSend300x']['tmp_name'], $fullPath_300x);
	
		if($debug) print "<br><hr>";
		if($debug) print "fileName_300x: $fileName_300x<br>\n";
		if($debug) print "tempFile_300x: $tempFile_300x<br>\n";
		if($debug) print "fullPath_300x: $fullPath_300x<br>\n";
		if($debug) print "moveResult: $moveResult<br>\n";
		if($debug) print "<hr><br>\n";

		// check up on the pixel dimensions of the file
		if($moveResult==TRUE) 
			{
			// test for minimum size
			$width_orig			=	GetWidth($fullPath_300x);
			$height_orig		=	GetHeight($fullPath_300x);

			if($debug) print "width_orig: $width_orig<br>\n";
			if($debug) print "height_orig: $height_orig<br>\n";
			
			if(($width_orig < 300) && ($height_orig < 300))
				{
				if($debug) print "*** IMAGE SIZE ERROR ***<br>\n";
				$errorMessage	=	"Your profile image needs to be bigger the 300 pixels in the long dimension";
				$error	=	1;
				}
			}		

		if($moveResult==TRUE && !$error) 
			{
			// resize the image
			$src300 = imagecreatefromjpeg($fullPath_300x);

			if($debug) print "src300: $src300<br>\n";

			// scale the h & w
			// Set a maximum height and width

			// do 300 px
			$width 	= $profileImageSize;
			$height = $profileImageSize;
			
			$ratio_orig = $width_orig/$height_orig;
			
			if ($width/$height > $ratio_orig) 
				{
			   	$width = $height*$ratio_orig;
				} 
			else 
				{
			   	$height = $width/$ratio_orig;
				}

			if($debug) print "width: $width<br>\n";
			if($debug) print "height: $height<br>\n";
			
			// do the 200
			
			$tmp300 	=	imagecreatetruecolor($width,$height);

			if($debug) print "tmp300: $tmp300<br>\n";

			// this line actually does the image resizing, copying from the original
			// image into the $tmp image
			$rv	=	imagecopyresampled($tmp300,$src300,0,0,0,0,$width,$height,$width_orig,$height_orig);

			if($debug) print "300x imagecopyresampled: $rv<br>\n";

			// make resource into file
			$rv	=	imagejpeg($tmp300,$fullPath_300x,100);

			if($debug) print "300x File Creation: $rv<br>\n";

			// clean up the landscape
			imagedestroy($src300);
			imagedestroy($tmp300);

			$rv = chmod($fullPath_300x, 0755); 	// Read and write for owner, read for everybody else

			if($debug) print "chmod 300 returned - $rv<br>\n";

			$errorMessage	=	"File uploaded";
	
			if($debug) print "File Successfully Uploaded<br>\n";
			} 
		else 
			{
			$errorNumber	=	$_FILES['gaFileToSend300x']["error"];	
	
			$error			=	1;
	
			if($debug) print "Error: " . $errorNumber . "<br>\n";
			
			// deduce the error and make a message
			$errorMessage	=	FileMoveError("300x File Upload",$errorNumber);	
			}

		if($debug) print '_FILE array values:<br>';
		if($debug) print_r($_FILES);
		if($debug) print '<br>';

		} // end 300x file upload

	// update the database
	
	if(!$error)
		{
		// get the owners id
		$galleryOwnerID		=	str_replace("APPDEV_GALLERY_","",$gaTable);

		if($debug) print 'Update DATABASE...<br>';

		// if there is no filename get the current filename
		if($fileName == "")
			{
			// get the current fn
			$fileName	=	GetFieldByComparison("APPDEV_GalleryProfiles","FileName","ArtistID",$galleryOwnerID);
	
			if($debug) print "SWAP FILE NAMES NO NEW FILE<br>\n";
			if($debug) print "fileName: $fileName<br>\n";
			}
		else
			{
			if($debug) print "UPDATE FILENAME<br>\n";
			if($debug) print "fileName: $fileName<br>\n";
			}

		// BUG potential when simple turned off will need more of these for the other parms
		// else rendered as html in the text fiels
		$gaGalleryTitle	=	htmlentities($gaGalleryTitle);
	
		$rv	=	UpdateArtistProfile($gaGalleryTitle,$gaStatement,$fileName,$gaWebsite,$gaArtist,$gaStudio,$gaEMail,$gaAddress,$gaCity,$gaState,$gaZip,$gaPhone,$galleryOwnerID);

		// new to 2.2.0.1
		// put in DB as HTML encoded chars
		$blSubHead	=	stripslashes($blSubHead);

	
		if($rv && !$error)
			{
			$errorMessage	=	"Your profile has been updated...";	
			}
		else
			$errorMessage	=	"A database error has occured...";	
	
		$inputArgs	=	MakeURIString();	

		$targetURL	=	"gallery_profile.php?$inputArgs&StatusMessage=$errorMessage";	
	
		if($debug) print "inputArgs: $inputArgs<br>\n";
		if($debug) print "errorMessage: $errorMessage<br>\n";
		if($debug) print "targetURL: $targetURL<br>\n";
		}
	
	// if error send back to editor with message
	if($error)
		{
		$inputArgs	=	MakeURIString();	
	
		$targetURL	=	"gallery_profile.php?$inputArgs&StatusMessage=$errorMessage";	
	
		if($debug) print "inputArgs: $inputArgs<br>\n";
		if($debug) print "errorMessage: $errorMessage<br>\n";
		if($debug) print "targetURL: $targetURL<br>\n";
		}
	}
// ---------------------------------------------------------------------------------------------------------------------------------------
// UPDATE IMAGE DATA
// ---------------------------------------------------------------------------------------------------------------------------------------
else if($gaMode == "ModifyImageData")
	{
	if($debug) print "MODIFY IMAGE DATA MODE<br>\n";

	// get the owners id
	$galleryOwnerID		=	str_replace("APPDEV_GALLERY_","",$gaTable);

	if($debug) print "galleryOwnerID: $galleryOwnerID<br>\n";
	

	// add the image data
	$gaTitle	=	htmlentities($gaTitle);
	$gaLocation	=	htmlentities($gaLocation);

	// update the text part of the lib info
	$rv	=	UpdateImageLibrary($gaFileName,$gaTitle,$gaLocation,$gaCaption,$gaArtist,$gaMedia);

	$gaTitle	=	stripslashes($gaTitle);
	$gaLocation	=	stripslashes($gaLocation);

	// should not happen
	if(!$rv)
		{
		$errorMessage	=	"A database error occured, the record was not updated...";
		$error			=	1;
		}

	// get the type of the gallery FORSALE or NFS
	$galleryType	=	GetProfileGalleryType($galleryOwnerID);

	if($galleryType=="FORSALE")
		{
		if($debug) print "PROCESSING PRICE LINES<br>\n";

		if(!$error)
			{
			// delete the old, this is easier then trying to update
			DeleteImageSizesFromLibrary($gaFileName);
			
			// add the size and price data
			// if there is a price we will add the line
			if($gaPriceOne != "" && !$error)
				{
				if($debug) print "ADD Price line 1<br>\n";

				$rv	=	AddImageSizetoLibrary($gaFileName, $galleryOwnerID, $gaHeightOne, $gaPriceOne, $gaDescriptionOne, $gaEditionTypeOne);
				
				if($rv == FALSE)
					{
					$error			=	1;	
					$errorMessage	=	"Database Error, Unable to add size one";	
					}
				}
				
			if($gaPriceTwo != "" && !$error)
				{
				if($debug) print "ADD Price line 2<br>\n";

				$rv	=	AddImageSizetoLibrary($gaFileName, $galleryOwnerID, $gaHeightTwo, $gaPriceTwo, $gaDescriptionTwo, $gaEditionTypeTwo);
				
				if($rv == FALSE)
					{
					$error			=	1;	
					$errorMessage	=	"Database Error, Unable to add size two";	
					}
				}
				
			if($gaPriceThree != "" && !$error)
				{
				if($debug) print "ADD Price line 3<br>\n";

				$rv	=	AddImageSizetoLibrary($gaFileName, $galleryOwnerID, $gaHeightThree, $gaPriceThree, $gaDescriptionThree, $gaEditionTypeThree);
				
				if($rv == FALSE)
					{
					$error			=	1;	
					$errorMessage	=	"Database Error, Unable to add size three";	
					}
				}
		
			if($gaPriceFour != "" && !$error)
				{
				if($debug) print "ADD Price line 4<br>\n";

				$rv	=	AddImageSizetoLibrary($gaFileName, $galleryOwnerID, $gaHeightFour, $gaPriceFour, $gaDescriptionFour, $gaEditionTypeFour);
				
				if($rv == FALSE)
					{
					$error			=	1;	
					$errorMessage	=	"Database Error, Unable to add size four";	
					}
				}
			} // if(!$error)
		} // end of if($galleryType=="FORSALE")
		
	// now to update the size and price info

	// no message on success...
	
	
	//if($debug) print "$gaArgs<br>";
	
	$passedArgs	=	"SystemUserID=$systemUserID&SystemPassword=$systemPassword&$gaArgs";
	$args		=	"gaTable=$gaTable&StatusMessage=$errorMessage&$passedArgs";	
	$targetURL	=	"gallery_edit.php?$args";	
	}


// ---------------------------------------------------------------------------------------------------------------------------------------
// UPDATE IMAGE DATA for NFS gaslleries
// ---------------------------------------------------------------------------------------------------------------------------------------

else if($gaMode == "ModifyImageDataNFS")
	{
	if($debug) print "MODIFY IMAGE DATA NFS MODE<br>\n";


	$gaTitle	=	htmlentities($gaTitle);
	$gaLocation	=	htmlentities($gaLocation);

	// update the text part of the lib info
	$rv	=	UpdateImageLibrary($gaFileName,$gaTitle,$gaLocation,$gaCaption,$gaArtist,$gaMedia);

	$gaTitle	=	stripslashes($gaTitle);
	$gaLocation	=	stripslashes($gaLocation);

	// should not happen
	if(!$rv)
		{
		$errorMessage	=	"A database error occured, the record was not updated...";
		$error			=	1;
		}

	// get the type of the gallery FORSALE or NFS
	$galleryType	=	GetProfileGalleryType($gaArtistID);

	// no message on success...
	
	if($debug) print "$gaArgs<br>";
	
	$passedArgs	=	"gaTable=$gaTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword&$gaArgs";
	$args		=	"StatusMessage=$errorMessage&$passedArgs";	
	$targetURL	=	"gallery_edit_nfs.php?$args";	
	}

// ---------------------------------------------------------------------------------------------------------------------------------------
// TRUNCATE GALLERY
// ---------------------------------------------------------------------------------------------------------------------------------------
else if($gaMode == "TruncateGallery")
	{
	// get the owners id
	$galleryOwnerID		=	str_replace("APPDEV_GALLERY_","",$gaTable);

	
	if($debug) print "TRUNCATE GALLERY MODE<br>\n";
	
	$rv	=	TruncateGallery($galleryOwnerID);
	
	if($rv == FALSE)
		{
		$error			=	1;	
		$errorMessage	=	"Database Error, Unable to truncate gallery";	
		}
	else
		{
		$errorMessage	=	"Click the images shown below in the order you would like them to appear in your gallery...";	
		}

	// this is the standard
	$gaArgs		=	"gaTable=$gaTable&gaArtistID=$gaArtistID&gaPageName=$gaPageName&gaImageIndex=$gaImageIndex&gaPageNumber=$gaPageNumber&gaGallery=$gaGallery&gaFileName=$gaFileName&gaTitle=$gaTitle&gaCaption=$gaCaption&gaLocation=$gaLocation&gaArtist=$gaArtist&gaMedia=$gaMedia&gaStatus=$gaStatus&gaPresentation=$gaPresentation&gaWidthOne=$gaWidthOne&gaWidthTwo=$gaWidthTwo&gaWidthThree=$gaWidthThree&gaWidthFour=$gaWidthFour&gaHeightOne=$gaHeightOne&gaHeightTwo=$gaHeightTwo&gaHeightThree=$gaHeightThree&gaHeightFour=$gaHeightFour&gaPriceOne=$gaPriceOne&gaPriceTwo=$gaPriceTwo&gaPriceThree=$gaPriceThree&gaPriceFour=$gaPriceFour&gaEditionTypeOne=$gaEditionTypeOne&gaEditionTypeTwo=$gaEditionTypeTwo&gaEditionTypeThree=$gaEditionTypeThree&gaEditionTypeFour=$gaEditionTypeFour&gaFileToSend500x=$gaFileToSend500x&gaFileToSend200x=$gaFileToSend200x&gaDescriptionOne=$gaDescriptionOne&gaDescriptionTwo=$gaDescriptionTwo&gaDescriptionThree=$gaDescriptionThree&gaDescriptionFour=$gaDescriptionFour";
	$passedArgs	=	"$gaArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	$args		=	"StatusMessage=$errorMessage&$passedArgs";	
//	$targetURL	=	"gallery_home.php?$args";	
	$targetURL	=	"gallery_build.php?$args";	

	}

// ---------------------------------------------------------------------------------------------------------------------------------------
// Delete detail images
// ---------------------------------------------------------------------------------------------------------------------------------------
else if($gaMode == "DeleteDetail")
	{
	if($debug) print "DELETE DETAIL IMAGE MODE<br>\n";

	$rv			=	DeleteDetailImage($gaFileName,$gaDetailFileName,$gaArtistID);

	if(!$rv)
		$errorMessage	=	"Failed to delete $gaDetailFileName";

	$gaArgs		=	"gaTable=$gaTable&gaArtistID=$gaArtistID&gaPageName=$gaPageName&gaImageIndex=$gaImageIndex&gaPageNumber=$gaPageNumber&gaGallery=$gaGallery&gaFileName=$gaFileName&gaTitle=$gaTitle&gaCaption=$gaCaption&gaLocation=$gaLocation&gaArtist=$gaArtist&gaMedia=$gaMedia&gaStatus=$gaStatus&gaPresentation=$gaPresentation&gaWidthOne=$gaWidthOne&gaWidthTwo=$gaWidthTwo&gaWidthThree=$gaWidthThree&gaWidthFour=$gaWidthFour&gaHeightOne=$gaHeightOne&gaHeightTwo=$gaHeightTwo&gaHeightThree=$gaHeightThree&gaHeightFour=$gaHeightFour&gaPriceOne=$gaPriceOne&gaPriceTwo=$gaPriceTwo&gaPriceThree=$gaPriceThree&gaPriceFour=$gaPriceFour&gaEditionTypeOne=$gaEditionTypeOne&gaEditionTypeTwo=$gaEditionTypeTwo&gaEditionTypeThree=$gaEditionTypeThree&gaEditionTypeFour=$gaEditionTypeFour&gaFileToSend500x=$gaFileToSend500x&gaFileToSend200x=$gaFileToSend200x&gaDescriptionOne=$gaDescriptionOne&gaDescriptionTwo=$gaDescriptionTwo&gaDescriptionThree=$gaDescriptionThree&gaDescriptionFour=$gaDescriptionFour";
	$passedArgs	=	"$gaArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	$args		=	"StatusMessage=$errorMessage&$passedArgs";	
	$targetURL	=	"gallery_detail.php?$args";	
	}

// ---------------------------------------------------------------------------------------------------------------------------------------
// Add Detail Images or preview the image set
// ---------------------------------------------------------------------------------------------------------------------------------------

else if($gaMode == "AddDetail")
	{
	// ---------------------------------------------------------------------------------------------------------------------------------------
	// see if the critical data is here
	// ---------------------------------------------------------------------------------------------------------------------------------------

	if($gaFileName == "Please Pick One...")
		{
		$errorMessage	=	"Please select a filename...";
		$error			=	1;
		}

	if(GetPassedValue("Submit")=="Preview")
		{
		if($debug) print "PREVIEW DETAIL IMAGE MODE<br>\n";

		// this is the standard
		$done		=	1;
		}
	else
		if($debug) print "ADD DETAIL IMAGE RESIZE MODE<br>\n";


	if(!$error && !$done)
		{
		$fileName		=	basename($_FILES['gaFileToSend500x']['name']); // get the filename lose the path	
		
		
		$fileName		=	CleanFileName($fileName); // get rid of bad vibes
		
		
		if($debug) print "fileName: $fileName<br>\n";

		// get parms for 500x
		$tempFile_500x		=	$_FILES['gaFileToSend500x']['tmp_name'];

		$fullPath_500x	 	= 	$gGalleryLocalPath . "/$gaArtistID/500x/" . $fileName; // use the clean file name
		$fullPath_200x	 	= 	$gGalleryLocalPath . "/$gaArtistID/200x/" . $fileName; // use the clean file name

		if($debug) print "tempFile_500x: $tempFile_500x<br>\n";
		if($debug) print "fullPath_500x: $fullPath_500x<br>\n";
		if($debug) print "fullPath_200x: $fullPath_200x<br>\n";
		
		// move the  file to the target location
	
		$moveResult	=	move_uploaded_file($_FILES['gaFileToSend500x']['tmp_name'], $fullPath_500x);

		if($debug) print "moveResult: $moveResult<br>\n";
	
		if($moveResult==TRUE) 
			{
			// test for minimum size
			$width_orig			=	GetWidth($fullPath_500x);
			$height_orig		=	GetHeight($fullPath_500x);

			if($debug) print "width_orig: $width_orig<br>\n";
			if($debug) print "height_orig: $height_orig<br>\n";
			
			if(($width_orig < 500) && ($height_orig < 500))
				{
				if($debug) print "*** IMAGE SIZE ERROR ***<br>\n";
				$errorMessage	=	"Your image file needs to be bigger the 500 pixels in the long dimension";
				$error	=	1;
				}
			}		
		
		// resize the file
		// make a 500x and a 200x
		// copy to the proper directories
		if($moveResult==TRUE && !$error) 
			{
			$src500 = imagecreatefromjpeg($fullPath_500x);

			if($debug) print "src500: $src500<br>\n";

			// scale the h & w
			// Set a maximum height and width

			// do 200 px
			$width 	= 200;
			$height = 200;
			
			$ratio_orig = $width_orig/$height_orig;
			
			if ($width/$height > $ratio_orig) 
				{
			   	$width = $height*$ratio_orig;
				} 
			else 
				{
			   	$height = $width/$ratio_orig;
				}

			if($debug) print "width: $width<br>\n";
			if($debug) print "height: $height<br>\n";
			
			// do the 200
			
			$tmp200 	=	imagecreatetruecolor($width,$height);

			if($debug) print "tmp200: $tmp200<br>\n";

			// this line actually does the image resizing, copying from the original
			// image into the $tmp image
			$rv	=	imagecopyresampled($tmp200,$src500,0,0,0,0,$width,$height,$width_orig,$height_orig);

			if($debug) print "200x imagecopyresampled: $rv<br>\n";

			// do 500 px
			$width 	= 500;
			$height = 500;
			
			$ratio_orig = $width_orig/$height_orig;
			
			if ($width/$height > $ratio_orig) 
				{
			   	$width = $height*$ratio_orig;
				} 
			else 
				{
			   	$height = $width/$ratio_orig;
				}

			if($debug) print "width: $width<br>\n";
			if($debug) print "height: $height<br>\n";

			$tmp500 	=	imagecreatetruecolor($width,$height);

			$rv	=	imagecopyresampled($tmp500,$src500,0,0,0,0,$width,$height,$width_orig,$height_orig);

			if($debug) print "500x imagecopyresampled: $rv<br>\n";

			// make resource into file
			$rv	=	imagejpeg($tmp500,$fullPath_500x,100);

			if($debug) print "500x File Creation: $rv<br>\n";

			$rv	=	imagejpeg($tmp200,$fullPath_200x,100);

			if($debug) print "200x File Creation: $rv<br>\n";

			// clean up the landscape
			imagedestroy($src500);
			imagedestroy($tmp200);
			imagedestroy($tmp500);

			$rv = chmod($fullPath_500x, 0755); 	// Read and write for owner, read for everybody else

			if($debug) print "chmod 500 returned - $rv<br>\n";

			$rv = chmod($fullPath_200x, 0755); 	// Read and write for owner, read for everybody else

			if($debug) print "chmod 200 returned - $rv<br>\n";

			$errorMessage	=	"File uploaded";
	
			if($debug) print "File Successfully Uploaded<br>\n";
			} 
		else 
			{
			$errorNumber	=	$_FILES['gaFileToSend500x']["error"];	
	
			$error			=	1;
	
			if($debug) print "Error: " . $errorNumber . "<br>\n";
			
			// deduce the error and make a message
			$errorMessage	=	FileMoveError("500x File Upload",$errorNumber);	
			}

		} //  end if(!$error)

	// we have the data and the files
	// now for the database part...

	// add the data to the image library table		
	if(!$error && !$done)
		{
		// add the image data
		$rv	=	AddDetailImage($gaFileName,$fileName,$gaArtistID);

		if($rv == FALSE)
			{
			$error			=	1;	
			$errorMessage	=	"Database Error, Unable to add detail image data.";	
			}
			
		}

	if(!$error)
		{
		if($done)
			$errorMessage	=	"";
		else
			$errorMessage	=	"File $gaFileName Uploaded";
		}
// ---------------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------------------------

	// this is the standard
	$gaArgs		=	"gaTable=$gaTable&gaArtistID=$gaArtistID&gaPageName=$gaPageName&gaImageIndex=$gaImageIndex&gaPageNumber=$gaPageNumber&gaGallery=$gaGallery&gaFileName=$gaFileName&gaTitle=$gaTitle&gaCaption=$gaCaption&gaLocation=$gaLocation&gaArtist=$gaArtist&gaMedia=$gaMedia&gaStatus=$gaStatus&gaPresentation=$gaPresentation&gaWidthOne=$gaWidthOne&gaWidthTwo=$gaWidthTwo&gaWidthThree=$gaWidthThree&gaWidthFour=$gaWidthFour&gaHeightOne=$gaHeightOne&gaHeightTwo=$gaHeightTwo&gaHeightThree=$gaHeightThree&gaHeightFour=$gaHeightFour&gaPriceOne=$gaPriceOne&gaPriceTwo=$gaPriceTwo&gaPriceThree=$gaPriceThree&gaPriceFour=$gaPriceFour&gaEditionTypeOne=$gaEditionTypeOne&gaEditionTypeTwo=$gaEditionTypeTwo&gaEditionTypeThree=$gaEditionTypeThree&gaEditionTypeFour=$gaEditionTypeFour&gaFileToSend500x=$gaFileToSend500x&gaFileToSend200x=$gaFileToSend200x&gaDescriptionOne=$gaDescriptionOne&gaDescriptionTwo=$gaDescriptionTwo&gaDescriptionThree=$gaDescriptionThree&gaDescriptionFour=$gaDescriptionFour";
	$passedArgs	=	"$gaArgs&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
	$args		=	"StatusMessage=$errorMessage&$passedArgs";	
	$targetURL	=	"gallery_detail.php?$args";	
	}

// ---------------------------------------------------------------------------------------------------------------------------------------
// THE END
// ---------------------------------------------------------------------------------------------------------------------------------------

else
	// all bad
	$targetURL	=	"$gBaseMountPoint/index.php?StatusMessage=Unknown Value for Mode";	

// ---------------------------------------------------------------------------------------------------------------------------------------
// do the redirection
// ---------------------------------------------------------------------------------------------------------------------------------------

FormRedirect($targetURL,"POST",$debug);
?>