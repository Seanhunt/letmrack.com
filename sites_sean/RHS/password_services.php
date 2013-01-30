<?php
// -----------------------------------------------------------------------------------------
//
// 2.2.0 - 022208 	Reworked the beginning of ligin to get rid of spurious call to 
//					UserExists() attempting to improve login performance in 
//					password_services.php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// -----------------------------------------------------------------------------------------

include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'blog_lib.php';
include 'html_lib.php';	
include "name_value_pair_lib.php";
include 'pw_parms.php';	
include 'file_lib.php';
include 'gallery_lib.php';
include 'pw_lib.php';
include 'status_message.php';	
include 'redirect.php';	
include 'admin_lib.php';
include 'question_lib.php';
include 'mail_lib.php';

$debug		=	0;

if($pwMode=="NewGallery")
	{
	if($debug) print "<hr>Processing: NewGallery<br>";

	// -----------------------------------------------------------------------------------------
	// does this user exist already ?
	// -----------------------------------------------------------------------------------------

	if(GetNumberOfRows("SELECT ArtistID FROM APPDEV_GalleryProfiles WHERE ArtistID='$pwUserID'"))
		{
		// Cant create
		$statusMessage	= 	"A User can only moderate one gallery.";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}


	// -----------------------------------------------------------------------------------------
	// do the two passwords match ?
	// -----------------------------------------------------------------------------------------

	if(($pwGalleryName =="") && !$error)
		{
		// Cant create
		$statusMessage	= 	"Please enter the gallery name...";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}

	// -----------------------------------------------------------------------------------------
	// create the user record
	//
	// This will make the gallery OWNER
	// the owner is GRANULAR but has their UID embedded in the table name
	// when the owner logs in they will go through the granular portal
	// and get a bump of rights at the editor to allow editing the profile and more
	// -----------------------------------------------------------------------------------------

	// give the moderator permission

	if(!$error)
		{
		NewUserPermission($pwUserID,"APPDEV_GALLERY_$pwUserID",1,0);
		}
			
	// -----------------------------------------------------------------------------------------
	// create the gallery extras
	// -----------------------------------------------------------------------------------------

	if(!$error) // Gallery user type deprecated in 2.2.0
		{
		if($debug) print "Create Gallery Extras...<br>";

		// set up the gallery infrastructure for the user
		$rv	=	NewGalleryUser($pwUserID,$pwGalleryType);

		if(!$rv)
			{
			$statusMessage	= 	"Unable to create gallery directories or table infrastructure...";

			if($debug) print "ERROR: $statusMessage<br>";

			$error			=	1;
			}
		}


	if(!$error)
		{
		// set the gallery name
		if(strlen($pwGalleryName))
			$rv	=	SetGalleryTitle($pwUserID,$pwGalleryName);

		if($debug) print "pwGalleryName: $pwGalleryName<br>";

		if(!$rv)
			{
			$statusMessage	= 	"Unable to set gallery title.";

			if($debug) print "ERROR: $statusMessage<br>";

			$error			=	1;
			}
		}


	// -----------------------------------------------------------------------------------------
	// send the user notification
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$toAddress	=	GetEMail($pwUserID);
		$fromAddress=	GetEMail("webmaster");
		$subject	=	"[New User Information] Your User ID and Password for $gSiteName";  
		$fromName	=	"$gSiteName Admin";

		// select the appropriate message template
		
		$templateFileName	=	"../messages/user_new_gallery.html";

		// get personalized message to user
		$message	=	PasswordGetTemplateFile($pwUserID,$templateFileName);
		
		if($debug) print "Send Mail To User<br>";
		if($debug) print "toAddress: $toAddress<br>";
		if($debug) print "fromAddress: $fromAddress<br>";
		if($debug) print "subject: $subject<br>";
		if($debug) print "fromName: $fromName<br>";
		if($debug) print "templateFileName: $templateFileName<br>";
		//if($debug) print "message: $message<br>";

		if(!$error)
			{
			if(email_send($toAddress,$fromAddress,$fromName,$subject,$message)!=1)
				print "Error - Unable to Send Mail, Please Contact System Administrator";
			}
		}

	// -----------------------------------------------------------------------------------------
	// send mail to web master
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$toAddress	=	GetEMail("webmaster");
	
		// Web master message
		$messageTemplateFile	=	"../messages/user_new_notify.html";
		
		$message	=	PasswordGetTemplateFile($pwUserID,$messageTemplateFile);
	
		if($debug) print "Send Mail To Webmaster<br>";
		if($debug) print "toAddress: $toAddress<br>";
		if($debug) print "fromAddress: $fromAddress<br>";
		if($debug) print "subject: $subject<br>";
		if($debug) print "fromName: $fromName<br>";
		//if($debug) print "message: $message<br>";
	
		if(!$error)
			{
			if(email_send($toAddress,$fromAddress,$fromName,$subject,$message)!=1)
				print "Error - Unable to Send Mail, Please Contact System Administrator";
			}
		}

	// -----------------------------------------------------------------------------------------
	// Set up the return conditions
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$addlArgs	=	"StatusMessage=$statusMessage&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$returnURL	=	"$pwCaller?$addlArgs";
		}
	else	// error
		{
		$addlArgs	=	"StatusMessage=$statusMessage&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$pwArgs		=	GetPasswordParms();
		$returnURL	=	"$pwCaller?$pwArgs&$addlArgs";
		}	

	if($debug) print "pwCaller: $pwCaller<br>";
	if($debug) print "returnURL: $returnURL<br>";
	if($debug) print "statusMessage: $statusMessage<br><hr>";
	} // end of if($pwMode=="NewGallery")

