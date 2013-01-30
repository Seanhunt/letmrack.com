//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

function HandleVote(score,fileName,electionName)
{
// call the voting endpoint with the vote
// getback the results HTML
// set the element by ID to the results HTML

// global from lib
xmlHttp	=	GetXmlHttpObject();

if (xmlHttp==null)
	{
	alert ("Browser does not support HTTP Request")
	return
	}

var url = "RHS/public_vote_endpoint.php?Mode=CastVote&Score=" + score + "&FileName=" + fileName + "&ElectionName=" + electionName;

// alert(url);

// set the state change callback
// this is invoked by the controls onchange flag
xmlHttp.onreadystatechange	=	refreshVoteUI; 

// set up the request
xmlHttp.open("GET",url,true);

// send
xmlHttp.send(null);
}

// the callback fn
function refreshVoteUI() 
{ 
var debug	=	0;


if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 	{ 
	//alert("Completion Callback");
	document.getElementById("voteUI").innerHTML	=	xmlHttp.responseText; 
 	} 
}
