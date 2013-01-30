<?php
$path  = ''; // It should be end with a trailing slash  
if ( !defined('WP_LOAD_PATH') ) {

	/** classic root path if wp-content and plugins is below wp-config.php */
	$classic_root = dirname(dirname(dirname(dirname(__FILE__)))) . '/' ;
	
	if (file_exists( $classic_root . 'wp-load.php') )
		define( 'WP_LOAD_PATH', $classic_root);
	else
		if (file_exists( $path . 'wp-load.php') )
			define( 'WP_LOAD_PATH', $path);
		else
			exit("Could not find wp-load.php");
}

// let's load WordPress
require_once( WP_LOAD_PATH . 'wp-load.php')
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Form Maker</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/jquery/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
    <link rel="stylesheet" href="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css?ver=342-20110630100">
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<base target="_self">
</head>
<body id="link"  style="" dir="ltr" class="forceColors">
	<div class="tabs" role="tablist" tabindex="-1">
		<ul>
			<li id="form_maker_tab" class="current" role="tab" tabindex="0"><span><a href="javascript:mcTabs.displayTab('Single_product_tab','Single_product_panel');" onMouseDown="return false;" tabindex="-1">Form Maker</a></span></li>
		</ul>
	</div>
    <style>
    .panel_wrapper{
		height:170px !important;
	}
    </style>
    	<div class="panel_wrapper">
			<div id="Single_product_panel" class="panel current">
                <table>
              	  <tr>
               		 <td style="height:100px; width:100px; vertical-align:top;">
                		Select a Form 
                	</td>
                	<td style="vertical-align:top">
                        <select name="Form_Makername" id="Form_Makername" style="width:250px; text-align:center">
                        	<option  style="text-align:center" value="- Select Form -" selected="selected">- Select a Form -</option>
                       		 <?php    $ids_Form_Maker=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."formmaker order by `id` DESC",0);
                               foreach($ids_Form_Maker as $arr_Form_Maker)
                               {
                                   ?>
                                   <option value="<?php echo $arr_Form_Maker->id; ?>"><?php echo $arr_Form_Maker->title; ?></option>
                         <?php }?>
                        </select>
               		 </td>
                </tr>
                </table>
                </div>
        </div>
        <div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="Cancel" onClick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="Insert" onClick="insert_Form_Maker();" />
		</div>
	</div>
<script type="text/javascript">
function insert_Form_Maker() {
	if(document.getElementById('Form_Makername').value=='- Select Form -')
	{
		tinyMCEPopup.close();
	}
	else
	{
	   var tagtext;
	   tagtext='[Form id="'+document.getElementById('Form_Makername').value+'"]';
	   window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
	   tinyMCEPopup.editor.execCommand('mceRepaint');
	   tinyMCEPopup.close();		
	}
	
}

</script>
</body>
</html>
<?php
?>