// -----------------------------------------------------------------------------------------
// New Admin or Super User
// -----------------------------------------------------------------------------------------

if($pwMode=="NewAdminUser")
	{
	if($debug) print "Processing: NewAdminUser<br>";

	// make lc useer id from firstlast
	$pwUserID	=	strtolower("$pwFirstName$pwLastName");
	
	// strip spaces
	$pwUserID	=	str_replace(" ","",$pwUserID);
	
	// gen up a hs password
	$pwPassword	=	GenerateFriendlyPassword();

	// -----------------------------------------------------------------------------------------
	// does this user exist already ?
	// -----------------------------------------------------------------------------------------

	if(UserExists($pwUserID)==true)
		{
		// Cant create
		$statusMessage	= 	"User Exists...";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}

	// -----------------------------------------------------------------------------------------
	// create the user record
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$rv	=	NewUserInfo($pwUserID,$pwPassword,$pwRightsID,$pwEMail,$pwFirstName,$pwLastName);
				
		if(!$rv)
			{
			// Cant create
			$statusMessage	= 	"Unable to create new user - Please contact the system administrator.";
			$error			=	1;

			if($debug) print "ERROR: $statusMessage<br>";
			}
		else
			{
			// good, all good
			$statusMessage	= 	"New Admin User Created";
			$error			=	0;
			}
		}

	// -----------------------------------------------------------------------------------------
	// send the user notification
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$toAddress	=	GetEMail($pwUserID);
		$fromAddress=	GetEMail("webmaster");
		$subject	=	"[New User Information] Your User ID and Password for $gSiteName";  
		$fromName	=	"$gSiteName Admin";

		// select the message template
		$templateFileName	=	"../messages/user_new_admin.html";

		// get personalized message for user
		$message	=	PasswordGetTemplateFile($pwUserID,$templateFileName);
		
		if($debug) print "Send Mail To User<br>";
		if($debug) print "toAddress: $toAddress<br>";
		if($debug) print "fromAddress: $fromAddress<br>";
		if($debug) print "subject: $subject<br>";
		if($debug) print "fromName: $fromName<br>";
		if($debug) print "templateFileName: $templateFileName<br>";
		//if($debug) print "message: $message<br>";

		if(!$error)
			{
			if(email_send($toAddress,$fromAddress,$fromName,$subject,$message)!=1)
				print "Error - Unable to Send Mail, Please Contact System Administrator";
			}
		}

	// -----------------------------------------------------------------------------------------
	// send mail to web master
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$toAddress	=	GetEMail("webmaster");
	
		// Web master message
		$messageTemplateFile	=	"../messages/user_new_notify.html";
		
		$message	=	PasswordGetTemplateFile($pwUserID,$messageTemplateFile);
	
		if($debug) print "Send Mail To Webmaster<br>";
		if($debug) print "toAddress: $toAddress<br>";
		if($debug) print "fromAddress: $fromAddress<br>";
		if($debug) print "subject: $subject<br>";
		if($debug) print "fromName: $fromName<br>";
		//if($debug) print "message: $message<br>";
	
		if(!$error)
			{
			if(email_send($toAddress,$fromAddress,$fromName,$subject,$message)!=1)
				print "Error - Unable to Send Mail, Please Contact System Administrator";
			}
		}

	// -----------------------------------------------------------------------------------------
	// Set up the return conditions
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$addlArgs	=	"StatusMessage=$statusMessage&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$returnURL	=	"$pwCaller?$addlArgs";
		}
	else	// error
		{
		$addlArgs	=	"StatusMessage=$statusMessage&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$pwArgs		=	GetPasswordParms();
		$returnURL	=	"$pwCaller?$pwArgs&$addlArgs";
		}	

	// cya
	if($debug) print "pwCaller: $pwCaller<br>";
	if($debug) print "returnURL: $returnURL<br>";
	if($debug) print "statusMessage: $statusMessage<br>";
	} // end of if($pwMode=="NewAdminUser")


