<?php





 /**

 * @package Form Maker

 * @author Web-Dorado

 * @copyright (C) 2011 Web-Dorado. All rights reserved.

 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html

 **/

  

 // Direct access must be allowed

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
require_once( WP_LOAD_PATH . 'wp-load.php');

global $wpdb;

$form_id=$_REQUEST['form_id'];

 if(!isset($_SERVER['HTTP_REFERER'])){

header('Location: ../../index.php');

 exit;

 }

 

 $query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."formmaker_submits where form_id= %d",$form_id);

	$rows = $wpdb->get_results($query);

	$n=count($rows);

	$labels= array();

	for($i=0; $i < $n ; $i++)



	{

		$row = &$rows[$i];

		if(!in_array($row->element_label, $labels))

		{

			array_push($labels, $row->element_label);

		}

	}

	$label_titles=array();

	$sorted_labels= array();

 

 $query_lable = "SELECT label_order,title FROM ".$wpdb->prefix."formmaker where id=$form_id ";



	$rows_lable = $wpdb->get_results($query_lable);
	

	

	$ptn = "/[^a-zA-Z0-9_]/";

			$rpltxt = "";

			

		

	$title=preg_replace($ptn, $rpltxt, $rows_lable[0]->title);

	

	$sorted_labels_id= array();

	$sorted_labels= array();

	$label_titles=array();

	if($labels)

	{

		

		$label_id= array();

		$label_order= array();

		$label_order_original= array();

		$label_type= array();

		

		///stexic

		$label_all	= explode('#****#',$rows_lable[0]->label_order);

		$label_all 	= array_slice($label_all,0, count($label_all)-1);   

		

		

		

		foreach($label_all as $key => $label_each) 

		{

			$label_id_each=explode('#**id**#',$label_each);

			array_push($label_id, $label_id_each[0]);

			

			$label_oder_each=explode('#**label**#', $label_id_each[1]);

			

			array_push($label_order_original, $label_oder_each[0]);

			

			$ptn = "/[^a-zA-Z0-9_]/";

			$rpltxt = "";

			$label_temp=preg_replace($ptn, $rpltxt, $label_oder_each[0]);

			array_push($label_order, $label_temp);

			

			array_push($label_type, $label_oder_each[1]);



		

			//echo $label."<br>";

			

		}

		

		foreach($label_id as $key => $label) 

			if(in_array($label, $labels))

			{

				array_push($sorted_labels, $label_order[$key]);

				array_push($sorted_labels_id, $label);

				array_push($label_titles, $label_order_original[$key]);

			}

			



	}

	

 	$m=count($sorted_labels);

	$group_id_s= array();

	$l=0;

	 

//var_dump($label_titles);

	if(count($rows)>0 and $m)

	for($i=0; $i <count($rows) ; $i++)

	{

	

		$row = &$rows[$i];

	

		if(!in_array($row->group_id, $group_id_s))

		{

		

			array_push($group_id_s, $row->group_id);

			

		}

	}

 



  

 $data=array();



 

 for($www=0;  $www < count($group_id_s); $www++)

	{	

	$i=$group_id_s[$www];

	

		$temp= array();

		for($j=0; $j < $n ; $j++)

		{

		

			$row = &$rows[$j];

			

			if($row->group_id==$i)

			{

			

				array_push($temp, $row);

			}

		}

		

		

		

		$f=$temp[0];

		$date=$f->date;

		$ip=$f->ip;

 $data_temp['Submit date']=$date;

 $data_temp['Ip']=$ip;

  

 

 $ttt=count($temp);

 

// var_dump($temp);

		for($h=0; $h < $m ; $h++)

		{		

			

			for($g=0; $g < $ttt ; $g++)

			{			

				$t = $temp[$g];

				if($t->element_label==$sorted_labels_id[$h])

				{

					if(strpos($t->element_value,"*@@url@@*"))

					{

						$new_file=str_replace("*@@url@@*",'', $t->element_value);

						$new_filename=explode('/', $new_file);

						$data_temp[$label_titles[$h]]=$new_file;

					}

					else
						if(strpos($t->element_value,"***br***"))
						{	
							$data_temp[$label_titles[$h]]= substr(str_replace("***br***",', ', $t->element_value), 0, -2);
						}	
						else
							if(strpos($t->element_value,"***map***"))
							{	
								$data_temp[$label_titles[$h]]= 'Longitude:'.substr(str_replace("***map***",', Latitude:', $t->element_value), 0, -2);
							}	
							else
								$data_temp[$label_titles[$h]]=$t->element_value;

				}

			}

			

			

		}

		$data[]=$data_temp;

 }

// var_dump($data);

 



  function cleanData(&$str)

  {

    $str = preg_replace("/\t/", "\\t", $str);

    $str = preg_replace("/\r?\n/", "\\n", $str);

    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';

  }



  // file name for download

	$filename = $title."_" . date('Ymd') . ".xml";



  header("Content-Disposition: attachment; filename=\"$filename\"");

  header("Content-Type:text/xml,  charset=utf-8");



 

  $flag = false;

  /*

  foreach($data as $row) {

    if(!$flag) {

      # display field/column names as first row

      echo implode("\t", array_keys($row)) . "\r\n";

      $flag = true;

    }

    array_walk($row, 'cleanData');

    echo implode("\t", array_values($row)) . "\r\n";

  }

  */

echo '

<?xml version="1.0" encoding="utf-8" ?> 

  <form title="'.$title.'">';

 

  foreach ($data as $key1 => $value1){

  echo  '<submition>';

	 

  foreach ($value1 as $key => $value){

  echo  '<field title="'.$key.'">';

		echo   '<![CDATA['.$value."]]>";

 echo  '</field>';

  }  

  

   echo  '</submition>';

  }

	

	  echo '';

echo ' </form>

';

 

  

?>