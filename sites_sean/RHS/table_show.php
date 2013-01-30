<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// table_show.php
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------
include '../local_info.php';	
include 'db_lib.php';
include 'cl_lib.php';
include 'admin_lib.php';
include 'question_lib.php';
include 'blog_lib.php';
include 'html_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'file_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	

$backLink				=	"table_home.php?SystemUserID=$systemUserID&SystemPassword=$systemPassword";

$tableName		=	GetPassedValue("TableName");
$delimited		=	GetPassedValue("Delimited");
$title			=	str_replace("APPDEV_","",$tableName);

if($debug) print "tableName: $tableName<br>";
if($debug) print "delimited: $delimited<br>";
?>

<html>
<head>
<title><?php print $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW">


<link href="admin.css" rel="stylesheet" type="text/css">
<link href="app_dev_II.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle" class="admin18Bold">Table Data 
      Viewer </td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="admin_help.php#ShowTable" target="_blank" class="galleryUILink">Help</a></td>
        <td width="33%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top">
	
	
	
	
	
	
<?php

print '<br><br><span class="cts14Bold">' . $tableName . '</span><br><br>';

// here are some table keys

OpenDatabase();

$query="SELECT * FROM $tableName";

$result	=	QueryDatabase($query);

if (!$result) 
	{
	$message		=	"<br>MySQL Error...<br>Message: " .	mysql_error() . "<br>Query: $query";
	
	print $message;
	}


$fields	=	mysql_num_fields($result);

if($delimited)
	print '<br><table  border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#efefef"><tr>';
else
	print '<br><table class="cts12Regular"  border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#efefef"><tr>';

for ($i=0; $i < mysql_num_fields($result); $i++) //Table Header
	{ 
	$val	=	mysql_field_name($result, $i);
	
	if($delimited)
		$val	.=	" |";	
	
	print "<th>" . $val . "</th>"; 
	}
	
print "</tr>\n";

while ($row = mysql_fetch_row($result)) 	
	{ 
	//Table body
	print "<tr>";

	for ($f=0; $f < $fields; $f++) 
		{
		if(strlen($row[$f])==0)
			$val	=	"&nbsp";
		else
			$val	=	$row[$f];

		if($delimited)
			$val	.=	" |";	
					
   		print "<td>" . $val . "</td>"; 
		}

	print "</tr>\n";
	}

print "</table></p>";

print "<br>";
print "<br>";

CloseDatabase();
?>

	
	
	
	
	</td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td height="40" align="center" valign="top" bgcolor="#EEEEEE">&nbsp;</td>
  </tr>
  <tr> 
    <td align="left" valign="top"> 
      <?php
	PrintAdminFooter();
	?>
    </td>
  </tr>
</table>
</body>
</html>











