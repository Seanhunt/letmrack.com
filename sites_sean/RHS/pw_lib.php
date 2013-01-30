<?php
// ---------------------------------------------------------------------------------------------------
// pw_lib.php
//
//
// Revision 2.2.0.2
//
// 2.2.0.2 had a bug in AddPasswordTarget() fixed
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------

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
	return false;	
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

		CloseDatabase();
		
		return false;
		}
	}
else
	{
	if($debug) print "AddPasswordTarget - WRITE DISABLED<br>";
	}

if($debug) print "AddPasswordTarget() - returned TRUE<br><hr>";

CloseDatabase();

return true;
}


function UserIsModerator($tableName,$userID)
{
$debug	=	0;

if($debug) print "UserIsModerator($tableName,$userID)<br>";

$query = "SELECT * FROM APPDEV_UserPermissions 
		  WHERE pwUserID='$userID'
		  AND pwPermission='$tableName'
		  AND pwModerator='1'";
		  
$rv	=	GetNumberOfRows($query);

if($debug) print "rv: $rv<br>";

return $rv;
}


// ---------------------------------------------------------------------------------------------------
// function GetTarget($targetID)
//
// To allow the password portal to fly in front of any page there is a table PasswordTargets
// that contains the targetID and the TargetPageName to allow the target to appear on the command line 
// but not have a bearing on the target page name
// ---------------------------------------------------------------------------------------------------

function GetTargetInfo($uid,$password)
{
$debug					=	0;

if($debug) print "GetTargetInfo($uid,$password)<br>";

OpenDatabase();

$query	=	"SELECT * FROM APPDEV_PasswordTargets WHERE pwUserID='$uid' AND pwPassword='$password'";

$rs	=	QueryDatabase($query);

$userExists	=	mysql_num_rows($rs);

$galleryName	=	GetDatabaseResult($rs,0,"pwTargetGalleryName");
$pageName		=	GetDatabaseResult($rs,0,"pwTargetPageName");

if($debug) print "galleryName: $galleryName<br>";
if($debug) print "pageName: $pageName<br>";
if($debug) print "query: $query<br>";
if($debug) print "userExists: $userExists<br>";

CloseDatabase();

$targetInfo	=	array();

if($userExists==0)
	{
	$targetInfo["Good"]					=	0;
	return 0;
	}

$targetInfo["pwTargetGalleryName"]	=	$galleryName;
$targetInfo["pwTargetPageName"]		=	$pageName;			
$targetInfo["Good"]					=	1;
return $targetInfo;
}







