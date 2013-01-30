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
<script src="RHS/ajax_lib.js"></script>
<!-- Load the public voting Lib -->
<script src="RHS/public_vote_lib.js"></script>

</head>
<body>

<hr>
<p>Vote For FOO</p>
<?php
PublicVoteShowUI("Test001","foo.bar");
?>
<hr>
</body>
</html>
<?php
DisplayStatusMessage($statusMessage);
?>