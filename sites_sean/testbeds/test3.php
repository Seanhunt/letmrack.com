<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>
FOO<br />
<?php

$fubar	=	" ";

if(IsBlankString("   "))
	print "Blank String...<br>";
else
	print "Non-blank String...<br>";

if(IsBlankString("\n\n\t ")==true)
	print "Blank String...<br>";
else
	print "Non-blank String...<br>";

if(IsBlankString(" \t\n\r\0\x0B")==true)
	print "Blank String...<br>";
else
	print "Non-blank String...<br>";

if(IsBlankString(" \t\n\r\0\x0BFUBAR")==true)
	print "Blank String...<br>";
else
	print "Non-blank String...<br>";




function IsBlankString($string)
{
print "IsBlankString($string)<br>";

//$test	=	str_replace(" \t\n\r\0\x0B","", $string);
$test	=	str_replace(" ","", $string);
$test	=	str_replace("\x0a","", $test);
$test	=	str_replace("\x09","", $test);
$test	=	str_replace("\x00","", $test);
$test	=	str_replace("\x0b","", $test);
$test	=	str_replace("\x0d","", $test);


print "ASCII codes in string: " .  implode (' ',unpack ('C*', $test)) . "<br>";


if($test=="")
	{
	return true;
	}

return false;
}



?>
<br />BAR<br />
<body>
</body>
</html>
