<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// public_vote_lib.php
//
// This has a public voting system in it...
// 
// Depends: images/vote
//			file_lib
//
// FIX:		Need to harden inserts against injection attack
//
// Rev:		052607 File Originated
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// --------------------------------------------------------------------------------------------------------------------------------------

// allows unlimited voting
$slutMode	=	0;

function PublicVoteShowUI($electionName,$fileName,$templateFilePath="templates/")
{
$debug	=	0;

if($debug) print "PublicVoteShowUI($electionName,$fileName)<br>";

$fn	=	array();

$score	=	PublicVoteGetScore($electionName,$fileName);
$count	=	PublicVoteGetCount($electionName,$fileName);

if($debug) print "score: $score<br>";
if($debug) print "count: $count	<br>";

// set all to empty
for($i=0;$i<5;$i++)	$fn[$i]	=	"one_empty_star.gif";

// fill based on the grade
for($i=0;$i<$score;$i++)	$fn[$i]	=	"one_full_star.gif";

// has this IP voted on this file ?
if(UserEligibleToVote($electionName,$fileName))
	{
	// show the voting UI
	print LoadVoteUITemplate($electionName,$fileName,"vote_UI.html",$templateFilePath);
	}
else
	{
	// show a result glyph
	print LoadVoteUITemplate($electionName,$fileName,"vote_result.html",$templateFilePath);
	}
}


function LoadVoteUITemplate($electionName,$fileName,$templateFileName,$templateFilePath)
{
global $gBaseMountPoint;
$debug	=	0;

$fnap		=	"$templateFilePath$templateFileName";

$template	=	FileRead($fnap);

if($template=="")
	print "Template File Not Found: $fnap<br>";
else
	if($debug) print "Template File Loaded<br>";

if($debug) print "fnap: $fnap<br>";


if($debug) print "LoadVoteUITemplate($electionName,$fileName,$templateFileName,$templateFilePath)<br>";

$fn	=	array();

$score	=	PublicVoteGetScore($electionName,$fileName);
$count	=	PublicVoteGetCount($electionName,$fileName);

if($debug) print "score: $score<br>";
if($debug) print "count: $count<br>";

// set all to empty
for($i=0;$i<5;$i++)	$fn[$i]	=	"one_empty_star.gif";

// fill based on the score
for($i=0;$i<$score;$i++)	$fn[$i]	=	"one_full_star.gif";

// fix up the template

$template	=	str_replace("_NUMBEROFVOTES_",$count,$template);
$template	=	str_replace("_FILENAME_",$fileName,$template);
$template	=	str_replace("_ELECTIONNAME_",$electionName,$template);
$template	=	str_replace("_FILENAME0_",$fn[0],$template);
$template	=	str_replace("_FILENAME1_",$fn[1],$template);
$template	=	str_replace("_FILENAME2_",$fn[2],$template);
$template	=	str_replace("_FILENAME3_",$fn[3],$template);
$template	=	str_replace("_FILENAME4_",$fn[4],$template);

return $template;
}




function PublicVoteGetNumberOfElections()
{
return GetNumberOfRows("SELECT * FROM APPDEV_PublicVoteElections");
}

function PublicVoteGetElectionName($index)
{
return GetFieldByIndex("APPDEV_PublicVoteElections","ElectionName",$index);
}

function PublicVoteGetElectionType($electionName)
{
return GetFieldByComparison("APPDEV_PublicVoteElections","Type","ElectionName",$electionName);
}

function PublicVoteGetScore($electionName,$fileName)
{
return GetFieldByComparison("APPDEV_PublicVoteTabulation","Score","FileName",$fileName);
}

function PublicVoteGetCount($electionName,$fileName)
{
return GetFieldByComparison("APPDEV_PublicVoteTabulation","Count","FileName",$fileName);
}

// does the deed in a single call
function PublicVoteHandler($mode,$electionName,$fileName,$score)
{
$debug	=	0;
$rv		=	0;

if($debug) print "PublicVoteHandler($mode,$electionName,$fileName,$score)<br>";

if($mode=="CastVote")
	{
	if($debug) print "Casting Vote...<br>";
	
	// try to cast a vote...
	$rv	=	PublicVote($electionName,$fileName,$score);

	if($debug) print "rv: $rv<br>";
	}
	
return $rv;
}



