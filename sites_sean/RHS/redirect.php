<?php
// ---------------------------------------------------------------------------------------------------
// redirect.php
//
// Redirection services
//
// Revision: 	021106 First Revision
//				121106 On a bug hunt IE 7 sucks
//				012507 Added FormRedirect()
//
// Depends: 	name_value_pair_lib.php
//				html_lib.php
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------------------
// function FormRedirect($uriString,$method=GET,$debugFlag=false)
//
// This does a form redirect with a doc.form.submit() in the onload tag of the page
// displays a good debug table if debug is set to true
//
//	$method == GET || PUT
//
// Depends	name_value_pair_lib
// --------------------------------------------------------------------------------------------------------

function FormRedirect($uriString,$method="GET",$debugFlag=false,$noRobots=false)
{
$debug		=	0;
$target		=	nvpGetFileNameFromURIString($uriString);

if($debug) print "<hr>FormRedirect($uriString,$method,$debugFlag)<br>\n";



if($debug) print "target: $target<br>\n";

// if debug is on don't redirect instead show a submit button
if($debugFlag)
	$onLoad	= '';	
else
	$onLoad	= ' onLoad="document.auto_submit_form.submit();"';	

// print some debug info here...
if(strstr($uriString,"?"))
	{
	if($debug) print "Had ? strip fn<br>\n";
	$array		=	explode("?",$uriString);
	$nvPairs	=	$array[1];
	}
else
	{
	if($debug) print "no ?<br>\n";
	$nvPairs	=	$uriString;
	}

$nvpArray		=	explode("&",$nvPairs);

$numberOfPairs	=	nvpGetNumberOfPairs($uriString);


if($debugFlag) 
	{
	print "<br><hr>\n";
	print	'
		<table width="400" border="1" cellspacing="0" cellpadding="0">
		  <tr align="center" bgcolor="#666666"> 
			<td height="45" colspan="3"><font color="#FFFFFF" size="5" face="Arial, Helvetica, sans-serif"><b>Form 
			  Redirect Debug Info</b></font></td>
		  </tr>
		  <tr align="center" valign="middle" bgcolor="#999999"> 
			<td height="30"><b> <font color="#E6E6E6" face="Arial, Helvetica, sans-serif">Name</font></b></td>
			<td>&nbsp;</td>
			<td height="35"><font color="#E6E6E6" face="Arial, Helvetica, sans-serif"><b>Value</b></font></b></td>
		  </tr>
			';
	}
		

for($i=0;$i<$numberOfPairs;$i++)
	{

	$name	=	nvpGetName($nvpArray[$i]);
	$value	=	nvpGetValue($nvpArray[$i]);

	if($debugFlag) 
		{
		print	'
				  <tr align="left" valign="middle"> 
					<td height="30"><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;' . $name . '&nbsp;&nbsp;</font></td>
					<td>&nbsp;&nbsp;=&nbsp;&nbsp;</td>
					<td height="30"><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;' . $value . '&nbsp;&nbsp;</font></td>
				  </tr>
				';
		}
	}

if($debugFlag) 
	{
	print	'
			  <tr bgcolor="#CCCCCC"> 
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td height="20">&nbsp;</td>
			  </tr>
			</table>
			<p>&nbsp;</p>
			';	

	print "<hr><br>\n";
	}

// now print the redir page and form
print	'
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		';

if($noRobots)
	{
	print	'
			<!-- Start hide from the robots -->		
			<meta name="robots" content="noindex,nofollow">
			<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
			<META NAME="GOOGLEBOT" CONTENT="nofollow">
			<META NAME="GOOGLEBOT" CONTENT="noindex">
			<!-- End hide from the robots -->
			';
	}
		
print	'
		</head>
		<body ' . $onLoad . '>
		<form method="' . $method . '" name="auto_submit_form" action="' . $target . '">
		';

nvpURIStringToHiddenTags($uriString);

// show submit button
if($debugFlag)
	print	'
			<input type="submit" name="Submit" value="Submit">
			';

print	'
		</form>
		</body>
		</html>
		';
}



// ---------------------------------------------------------------------------------------------------------------------------------------
// function Redirect($targetURL)
//
// Redirects the browser to the given URL by all possible CL methods.
// ---------------------------------------------------------------------------------------------------------------------------------------

function Redirect($targetURL)
{
print 	'
<!-- Redirect Page - (c) 2006 www.cookseytalbottstudio.com-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>


<script language="JavaScript">
<!--
var sTargetURL = "' . $targetURL . '";

function doRedirect()
{
setTimeout( "window.location.href = sTargetURL", 10 );
}

//-->
</script>

<script language="JavaScript1.1">
<!--
function doRedirect()
{
window.location.replace( sTargetURL );
}

doRedirect();

//-->
</script>

<head>
<title>Redirect Page</title>

<meta http-equiv="refresh" content="0;URL=' . $targetURL . '">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" onload="doRedirect()">
<center>
  <p>If you havent been automatically redirected please click   
  <a href="' . $targetURL . '">here...</a>
   </p>
</center>
</body>
</html>
		';
}

?>
