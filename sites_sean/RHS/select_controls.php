<?php
// --------------------------------------------------------------------------------------------------------------------------------------
// select_controls.php
//
// This has various select controls that will default properly
//
//	011107 Added defaulting name arg to - DisplayStateSelector($default,$name="State")
//	121708 Addes defaulting class arg to several of the selector functions
//
// Author: 	Ralph Cooksey-Talbott
// Contact: cooksey@cookseytalbottstudio.com
// (c) 2007 Cooksey-Talbott Studio, All Rights Reserved. 
// --------------------------------------------------------------------------------------------------------------------------------------


function DisplayDurationSelector($defaultHours,$defaultMinutes)
{
$hours		=	array();

$minutes	=	array("00","05","10","15","20","25","30","35","40","45","50","55",);

for($i=1;$i<13;$i++)
	{
	if($i<10)		
		{
		$hours[]	=	"0$i";
		}
	else
		{
		$hours[]	=	"$i";
		}
	}

if($defaultHours=="")
	{
	$defaultHours	=	"01";
	$defaultMinutes	=	"00";
	}
	
DisplaySelector("blDurationHour",$hours,$hours,$defaultHours);
print ":";
DisplaySelector("blDurationMinute",$minutes,$minutes,$defaultMinutes);
}


function DisplayTimeSelector($defaultHours,$defaultMinutes,$defaultAmPm)
{
$hours		=	array();
$duration	=	array();

$minutes	=	array("00","05","10","15","20","25","30","35","40","45","50","55",);

for($i=1;$i<13;$i++)
	{
	if($i<10)		
		{
		$hours[]	=	"0$i";
		$duration[]	=	"0$i";
		}
	else
		{
		$duration[]	=	"$i";
		$hours[]	=	"$i";
		}
	}
	
$amPm	=	array("AM","PM");	

if($defaultHours=="")
	{
	$defaultHours	=	"09";
	$defaultMinutes	=	"00";
	$defaultAmPm	=	"AM";
	}


	
DisplaySelector("blEventHour",$hours,$hours,$defaultHours);
print ":";
DisplaySelector("blEventMinute",$minutes,$minutes,$defaultMinutes);
print "-";
DisplaySelector("blEventAmPm",$amPm,$amPm,$defaultAmPm);
}

function DisplayRadioButtonArray($name,$default,$valuesArray,$labelsArray)
{
$numberOfButtons	=	count($valuesArray);
/*
<input name="blItemType" type="radio" value="TEXT" checked>Plain Text
<input type="radio" name="blItemType" value="HTML">HTML 
<input type="radio" name="blItemType" value="FUBAR">Fubar 
...
*/

for($i=0;$i<$numberOfButtons;$i++)
	{
	if($valuesArray[$i]==$default)
		$checked	=	"checked";
	else
		$checked	=	"";
						
	if($i==0)
		{
		print 	'<input name="' . 
				$name . 
				'" type="radio" value="' . 
				$valuesArray[$i] . 
				'" ' . 
				$checked . 
				">" . 
				$labelsArray[$i];
		}
	else
		{
		print	'<input type="radio" name="' . 
				$name . 
				'" value="' . 
				$valuesArray[$i] . 
				'"' .
				$checked . 
				'>' . 
				$labelsArray[$i];
		}
	}

}

