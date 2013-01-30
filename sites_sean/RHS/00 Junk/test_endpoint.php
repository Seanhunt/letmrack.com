
<?php
include '../local_info.php';
include 'db_lib.php';
include 'cl_lib.php';
include 'file_lib.php';
include 'mail_lib.php';
include 'html_lib.php';	
include "name_value_pair_lib.php";
include 'redirect.php';
include 'pw_lib.php';
include 'numbers_lib.php';
include 'pw_parms.php';
include 'mail_sub_parms.php';
include "debug_lib.php";
include 'blog_parms.php';	




?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
</head>

<body>

<?php
$uriString	=	"test_entities.php?$blArgs";
FormRedirect($uriString,"GET",1);
?>

</body>
</html>
