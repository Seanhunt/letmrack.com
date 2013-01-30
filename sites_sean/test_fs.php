<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

include "local_info.php";
include "RHS/db_lib.php";
include "RHS/cl_lib.php";
include 'RHS/gallery_parms.php';	
include 'RHS/html_lib.php';	
include 'RHS/file_lib.php';
include 'RHS/gallery_lib.php';	
include 'RHS/pw_parms.php';	
include 'RHS/pw_lib.php';
include 'RHS/select_controls.php';	
include 'RHS/status_message.php';	
include "RHS/admin_lib.php";
include "RHS/appdev_revision.php";



	print	'
			  <!-- Start FORSALE Data -->
              <p class="admin15Bold">What purchase options are available ? You must list 
                at least one price and description per item.</p>
              <table width="95%" border="1" cellspacing="0" cellpadding="5">
                <tr align="center" class="admin12Bold"> 
                  <td width="5%" class="admin12Bold">#</td>
                  <td width="10%">Size</td>
                  <td width="15%">Price</td>
                  <td>Description</td>
                  <td>Description Two</td>
                </tr>
                <tr align="center"> 
                  <td class="admin12Bold">1</td>
                  <td>             
<input name="gaHeightOne" type="text" id="gaHeightOne" value="' .$gaHeightOne . '" size="10" />                  </td>
                  <td><span class="admin18BoldRed">*</span>$ 
					  <input name="gaPriceOne" type="text" id="gaPriceOne" value="' .$gaPriceOne . '" size="9" maxlength="8"></td>
						  <td><span class="admin18BoldRed">*</span> 
						  <input name="gaDescriptionOne" type="text" id="gaDescriptionOne" value="' .$gaDescriptionOne . '" size="30"></td>
						  <td>&nbsp;
                          
<input name="gaEditionTypeOne" type="text" id="gaEditionTypeOne" value="' .$gaEditionTypeOne . '" size="30">                          							  </td>
							</tr>
								';
					
					
					print	'
							<tr align="center"> 
							  <td class="admin12Bold">2</td>
							  <td><input name="gaHeightTwo" type="text" id="gaHeightTwo" value="' .$gaHeightTwo . '" size="10" /></td>
							  <td>&nbsp;$ 
							<input name="gaPriceTwo" type="text" id="PriceOne8" value="' . $gaPriceTwo . '" size="9" maxlength="8"></td>
						  <td>&nbsp;&nbsp; 
						  
						  <input name="gaDescriptionTwo" type="text" id="gaDescriptionTwo" value="' . $gaDescriptionTwo . '" size="30"></td>
						  
						  
						  <td>&nbsp;&nbsp; 
							
<input name="gaEditionTypeTwo" type="text" id="gaEditionTypeTwo" value="' .$gaEditionTypeTwo . '" size="30">                                   </td>
                </tr>
                <tr align="center"> 
                  <td class="admin12Bold">3</td>
                  <td><input name="gaHeightThree" type="text" id="gaHeightThree" value="' .$gaHeightThree . '" size="10" /></td>
                  <td>&nbsp;$ 
                    <input name="gaPriceThree" type="text" id="PriceOne9" value="' . $gaPriceThree . '" size="9" maxlength="8"></td>
                  <td>&nbsp;&nbsp; <input name="gaDescriptionThree" type="text" id="gaDescriptionThree" value="' . $gaDescriptionThree . '" size="30"></td>
                  <td>&nbsp;&nbsp; 
				  

<input name="gaEditionTypeThree" type="text" id="gaEditionTypeThree" value="' .$gaEditionTypeThree . '" size="30">                                    </td>
                </tr>
                <tr align="center"> 
                  <td class="admin12Bold">4</td>
                  <td><input name="gaHeightFour" type="text" id="gaHeightFour" value="' .$gaHeightFour . '" size="10" /></td>
                  <td>&nbsp;$ 
                    <input name="gaPriceFour" type="text" id="gaPriceFour" value="' .$gaPriceFour . '" size="9" maxlength="8"></td>
                  <td>&nbsp;&nbsp; <input name="gaDescriptionFour" type="text" id="gaDescriptionFour" value="' . $gaDescriptionFour . '" size="30"></td>
                  <td>&nbsp;&nbsp;
<input name="gaEditionTypeFour" type="text" id="gaEditionTypeFour" value="' .$gaEditionTypeFour . '" size="30" /></td>
				</tr>
						  </table>
						  <!-- End FORSALE Data -->
							';



?>