// --------------------------------------------------------------------------------------------------------------------------------------
// function DisplayTitleSelector($default)
// Displays a Mr, Ms Miss type of selector
// --------------------------------------------------------------------------------------------------------------------------------------
/*
Here is the title list from hell that I skodged
		<option value="Admiral">Admiral</option>
		<option value="Ambass. &amp; Mrs.">Ambass. &amp; Mrs.</option>
		<option value="Ambassador">Ambassador</option>
		<option value="BGEN">BGEN</option>

		<option value="Bishop">Bishop</option>
		<option value="Bishop and Mr.">Bishop and Mr.</option>
		<option value="Bishop and Mrs.">Bishop and Mrs.</option>
		<option value="Brother">Brother</option>
		<option value="c/o">c/o</option>
		<option value="Cantor">Cantor</option>

		<option value="Cantor and Mr.">Cantor and Mr.</option>
		<option value="Cantor and Mrs.">Cantor and Mrs.</option>
		<option value="Capt.">Capt.</option>
		<option value="Capt. and Mrs.">Capt. and Mrs.</option>
		<option value="CDR">CDR</option>
		<option value="Cdr.">Cdr.</option>

		<option value="Chap.">Chap.</option>
		<option value="Chaplain &amp; Mrs.">Chaplain &amp; Mrs.</option>
		<option value="Cmdr.">Cmdr.</option>
		<option value="CMSGT">CMSGT</option>
		<option value="Col.">Col.</option>

		<option value="Col. and Mrs.">Col. and Mrs.</option>
		<option value="Commissioner">Commissioner</option>
		<option value="CWO4">CWO4</option>
		<option value="Deacon">Deacon</option>
		<option value="Dr.">Dr.</option>
		<option value="Dr. &amp; Rev.">Dr. &amp; Rev.</option>

		<option value="Dr. and Dr.">Dr. and Dr.</option>
		<option value="Dr. and Mr.">Dr. and Mr.</option>
		<option value="Dr. and Mrs.">Dr. and Mrs.</option>
		<option value="Dr. and Ms.">Dr. and Ms.</option>
		<option value="Dr. and Rev.">Dr. and Rev.</option>
		<option value="Drs.">Drs.</option>

		<option value="Elder">Elder</option>
		<option value="Estate of">Estate of</option>
		<option value="Father">Father</option>
		<option value="Fr.">Fr.</option>
		<option value="General">General</option>
		<option value="H.R.H.">H.R.H.</option>

		<option value="Hon.">Hon.</option>
		<option value="Judge">Judge</option>
		<option value="Lt.">Lt.</option>
		<option value="Lt. Cmdr.">Lt. Cmdr.</option>
		<option value="Lt. Col.">Lt. Col.</option>
		<option value="Major">Major</option>

		<option value="Messrs.">Messrs.</option>
		<option value="Miss">Miss</option>
		<option value="Monsignor">Monsignor</option>
		<option value="Mother">Mother</option>
		<option value="Mr.">Mr.</option>
		<option value="Mr. and Mrs.">Mr. and Mrs.</option>

		<option value="Mr. and Ms.">Mr. and Ms.</option>
		<option value="Mrs.">Mrs.</option>
		<option value="Ms.">Ms.</option>
		<option value="MSG.">MSG.</option>
		<option value="Mss.">Mss.</option>
		<option value="Prof.">Prof.</option>

		<option value="Prof. and Mr.">Prof. and Mr.</option>
		<option value="Prof. and Mrs.">Prof. and Mrs.</option>
		<option value="Prof. and Ms.">Prof. and Ms.</option>
		<option value="Rabbi">Rabbi</option>
		<option value="Rabbi and Mr.">Rabbi and Mr.</option>
		<option value="Rabbi and Mrs.">Rabbi and Mrs.</option>

		<option value="Rep.">Rep.</option>
		<option value="Rev.">Rev.</option>
		<option value="Rev. and Mr.">Rev. and Mr.</option>
		<option value="Rev. and Mrs.">Rev. and Mrs.</option>
		<option value="Rev. Dr.">Rev. Dr.</option>
		<option value="Rev. Dr. &amp; Mrs.">Rev. Dr. &amp; Mrs.</option>

		<option value="Rt. Rev.">Rt. Rev.</option>
		<option value="Rt. Rev. &amp; Mr.">Rt. Rev. &amp; Mr.</option>
		<option value="Rt. Rev. &amp; Mrs.">Rt. Rev. &amp; Mrs.</option>
		<option value="Senator">Senator</option>
		<option value="Senator &amp; Mrs.">Senator &amp; Mrs.</option>

		<option value="Sgt.">Sgt.</option>
		<option value="Sister">Sister</option>
		<option value="Sr.">Sr.</option>
		<option value="The">The</option>
		<option value="The Hon.">The Hon.</option>
		<option value="The Hon. &amp; Mr.">The Hon. &amp; Mr.</option>

		<option value="The Hon. &amp; Mrs.">The Hon. &amp; Mrs.</option>
		<option value="The Rev. Deacon">The Rev. Deacon</option>
*/
function DisplayTitleSelector($default,$class="")
{
$debug	=	0;

$labelArray	=	array();
$labelArray[]	=	"Pick One";	
$labelArray[]	=	"Mr";	
$labelArray[]	=	"Ms";	
$labelArray[]	=	"Mrs";	
$labelArray[]	=	"Miss";	
$labelArray[]	=	"Dr";	
$labelArray[]	=	"Rev";	

$valueArray	=	array();
$valueArray[]	=	"";	
$valueArray[]	=	"Mr";	
$valueArray[]	=	"Ms";	
$valueArray[]	=	"Mrs";	
$valueArray[]	=	"Miss";	
$valueArray[]	=	"Dr";	
$valueArray[]	=	"Rev";	

if($debug) print "DisplayTitleSelector($default)<br>";

DisplaySelector("Title",$labelArray,$valueArray,$default,"",$class);
}






