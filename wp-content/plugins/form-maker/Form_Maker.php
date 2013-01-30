<?php
/*
Plugin Name: Form Maker
Plugin URI: http://web-dorado.com/products/form-maker-wordpress.html
Version: 1.3.2
Author: http://web-dorado.com/
License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

//// load languages
///////////////////////////////////////////////////////////////////////////////////////////////////////////////// css



$first_css = ".wdform_table1
{
font-size:14px;
font-weight:normal;
color:#000000;
width:100% ;
}

.wdform_tbody1
{
float:left;
}
.wdform_table2
{
padding-right:50px !important;
float:left;
border-spacing: 0px;
border-collapse:separate !important;
}
#edit_main_table label
{
	line-height: 19px;
}
#edit_main_table td
{
padding-right: 5px;
}
.time_box
{
border-width:1px;
margin: 0px;
padding: 0px;
text-align:right;
width:30px;
vertical-align:middle
}

.mini_label
{
font-size:10px;
font-family: 'Lucida Grande', Tahoma, Arial, Verdana, sans-serif;
}

.ch_rad_label
{
display:inline;
margin-left:5px;
margin-right:15px;
float:none;
}

.label
{
border:none;
}


.td_am_pm_select
{
padding-left:5;
}

.am_pm_select
{
height: 16px;
margin:0;
padding:0
}

.input_deactive
{
color:#999999;
font-style:italic;
border-width:1px;
margin: 0px;
padding: 0px
}

.input_active
{
color:#000000;
font-style:normal;
border-width:1px;
margin: 0px;
padding: 0px
}

.required
{
border:none;
color:red
}

.captcha_img
{
border-width:0px;
margin: 0px;
padding: 0px;
cursor:pointer;


}

.captcha_refresh
{
width:30px;
height:30px;
border-width:0px;
margin: 0px;
padding: 0px;
vertical-align:middle;
cursor:pointer;
background-image: url(".plugins_url('images/refresh_black.png',__FILE__).");
}

.captcha_input
{
height:20px;
border-width:1px;
margin: 0px;
padding: 0px;
vertical-align:middle;
}

.file_upload
{
border-width:1px;
margin: 0px;
padding: 0px
}    

.page_deactive
{
border:1px solid black;
padding:4px 7px 4px 7px;
margin:4px;
cursor:pointer;
background-color:#DBDBDB;
}

.page_active
{
border:1px solid black;
padding:4px 7px 4px 7px;
margin:4px;
cursor:pointer;
background-color:#878787;
}

.page_percentage_active
{
padding:0px;
margin:0px;
border-spacing: 0px;
height:30px;
line-height:30px;
background-color:yellow;
border-radius:30px;
font-size:15px;
float:left;
text-align: right !important; 
}


.page_percentage_deactive
{
height:30px;
line-height:30px;
padding:5px;
border:1px solid black;
width:100%;
background-color:white;
border-radius:30px;
text-align: left !important; 
}

.page_numbers
{
font-size:11px;
}

.phone_area_code
{
width:50px;
}

.phone_number
{
width:100px;
}";





















//////////////////////////////////////////////////////////////////






require_once("front_end_form_maker.php");
require_once('recaptchalib.php');

add_action( 'init', 'form_maker_language_load' );

function form_maker_language_load() {
	 load_plugin_textdomain('form_maker', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action('init', 'do_output_buffer');
function do_output_buffer() {
        ob_start();
}


function form_shotrcode($atts) {
     extract(shortcode_atts(array(
	      'id' => 'no Form',
     ), $atts));
     return form_maker_front_end($id);
}
add_shortcode('Form', 'form_shotrcode');


function form_maker_scripts_method() {
				wp_enqueue_style("gmap_styles_",plugins_url("css/style_for_map.css",__FILE__),false); 
				//wp_enqueue_script("mootools",plugins_url("js/mootools.js",__FILE__));
    			wp_enqueue_script("main_g_js",plugins_url("js/main_front_end.js",__FILE__),false);
				wp_enqueue_script("Calendar",plugins_url("js/calendar.js",__FILE__),false);
 			  	wp_enqueue_script("calendar-setup",plugins_url("js/calendar-setup.js",__FILE__),false);
				wp_enqueue_script("calendar_function",plugins_url("js/calendar_function.js",__FILE__),false);
				wp_enqueue_style("Css",plugins_url("js/calendar-jos.css",__FILE__),false); 
				     
}    
 
add_action('wp_enqueue_scripts', 'form_maker_scripts_method');



///////////////////////////// FORNT END Print message



function print_massage($content)
{
$mh_after_head = did_action( 'wp_enqueue_scripts' );
if($mh_after_head==1){
	global $wpdb;
	
	
	       @session_start();
		   if(isset($_SESSION['form_submit_type'])){
		   $type_and_id=$_SESSION['form_submit_type'];
			$type_and_id=explode(',',$type_and_id);
			$form_get_type=$type_and_id[0];
			$form_get_id=$type_and_id[1];
			$_SESSION['form_submit_type']=0;
			if($form_get_type==3){
			$_SESSION['massage_after_submit']="";
			$row=$wpdb->get_row("SELECT * FROM ".$wpdb->prefix."formmaker WHERE id='".$form_get_id."'");
			return $row->submit_text;
			}
		   }
			if( isset($_SESSION['massage_after_submit']))
			{
				if($_SESSION['massage_after_submit']!="")
				{

				$message=$_SESSION['massage_after_submit'];
				$_SESSION['massage_after_submit']="";
			
 $returned_content="   <style>	
.updated,.error{
border-width:1px !important;
border-style:solid !important;
padding:0 .6em !important;
margin:5px 15px 2px !important;
-moz-border-radius:3px !important;
-khtml-border-radius:3px !important;
-webkit-border-radius:3px !important;
border-radius:3px !important;
}
.updated p, .error p
{
font-size: 12px !important;
margin:.5em 0 !important;
line-height:1 !important;
padding:2px !important;
}
 .updated, .error
{
	margin:5px 0 15px !important;
}
.updated{
	background-color:#ffffe0 !important;
	border-color:#e6db55 !important;
}
.error
{
	background-color:#ffebe8 !important;
	border-color:#c00 !important;
}
error a
{
	color:#c00 !important;
}
.error
{
	line-height:22px !important;
	margin:0 15px !important;
	padding:3px 5px !important;
}
.error-div
{
	display:block !important;
	line-height:36px !important;
	float:right !important;
	margin-right:20px !important;
}
</style>";


if($_SESSION['error_or_no'])
{
	$error='error';
}
else
{
	$error='updated';
}

			$returned_content.="<div class=\"".$error."\" ><p><strong>".$message."</strong></p></div>".$content;// modified content
			return $returned_content;
				}
				else
				{
					return $content;
				}
			}
			else
			{
				return $content;
			}
			}
			else
			{
			return $content;
			}
}


add_filter('the_content', 'print_massage'); 


///////////////////////////// FORNT END FUNCTION  
//// add front end



//// add editor new mce button
add_filter('mce_external_plugins', "Form_Maker_register");
add_filter('mce_buttons', 'Form_Maker_add_button', 0);

/// function for add new button
function Form_Maker_add_button($buttons)
{
    array_push($buttons, "Form_Maker_mce");
    return $buttons;
}
 /// function for registr new button
function Form_Maker_register($plugin_array)
{
    $url = plugins_url( 'js/editor_plugin.js' , __FILE__ ); 
    $plugin_array["Form_Maker_mce"] = $url;
    return $plugin_array;
}











function add_button_style1()
{
echo '<style type="text/css">
.wp_themeSkin span.mce_Form_Maker_mce {background:url('.plugins_url( 'images/formmakerLogo.png' , __FILE__ ).') no-repeat !important;}
.wp_themeSkin .mceButtonEnabled:hover span.mce_Form_Maker_mce,.wp_themeSkin .mceButtonActive span.mce_Form_Maker_mce
{background:url('.plugins_url( 'images/formmakerLogoHover.png' , __FILE__ ).') no-repeat !important;}
</style>';
}

add_action('admin_head', 'add_button_style1');







add_action('admin_menu', 'Form_maker_options_panel');
function Form_maker_options_panel(){
	 $icon_url=plugins_url( 'images/FormMakerLogo-16.png' , __FILE__ );
  add_menu_page('Theme page title', 'Form Maker', 'manage_options', 'Form_maker', 'Manage_Form_maker', $icon_url);
  $page_form= add_submenu_page( 'Form_maker', 'Form Maker Manager', 'Manager', 'manage_options', 'Form_maker', 'Manage_Form_maker');
  $page_submits=add_submenu_page( 'Form_maker', 'Form Maker  submissions', 'Submissions', 'manage_options', 'Form_maker_Submits', 'Form_maker_Submits');
  add_submenu_page( 'Form_maker', 'Form Maker  Themes', 'Themes', 'manage_options', 'Form_maker_Themes', 'Form_maker_Themes');
  add_submenu_page( 'Form_maker', 'Uninstall Form Maker ', 'Uninstall Form Maker', 'manage_options', 'Uninstall_Form_Maker', 'Uninstall_Form_Maker');
  
  
  
  		add_action('admin_print_styles-' . $page_form, 'form_maker_admin_styles_scripts');
  		add_action('admin_print_styles-' . $page_submits, 'form_maker_submits_styles_scripts');
}




function form_maker_submits_styles_scripts()
{
	
	
			  wp_enqueue_script('word-count');
			  wp_enqueue_script('post');
			  wp_enqueue_script('editor');
			  wp_enqueue_script('media-upload');
			  wp_admin_css('thickbox');
			  wp_print_scripts('media-upload');
			  wp_print_scripts('editor-functions');
			  do_action('admin_print_styles');
			  wp_enqueue_script( 'common' );
		  	  wp_enqueue_script( 'jquery-color' );
			  wp_print_scripts('editor');
			  if (function_exists('add_thickbox')) add_thickbox();
			  if (function_exists('wp_tiny_mce')) wp_tiny_mce();
			  wp_enqueue_script('utils');
				 wp_enqueue_script("mootools",plugins_url("js/mootools.js",__FILE__));
			  	wp_enqueue_script("f_calendar",plugins_url("js/calendar.js",__FILE__));
			  	wp_enqueue_script("f_calendar_functions",plugins_url("js/calendar_function.js",__FILE__));
			 	 wp_enqueue_script("f_calendar_setup",plugins_url("js/calendar-setup.js",__FILE__));
				 wp_enqueue_style("calendar-jos",plugins_url("js/calendar-jos.css",__FILE__));
}






function form_maker_admin_styles_scripts()
{
	if(isset($_GET['task']))
	{
		if($_GET['task']=="update" ||$_GET['task']=="save_update" || $_GET['task']=="gotoedit" ||$_GET['task']=="add_form" || $_GET['task']=="edit_form" || $_GET['task']=="Save_Edit_JavaScript" || $_GET['task']=="Save_Actions_after_submission" || $_GET['task']=="Save_Custom_text_in_email_for_administrator" || $_GET['task']=="Save_Custom_text_in_email_for_user")
		{
			  wp_enqueue_script('word-count');
			  wp_enqueue_script('post');
			  wp_enqueue_script('editor');
			  wp_enqueue_script('media-upload');
			  wp_admin_css('thickbox');
			  wp_print_scripts('media-upload');
			  wp_print_scripts('editor-functions');
			  do_action('admin_print_styles');
			  wp_enqueue_script( 'common' );
		  	  wp_enqueue_script( 'jquery-color' );
			  wp_print_scripts('editor');
			  if (function_exists('add_thickbox')) add_thickbox();
			  if (function_exists('wp_tiny_mce')) wp_tiny_mce();
			  wp_enqueue_script('utils');
			  wp_enqueue_script("jquery_form",plugins_url("js/jquery.js",__FILE__));
			  wp_enqueue_script("form_main_js",plugins_url("js/formmaker_free.js",__FILE__));
			  wp_enqueue_style("styles_form",plugins_url("css/style.css",__FILE__));
			  wp_enqueue_script("mootools",plugins_url("js/mootools.js",__FILE__));
			  wp_enqueue_script("f_calendar",plugins_url("js/calendar.js",__FILE__));
			  wp_enqueue_script("f_calendar_functions",plugins_url("js/calendar_function.js",__FILE__));
			  wp_enqueue_script("f_calendar_setup",plugins_url("js/calendar-setup.js",__FILE__));
			  
			 // wp_enqueue_script("main",plugins_url("js/main.js",__FILE__));
			  wp_enqueue_style("calendar-jos",plugins_url("js/calendar-jos.css",__FILE__));
			
			
			
			
		}
	}
	
	
	
}
















function Manage_Form_maker()
{

	require_once("form_maker_functions.php");
	require_once("form_maker_functions.html.php");	
	if(!function_exists('print_html_nav'))
	require_once("nav_function/nav_html_func.php");
	
	
	global $wpdb;
	if(isset($_GET["task"]))
	{
		$task=$_GET["task"];
	}
	else
	{
		$task="show";
	}
	if(isset($_GET["id"]))
	{
		$id=$_GET["id"];
	}
	else
	{
		$id=0;
	}


	switch($task){
		
		case 'update':
		update_form_maker();
		break;
		
		case 'save_update':
		save_update_form_maker();
		break;
		
		
		case 'update_complite':
		update_complete();
		display_form_lists();
		break;
		
		case "add_form" :
		add_form();
		break;
		
		case "edit_form" :
		edit_form_maker($id);
		break;
		
		case "Save" :
		if($id)
		apply_form($id);
		else
		save_form();
		display_form_lists();
		break;	
		
		case "Apply" :
		if($id){
			apply_form($id);
		}
		else
		{
			save_form();
			$id=$wpdb->get_var("SELECT MAX(id) FROM ".$wpdb->prefix."formmaker");
		}
		forchrome($id);
		break;
		
		case "gotoedit" :
		gotoedit();
		edit_form_maker($id);
		break;
		
		case "remove_form" :
		remove_form($id);
		display_form_lists();
		break;
		
		
		
		
		//	Actions_after_submission
		case "Actions_after_submission" :
		if($id){
			apply_form($id);
		}
		else
		{
			save_form();
			$id=$wpdb->get_var("SELECT MAX(id) FROM ".$wpdb->prefix."formmaker");
		}
		Actions_after_submission($id);
		break;
		
		case "Save_Actions_after_submission" :
		Apply_Actions_after_submission($id);
		edit_form_maker($id);
		break;
		
		case "Apply_Actions_after_submission" :
		Apply_Actions_after_submission($id);
		Actions_after_submission($id);
		break;
		
		
		//	Edit_JavaScript
		case "Edit_JavaScript" :
		if($id){
			apply_form($id);
		}
		else
		{
			save_form();
			$id=$wpdb->get_var("SELECT MAX(id) FROM ".$wpdb->prefix."formmaker");
		}
		Edit_JavaScript($id);
		break;
		
		case "Save_Edit_JavaScript" :
		Apply_Edit_JavaScript($id);
		edit_form_maker($id);
		break;
		
		case "Apply_Edit_JavaScript" :
		Apply_Edit_JavaScript($id);
		Edit_JavaScript($id);
		break;
		
		
		
		
		
		
		
		//	Custom_text_in_email_for_administrator
		case "Custom_text_in_email_for_administrator" :
		if($id){
			apply_form($id);
		}
		else
		{
			save_form();
			$id=$wpdb->get_var("SELECT MAX(id) FROM ".$wpdb->prefix."formmaker");
		}
		Custom_text_in_email_for_administrator($id);
		break;
		
		case "Save_Custom_text_in_email_for_administrator" :
		Apply_Custom_text_in_email_for_administrator($id);
		edit_form_maker($id);
		break;
		
		case "Apply_Custom_text_in_email_for_administrator" :
		Apply_Custom_text_in_email_for_administrator($id);
		Custom_text_in_email_for_administrator($id);
		break;
		
		
		
		
		
		
		
		
		
		
		
		//	Custom text in email for user
		case "Custom_text_in_email_for_user" :
		if($id){
			apply_form($id);
		}
		else
		{
			save_form();
			$id=$wpdb->get_var("SELECT MAX(id) FROM ".$wpdb->prefix."formmaker");
		}
		Custom_text_in_email_for_user($id);
		break;
		
		case "Save_Custom_text_in_email_for_user" :
		Apply_Custom_text_in_email_for_user($id);
		edit_form_maker($id);
		break;
		
		case "Apply_Custom_text_in_email_for_user" :
		Apply_Custom_text_in_email_for_user($id);
		Custom_text_in_email_for_user($id);
		break;
		case "save_as_copy":
		save_as_copy();
		display_form_lists();
		break;
		default:
		display_form_lists();
	}
	
	
	
	
	
}

////////////////////////////////////////////
//////////////////////////////////////////// Submi
////////////////////////////////////////////

////map in spubmits


add_action('wp_ajax_frommapeditinpopup', 'spider_form_map_edit');

function spider_form_map_edit(){
	if(isset($_GET['long']) && isset($_GET['lat'])){
	$long 	= $_GET['long'];
	$lat 	= $_GET['lat'];
	

		?>
        <script src="<?php echo plugins_url("js/if_gmap.js",__FILE__); ?>"></script>
		<script src="http://maps.google.com/maps/api/js?sensor=false"></script>

<table style="margin:0px; padding:0px">
<tr><td><b>Address:</b></td><td><input type="text" id="addrval0" style="border:0px; background:none" size="80" readonly /> </td></tr>
<tr><td><b>Longitude:</b></td> <td><input type="text" id="longval0" style="border:0px; background:none" size="80" readonly /> </td></tr>
<tr><td><b>Latitude:</b></td><td><input type="text" id="latval0" style="border:0px; background:none" size="80" readonly /> </td></tr>
</table>
		
<div id="0_elementform_id_temp" long="<?php echo $long ?>" center_x="<?php echo $long ?>" center_y="<?php echo $lat ?>" lat="<?php echo $lat ?>" zoom="8" info="" style="width:600px; height:500px; "></div>

<script>
		if_gmap_init("0");
		add_marker_on_map(0, 0, "<?php echo $long ?>", "<?php echo $lat ?>", '');


</script>

<?php		


	die();
	
	}
	else{
		return 0;
	}
	
}


/////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////           priview form
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

add_action('wp_ajax_frommakerpreview', 'preview_formmaker');



function html_preview_formmaker($css){
	
 /**
 * @package SpiderFC
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
		
 		$cmpnt_js_path =plugins_url('js',__FILE__);		
		$id='form_id_temp';
?>
<script src="<?php echo $cmpnt_js_path."/if_gmap.js"; ?>"></script>
<script src="<?php echo $cmpnt_js_path."/main.js"; ?>"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<style>
<?php
$cmpnt_js_path =plugins_url('',__FILE__);
 echo str_replace('[SITE_ROOT]',$cmpnt_js_path, $css);

?>
</style>
<div id="form_id_temppages" class="wdform_page_navigation" show_title="" show_numbers="" type=""></div>

  <form id="form_preview"></form>
<input type="hidden" id="counter<?php echo $id ?>" value="" name="counter<?php echo $id ?>" />

<script>
	JURI_ROOT				='<?php echo $cmpnt_js_path ?>';  

	document.getElementById('form_preview').innerHTML = window.parent.document.getElementById('take').innerHTML;
	document.getElementById('form_id_temppages').setAttribute('show_title', window.parent.document.getElementById('pages').getAttribute('show_title'));
	document.getElementById('form_id_temppages').setAttribute('show_numbers', window.parent.document.getElementById('pages').getAttribute('show_numbers'));
	document.getElementById('form_id_temppages').setAttribute('type', window.parent.document.getElementById('pages').getAttribute('type'));
	document.getElementById('counterform_id_temp').value=window.parent.gen;;

	form_view_count<?php echo $id ?>=0;
	for(i=1; i<=30; i++)
	{
		if(document.getElementById('<?php echo $id ?>form_view'+i))
		{
			form_view_count<?php echo $id ?>++;
			form_view_max<?php echo $id ?>=i;
			document.getElementById('<?php echo $id ?>form_view'+i).parentNode.removeAttribute('style');
		}
	}
	
	refresh_first();

	
	if(form_view_count<?php echo $id ?>>1)
	{
		for(i=1; i<=form_view_max<?php echo $id ?>; i++)
		{
			if(document.getElementById('<?php echo $id ?>form_view'+i))
			{
				first_form_view<?php echo $id ?>=i;
				break;
			}
		}
		
		generate_page_nav(first_form_view<?php echo $id ?>, '<?php echo $id ?>', form_view_count<?php echo $id ?>, form_view_max<?php echo $id ?>);
	}
	

function remove_add_(id)
{
			attr_name= new Array();
			attr_value= new Array();
			var input = document.getElementById(id); 
			atr=input.attributes;
			for(v=0;v<30;v++)
				if(atr[v] )
				{
					if(atr[v].name.indexOf("add_")==0)
					{
						attr_name.push(atr[v].name.replace('add_',''));
						attr_value.push(atr[v].value);
						input.removeAttribute(atr[v].name);
						v--;
					}
				}
			for(v=0;v<attr_name.length; v++)
			{
				input.setAttribute(attr_name[v],attr_value[v])
			}
}

function refresh_first()
{
		
	n=window.parent.gen;
	for(i=0; i<n; i++)
	{
		if(document.getElementById(i))
		{	
			for(z=0; z<document.getElementById(i).childNodes.length; z++)
				if(document.getElementById(i).childNodes[z].nodeType==3)
					document.getElementById(i).removeChild(document.getElementById(i).childNodes[z]);

			if(document.getElementById(i).getAttribute('type')=="type_map")
			{
				if_gmap_init(i);
				for(q=0; q<20; q++)
					if(document.getElementById(i+"_elementform_id_temp").getAttribute("long"+q))
					{
					
						w_long=parseFloat(document.getElementById(i+"_elementform_id_temp").getAttribute("long"+q));
						w_lat=parseFloat(document.getElementById(i+"_elementform_id_temp").getAttribute("lat"+q));
						w_info=parseFloat(document.getElementById(i+"_elementform_id_temp").getAttribute("info"+q));
						add_marker_on_map(i,q, w_long, w_lat, w_info, false);
					}
			}
			
			if(document.getElementById(i).getAttribute('type')=="type_mark_map")
			{
				if_gmap_init(i);
				w_long=parseFloat(document.getElementById(i+"_elementform_id_temp").getAttribute("long"+0));
				w_lat=parseFloat(document.getElementById(i+"_elementform_id_temp").getAttribute("lat"+0));
				w_info=parseFloat(document.getElementById(i+"_elementform_id_temp").getAttribute("info"+0));
				add_marker_on_map(i,0, w_long, w_lat, w_info, true);
			}
			
			
			
			if(document.getElementById(i).getAttribute('type')=="type_captcha" || document.getElementById(i).getAttribute('type')=="type_recaptcha")
			{
				if(document.getElementById(i).childNodes[10])
				{
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				}
				else
				{
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				}
				continue;
			}
			
			if(document.getElementById(i).getAttribute('type')=="type_section_break")
			{
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				continue;
			}
						

			if(document.getElementById(i).childNodes[10])
			{
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[2]);
			}
			else
			{
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
				document.getElementById(i).removeChild(document.getElementById(i).childNodes[1]);
			}
		}
	}
	
	for(i=0; i<=n; i++)
	{	
		if(document.getElementById(i))
		{
			type=document.getElementById(i).getAttribute("type");
				switch(type)
				{
					case "type_text":
					case "type_number":
					case "type_password":
					case "type_submitter_mail":
					case "type_own_select":
					case "type_country":
					case "type_hidden":
					case "type_map":
					{
						remove_add_(i+"_elementform_id_temp");
						break;
					}
					
					case "type_submit_reset":
					{
						remove_add_(i+"_element_submitform_id_temp");
						if(document.getElementById(i+"_element_resetform_id_temp"))
							remove_add_(i+"_element_resetform_id_temp");
						break;
					}
					
					case "type_captcha":
					{
						remove_add_("_wd_captchaform_id_temp");
						remove_add_("_element_refreshform_id_temp");
						remove_add_("_wd_captcha_inputform_id_temp");
						break;
					}
					
					case "type_recaptcha":
					{
						remove_add_("wd_recaptchaform_id_temp");
						break;
					}
						
					case "type_file_upload":
						{
							remove_add_(i+"_elementform_id_temp");
								break;
						}
						
					case "type_textarea":
						{
						remove_add_(i+"_elementform_id_temp");

								break;
						}
						
					case "type_name":
						{
						
						if(document.getElementById(i+"_element_titleform_id_temp"))
							{
							remove_add_(i+"_element_titleform_id_temp");
							remove_add_(i+"_element_firstform_id_temp");
							remove_add_(i+"_element_lastform_id_temp");
							remove_add_(i+"_element_middleform_id_temp");
							}
							else
							{
							remove_add_(i+"_element_firstform_id_temp");
							remove_add_(i+"_element_lastform_id_temp");

							}
							break;

						}
						
					case "type_phone":
						{
						
							remove_add_(i+"_element_firstform_id_temp");
							remove_add_(i+"_element_lastform_id_temp");

							break;

						}
						case "type_address":
							{	
								remove_add_(i+"_street1form_id_temp");
								remove_add_(i+"_street2form_id_temp");
								remove_add_(i+"_cityform_id_temp");
								remove_add_(i+"_stateform_id_temp");
								remove_add_(i+"_postalform_id_temp");
								remove_add_(i+"_countryform_id_temp");
							
								break;
	
							}
							
						
					case "type_checkbox":
					case "type_radio":
						{
							is=true;
							for(j=0; j<100; j++)
								if(document.getElementById(i+"_elementform_id_temp"+j))
								{
									remove_add_(i+"_elementform_id_temp"+j);
								}
						/*	if(document.getElementById(i+"_randomize").value=="yes")
								choises_randomize(i);*/
							
							break;
						}
						
					case "type_button":
						{
							for(j=0; j<100; j++)
								if(document.getElementById(i+"_elementform_id_temp"+j))
								{
									remove_add_(i+"_elementform_id_temp"+j);
								}
							break;
						}
						
					case "type_time":
						{	
						if(document.getElementById(i+"_ssform_id_temp"))
							{
							remove_add_(i+"_ssform_id_temp");
							remove_add_(i+"_mmform_id_temp");
							remove_add_(i+"_hhform_id_temp");
							}
							else
							{
							remove_add_(i+"_mmform_id_temp");
							remove_add_(i+"_hhform_id_temp");
							}
							break;

						}
						
					case "type_date":
						{	
						remove_add_(i+"_elementform_id_temp");
						remove_add_(i+"_buttonform_id_temp");
							break;
						}
					case "type_date_fields":
						{	
						remove_add_(i+"_dayform_id_temp");
						remove_add_(i+"_monthform_id_temp");
						remove_add_(i+"_yearform_id_temp");
								break;
						}
				}	
		}
	}
	

	for(t=1;t<=form_view_max<?php echo $id ?>;t++)
	{
		if(document.getElementById('form_id_tempform_view'+t))
		{
			form_view_element=document.getElementById('form_id_tempform_view'+t);
			xy=form_view_element.childNodes.length-2;
			for(z=0;z<=xy;z++)
			{
				if(form_view_element.childNodes[z])
				if(form_view_element.childNodes[z].nodeType!=3)
				if(!form_view_element.childNodes[z].id)
				{
					del=true;
					GLOBAL_tr=form_view_element.childNodes[z];
					//////////////////////////////////////////////////////////////////////////////////////////
					for (x=0; x < GLOBAL_tr.firstChild.childNodes.length; x++)
					{
						table=GLOBAL_tr.firstChild.childNodes[x];
						tbody=table.firstChild;
						if(tbody.childNodes.length)
							del=false;
					}
					
					if(del)
					{
						form_view_element.removeChild(form_view_element.childNodes[z]);
					}

				}
			}
		}
	}


	for(i=1; i<=window.parent.form_view_max; i++)
		if(document.getElementById('form_id_tempform_view'+i))
		{
			document.getElementById('form_id_tempform_view'+i).parentNode.removeChild(document.getElementById('form_id_tempform_view_img'+i));
			document.getElementById('form_id_tempform_view'+i).removeAttribute('style');
		}
	
}