function UserEligibleToVote($electionName,$fileName)
{
$debug	=	0;

global $slutMode;
global $REMOTE_ADDR;

if($debug) print "UserEligibleToVote($electionName,$fileName)<br>";

if($slutMode)
	{
	$ipAddress 		= 	rand(1000,10000000);
	}
else
	{
	$ipAddress 		= 	gethostbyname($REMOTE_ADDR);
	}

$hostName 		= 	gethostbyaddr($_SERVER['REMOTE_ADDR']);

if($debug) print "ipAddress: $ipAddress<br>";
if($debug) print "hostName: $hostName<br>";

$electionType	=	PublicVoteGetElectionType($electionName);

if($debug) print "electionType: $electionType<br>";

// this allows the user to vote on all pieces, one vote per piece
if($electionType=="VotePerPiece")
	{
	$voterScreeningQuery	=	"SELECT * FROM APPDEV_PublicVote 
								WHERE FileName='$fileName' 
								AND ElectionName='$electionName' 
								AND IP='$ipAddress' 
								AND Host='$hostName'";	
	}
// this allows the user to vote once on one piece only
else if($electionType=="VotePerIP")
	{
	$voterScreeningQuery	=	"SELECT * FROM APPDEV_PublicVote 
								WHERE ElectionName='$electionName' 
								AND IP='$ipAddress' 
								AND Host='$hostName'";	
	}
else
	{
	$statusMessage	=	"Internal Error: Unknown Election Type...";
	return 0;
	}


if($debug) print "electionType: $electionType<br>";
if($debug) print "voterScreeningQuery: $voterScreeningQuery<br>";


if(GetNumberOfRows($voterScreeningQuery))
	{
	// this IP has already voted
	if($debug) print "$ipAddress - $hostName <br>NOT ELIGIBLE<br>";

	return	0;
	}
else
	{
	if($debug) print "$ipAddress - $hostName <br>ELIGIBLE<br>";

	return 1;
	}
}

function PublicVote($electionName,$fileName,$score=1)
{
$debug = 0;

global $REMOTE_ADDR;
global $slutMode;

if($debug) print "PublicVote($electionName,$fileName,$score)<br>";

if($slutMode)
	{
	$ipAddress 		= 	rand(1000,10000000);
	}
else
	{
	$ipAddress 		= 	gethostbyname($REMOTE_ADDR);
	}

$hostName 		= 	gethostbyaddr($_SERVER['REMOTE_ADDR']);

if($debug) print "ipAddress: $ipAddress<br>";
if($debug) print "hostName: $hostName<br>";

if(UserEligibleToVote($electionName,$fileName))
	{
	if($debug) print "$ipAddress - $hostName has cast a vote...<br>";
	// record the vote
/*
	$vote	=	"INSERT INTO APPDEV_PublicVote
				 (Host, IP, ElectionName, FileName, Score)
				 VALUES
				 ('$hostName','$ipAddress','$electionName','$fileName','$score')";
*/
	OpenDatabase();
	
	$vote 		= 	sprintf("INSERT INTO APPDEV_PublicVote
				 			(Host, IP, ElectionName, FileName, Score)
				 			VALUES
						 	( '%s' , '%s' , '%s' , '%s' , '%s' )",
						mysql_real_escape_string($hostName),	
						mysql_real_escape_string($ipAddress),	
						mysql_real_escape_string($electionName),
						mysql_real_escape_string($fileName),
						mysql_real_escape_string($score)
						);


	if($debug) print "vote: $vote<br>";

	
	// record the vote
	ShowDatabaseError(QueryDatabase($vote));	

	// retabulate the result
	$count	=	mysql_numrows(QueryDatabase("SELECT * FROM APPDEV_PublicVote WHERE FileName='$fileName'"));

	if($debug) print "count: $count<br>";

	// sum the grades
	$rs	=	QueryDatabase("SELECT SUM(Score) FROM APPDEV_PublicVote WHERE FileName='$fileName'");

	$row = mysql_fetch_array($rs);

	$totalScore		=	$row['SUM(Score)'];

	$averageScore	=	ceil($totalScore/$count);

	if($debug) print "totalScore: $totalScore<br>";
	if($debug) print "averageScore: $averageScore<br>";
	
	// update the tabulated table
	
	// does the line exist ? 
	if(mysql_numrows(QueryDatabase("SELECT * FROM APPDEV_PublicVoteTabulation WHERE FileName='$fileName' AND ElectionName='$electionName'")))
		{
		// do update
		/*
		$update	=	"UPDATE APPDEV_PublicVoteTabulation
					 SET 
					 Count='$count',
					 Score='$averageScore'
					 WHERE 
					 FileName='$fileName'
					 ";
		*/
		
		$update 		= 	sprintf("UPDATE APPDEV_PublicVoteTabulation
									 SET 
									 Count='%s',
									 Score='%s'
									 WHERE 
									 FileName='%s'",
						mysql_real_escape_string($count),	
						mysql_real_escape_string($averageScore),	
						mysql_real_escape_string($fileName)
						);


		if($debug) print "update: $update<br>";

		// update the tabulation
		ShowDatabaseError(QueryDatabase($update));	
		}
	else
		{
		/*
		$insert	=	"INSERT INTO APPDEV_PublicVoteTabulation
					(ElectionName, FileName,Count, Score)
					VALUES
					('$electionName','$fileName','$count','$averageScore')
					";	

		*/
		$insert 		= 	sprintf("INSERT INTO APPDEV_PublicVoteTabulation
								(ElectionName, FileName,Count, Score)
								VALUES
						 	( '%s' , '%s' , '%s' , '%s' )",
						mysql_real_escape_string($electionName),	
						mysql_real_escape_string($fileName),	
						mysql_real_escape_string($count),
						mysql_real_escape_string($averageScore)
						);

		if($debug) print "insert: $insert<br>";

		// update the tabulation
		ShowDatabaseError(QueryDatabase($insert));	
		}
	
	CloseDatabase();
	}

return 1;
}

?>