// New Gallery or Blog Sub User
// This does not create the gallery assets structure!!!

if($pwMode=="NewGranularRightsUser")
	{
	if($debug) print "Processing: NewGranularRightsUser<br>";
	
	$error	=	0;
	
	// -----------------------------------------------------------------------------------------
	// gotta have the goop or gank
	// -----------------------------------------------------------------------------------------

	if(($pwEMail =="") && !$error)
		{
		// Cant create
		$statusMessage	= 	"Please enter an email address and re-set your permissions checkboxes...";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}

	if(($pwFirstName ==""))
		{
		// Cant create
		$statusMessage	= 	"Please enter a first name and re-set your permissions checkboxes...";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}

	if(($pwLastName =="") && !$error)
		{
		// Cant create
		$statusMessage	= 	"Please enter a last name and re-set your permissions checkboxes...";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}
	
	
	// make lc useer id from firstlast
	$pwUserID	=	strtolower("$pwFirstName$pwLastName");
	
	// strip spaces
	$pwUserID	=	str_replace(" ","",$pwUserID);
	
	// gen up a hs password
	$pwPassword	=	GenerateFriendlyPassword();


	// -----------------------------------------------------------------------------------------
	// if user does not exist make a user else continue
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		if(UserExists($pwUserID)==false)
			{
			$rv	=	NewUserInfo($pwUserID,$pwPassword,$pwRightsID,$pwEMail,$pwFirstName,$pwLastName);
					
			if(!$rv)
				{
				// Cant create
				$statusMessage	= 	"Unable to create new gallery sub user - Please contact the system administrator.";
				$error			=	1;
			
				if($debug) print "ERROR: $statusMessage<br>";
				}
			else
				{
				// good, all good
				$statusMessage	= 	"New User Created";
				$error			=	0;
	
				if($debug) print "SUCCESS: $statusMessage<br>";
				}
			}
		}
		
	// -----------------------------------------------------------------------------------------
	// add the permissions
	// -----------------------------------------------------------------------------------------

	// BLOG PERMISSIONS
	if(!$error)
		{
		// clean up any old permissions for galleries
		DeleteUserPermissions($pwUserID,"Blog");

		// go through the blog table and see what is checked
		$numberOfBlogs	=	GetNumberOfRows("SELECT * FROM APPDEV_BlogHeaders");
	
		if($debug) print "numberOfBlogs: $numberOfBlogs<br>";
	
		// -----------------------------------------------------------------------------------------
		// Iterate the 0-n... checkboxes from the dialog and grant rights as indicated
		// -----------------------------------------------------------------------------------------
	
		for($i=0;$i<$numberOfBlogs;$i++)
			{
			$moderatorCheckBox		=	"checkbox_bm$i";
			$contributorCheckBox	=	"checkbox_bc$i";

			$m	=	GetPassedValue($moderatorCheckBox);
			$c	=	GetPassedValue($contributorCheckBox);

			if($debug) print "checkbox_bm$i: $m<br>";
			if($debug) print "checkbox_bc$i: $c<br>";
	
			$blogTable		=	GetFieldByIndex("APPDEV_BlogHeaders","blTable",$i);

			// set up the permission
			if($m && $c)
				{
				if($debug) print "MODERATOR - $i: $blogTable<br>";

				NewUserPermission($pwUserID,$blogTable,1,0);
				}
			else if(!$m && $c)
				{
				if($debug) print "CONTRIBUTOR - $i: $blogTable<br>";
				NewUserPermission($pwUserID,$blogTable,0,1);
				}
			else if($m && !$c)
				{
				if($debug) print "MODERATOR - $i: $blogTable<br>";
				NewUserPermission($pwUserID,$blogTable,1,0);
				}
			else
				{
				if($debug) print "NO RIGHTS - $i: $blogTable<br>";
				//NewUserPermission($pwUserID,$blogTable,0,0);
				}			
			}		


		// GALLERY PERMISSIONS

		// clean up any old permissions for galleries
		DeleteUserPermissions($pwUserID,"Gallery");
		
		// -----------------------------------------------------------------------------------------
		// Iterate the 0-n... checkboxes from the dialog and grant rights as indicated
		// -----------------------------------------------------------------------------------------

		$numberOfGalleries	=	GetNumberOfRows("SELECT * FROM APPDEV_GalleryProfiles");

		if($debug) print "numberOfGalleries: $numberOfGalleries<br>";
		
		// -----------------------------------------------------------------------------------------
		// Iterate the 0-n... checkboxes from the dialog and grant rights as indicated
		// -----------------------------------------------------------------------------------------
	
		for($i=0;$i<$numberOfGalleries;$i++)
			{
			$moderatorCheckBox		=	"checkbox_gm$i";
			$contributorCheckBox	=	"checkbox_gc$i";

			$m	=	GetPassedValue($moderatorCheckBox);
			$c	=	GetPassedValue($contributorCheckBox);

			if($debug) print "checkbox_bm$i: $m<br>";
			if($debug) print "checkbox_bc$i: $c<br>";
	
			$galleryTable		=	"APPDEV_GALLERY_" . GetFieldByIndex("APPDEV_GalleryProfiles","ArtistID",$i);

			// set up the permission
			if($m && $c)
				{
				if($debug) print "MODERATOR - $i: $galleryTable<br>";

				NewUserPermission($pwUserID,$galleryTable,1,0);
				}
			else if(!$m && $c)
				{
				if($debug) print "CONTRIBUTOR - $i: $galleryTable<br>";
				NewUserPermission($pwUserID,$galleryTable,0,1);
				}
			else if($m && !$c)
				{
				if($debug) print "MODERATOR - $i: $galleryTable<br>";
				NewUserPermission($pwUserID,$galleryTable,1,0);
				}
			else
				{
				if($debug) print "NO RIGHTS - $i: $galleryTable<br>";
				}			
			
			} // end of for($i=0;$i<$numberOfGalleries;$i++)		





		} // END OF if(!$error)



		
	
	// -----------------------------------------------------------------------------------------
	// send the user notification
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$toAddress	=	$pwEMail;
		$fromAddress=	GetEMail("webmaster");
		$subject	=	"[New User Information] Your User ID and Password for $gSiteName";  
		$fromName	=	"$gSiteName Admin";

		// TEST: select the appropriate message template
		$templateFileName	=	"../messages/user_new_granular_rights.html";

		// get personalized message to user
		$message	=	PasswordGetTemplateFile($pwUserID,$templateFileName);
		
		if($debug) print "Send Mail To User<br>";
		if($debug) print "toAddress: $toAddress<br>";
		if($debug) print "fromAddress: $fromAddress<br>";
		if($debug) print "subject: $subject<br>";
		if($debug) print "fromName: $fromName<br>";
		if($debug) print "templateFileName: $templateFileName<br>";
		//if($debug) print "message: $message<br>";

		if(email_send($toAddress,$fromAddress,$fromName,$subject,$message)!=1)
			print "Error - Unable to Send Mail, Please Contact System Administrator";
		}

	// -----------------------------------------------------------------------------------------
	// send mail to web master
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$toAddress	=	GetEMail("webmaster");
	
		// Web master message
		$messageTemplateFile	=	"../messages/user_new_notify.html";
		
		$message	=	PasswordGetTemplateFile($pwUserID,$messageTemplateFile);
	
		if($debug) print "Send Mail To Webmaster<br>";
		if($debug) print "toAddress: $toAddress<br>";
		if($debug) print "fromAddress: $fromAddress<br>";
		if($debug) print "subject: $subject<br>";
		if($debug) print "fromName: $fromName<br>";
		//if($debug) print "message: $message<br>";
	
		if(email_send($toAddress,$fromAddress,$fromName,$subject,$message)!=1)
			print "Error - Unable to Send Mail, Please Contact System Administrator";
		}

		// -----------------------------------------------------------------------------------------
		// Set up the return conditions
		// -----------------------------------------------------------------------------------------
	
		if(!$error)
			{
			$addlArgs	=	"StatusMessage=$statusMessage&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
			$returnURL	=	"$pwCaller?$addlArgs";
			}
		else	// error
			{
			$addlArgs	=	"StatusMessage=$statusMessage&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
			$pwArgs		=	GetPasswordParms();
			$returnURL	=	"$pwCaller?$pwArgs&$addlArgs";
			}	
	
	
		if($debug) print "pwCaller: $pwCaller<br>";
		if($debug) print "returnURL: $returnURL<br>";
		if($debug) print "statusMessage: $statusMessage<br>";
	}			