</script>
<?php 
die();
}
function  preview_formmaker()
{
	global $wpdb;
	if(isset($_GET['id']))
	$getparams=$_GET['id'];
	$query = "SELECT css FROM ".$wpdb->prefix."formmaker_themes WHERE id=".$getparams;	
	$css = $wpdb->get_var($query);
	html_preview_formmaker($css);
}













//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////



function Form_maker_Submits()
{
	require_once("Submissions.html.php");
	require_once("Submissions.php");
	if(!function_exists('print_html_nav'))
	require_once("nav_function/nav_html_func.php");
	
		
	global $wpdb;
	if(isset($_GET["task"]))
	{
		$task=$_GET["task"];
	}
	else
	{
		$task="show";
	}
	if(isset($_GET["id"]))
	{
		$id=$_GET["id"];
	}
	else
	{
		$id=0;
	}
switch($task){
		case "submits" :
		show_submits();
		break;
		case "edit_submit" :
		editSubmit($id);
		break;
		case "remove_submit" :
		remov_submit($id);
		show_submits();
		break;
		case "remov_cheched" :
		remov_cheched_submission();
		show_submits();
		break;
		case "appply_submit" :
		save_submit($id);
		editSubmit($id);
		break;
		case "save_submit" :
		save_submit($id);
		show_submits();
		break;
		case "cancel" :
		show_submits();
		break;
		
		
		default:
		show_submits();
	}
	
	
	
	
	
	
	
}
function Form_maker_Themes(){

	require_once("Theme_functions.php");
	require_once("Themes_function.html.php");
	if(!function_exists('print_html_nav'))
	require_once("nav_function/nav_html_func.php");
	global $wpdb;
	if(isset($_GET["task"]))
	{
		$task=$_GET["task"];
	}
	else
	{
		$task="";
	}
	if(isset($_GET["id"]))
	{
		$id=$_GET["id"];
	}
	else
	{
		$id=0;
	}
	switch($task){
	case 'theme':
		show_theme();
		break;
	case 'default':
		default_theme($id);
		show_theme();
		break;
		
	case 'add_theme':
		add_theme();
		break;
		
	case 'Save':
	if($id)
	{
		apply_theme($id);
	}
	else
	{
		save_theme();
	}
	
	show_theme();	
		break;
		
		case 'Apply':	
		if($id)	
		{
			apply_theme($id);
		}
		else
		{
			save_theme();
			$id=$wpdb->get_var("SELECT MAX(id) FROM ".$wpdb->prefix."formmaker_themes");
		}
		
		edit_theme($id);
		break;
		
	case 'edit_theme':
    		edit_theme($id);
    		break;	
		
	case 'remove_theme':
		remove_theme($id);
		show_theme();
		break;
		default:
		show_theme();
	}

	
	
	
	}