function PasswordGetTemplateFile($userID,$templateFileName)
{
$debug			=	0;

if($debug) print "PasswordGetTemplateFile($userID,$templateFileName)<br>";

$query	=	"
			SELECT * FROM
			APPDEV_UserInfo
			WHERE	
			pwUserID='$userID'
			";

OpenDatabase();

$rs	=	QueryDatabase($query);

CloseDatabase();

// round up the data
$password	=	GetDatabaseResult($rs,0,"pwPassword");
$type		=	GetDatabaseResult($rs,0,"pwRightsID");
$eMail		=	GetDatabaseResult($rs,0,"pwEMail");
$first		=	GetDatabaseResult($rs,0,"pwFirstName");
$last		=	GetDatabaseResult($rs,0,"pwLastName");

if($debug) print "password: $password<br>";
if($debug) print "type: $type<br>";
if($debug) print "eMail: $eMail<br>";
if($debug) print "first: $first<br>";
if($debug) print "last: $last<br>";


// PW FIX
// Here I have to give up the names of the blogs that permission is granted to...
$numberOfBlogs	=	GetNumberOfRows("SELECT * FROM APPDEV_UserPermissions WHERE pwUserID='$userID'");

if($debug) print "numberOfBlogs: $numberOfBlogs<br>";

$names	=	array();

for($i=0;$i<$numberOfBlogs;$i++)
	{
	$blogTable	=	GetFieldByComparisonAndIndex("APPDEV_UserPermissions","pwPermission","pwUserID",$userID,$i);
	$blogName	=	GetFieldByComparison("APPDEV_BlogHeaders","blName","blTable",$blogTable);
	$names[]	=	$blogName;		
	}		

$blogNames	=	"$names[0]";

if(count($names))
	{
	$n	=	count($names);

	for($i=1;$i<$n;$i++)
		{
		$blogNames	.=	"<br><br>$names[$i]";
		}
	}
	





if($debug) print "password: $password<br>";
if($debug) print "type: $type<br>";
if($debug) print "blogNames: $blogNames<br>";
if($debug) print "eMail: $eMail<br>";
if($debug) print "first: $first<br>";
if($debug) print "last: $last<br>";

if($password=="") 	$password	= "&nbsp;";
if($type=="") 		$type		= "&nbsp;";
if($table=="") 		$table		= "&nbsp;";
if($eMail=="") 		$eMail		= "&nbsp;";
if($first=="") 		$first		= "&nbsp;";
if($last=="") 		$last		= "&nbsp;";

// read in the file
$templateFile	=	FileRead($templateFileName);

if($debug) print "BEFORE SR - templateFile: $templateFile<br>";

// undecorate the blog name
$blogName	=	str_replace("APPDEV_BLOG_","",$table);

if($debug) print "blogName: $blogName<br>";

// do the dookey
$templateFile	=	str_replace("_FIRSTNAME_",$first,$templateFile);
$templateFile	=	str_replace("_LASTNAME_",$last,$templateFile);
$templateFile	=	str_replace("_EMAIL_",$eMail,$templateFile);
$templateFile	=	str_replace("_ACCOUNTTYPE_",$type,$templateFile);
$templateFile	=	str_replace("_BLOGNAME_",$blogNames,$templateFile);
$templateFile	=	str_replace("_USERID_",$userID,$templateFile);
$templateFile	=	str_replace("_PASSWORD_",$password,$templateFile);

global $gSiteName;
global $gSiteURL;
global $gSiteOwner;
global $gBaseMountPoint;

$siteRoot		=	$gSiteURL . $gBaseMountPoint;

$templateFile	=	str_replace("_SITEOWNERNAME_",$gSiteOwner,$templateFile);
$templateFile	=	str_replace("_SITENAME_",$gSiteName,$templateFile);
$templateFile	=	str_replace("_SITEURL_",$siteRoot,$templateFile);

if($debug) print "AFTER SR - templateFile: $templateFile<br>";

return $templateFile;
}


// fookin POS...
function GetUserTableName()
{
return "APPDEV_UserInfo";
}



function DeleteUserPermissions($userID,$permissionType="Blog")
{
$debug = 0;

$writeEnabled	=	true;

// set up a filter clause, this has been retained in 2.2.0
// to allow the deletion of all gallery rights or all blog rights
if($permissionType=="Gallery")
	{
	$andClause	=	" AND pwPermission LIKE 'APPDEV_GALLERY_%'";	
	}
else if($permissionType=="Blog")
	{
	$andClause	=	" AND pwPermission LIKE 'APPDEV_BLOG_%'";	
	}
else
	$andClause	=	"";

if($debug) print "DeleteUserPermissions($userID,$permissionType)<br>";

OpenDatabase();

// SELECT * FROM APPDEV_UserPermissions WHERE pwUserID='blogtester' AND pwPermission='APPDEV_BLOG_%'

$query 		= 	"DELETE FROM APPDEV_UserPermissions WHERE pwUserID='$userID'" . $andClause;

if($debug) print "andClause: $andClause<br>";
if($debug) print "query: $query<br>";

if($writeEnabled)
	{
	if($debug) print "DeleteUserPermissions - WRITE ENABLED<br>";

	if(($rv=QueryDatabase($query))==false)
		{
		print "SQL Error in DeleteUserPermissions() " . mysql_error() . "<br>" . "Query: $query";
		}
	}
else
	{
	if($debug) print "DeleteUserPermissions - WRITE DISABLED<br>";
	
	$rv	=	true;	
	}

if($debug) print "rv: $rv<br>";

CloseDatabase();

return $rv;
}

// this will not create duplicate permission records
// it will return success if a given permission is already present
// 2.2.0 added defaulting arg for type

