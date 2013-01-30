
<?php
// ---------------------------------------------------------------------------------------------------------------------------------------
// admin_template.php
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
include 'blog_ads.php';
include 'blog_parms.php';
include 'html_lib.php';
include 'pw_parms.php';	
include 'pw_lib.php';
include 'status_message.php';	
include 'select_controls.php';	
include 'appdev_revision.php';	
include 'file_lib.php';	

$debug	=	0;

// ---------------------------------------------------------------------------------------------------------------------------------------
// Authorize the user
// ---------------------------------------------------------------------------------------------------------------------------------------

if(UserIsAdmin($systemUserID) || UserIsSuper($systemUserID))
	{
	if($debug) print "User is ADMIN or SUPER<br>\n";

	if(AuthorizeAdminUser($systemUserID,$systemPassword)==false)
		{
		exit(0);
		}
	}
else
	{
	if($debug) print "User is BLOGGER<br>\n";

	$table		=	$blTable;

	if($debug) print "table: $table<br>\n";
	
	// authenticate
	if(AuthorizeBlogUser($systemUserID,$systemPassword,$table)==false)
		{
		print "Access denied.<br>";
		exit(0);
		}
	}	

// ---------------------------------------------------------------------------------------------------------------------------------------
// handle modes
// ---------------------------------------------------------------------------------------------------------------------------------------

if($blMode=="Set All to Random")
	{
	OpenDatabase();

	$query	=	"UPDATE $blTable SET blSorttype='RANDOM' WHERE 1";

	QueryDatabase($query);
	
	CloseDatabase();
	}
else if($blMode=="Toggle")
	{
	if($debug) print "Mode: TOGGLE<br>\n";

	$currentState	=	GetFieldByComparison($blTable,"blSortType","blRecordNumber",$blRecordNumber);

	if($debug) print "currentState: $currentState<br>\n";

	$toggledState	=	($currentState=="RANDOM")?"FIXED":"RANDOM";

	if($debug) print "toggledState: $toggledState<br>\n";

// have to deal a sort number here
	OpenDatabase();
	
//	$query	=	"SELECT MAX(blSortOrder) as max FROM $blTable";
	$query	=	"SELECT * FROM $blTable WHERE blSortOrder=(SELECT MAX(blSortOrder) FROM $blTable)";

	$rs	=	QueryDatabase($query);

	$priorIndex	=	GetDatabaseResult($rs,0,"blSortOrder");
	$nextIndex	=	$priorIndex+1;
	
		if($debug) print "priorIndex: $priorIndex<br>"; 
		if($debug) print "nextIndex: $nextIndex<br>"; 

	$query	=	"UPDATE $blTable SET blSortType='$toggledState', blSortOrder='$nextIndex' WHERE blRecordNumber='$blRecordNumber'";

		if($debug) print "query: $query<br>"; 
	
	QueryDatabase($query);
	
	CloseDatabase();
	}
	
// ---------------------------------------------------------------------------------------------------------------------------------------
// set up page data
// ---------------------------------------------------------------------------------------------------------------------------------------

//print "<hr>set up page data<br><br>"; 

$randomAds	=	array();
$fixedAds	=	array();

$numberOfFixed	=	BlogGetNumberOfFixedAds($blTable);
$numberOfRand	=	BlogGetNumberOfRandomAds($blTable);

if($debug) print "numberOfFixed: $numberOfFixed<br>";
if($debug) print "numberOfRand: $numberOfRand<br>";

$numberOfRows	=	($numberOfFixed <= $numberOfRand)?$numberOfRand:$numberOfFixed;

if($debug) print "numberOfRows: $numberOfRows<br>";


// get headlines
OpenDatabase();

$query	=	"SELECT blHeadline, blRecordNumber FROM $blTable WHERE blSortType='FIXED' ORDER BY blSortOrder";

if($debug) print "query: $query<br>";


$rs	=	QueryDatabase($query);

// get fixed
for($i=0;$i<$numberOfFixed;$i++)
	{
	$ad	=	array();
	
	$ad["blHeadline"]		=	GetDatabaseResult($rs,$i,"blHeadline");	
	$ad["blRecordNumber"]	=	GetDatabaseResult($rs,$i,"blRecordNumber");;	
	
	$fixedAds[]	=	$ad;
	}


$query	=	"SELECT blHeadline, blRecordNumber FROM $blTable WHERE blSortType='RANDOM' ORDER BY blRecordNumber";

$rs	=	QueryDatabase($query);

// get rand	
for($i=0;$i<$numberOfRand;$i++)
	{
	$ad	=	array();
	
	$ad["blHeadline"]		=	GetDatabaseResult($rs,$i,"blHeadline");	
	$ad["blRecordNumber"]	=	GetDatabaseResult($rs,$i,"blRecordNumber");;	

	$randomAds[]	=	$ad;
	}

CloseDatabase();



if($debug) print "blPosterID: $blPosterID<br>";
if($debug) print "blTable: $blTable<br>";
if($debug) print "blMode: $blMode<br>";
if($debug) print "blRecordNumber: $blRecordNumber<br>";