// -----------------------------------------------------------------------------------------
// NEW USER this is changing...
// -----------------------------------------------------------------------------------------

// FIX this needs to be seperated into gallery, blog and admin/super

if($pwMode=="NewUser")
	{
	if($debug) print "Processing: NewUser<br>";

	// -----------------------------------------------------------------------------------------
	// does this user exist already ?
	// -----------------------------------------------------------------------------------------

	if(UserExists($pwUserID)==true)
		{
		// Cant create
		$statusMessage	= 	"User Exists...";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}

	// -----------------------------------------------------------------------------------------
	// do the two passwords match ?
	// -----------------------------------------------------------------------------------------

	if(($pwPassword != $pwPasswordConfirm) && !$error)
		{
		// Cant create
		$statusMessage	= 	"The password does not match the confirmation password";
		$error			=	1;

		if($debug) print "ERROR: $statusMessage<br>";
		}
		
	// -----------------------------------------------------------------------------------------
	// create the user record
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$rv	=	NewUserInfo($pwUserID,$pwPassword,$pwRightsID,$pwEMail,$pwFirstName,$pwLastName);
				
		if(!$rv)
			{
			// Cant create
			$statusMessage	= 	"Unable to create new user - Please contact the system administrator.";
			$error			=	1;

			if($debug) print "ERROR: $statusMessage<br>";
			}
		else
			{
			// good, all good
			$statusMessage	= 	"New User Created";
			$error			=	0;
			}
		}

	// -----------------------------------------------------------------------------------------
	// create the gallery extras
	// -----------------------------------------------------------------------------------------

	if(!$error && $pwRightsID=="Blog-Gallery") // Gallery user type deprecated in 2.2.0
		{
		if($debug) print "Create Gallery Extras...<br>";

		// set up the gallery infrastructure for the user
		$rv	=	NewGalleryUser($pwUserID,$pwGalleryType);

		if(!$rv)
			{
			$statusMessage	= 	"Unable to create gallery directories or table infrastructure...";

			if($debug) print "ERROR: $statusMessage<br>";

			$error			=	1;
			}
		}

	// -----------------------------------------------------------------------------------------
	// create the blog extra permissions
	// -----------------------------------------------------------------------------------------

	if(!$error  && $pwRightsID=="Blog-Gallery")
		{
		// PW FIX
		if($debug) print "Create Blog Permissions...<br>";

		$numberOfBlogs	=	GetNumberOfRows("SELECT blTable FROM APPDEV_BlogHeaders");

		if($debug) print "numberOfBlogs: $numberOfBlogs<br>";
		
		// clean up any old permissions
		DeleteUserPermissions($pwUserID,"Blog");
		
		// -----------------------------------------------------------------------------------------
		// Iterate the 0-n... checkboxes from the dialog and grant rights as indicated
		// -----------------------------------------------------------------------------------------

		for($i=0;$i<$numberOfBlogs;$i++)
			{
			$checkBoxName	=	"checkbox$i";
			
			$p	=	GetPassedValue($checkBoxName);
			
			if($debug) print "checkbox$i: $p<br>";

			if($p=="checked")
				{
				$blogTable		=	GetFieldByIndex("APPDEV_BlogHeaders","blTable",$i);
			
				if($debug) print "blogTable: $blogTable<br>";
			
				// create permission for this table...
				NewUserPermission($pwUserID,$blogTable,0,1);
				}
			}		
		}

	// -----------------------------------------------------------------------------------------
	// send the user notification
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$toAddress	=	GetEMail($pwUserID);
		$fromAddress=	GetEMail("webmaster");
		$subject	=	"[New User Information] Your User ID and Password for $gSiteName";  
		$fromName	=	"$gSiteName Admin";

		// select the appropriate message template
		
		if($pwRightsID=="Blog-Gallery")
			{
			$templateFileName	=	"../messages/user_new_gallery.html";
			}
		else if($pwRightsID=="Administrator" || $pwRightsID=="SuperUser")
			{
			$templateFileName	=	"../messages/user_new_admin.html";
			}
		else
			{
			$statusMessage	=	"Unknown pwRightsID";
			$error	=	1;
			}
		// get personalized message to user
		$message	=	PasswordGetTemplateFile($pwUserID,$templateFileName);
		
		if($debug) print "Send Mail To User<br>";
		if($debug) print "toAddress: $toAddress<br>";
		if($debug) print "fromAddress: $fromAddress<br>";
		if($debug) print "subject: $subject<br>";
		if($debug) print "fromName: $fromName<br>";
		if($debug) print "templateFileName: $templateFileName<br>";
		//if($debug) print "message: $message<br>";

		if(!$error)
			{
			if(email_send($toAddress,$fromAddress,$fromName,$subject,$message)!=1)
				print "Error - Unable to Send Mail, Please Contact System Administrator";
			}
		}

	// -----------------------------------------------------------------------------------------
	// send mail to web master
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$toAddress	=	GetEMail("webmaster");
	
		// Web master message
		$messageTemplateFile	=	"../messages/user_new_notify.html";
		
		$message	=	PasswordGetTemplateFile($pwUserID,$messageTemplateFile);
	
		if($debug) print "Send Mail To Webmaster<br>";
		if($debug) print "toAddress: $toAddress<br>";
		if($debug) print "fromAddress: $fromAddress<br>";
		if($debug) print "subject: $subject<br>";
		if($debug) print "fromName: $fromName<br>";
		//if($debug) print "message: $message<br>";
	
		if(!$error)
			{
			if(email_send($toAddress,$fromAddress,$fromName,$subject,$message)!=1)
				print "Error - Unable to Send Mail, Please Contact System Administrator";
			}
		}

	// -----------------------------------------------------------------------------------------
	// Set up the return conditions
	// -----------------------------------------------------------------------------------------

	if(!$error)
		{
		$addlArgs	=	"StatusMessage=$statusMessage&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$returnURL	=	"$pwCaller?$addlArgs";
		}
	else	// error
		{
		$addlArgs	=	"StatusMessage=$statusMessage&SystemUserID=$systemUserID&SystemPassword=$systemPassword";
		$pwArgs		=	GetPasswordParms();
		$returnURL	=	"$pwCaller?$pwArgs&$addlArgs";
		}	

	if($debug) print "pwCaller: $pwCaller<br>";
	if($debug) print "returnURL: $returnURL<br>";
	if($debug) print "statusMessage: $statusMessage<br>";
	}

