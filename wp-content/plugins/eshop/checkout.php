<?php
if ('checkout.php' == basename($_SERVER['SCRIPT_FILENAME']))
     die ('<h2>Direct File Access Prohibited</h2>');

global $wpdb;

if (!function_exists('eshopShowform')) {
	function eshopShowform($first_name,$last_name,$company,$phone,$email,$address1,$address2,$city,$state,$altstate,$zip,$country,$reference,$comments,$ship_name,$ship_company,$ship_phone,$ship_address,$ship_city,$ship_postcode,$ship_state,$ship_altstate,$ship_country){
	global $wpdb, $blog_id,$eshopoptions;
	$reqdvalues=array('shipping','first_name','last_name','email','phone','address','city','zip','pay');
	//setupshipping arrays
	if($eshopoptions['shipping']!='4'){
		if($eshopoptions['shipping_zone']=='country'){
			$reqdvalues[]='country';
		}else{
			$reqdvalues[]='state';
		}
	}else{
		$creqd='';
		$dtable=$wpdb->prefix.'eshop_rates';
		$query=$wpdb->get_results("SELECT DISTINCT(area) from $dtable where rate_type='ship_weight'");
		foreach($query as $k)
			$reqdvalues[]=$k->area;
	}
	$linkattr=apply_filters('eShopCheckoutLinksAttr','');
	
	$reqdarray=apply_filters('eshopCheckoutReqd', $reqdvalues );

	$xtralinks=eshop_show_extra_links();
	$echo = '
	<div class="hr"></div>
	<div class="eshopcustdetails custdetails">
	<p><small class="privacy"><span class="reqd" title="Asterisk">*</span> '.__('Denotes Required Field ','eshop').'
	'.__($xtralinks,'eshop').'</small></p>
	<form action="'.esc_url($_SERVER['REQUEST_URI']).'" method="post" class="eshop eshopform">';
	
	if($eshopoptions['shipping']=='4' && 'no' == $eshopoptions['downloads_only'] && !eshop_only_downloads()){
		//only for ship by weight
		$echo.='<fieldset class="eshop fld0"><legend id="shiplegend">'. __('Please Choose Shipping','eshop').eshop_checkreqd($reqdarray,'shipping').'</legend>';
		$typearr=explode("\n", $eshopoptions['ship_types']);
		$cartweight=$_SESSION['eshop_totalweight'.$blog_id]['totalweight'];
		$eshopshiptable='';
		$eshopletter = "A";
		$dtable=$wpdb->prefix.'eshop_rates';
		$weightsymbol=$eshopoptions['weight_unit'];
		$currsymbol=$eshopoptions['currency_symbol'];
		$stype='';
		if(isset($_POST['eshop_shiptype'])) $stype=$_POST['eshop_shiptype'];
		$first=apply_filters('eshop_default_shipping','1');
		/* '1- text 2 - weight 3-weight symbol' */
		$echo .='<label for ="eshop_shiptype1"><p>'.sprintf( __('%1$s %2$s %3$s','eshop'),__('Total weight: ','eshop'), number_format_i18n($cartweight,__('2','eshop')),$weightsymbol).'</label></p>';
		foreach ($typearr as $k=>$type){
			$k++;
			$query=$wpdb->get_results("SELECT * from $dtable  where weight<='$cartweight' &&  class='$k' && rate_type='ship_weight' order by weight DESC limit 1");
			if(count($query)==0) 
				continue;
			if($query['0']->maxweight!='' && $cartweight > $query['0']->maxweight)
				continue;
			$eshopshiptableinner ='
			<table class="eshopshiprates eshop">
			<thead>
			<tr>';
			for($z=1;$z<=$eshopoptions['numb_shipzones'];$z++){
				$y='zone'.$z;
				$echozone=sprintf(__('Zone %1$d','eshop'),$z);
				$dispzone=apply_filters('eshop_rename_ship_zone',array());
				if(isset($dispzone[$z]))
					$echozone=$dispzone[$z];
				$eshopshiptableinner.='<th id="'.$eshopletter.$y.'" class="'.$y.'">'. $echozone .'</th>';
			}
			$eshopshiptableinner.='</tr>
			</thead>
			<tbody>';
			
			$x=1;
			foreach ($query as $row){
				$alt = ($x % 2) ? '' : ' class="alt"';
				$eshopshiptableinner.='
				<tr'.$alt.'>';
				for($z=1;$z<=$eshopoptions['numb_shipzones'];$z++){
					$y='zone'.$z;
					$eshopshiptableinner.='<td headers="'.$eshopletter.$y.'" class="'.$y.'">'.sprintf( __('%1$s%2$s','eshop'), $currsymbol, $row->$y).'</td>';
				}
				$eshopshiptableinner.='</tr>';
				$x++;
			}
			$eshopletter++;
			$eshopshiptableinner.='</tbody></table>'."\n";
			
			if($row->area=='country')
				$eshopshiptableheadtext = sprintf( __('%1$s <small>%2$s</small>','eshop'),stripslashes(esc_attr($type)), __('(Shipping Zones by Country)','eshop'));
			else
				$eshopshiptableheadtext = sprintf( __('%1$s <small>%2$s</small>','eshop'),stripslashes(esc_attr($type)), __('(Shipping Zones by State/County/Province)','eshop'));
			
			if(isset($row->maxweight) && $row->maxweight!='')
				$eshopshiptableheadtext .= ' '.sprintf( __('Max. Weight %1$s %2$s','eshop'),$row->maxweight,$eshopoptions['weight_unit']);
			if($first=='1' && $stype==''){
				$stype=$k;
				$first=0;
			}
			$eshopshiptablehead='<span><input class="rad" type="radio" name="eshop_shiptype" value="'.$k.'" id="eshop_shiptype'.$k.'"'.checked($stype,$k,false).' /> <label for="eshop_shiptype'.$k.'">'.$eshopshiptableheadtext.'</label></span>';
			
			$eshopshiptable .= $eshopshiptablehead.$eshopshiptableinner;

		}
		if($eshopshiptable != '')
			$echo .= $eshopshiptable;
		else
			$echo .= '<input type="hidden" name="eshop_shiptype" value="0" id="eshop_shiptype0" />';
		$echo .='</fieldset>';
	}
	
	//for display:
	$first_name=esc_html((stripslashes(trim($first_name))),'1');
	$last_name=esc_html((stripslashes(trim($last_name))),'1');
	
	$echo.='<fieldset class="eshop fld1"><legend id="mainlegend">'. __('Please Enter Your Details','eshop').'</legend>
	<fieldset class="eshop fld2">';
	if('no' == $eshopoptions['downloads_only']){
		$echo .='<legend>'.__('Mailing Address','eshop').'</legend>';
	}else{
		$echo .='<legend>'.__('Contact Details','eshop').'</legend>';
	}
	$echo .='<span class="firstname"><label for="first_name">'.__('First Name','eshop').eshop_checkreqd($reqdarray,'first_name').'</label>
	  <input class="med" type="text" name="first_name" value="'.$first_name.'" id="first_name" maxlength="50" size="40" /><br /></span>
	 <span class="lastname"><label for="last_name">'.__('Last Name','eshop').eshop_checkreqd($reqdarray,'last_name').'</label>
	  <input class="med" type="text" name="last_name" value="'.$last_name.'" id="last_name" maxlength="50" size="40" /><br /></span>';
	if('no' == $eshopoptions['downloads_only']){
	$echo .='<span class="company"><label for="company">'.__('Company','eshop').eshop_checkreqd($reqdarray,'company').'</label>
	  <input class="med" type="text" name="company" value="'.$company.'" id="company" size="40" maxlength="255" /><br /></span>';
	}
	$echo .='<span class="email"><label for="email">'.__('Email','eshop').eshop_checkreqd($reqdarray,'email').'</label>
	  <input class="med" type="text" name="email" value="'.$email.'" id="email" maxlength="100" size="40" /><br /></span>';
	if('no' == $eshopoptions['downloads_only']){
		$echo .='<span class="phone"><label for="phone">'.__('Phone','eshop').eshop_checkreqd($reqdarray,'phone').'</label>
		  <input class="med" type="text" name="phone" value="'.$phone.'" id="phone" maxlength="30" size="30" /><br /></span>
		 <span class="address1"><label for="address1">'.__('Address','eshop').eshop_checkreqd($reqdarray,'address').'</label>
		  <input class="med" type="text" name="address1" id="address1" value="'.$address1.'" maxlength="255" size="40" /><br /></span>
		 <span class="address2"><label for="address2">'.__('Address (continued)','eshop').'</label>
		  <input class="med" type="text" name="address2" id="address2" value="'.$address2.'" maxlength="255" size="40" /><br /></span>
		 <span class="city"><label for="city">'.__('City or town','eshop').eshop_checkreqd($reqdarray,'city').'</label>
		  <input class="med" type="text" name="city" value="'.$city.'" id="city" maxlength="100" size="40" /><br /></span>'."\n";

		// state list from db
		$table=$wpdb->prefix.'eshop_states';
		$getstate=$eshopoptions['shipping_state'];
		if($eshopoptions['show_allstates'] != '1'){
			$stateList=$wpdb->get_results("SELECT id,code,stateName FROM $table WHERE list='$getstate' ORDER BY stateName",ARRAY_A);
		}else{
			$stateList=$wpdb->get_results("SELECT id,code,stateName,list FROM $table ORDER BY list,stateName",ARRAY_A);
		}
		
		if(sizeof($stateList)>0){
			$echo .='<span class="state"><label for="state">'.__('State/County/Province','eshop').eshop_checkreqd($reqdarray,'state').'</label>
			  <select class="med pointer" name="state" id="state">';
			$echo .='<option value="">'.__('Please Select','eshop').'</option>';
			$echo .= apply_filters('eshop_states_na','<option value="">'.__('not applicable','eshop').'</option>');
			foreach($stateList as $code => $value){
				if(isset($value['list'])) $li=$value['list'];
				else $li='1';
				$eshopstatelist[$li][$value['id']]=array($value['code'],$value['stateName']);
			}
			$tablec=$wpdb->prefix.'eshop_countries';
			foreach($eshopstatelist as $egroup =>$value){
				$eshopcname=$wpdb->get_var("SELECT country FROM $tablec where code='$egroup' limit 1");
				$echo .='<optgroup label="'.$eshopcname.'">'."\n";

				foreach($value as $code =>$stateName){
					//$stateName=esc_attr($stateName);
					if (isset($state) && ($state == $stateName['0'] || $state == $code)){
						$echo.= '<option value="'.$code.'" selected="selected">'.$stateName['1']."</option>\n";
					}else{
						$echo.='<option value="'.$code.'">'.$stateName['1']."</option>\n";
					}
				}
				$echo .="</optgroup>\n";
			}
			$echo.= "</select><br /></span>\n";
		}else{
			$echo .='<input type="hidden" name="state" value="" />';
		}
		$echo .= '<span class="altstate"><label for="altstate">'.__('State/County/Province <small>if not listed above</small>','eshop').'</label>
				  <input class="short" type="text" name="altstate" value="'.$altstate.'" id="altstate" size="20" /><br /></span>';
		$echo .= '
		 <span class="zip"><label for="zip">'.__('Zip/Post code','eshop').eshop_checkreqd($reqdarray,'zip').'</label>
		  <input class="short" type="text" name="zip" value="'.$zip.'" id="zip" maxlength="20" size="20" /><br /></span>
		 <span class="country"><label for="country">'.__('Country','eshop').eshop_checkreqd($reqdarray,'country').'</label>
		  <select class="med pointer" name="country" id="country">
		';
		// country list from db
		$tablec=$wpdb->prefix.'eshop_countries';
		$List=$wpdb->get_results("SELECT code,country FROM $tablec GROUP BY list,country",ARRAY_A);
		foreach($List as $key=>$value){
			$k=$value['code'];
			$v=$value['country'];
			$countryList[$k]=$v;
		}
		if(!isset($countryList)){
			wp_die(__('Error, please contact site owner.','eshop'));
		}
		$echo .='<option value="" selected="selected">'.__('Select your Country','eshop').'</option>';
		foreach($countryList as $code => $label){
			$label=htmlspecialchars($label);
			if (isset($country) && $country == $code){
				$echo.= "<option value=\"$code\" selected=\"selected\">$label</option>\n";
			}else{
				$echo.="<option value=\"$code\">$label</option>";
			}
		}
		$echo.= "</select></span>";
	}
	$echo .="</fieldset>";
	
	$echo = apply_filters('eshopaddtocheckout',$echo);

	if('yes' != $eshopoptions['hide_addinfo']){
		$echo .= '<fieldset class="eshop fld3">
		<legend>'.__('Additional information','eshop').'</legend>
		 <span class="eshopreference"><label for="reference">'.__('Reference or <abbr title="Purchase Order number">PO</abbr>','eshop').eshop_checkreqd($reqdarray,'ref').'</label>
		  <input type="text" class="med" name="reference" value="'.$reference.'" id="reference" size="30" maxlength="255" /><br /></span>
		 <label for="eshop-comments">'.__('Comments or special instructions','eshop').eshop_checkreqd($reqdarray,'comments').'</label>
		  <textarea class="textbox" name="comments" id="eshop-comments" cols="60" rows="5">'.$comments.'</textarea>';
		 $echo = apply_filters('eshopaddtoadditionalinformation',$echo);
		 $echo .= "</fieldset>\n";
	}
	if('no' == $eshopoptions['downloads_only']){
		if('yes' != $eshopoptions['hide_shipping']){
			$echo .='<fieldset class="eshop fld4">
			<legend>'.__('Shipping address (if different)','eshop').'</legend>
			 <span class="ship_name"><label for="ship_name">'.__('Name','eshop').'</label>
			  <input class="med" type="text" name="ship_name" id="ship_name" value="'.stripslashes(esc_attr($ship_name)).'" maxlength="100" size="40" /><br /></span>
			 <span class="ship_company"><label for="ship_company">'.__('Company','eshop').'</label>
			  <input class="med" type="text" name="ship_company" value="'.stripslashes(esc_attr($ship_company)).'" id="ship_company" size="40" maxlength="255" /><br /></span>
			 <span class="ship_phone"><label for="ship_phone">'.__('Phone','eshop').'</label>
			  <input class="med" type="text" name="ship_phone" value="'.$ship_phone.'" id="ship_phone" maxlength="30" size="30" /><br /></span>
			 <span class="ship_address"><label for="ship_address">'.__('Address','eshop').'</label>
			  <input class="med" type="text" name="ship_address" id="ship_address" value="'.stripslashes(esc_attr($ship_address)).'" maxlength="255" size="40" /><br /></span>
			 <span class="ship_city"><label for="ship_city">'.__('City or town','eshop').'</label>
			  <input class="med" type="text" name="ship_city" id="ship_city" value="'.stripslashes(esc_attr($ship_city)).'" maxlength="100" size="40" /><br /></span>'."\n";
			if(isset($stateList) && sizeof($stateList)>0){
				$echo .='<span class="ship_state"><label for="shipstate">'.__('State/County/Province','eshop').'</label>
				  <select class="med pointer" name="ship_state" id="shipstate">';
				//state list from db, as above
				$echo .='<option value="" selected="selected">'.__('Please Select','eshop').'</option>';
				$echo .=apply_filters('eshop_states_na','<option value="">'.__('not applicable','eshop').'</option>');
				foreach($eshopstatelist as $egroup =>$value){
					$eshopcname=$wpdb->get_var("SELECT country FROM $tablec where code='$egroup' limit 1");

					$echo .='<optgroup label="'.$eshopcname.'">'."\n";
					foreach($value as $code =>$stateName){
						//$stateName=htmlspecialchars($stateName);
						if (isset($ship_state) && ($ship_state == $code ||$ship_state == $stateName['0']) ){
							$echo.= '<option value="'.$code.'" selected="selected">'.$stateName['1']."</option>\n";
						}else{
							$echo.='<option value="'.$code.'">'.$stateName['1']."</option>\n";
						}
					}
					$echo .="</optgroup>\n";
				}
				$echo .= '</select><br /></span>';
			}else{
				$echo .='<input type="hidden" name="ship_state" value="" />';
			}
			$echo .= '<span class="ship_altstate"><label for="ship_altstate">'.__('State/County/Province <small>if not listed above</small>','eshop').'</label>
					 <input class="short" type="text" name="ship_altstate" value="'.stripslashes(esc_attr($ship_altstate)).'" id="ship_altstate" size="20" /><br /></span>';

			$echo .='<span class="shippostcode"><label for="ship_postcode">'.__('Zip/Post Code','eshop').'</label>
			  <input class="short" type="text" name="ship_postcode" id="ship_postcode" value="'.$ship_postcode.'" maxlength="20" size="20" />
			  <br /></span>
			<span class="shipcountry"><label for="shipcountry">'.__('Country','eshop').'</label>
			  <select class="med pointer" name="ship_country" id="shipcountry">
			';
			$echo .='<option value="" selected="selected">'.__('Select your Country','eshop').'</option>';
			foreach($countryList as $code => $label){
				$label=htmlspecialchars($label);
				if (isset($ship_country) && $ship_country == $code){
					$echo.= "<option value=\"$code\" selected=\"selected\">$label</option>\n";
				}else{
					$echo.="<option value=\"$code\">$label</option>";
				}
			}
			$echo.= "</select></span>";
			$echo .='</fieldset>';
		}
	}
	$final_price=number_format($_SESSION['final_price'.$blog_id], 2,'.','');
	$discounttotal=0;
	if(eshop_discount_codes_check()){
		$eshop_discount='';
		if(isset($_POST['eshop_discount'])) $eshop_discount=$_POST['eshop_discount'];
		//for display:
		$eshop_discount=esc_html((stripslashes(trim($eshop_discount))),'1');
		$echo .='<fieldset class="eshop fld5"><legend><label for="eshop_discount">'.__('Discount Code','eshop').'</label></legend>
	  	<input class="med" type="text" name="eshop_discount" value="'.$eshop_discount.'" id="eshop_discount" size="40" /></fieldset>'."\n";
	}
	if(is_array($eshopoptions['method'])){
		$i=1;
		$eshopfiles=eshop_files_directory();
		$echo .='<fieldset class="eshop fld6 eshoppayvia"><legend>'.__('Pay Via', 'eshop').eshop_checkreqd($reqdarray,'pay').'</legend>'."\n";
		$echo = apply_filters('eshopaddtocheckoutpayvia',$echo);
		$echo .= "<ul>\n";
		$eshop_paymentx='';
		if(isset($_POST['eshop_payment'])) $eshop_paymentx = $_POST['eshop_payment'];
		if(sizeof((array)$eshopoptions['method'])!=1){
			foreach($eshopoptions['method'] as $k=>$eshoppayment){
				$replace = array(".");
				$eshoppayment = str_replace($replace, "", $eshoppayment);
				$eshoppayment_text=$eshoppayment;
				if($eshoppayment_text=='cash'){
					$eshopcash = $eshopoptions['cash'];
					if($eshopcash['rename']!='')
						$eshoppayment_text=$eshopcash['rename'];
				}
				if($eshoppayment_text=='bank'){
					$eshopbank = $eshopoptions['bank'];
					if($eshopbank['rename']!='')
						$eshoppayment_text=$eshopbank['rename'];
				}
				$eshopmi=apply_filters('eshop_merchant_img_'.$eshoppayment,array('path'=>$eshopfiles['0'].$eshoppayment.'.png','url'=>$eshopfiles['1'].$eshoppayment.'.png'));
				$eshopmerchantimgpath=$eshopmi['path'];
				$eshopmerchantimgurl=$eshopmi['url'];
				$dims=array('3'=>'');
				if(file_exists($eshopmerchantimgpath))
					$dims=getimagesize($eshopmerchantimgpath);
				$echo .='<li><input class="rad" type="radio" name="eshop_payment" value="'.$eshoppayment.'" id="eshop_payment'.$i.'"'.checked($eshop_paymentx,$eshoppayment,false).' /><label for="eshop_payment'.$i.'"><img src="'.$eshopmerchantimgurl.'" '.$dims[3].' alt="'.__('Pay via','eshop').' '.$eshoppayment_text.'" title="'.__('Pay via','eshop').' '.$eshoppayment_text.'" /></label></li>'."\n";
				$i++;
			}
		}else{
			foreach($eshopoptions['method'] as $k=>$eshoppayment){
				$replace = array(".");
				$eshoppayment = str_replace($replace, "", $eshoppayment);
				$eshoppayment_text=$eshoppayment;
				if($eshoppayment_text=='cash'){
					$eshopcash = $eshopoptions['cash'];
					if($eshopcash['rename']!='')
						$eshoppayment_text=$eshopcash['rename'];
				}
				if($eshoppayment_text=='bank'){
					$eshopbank = $eshopoptions['bank'];
					if($eshopbank['rename']!='')
						$eshoppayment_text=$eshopbank['rename'];
				}
				$eshopmi=apply_filters('eshop_merchant_img_'.$eshoppayment,array('path'=>$eshopfiles['0'].$eshoppayment.'.png','url'=>$eshopfiles['1'].$eshoppayment.'.png'));
				$eshopmerchantimgpath=$eshopmi['path'];
				$eshopmerchantimgurl=$eshopmi['url'];
				$dims='';
				if(file_exists($eshopmerchantimgpath))
					$dims=getimagesize($eshopmerchantimgpath);
				$echo .='<li><img src="'.$eshopmerchantimgurl.'" '.$dims[3].' alt="'.__('Pay via','eshop').' '.$eshoppayment_text.'" title="'.__('Pay via','eshop').' '.$eshoppayment_text.'" /><input type="hidden" name="eshop_payment" value="'.$eshoppayment.'" id="eshop_payment'.$i.'" /></li>'."\n";
				$i++;
			}
		}
		$echo .="</ul>\n";
		$echo .= eshopCartFields();	
		$echo .="</fieldset>\n";
	}
	if('yes' == $eshopoptions['tandc_use']){
		if($eshopoptions['tandc_id']!='')
			$eshoptc='<a href="'.get_permalink($eshopoptions['tandc_id']).'"'.$linkattr.'>'.$eshopoptions['tandc'].'</a>';
		else
			$eshoptc=$eshopoptions['tandc'];

		$echo .='<p class="eshop_tandc"><input type="checkbox" name="eshop_tandc" id="eshop_tandc" value="1" /><label for="eshop_tandc">'.$eshoptc.'<span class="reqd">*</span></label></p>';
	}
	if(isset($eshopoptions['users']) && $eshopoptions['users']=='yes' && !is_user_logged_in()){
			if(isset($eshopoptions['users_text']) && $eshopoptions['users_text']!='')
				$edisplay=$eshopoptions['users_text'];
			else
				$edisplay=__('Sign me up to the site so I can view my orders.','eshop');
			$echo .='<p class="eshop_users"><input type="checkbox" name="eshop_users" id="eshop_users" value="1" /><label for="eshop_users">'.$edisplay.eshop_checkreqd($reqdarray,'signup').'</label></p>';
	}
	if('no' == $eshopoptions['downloads_only']){
			$echo .='<label for="submitit"><small id="eshopshowshipcost">'.__('<strong>Note:</strong> Submit to show shipping charges.','eshop').'</small></label><br />';
	}
		
	$echo .= '<input type="hidden" name="amount" value="'.$final_price.'" />';

	$echo .='<span class="buttonwrap"><input type="submit" class="button" id="submitit" name="submit" value="'.__('Proceed to Confirmation &raquo;','eshop').'" /></span>
	</fieldset>
	</form>
	</div>
	';
	if(get_bloginfo('version')<'2.5.1')
		remove_filter('the_content', 'wpautop');
		
	return $echo;
	}
}
if (!function_exists('eshop_checkout')) {
 	function eshop_checkout($espost){
 		$espost=stripslashes_deep($espost);
 		global $blog_id,$eshopoptions,$wpdb;
 		$contineproceed='1';
 		//cache
		eshop_cache();
		$echoit='';
		include_once(ABSPATH.'wp-includes/wp-db.php');
		include_once WP_PLUGIN_DIR."/eshop/cart-functions.php";
		if(isset($espost['eshop_payment']))
			$_SESSION['eshop_payment'.$blog_id]=preg_replace('/[^a-zA-Z0-9\-_]/','',$espost['eshop_payment']);
			
		if(!isset($_SESSION['eshop_payment'.$blog_id])){
			$paymentmethod='paypal';
		}else{
			$paymentmethod=$_SESSION['eshop_payment'.$blog_id];
		}
		//left over from previous script, leaving in just in case another payment method is used.
		$chkerror=0;
		$numberofproducts=0;
		//filter for plugin merchant gateways
		$eshopmgincpath=apply_filters('eshop_mg_inc_path',WP_PLUGIN_DIR.'/eshop/'.$paymentmethod.'.php',$paymentmethod);
		// if everything went ok do the following, hopefully the rest won't happen!
		if(isset($_GET['eshopaction'])){
			if($_GET['eshopaction']=='success'){
				include_once($eshopmgincpath);
			}
		}
		//filter for plugin merchant gateways
		$eshopmgincidxpath=apply_filters('eshop_mg_inc_idx_path',WP_PLUGIN_DIR.'/eshop/'.$paymentmethod.'/index.php',$paymentmethod);
		if(file_exists($eshopmgincidxpath))
			include_once($eshopmgincidxpath);

		if(isset($_SESSION['eshopcart'.$blog_id])){
			$shopcart=$_SESSION['eshopcart'.$blog_id];
			$numberofproducts=sizeof($_SESSION['eshopcart'.$blog_id]);
			$keys = array_keys($_SESSION['eshopcart'.$blog_id]);
			$productidkeys=implode(",", $keys);
			$productidkeys=trim($productidkeys);
			//reqd for shipping - finds the correct state for working out shipping, and set things up for later usage.
			if(isset($espost['ship_name'])){
				if($espost['ship_name']!='' || $espost['ship_address']!='' 
				|| $espost['ship_city']!='' || $espost['ship_postcode']!=''
				|| $espost['ship_company']!='' || $espost['ship_phone']!=''
				|| $espost['ship_country']!='' || $espost['ship_state']!=''){
				if($espost['ship_name']==''){
					$espost['ship_name']=$espost['first_name']." ".$espost['last_name'];
				}
				if($espost['ship_company']==''){
					$espost['ship_company']=$espost['company'];
				}
				if($espost['ship_phone']==''){
					$espost['ship_phone']=$espost['phone'];
				}
				if($espost['ship_address']==''){
					$espost['ship_address']=$espost['address1'];
					if($espost['address2']!=''){
						$espost['ship_address'].=", ".$espost['address2'];
					}
				}
				if($espost['ship_city']==''){
					$espost['ship_city']=$espost['city'];
				}
				if($espost['ship_postcode']==''){
					$espost['ship_postcode']=$espost['zip'];
				}
				if($espost['ship_country']==''){
					$espost['ship_country']=$espost['country'];
				}
				if($espost['ship_state']==''){
					$espost['ship_state']=$espost['state'];
				}
				if($espost['ship_altstate']==''){
					$espost['ship_altstate']=$espost['altstate'];
				}
			}else{
				$espost['ship_name']=$espost['first_name']." ".$espost['last_name'];
				$espost['ship_company']=$espost['company'];
				$espost['ship_phone']=$espost['phone'];
				if($espost['ship_address']==''){
					$espost['ship_address']=$espost['address1'];
					if($espost['address2']!=''){
						$espost['ship_address'].=", ".$espost['address2'];
					}
				}
				$espost['ship_city']=$espost['city'];
				$espost['ship_postcode']=$espost['zip'];
				$espost['ship_country']=$espost['country'];
				$espost['ship_state']=$espost['state'];
				$espost['ship_altstate']=$espost['altstate'];
			}
			$tablecountries=$wpdb->prefix.'eshop_countries';
			$tablestates=$wpdb->prefix.'eshop_states';
			$shippingzone=$eshopoptions['shipping_zone'];
			if(isset($espost['eshop_shiptype']) && $espost['eshop_shiptype'] != '0'){
				$sztype=$espost['eshop_shiptype'];
				$shippingzone=$wpdb->get_var("SELECT area FROM ".$wpdb->prefix."eshop_rates WHERE rate_type='ship_weight' && class='$sztype' LIMIT 1");
			}
			$pzoneid='';//$eshopoptions['unknown_state'];
			if($shippingzone=='country'){
				if(isset($espost['ship_country']) && $espost['ship_country']!=''){
					$pzoneid=$espost['ship_country'];
				}elseif(isset($espost['country']) && $espost['country']!=''){
					$pzoneid=$espost['country'];
				}
				$pzone=$wpdb->get_var("SELECT zone FROM $tablecountries WHERE code='$pzoneid' LIMIT 1");

			}else{
				if(isset($espost['state']) && $espost['state']!=''){
					$pzoneid=$espost['state'];
				}
				if(isset($espost['ship_state']) && $espost['ship_state']!=''){
					$pzoneid=$espost['ship_state'];
				}
				$pzone=$wpdb->get_var("SELECT zone FROM $tablestates WHERE id='$pzoneid' LIMIT 1");
				if(isset($espost['altstate']) && $espost['altstate']!=''){
					$pzone=$eshopoptions['unknown_state'];
				}
				if(isset($espost['ship_altstate']) && $espost['ship_altstate']!=''){
					$pzone=$eshopoptions['unknown_state'];
				}
			}
			
			$_SESSION['shiptocountry'.$blog_id] = $eshopoptions['location'];
			if(isset($espost['ship_country']) && $espost['ship_country']!=''){
				$_SESSION['shiptocountry'.$blog_id] = $espost['ship_country'];
			}elseif(isset($espost['country']) && $espost['country']!=''){
				$_SESSION['shiptocountry'.$blog_id] = $espost['country'];
			}
		}else{
			$pzoneid='';//$eshopoptions['unknown_state'];
			$tablecountries=$wpdb->prefix.'eshop_countries';
			$tablestates=$wpdb->prefix.'eshop_states';
			$shippingzone=$eshopoptions['shipping_zone'];
			if(isset($espost['eshop_shiptype'])){
				$sztype=$espost['eshop_shiptype'];
				$shippingzone=$wpdb->get_var("SELECT area FROM ".$wpdb->prefix."eshop_rates WHERE rate_type='ship_weight' && class='$sztype' LIMIT 1");
			}
			if($shippingzone=='country'){
				if(isset($espost['ship_country']) && $espost['ship_country']!=''){
					$pzoneid=$espost['ship_country'];
				}elseif(isset($espost['country']) && $espost['country']!=''){
					$pzoneid=$espost['country'];
				}
				$pzone=$wpdb->get_var("SELECT zone FROM $tablecountries WHERE code='$pzoneid' LIMIT 1");

			}else{
				if(isset($espost['ship_state']) && $espost['ship_state']!=''){
					$pzoneid=$espost['ship_state'];
				}
				if(isset($espost['state']) && $espost['state']!=''){
					$pzoneid=$espost['state'];
				}
				$pzone=$wpdb->get_var("SELECT zone FROM $tablestates WHERE id='$pzoneid' LIMIT 1");
				if(isset($espost['altstate']) && $espost['altstate']!=''){
					$pzone=$eshopoptions['unknown_state'];
				}
				if(isset($espost['ship_altstate']) && $espost['ship_altstate']!=''){
					$pzone=$eshopoptions['unknown_state'];
				}
			}
		}
		//
		$shiparray=array();
		$eshopcartarray=$_SESSION['eshopcart'.$blog_id];
		foreach ($eshopcartarray as $productid => $opt){
			if(is_array($opt)){
				switch($eshopoptions['shipping']){
					case '1'://( per quantity of 1, prices reduced for additional items )
						for($i=1;$i<=$opt['qty'];$i++){
							array_push($shiparray, $opt["pclas"]);
						}
						break;
					case '2'://( once per shipping class no matter what quantity is ordered )
						if(!in_array($opt["pclas"], $shiparray)) {
							array_push($shiparray, $opt["pclas"]);
						}
						break;
					case '3'://( one overall charge no matter how many are ordered )
						if(!in_array($opt["pclas"], $shiparray)) {
							if($opt["pclas"]!='F'){
								array_push($shiparray, 'A');
							}
						}
						break;

					case '4'://( weight )
						if(isset($espost['eshop_shiptype'])){
							unset ($shiparray);
							$shiparray=$espost['eshop_shiptype'];
						}
						break;
				}
			}
		}
		//need an extra check
		if($eshopoptions['shipping']=='4' && 'no' == $eshopoptions['downloads_only'] && isset($espost['submit']) && !isset($espost['eshop_shiptype']) && !eshop_only_downloads()){
			$pzone='';
		}
		//need to check the discount codes here as well:
		if(eshop_discount_codes_check()){
			$_SESSION['eshop_discount'.$blog_id]='';
			unset($_SESSION['eshop_discount'.$blog_id]);
			if(isset($espost['eshop_discount']) && $espost['eshop_discount']!=''){
				$chkcode=valid_eshop_discount_code($espost['eshop_discount']);
				if($chkcode)
					$_SESSION['eshop_discount'.$blog_id]=$espost['eshop_discount'];
			}
		}
		//show the cart
		if((isset($_GET['eshopaction']) && $_GET['eshopaction']!='redirect')||!isset($_GET['eshopaction'])){
			$echoit.= display_cart($_SESSION['eshopcart'.$blog_id], false,$eshopoptions['checkout'],$pzone,$shiparray);
		}
	}
	$error='';

	if (isset ($espost['submit'])) {
		//form handling
		foreach($espost as $key=>$value) {
			$key = $value;
		}
		//setupshipping arrays
		$reqdvalues=array('shipping','first_name','last_name','email','phone','address','city','zip','pay');

		if($eshopoptions['shipping']!='4'){
			if($eshopoptions['shipping_zone']=='country'){
				$reqdvalues[]='country';
			}else{
				$reqdvalues[]='state';
			}
		}else{
			$creqd='';
			$dtable=$wpdb->prefix.'eshop_rates';
			$query=$wpdb->get_results("SELECT DISTINCT(area) from $dtable where rate_type='ship_weight'");
			foreach($query as $k)
				$reqdvalues[]=$k->area;
		}
		$linkattr=apply_filters('eShopCheckoutLinksAttr','');
			
		$reqdarray=apply_filters('eshopCheckoutReqd', $reqdvalues );

		if($eshopoptions['shipping']=='4' && 'no' == $eshopoptions['downloads_only'] && !isset($espost['eshop_shiptype']) && !eshop_only_downloads()){
			$error.= '<li>'.__('<strong>Shipping</strong> - not selected.','eshop').'</li>';
		}
		if(isset($espost['first_name'])){
			$valid=checkAlpha($espost['first_name']);
			if($valid==FALSE && eshop_checkreqd($reqdarray,'first_name')){
				$error.= '<li>'.__('<strong>First name</strong> - missing or incorrect.','eshop').'</li>';
			}
		}
		if(isset($espost['last_name'])){
				$valid=checkAlpha($espost['last_name']);
				if($valid==FALSE && eshop_checkreqd($reqdarray,'last_name')) {
					$error.= '<li>'.__('<strong>Last name</strong> - missing or incorrect.','eshop').'</li>';
				}
		}
		if(isset($espost['email'])){
				$valid=checkEmail($espost['email']);
				if($valid==FALSE && eshop_checkreqd($reqdarray,'email')){
					$error.= '<li>'.__('<strong>Email address</strong> - missing or incorrect.','eshop').'</li>';
				}
		}
		if(isset($espost['phone'])){
				$valid=checkPhone($espost['phone']);
				if($valid==FALSE && eshop_checkreqd($reqdarray,'phone')){
					$error.= '<li>'.__('<strong>Phone Number</strong> - missing or incorrect','eshop').'.</li>';
				}
		}
		if(isset($espost['address1'])){
				$valid=checkAlpha($espost['address1']);
				if($valid==FALSE && eshop_checkreqd($reqdarray,'address')){
					$error.= '<li>'.__('<strong>Address</strong> - missing or incorrect.','eshop').'</li>';
				}
		}
		if(isset($espost['city'])){
				$valid=checkAlpha($espost['city']);
				if($valid==FALSE && eshop_checkreqd($reqdarray,'city')){
					$error.= '<li>'.__('<strong>City or town</strong> - missing or incorrect.','eshop').'</li>';
				}
		}
		
		if(eshop_checkreqd($reqdarray,'state')){
			if(isset($espost['state']) && $espost['state']=='' &&  $espost['altstate']==''){
				$error.= '<li>'.__('<strong>State/County/Province</strong> - missing or incorrect.','eshop').'</li>';
			}
		}
		if(eshop_checkreqd($reqdarray,'country')){
			if(isset($espost['country'])){
				$valid=checkAlpha($espost['country']);
				if($valid==FALSE){
					$error.= '<li>'.__('<strong>Country</strong> - missing or incorrect.','eshop').'</li>';
				}
			}
		}
		if(isset($espost['country']) && $espost['country']=='US' && $espost['state']=='' && $espost['altstate']==''){
			//must pick a state for US deliveries
				$error.= '<li>'.__('<strong><abbr title="United States">US</abbr> State</strong> - missing or incorrect.','eshop').'</li>';
		}
		if(isset($espost['zip'])){
				$valid=checkAlphaNum($espost['zip']);
				if($valid==FALSE && eshop_checkreqd($reqdarray,'zip')){
					$error.= '<li>'.__('<strong>Zip/Post code</strong> - missing or incorrect.','eshop').'</li>';
				}
		}
		if(isset($espost['reference'])){
				if($espost['reference']=='' && eshop_checkreqd($reqdarray,'ref')){
					$error.= '<li>'.__('<strong>Reference</strong> - missing.','eshop').'</li>';
				}
		}
		if(isset($espost['comments'])){
				if($espost['comments']=='' && eshop_checkreqd($reqdarray,'comments')){
					$error.= '<li>'.__('<strong>Comments</strong> - missing.','eshop').'</li>';
				}
		}
		if(isset($eshopoptions['users']) && $eshopoptions['users']=='yes' && !is_user_logged_in()){
			if(!isset($espost['eshop_users']) && eshop_checkreqd($reqdarray,'signup')){
				$error.= '<li>'.__('<strong>Sign Up</strong> - not checked.','eshop').'</li>';
			}
		}

		//add in error checking for any new values here
		$temperror=apply_filters('eshoperrorcheckout',$espost);
		if(!is_array($temperror)) $error .= $temperror;
		
		////////////////////////////////////////////////
		if('yes' == $eshopoptions['tandc_use']){
			if(!isset($espost['eshop_tandc'])){
				$error.= '<li><strong>'.$eshopoptions['tandc'].'</strong>'.__(' - not checked.','eshop').'</li>';
			}
		}
			
		if(!isset($espost['eshop_payment'])){
			$error.= '<li>'.__('You have not chosen a <strong>payment option</strong>.','eshop').'</li>';
		}
		if(eshop_discount_codes_check()){
			$_SESSION['eshop_discount'.$blog_id]='';
			unset($_SESSION['eshop_discount'.$blog_id]);
			if(isset($espost['eshop_discount']) && $espost['eshop_discount']!=''){
				$chkcode=valid_eshop_discount_code($espost['eshop_discount']);
				if(!$chkcode)
					$error.= '<li>'.__('<strong>Discount Code</strong> - is not valid.','eshop').'</li>';
				else
					$_SESSION['eshop_discount'.$blog_id]=$espost['eshop_discount'];
			}
		}
		if(isset($eshopoptions['users']) && $eshopoptions['users']=='yes' && isset($espost['eshop_users']) && !is_user_logged_in()){
			$_SESSION['eshop_user'.$blog_id]='1';
		}

		if($error!=''){
				$echoit.= "<p><strong class=\"eshoperror error\">".__('There were some errors in the details you entered&#8230;','eshop')."</strong></p><ul class=\"eshoperrors errors\">".$error.'</ul>';
				$first_name=$last_name=$company=$phone=$reference='';
				$email=$address1=$address2=$city=$country='';
				$state=$altstate=$zip=$ship_name=$ship_company='';
				$ship_phone=$ship_address=$ship_city=$ship_postcode='';
				$ship_country=$ship_state=$ship_altstate=$comments='';
				if(isset($espost['first_name']))
					$first_name=$espost['first_name'];
				if(isset($espost['last_name']))
					$last_name=$espost['last_name'];
				if(isset($espost['phone']))
					$phone=$espost['phone'];
				if(isset($espost['reference']))
					$reference=$espost['reference'];
				if(isset($espost['email']))
					$email=$espost['email'];
				if(isset($espost['address1']))
					$address1=$espost['address1'];
				if(isset($espost['address2']))
					$address2=$espost['address2'];
				if(isset($espost['city']))
					$city=$espost['city'];
				if(isset($espost['country']))
					$country=$espost['country'];
				if(isset($espost['state']))
					$state=$espost['state'];
				if(isset($espost['altstate']))
					$altstate=$espost['altstate'];
				if(isset($espost['zip']))
					$zip=$espost['zip'];
				if(isset($espost['ship_name']))
					$ship_name=$espost['ship_name'];
				if(isset($espost['ship_company']))
					$ship_company=$espost['ship_company'];
				if(isset($espost['ship_phone']))
					$ship_phone=$espost['ship_phone'];
				if(isset($espost['ship_address']))
					$ship_address=$espost['ship_address'];
				if(isset($espost['ship_city']))
					$ship_city=$espost['ship_city'];
				if(isset($espost['ship_country']))
					$ship_country=$espost['ship_country'];
				if(isset($espost['ship_state']))
					$ship_state=$espost['ship_state'];
				if(isset($espost['ship_altstate']))
					$ship_altstate=$espost['ship_altstate'];
				if(isset($espost['ship_postcode']))
					$ship_postcode=$espost['ship_postcode'];
				if(isset($espost['comments']))
					$comments=$espost['comments'];
				$chkerror='1';
		}else{
			if(!isset($_GET['eshopaction'])){
				$shipping=0;
				$echoit.= "<div class=\"hr\"></div><div class=\"eshopcheckoutconf\"><h3>".__('<span class="noprint">Please Confirm </span>Your Details','eshop').'</h3>';
				// create a custom id, and shove details in database
				$date=date('YmdHis');
				$_SESSION['date'.$blog_id]=$date;
				$fprice=number_format($_SESSION['final_price'.$blog_id], 2,'.','');

				$espost['amount']=$fprice;
				$espost['custom']=$date;
				$espost['numberofproducts']=sizeof($_SESSION['eshopcart'.$blog_id]);

				//shipping
				if(isset($_SESSION['shipping'.$blog_id]))$shipping=eshopShipTaxAmt();
				//discount shipping
				if(is_shipfree(calculate_total())) $shipping=0;
				//shipping
				$espost['shipping_1']=$shipping;
				$ctable=$wpdb->prefix.'eshop_countries';
				$stable=$wpdb->prefix.'eshop_states';
				if('no' == $eshopoptions['downloads_only']){
					$echoit.='<h4>'.__('Mailing Address','eshop').'</h4><ul class="eshop confirm">';
				}else{
					$echoit.='<h4>'.__('Contact Details','eshop').'</h4><ul class="eshop confirm">';
				}
				$echoit.= "<li><span class=\"items fullname\">".__('Full name:','eshop')."</span> ".$espost['first_name']." ".$espost['last_name']."</li>\n";
				if('no' == $eshopoptions['downloads_only']){
					$echoit.= "<li class=\"company\"><span class=\"items\">".__('Company:','eshop')."</span> ".$espost['company']."</li>\n";
				}
				$echoit.= "<li class=\"email\"><span class=\"items\">".__('Email:','eshop')."</span> ".$espost['email']."</li>\n";
				if('no' == $eshopoptions['downloads_only']){
					$echoit.= "<li class=\"phone\"><span class=\"items\">".__('Phone:','eshop')."</span> ".$espost['phone']."</li>\n";
					$echoit.= "<li class=\"address\"><span class=\"items\">".__('Address:','eshop')."</span> ".$espost['address1']." ".$espost['address2']."</li>\n";
					$echoit.= "<li class=\"city\"><span class=\"items\">".__('City or town:','eshop')."</span> ".$espost['city']."</li>\n";
					$qcode=$wpdb->escape($espost['state']);
					$qstate = $wpdb->get_var("SELECT stateName FROM $stable WHERE id='$qcode' limit 1");
					if($espost['altstate']!='')
						$echoit.= "<li class=\"state\"><span class=\"items\">".__('State/County/Province:','eshop')."</span> ".$espost['altstate']."</li>\n";
					elseif($qstate!='')
						$echoit.= "<li class=\"state\"><span class=\"items\">".__('State/County/Province:','eshop')."</span> ".$qstate."</li>\n";
										
					$echoit.= "<li class=\"zip\"><span class=\"items\">".__('Zip/Post code:','eshop')."</span> ".$espost['zip']."</li>\n";
					$qccode=$wpdb->escape($espost['country']);
					$qcountry = $wpdb->get_var("SELECT country FROM $ctable WHERE code='$qccode' limit 1");
					$echoit.= "<li class=\"country\"><span class=\"items\">".__('Country:','eshop')."</span> ".$qcountry."</li>\n";
				}
				$echoit.= "</ul>\n";
				//
				$temp=apply_filters('eshopconfcheckout',$espost);
				if(!is_array($temp)) $echoit .= $temp;
				//
				if(!isset($espost['reference'])) $espost['reference']='';
				if(!isset($espost['comments'])) $espost['comments']='';
				if( (trim($espost['reference'])!='') && trim($espost['comments'])==''){
					$echoit.= "<div class=\"eshop fld3\"><h4>".__('Additional information','eshop')."</h4>\n<ul class=\"eshop confirmref\">\n";
					$echoit.= '<li><span class="items">'.__('Reference or <abbr title="Purchase Order number">PO</abbr>','eshop').'</span> '.$espost['reference'].'</li>'."\n";
					$echoit.= '</ul></div>'."\n";
				}
				if( (trim($espost['reference'])=='') && trim($espost['comments'])!=''){
					$echoit.= "<div class=\"eshop fld3\"><h4>".__('Additional information','eshop')."</h4>\n<ul class=\"eshop confirmref\">\n";
					$echoit.= '<li><span class="items">'.__('Comments or instructions:','eshop').'</span> '.$espost['comments'].'</li>'."\n";
					$echoit.= '</ul></div>'."\n";
				}
				if( (trim($espost['reference'])!='') && trim($espost['comments'])!=''){
					$echoit.= "<div class=\"eshop fld3\"><h4>".__('Additional information','eshop')."</h4>\n<ul class=\"eshop confirmref\">\n";
					$echoit.= '<li><span class="items">'.__('Reference or PO:','eshop').'</span> '.$espost['reference'].'</li>'."\n";
					$echoit.= '<li><span class="items">'.__('Comments or instructions:','eshop').'</span> '.$espost['comments'].'</li>'."\n";
					$echoit.= '</ul></div>'."\n";
				}
				if('no' == $eshopoptions['downloads_only']){
					if('yes' != $eshopoptions['hide_shipping']){
						if($espost['ship_name']!='' || $espost['ship_address']!='' || $espost['ship_city']!='' || $espost['ship_postcode']!=''){
							$echoit.= "<div class=\"eshop fld4\"><h4>".__('Shipping Address','eshop')."</h4>\n<ul class=\"eshop confirmship\">\n";
							$echoit.= "<li><span class=\"items\">".__('Full name:','eshop')."</span> ".$espost['ship_name']."</li>\n";
							$echoit.= "<li class=\"ship_company\"><span class=\"items\">".__('Company:','eshop')."</span> ".$espost['ship_company']."</li>\n";
							$echoit.= "<li><span class=\"items\">".__('Phone:','eshop')."</span> ".$espost['ship_phone']."</li>\n";
							$echoit.= "<li><span class=\"items\">".__('Address:','eshop')."</span> ".$espost['ship_address']."</li>\n";
							$echoit.= "<li><span class=\"items\">".__('City or town:','eshop')."</span> ".$espost['ship_city']."</li>\n";
							$qcode=$wpdb->escape($espost['ship_state']);
							$qstate = $wpdb->get_var("SELECT stateName FROM $stable WHERE id='$qcode' limit 1");
							if($espost['ship_altstate']!='')
								$echoit.= "<li class=\"ship_state\"><span class=\"items\">".__('State/County/Province:','eshop')."</span> ".$espost['ship_altstate']."</li>\n";
							elseif($qstate!='')
								$echoit.= "<li class=\"ship_state\"><span class=\"items\">".__('State/County/Province:','eshop')."</span> ".$qstate."</li>\n";

							$echoit.= "<li><span class=\"items\">".__('Zip/Post code:','eshop')."</span> ".$espost['ship_postcode']."</li>\n";
							$qccode=$wpdb->escape($espost['ship_country']);
							$qcountry = $wpdb->get_var("SELECT country FROM $ctable WHERE code='$qccode' limit 1");
							$echoit.= "<li class=\"shipcountry\"><span class=\"items\">".__('Country:','eshop')."</span> ".$qcountry."</li>\n";
							$echoit.= "</ul></div>\n";
						}
					}
				}
				$echoit.= "\n";
				$echoit .= "</div>\n";
			}
			
			//add to a session to store address:
			$_SESSION['addy'.$blog_id]['first_name']=$espost['first_name'];
			$_SESSION['addy'.$blog_id]['last_name']=$espost['last_name'];
			$_SESSION['addy'.$blog_id]['email']=$espost['email'];
			if(isset($espost['company']))
				$_SESSION['addy'.$blog_id]['company']=$espost['company'];
			else
				$_SESSION['addy'.$blog_id]['company']='';
			if(isset($espost['phone']))
				$_SESSION['addy'.$blog_id]['phone']=$espost['phone'];
			else
				$_SESSION['addy'.$blog_id]['phone']='';
			if(isset($espost['reference']))
				$_SESSION['addy'.$blog_id]['reference']=$espost['reference'];
			else
				$_SESSION['addy'.$blog_id]['reference']='';
			if(isset($espost['address1']))
				$_SESSION['addy'.$blog_id]['address1']=$espost['address1'];
			else
				$_SESSION['addy'.$blog_id]['address1']='';
			if(isset($espost['address2']))
				$_SESSION['addy'.$blog_id]['address2']=$espost['address2'];
			else
				$_SESSION['addy'.$blog_id]['address2']='';
			if(isset($espost['city']))
				$_SESSION['addy'.$blog_id]['city']=$espost['city'];
			if(isset($espost['country']))
				$_SESSION['addy'.$blog_id]['country']=$espost['country'];
			else
				$_SESSION['addy'.$blog_id]['country']='';
			if(isset($espost['state']))
				$_SESSION['addy'.$blog_id]['state']=$espost['state'];
			else
				$_SESSION['addy'.$blog_id]['state']='';
		
			if(isset($espost['altstate']) && $espost['altstate']!='')
				$_SESSION['addy'.$blog_id]['state']=$espost['altstate'];
			if(isset($espost['zip']))
				$_SESSION['addy'.$blog_id]['zip']=$espost['zip'];
			else
				$_SESSION['addy'.$blog_id]['zip']='';
			if(isset($espost['ship_name'])){
				$_SESSION['addy'.$blog_id]['ship_name']=$espost['ship_name'];
				$_SESSION['addy'.$blog_id]['ship_company']=$espost['ship_company'];
				$_SESSION['addy'.$blog_id]['ship_phone']=$espost['ship_phone'];
				$_SESSION['addy'.$blog_id]['ship_address']=$espost['ship_address'];
				$_SESSION['addy'.$blog_id]['ship_city']=$espost['ship_city'];
				$_SESSION['addy'.$blog_id]['ship_country']=$espost['ship_country'];
				$_SESSION['addy'.$blog_id]['ship_state']=$espost['ship_state'];
				if(isset($espost['ship_altstate']) && $espost['ship_altstate']!='')
					$_SESSION['addy'.$blog_id]['ship_state']=$espost['ship_altstate'];
				$_SESSION['addy'.$blog_id]['ship_postcode']=$espost['ship_postcode'];
			}
			if(isset($espost['comments']))
				$_SESSION['addy'.$blog_id]['comments']=$espost['comments'];
			else
				$_SESSION['addy'.$blog_id]['comments']='';
			
			if(!isset($_SESSION['shipping'.$blog_id]))$_SESSION['shipping'.$blog_id]['cost']=$shipping;

			//grab all the POST variables and store in cookie
			$array=$espost;
			//but first make a few extra equal nothing
			//add others in here if needed
			$array['comments']=$array['reference']='';
			$eshopsetcookie=apply_filters('eshop_use_cookie',true);
			if($eshopsetcookie==true){
				$biscuits=eshop_build_cookie($array);
				setcookie("eshopcart", $biscuits,time()+60*60*24*365);
			}
			$eshopmgincpath=apply_filters('eshop_mg_inc_path',WP_PLUGIN_DIR.'/eshop/'.$paymentmethod.'.php',$paymentmethod);
			include_once($eshopmgincpath);
			$contineproceed='3';
		}
	}else{
		//for first time form usage.
		if(isset($_SESSION['addy'.$blog_id])){
			$first_name=$_SESSION['addy'.$blog_id]['first_name'];
			$last_name=$_SESSION['addy'.$blog_id]['last_name'];
			$company=$_SESSION['addy'.$blog_id]['company'];
			$phone=$_SESSION['addy'.$blog_id]['phone'];
			$reference=$_SESSION['addy'.$blog_id]['reference'];
			$email=$_SESSION['addy'.$blog_id]['email'];
			$address1=$_SESSION['addy'.$blog_id]['address1'];
			$address2=$_SESSION['addy'.$blog_id]['address2'];
			$city='';
			if(isset($_SESSION['addy'.$blog_id]['city']))
				$city=$_SESSION['addy'.$blog_id]['city'];
			$country=$_SESSION['addy'.$blog_id]['country'];
			$state=$_SESSION['addy'.$blog_id]['state'];
			if (!is_numeric($state) ){
				$li = $wpdb->escape($state);
				$table=$wpdb->prefix.'eshop_states';
				$stateList = $wpdb->get_var("SELECT id FROM $table WHERE code='$li' limit 1");
			    $state = $stateList;
			}
			
			if(isset($_SESSION['addy'.$blog_id]['altstate']))
				$altstate=$_SESSION['addy'.$blog_id]['altstate'];
			else
				$altstate='';
			$zip=$_SESSION['addy'.$blog_id]['zip'];
			/* defaults */
			$ship_name=$ship_company='';
			$ship_phone=$ship_address=$ship_city=$ship_postcode='';
			$ship_country=$ship_state=$ship_altstate=$comments='';
			if(isset($_SESSION['addy'.$blog_id]['ship_name']))
				$ship_name=$_SESSION['addy'.$blog_id]['ship_name'];
			if(isset($_SESSION['addy'.$blog_id]['ship_company']))
				$ship_company=$_SESSION['addy'.$blog_id]['ship_company'];
			if(isset($_SESSION['addy'.$blog_id]['ship_phone']))
				$ship_phone=$_SESSION['addy'.$blog_id]['ship_phone'];
			if(isset($_SESSION['addy'.$blog_id]['ship_address']))
				$ship_address=$_SESSION['addy'.$blog_id]['ship_address'];
			if(isset($_SESSION['addy'.$blog_id]['ship_city']))
				$ship_city=$_SESSION['addy'.$blog_id]['ship_city'];
			if(isset($_SESSION['addy'.$blog_id]['ship_country']))
				$ship_country=$_SESSION['addy'.$blog_id]['ship_country'];
			if(isset($_SESSION['addy'.$blog_id]['ship_state'])){
				$ship_state=$_SESSION['addy'.$blog_id]['ship_state'];
				if (!is_numeric($ship_state) ){
					$li = $wpdb->escape($ship_state);
					$table=$wpdb->prefix.'eshop_states';
					$stateSList = $wpdb->get_var("SELECT id FROM $table WHERE code='$li' limit 1");
					$ship_state = $stateSList;
				}
			}
			
			if(isset($_SESSION['addy'.$blog_id]['ship_altstate']))
				$ship_altstate=$_SESSION['addy'.$blog_id]['ship_altstate'];
			else
				$ship_altstate='';
			if(isset($_SESSION['addy'.$blog_id]['ship_postcode']))
				$ship_postcode=$_SESSION['addy'.$blog_id]['ship_postcode'];
			if(isset($_SESSION['addy'.$blog_id]['comments']))
				$comments=$_SESSION['addy'.$blog_id]['comments'];
		}else{
			$first_name=$last_name=$company=$phone=$reference='';
			$email=$address1=$address2=$city=$country='';
			$state=$altstate=$zip=$ship_name=$ship_company='';
			$ship_phone=$ship_address=$ship_city=$ship_postcode='';
			$ship_country=$ship_state=$ship_altstate=$comments='';
			$eshopsetcookie=apply_filters('eshop_use_cookie',true);
			
			if(isset($_COOKIE["eshopcart"]) && calculate_items()!=0 && $eshopsetcookie==true){
				$crumbs=eshop_break_cookie($_COOKIE["eshopcart"]);
				foreach($crumbs as $k=>$v){
					$$k=$v;
				}
			}
			if(is_user_logged_in() && isset($eshopoptions['users']) && 'yes' == $eshopoptions['users']){
				global $current_user;
				get_currentuserinfo();
				$crumbs=stripslashes_deep(get_the_author_meta( 'eshop', $current_user->ID )) ;
				if(is_array($crumbs)){
					foreach($crumbs as $k=>$v){
						$$k=esc_attr($v);
					}
					$first_name=esc_attr($current_user->user_firstname);
					$last_name=esc_attr($current_user->user_lastname);
					$email=esc_attr($current_user->user_email);
				}
			}
			
			if($state=='' && isset($eshopoptions['shipping_state_selected']))
				$state=$eshopoptions['shipping_state_selected'];
			if($country=='' && isset($eshopoptions['shipping_country_selected']))
				$country=$eshopoptions['shipping_country_selected'];
		}

	}

	if($chkerror!=0 || (!isset ($espost['submit'])) && $numberofproducts>=1){
		// only show form if not filled in.
		$echoit.= eshopShowform($first_name,$last_name,$company,$phone,$email,$address1,$address2,$city,$state,$altstate,$zip,$country,$reference,$comments,$ship_name,$ship_company,$ship_phone,$ship_address,$ship_city,$ship_postcode,$ship_state,$ship_altstate,$ship_country);
	}

	if(isset($_SESSION['eshopcart'.$blog_id])){
		switch($contineproceed){
			case ('1'):
				$echoit.='<ul class="continue-proceed eshopcp1"><li class="editcart"><a href="'.get_permalink($eshopoptions['cart']).'">'.__('&laquo; Edit Cart or Continue Shopping','eshop').'</a></li></ul>';
				break;
			case ('2'):
				$echoit.='<ul class="continue-proceed redirect eshopcp2"><li class="editcheckout"><a href="'.get_permalink($eshopoptions['checkout']).'">'.__('&laquo; Edit Details or Continue Shopping','eshop').'</a></li></ul>';
				break;
			case ('3'):
				$echoit.='<ul class="continue-proceed redirect eshopcp3"><li class="editcheckout"><a href="'.get_permalink($eshopoptions['checkout']).'">'.__('&laquo; Edit Details or Continue Shopping','eshop').'</a></li></ul>';
				break;
		}

	}else{
		$echoit.= "<p><strong class=\"eshoperror error\">".__('Your shopping cart is currently empty.','eshop')."</strong></p>";
	}
	return $echoit;
 }
}
if (!function_exists('eshop_checkreqd')) {
 	function eshop_checkreqd($reqdarray,$tocheck){
		if(in_array($tocheck,$reqdarray)){
			$reqd='<span class="reqd">*</span>';
		}else{
			$reqd='';
		}
		return $reqd;
	}
}
?>