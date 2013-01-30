<?php
include '../local_info.php';
include 'db_lib.php';
include 'cl_lib.php';
include 'file_lib.php';
include 'pw_lib.php';
include 'html_lib.php';
include 'blog_parms.php';
include 'blog_lib.php';
include 'question_lib.php';
include 'select_controls.php';
include 'redirect.php';
include "name_value_pair_lib.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>
<body>
<form id="form1" name="form1" method="get" action="test_entities.php">


<?php
$ts	=	MakeSQLTimeStamp($blMonth,$blDay,$blYear,$blEventHour,$blEventMinute,"PM");

print "$ts<br>";

$ts	=	BreakSQLTimeStamp($ts);

print "<pre>";
var_dump($ts);
print "</pre>";

print "<br><br><br><br>";

$blEventDate		=	"$blYear-$blMonth-$blDay";

DisplayFutureDateSelector($blEventDate,2);

print "<br><br>";

print " Start Time: ";

DisplayTimeSelector($blEventHour,$blEventMinute,$blEventAmPm);

print " Event Duration: ";

DisplayDurationSelector($blDurationHour,$blDurationMinute);

print " hours<br>";

?>
<input type="submit" name="button" id="button" value="Submit" />
</form>

</body>
</html>