function NewUserPermission($userID,$permission,$moderator,$contributor)
{
$debug = 0;
$writeEnabled	=	true;

if($debug) print "NewUserPermission($userID,$permission,$moderator,$contributor)<br>";


$query = "SELECT * FROM APPDEV_UserPermissions 
		  WHERE pwUserID='$userID' 
		  AND pwPermission='$permission'
		  AND pwModerator='$moderator'
		  AND pwContributor='$contributor'";


// if the user already has the permission return true	
if(GetNumberOfRows($query))
	{
	if($debug) print "Permission Exists<br>";
	return true;	
	}
	
OpenDatabase();

$query 		= 	sprintf("INSERT INTO APPDEV_UserPermissions 
						 (pwUserID, pwPermission, pwModerator, pwContributor) 
						 VALUES 
						 ( '%s' , '%s' , '%s' , '%s')",
						mysql_real_escape_string($userID),	
						mysql_real_escape_string($permission),	
						mysql_real_escape_string($moderator),	
						mysql_real_escape_string($contributor)
						);

if($debug) print "query: $query<br>";

if($writeEnabled)
	{
	if($debug) print "NewUserPermission - WRITE ENABLED<br>";

	if(($rv=QueryDatabase($query))==false)
		{
		print "SQL Error in NewUserPermission() " . mysql_error() . "<br>" . "Query: $query";
		}
	}
else
	{
	if($debug) print "NewUserPermission - WRITE DISABLED<br>";
	
	$rv	=	true;	
	}

if($debug) print "rv: $rv<br>";

CloseDatabase();

return $rv;
}




// This is the lib for the new multi-user granular rights system
function AuthorizeSuperUser($userID,$password)
{
$debug	=	0;

if($debug) print "AuthorizeSuperUser($userID,$password)<br>";

// unknown user ID
if(UserExists($userID)==false)
	{
	if($debug) print "UserID: $userID Not Found<br>";
	return false;
	}

// If the user is an Admin dispense with the rest...
if(PasswordIsOk($userID,$password)==1)
	{
	if(UserIsSuper($userID))
		{
		if($debug) print "User is good<br>";
		return true;
		}
	else
		{
		if($debug) print "Not Super User<br>";
		}
	}
else
	{
	if($debug) print "Bad Password<br>";
	}
	

return false;
}

// ---------------------------------------------------------------------------------------------------
// function AuthorizeGalleryUser($userID,$password)
// true if gallerey, admin or super
// New in 2.0
// Added tableName for granular rights galleries in 2.2.0
// ---------------------------------------------------------------------------------------------------

function AuthorizeGalleryUser($userID,$password,$tableName)
{
$debug	=	0;

if($debug) print "AuthorizeGalleryUser($userID,$password,$tableName)<br>";

// unknown user ID
if(UserExists($userID)==false)
	{
	if($debug) print "User: $userID does not exist...<br>";
	return false;
	}

// If the user is an Admin dispense with the rest...
if(PasswordIsOk($userID,$password)==1)
	{
	if(UserIsGallery($userID,$tableName) || UserIsAdmin($userID) || UserIsSuper($userID))
		{
		if($debug) print "User is good<br>";
		return true;
		}
	else
		{
		if($debug) print "User is not gallery user...<br>";
		}
	}
else
	{
	if($debug) print "User has bad password<br>";
	}
	
return false;
}


function AuthorizeBlogUser($userID,$password,$tableName)
{
$debug	=	0;

if($debug) print "AuthorizeBlogUser($userID,$password,$tableName)<br>";

// unknown user ID
if(UserExists($userID)==false)
	{
	if($debug) print "User does not exist...<br>";
	return false;
	}

if(PasswordIsOk($userID,$password)==1)
	{
	// If the user is an Admin dispense with the rest...
	if(UserIsAdmin($userID)==true)
		{
		// huh if(AuthorizeAdminUser($userID,$password)==true)
		return true;
		}

	if(AuthorizeUserTable($userID,$tableName)==false)
		{
		if($debug) print "User $userID is not $tableName user<br>";
		
		return false;
		}

	return true;
	}
else
	{
	if($debug) print "User $userID has bad pass<br>";
	}
	

return false;
}

// ---------------------------------------------------------------------------------------------------

// ---------------------------------------------------------------------------------------------------

function AuthorizeUserTable($userID,$permissionID)
{
if(GetNumberOfRows("SELECT * FROM APPDEV_UserPermissions WHERE pwPermission='$permissionID' AND pwUserID='$userID'"))
	return true;

return false;
}

// ---------------------------------------------------------------------------------------------------
// function AuthorizeAdminUser($userID,$password)
// 
// tests and returns true if valid admin or super user
// ---------------------------------------------------------------------------------------------------