function DisplayBirthYearSelector($default)
{
$debug	=	0;
$range	=	100;

if($debug) print "DisplayBirthYearSelector($default)<br>";

$yearArray 	= 	array();
$year		=	date("Y");

if($debug) print "year: $year<br>";

//$year		=	$year	- $range;

//if($debug) print "year: $year<br>";

$yearArray[0]	=	"----";

for($i=1;$i<$range;$i++)
	{
	$yearArray[$i]	=	$year	-	($i-1);	
	
	if($debug) print "yearArray[$i]: $yearArray[$i]<br>";
	}


print '<select name="Year">';

for($i=0;$i<sizeof($yearArray);$i++)
	{
	// if we are returning to the form set up the state they picked as the default
	if(strcmp($default,$yearArray[$i])==0)
		{
		print '<option value="' . $yearArray[$i] .    '" selected>' . $yearArray[$i] . '</option>';
		}
	else
		print '<option value="' . $yearArray[$i] .    '">' . $yearArray[$i] . '</option>';
	}


print '</select>';
}

// -------------------------------------------------------------------------------------------------------------
// function DisplayDateSelector()
//
// displays a 3 combo box date selector defaulted to todays date, Day, Month and Year
// -------------------------------------------------------------------------------------------------------------

// expects yyyy-mm-dd
function DisplayDateSelector($default="")
{
$debug	=	0;

if($debug) print "<hr>DisplayDateSelector($default)<br>";

if($default=="")
	{
	$m		=	date("m");	
	$d		=	date("d");	
	$y		=	date("Y");	

	if($debug) print "m:  $m<br>";
	if($debug) print "d:  $d<br>";
	if($debug) print "y:  $y<br>";
	}
else
	{
	if($debug) print count($dateArray) . "<br>";

	$dateArray	=	explode("-",$default);
	$m			=	$dateArray[1];	
	$d			=	$dateArray[2];	
	$y			=	$dateArray[0];	

	if($debug) print "m:  $m<br>";
	if($debug) print "d:  $d<br>";
	if($debug) print "y:  $y<br>";
	}

if($debug) print "<hr>";

DisplayMonthSelector($m);
DisplayDaySelector($d);
DisplayYearSelector($y,10);
//function DisplayFutureYearSelector($default,$range)
}

function DisplayFutureDateSelector($default="",$range="10")
{
$debug	=	0;

if($debug) print "<hr>DisplayFutureDateSelector($default)<br>";

if($default=="")
	{
	$m		=	date("m");	
	$d		=	date("d");	
	$y		=	date("Y");	

	if($debug) print "m:  $m<br>";
	if($debug) print "d:  $d<br>";
	if($debug) print "y:  $y<br>";
	}
else
	{

	$dateArray	=	explode("-",$default);

	if($debug) print count($dateArray) . "<br>";

	$m			=	$dateArray[1];	
	$d			=	$dateArray[2];	
	$y			=	$dateArray[0];	

	if($debug) print "m:  $m<br>";
	if($debug) print "d:  $d<br>";
	if($debug) print "y:  $y<br>";
	}

if($debug) print "<hr>";

DisplayMonthSelector($m);
DisplayDaySelector($d);
DisplayFutureYearSelector($y,$range);
}



