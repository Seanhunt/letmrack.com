<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include '../local_info.php';	
include '../RHS/db_lib.php';
include '../RHS/cl_lib.php';
include '../RHS/admin_lib.php';
include '../RHS/question_lib.php';
include '../RHS/file_lib.php';
include '../RHS/blog_lib.php';
include '../RHS/html_lib.php';
include '../RHS/pw_parms.php';	
include '../RHS/pw_lib.php';
include '../RHS/status_message.php';	
include '../RHS/select_controls.php';	
include '../RHS/download_lib.php';	
include '../RHS/public_vote_lib.php';

// tally the incoming vote
$rv		=	PublicVoteHandler(GetPassedValue("Mode"),GetPassedValue("ElectionName"),GetPassedValue("FileName"),GetPassedValue("Score"));

// get the inputs
$electionName	=	GetPassedValue("ElectionName");
$fileName		=	GetPassedValue("FileName");

PublicVoteShowUI($electionName,$fileName,"../templates/");
?>