// returns true if user is admin or super
function AuthorizeAdminUser($userID,$password)
{
$debug	=	0;

if($debug) print "AuthorizeAdminUser($userID,$password)<br>";

// unknown user ID
if(UserExists($userID)==false)
	{
	if($debug) print "UserID not Found<br>";
	return FALSE;
	}

// If the user is an Admin dispense with the rest...
if(PasswordIsOk($userID,$password)==1)
	{
	if(UserIsAdmin($userID) || UserIsSuper($userID))
		{
		if($debug) print "User is good<br>";
		return TRUE;
		}
	else
		{
		if($debug) print "Insufficient Rights<br>";
		}
	}
else
	{
	if($debug) print "Bad Password<br>";
	}
	

return FALSE;
}

// ---------------------------------------------------------------------------------------------------
// function UserIsAdmin($userID)
//
// Returns:		 true or false
// ---------------------------------------------------------------------------------------------------

function UserIsAdmin($userID)
{
$debug	=	0;

if($debug) print "UserIsAdmin($userID)<br>";

if(GetNumberOfRows("SELECT * FROM APPDEV_UserInfo WHERE pwUserID='$userID' AND pwRightsID='Administrator'")==0)
	{
	if($debug) print "NOT ADMIN: $userID<br>";
	return false;
	}

if($debug) print "ADMIN USER: $userID<br>";

return true;
}

// ---------------------------------------------------------------------------------------------------
// function UserIsGallery($userID)
//
// Returns:		 1 on success
//				 0 on No Admin Privlege 
//				-1 on Unknown user
// ---------------------------------------------------------------------------------------------------

function UserIsGallery($userID,$tableName)
{
$debug	=	0;

if($debug) print "UserIsGallery($userID,$tableName)<br>";

if(GetNumberOfRows("SELECT * FROM APPDEV_UserPermissions WHERE pwUserID='$userID' AND pwPermission='$tableName'")==0)
	{
	if($debug) print "NOT GALLERY USER: $userID<br>";
	return false;
	}

if($debug) print "GALLERY USER: $userID<br>";
return true;
}

function UserIsSuper($userID)
{
$debug	=	0;

if($debug) print "UserIsSuper($userID)<br>";

if(GetNumberOfRows("SELECT * FROM APPDEV_UserInfo WHERE pwUserID='$userID' AND pwRightsID='SuperUser'")==0)
	{
	if($debug) print "NOT SUPER: $userID<br>";
	return false;
	}

if($debug) print "SUPER USER: $userID<br>";
return true;
}

// ---------------------------------------------------------------------------------------------------
// function UserIsAdmin($userID)
//
// Returns:		 true or false
// ---------------------------------------------------------------------------------------------------

function UserIsGranular($userID)
{
$debug	=	0;

if($debug) print "UserIsGranular($userID)<br>";

$query	=	"SELECT * FROM APPDEV_UserInfo WHERE pwUserID='$userID' AND pwRightsID='Blog-Gallery'";

if($debug) print "query:  $query<br>";


if(GetNumberOfRows($query)==0)
	{
	if($debug) print "User Not Found...<br>";
	return false;
	}

return true;
}

// ---------------------------------------------------------------------------------------------------
// function GetPasswordErrorMessage($result)
// 
// Returns text errors from the rv of PasswordIsOk() function.
// ---------------------------------------------------------------------------------------------------

function GetPasswordErrorMessage($result)
{
if($result==1)
	return "No Error";
else if($result==-1)
	return "Unknown User ID";
else if($result==-2)
	return "Incorrect Password";
else if($result==-3)
	return "Incorrect Table Name";
else
	return "Unknown Error";
}

// ---------------------------------------------------------------------------------------------------
// function PasswordIsOk($userID,$password)
//
// Tests a user ID and pass for goodness
//
// Returns:		 1 on success
//				-1 on Unknown user
//				-2 on Bad pass
// ---------------------------------------------------------------------------------------------------

function PasswordIsOk($userID,$password)
{
$debug	=	0;

if($debug) print "PasswordIsOk($userID,$password)<br>";

// uinknown user ID
if(UserExists($userID)==0)
	{
	if($debug) print "Unknown User ID<br>";
	return -1;
	}

// fetch the pass for this user
$value	=	GetPassword($userID);

if($debug) print "value:  $value<br>";

if($password!=$value)
	{
	if($debug) print "Password FAIL<br>";
	return -2;
	}

if($debug) print "Password OK<br>";
return 1;
}


