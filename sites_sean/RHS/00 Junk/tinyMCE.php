


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
<title>tinyMCE testbed</title>
<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
    theme : "advanced",
    mode: "exact",
    elements : "elm1",
    theme_advanced_toolbar_location : "top",
    theme_advanced_buttons1 : "bold,italic,underline,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,help",
    theme_advanced_buttons2 : "",
    theme_advanced_buttons3 : "",
	theme_advanced_toolbar_align : "left",	
    height:"350px",
    width:"600px",
  });
</script>
<!-- /tinyMCE -->

</head>
<body>


<form method="get" action="test_endpoint.php">

<?php

//$data	=	'
//			<p>This</p>
//			<p>&nbsp;</p>
//			<p>Is </p>
//			<p>A FOOBER GOOBER!!!!!@?@*&amp;(@^$@^$ </p>
//			';

$copy	=	 stripslashes(urldecode(htmlentities($_GET["copy"])));
?>

<textarea id="elm1" name="copy" rows="10" cols="40"><?php print $copy; ?></textarea>

<br />

	<input type="submit" name="save" value="Submit" />
	<input type="reset" name="reset" value="Reset" />
</form>

</body>
</html>
