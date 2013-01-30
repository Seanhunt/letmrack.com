<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// debug_testbed.php
//
// This is the testbed for the debug utility
// 
// Revision:	080806 File Originated
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

include("../local_info.php");
include("db_lib.php");
include("debug_lib.php");

$debug	=	true;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Debug Library Test Harness</title>
<script src="debug_lib.js" language="JavaScript" type="text/JavaScript"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body <?php DebugMessagePump($debug); ?>>
FOO
<?php
$foo	=	"FOO";
$bar	=	"BAR";
$biteMe	=	"BITE";

/*
DebugMessage("Simple Call - foo: $foo");
DebugMessage("Simple Call - bar: $bar",$debug);
DebugMessage("foo: $foo",$debug,__LINE__,__FILE__,__FUNCTION__);
DebugMessage("foo: $foo",$debug,__LINE__,__FILE__,__FUNCTION__);
*/

TestFunc();

function TestFunc()
{
$debug	=	true;
$foo	=	"bar";

DebugMessage("In TestFunc() foo: $foo",$debug,__LINE__,__FILE__,__FUNCTION__);

print "<br>FUBAR!!! " . __FUNCTION__ . "()<br>";
}

?>
BAR
</body>
</html>