// ************************************************************************************************************
// Lost Password
// ************************************************************************************************************

else if($pwMode=="LostPassword")
	{
	if($debug) print "Processing: LostPassword<br>";

	$error	=	0;	// 0 if no error 1 if error

	// does user exist ?
	if(UserExists($pwUserID)==0)
		{
		if($debug) print "User does not exist...<br>";
		// User does not exist
		$statusMessage	= 	"The UserID $pwUserID does not exist.";
		$error			=	1;
				
		// FIX needs to return to caller
		}
	else
		{
		// get the password
		$password	=	GetPassword($pwUserID);
		
		// get the email
		$toAddress	=	GetEMail($pwUserID);
	
		$fromAddress=	GetEMail("webmaster");
		
		$subject	=	"[Password Recovery] Your Password for $gSiteName";  
	
		$fromName	=	"Website Services";
	
		$message	=	PasswordGetTemplateFile($pwUserID,"../messages/user_lost_pass.html");

		if(email_send($toAddress,$fromAddress,$fromName,$subject,$message)==1)
			{
			$error			=	0;
			$statusMessage	=	"The password for $pwUserID has been sent to that users registered email address";
			}
		else
			{
			$error	=	1;
			$statusMessage	=	"Error - Unable to Send Mail, Please Contact System Administrator";
			}
		} // if(strcmp($pwMode,"LostPassword")==0)

	$returnURL	=	"../pw_login.php?&StatusMessage=$statusMessage&pwUserID=$pwUserID";

	if($debug) print "password: $password<br>";
	if($debug) print "toAddress: $toAddress<br>";
	if($debug) print "fromAddress: $fromAddress<br>";
	if($debug) print "subject: $subject<br>";
	if($debug) print "message: $message<br>";
	if($debug) print "error: $error<br>";
	if($debug) print "statusMessage: $statusMessage<br>";
	} // end of if(strcmp($pwMode,"LostPassword")==0)

