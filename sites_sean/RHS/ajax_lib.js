//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548

var xmlHttp

function GetXmlHttpObject()
{
var xmlHttp=null;

//alert("FOOOO-");

try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 //Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }

//alert("BARRRRR");

return xmlHttp;
}

// presumes the existence of an endpoint that is ready to deal
// set up the URI to fetch the data
// "ajax_target.php?TableName=APPDEV_PublicVoteTabulation&Index=" + recordNumber +"&Field0=FileName&Field1=Count&Field2=Score";
