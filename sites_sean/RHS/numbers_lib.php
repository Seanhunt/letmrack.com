<?php
// ---------------------------------------------------------------------------------------------------
// numbers_lib.php
//
// Functions that do numerical type stuff..
//
// Revision: 	112106 	File originated
//						GenerateUniqueID moved here from password_lib
//
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

function GenerateUniqueID($prefix)
{
$guid = $prefix;
$guid .= chr(rand(65,90));
$guid .= time();
$guid .= uniqid($prefix{0});
//$guid .= uniqid($prefix);
if($debug) print $guid;

return $guid;
}

?>