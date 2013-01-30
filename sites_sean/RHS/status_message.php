<?php
// ---------------------------------------------------------------------------------------------------
// status_message.php
//
// Displays a javascript alert for the var $statusMessage
//
// Revision: 	020906 First Revision
//				100306 gets its own var
//
//
// Depends:	cl_lib
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

$statusMessage		=	GetPassedValue("StatusMessage");

// ---------------------------------------------------------------------------------------------------------------------------------------
// function DisplayStatusMessage($statusMessage)
//
// Displays system status messages via a javascript alert box
// ---------------------------------------------------------------------------------------------------------------------------------------

function DisplayStatusMessage($statusMessage)
{
// if there is a message from the system display it in a javascript alert box...
if(strlen($statusMessage))
	{
	print 	'
			<script language="JavaScript"><!-- Hide Script 
			javascript:alert("' . $statusMessage . '");
			//End Hide Script-->
			</script>
			';
	
	}
}



?>
