<?php
// ---------------------------------------------------------------------------------------------------
// pw_lib.php
//
// Password services
//
// Revision: 	020806 First Revision
//
//				112506 Added:
//				AuthorizeAdminUser($userID,$password)

// Depends:		../local_info.php
//				db_lib.php
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

/*
// returns the full name of a given user
function paGetUserFullName($userID)
{
// fix up the table name
$tableName	=	GetUserTableName();

$firstName	=	GetFieldByComparison($tableName,"FirstName","UserID",$userID);
$lastName	=	GetFieldByComparison($tableName,"LastName","UserID",$userID);

$fullName	=	"$firstName $lastName";

return $fullName;
}*/





// ---------------------------------------------------------------------------------------------------
// function GetUserBlogTable($userID,$password)
// 
// 030207 Chopping at this to enable multitable users
// deprecated...
// ---------------------------------------------------------------------------------------------------

function GetUserBlogTable($userID,$tableName="")
{
$debug	=	0;

if($debug) print "GetUserBlogTable($tableName,$userID,$password)<br>";

if($tableName=="")
	{
	$userTableName	=	GetUserTableName();
	
	if($debug) print "userTableName:  $userTableName<br>";
	
	$result	=	GetFieldByComparison($userTableName,"TableName","UserID",$userID);
	}
else
	$result	=	$tableName;		

if($debug) print "result:  $result<br>";

return $result;
}











?>
