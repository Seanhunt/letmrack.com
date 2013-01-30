<?php
include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'question_lib.php';
include 'file_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
//include 'pw_parms.php';	
//include 'pw_lib.php';
include 'gallery_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	
include 'admin_lib.php';

include 'pw_lib.php';
include 'pw_parms.php';

$userID		=	"blogtester";
$password	=	"blogtester";
$rightsID	=	"Blog";
$eMail		=	"reeble@freddie.com";
$firstName	=	"reeble";
$lastName	=	"rreddie";

$permission	=	"APPDEV_BLOG_LifoBlog";

print "NewUserPermission: " . NewUserPermission($userID,$permission) . "<br>";

$permission	=	"APPDEV_BLOG_CalendarBlog";

print "NewUserPermission: " . NewUserPermission($userID,$permission) . "<br>";



//print "DeleteUserPermissions: " . DeleteUserPermissions($userID) . "<br>";

/*
$tableName	=	"APPDEV_BLOG_CalendarBlog";
print "AuthorizeBlogUser: " . AuthorizeBlogUser($userID,$password,$tableName) . "<br>";
*/

/*
print "AuthorizeBlogUser: " . AuthorizeBlogUser($userID,$password,$tableName) . "<br>";
$tableName	=	"PERMISSION_3";
print "AuthorizeBlogUser: " . AuthorizeBlogUser($userID,$password,$tableName) . "<br>";

//print "NewUserInfo: " . NewUserInfo($userID,$password,$rightsID,$eMail,$firstName,$lastName) . "<br>";
print "GetPassword: " . GetPassword("rubar") . "<br>";
print "UserExists: " . UserExists("rubar") . "<br>";
print "UserExists: " . UserExists("fubar") . "<br>";

$userID		=	"YYYfubar";
$password	=	"YYYfoo";
$rightsID	=	"Administrator";
$eMail		=	"YYYfeeble@freddie.com";
$firstName	=	"YYYfeeble";
$lastName	=	"YYYfreddie";
$recordNumber=	36;

print "GetNumberOfRights: " . GetNumberOfRights() . "<br>";

print "SetPassword: " . SetPassword("rubar","boo") . "<br>";

print "GetPassword: " . GetPassword("rubar") . "<br>";

//$rv	=	ModifyUserInfo($userID,$password,$rightsID,$eMail,$firstName,$lastName,$recordNumber);

//$rv	=	DeleteUser($userID);

//print "rv: $rv<br>";

// PASS
*/
?>
<br>BAR