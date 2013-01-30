<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// question_lib.php
//
// for user response and warn pages 
//
// Rev:		011507 FILE ORIGINATED 
//
// Depends: cl_lib.php
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

$quTarget	=	GetPassedValue("quTarget");
$quCaller	=	GetPassedValue("quCaller");
$quMessage	=	GetPassedValue("quMessage");
$quCallOut	=	GetPassedValue("quCallOut");

$debug	=	0;

if($debug) print "quTarget: $quTarget<br>";
if($debug) print "quCaller: $quCaller<br>";
if($debug) print "quMessage: $quMessage<br>";
if($debug) print "quCallOut: $quCallOut<br>";


// this will return an arg string for the question_yn page
function quBuildArgs($target,$caller,$message,$callOut)
{
//$message	=	urlencode($message);
//$callOut	=	urlencode($callOut);

return "quTarget=$target&quCaller=$caller&quMessage=$message&quCallOut=$callOut";
}


?>

