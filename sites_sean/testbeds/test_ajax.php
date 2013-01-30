<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// test.php
//
//
//
// Author: 	Ralph Cooksey-Talbott
// Contact: cooksey@cookseytalbottstudio.com
// (c) 2007 Cooksey-Talbott Studio, All Rights Reserved. 
// ---------------------------------------------------------------------------------------------------------------------------------------

include 'local_info.php';	
include 'RHS/db_lib.php';
include 'RHS/cl_lib.php';
include 'RHS/admin_lib.php';
include 'RHS/question_lib.php';
include 'RHS/file_lib.php';
include 'RHS/blog_lib.php';
include 'RHS/html_lib.php';
include 'RHS/pw_parms.php';	
include 'RHS/pw_lib.php';
include 'RHS/status_message.php';	
include 'RHS/select_controls.php';	
include 'RHS/download_lib.php';	
include 'RHS/public_vote_lib.php';
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Voting System Testbed</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="RHS/public_vote.css" rel="stylesheet" type="text/css">

<!-- Load the Ajax Lib -->
<script src="Ajax/ajax_lib.js"></script>

<script type="text/javascript">

// the scheduler call
function DataLoader(recordNumber)
{

// global from lib
xmlHttp	=	GetXmlHttpObject();

if (xmlHttp==null)
	{
	alert ("Browser does not support HTTP Request")
	return
	}

// set up the URI to fetch the data
var url="ajax_target.php?TableName=APPDEV_PublicVoteTabulation&Index=" + recordNumber +"&Field0=FileName&Field1=Count&Field2=Score";

//alert(url);

// set the state change callback
// this is invoked by the controls onchange flag
xmlHttp.onreadystatechange	=	controlStateChanged; 

// set up the request
xmlHttp.open("GET",url,true);

// send
xmlHttp.send(null);
}

function HandleVote(score)
{
// call the voting endpoint with the vote
// getback the results HTML
// set the element by ID to the results HTML

var targetURI = "Ajax/public_vote_endpoint.php?Mode=CastVote&Score=" + score + "&FileName=$fileName&ElectionName=$electionName";

alert(targetURI);


}

// the callback fn
function controlStateChanged() 
{ 
var debug	=	0;


if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 	{ 
	//alert("Completion Callback");
	
	var responseString	=	xmlHttp.responseText;
	
	if(debug)	alert(responseString);

	// the end point returns an NVP string seperated by ampersands 
	// break into an array of NVP's ready for consumption
	var nameValuePairs	=	responseString.split("&");

	if(debug)	alert("Count: " + nameValuePairs.length);
	
	for(i=0;i<nameValuePairs.length;i++)
		{
		var both	=	nameValuePairs[i].split("=");
		var name	=	both[0];
		var value	=	both[1];

		// replace the contents of any id's in the document that match the names with
		// the values from the pairs.
	 	document.getElementById(name).innerHTML	=	value; 
		}
 	} 
}

</script>


</head>
<body>

<hr>
<p>Vote For FOO</p>
<?php
PublicVoteShowUI("Test001","foo.bar");
?>
<p>&nbsp;</p>
<hr>
<p>Vote For BAR</p>
<?php
PublicVoteShowUI("Test001","bar.foo");
?>
<p>&nbsp;</p>
<hr>
<p>&nbsp;</p>

<!-- Public Vote UI -->
<table width="220" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="100">
<table width="100" border="0" cellspacing="0" cellpadding="0" id="ratings_bar">
        <tr> 
          <td>Rate Me!</td>
        </tr>
        <tr> 
          <td><table border="0" cellspacing="0" cellpadding="0" id="ratings_bar">
              <tr> 
                <td width="10" height="10" align="left" valign="top">
				<a  href="#" onClick="' . JavaScriptWindowOpen("public_vote_popup.php?Mode=CastVote&Score=1&FileName=$fileName&ElectionName=$electionName","Vote Results",200,140) . '"></a></td>
                <td align="left" valign="top">
				<a  href="#" onClick="' . JavaScriptWindowOpen("public_vote_popup.php?Mode=CastVote&Score=2&FileName=$fileName&ElectionName=$electionName","Vote Results",200,140) . '"></a></td>
                <td align="left" valign="top">
				<a  href="#" onClick="' . JavaScriptWindowOpen("public_vote_popup.php?Mode=CastVote&Score=3&FileName=$fileName&ElectionName=$electionName","Vote Results",200,140) . '"></a></td>
                <td align="left" valign="top">
				<a  href="#" onClick="' . JavaScriptWindowOpen("public_vote_popup.php?Mode=CastVote&Score=4&FileName=$fileName&ElectionName=$electionName","Vote Results",200,140) . '"></a></td>
                <td align="left" valign="top">
				<a  href="#" onClick="' . JavaScriptWindowOpen("public_vote_popup.php?Mode=CastVote&Score=5&FileName=$fileName&ElectionName=$electionName","Vote Results",200,140) . '"></a></td>
              </tr>
            </table></td>
        </tr>
      </table>
    </td>
    <td width="20">&nbsp;</td>
    <td width="100"><table width="100" border="0" cellspacing="0" cellpadding="0" id="ratings_bar">
        <tr> 
          <td>Current Score...</td>
        </tr>
        <tr> 
          <td><table border="0" cellspacing="0" cellpadding="0" id="ratings_bar">
              <tr> 
                <td width="10" height="10" align="left" valign="top">
				<img src="images/vote/' . $fn[0] . '" width="18" height="18"></td>
                <td align="left" valign="top">
				<img src="images/vote/' . $fn[1] . '" width="18" height="18"></td>
                <td align="left" valign="top">
				<img src="images/vote/' . $fn[2] . '" width="18" height="18"></td>
                <td align="left" valign="top">
				<img src="images/vote/' . $fn[3] . '" width="18" height="18"></td>
                <td align="left" valign="top">
				<img src="images/vote/' . $fn[4] . '" width="18" height="18"></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<!-- Public Vote UI -->



</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>