// ************************************************************************************************************
// Change Password
// ************************************************************************************************************

else if($pwMode=="ChangePassword")
	{
	$error 	=	0;
	
	if($debug) print "Processing: ChangePassword<br>";

	// -----------------------------------------------------------------------------------------------
	// does user exist ?
	// -----------------------------------------------------------------------------------------------

	if(UserExists($pwUserID)==false)
		{
		$statusMessage	= 	"The user: $pwUserID does not exist.";
		$error			=	1;
		$returnURL		=	"../password_change.php?StatusMessage=$statusMessage&pwUserID=$pwUserID&pwPassword=$pwPassword&pwNewPassword=$pwNewPassword&pwPasswordConfirm=$pwPasswordConfirm";
		if($debug) print "statusMessage: $statusMessage<br>";
		}

	// -----------------------------------------------------------------------------------------------
	// see if they know their password
	// -----------------------------------------------------------------------------------------------

	if(!$error)
		{
		// get the password
		$tablePassword	=	GetPassword($pwUserID);
	
		if($debug) print "tablePassword: $tablePassword<br>";
		if($debug) print "password: $password<br>";

		if($pwPassword != $tablePassword)
			{

			// User does not exist
			$statusMessage	= 	"The password for $pwUserID is incorrect.";
			$error			=	1;
			$returnURL		=	"../password_change.php?StatusMessage=$statusMessage&pwUserID=$pwUserID&pwPassword=$pwPassword&pwNewPassword=$pwNewPassword&pwPasswordConfirm=$pwPasswordConfirm";
			if($debug) print "statusMessage: $statusMessage<br>";
			}
		} // end of if(!$error)

	// -----------------------------------------------------------------------------------------------
	// see if the new passwords they sent in match		
	// -----------------------------------------------------------------------------------------------

	if(!$error)
		{

		// do the new pass and new pass confirm compare ?	
		if($pwNewPassword != $pwPasswordConfirm)
			{
			// User does not exist
			$statusMessage	= 	"The new password does not match the new password confirmation.";
			$error			=	1;
			$returnURL		=	"../password_change.php?StatusMessage=$statusMessage&pwUserID=$pwUserID&pwPassword=$pwPassword&pwNewPassword=$pwNewPassword&pwPasswordConfirm=$pwPasswordConfirm";
			if($debug) print "statusMessage: $statusMessage<br>";
			}
		} // end of if(!$error)
		
	// all is well, do the deal.
	if(!$error)
		{
		$rv	=	SetPassword($pwUserID,$pwNewPassword);
				
		if($rv==1)
			{
			$statusMessage	= 	"The password for $pwUserID has been changed.";
			$error			=	0;
			$returnURL		=	"../pw_login.php?StatusMessage=$statusMessage&pwUserID=$pwUserID";
			if($debug) print "statusMessage: $statusMessage<br>";
			
			}
		else
			{
			// db error
			$statusMessage	= 	"Database Error in SetPassword() - Could not change password for $pwUserID, Please contact the system administrator.";
			$error			=	1;
			$returnURL		=	"../pw_login.php?StatusMessage=$statusMessage&pwUserID=$pwUserID";
			if($debug) print "statusMessage: $statusMessage<br>";
			}
		} // end of if(!$error)
	} // end of if(strcmp($pwMode,"ChangePassword")==0)



