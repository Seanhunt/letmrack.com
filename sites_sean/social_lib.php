<?php
// this has functions for instanciating social buttons and widgets


// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialLoadLibraries()
//
// Call this inside the <body> tag prior to calling functions in this module to load js libs for widgets.
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialLoadLibraries()
{
print	'
<!-- RHS Social Networking Library (c) 2012 Rolling Hills Software -->
<!-- START Load Social Libs  -->

<!-- LOAD FB JS API -->
<div id="fb-root"></div>
<script type="text/javascript">(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=185745394869494";
  fjs.parentNode.insertBefore(js, fjs);
}(document, ' . "'script', 'facebook-jssdk'" . '));</script>
<!-- END LOAD FB JS API -->

<!-- LOAD G+ JS API --> 
<script type="text/javascript">
window.___gcfg = {lang: ' . "'en'" . '};
(function() 
{var po = document.createElement("script");
po.type = "text/javascript"; po.async = true;po.src = "https://apis.google.com/js/plusone.js";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(po, s);
})();</script>
<!-- END LOAD G+ JS API --> 

<!-- STUMBLE BUTTON API --> 
 <script type="text/javascript"> 
 (function() { 
     var li = document.createElement(' . "'script'" . '); li.type = ' . "'text/javascript'" . '; li.async = true; 
     li.src = window.location.protocol + ' . "'//platform.stumbleupon.com/1/widgets.js'" . '; 
     var s = document.getElementsByTagName(' . "'script'" . ')[0]; s.parentNode.insertBefore(li, s); 
 })(); 
 </script>
<!-- END STUMBLE BUTTON API --> 

<!-- PINTEREST API --> 
<script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>
<!-- END PINTEREST API --> 
<!-- END Load Social Libs  -->' . "\n\n";
}



// *****************************************************************************************************************************************************
// PayPal
// *****************************************************************************************************************************************************

// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialPayPalTipButton($buttonID="GL66KE9SXF6ZA")
//
// Renders a PayPal tip button
//
// This requires a tip button set up in PayPal with the given button ID
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialPayPalTipButton($buttonID="GL66KE9SXF6ZA")
{
print	'
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="' . $buttonID . '" />
<input  type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" />
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>';
}

// *****************************************************************************************************************************************************
// StumbleUpon
// *****************************************************************************************************************************************************

// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialStumbleUponButton($targetURL)
//
// Renders a StumbleUpon button
//
// pageURL - unencoded URL of page to submit
//
// i.e. http://cookseytalbottgallery.com/image-scroll.php?gaGallery=<?php $safeGallery; 
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialStumbleUponButton($pageURL)
{
print '
		<!-- START Stumble Upon BUTTON -->
		<su:badge layout="1" location="' . $pageURL . '"></su:badge>
		<!-- END Stumble Upon BUTTON -->
		';
}


// *****************************************************************************************************************************************************
// AddThis
// *****************************************************************************************************************************************************

// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialAddThisWidget($publisherID='cookseytalbott')
//
// It wants your addthis publisher ID as its arg
//
// Renders an addthis widget
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialAddThisWidget($publisherID='cookseytalbott')
{
print	'
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style ">
		<a class="addthis_button_preferred_1"></a>
		<a class="addthis_button_preferred_2"></a>
		<a class="addthis_button_preferred_3"></a>
		<a class="addthis_button_preferred_4"></a>
		<a class="addthis_button_compact"></a>
		<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=' . $publisherID . '"></script>
		<!-- AddThis Button END -->
		';
}

// *****************************************************************************************************************************************************
// G+
// *****************************************************************************************************************************************************

// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialGooglePlusBadge()
//
// Renders a G+ badge
//
// Wants the URL of your G+ profile
//
// i.e. https://plus.google.com/103202760473534065366
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialGooglePlusBadge($profileURL='https://plus.google.com/103202760473534065366')
{
print 	'
	<!-- START G+ badge -->
	<g:plus href="' . $profileURL . '" rel="publisher" width="340" height="69" theme="light"></g:plus>        
	<!-- END G+ badge -->
	';
}

// *****************************************************************************************************************************************************
// Facebook
// *****************************************************************************************************************************************************

// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialFaceBookMetaTags($imageURL,$pageURL,$pageTitle='Cooksey-Talbott Gallery',$applicationID='1642397372')
//
// this wants unencoded URL's for the page and image
// This info will populate the like dialog when the like button is pressed
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialFaceBookMetaTags($imageURL,$pageURL,$pageTitle,$siteName="Cooksey-Talbott Gallery",$applicationID='1642397372')
{
$tags	=	
'<!-- START Facebook Open Graph Meta Tags -->
<meta property="og:title" content="' . $pageTitle . '" />
<meta property="og:type" content="website" />
<meta property="og:url" content="' . $pageURL . '" />
<meta property="og:image" content="' . $imageURL . '" />
<meta property="og:site_name" content="' . $siteName . '" />
<meta property="fb:admins" content="' . $applicationID . '" />
<!-- END Facebook Open Graph Meta Tags -->
';
			 
print $tags;            
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialGetFBLikeButton($likeURL)
//
// Returns a FB like button string
//
// likeURL - unencoded URL of page containing item to be liked
//
// The Like dialog will be populated with info from the Open Graph meta tags (see FB OG meta function)
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialGetFBLikeButton($likeURL)
{
return	"\n<!-- START FB Like Button -->\n" . '<div class="fb-like" data-href="' . $likeURL . '" data-send="true" data-layout="button_count" data-width="450" data-show-faces="false" data-font="arial"></div>' . "\n<!-- END FB Like Button -->\n" ;
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialFaceBookLikeButton($likeURL)
//
// Renders a FB like button
//
// likeURL - unencoded URL of page containing item to be liked
//
// The Like dialog will be populated with info from the Open Graph meta tags (see FB OG meta function)
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialFaceBookLikeButton($likeURL)
{
$likeButton	=	SocialGetFBLikeButton($likeURL);

print $likeButton;
}


// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialFBLikeBox($fbPageURL='http://www.facebook.com/cookseytalbottgallery')
//
// Renders a FB like box
//
// fbPageURL - The URL of the FB page to like
// showFaces - show a facepile true or false as string
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialFBLikeBox($fbPageURL='http://www.facebook.com/cookseytalbottgallery',$showFaces='false')
{
print	'
        <!-- START FB Like Box -->
        <div class="fb-like-box" data-href="' . $fbPageURL . '" data-width="320" data-show-faces="' . $showFaces . '" data-border-color="#ffffff" data-stream="false" data-header="true"></div>
        <!-- END FB Like Box -->
		';
}


// *****************************************************************************************************************************************************
// Pinterest
// *****************************************************************************************************************************************************

// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialPinterestFollowButton()
//
// Renderes a Pinterest follow button
//
// pinterestPageURL - The unencoded URL of the page to be followed
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialPinterestFollowButton($pinterestPageURL="http://pinterest.com/cookseytalbott/")
{
print	'

		<!-- START Pinterest Follow Button -->
		<a href="' . $pinterestPageURL . '">
		<img src="http://passets-cdn.pinterest.com/images/follow-on-pinterest-button.png" width="156" height="26" alt="Follow Me on Pinterest" />
		</a>
		<!-- END Pinterest Follow Button -->
		';
}

// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialPinterestPinItButton()
//
// Renders a PinIt button
//
// imageURL - unencoded URL of the image to be pinned
// pageURL - unencoded URL of the current page
// description - Descriptive text to appear with the pin
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialPinterestPinItButton($imageURL,$pageURL,$description)
{
print SocialGetPinterestPinItButton($imageURL,$pageURL,$description);
}


// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialGetPinterestPinItButton()
//
// Returns a PinIt button string
//
// imageURL - unencoded URL of the image to be pinned
// pageURL - unencoded URL of the current page
// description - Descriptive text to appear with the pin
// -----------------------------------------------------------------------------------------------------------------------------------------------------

function SocialGetPinterestPinItButton($imageURL,$pageURL,$description)
{
$debug	=	0;

if($debug) print "SocialPinterestPinItButton($imageURL,$pageURL,$description)<br />";
	
$imageURL	=	urlencode($imageURL);
$pageURL	=	urlencode($pageURL);

if($debug) print "imageURL: $imageURL<br />";
if($debug) print "pageURL: $pageURL<br />";

$endpointURL	=	"<!-- START PinIt Button -->\n" .
					'<a href="http://pinterest.com/pin/create/button/' . 
					"?url=" . $pageURL . 
					"&media=" . $imageURL . 
					"&description=" . $description . '"' . 
					' class="pin-it-button" count-layout="horizontal">' .
					'<img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" />' .
					'</a>' . 
					"\n<!-- END PinIt Button -->\n\n";

if($debug) print "endpointURL: $endpointURL<br />";

return $endpointURL;
}



// *****************************************************************************************************************************************************
// Utilities
// *****************************************************************************************************************************************************

// -----------------------------------------------------------------------------------------------------------------------------------------------------
// function SocialGetCurrentPageURL() 
//
// Gets the URL of the current page
//
// use with SocialPinterestPinItButton and other functions that want the URL of the current page.
// -----------------------------------------------------------------------------------------------------------------------------------------------------
function SocialGetCurrentPageURL() 
{
$debug	=	0;

if($debug) print "GetCurrentPageURL()<br />";
	
$pageURL = 'http';

if ($_SERVER["HTTPS"] == "on") 
	{
		$pageURL .= "s";
	}

$pageURL .= "://";

if ($_SERVER["SERVER_PORT"] != "80") 
	{
	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} 
	else 
	{
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	

if($debug) print "pageURL: $pageURL<br />";

return $pageURL;
}






?>

