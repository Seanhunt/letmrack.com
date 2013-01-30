<?php
$themename = "Marina";
$shortname = "marina";
$zm_categories_obj = get_categories('hide_empty=0');
$zm_categories = array();
foreach ($zm_categories_obj as $zm_cat) {
$zm_categories[$zm_cat->cat_ID] = $zm_cat->category_nicename;
}
$categories_tmp = array_unshift($zm_categories, "Select a category:");	
$number_entries = array("Select a Number:","1","2","3","4","5","6","7","8","9","10", "12","14", "16", "18", "20");
$options = array (


array(  "name" => "Custom Fonts",
            "type" => "title",
			"desc" => "Enables or disables custom fonts.",
       ),
	array(    "type" => "open"),
	
	array(   "name" => "Enable Custom fonts?",
      	     "id" => $shortname."_cufon",
     		 "type" => "select",		
			 "options" => array('Yes', 'No'),		
   		     "std" => "Yes"),
			
	array(    "type" => "close"),

    array(  "name" => "Slider settings",
            "type" => "title",
			"desc" => "This section customizes the Sliding panel.",
       		),
	   
	array(    "type" => "open"),
	
	array( 	"name" => "Slider post Category",
			"desc" => "Select the category from which you want to display the slides.",
			"id" => $shortname."_gldcat",
			"std" => "Uncategorized",
			"type" => "select",
			"options" => $zm_categories),
			
	array(	"name" => "Number of slides",
			"desc" => "Enter the number of posts to display .",
			"id" => $shortname."_gldct",
			"std" => "4",
			"type" => "text",
			),
			
	array(    "type" => "close"),
	
	
	array(  "name" => "Twitter ",
            "type" => "title",
		       		),		
  
	array(    "type" => "open"),
	
	array("name" => "Twitter Id",
			"desc" => "Enter your twitter ID.",
            "id" => $shortname."_twit",
            "std" => "twitter",
            "type" => "text"),   
  		
	
	array(    "type" => "close"),
		
	array(  "name" => "125 x 125 banner Settings",
            "type" => "title",
			"desc" => "You can setup four 125 x 125 banners on your sidebar from here",
       ), 
	 
	array("type" => "open"),
	array("name" => "Banner-1 Image",
			"desc" => "Enter your 125 x 125 banner image url here.",
            "id" => $shortname."_banner1",
            "std" => "http://bit.ly/9CE5WS ",
            "type" => "text"),    
	  
	array("name" => "Banner-1 Url",
			"desc" => "Enter the banner-1 url here.",
            "id" => $shortname."_url1",
            "std" => "Banner-1 url",
            "type" => "text"),    
		 
	array("name" => "Banner-2 Image",
			"desc" => "Enter your 125 x 125 banner image url here.",
            "id" => $shortname."_banner2",
            "std" => "http://bit.ly/9CE5WS ",
            "type" => "text"),    
	   
	array("name" => "Banner-2 Url",
			"desc" => "Enter the banner-2 url here.",
            "id" => $shortname."_url2",
            "std" => "Banner-2 url",
            "type" => "text"), 

	array("name" => "Banner-3 Image",
			"desc" => "Enter your 125 x 125 banner image url here.",
            "id" => $shortname."_banner3",
            "std" => "http://bit.ly/9CE5WS ",
            "type" => "text"),    
	   
	array("name" => "Banner-3 Url",
			"desc" => "Enter the banner-3 url here.",
            "id" => $shortname."_url3",
            "std" => "Banner-3 url",
            "type" => "text"),

	array("name" => "Banner-4 Image",
			"desc" => "Enter your 125 x 125 banner image url here.",
            "id" => $shortname."_banner4",
            "std" => "http://bit.ly/9CE5WS ",
            "type" => "text"),    
	   
	array("name" => "Banner-4 Url",
			"desc" => "Enter the banner-4 url here.",
            "id" => $shortname."_url4",
            "std" => "Banner-4 url",
            "type" => "text"),
		
	array("type" => "close"),	
				
	array(  "name" => "Advertizement",
       		 "type" => "title",
			"desc" => "Setup the ad banner on posts.",
       ),
	   
  	array("type" => "open"),
	   
	   
    array("name" => "Ads on posts ",
			"desc" => "adsense or other ad scripts .",
            "id" => $shortname."_ad1",
            "std" => "",
            "type" => "textarea"), 
			

			
	array("type" => "close"),	
	
	
	array(  "name" => "Footer stuff",
        	"type" => "title",
			"desc" => "Add stuff to footer .",
       ),
	   
    array("type" => "open"),
	
    array("name" => "Add a line of text or analytics script to the footer ",
			"desc" => "You can add your analytic code, privacy policy text etc to the footer ",
            "id" => $shortname."_foot",
            "std" => "",
            "type" => "textarea"), 
			
	array( "type" => "close"),	
);

 
function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: themes.php?page=theme_options.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); 
                update_option( $value['id'], $value['std'] );}

            header("Location: themes.php?page=theme_options.php&reset=true");
            die;

        }
    }

      add_theme_page($themename." Options", "$themename Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}



function mytheme_admin() {

    global $themename, $shortname, $options;


    
    
?>
<div class="wrap">
<div class="opwrap" style="background:#fff; margin:20px auto; width:80%; padding:30px; border:1px solid #ddd;" >

 <h2 class="wraphead2" style="margin:0px 0px; padding:15px 10px; font-family:arial black; font-style:normal; background:#B3D5EF;"><b><?php echo $themename; ?> images setup guide</b></h2>
<table width="100%" border="0" style="background-color:#eef5fb; padding:10px;">

<tr>
    <td>
	
	<b> Featured thumbnail configuration </b>
	<p style="text-align:center;">
	<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,115,0' width='560' height='345'><param name='movie' value='http://screenr.com/Content/assets/screenr_1116090935.swf' /><param name='flashvars' value='i=88375' /><param name='allowFullScreen' value='true' /><embed src='http://screenr.com/Content/assets/screenr_1116090935.swf' flashvars='i=88375' allowFullScreen='true' width='560' height='345' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed></object>
	</p>
	
	

	</td>
</tr>

</table><br /> 

<h2 class="wraphead" style="margin:10px 0px; padding:15px 10px; font-family:arial black; font-style:normal; background:#B3D5EF;"><b><?php echo $themename; ?> theme options</b></h2>
   <?php
   if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
	?>
<form method="post">

<?php foreach ($options as $value) {


switch ( $value['type'] ) {

case "open":
?>
<table width="100%" border="0" style="background-color:#eef5fb; padding:10px;">

<?php break;

case "close":
?>

</table><br />

<?php break;

case "title":
?>

<table width="100%" border="0" style="background-color:#dceefc; padding:5px 10px;"><tr>
    <td colspan="2"><h3 style="font-size:18px;font-family:Georgia,'Times New Roman',Times,serif;"><?php echo $value['name']; ?></h3></td>
</tr>

<?php break;

case 'text':
?>

<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="80%"><input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ffffff;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'textarea':
?>

<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="80%"><textarea name="<?php echo $value['id']; ?>" style="width:400px; height:200px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes (get_settings( $value['id'] )); } else { echo $value['std']; } ?></textarea></td>

</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ffffff;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case 'select':
?>
<tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
    <td width="80%"><select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select></td>
</tr>

<tr>
    <td><small><?php echo $value['desc']; ?></small></td>
</tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ffffff;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php
break;

case "checkbox":
?>
    <tr>
    <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
        <td width="80%"><? if(get_settings($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                </td>
    </tr>

    <tr>
        <td><small><?php echo $value['desc']; ?></small></td>
   </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #ffffff;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

<?php         break;

}
}
?>

<p class="submit">
<input name="save" type="submit" value="Save changes" />
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form>
<p style="text-align:right;"> <small> WordPress themes from <a href="http://www.web2feel.com/">web2feel.com</a> </small>
</div>
<?php
}
add_action('admin_menu', 'mytheme_add_admin'); ?>