// ************************************************************************************************************
// Log In
// ************************************************************************************************************

	else if($pwMode=="LogIn")
		{
		if($debug) print "Processing: LogIn<br>";
	
			$rv	=	PasswordIsOk($pwUserID,$pwPassword);
			
			if($rv == -2) // bad pass
				{
				// send home with warn
				$statusMessage	= 	"The password for $pwUserID is incorrect.";
				$error			=	1;
				$targetPageName	=	"../pw_login.php";
				$error			=	true;
				if($debug) print "statusMessage: $statusMessage<br>";
				}
			else if($rv == -1) // unknown user
				{
				$statusMessage	= 	"The password for $pwUserID is incorrect.";
				$error			=	1;
				$targetPageName	=	"../pw_login.php";
				$error			=	true;
				if($debug) print "statusMessage: $statusMessage<br>";
				}
			else
				$error			=	false; // pw and UID match

		//-----------------------------------------------------------------------------------------------
		// if admin or better send to admin home
		// -----------------------------------------------------------------------------------------------

		if(!$error)
			{
			if(UserIsAdmin($pwUserID) || UserIsSuper($pwUserID))
				{
				if($debug) print "** USER IS ADMIN OR SUPER **<br>";
				$targetPageName 	=	"admin_home.php";	
				}
			else if(UserIsGranular($pwUserID))
				{
				$numberOfTables	=	GetNumberOfRows("SELECT * FROM APPDEV_UserPermissions WHERE pwUserID='$pwUserID'");

				if($debug) print "numberOfTables: $numberOfTables<br>";

				if($numberOfTables > 1)
					{
					if($debug) print "** USER IS GRANULAR WITH MUTIPLE TABLE ACCESS **<br>";
	
					// we know the user is blogger and the target is the table selector page
					
					$targetPageName 	=	"password_select_blog.php";	
					//$statusMessage		= 	"Please select the blog into which you would like to post...";
					}
				else	// go directly to the editor
					{
					if($debug) print "** USER IS GRANULAR WITH SINGLE TABLE ACCESS **<br>";
					// get the one table authorized
					$blTable				=	GetFieldByComparison("APPDEV_UserPermissions","pwPermission","pwUserID",$pwUserID);

					if($debug) print "blTable: $blTable<br>";

					if(strstr($blTable,"APPDEV_BLOG_")==true)
						{
						if($debug) print "USE BLOG EDITOR<br>";
						$addlArgs			=	"blTable=$blTable";
						$targetPageName 	=	"blog_home.php";	
						}
					else
						{
						if($debug) print "USE GALLERY EDITOR<br>";
						$addlArgs			=	"gaTable=$blTable";
						$targetPageName 	=	"gallery_home.php";	
						}
					// the blog home wants the table name as input
					}
				}
				
			else
				print "Unknown User Type<br>";

			}

		$pwArgs		=	GetPasswordParms();
		$systemArgs	=	"StatusMessage=$statusMessage&SystemUserID=$pwUserID&SystemPassword=$pwPassword";	
		$returnURL	=	"$targetPageName?$systemArgs&$pwArgs&$addlArgs";

		if($debug) print "returnURL: $returnURL<br>";
		if($debug) print "statusMessage: $statusMessage<br>";
		if($debug) print "targetPageName: $targetPageName<br>";
		}	// end of strcmp($pwMode,"LogIn")==0