// ---------------------------------------------------------------------------------------------------
// function SetPassword($userID,$newPassword)
//
// Sets the password for the given User
// No Authentication just set
// ---------------------------------------------------------------------------------------------------

function SetPassword($userID,$newPassword)
{
$debug					=	0;

if($debug) print "SetPassword($userID,$newPassword)<br>";

// change the pass
$query		=	"UPDATE APPDEV_UserInfo SET pwPassword ='$newPassword' WHERE pwUserId ='$userID'";

if($debug) print "query: $query<br>";

OpenDatabase();

$rv	=	QueryDatabase($query);

CloseDatabase();

return ($rv)?true:false;
}





// ---------------------------------------------------------------------------------------------------
// function UserExists($userID)
// ---------------------------------------------------------------------------------------------------

function UserExists($userID)
{
$debug = 0;

if($debug) print "UserExists($userID)<br>";

return (GetNumberOfRows("SELECT * FROM APPDEV_UserInfo WHERE pwUserID='$userID'")==0)?false:true;
}
// ---------------------------------------------------------------------------------------------------
// function GetEMail($userID)
// ---------------------------------------------------------------------------------------------------

function GetEMail($userID)
{
$debug	=	0;

if($debug) print "GetEMail($userID)<br>";

$eMail	=	GetFieldByComparison("APPDEV_UserInfo","pwEMail","pwUserID",$userID);

return $eMail;
}

function GetPassword($userID)
{
$debug	=	0;

if($debug) print "GetPassword($userID)<br>";

$password	=	GetFieldByComparison("APPDEV_UserInfo","pwPassword","pwUserID",$userID);

if($debug) print "GetPassword returned $password<br>";

return $password;
}
// ---------------------------------------------------------------------------------------------------
// function GeneratePassword ($length = 8)
//
// 2.2.0 Knocked out the cap O and number 0 as they confuse users
// ---------------------------------------------------------------------------------------------------

// generates a cryptic higher security password
function GeneratePassword ($length = 8)
{
// start with a blank password
$password = "";

// define possible characters
$possible = "123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ"; 

// set up a counter
$i = 0; 

// add random characters to $password until $length is reached
while($i < $length) 
	{ 
	// pick a random character from the possible ones
	$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

	// we don't want this character if it's already in the password
	if (!strstr($password, $char)) 
		{ 
		$password .= $char;
		$i++;
		}
	}

// done!
return $password;
}
// ---------------------------------------------------------------------------------------------------
// function GenerateFriendlyPassword()
// ---------------------------------------------------------------------------------------------------

// gens a friendly medium security password
function GenerateFriendlyPassword()
{
srand((double)microtime()*1000000); // start the random generator

global $gFriendlyPasswords;

$words	=	$gFriendlyPasswords;

do	{
	$index		=	rand() % (count($words)-1);	
	$password	=	$words[$index];
	}while($password=="");

$flip	=	rand(0,1);
$num	=	rand(1,20);

if($flip)
	$password	=	$num . $password;
else
	$password	=	$password . $num;

return $password;
}

function GetNumberOfRights()
{
$debug			=	0;

if($debug) print "GetNumberOfRights()<br>";

$query	=	"SELECT * FROM APPDEV_SystemRights";

if($debug) print "query: $query<br>";

return GetNumberOfRows($query);
}


function ModifyUserInfo($userID,$password,$rightsID,$eMail,$firstName,$lastName,$recordNumber)
{
$debug			=	0;
$writeEnabled	=	true;

if($debug) print "ModifyUserInfo($userID,$password,$rightsID,$eMail,$firstName,$lastName)<br>";

OpenDatabase();

$query 		= 	sprintf("UPDATE 
						 APPDEV_UserInfo 
						 SET
						 pwUserID='%s',
						 pwPassword='%s',
						 pwRightsID='%s',
						 pwEMail='%s',
						 pwFirstName='%s',
						 pwLastName='%s'
						 WHERE
						 pwRecordNumber='%s'",
						mysql_real_escape_string($userID),	
						mysql_real_escape_string($password),
						mysql_real_escape_string($rightsID),
						mysql_real_escape_string($eMail),
						mysql_real_escape_string($firstName),
						mysql_real_escape_string($lastName),
						mysql_real_escape_string($recordNumber)
						);

if($debug) print "query: $query<br>";

if($writeEnabled)
	{
	if($debug) print "ModifyUserInfo - WRITE ENABLED<br>";

	if(($rv=QueryDatabase($query))==false)
		{
		print "SQL Error in ModifyUserInfo() " . mysql_error() . "<br>" . "Query: $query";
		}
	}
else
	{
	if($debug) print "ModifyUserInfo - WRITE DISABLED<br>";
	
	$rv	=	true;	
	}

if($debug) print "rv: $rv<br>";

CloseDatabase();

return $rv;
}

