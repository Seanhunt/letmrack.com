<?php 
  
 /**
 * @package Form Maker
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

	function showform($id)
	{	
		global $wpdb;
		$row=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."formmaker WHERE id=%d",$id )); 	
		
		if(!$row)
			return false;
			
			
		$form_theme=$wpdb->get_var($wpdb->prepare("SELECT css FROM ".$wpdb->prefix."formmaker_themes WHERE id=%d",$row->theme )); 		
		if(!$form_theme)
			return false;

		$label_id= array();
		$label_type= array();
			
		$label_all	= explode('#****#',$row->label_order);
		$label_all 	= array_slice($label_all,0, count($label_all)-1);   
		
		foreach($label_all as $key => $label_each) 
		{
			$label_id_each=explode('#**id**#',$label_each);
			array_push($label_id, $label_id_each[0]);
			
			$label_order_each=explode('#**label**#', $label_id_each[1]);
			
			array_push($label_type, $label_order_each[1]);
		}
		
		return array($row, $Itemid, $label_id, $label_type, $form_theme);
	}

	function savedata($form,$id)
	{	

	
		$all_files=array();
		@session_start();

		$captcha_input=$_POST["captcha_input"];
		$recaptcha_response_field=$_POST["recaptcha_response_field"];
		$id_for_old=$id;
		if(!$form->form_front)
		$id='';
		if(isset($_POST["counter".$id]))
		{	
			$counter=$_POST["counter".$id];
			if (isset($_POST["captcha_input"]))
			{		
				$session_wd_captcha_code=isset($_SESSION[$id.'_wd_captcha_code'])?$_SESSION[$id.'_wd_captcha_code']:'-';
				if($captcha_input==$session_wd_captcha_code)
				{
					
					
					$all_files=save_db($counter,$id_for_old);
					if(is_numeric($all_files))		
						remove($all_files,$id_for_old);
					else
						if(isset($counter))
						gen_mail($counter, $all_files,$id_for_old);

				}
				else
				{
							echo "<script> alert('".addslashes(__('Error, incorrect Security code.', 'form_maker'))."');
						</script>";
				}
			}	
			
			else
				if(isset($recaptcha_response_field))
				{	
				$privatekey = $form->private_key;
	
					$resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$recaptcha_response_field);
					if($resp->is_valid)
					{
						$all_files=save_db($counter,$id_for_old);
						if(is_numeric($all_files))		
							remove($all_files,$id_for_old);
						else
							if(isset($counter))
								gen_mail($counter, $all_files, $id_for_old);
	
					}
					else
					{
								echo "<script> alert('".addslashes(__('Error, incorrect Security code.', 'form_maker'))."');
							</script>";
					}
				}	
			
				else	
				{
				
					$all_files=save_db($counter, $id_for_old);
					if(is_numeric($all_files))		
						remove($all_files, $id_for_old);
					else
						if(isset($counter))
							gen_mail($counter, $all_files, $id_for_old);
		
				}
	

			return $all_files;
		}

		return $all_files;
			
			
	}
	
	function save_db($counter,$id)
	{

		global $wpdb;
		$chgnac=true;	
		$all_files=array();
		$form=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."formmaker WHERE id= %d",$id));
		
		$id_old=$id;
		if(!$form->form_front){
		$id='';
		
		}
		$label_id= array();
		$label_label= array();
		$label_type= array();
			
		$label_all	= explode('#****#',$form->label_order);
		$label_all 	= array_slice($label_all,0, count($label_all)-1);   
		
		foreach($label_all as $key => $label_each) 
		{
			$label_id_each=explode('#**id**#',$label_each);
			array_push($label_id, $label_id_each[0]);
			
			$label_order_each=explode('#**label**#', $label_id_each[1]);
			
			array_push($label_label, $label_order_each[0]);
			array_push($label_type, $label_order_each[1]);
		}
		

		
		$max = $wpdb->get_var("SELECT MAX( group_id ) FROM ".$wpdb->prefix."formmaker_submits" );
		foreach($label_type as $key => $type)
		{
			$value='';
			if($type=="type_submit_reset" or $type=="type_map" or $type=="type_editor" or  $type=="type_captcha" or  $type=="type_recaptcha" or  $type=="type_button")
				continue;

			$i=$label_id[$key];
			
			if($type!="type_address")
			{
				$deleted=$_POST[$i."_type".$id];
				if(!isset($_POST[$i."_type".$id]))
					break;
			}
			
			switch ($type)
			{
				case 'type_text':
				case 'type_password':
				case 'type_textarea':
				case "type_submitter_mail":
				case "type_date":
				case "type_own_select":					
				case "type_country":				
				case "type_number":				
				{
					$value=$_POST[$i."_element".$id];
					break;
				}
				case "type_mark_map":				
				{
					$value=$_POST[$i."_long".$id].'***map***'.$_POST[$i."_lat".$id];
					break;
				}
				
				case "type_date_fields":
				{
					$value=$_POST[$i."_day".$id].'-'.$_POST[$i."_month".$id].'-'.$_POST[$i."_year".$id];
					break;
				}
				
				case "type_time":
				{
					$ss=$_POST[$i."_ss".$id];
					if(isset($_POST[$i."_ss".$id]))
						$value=$_POST[$i."_hh".$id].':'.$_POST[$i."_mm".$id].':'.$_POST[$i."_ss".$id];
					else
						$value=$_POST[$i."_hh".$id].':'.$_POST[$i."_mm".$id];
							
					$am_pm=$_POST[$i."_am_pm".$id];
					if(isset($_POST[$i."_am_pm".$id]))
						$value=$value.' '.$_POST[$i."_am_pm".$id];
						
					break;
				}
				
				case "type_phone":
				{
					$value=$_POST[$i."_element_first".$id].' '.$_POST[$i."_element_last".$id];
						
					break;
				}
	
				case "type_name":
				{
					$element_title=$_POST[$i."_element_title".$id];
					if(isset($_POST[$i."_element_title".$id]))
						$value=$_POST[$i."_element_title".$id].' '.$_POST[$i."_element_first".$id].' '.$_POST[$i."_element_last".$id].' '.$_POST[$i."_element_middle".$id];
					else
						$value=$_POST[$i."_element_first".$id].' '.$_POST[$i."_element_last".$id];
						
					break;
				}
	
				case "type_file_upload":
				{
					$file = $_FILES[$i.'_file'.$id];
					if($file['name'])
					{	
						$untilupload = $form->form;
						$pos1 = strpos($untilupload, "***destinationskizb".$i."***");
						$pos2 = strpos($untilupload, "***destinationverj".$i."***");
						$destination = substr($untilupload, $pos1+(23+(strlen($i)-1)), $pos2-$pos1-(23+(strlen($i)-1)));
						$pos1 = strpos($untilupload, "***extensionskizb".$i."***");
						$pos2 = strpos($untilupload, "***extensionverj".$i."***");
						$extension = substr($untilupload, $pos1+(21+(strlen($i)-1)), $pos2-$pos1-(21+(strlen($i)-1)));
						$pos1 = strpos($untilupload, "***max_sizeskizb".$i."***");
						$pos2 = strpos($untilupload, "***max_sizeverj".$i."***");
						$max_size = substr($untilupload, $pos1+(20+(strlen($i)-1)), $pos2-$pos1-(20+(strlen($i)-1)));
						$fileName = $file['name'];
						$destination=str_replace(site_url().'/','',$destination);
						/*$destination = JPATH_SITE.DS.$_POST[$i.'_destination');
						$extension = $_POST[$i.'_extension');
						$max_size = $_POST[$i.'_max_size');*/
					
						$fileSize = $file['size'];

						if($fileSize > $max_size*1024)
						{
							echo "<script> alert('".addslashes(__('The file exceeds the allowed size of', 'form_maker')).$max_size." KB');</script>";
							return ($max+1);
						}
						
						$uploadedFileNameParts = explode('.',$fileName);
						$uploadedFileExtension = array_pop($uploadedFileNameParts);
						$to=strlen($fileName)-strlen($uploadedFileExtension)-1;
						
						$fileNameFree= substr($fileName,0, $to);
						$invalidFileExts = explode(',', $extension);
						$extOk = false;

						foreach($invalidFileExts as $key => $value)
						{
						if(  is_numeric(strpos(strtolower($value), strtolower($uploadedFileExtension) )) )
							{
								$extOk = true;
							}
						}
						 
						if ($extOk == false) 
						{
							echo "<script> alert('".addslashes(__('Sorry, you are not allowed to upload this type of file.', 'form_maker'))."');</script>";
							return ($max+1);
						}
						
						$fileTemp = $file['tmp_name'];
						$p=1;
						while(file_exists( $destination."/".$fileName))
						{
						$to=strlen($file['name'])-strlen($uploadedFileExtension)-1;
						$fileName= substr($fileName,0, $to).'('.$p.').'.$uploadedFileExtension;
						$p++;
						}
						
						if(is_dir(ABSPATH.$destination)){
						if(!move_uploaded_file($fileTemp, ABSPATH.$destination.'/'.$fileName)) 
						{	
							echo "<script> alert('".addslashes(__('Error, file cannot be moved.', 'form_maker'))."');</script>";
							return ($max+1);
						
						}
						
						}
						else
						{
							echo "<script> alert('".addslashes(__('Error, file cannot be moved.', 'form_maker'))."');</script>";
							return ($max+1);
						}
						$value= site_url().'/'.$destination.'/'.$fileName.'*@@url@@*';
		
						$file['tmp_name']=$destination."/".$fileName;
						
						array_push($all_files,$file);

					}
					break;
				}
				
				case 'type_address':
				{
					$value='*#*#*#';
					if(isset($_POST[$i."_street1".$id]))
					{
						$value=$_POST[$i."_street1".$id];
						break;
					}
					
					if(isset($_POST[$i."_street2".$id]))
					{
						$value=$_POST[$i."_street2".$id];
						break;
					}
					
					if(isset($_POST[$i."_city".$id]))
					{
						$value=$_POST[$i."_city".$id];
						break;
					}
					
		
					if(isset($_POST[$i."_state".$id]))
					{
						$value=$_POST[$i."_state".$id];
						break;
					}
					
			
					if(isset($_POST[$i."_postal".$id]))
					{
						$value=$_POST[$i."_postal".$id];
						break;
					}
					
			
					if(isset($_POST[$i."_country".$id]))
					{
						$value=$_POST[$i."_country".$id];
						break;
					}
					
					break;
				}
				
				case "type_hidden":				
				{
					$value=$_POST[$label_label[$key]];
					break;
				}
				
				case "type_radio":				
				{
					$element=$_POST[$i."_other_input".$id];
					if(isset($element))
					{
						$value=$element;	
						break;
					}
					
					$value=$_POST[$i."_element".$id];
					break;
				}
				
				case "type_checkbox":				
				{
					
					
					$start=-1;
					$value='';
					for($j=0; $j<100; $j++)
					{
					
						//$element=$_POST[$i."_element".$id.$j];
		
						if(isset($_POST[$i."_element".$id.$j]))
						{
							$start=$j;
							break;
						}
					}
					$other_element_id=-1;
					$is_other=$_POST[$i."_allow_other".$id];
					if($is_other=="yes")
					{
						$other_element_id=$_POST[$i."_allow_other_num".$id];
					}
					
					if($start!=-1)
					{
						for($j=$start; $j<100; $j++)
						{
							//$element=$_POST[$i."_element".$id.$j];
							if(isset($_POST[$i."_element".$id.$j])){
								
							if($j==$other_element_id)
							{
							
								$value=$value.$_POST[$i."_other_input".$id].'***br***';
							}
							else
							{
							
								$value=$value.$_POST[$i."_element".$id.$j].'***br***';
							}
							}
						}
					}
					break;
				}
				
			}
	
			if($type=="type_address")
				if(	$value=='*#*#*#')
					break;

			$unique_element=$_POST[$i."_unique".$id];
			if($unique_element=='yes')
			{
				$unique = $wpdb->get_col($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."formmaker_submits WHERE form_id= %d  and element_label= %s and element_value= %s",$id_old,$i,addslashes($value)));	
				if ($unique) 
				{
					echo "<script> alert('".addslashes(__('This field %s requires a unique entry and this value was already submitted.', 'form_maker'))."'.replace('%s','".$label_label[$key]."'));</script>";		
					return ($max+1);
				}
			}
			$ip=$_SERVER['REMOTE_ADDR'];
			$r=$wpdb->prefix."formmaker_submits";
			
			$save_or_no=$wpdb->insert($r, array(
					'form_id'     => $id_old,
					'element_label'    => $i,
					'element_value'  => addslashes($value),
					'group_id'   => ($max+1),
					'date'  => date('Y-m-d H:i:s'),
					'ip'    => $ip,
							),
							array(
				'%d',
				'%s',
				'%s',
				'%d',
				'%s',
				'%s'
			
				)
				);
			
			if (!$save_or_no){return false;}
			$chgnac=false;
		}
		if($chgnac)
		{		global $wpdb;
			
				if(count($all_files)==0)
				@session_start();
				if($form->submit_text_type!=4)

				$_SESSION['massage_after_submit']=addslashes(addslashes(__('Nothing was submitted.', 'form_maker')));
				$_SESSION['error_or_no']=1;
				$_SESSION['form_submit_type']=$form->submit_text_type.",".$form->id;
					wp_redirect($_SERVER["REQUEST_URI"]);					
					exit;
		}
		return $all_files;
	}
	
	
	
	function gen_mail($counter, $all_files, $id)
	{
		@session_start();
		global $wpdb;
		
		$row=$wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."formmaker WHERE id=%d",$id));
		if(!$row->form_front)
		{
			$id='';
		}
			$label_order_original= array();
			$label_order_ids= array();
			$label_label= array();
			$label_type= array();
			$cc=array();
			$row_mail_one_time=1;
			$label_all	= explode('#****#',$row->label_order);
			$label_all 	= array_slice($label_all,0, count($label_all)-1);   
			foreach($label_all as $key => $label_each) 
			{
				$label_id_each=explode('#**id**#',$label_each);
				$label_id=$label_id_each[0];
				array_push($label_order_ids,$label_id);
				
				$label_order_each=explode('#**label**#', $label_id_each[1]);							
				$label_order_original[$label_id]=$label_order_each[0];
				array_push($label_label, $label_order_each[0]);
				array_push($label_type, $label_order_each[1]);
			}
				

			$list='<table border="1" cellpadding="3" cellspacing="0" style="width:600px;">';
			foreach($label_order_ids as $key => $label_order_id)
			{
				$i=$label_order_id;
				$type=$_POST[$i."_type".$id];
				if(isset($_POST[$i."_type".$id]))
				if($type!="type_map" and  $type!="type_submit_reset" and  $type!="type_editor" and  $type!="type_captcha" and  $type!="type_recaptcha" and  $type!="type_button")
				{	
					$element_label=$label_order_original[$i];
					
					switch ($type)
					{
						case 'type_text':
						case 'type_password':
						case 'type_textarea':
						case "type_date":
						case "type_own_select":					
						case "type_country":				
						case "type_number":	
						{
							$element=$_POST[$i."_element".$id];
							if(isset($_POST[$i."_element".$id]))
							{
								$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td ><pre style="font-family:inherit; margin:0px; padding:0px">'.$element.'</pre></td></tr>';					
							}
							break;
						
						
						}
						
						case "type_submitter_mail":
						{
							$element=$_POST[$i."_element".$id];
							if(isset($_POST[$i."_element".$id]))
							{
								$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td ><pre style="font-family:inherit; margin:0px; padding:0px">'.$element.'</pre></td></tr>';					
								if($_POST[$i."_send".$id]=="yes")
									array_push($cc, $element);
							}
							break;		
						}
						
						case "type_time":
						{
							
							$hh=$_POST[$i."_hh".$id];
							if(isset($_POST[$i."_hh".$id]))
							{
								$ss=$_POST[$i."_ss".$id];
								if(isset($_POST[$i."_ss".$id]))
									$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >'.$_POST[$i."_hh".$id].':'.$_POST[$i."_mm".$id].':'.$_POST[$i."_ss".$id];
								else
									$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >'.$_POST[$i."_hh".$id].':'.$_POST[$i."_mm".$id];
								$am_pm=$_POST[$i."_am_pm".$id];
								if(isset($_POST[$i."_am_pm".$id]))
									$list=$list.' '.$_POST[$i."_am_pm".$id].'</td></tr>';
								else
									$list=$list.'</td></tr>';
							}
								
							break;
						}
						
						case "type_phone":
						{
							$element_first=$_POST[$i."_element_first".$id];
							if(isset($_POST[$i."_element_first".$id]))
							{
									$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >'.$_POST[$i."_element_first".$id].' '.$_POST[$i."_element_last".$id].'</td></tr>';
							}	
							break;
						}
						
						case "type_name":
						{
							$element_first=$_POST[$i."_element_first".$id];
							if(isset($_POST[$i."_element_first".$id]))
							{
								$element_title=$_POST[$i."_element_title".$id];
								if(isset($_POST[$i."_element_title".$id]))
									$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >'.$_POST[$i."_element_title".$id].' '.$_POST[$i."_element_first".$id].' '.$_POST[$i."_element_last".$id].' '.$_POST[$i."_element_middle".$id].'</td></tr>';
								else
									$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >'.$_POST[$i."_element_first".$id].' '.$_POST[$i."_element_last".$id].'</td></tr>';
							}	   
							break;		
						}
						case "type_mark_map":
						{
							
							if(isset($_POST[$i."_long".$id]))
							{
								$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >Longitude:'.$_POST[$i."_long".$id].'<br/>Latitude:'.$_POST[$i."_lat".$id].'</td></tr>';
							}
							break;		
						}
						
						case "type_address":
						{
							$street1=$_POST[$i."_street1".$id];
							if(isset($_POST[$i."_street1".$id]))
							{
								$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$_POST[$i."_street1".$id].'</td></tr>';
								$i++;
								$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$_POST[$i."_street2".$id].'</td></tr>';
								$i++;
								$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$_POST[$i."_city".$id].'</td></tr>';
								$i++;
								$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$_POST[$i."_state".$id].'</td></tr>';
								$i++;
								$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$_POST[$i."_postal".$id].'</td></tr>';
								$i++;
								$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$_POST[$i."_country".$id].'</td></tr>';
								$i++;			
							}		
							break;
						}
						
						case "type_date_fields":
						{
							$day=$_POST[$i."_day".$id];
							if(isset($_POST[$i."_day".$id]))
							{
								$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >'.$_POST[$i."_day".$id].'-'.$_POST[$i."_month".$id].'-'.$_POST[$i."_year".$id].'</td></tr>';
							}
							break;
						}
						
						case "type_radio":
						{
							$element=$_POST[$i."_other_input".$id];
							if(isset($_POST[$i."_other_input".$id]))
							{
								$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >'.$_POST[$i."_other_input".$id].'</td></tr>';
								break;
							}	
							
							$element=$_POST[$i."_element".$id];
							if(isset($_POST[$i."_element".$id]))
							{
								$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td ><pre style="font-family:inherit; margin:0px; padding:0px">'.$element.'</pre></td></tr>';					
							}
							break;	
						}
						
						case "type_checkbox":
						{
							$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >';
						
							$start=-1;
							for($j=0; $j<100; $j++)
							{
								if(isset($_POST[$i."_element".$id.$j]))
								{
									$start=$j;
									break;
								}
							}	
							$other_element_id=-1;
							$is_other=$_POST[$i."_allow_other".$id];
							if($is_other=="yes")
							{
								$other_element_id=$_POST[$i."_allow_other_num".$id];
							}
							
					
							if($start!=-1)
							{
								for($j=$start; $j<100; $j++)
								{
									
									$element=$_POST[$i."_element".$id.$j];
									if(isset($_POST[$i."_element".$id.$j]))
									if($j==$other_element_id)
									{
										$list=$list.$_POST[$i."_other_input".$id].'<br>';
									}
									else
									
										$list=$list.$_POST[$i."_element".$id.$j].'<br>';
								}
								$list=$list.'</td></tr>';
							}
										
							
							break;
						}
						default: break;
					}
				
				}
				
			}
			$list=$list.'</table>';
			$list = wordwrap($list, 70, "\n", true);
			 add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));				
							var_dump($all_files);
							for($k=0;$k<count($all_files);$k++){
								$attachment[$k]= dirname(__FILE__). '/uploads/'.$all_files[$k]['name'];
							}
							if(isset($cc[0]))
							{
								foreach	($cc as $c)	
								{
				
							
									if($c)
									{
									
										 $recipient = $c;
										 $subject   = $row->title;
										 $body      = $row->script_user1.'<br>'.$list.'<br>'.$row->script_user2; 
										 $send=wp_mail($recipient, $subject, $body, "",$attachment);  
									}	
									
									
									if($row->mail)
									{
										if($c)
										{
											$headers_form_mail = 'From: '.$c.' <'.$c.'>' . "\r\n";
											
										}
										else
										{
											$headers_form_mail ="";
										}
										if($row_mail_one_time){
											 $recipient = $row->mail;
											 $subject   = $row->title;
											 $body      = $row->script1.'<br>'.$list.'<br>'.$row->script2;
											 $mode      = 1; 

										$send=wp_mail($recipient, $subject, $body, $headers_form_mail,$attachment); 
										$row_mail_one_time=0;
										}
									}
								}
							}
							else 
							{ 
								if($row->mail)
								{
								 $recipient = $row->mail;
								 $subject     = $row->title;
								 $body      = $row->script1.'<br>'.$list.'<br>'.$row->script2;
								 $mode        = 1; 
            
								 $send=wp_mail($recipient, $subject, $body, "",$attachment);  
								} 
							}
		if($row->mail)
			{
				if ( $send != true ) 
				{
					@session_start();
					$_SESSION['error_or_no']=1;
					$msg=addslashes(__('Error, email was not sent.', 'form_maker'));
				}
				else 
				{
					@session_start();
					$_SESSION['error_or_no']=0;
					$msg=addslashes(__('Your form was successfully submitted.', 'form_maker'));
				}
			}
		else
		{	
		
			@session_start();
			$_SESSION['error_or_no']=0;
			$msg=addslashes(__('Your form was successfully submitted.', 'form_maker'));
		}
						
		switch($row->submit_text_type)
		{
					case "2":
					case "5":
					{
						@session_start();
						if($row->submit_text_type!=4)
						$_SESSION['massage_after_submit']=$msg;
						$_SESSION['form_submit_type']=$row->submit_text_type.",".$row->id;
						if($row->article_id)
						wp_redirect($row->article_id);
						else
						wp_redirect($_SERVER["REQUEST_URI"]);
						
						exit;
						break;
					}
					case "3":
					{
						@session_start();
						if($row->submit_text_type!=4)
						$_SESSION['massage_after_submit']=$msg;
						$_SESSION['form_submit_type']=$row->submit_text_type.",".$row->id;
						wp_redirect($_SERVER["REQUEST_URI"]);
						
						exit;
						break;
					}											
					case "4":
					{
						@session_start();
						if($row->submit_text_type!=4)
						$_SESSION['massage_after_submit']=$msg;
						$_SESSION['form_submit_type']=$row->submit_text_type.",".$row->id;
						wp_redirect($row->url);
						
						exit;
						break;
					}
					default:
					{
						@session_start();
						if($row->submit_text_type!=4)
						$_SESSION['massage_after_submit']= $msg;
						$_SESSION['form_submit_type']=$row->submit_text_type.",".$row->id;
						wp_redirect($_SERVER["REQUEST_URI"]);
						
						exit;
						break;
					}
		}														
	}
	
	/*function sendMail($from='', $fromname='', $recipient, $subject, $body, $mode=0, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null)
    {
				$recipient=explode (',', str_replace(' ', '', $recipient ));
                // Get a JMail instance
                $mail = &JFactory::getMailer();
 
                $mail->setSender(array($from, $fromname));
                $mail->setSubject($subject);
                $mail->setBody($body);
 
                // Are we sending the email as HTML?
                if ($mode) {
                        $mail->IsHTML(true);
                }
 
                $mail->addRecipient($recipient);
                $mail->addCC($cc);
                $mail->addBCC($bcc);
				
				if($attachment)
					foreach($attachment as $attachment_temp)
					{
						$mail->addAttachment($attachment_temp[0], $attachment_temp[1], $attachment_temp[2]);
					}
 
                // Take care of reply email addresses
                if (is_array($replyto)) {
                        $numReplyTo = count($replyto);
                        for ($i=0; $i < $numReplyTo; $i++){
                                $mail->addReplyTo(array($replyto[$i], $replytoname[$i]));
                        }
                } elseif (isset($replyto)) {
                        $mail->addReplyTo(array($replyto, $replytoname));
                }
 
                return  $mail->Send();
        }
	
	*/
	
	function remove($group_id)
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare('DELETE FROM '.$wpdb->prefix.'formmaker_submits WHERE group_id= %d',$group_id));
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	//////////////////////////////////////////////////////         DISPLAY
	
	
	
	
	
	
	
	
	
	
	
	
function form_maker_front_end($id){
	$form_maker_front_end="";
$result =showform($id);
			if(!$result)
				return;
			$ok	=savedata($result[0],$id);
			if(is_numeric($ok))		
				remove($ok);

@session_start();
global $wpdb;
$row	= $result[0];
$Itemid = $result[1];
$label_id = $result[2];
$label_type = $result[3];
$form_theme = $result[4];


$ok	= $ok;


if(isset($_SESSION['show_submit_text'.$id]))
	if($_SESSION['show_submit_text'.$id]==1)
	{
		$_SESSION['show_submit_text'.$id]=0;
		$form_maker_front_end.=$row->submit_text;
		return;
	}
		$vives_form=$wpdb->get_var($wpdb->prepare("SELECT views FROM ".$wpdb->prefix."formmaker_views WHERE form_id=%d",$id));
		$vives_form=$vives_form+1;
		$wpdb->update( $wpdb->prefix."formmaker_views", 
			array( 
				'views' => $vives_form,	
			), 
			array( 'form_id' => $id ), 
			array( 
				'%d',	
			), 
			array( '%d' ) 
		);

	/*	$cmpnt_js_path =plugins_url("js",__FILE__);
		$document->addScript($cmpnt_js_path.'/if_gmap.js');
		$document->addScript( JURI::root(true).'/components/com_formmaker/views/formmaker/tmpl/main.js');
		$document->addScript( JURI::root(true).'/includes/js/joomla.javascript.js');
		$document->addScript('http://maps.google.com/maps/api/js?sensor=false');*/

			$article=$row->article_id;
			if($row->form_front){
				
				
				/////////if form is new version
				
				
				
				
				
				
			$form_maker_front_end.='<script type="text/javascript">'.$row->javascript.'</script>';
						$new_form_theme=explode('{',$form_theme);
			$count_after_explod_theme=count($new_form_theme);
			for($i=0;$i<$count_after_explod_theme;$i++){
				$body_or_classes[$i]=explode('}',$new_form_theme[$i]);
			}
			for($i=0;$i<$count_after_explod_theme;$i++){
				if($i==0)
				$body_or_classes[$i][0]="#form".$id.' '.str_replace(',',", #form".$id,$body_or_classes[$i][0]);
				else
				$body_or_classes[$i][1]="#form".$id.' '.str_replace(',',", #form".$id,$body_or_classes[$i][1]);
			}
			for($i=0;$i<$count_after_explod_theme;$i++){
				$body_or_classes_implode[$i]=implode('}',$body_or_classes[$i]);
			}
			$form_theme=implode('{',$body_or_classes_implode);
			$form_maker_front_end.= '<style>'.str_replace('[SITE_ROOT]', plugins_url("",__FILE__), $form_theme).'</style>';

//			echo '<h3>'.$row->title.'</h3><br />';

	$form_maker_front_end.='<form name="form'.$id.'" action="'.$_SERVER['REQUEST_URI'].'" method="post" id="form'.$id.'" enctype="multipart/form-data"  onsubmit="check_required(\'submit\', \''.$id.'\'); return false;">
		<div id="'.$id.'pages" class="wdform_page_navigation" show_title="'.$row->show_title.'" show_numbers="'.$row->show_numbers.'" type="'.$row->pagination.'"></div>
		<input type="hidden" id="counter'.$id.'" value="'.$row->counter.'" name="counter'.$id.'" />
		<input type="hidden" id="Itemid'.$id.'" value="'.$Itemid.'" name="Itemid'.$id.'" />';


//inch@ petq chi raplace minchev form@ tpi			
			
			$captcha_url='components/com_formmaker/wd_captcha.php?digit=';
			$captcha_rep_url='components/com_formmaker/wd_captcha.php?r2='.mt_rand(0,1000).'&digit=';
			
			$rep1=array(
			"<!--repstart-->Title<!--repend-->",
			"<!--repstart-->First<!--repend-->",
			"<!--repstart-->Last<!--repend-->",
			"<!--repstart-->Middle<!--repend-->",
			"<!--repstart-->January<!--repend-->",
			"<!--repstart-->February<!--repend-->",
			"<!--repstart-->March<!--repend-->",
			"<!--repstart-->April<!--repend-->",
			"<!--repstart-->May<!--repend-->",
			"<!--repstart-->June<!--repend-->",
			"<!--repstart-->July<!--repend-->",
			"<!--repstart-->August<!--repend-->",
			"<!--repstart-->September<!--repend-->",
			"<!--repstart-->October<!--repend-->",
			"<!--repstart-->November<!--repend-->",
			"<!--repstart-->December<!--repend-->",
			"<!--repstart-->Street Address<!--repend-->",
			"<!--repstart-->Street Address Line 2<!--repend-->",
			"<!--repstart-->City<!--repend-->",
			"<!--repstart-->State / Province / Region<!--repend-->",
			"<!--repstart-->Postal / Zip Code<!--repend-->",
			"<!--repstart-->Country<!--repend-->",
			"<!--repstart-->Area Code<!--repend-->",
			"<!--repstart-->Phone Number<!--repend-->",
			$captcha_url,
			'class="captcha_img"',
			plugins_url("images/refresh.png",__FILE__),
			'form_id_temp',
			'style="padding-right:170px"');
			
			$rep2=array(
			addslashes(__("Title", 'form_maker')),
			addslashes(__("First", 'form_maker')),
			addslashes(__("Last", 'form_maker')),
			addslashes(__("Middle", 'form_maker')),
			addslashes(__("January", 'form_maker')),
			addslashes(__("February", 'form_maker')),
			addslashes(__("March", 'form_maker')),
			addslashes(__("April", 'form_maker')),
			addslashes(__("May", 'form_maker')),
			addslashes(__("June", 'form_maker')),
			addslashes(__("July", 'form_maker')),
			addslashes(__("August", 'form_maker')),
			addslashes(__("September", 'form_maker')),
			addslashes(__("October", 'form_maker')),
			addslashes(__("November", 'form_maker')),
			addslashes(__("December", 'form_maker')),
			addslashes(__("Street Address", 'form_maker')),
			addslashes(__("Street Address Line 2", 'form_maker')),
			addslashes(__("City", 'form_maker')),
			addslashes(__("State / Province / Region", 'form_maker')),
			addslashes(__("Postal / Zip Code", 'form_maker')),
			addslashes(__("Country", 'form_maker')),
			addslashes(__("Area Code", 'form_maker')),
			addslashes(__("Phone Number", 'form_maker')),
			$captcha_rep_url,
			'class="captcha_img" style="display:none"',
			plugins_url("images/refresh.png",__FILE__),
			$id,
			'');
			
			$untilupload = str_replace($rep1,$rep2,$row->form_front);
			while(strpos($untilupload, "***destinationskizb")>0)
			{
				$pos1 = strpos($untilupload, "***destinationskizb");
				$pos2 = strpos($untilupload, "***destinationverj");
				$untilupload=str_replace(substr($untilupload, $pos1, $pos2-$pos1+22), "", $untilupload);
			}
$form_maker_front_end.=$untilupload;

$is_recaptcha=false;


$form_maker_front_end.='<script type="text/javascript">';

$form_maker_front_end.='WDF_FILE_TYPE_ERROR = \''.addslashes(__("Sorry, you are not allowed to upload this type of file.", 'form_maker')).'\';
';
$form_maker_front_end.='WDF_INVALID_EMAIL = \''.addslashes(__("This is not a valid email address.", 'form_maker')).'\';
';
$form_maker_front_end.='REQUEST_URI	= "'.$_SERVER['REQUEST_URI'].'";
';
$form_maker_front_end.='ReqFieldMsg	=\'`FIELDNAME`'.addslashes(__('field is required.', 'form_maker')).'\';
';  
$form_maker_front_end.='function formOnload'.$id.'()
{
';//enable maps and refresh captcha


	foreach($label_type as $key => $type)
	{
		switch ($type)
		{
			case 'type_map':
			
			
	$form_maker_front_end.='if(document.getElementById("'.$label_id[$key].'_element'.$id.'"))
		{
			if_gmap_init('.$label_id[$key].','.$id.');
			for(q=0; q<20; q++)
				if(document.getElementById('.$label_id[$key].'+"_element"+'.$id.').getAttribute("long"+q))
				{
				
					w_long=parseFloat(document.getElementById('.$label_id[$key].'+"_element"+'.$id.').getAttribute("long"+q));
					w_lat=parseFloat(document.getElementById('.$label_id[$key].'+"_element"+'.$id.').getAttribute("lat"+q));
					w_info=parseFloat(document.getElementById('.$label_id[$key].'+"_element"+'.$id.').getAttribute("info"+q));
					add_marker_on_map('.$label_id[$key].',q, w_long, w_lat, w_info,'.$id.',false);
				}
		}';
			break;
	
			case 'type_mark_map':
	$form_maker_front_end.='if(document.getElementById("'.$label_id[$key].'_element'.$id.'"))
	if(!document.getElementById("'.$label_id[$key].'_long'.$id.'"))	
	{      	
	
		var longit = document.createElement(\'input\');
         	longit.setAttribute("type", \'hidden\');
         	longit.setAttribute("id", \''.$label_id[$key].'_long'.$id.'\');
         	longit.setAttribute("name", \''.$label_id[$key].'_long'.$id.'\');

		var latit = document.createElement(\'input\');
         	latit.setAttribute("type", \'hidden\');
         	latit.setAttribute("id", \''.$label_id[$key].'_lat'.$id.'\');
         	latit.setAttribute("name", \''.$label_id[$key].'_lat'.$id.'\');

		document.getElementById("'.$label_id[$key].'_element_section'.$id.'").appendChild(longit);
		document.getElementById("'.$label_id[$key].'_element_section'.$id.'").appendChild(latit);
	
		if_gmap_init('.$label_id[$key].', '.$id.');
		
		w_long=parseFloat(document.getElementById('.$label_id[$key].'+"_element"+'.$id.').getAttribute("long0"));
		w_lat=parseFloat(document.getElementById('.$label_id[$key].'+"_element"+'.$id.').getAttribute("lat0"));
		w_info=parseFloat(document.getElementById('.$label_id[$key].'+"_element"+'.$id.').getAttribute("info0"));
		
		
		longit.value=w_long;
		latit.value=w_lat;
		add_marker_on_map('.$label_id[$key].',0, w_long, w_lat, w_info, '.$id.', true);		
	}';

			break;
	
			case 'type_captcha':
	$form_maker_front_end.='if(document.getElementById(\'_wd_captcha'.$id.'\'))
		captcha_refresh(\'_wd_captcha\', \''.$id.'\');';
			break;
			
			case 'type_recaptcha':
			$is_recaptcha=true;
			
			break;
	
			case 'type_radio':
			case 'type_checkbox':
	$form_maker_front_end.='if(document.getElementById(\''.$label_id[$key].'_randomize'.$id.'\'))
		if(document.getElementById(\''.$label_id[$key].'_randomize'.$id.'\').value=="yes")
		{
			choises_randomize(\''.$label_id[$key].'\', \''.$id.'\');
		}';
			break;
	
			default:
			break;
		}
	}



	$form_maker_front_end.='if(window.before_load)
	{
		before_load();
	}
}
';

$form_maker_front_end.='function formAddToOnload'.$id.'()
{ 
	if(formOldFunctionOnLoad'.$id.'){ formOldFunctionOnLoad'.$id.'(); }
	formOnload'.$id.'();
}
function formLoadBody'.$id.'()
{
	formOldFunctionOnLoad'.$id.' = window.onload;
	window.onload = formAddToOnload'.$id.';
}
var formOldFunctionOnLoad'.$id.' = null;
formLoadBody'.$id.'();';



$captcha_input=$_POST["captcha_input"];
$recaptcha_response_field=$_POST["recaptcha_response_field"];
$counter=$_POST["counter".$id];
$old_key=-1;
if(isset($counter))
{
	foreach($label_type as $key => $type)
	{
			switch ($type)
			{
			case "type_text":
			case "type_number":		
			case "type_submitter_mail":{
								$form_maker_front_end.=
	"if(document.getElementById('".$label_id[$key]."_element".$id."'))
		if(document.getElementById('".$label_id[$key]."_element".$id."').title!='".addslashes($_POST[$label_id[$key]."_element".$id])."')
		{	document.getElementById('".$label_id[$key]."_element".$id."').value='".addslashes($_POST[$label_id[$key]."_element".$id])."';
			document.getElementById('".$label_id[$key]."_element".$id."').className='input_active';
		}
	";
								break;
							}
									
			case "type_textarea":{
			$order   = array("\r\n", "\n", "\r");
								$form_maker_front_end.= 
	"if(document.getElementById('".$label_id[$key]."_element".$id."'))
		if(document.getElementById('".$label_id[$key]."_element".$id."').title!='".str_replace($order,'\n',addslashes($_POST[$label_id[$key]."_element".$id]))."')
		{	document.getElementById('".$label_id[$key]."_element".$id."').innerHTML='".str_replace($order,'\n',addslashes($_POST[$label_id[$key]."_element".$id]))."';
			document.getElementById('".$label_id[$key]."_element".$id."').className='input_active';
		}
	";
								break;
							}
			case "type_name":{
								$element_title=$_POST[$label_id[$key]."_element_title".$id];
								if(isset($_POST[$label_id[$key]."_element_title".$id]))
								{
									$form_maker_front_end.=
	"if(document.getElementById('".$label_id[$key]."_element_first".$id."'))
	{
		if(document.getElementById('".$label_id[$key]."_element_title".$id."').title!='".addslashes($_POST[$label_id[$key]."_element_title".$id])."')
		{	document.getElementById('".$label_id[$key]."_element_title".$id."').value='".addslashes($_POST[$label_id[$key]."_element_title".$id])."';
			document.getElementById('".$label_id[$key]."_element_title".$id."').className='input_active';
		}
		
		if(document.getElementById('".$label_id[$key]."_element_first".$id."').title!='".addslashes($_POST[$label_id[$key]."_element_first".$id])."')
		{	document.getElementById('".$label_id[$key]."_element_first".$id."').value='".addslashes($_POST[$label_id[$key]."_element_first".$id])."';
			document.getElementById('".$label_id[$key]."_element_first".$id."').className='input_active';
		}
		
		if(document.getElementById('".$label_id[$key]."_element_last".$id."').title!='".addslashes($_POST[$label_id[$key]."_element_last".$id])."')
		{	document.getElementById('".$label_id[$key]."_element_last".$id."').value='".addslashes($_POST[$label_id[$key]."_element_last".$id])."';
			document.getElementById('".$label_id[$key]."_element_last".$id."').className='input_active';
		}
		
		if(document.getElementById('".$label_id[$key]."_element_middle".$id."').title!='".addslashes($_POST[$label_id[$key]."_element_middle".$id])."')
		{	document.getElementById('".$label_id[$key]."_element_middle".$id."').value='".addslashes($_POST[$label_id[$key]."_element_middle".$id])."';
			document.getElementById('".$label_id[$key]."_element_middle".$id."').className='input_active';
		}
		
	}";
								}
								else
								{
								$form_maker_front_end.=
	"if(document.getElementById('".$label_id[$key]."_element_first".$id."'))
	{
		
		if(document.getElementById('".$label_id[$key]."_element_first".$id."').title!='".addslashes($_POST[$label_id[$key]."_element_first".$id])."')
		{	document.getElementById('".$label_id[$key]."_element_first".$id."').value='".addslashes($_POST[$label_id[$key]."_element_first".$id])."';
			document.getElementById('".$label_id[$key]."_element_first".$id."').className='input_active';
		}
		
		if(document.getElementById('".$label_id[$key]."_element_last".$id."').title!='".addslashes($_POST[$label_id[$key]."_element_last".$id])."')
		{	document.getElementById('".$label_id[$key]."_element_last".$id."').value='".addslashes($_POST[$label_id[$key]."_element_last".$id])."';
			document.getElementById('".$label_id[$key]."_element_last".$id."').className='input_active';
		}
		
	}";
								}
								break;
							}
							
			case "type_phone":{
	
								$form_maker_front_end.=
	"if(document.getElementById('".$label_id[$key]."_element_first".$id."'))
	{
		if(document.getElementById('".$label_id[$key]."_element_first".$id."').title!='".addslashes($_POST[$label_id[$key]."_element_first".$id])."')
		{	document.getElementById('".$label_id[$key]."_element_first".$id."').value='".addslashes($_POST[$label_id[$key]."_element_first".$id])."';
			document.getElementById('".$label_id[$key]."_element_first".$id."').className='input_active';
		}
		
		if(document.getElementById('".$label_id[$key]."_element_last".$id."').title!='".addslashes($_POST[$label_id[$key]."_element_last".$id])."')
		{	document.getElementById('".$label_id[$key]."_element_last".$id."').value='".addslashes($_POST[$label_id[$key]."_element_last".$id])."';
			document.getElementById('".$label_id[$key]."_element_last".$id."').className='input_active';
		}
	}";
								
								break;
								}
							
			case "type_address":
								{	
								if($key>$old_key)
								{
								$form_maker_front_end.=
	"if(document.getElementById('".$label_id[$key]."_street1".$id."'))
	{
			document.getElementById('".$label_id[$key]."_street1".$id."').value='".addslashes($_POST[$label_id[$key]."_street1".$id])."';
			document.getElementById('".$label_id[$key]."_street2".$id."').value='".addslashes($_POST[$label_id[$key+1]."_street2".$id])."';
			document.getElementById('".$label_id[$key]."_city".$id."').value='".addslashes($_POST[$label_id[$key+2]."_city".$id])."';
			document.getElementById('".$label_id[$key]."_state".$id."').value='".addslashes($_POST[$label_id[$key+3]."_state".$id])."';
			document.getElementById('".$label_id[$key]."_postal".$id."').value='".addslashes($_POST[$label_id[$key+4]."_postal".$id])."';
			document.getElementById('".$label_id[$key]."_country".$id."').value='".addslashes($_POST[$label_id[$key+5]."_country".$id])."';
		
	}";
									$old_key=$key+5;
									}
									break;
		
								}
								
							
							
							
			case "type_checkbox":{
			
											
			$is_other=false;
	
			if( $_POST[$label_id[$key]."_allow_other".$id]=="yes")
			{
				$other_element=$_POST[$label_id[$key]."_other_input".$id];
				$other_element_id=$_POST[$label_id[$key]."_allow_other_num".$id];
				if(isset($_POST[$label_id[$key]."_allow_other_num".$id]))
					$is_other=true;
			}

								$form_maker_front_end.=
	"
	if(document.getElementById('".$label_id[$key]."_other_input".$id."'))
	{
	document.getElementById('".$label_id[$key]."_other_input".$id."').parentNode.removeChild(document.getElementById('".$label_id[$key]."_other_br".$id."'));
	document.getElementById('".$label_id[$key]."_other_input".$id."').parentNode.removeChild(document.getElementById('".$label_id[$key]."_other_input".$id."'));
	}
	for(k=0; k<30; k++)
		if(document.getElementById('".$label_id[$key]."_element".$id."'+k))
			document.getElementById('".$label_id[$key]."_element".$id."'+k).removeAttribute('checked');
		else break;
	";
								for($j=0; $j<100; $j++)
								{
										$element=$_POST[$label_id[$key]."_element".$id.$j];
										if(isset($_POST[$label_id[$key]."_element".$id.$j]))
												{
												$form_maker_front_end.=
	"document.getElementById('".$label_id[$key]."_element".$id.$j."').setAttribute('checked', 'checked');
	";
												}
								}
								
	if($is_other)
		$form_maker_front_end.=
	"
		show_other_input('".$label_id[$key]."','".$id."');
		document.getElementById('".$label_id[$key]."_other_input".$id."').value='".$_POST[$label_id[$key]."_other_input".$id]."';
	";
	
								
								
								break;
								}
			case "type_radio":{
			
			$is_other=false;
			
			if( $_POST[$label_id[$key]."_allow_other".$id]=="yes")
			{
				$other_element=$_POST[$label_id[$key]."_other_input".$id];
				if(isset($_POST[$label_id[$key]."_other_input".$id]))
					$is_other=true;
			}
			
			
									$form_maker_front_end.=
	"
	if(document.getElementById('".$label_id[$key]."_other_input".$id."'))
	{
	document.getElementById('".$label_id[$key]."_other_input".$id."').parentNode.removeChild(document.getElementById('".$label_id[$key]."_other_br".$id."'));
	document.getElementById('".$label_id[$key]."_other_input".$id."').parentNode.removeChild(document.getElementById('".$label_id[$key]."_other_input".$id."'));
	}
	
	for(k=0; k<50; k++)
		if(document.getElementById('".$label_id[$key]."_element".$id."'+k))
		{
			document.getElementById('".$label_id[$key]."_element".$id."'+k).removeAttribute('checked');
			if(document.getElementById('".$label_id[$key]."_element".$id."'+k).value=='".addslashes($_POST[$label_id[$key]."_element".$id])."')
			{
				document.getElementById('".$label_id[$key]."_element".$id."'+k).setAttribute('checked', 'checked');
								
			}
		}
		else break;
	";
	if($is_other)
								$form_maker_front_end.=
	"
		show_other_input('".$label_id[$key]."','".$id."');
		document.getElementById('".$label_id[$key]."_other_input".$id."').value='".$_POST[$label_id[$key]."_other_input".$id]."';
	";
	
						break;
							}
			
			case "type_time":{
								$ss=$_POST[$label_id[$key]."_ss".$id];
								if(isset($_POST[$label_id[$key]."_ss".$id]))
								{
									$form_maker_front_end.=
	"if(document.getElementById('".$label_id[$key]."_hh".$id."'))
	{
		document.getElementById('".$label_id[$key]."_hh".$id."').value='".$_POST[$label_id[$key]."_hh".$id]."';
		document.getElementById('".$label_id[$key]."_mm".$id."').value='".$_POST[$label_id[$key]."_mm".$id]."';
		document.getElementById('".$label_id[$key]."_ss".$id."').value='".$_POST[$label_id[$key]."_ss".$id]."';
	}";
								}
								else
								{
									$form_maker_front_end.=
	"if(document.getElementById('".$label_id[$key]."_hh".$id."'))
	{
		document.getElementById('".$label_id[$key]."_hh".$id."').value='".$_POST[$label_id[$key]."_hh".$id]."';
		document.getElementById('".$label_id[$key]."_mm".$id."').value='".$_POST[$label_id[$key]."_mm".$id]."';
	}";
								}
								$am_pm=$_POST[$label_id[$key]."_am_pm".$id];
								if(isset($am_pm))
									$form_maker_front_end.= 
	"if(document.getElementById('".$label_id[$key]."_am_pm".$id."'))
		document.getElementById('".$label_id[$key]."_am_pm".$id."').value='".$_POST[$label_id[$key]."_am_pm".$id]."';
	";
								break;
							}
							
							
			case "type_date_fields":{
				$date_fields=explode('-',$_POST[$label_id[$key]."_element".$id]);
									$form_maker_front_end.=
	"if(document.getElementById('".$label_id[$key]."_day".$id."'))
	{
		document.getElementById('".$label_id[$key]."_day".$id."').value='".$date_fields[0]."';
		document.getElementById('".$label_id[$key]."_month".$id."').value='".$date_fields[1]."';
		document.getElementById('".$label_id[$key]."_year".$id."').value='".$date_fields[2]."';
	}";
							break;
							}
							
			case "type_date":
			case "type_own_select":					
			case "type_country":{
									$form_maker_front_end.=
	"if(document.getElementById('".$label_id[$key]."_element".$id."'))
		document.getElementById('".$label_id[$key]."_element".$id."').value='".addslashes($_POST[$label_id[$key]."_element".$id])."';
	";
							break;
							}
							
			default:{
							break;
						}
	
			}
		
	}
}





$form_maker_front_end.='	form_view_count'.$id.'=0;
	for(i=1; i<=30; i++)
	{
		if(document.getElementById(\''.$id.'form_view\'+i))
		{
			form_view_count'.$id.'++;
			form_view_max'.$id.'=i;
			document.getElementById(\''.$id.'form_view\'+i).parentNode.removeAttribute(\'style\');
		}
	}	
	if(form_view_count'.$id.'>1)
	{
		for(i=1; i<=form_view_max'.$id.'; i++)
		{
			if(document.getElementById(\''.$id.'form_view\'+i))
			{
				first_form_view'.$id.'=i;
				break;
			}
		}		
		generate_page_nav(first_form_view'.$id.', \''.$id.'\', form_view_count'.$id.', form_view_max'.$id.');
	}
	var RecaptchaOptions = {
theme: "'.$row->recaptcha_theme.'"
};
</script>
</form>';
 if($is_recaptcha) {
	/*	$document->addScriptDeclaration('var RecaptchaOptions = {
theme: "'.$row->recaptcha_theme.'"
};
');*/


$form_maker_front_end.='<div id="main_recaptcha" style="display:none;">';

// Get a key from https://www.google.com/recaptcha/admin/create
if($row->public_key)
	$publickey = $row->public_key;
else
	$publickey = '0';
$error = null;
$form_maker_front_end.=recaptcha_get_html($publickey, $error);


$form_maker_front_end.='</div>
    <script>
	recaptcha_html=document.getElementById(\'main_recaptcha\').innerHTML.replace(\'Recaptcha.widget = Recaptcha.$("recaptcha_widget_div"); Recaptcha.challenge_callback();\',"");
	document.getElementById(\'main_recaptcha\').innerHTML="";
	if(document.getElementById(\'wd_recaptcha'.$id.'\'))
	document.getElementById(\'wd_recaptcha'.$id.'\').innerHTML=recaptcha_html;
    </script>';




		}
}
else
			{
				
					
$form_maker_front_end.='<script type="text/javascript">'.str_replace ("
"," ",$row->javascript).'</script>';
$form_maker_front_end.='<style>'.str_replace('[SITE_ROOT]', plugins_url("",__FILE__),str_replace('.wdform_table1','.form_view',str_replace ("
"," ",$form_theme ))).'</style>';
				
				
				$form_maker_front_end.="<form name=\"form\" action=\"".$_SERVER['REQUEST_URI']."\" method=\"post\" id=\"form\" enctype=\"multipart/form-data\">
									<input type=\"hidden\" id=\"counter\" value=\"".$row->counter."\" name=\"counter\" />
									<input type=\"hidden\" id=\"Itemid\" value=\"".$Itemid."\" name=\"Itemid\" />";
                   $captcha_url=plugins_url("wd_captcha.php",__FILE__).'?digit=';
				   $captcha_rep_url=plugins_url("wd_captcha.php",__FILE__).'?r2='.mt_rand(0,1000).'&digit=';
				   			$rep1=array(
			"<!--repstart-->Title<!--repend-->",
			"<!--repstart-->First<!--repend-->",
			"<!--repstart-->Last<!--repend-->",
			"<!--repstart-->Middle<!--repend-->",
			"<!--repstart-->January<!--repend-->",
			"<!--repstart-->February<!--repend-->",
			"<!--repstart-->March<!--repend-->",
			"<!--repstart-->April<!--repend-->",
			"<!--repstart-->May<!--repend-->",
			"<!--repstart-->June<!--repend-->",
			"<!--repstart-->July<!--repend-->",
			"<!--repstart-->August<!--repend-->",
			"<!--repstart-->September<!--repend-->",
			"<!--repstart-->October<!--repend-->",
			"<!--repstart-->November<!--repend-->",
			"<!--repstart-->December<!--repend-->",
			$captcha_url,
			'class="captcha_img"',
			 plugins_url('images/refresh.png',__FILE__),
			 plugins_url('images/delete_el.png',__FILE__),
			 plugins_url('images/up.png',__FILE__),
			 plugins_url('images/down.png',__FILE__),
			 plugins_url('images/left.png',__FILE__),
			 plugins_url('images/right.png',__FILE__),
			 plugins_url('images/edit.png',__FILE__));
			$rep2=array(
			addslashes(__("Title","form_maker")),
			addslashes(__("First","form_maker")),
			addslashes(__("Last","form_maker")),
			addslashes(__("Middle","form_maker")),
			addslashes(__("January","form_maker")),
			addslashes(__("February","form_maker")),
			addslashes(__("March","form_maker")),
			addslashes(__("April","form_maker")),
			addslashes(__("May","form_maker")),
			addslashes(__("June","form_maker")),
			addslashes(__("July","form_maker")),
			addslashes(__("August","form_maker")),
			addslashes(__("September","form_maker")),
			addslashes(__("October","form_maker")),
			addslashes(__("November","form_maker")),
			addslashes(__("December","form_maker")),
			$captcha_rep_url,
			'class="captcha_img" style="display:none"',
			 plugins_url('images/refresh.png',__FILE__),
			'','','','','','');
			$untilupload = str_replace($rep1,$rep2,$row->form);
				   while(strpos($untilupload, "***destinationskizb")>0)
			{
				$pos1 = strpos($untilupload, "***destinationskizb");
				$pos2 = strpos($untilupload, "***destinationverj");
				$untilupload=str_replace(substr($untilupload, $pos1, $pos2-$pos1+22), "", $untilupload);
			}
				   $form_maker_front_end.=$untilupload;
				   $form_maker_front_end.="<script type=\"text/javascript\">
							function formOnload()
							{
								if(document.getElementById(\"wd_captcha_input\"))
									captcha_refresh('wd_captcha');
					for(t=0; t<". $row->counter."; t++)
						if(document.getElementById(t+\"_type\"))
							if(document.getElementById(t+\"_type\").value==\"type_map\")
								if_gmap_init(t+\"_element\", false);
							}							
							function formAddToOnload()
							{ 
								if(formOldFunctionOnLoad){ formOldFunctionOnLoad(); }
								formOnload();
							}							
							function formLoadBody()
							{
								formOldFunctionOnLoad = window.onload;
								window.onload = formAddToOnload;
							}							
							var formOldFunctionOnLoad = null;
							formLoadBody();
							";							
				if(isset($_POST["captcha_input"]))
				{						
					$captcha_input=$_POST["captcha_input"];
				}
				if(isset($_POST["counter"]))
				{						
					$counter=$_POST["counter"];
				}
				if(isset($counter))
				if (isset($captcha_input) or is_numeric($ok))
				{
				$session_wd_captcha_code=isset($_SESSION['wd_captcha_code'])?$_SESSION['wd_captcha_code']:'-';
				if($captcha_input!=$session_wd_captcha_code or is_numeric($ok))
				{
				for($i=0; $i<$counter; $i++)
				{
					if(isset($_POST[$i."_type"]))
					{						
						$type=$_POST[$i."_type"];
					}
					if(isset($_POST[$i."_type"]))
					{	
						switch ($type)
						{
						case "type_text":
						
						case "type_submitter_mail":{
											 $form_maker_front_end.= 
				"if(document.getElementById('".$i."_element"."').title!='".addslashes($_POST[$i."_element"])."')
				{	document.getElementById('".$i."_element"."').value='".addslashes($_POST[$i."_element"])."';
					document.getElementById('".$i."_element"."').style.color='#000000';
					document.getElementById('".$i."_element"."').style.fontStyle='normal !important';
				}
				";
											break;
										}
												
						case "type_textarea":{
											 $form_maker_front_end.= 
				"if(document.getElementById('".$i."_element"."').title!='".addslashes($_POST[$i."_element"])."')
				{	document.getElementById('".$i."_element"."').innerHTML='".addslashes($_POST[$i."_element"])."';
					document.getElementById('".$i."_element"."').style.color='#000000';
					document.getElementById('".$i."_element"."').style.fontStyle='normal';
				}
				";
									break;
										}
						case "type_password":{
											 $form_maker_front_end.= 
				"document.getElementById('".$i."_element"."').value='';
				";						break;
										}
						case "type_name":{
											if(isset($_POST[$i."_element_title"]))
											{
												 $form_maker_front_end.= 
				"document.getElementById('".$i."_element_title"."').value='".addslashes($_POST[$i."_element_title"])."';
				document.getElementById('".$i."_element_first"."').value='".addslashes($_POST[$i."_element_first"])."';
				document.getElementById('".$i."_element_last"."').value='".addslashes($_POST[$i."_element_last"])."';
				document.getElementById('".$i."_element_middle"."').value='".addslashes($_POST[$i."_element_middle"])."';
				";
											}
											else
											{
											 $form_maker_front_end.= 
				"document.getElementById('".$i."_element_first"."').value='".addslashes($_POST[$i."_element_first"])."';
				document.getElementById('".$i."_element_last"."').value='".addslashes($_POST[$i."_element_last"])."';
				";						}
											break;
										}
						case "type_checkbox":{
											 $form_maker_front_end.=
				"for(k=0; k<20; k++)
					if(document.getElementById('".$i."_element'+k))
						document.getElementById('".$i."_element'+k).removeAttribute('checked');
					else break;	";			for($j=0; $j<100; $j++)
											{
												if(isset($_POST[$i."_element".$j]))
															{
															 $form_maker_front_end.=
				"document.getElementById('".$i."_element".$j."').setAttribute('checked', 'checked');
				";									}
											}
											break;
											}
						case "type_radio":{
											 $form_maker_front_end.=
				"for(k=0; k<100; k++)
					if(document.getElementById('".$i."_element'+k))
					{
						document.getElementById('".$i."_element'+k).removeAttribute('checked');
						if(document.getElementById('".$i."_element'+k).value=='".addslashes($_POST[$i."_element"])."')
							document.getElementById('".$i."_element'+k).setAttribute('checked', 'checked');
					}
					else break;
				";						break;
										}
						case "type_time":{
											if(isset($_POST[$i."_ss"]))
											{
												 $form_maker_front_end.= 
				"document.getElementById('".$i."_hh"."').value='".$_POST[$i."_hh"]."';
				document.getElementById('".$i."_mm"."').value='".$_POST[$i."_mm"]."';
				document.getElementById('".$i."_ss"."').value='".$_POST[$i."_ss"]."';
				";					}
											else
											{
												 $form_maker_front_end.= 
				"document.getElementById('".$i."_hh"."').value='".$_POST[$i."_hh"]."';
				document.getElementById('".$i."_mm"."').value='".$_POST[$i."_mm"]."';
				";
											}
											if(isset($_POST[$i."_am_pm"]))
												 $form_maker_front_end.= 
				"document.getElementById('".$i."_am_pm').value='".$_POST[$i."_am_pm"]."';
				";						break;
										}										
						case "type_date":{	 $form_maker_front_end.="document.getElementById('".$i."_element"."').value='".$_POST[$i."_element"]."';
				";						break;
										}										
						case "type_date_fields":{
							$date_fields=explode('-',$_POST[$i."_element"]);
												 $form_maker_front_end.= 
				"document.getElementById('".$i."_day"."').value='".$date_fields[0]."';
				document.getElementById('".$i."_month"."').value='".$date_fields[1]."';
				document.getElementById('".$i."_year"."').value='".$date_fields[2]."';
				";						break;
										}										
					case "type_country":{
											$form_maker_front_end.="document.getElementById('".$i."_element').value='".addslashes($_POST[$i."_element"])."';
				";						break;
										}									
						case "type_own_select":{
												 $form_maker_front_end.=
				"document.getElementById('".$i."_element').value='".addslashes($_POST[$i."_element"])."';
				";
								break;
										}										
						case "type_file":{
										break;
									}				
						}
					}
				}
			}
		}
		
 $form_maker_front_end.="n=".$row->counter.";
	for(i=0; i<n; i++)
	{
		if(document.getElementById(i))
		{	
			for(z=0; z<document.getElementById(i).childNodes.length; z++)
				if(document.getElementById(i).childNodes[z].nodeType==3)
					document.getElementById(i).removeChild(document.getElementById(i).childNodes[z]);		
			if(document.getElementById(i).childNodes[7])
			{			
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
			}
		}
	}	
	for(i=0; i<=n; i++)
	{	
		if(document.getElementById(i))
		{
			type=document.getElementById(i).getAttribute(\"type\");
				switch(type)
				{	case \"type_text\":
					case \"type_password\":
					case \"type_submitter_mail\":
					case \"type_own_select\":
					case \"type_country\":
					case \"type_hidden\":
					case \"type_map\":
					{
						remove_add_(i+\"_element\");
						break;
					}					
					case \"type_submit_reset\":
					{
						remove_add_(i+\"_element_submit\");
						if(document.getElementById(i+\"_element_reset\"))
							remove_add_(i+\"_element_reset\");
						break;
					}					
					case \"type_captcha\":
					{	remove_add_(\"wd_captcha\");
						remove_add_(\"element_refresh\");
						remove_add_(\"wd_captcha_input\");
						break;
					}						
					case \"type_file_upload\":
						{	remove_add_(i+\"_element\");
							if(document.getElementById(i+\"_element\").value==\"\")
							{	
								seted=false;
								break;
							}
							ext_available=getfileextension(i);
							if(!ext_available)
								seted=false;										
								break;
						}						
					case \"type_textarea\":
						{
						remove_add_(i+\"_element\");							if(document.getElementById(i+\"_element\").innerHTML==document.getElementById(i+\"_element\").title || document.getElementById(i+\"_element\").innerHTML==\"\")
								seted=false;
								break;
						}						
					case \"type_name\":
						{						
						if(document.getElementById(i+\"_element_title\"))
							{
							remove_add_(i+\"_element_title\");
							remove_add_(i+\"_element_first\");
							remove_add_(i+\"_element_last\");
							remove_add_(i+\"_element_middle\");
								if(document.getElementById(i+\"_element_title\").value==\"\" || document.getElementById(i+\"_element_first\").value==\"\" || document.getElementById(i+\"_element_last\").value==\"\" || document.getElementById(i+\"_element_middle\").value==\"\")
									seted=false;
							}
							else
							{
							remove_add_(i+\"_element_first\");
							remove_add_(i+\"_element_last\");
								if(document.getElementById(i+\"_element_first\").value==\"\" || document.getElementById(i+\"_element_last\").value==\"\")
									seted=false;
							}
							break;
						}						
					case \"type_checkbox\":
					case \"type_radio\":
						{	is=true;
							for(j=0; j<100; j++)
								if(document.getElementById(i+\"_element\"+j))
								{
							remove_add_(i+\"_element\"+j);
									if(document.getElementById(i+\"_element\"+j).checked)
									{
										is=false;										
										break;
									}
								}
							if(is)
							seted=false;
							break;
						}						
					case \"type_button\":
						{
							for(j=0; j<100; j++)
								if(document.getElementById(i+\"_element\"+j))
								{
									remove_add_(i+\"_element\"+j);
								}
							break;
						}						
					case \"type_time\":
						{	
						if(document.getElementById(i+\"_ss\"))
							{
							remove_add_(i+\"_ss\");
							remove_add_(i+\"_mm\");
							remove_add_(i+\"_hh\");
								if(document.getElementById(i+\"_ss\").value==\"\" || document.getElementById(i+\"_mm\").value==\"\" || document.getElementById(i+\"_hh\").value==\"\")
									seted=false;
							}
							else
							{
							remove_add_(i+\"_mm\");
							remove_add_(i+\"_hh\");
								if(document.getElementById(i+\"_mm\").value==\"\" || document.getElementById(i+\"_hh\").value==\"\")
									seted=false;
							}
							break;
						}						
					case \"type_date\":
						{	
						remove_add_(i+\"_element\");
						remove_add_(i+\"_button\");						
							if(document.getElementById(i+\"_element\").value==\"\")
								seted=false;
							break;
						}
					case \"type_date_fields\":
						{	
						remove_add_(i+\"_day\");
						remove_add_(i+\"_month\");
						remove_add_(i+\"_year\");
						if(document.getElementById(i+\"_day\").value==\"\" || document.getElementById(i+\"_month\").value==\"\" || document.getElementById(i+\"_year\").value==\"\")
							seted=false;
								break;
					}
				}						
		}
	}	
function check_year2(id)
{
	year=document.getElementById(id).value;	
	from=parseFloat(document.getElementById(id).getAttribute('from'));	
	year=parseFloat(year);	
	if(year<from)
	{
		document.getElementById(id).value='';
		alert('".addslashes(__('The value of year is not valid','form_maker'))."');
	}
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
		if(atr[v].name.indexOf(\"add_\")==0)
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
function getfileextension(id) 
{ 
 var fileinput = document.getElementById(id+\"_element\"); 
 var filename = fileinput.value; 
 if( filename.length == 0 ) 
 return true; 
 var dot = filename.lastIndexOf(\".\"); 
 var extension = filename.substr(dot+1,filename.length); 
 var exten = document.getElementById(id+\"_extension\").value.replace(\"***extensionverj\"+id+\"***\", \"\").replace(\"***extensionskizb\"+id+\"***\", \"\");
 exten=exten.split(','); 
 for(x=0 ; x<exten.length; x++)
 {
  exten[x]=exten[x].replace(/\./g,'');
  exten[x]=exten[x].replace(/ /g,'');
  if(extension.toLowerCase()==exten[x].toLowerCase())
  	return true;
 }
 return false; 
} 
function check_required(but_type)
{
	if(but_type=='reset')
	{
	window.location.reload( true );
	return;
	}	
	n=".$row->counter.";
	ext_available=true;
	seted=true;
	for(i=0; i<=n; i++)
	{	
		if(seted)
		{		
			if(document.getElementById(i))
			    if(document.getElementById(i+\"_required\"))
				if(document.getElementById(i+\"_required\").value==\"yes\")
				{
					type=document.getElementById(i).getAttribute(\"type\");
					switch(type)
					{
						case \"type_text\":
						case \"type_password\":
						case \"type_submitter_mail\":
						case \"type_own_select\":
						case \"type_country\":
							{
								if(document.getElementById(i+\"_element\").value==document.getElementById(i+\"_element\").title || document.getElementById(i+\"_element\").value==\"\")
									seted=false;
									break;
							}							
						case \"type_file_upload\":
							{
								if(document.getElementById(i+\"_element\").value==\"\")
								{	
									seted=false;
									break;
								}
								ext_available=getfileextension(i);
								if(!ext_available)
									seted=false;											
									break;
							}							
						case \"type_textarea\":
							{
								if(document.getElementById(i+\"_element\").innerHTML==document.getElementById(i+\"_element\").title || document.getElementById(i+\"_element\").innerHTML==\"\")
									seted=false;
									break;
							}							
						case \"type_name\":
							{	
							if(document.getElementById(i+\"_element_title\"))
								{
									if(document.getElementById(i+\"_element_title\").value==\"\" || document.getElementById(i+\"_element_first\").value==\"\" || document.getElementById(i+\"_element_last\").value==\"\" || document.getElementById(i+\"_element_middle\").value==\"\")
										seted=false;
								}
								else
								{
									if(document.getElementById(i+\"_element_first\").value==\"\" || document.getElementById(i+\"_element_last\").value==\"\")
										seted=false;
								}
								break;	
							}							
						case \"type_checkbox\":
						case \"type_radio\":
							{
								is=true;
								for(j=0; j<100; j++)
									if(document.getElementById(i+\"_element\"+j))
										if(document.getElementById(i+\"_element\"+j).checked)
										{
											is=false;										
											break;
										}
								if(is)
								seted=false;
								break;
							}					
						case \"type_time\":
							{	
							if(document.getElementById(i+\"_ss\"))
								{
									if(document.getElementById(i+\"_ss\").value==\"\" || document.getElementById(i+\"_mm\").value==\"\" || document.getElementById(i+\"_hh\").value==\"\")
										seted=false;
								}
								else
								{
									if(document.getElementById(i+\"_mm\").value==\"\" || document.getElementById(i+\"_hh\").value==\"\")
										seted=false;
								}
								break;	
							}							
						case \"type_date\":
							{	
								if(document.getElementById(i+\"_element\").value==\"\")
									seted=false;
								break;
							}
						case \"type_date_fields\":
							{	
								if(document.getElementById(i+\"_day\").value==\"\" || document.getElementById(i+\"_month\").value==\"\" || document.getElementById(i+\"_year\").value==\"\")
									seted=false;
								break;
							}
							}						
				}
				else
				{	
					type=document.getElementById(i).getAttribute(\"type\");
					if(type==\"type_file_upload\")
						ext_available=getfileextension(i);
							if(!ext_available)
							seted=false;											
				}
		}
		else
		{		
			if(!ext_available)
				{alert('".addslashes(__('Sorry, you are not allowed to upload this type of file','form_maker'))."');
				break;}			
			x=document.getElementById(i-1+'_element_label');
			while(x.firstChild)
			{
				x=x.firstChild;
			}
			alert(x.nodeValue+' ".addslashes(__('field is required','form_maker'))."');
			break;
		}		
	}
	if(seted)
	for(i=0; i<=n; i++)
	{	
		if(document.getElementById(i))
			if(document.getElementById(i).getAttribute(\"type\")==\"type_submitter_mail\")
				if (document.getElementById(i+\"_element\").value!='')	if(document.getElementById(i+\"_element\").value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1)
				{		alert( \"".addslashes(__('This is not a valid email address','form_maker'))."\" );	
							return;
				}	
	}
	if(seted)
		create_headers();
}	
function create_headers()
{	form_=document.getElementById('form');
	n=".$row->counter.";
	for(i=0; i<n; i++)
	{	if(document.getElementById(i))
		{if(document.getElementById(i).getAttribute(\"type\")!=\"type_map\")
		if(document.getElementById(i).getAttribute(\"type\")!=\"type_captcha\")
		if(document.getElementById(i).getAttribute(\"type\")!=\"type_submit_reset\")
		if(document.getElementById(i).getAttribute(\"type\")!=\"type_button\")
			if(document.getElementById(i+'_element_label'))
			{	var input = document.createElement('input');
				input.setAttribute(\"type\", 'hidden');
				input.setAttribute(\"name\", i+'_element_label');
				input.value=i;
				form_.appendChild(input);
				if(document.getElementById(i).getAttribute(\"type\")==\"type_date_fields\")
				{		var input = document.createElement('input');
						input.setAttribute(\"type\", 'hidden');
						input.setAttribute(\"name\", i+'_element');					input.value=document.getElementById(i+'_day').value+'-'+document.getElementById(i+'_month').value+'-'+document.getElementById(i+'_year').value;
					form_.appendChild(input);
				}
			}
		}
	}
form_.submit();
}	
</script>
</form>";
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
			}
return $form_maker_front_end;
}
	