function DisplayYearSelector($default,$range)
{
$debug	=	0;

if($debug) print "<HR>DisplayYearSelector($default,$range)<br>";

$yearArray 	= 	array();
$year		=	date("Y");

if($debug) print "year: $year<br>";

$year		=	$year	- ($range/2);

if($debug) print "year: $year<br>";

for($i=0;$i<$range;$i++)
	{
	$yearArray[$i]	=	$year	+	$i;	
	
	if($debug) print "yearArray[$i]: $yearArray[$i]<br>";
	}

if($debug) print "<hr>";


print '<select name="Year">';

for($i=0;$i<sizeof($yearArray);$i++)
	{
	// if we are returning to the form set up the state they picked as the default
	if(strcmp($default,$yearArray[$i])==0)
		{
		print '<option value="' . $yearArray[$i] .    '" selected>' . $yearArray[$i] . '</option>';
		}
	else
		print '<option value="' . $yearArray[$i] .    '">' . $yearArray[$i] . '</option>';
	}


print '</select>';
}

function DisplayFutureYearSelector($default,$range)
{
$debug	=	0;

if($debug) print "<HR>DisplayYearSelector($default,$range)<br>";

$yearArray 	= 	array();
$year		=	date("Y");

if($debug) print "year: $year<br>";

for($i=0;$i<$range;$i++)
	{
	$yearArray[]	=	$year	+	$i;	
	
	if($debug) print "yearArray[$i]: $yearArray[$i]<br>";
	}

if($debug) print "<hr>";


print "\n\n" . '<select name="Year">' . "\n";

for($i=0;$i<sizeof($yearArray);$i++)
	{
	// if we are returning to the form set up the state they picked as the default
	if(strcmp($default,$yearArray[$i])==0)
		{
		print '<option value="' . $yearArray[$i] .    '" selected>' . $yearArray[$i] . "</option>\n";
		}
	else
		print '<option value="' . $yearArray[$i] .    '">' . $yearArray[$i] . "</option>\n";
	}


print "</select>\n";
}


function DisplayDaySelector($default)
{
$debug	=	0;

if($debug) print "DisplayDaySelector($default)<br>";

$dayArray 	= 	array();

$dayArray[0]	=	"--";

for($i=1;$i<=31;$i++)
	{
	$dayArray[$i]	=	$i;	
	
	if(strlen($dayArray[$i])==1)
		$dayArray[$i]	=	"0" . $dayArray[$i];
	
	if($debug) print "dayArray[$i]: $dayArray[$i]<br>";
	}


print "\n\n" . '<select name="Day">' . "\n";

for($i=0;$i<sizeof($dayArray);$i++)
	{
	// if we are returning to the form set up the state they picked as the default
	if(strcmp($default,$dayArray[$i])==0)
		{
		print '<option value="' . $dayArray[$i] .    '" selected>' . $dayArray[$i] . "</option>\n";
		}
	else
		print '<option value="' . $dayArray[$i] .    '">' . $dayArray[$i] . "</option>\n";
	}


print "</select>\n";
}

function DisplayMonthSelector($default)
{
$debug	=	0;

if($debug) print "DisplayMonthSelector($default)<br>";

$monthArray 	= 	array();

$monthArray[0]	=	"--";

for($i=1;$i<=12;$i++)
	{
	$monthArray[$i]	=	$i;	

	if(strlen($monthArray[$i])==1)
		$monthArray[$i]	=	"0" . $monthArray[$i];
	
	if($debug) print "monthArray[$i]: $monthArray[$i]<br>";
	}

print "\n\n" . '<select name="Month">' . "\n";

for($i=0;$i<sizeof($monthArray);$i++)
	{
	// if we are returning to the form set up the state they picked as the default
	if(strcmp($default,$monthArray[$i])==0)
		{
		print '<option value="' . $monthArray[$i] .    '" selected>' . $monthArray[$i] . "</option>\n";
		}
	else
		print '<option value="' . $monthArray[$i] .    '">' . $monthArray[$i] . "</option>\n";
	}


print "</select>\n\n";
}