function Uninstall_Form_Maker()
{

global $wpdb; 
$base_name = plugin_basename('Form_maker');
$base_page = 'admin.php?page='.$base_name;
$mode = trim($_GET['mode']);


if(!empty($_POST['do'])) {

	if($_POST['do']=="UNINSTALL Form Maker") {
			check_admin_referer('Form Maker_uninstall');
			if(trim($_POST['uninstall_Form_yes']) == 'yes') {
				echo '<div id="message" class="updated fade">';
				echo '<p>';
				echo "Table 'formmaker' has been deleted.";
				$wpdb->query("DROP TABLE ".$wpdb->prefix."formmaker");
				echo '<font style="color:#000;">';
				echo '</font><br />';
				echo '</p>';
				echo '<p>';
				echo "Table 'formmaker_submits' has been deleted.";
				$wpdb->query("DROP TABLE ".$wpdb->prefix."formmaker_submits");
				echo '<font style="color:#000;">';
				echo '</font><br />';
				echo '</p>';
				echo '<p>';
				echo "Table 'formmaker_views' has been deleted.";
				$wpdb->query("DROP TABLE ".$wpdb->prefix."formmaker_views");
				echo '<font style="color:#000;">';
				echo '</font><br />';
				echo '</p>';
				echo '<p>';
				echo "Table 'formmaker_themes' has been deleted.";
				$wpdb->query("DROP TABLE ".$wpdb->prefix."formmaker_themes");
				echo '<font style="color:#000;">';
				echo '</font><br />';
				echo '</p>';
				echo '</div>'; 
				$mode = 'end-UNINSTALL';
				
			}
		}
}



switch($mode) {

		case 'end-UNINSTALL':
			$deactivate_url = wp_nonce_url('plugins.php?action=deactivate&amp;plugin='.plugin_basename(__FILE__), 'deactivate-plugin_'.plugin_basename(__FILE__)).'&form_maker_uninstall=1';
			echo '<div class="wrap">';
			echo '<div id="icon-Form_maker" class="icon32"><br /></div>';
			echo '<h2>Uninstall Form Maker</h2>';
			echo '<p><strong>'.sprintf('<a href="%s">Click Here</a> To Finish The Uninstallation And Form Maker Will Be Deactivated Automatically.', $deactivate_url).'</strong></p>';
			echo '</div>';
			break;
	// Main Page
	default:
?>
<form method="post" action="<?php echo admin_url('admin.php?page=Uninstall_Form_Maker'); ?>">
<?php wp_nonce_field('Form Maker_uninstall'); ?>
<div class="wrap">
	<div id="icon-Form_maker" class="icon32"><br /></div>
	<h2><?php echo 'Uninstall Form Maker'; ?></h2>
	<p>
		<?php echo 'Deactivating Form Maker plugin does not remove any data that may have been created, such as the Forms and the Submissions. To completely remove this plugin, you can uninstall it here.'; ?>
	</p>
	<p style="color: red">
		<strong><?php echo'WARNING:'; ?></strong><br />
		<?php echo 'Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.'; ?>
	</p>
	<p style="color: red">
		<strong><?php echo 'The following WordPress Options/Tables will be DELETED:'; ?></strong><br />
	</p>
	<table class="widefat">
		<thead>
			<tr>
				<th><?php echo 'WordPress Tables'; ?></th>
			</tr>
		</thead>
		<tr>
			<td valign="top">
				<ol>
				<?php
						echo '<li>formmaker</li>'."\n";
						echo '<li>formmaker_submits</li>'."\n";
						echo '<li>formmaker_views</li>'."\n";
						echo '<li>formmaker_themes</li>'."\n";
					
				?>
				</ol>
			</td>
		</tr>
	</table>
	<p style="text-align: center;">
		<?php echo 'Do you really want to uninstall Form Maker?'; ?><br /><br />
		<input type="checkbox" name="uninstall_Form_yes" value="yes" />&nbsp;<?php echo 'Yes'; ?><br /><br />
		<input type="submit" name="do" value="<?php echo 'UNINSTALL Form Maker'; ?>" class="button-primary" onclick="return confirm('<?php echo 'You Are About To Uninstall Form Maker From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.'; ?>')" />
	</p>
</div>
</form>
<?php
} // End switch($mode)

}



require_once("update_sql.php");





function formmaker_activate()
{
 include 'setup_sql.php';
 set_form_maker_sql();

}


if(get_bloginfo ('version')>=3.1){

add_action('plugins_loaded', 'formmaker_chech_update');

}
else{
	formmaker_chech_update();
}






register_activation_hook( __FILE__, 'formmaker_activate' );


function sp_form_deactiv(){
	echo $_GET['form_maker_uninstall'];
	
	if(isset($_GET['form_maker_uninstall']))
	{
		if($_GET['form_maker_uninstall']==1){
		delete_option('formmaker_cureent_version');
		}
	}
}

register_deactivation_hook( __FILE__, 'sp_form_deactiv' );