$systemArgs		=	"SystemUserID=$systemUserID&SystemPassword=$systemPassword";

$helpLink		=	"blog_help.php#SetAdOrder";
$backLink		=	"blog_home.php?blTable=$blTable&SystemUserID=$systemUserID&SystemPassword=$systemPassword";

// get the name of the blog
$blogName		=	BlogHeaderGetName($blTable);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php print $blogName; ?> - Set Ad Order</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- Hide from the robots -->		
<meta name="robots" content="noindex,nofollow">
<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
<META NAME="GOOGLEBOT" CONTENT="nofollow">
<META NAME="GOOGLEBOT" CONTENT="noindex">
<!-- End Hide from the robots -->
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="955" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="30" align="center" valign="middle" class="admin18Bold"><?php print $blogName; ?> - Set Ad 
      Order</td>
  </tr>
  <td height="40" align="center" valign="top" bgcolor="#EEEEEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="33%" height="40">&nbsp;&nbsp;&lt; <a href="<?php print $backLink; ?>" class="galleryUILink" >Back</a></td>
          <td width="33%" align="center"><a href="<?php print $backLink; ?>" target="_blank" class="galleryUILink">Help</a></td>
        <td width="33%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top"><form name="form1" method="get" action="blog_ad_set_order.php">
        <input name="blMode" type="submit" id="blMode" value="Set All to Random">
        <input name="SystemUserID" type="hidden" id="SystemUserID" value="<?php print $systemUserID; ?>">
        <input name="SystemPassword" type="hidden" id="SystemPassword" value="<?php print $systemPassword; ?>">
        <input name="blTable" type="hidden" id="blTable" value="<?php print $blTable; ?>">
      </form></td>
  </tr>
  <tr> 
    <td align="center" valign="top">&nbsp; </td>
  </tr>
  <tr> 
    <td align="center" valign="top"><table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td width="10%" bgcolor="#CCCCCC">&nbsp;</td>
          <td width="80%"><table width="100%" border="1" cellspacing="0" cellpadding="0">
              <tr align="center" valign="middle" class="admin14Bold"> 
                <td width="50%" height="30">Random Order</td>
                <td>Fixed Order</td>
              </tr>


<?php
for($i=0;$i<$numberOfRows;$i++)
	{
	if(isset($randomAds[$i]))
		{
		$randomAd		=	$randomAds[$i]["blHeadline"] . "</a>";
		$recNo			=	$randomAds[$i]["blRecordNumber"];

		$blMode			=	"Toggle";
		$blRecordNumber	=	$recNo;
		$blArgs				=	"blRecordNumber=$blRecordNumber&blSortOrder=$blSortOrder&blSortType=$blSortType&blLinkCaption=$blLinkCaption&blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller&blLinkURL=$blLinkURL&blSiteName=$blSiteName&blLinkDescription=$blLinkDescription";

		$randomAdLink	=	"blog_ad_set_order.php?$blArgs&$systemArgs";
		}
	else
		{
		$randomAd		=	"</a>&nbsp;";	
		$randomAdLink	=	"#";
		}

	if(isset($fixedAds[$i]))
		{
		$fixedAd		=	$fixedAds[$i]["blHeadline"] . "</a>";
		$recNo			=	$fixedAds[$i]["blRecordNumber"];

		$blMode			=	"Toggle";
		$blRecordNumber	=	$recNo;
		$blArgs			=	"blRecordNumber=$blRecordNumber&blSortOrder=$blSortOrder&blSortType=$blSortType&blLinkCaption=$blLinkCaption&blPosterID=$blPosterID&blHeadline=$blHeadline&blSubHead=$blSubHead&blCopy=$blCopy&blEventDate=$blEventDate&blMonth=$blMonth&blDay=$blDay&blYear=$blYear&blEventTime=$blEventTime&blItemType=$blItemType&blItemNumber=$blItemNumber&blMode=$blMode&blTable=$blTable&blCaller=$blCaller&blLinkURL=$blLinkURL&blSiteName=$blSiteName&blLinkDescription=$blLinkDescription";

		$fixedAdLink	=	"blog_ad_set_order.php?$blArgs&$systemArgs";
		}
	else
		{
		$fixedAd		=	"</a>&nbsp;";	
		$fixedAdLink	=	"#";
		}

		
	print 	'
	
			<tr align="center" valign="middle"> 
                <td height="25">
					<a CLASS="boldLink14" href="' . $randomAdLink . '">' . $randomAd . '</a>
				</td>
                <td>
						<a CLASS="boldLink14" href="' . $fixedAdLink . '">' . $fixedAd . '</a>
				</td>
            </tr>

			';
	}

?>


              


              <tr> 
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table></td>
          <td width="10%" bgcolor="#999999">&nbsp;</td>
        </tr>
      </table></td>
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
<p>&nbsp; </p>
</body>
</html>



<?php
DisplayStatusMessage($statusMessage);
?>