function DisplayYesNoSelector($default,$name)
{

print "\n\n<select name=$name id=select>\n";
                      
// --------------------------------------------------------------------------------------------------------------------------------------
// print out the states from an array
// --------------------------------------------------------------------------------------------------------------------------------------

$yesNoArray		=	array("Yes", "No");
$valueArray		=	array("Y", "N");
$i				=	0;
$found			=	0;

for($i=0;$i<sizeof($yesNoArray);$i++)
	{
	// if we are returning to the form set up the state they picked as the default
	if(strcmp($default,$valueArray[$i])==0)
		{
		$found	=	1;
		print '<option value="' . $valueArray[$i] .    '" selected>' . $yesNoArray[$i] . '</option>';
		}

	// This is the default setting of "--"
	else if(($i==count($yesNoArray)-1) && ($found==0))
		print '<option value="' . $valueArray[$i] .    '" selected>' . $yesNoArray[$i] . '</option>';
	// if the state is not found and its not the default print a non-selected state name
	else 
		print '<option value="' . $valueArray[$i] .    '">' . $yesNoArray[$i] . '</option>';
	}

// --------------------------------------------------------------------------------------------------------------------------------------
// End state printing
// --------------------------------------------------------------------------------------------------------------------------------------

print '</select>';
}


// --------------------------------------------------------------------------------------------------------------------------------------
// function DisplayStateSelector($default)
//
// Displays a combo box filled with state mnenomics 
// --------------------------------------------------------------------------------------------------------------------------------------


function DisplayStateSelector($default,$name="State",$class="")
{

if($class=="")
	print '<select name=' . $name . ' id="select">';
else
	print '<select class="' . $class . '" name=' . $name . ' id="select">';
                  
// --------------------------------------------------------------------------------------------------------------------------------------
// print out the states from an array
// --------------------------------------------------------------------------------------------------------------------------------------

$stateArray		=	array("AL", "AK", "AS", "AZ", "AR", "CA", "CO", "CT", "DE", "FL", "GA", "GU", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "PR", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "VI", "WA", "WV", "WI", "WY","--");
$i				=	0;
$found			=	0;

for($i=0;$i<sizeof($stateArray);$i++)
	{
	// if we are returning to the form set up the state they picked as the default
	if(strcmp($default,$stateArray[$i])==0)
		{
		$found	=	1;
		print '<option value="' . $stateArray[$i] .    '" selected>' . $stateArray[$i] . '</option>';
		}

	// This is the default setting of "--"
	else if(($i==count($stateArray)-1) && ($found==0))
		print '<option value="' . $stateArray[$i] .    '" selected>' . $stateArray[$i] . '</option>';
	// if the state is not found and its not the default print a non-selected state name
	else 
		print '<option value="' . $stateArray[$i] .    '">' . $stateArray[$i] . '</option>';
	}

// --------------------------------------------------------------------------------------------------------------------------------------
// End state printing
// --------------------------------------------------------------------------------------------------------------------------------------

print '</select>';
}

// --------------------------------------------------------------------------------------------------------------------------------------
// function DisplaySelector($name,$labels,$values,$default)
// Args:	$name, the widget name
//			$labels,$values, two equal sized arrays of labels and values
//			$default. the default value or "" to default to the first item
//
// Returns:	An error message if the two arrays are not the same length
//
// 061406 Fixed this so that it looks to match the value rather then the lable for the defaulting behavior
// --------------------------------------------------------------------------------------------------------------------------------------

function DisplaySelector($name,$labels,$values,$default,$additionalProperties="",$class="")
{
$debug		=	0;
$defaultSet	=	0;

if($debug) print "DisplaySelector($name,$labels,$values,$default)<br>";

// there must be a matching number of labels and values
if(count($labels) != count($values))
	{
	print "Item Count Error in DisplaySelector($name,$labels,$values)";
	return 0;
	}
	
$numberOfItems	=	count($labels);

if($debug) print "numberOfItems:$numberOfItems<br>";

$defaultSet	=	strlen($default);

if($debug) print "defaultSet:$defaultSet<br>";

if($class!="")
	{
	$classProperty	=	' class="' . $class . '" ';
	}


print '
<select ' . $classProperty . $additionalProperties . ' name="' . $name . '" id="' . $name . '">
';	

for($i=0;$i<$numberOfItems;$i++)
	{
	print '		<option value="' . $values[$i] . '"';
	
	// handle the default behaviors
	// none specified default to the first item
	if($defaultSet==0 && $i==0)
		{
		print ' selected>';
		}
	// this is the specified label
	else if($defaultSet && (strcmp($values[$i],$default)==0))
		{
		print ' selected>';
		}
	else
		{
		print '>';
		}
	print $labels[$i];

	print '</option>
	';
	}
print '</select>
';
}

?>