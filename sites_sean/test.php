<?php

$mode	=	$_POST["Mode"];

if($mode=="DoUpload")
	{
	print "Do Upload...<br>";
	
	$localPath 			= 	'69.89.31.192/home/rollingh/RHSDev/';
//	$localPath 			= 	'69.89.31.192/home/rollingh/';
	$fileNameAndPath 	= 	$localPath . basename($_FILES['userfile']['name']);

	print "fileNameAndPath: $fileNameAndPath<br>";
	print "Temp File Name: " . $_FILES['userfile']['tmp_name'];
	
	echo '<pre>';

	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $fileNameAndPath)) 
		{
		echo "File is valid, and was successfully uploaded.\n";
		} 

	print_r($_FILES);
	
	print "</pre>";
	
	
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Test Can</title>
</head>
<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="test.php" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />

    <input type="hidden" name="Mode" value="DoUpload" />
</form>
<body>
</body>
</html>