// ************************************************************************************************************
// Target Mode
// ************************************************************************************************************

	else if($pwMode=="Target")
		{
		if($debug) print "Processing: Target<br>";
	
		// if not exist see ya
		//
		
		$targetInfo	=	GetTargetInfo($pwUserID,$pwPassword);
		

		if($debug) print "targetInfo: $targetInfo<br>";

		if($targetInfo["Good"]==0)	// 040308
			{	
			// send home with warn
			$statusMessage	= 	"Incorrect UserID or Password - User ID and Password are case sensitive...";
			$error			=	1;
			$returnURL	=	"../pw_login.php?StatusMessage=$statusMessage";	// 040308

			if($debug) print "statusMessage: $statusMessage<br>";
			}	
		else
			{
			if($targetInfo["pwTargetGalleryName"]=="")
				{
				$pageName	=	$targetInfo["pwTargetPageName"];
				
				$returnURL	=	"$pageName";
				}
			else
				{
				// this expects the ID
				$galleryID		=	$targetInfo["pwTargetGalleryName"];
				$galleryTable	=	"APPDEV_GALLERY_" . $galleryID;

				// get the thumbs page name
				$thumbsPageName	=	GetGalleryThumbsPage($galleryID);		

				// set up a URL for that gallery
				$returnURL	=	"../$thumbsPageName?gaGallery=$galleryTable&gaPageNumber=1";
				}
			}			
		

		if($debug) print "returnURL: $returnURL<br>";
		if($debug) print "statusMessage: $statusMessage<br>";
		if($debug) print "targetPageName: $targetPageName<br>";
		}	// end of strcmp($pwMode,"LogIn")==0




// ---------------------------------------------------------------------------------------------------------------------------------------
// do the redirection
// ---------------------------------------------------------------------------------------------------------------------------------------

FormRedirect($returnURL,"POST",$debug);
?>