// creats an info record for the user
function NewUserInfo($userID,$password,$rightsID,$eMail,$firstName,$lastName)
{
$debug	=	0;
$writeEnabled	=	true;

if($debug) print "NewUserInfo($userID,$password,$rightsID,$eMail,$firstName,$lastName)<br>";
	
OpenDatabase();

$query 		= 	sprintf("INSERT INTO APPDEV_UserInfo 
						 (pwUserID, pwPassword, pwRightsID, pwEMail, pwFirstName, pwLastName) 
						 VALUES 
						 ( '%s' , '%s' , '%s' , '%s' , '%s' , '%s')",
						mysql_real_escape_string($userID),	
						mysql_real_escape_string($password),	
						mysql_real_escape_string($rightsID),	
						mysql_real_escape_string($eMail),
						mysql_real_escape_string($firstName),
						mysql_real_escape_string($lastName)
						);

if($debug) print "query: $query<br>";

if($writeEnabled)
	{
	if($debug) print "NewUserInfo - WRITE ENABLED<br>";

	if(($rv=QueryDatabase($query))==false)
		{
		print "SQL Error in NewUserInfo() " . mysql_error() . "<br>" . "Query: $query";
		}
	}
else
	{
	if($debug) print "NewUserInfo - WRITE DISABLED<br>";
	
	$rv	=	true;	
	}

if($debug) print "rv: $rv<br>";

CloseDatabase();

return $rv;
}

// this will return false if permissions table has no entries
// disregard the rv
function DeleteUser($userID)
{
$debug	=	0;
$rv		=	true;

if($debug) print "DeleteUser($userID)<br>";

$writeEnabled	=	true;

$userInfo			=	"DELETE FROM APPDEV_UserInfo WHERE pwUserID='$userID'";
$userPermissions	=	"DELETE FROM APPDEV_UserPermissions WHERE pwUserID='$userID'";

if($debug) print "userInfo: $userInfo<br>";
if($debug) print "userPermissions: $userPermissions<br>";

if($writeEnabled)
	{
	if($debug) print "DeleteUser - WRITE ENABLED<br>";

	OpenDatabase();
	
	if(QueryDatabase($userInfo)==false)
		{
		print "SQL Error in DeleteUser() " . mysql_error() . "<br>" . "Query: $userInfo";
		$rv		=	false;
		}
		
	if(QueryDatabase($userPermissions)==false)
		{
		print "SQL Error in DeleteUser() " . mysql_error() . "<br>" . "Query: $userPermissions";
		$rv		=	false;
		}
	
	CloseDatabase();
	}
else
	{
	if($debug) print "DeleteUser - WRITE DISABLED<br>";
	
	$rv	=	true;	
	}

if($debug) print "rv: $rv<br>";

return $rv;
}

// retrieves the current state of the parms as a URI formatted NVP string
function GetPasswordParms()
{
global $pwUserID;
global $pwPassword;
global $pwNewPassword;
global $pwRightsID;
global $pwEMail;
global $pwFirstName;
global $pwLastName;
global $pwRecordNumber;
global $pwPermission;
global $pwMode;
global $pwTargetID;
global $pwPasswordConfirm;
global $pwCaller;
global $pwGalleryType;
global $pwGalleryName;

$debug		=	0;

if($debug) print "GetPasswordParms()<br>";

$argList	=	"pwNewPassword=$pwNewPassword&pwCaller=$pwCaller&pwPasswordConfirm=$pwPasswordConfirm&TargetID=$targetID&pwEMail=$pwEMail&pwFirstName=$pwFirstName&pwLastName=$pwLastName&pwRecordNumber=$pwRecordNumber&pwPermission=$pwPermission&pwMode=$pwMode&pwRightsID=$pwRightsID&pwUserID=$pwUserID&pwPassword=$pwPassword&pwGalleryType=$pwGalleryType&pwGalleryName=$pwGalleryName";

if($debug) print "argList: $argList<br>";

return $argList;
}


?>