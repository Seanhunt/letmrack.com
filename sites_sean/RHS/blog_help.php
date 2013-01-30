<?php
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Blog Admin Help</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css">

<SCRIPT LANGUAGE="JavaScript"> 
<!--
window.resizeTo(800,600);
-->
</SCRIPT>
</head>

<body bgcolor="#CCCCCC">
<p><a name="BlogSelector" id="BlogSelector"></a></p>
<table width="95%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="left" class="admin14Bold">Blog Selector</td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="90%" border="1" align="center" cellpadding="10" cellspacing="0" class="admin12Regular">
        <tr> 
          <td valign="top">
<p>&nbsp;This page allows the selection of the blog to which you 
              want to post. The selection page will only appear for users that 
              have rights to post in multiple blogs.</p>
            <p>Click on the link for the blog you want to edit or post to...</p>
            <p><b>Help - </b>On each of the screens a context sensitive Help link 
              is available at the top center that will provide help about that 
              dialog. </p>
            <p>The help system opens a new window.</p></td>
        </tr>
      </table>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<p><a name="BlogAdminOverview" id="BlogAdminOverview"></a></p>
<table width="95%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="left" class="admin14Bold">Blog Admin Overview</td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="90%" border="1" align="center" cellpadding="10" cellspacing="0" class="admin12Regular">
        <tr> 
          <td><p><b>What is a blog ?</b> </p>
            <p><a href="http://en.wikipedia.org/wiki/Blog">The WikiPedia says 
              this:</a> </p>
            <p><b><i>&quot;A blog is a user-generated website where entries are 
              made in journal style and displayed in a reverse chronological order.&quot;</i></b></p>
            <p>This blog editor will allow you to create edit and delete 
              posts from your blogs on this site.</p>
            <p>Blogs have two types of users, Contributors and Moderators. Contributors have less rights and menu entries then moderators.</p>
            <p>If a blog has been configured with links the blogs moderator will be able to edit 
              them.</p>
            <p>Each Contributor can edit only their own posts and links. All postings 
              and links are displayed with the posters user ID at the bottom.</p>
            <p>There are several types of blogs supported, Calendar, Last In First 
              Out - LIFO, Music and Ordered - Random. </p>
            <p>The calendar blog is sorted by the event date so that the most 
              recent dates appear at the top of the listing scroll. </p>
            <p>The LIFO blog is sorted so that the most recent post is displayed 
              at the top of the scroll, the reverse chronological order described 
              in the Wiki definition above.</p>
            <p>Music is a LIFO blog with special functions for MP3 file sharing.</p>
            <p>Ordered Random allows some entries to be displaied in fixed positions and others to be in random order. This is used for advertisements.</p>
            <p><b>Help - </b>On each of the screens a context sensitive Help link 
              is available at the top center that will provide help about that 
              dialog. </p>
            <p>The help system opens a new window.</p>
            </td>
        </tr>
      </table>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<p><a name="NewItem" id="NewItem"></a> </p>
<table width="95%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="left" class="admin14Bold">Start New Posting</td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="90%" border="1" align="center" cellpadding="10" cellspacing="0" class="admin12Regular">
        <tr> 
          <td><p>This dialog allows to to create a posting. Postings become publically 
              visible immediately on non-moderated forums. Your user ID will appear 
              at the bottom of your posts.</p>
            <p>Depending on the configuration of the site you may not see some of these (in <em>italics</em>) in your editor.</p>
            <p>Post responsibily and respect copyrighted materials. Your postings are bound by the terms of use for the specific site.</p>
            <table width="770" border="1" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="12%" height="40" align="right" valign="middle" class="admin12Bold"> 
                  <span class="admin18BoldRed">*</span>&nbsp;&nbsp;Headline:&nbsp;&nbsp; 
                </td>
                <td width="88%" align="left" valign="middle" class="admin12Bold"> 
                  &nbsp;
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                    <tr> 
                      <td>The headline for your posting. This line will appear 
                        at the top of the posting, it will also be the link text 
                        in the table of contents. </td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td height="40" align="right" valign="middle" class="admin12Bold">SubHead:&nbsp;&nbsp;</td>
                <td align="left" valign="middle" class="admin12Bold"> 
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                    <tr> 
                      <td>The subheading for your post. This will appear below 
                        the headline in smaller type then the headline.</td>
                    </tr>
                  </table> </td>
              </tr>
              <?php
		if($blogType=="CALENDAR")
			{
			print	'
				  <tr> 
					<td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Event 
					  Date&nbsp;&nbsp; </td>
					<td align="left" valign="middle">&nbsp;&nbsp; 
					';

			  	//	DisplayDateSelector($blEventDate);
				
				DisplayFutureDateSelector($blEventDate,2);
				
			print	'
					  &nbsp; </td>
					  </tr>
					  <tr> 
						<td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Event 
						  Time &nbsp;</td>
						<td align="right" valign="middle"><input name="blEventTime" type="text" id="blEventTime2" value="' . $blEventTime . '" size="80"> 
						  &nbsp;</td>
					  </tr>
					';
			
			}

		
		?>
              <tr> 
                <td height="40" align="right" valign="middle" class="admin12Bold"><em>Posting 
                  Type:</em> &nbsp;&nbsp;</td>
                <td align="left" valign="middle" class="admin12Bold"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                    <tr> 
                      <td><p>Plain text or HTML. If you don't know what this is 
                          set it to its default setting of Plain Text. </p>
                        <p>With the Plain Text setting returns in the text are 
                          translated to HTML for posting and back to returns for 
                          editing.</p>
                        <p>In HTML mode returns in the text are ignored. You can 
                          use simple HTML in your posts &lt;br&gt; &lt;p&gt; &lt;b&gt; 
                          &lt;i&gt; when the post type is set to HTML.</p></td>
                    </tr>
                  </table> </td>
              </tr>
              <tr> 
                <td height="40" align="right" valign="middle" class="admin12Bold"><em>Posting 
                  in File:</em>&nbsp;&nbsp;</td>
                <td align="left" valign="middle" class="admin12Bold"> 
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                    <tr> 
                      <td><p>If you have the posting as a text (.txt) or HTML 
                          (.htm, .html) file on your computer you can browse to 
                          the file using this and it will be uploaded and inserted 
                          into the posting. When this is done any copy in the 
                          Copy box will be ignored.</p>
                        <p>There is a limit to the length of the posting that 
                          you can type or paste into the box. If you reach that 
                          limit the file upload method is necessary as it is not 
                          limited in length.</p></td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td align="right" valign="top" class="admin12Bold"> 
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td align="right" class="cts12Bold">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td height="25" align="right" class="admin12Bold"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Copy:&nbsp;&nbsp;</td>
                    </tr>
                  </table></td>
                <td align="right" valign="top"> 
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td align="right"> <table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                          <tr> 
                            <td valign="middle"><p>Type or paste your posting 
                                copy here. It is good to prepare longer posts 
                                in your favorite word processer and then paste 
                                it into the Copy box as this allows spell checking 
                                and good editting controls.</p>
                              <p>There is a limit to the length of the posting 
                                that you can type or paste into the box. If you 
                                reach that limit the file upload method is necessary 
                                as it is not limited in length.</p></td>
                          </tr>
                        </table>
                        &nbsp; </td>
                    </tr>
                  </table>
                  </td>
              </tr>
            </table>
            <p class="admin14Bold">Calendar Type Blogs</p>
            <table width="770" border="1" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="12%" height="40" align="right" valign="middle" class="admin12Bold">Event 
                  Date: &nbsp; </td>
                <td width="88%" align="left" valign="middle" class="admin12Bold">
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                    <tr> 
                      <td>The headline for your posting. This line will appear 
                        at the top of the posting, it will also be the link text 
                        in the table of contents. The list is sorted by this value.</td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td height="40" align="right" valign="middle" class="admin12Bold">Event 
                  Time :&nbsp;&nbsp;</td>
                <td align="left" valign="middle" class="admin12Bold"> 
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                    <tr> 
                      <td><p>The time of the event. This is a terse description 
                          of the event time like: 6:00-9:30pm. </p>
                        <p>Look at the calendar on this site for the times that 
                          are posted to see what is possible as it can vary per 
                          the sites design.</p></td>
                    </tr>
                  </table></td>
              </tr>
              <?php
		if($blogType=="CALENDAR")
			{
			print	'
				  <tr> 
					<td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Event 
					  Date&nbsp;&nbsp; </td>
					<td align="left" valign="middle">&nbsp;&nbsp; 
					';

			  	//	DisplayDateSelector($blEventDate);
				
				DisplayFutureDateSelector($blEventDate,2);
				
			print	'
					  &nbsp; </td>
					  </tr>
					  <tr> 
						<td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Event 
						  Time &nbsp;</td>
						<td align="right" valign="middle"><input name="blEventTime" type="text" id="blEventTime2" value="' . $blEventTime . '" size="80"> 
						  &nbsp;</td>
					  </tr>
					';
			
			}

		
		?>
            </table>
            
          </td>
        </tr>
      </table>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
      
    </td>
  </tr>
</table>
<p><a name="FileSharingPost" id="FileSharingPost"></a> </p>
<table width="95%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="left" class="admin14Bold">File Sharing Post</td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="90%" border="1" align="center" cellpadding="10" cellspacing="0" class="admin12Regular">
        <tr> 
          <td><p>This dialog allows to to create a posting that will offer a file 
              to be downloaded by the reader. This is available only to Moderators and Administrators.</p>
            <p>Postings become publically visible immediately. Your user ID will appear at the bottom of your posts.</p>
            <p>Post responsibily and respect copyrighted materials. Your postings are bound by the terms of use for the specific site.</p>
            <table width="770" border="1" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="12%" height="40" align="right" valign="middle" class="admin12Bold"> 
                  <span class="admin18BoldRed">*</span>&nbsp;&nbsp;Headline:&nbsp;&nbsp; 
                </td>
                <td width="88%" align="left" valign="middle" class="admin12Bold"> 
                  &nbsp; <table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                    <tr> 
                      <td>The headline for your posting. This line will appear 
                        at the top of the posting, it will also be the link text 
                        in the table of contents. </td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td height="40" align="right" valign="middle" class="admin12Bold">SubHead:&nbsp;&nbsp;</td>
                <td align="left" valign="middle" class="admin12Bold"> <table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                    <tr> 
                      <td>The subheading for your post. This will appear below 
                        the headline in smaller type then the headline.</td>
                    </tr>
                  </table></td>
              </tr>
              <?php
		if($blogType=="CALENDAR")
			{
			print	'
				  <tr> 
					<td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Event 
					  Date&nbsp;&nbsp; </td>
					<td align="left" valign="middle">&nbsp;&nbsp; 
					';

			  	//	DisplayDateSelector($blEventDate);
				
				DisplayFutureDateSelector($blEventDate,2);
				
			print	'
					  &nbsp; </td>
					  </tr>
					  <tr> 
						<td height="40" align="right" valign="middle" bgcolor="#FFFFFF" class="cts12Bold"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Event 
						  Time &nbsp;</td>
						<td align="right" valign="middle"><input name="blEventTime" type="text" id="blEventTime2" value="' . $blEventTime . '" size="80"> 
						  &nbsp;</td>
					  </tr>
					';
			
			}

		
		?>
              <tr> 
                <td height="40" align="right" valign="middle" class="admin12Bold">File 
                  to Share:&nbsp;&nbsp;</td>
                <td align="left" valign="middle" class="admin12Bold"> <table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                    <tr> 
                      <td><p>Browse to the file you want to share on your local 
                          hard drive by clicking the Browse button. This file 
                          will be uploaded as part of your post.</p>
                        </td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td align="right" valign="top" class="admin12Bold"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td align="right" class="cts12Bold">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td height="25" align="right" class="admin12Bold"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Copy:&nbsp;&nbsp;</td>
                    </tr>
                  </table></td>
                <td align="right" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td align="right"> <table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                          <tr> 
                            <td valign="middle"><p>Type or paste your posting 
                                copy here. It is good to prepare longer posts 
                                in your favorite word processer and then paste 
                                it into the Copy box as this allows spell checking 
                                and good editting controls.</p>
                              <p>There will be a link to the file automatically 
                                created at the bottom of your post.</p>
                              <p>&nbsp;</p>
                              </td>
                          </tr>
                        </table>
                        &nbsp; </td>
                    </tr>
                  </table></td>
              </tr>
            </table>
            <p class="admin14Bold">&nbsp;</p>
            </td>
        </tr>
      </table>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<p><a name="EditPicker" id="EditPicker"></a> </p>
<table width="95%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="left" class="admin14Bold">Edit and Delete Postings</td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="90%" border="1" align="center" cellpadding="10" cellspacing="0" class="admin12Regular">
        <tr> 
          <td><p>This allows you to edit and delete your postings. Each posting 
              is displayed in a box with links at the top left for Editing and 
              Right for Deletion.</p>
            <p>If you are deleting you will get a prompt asking wether you would 
              like to delete the post or not.</p>
            <p>The edit link will take to the new/edit dialog so that you can 
              edit the posting. This will stay in the editor and allow you to 
              modify the post multiple times to correct that darned error.</p>
            <p>When you are done editing use the Back link to return to the Edit/Delete 
              posting picker dialog.</p>
            <p>Moderators and Administrators can edit or delete any posting. When an Administrative 
              user views the edit/delete pick list they will see the postings 
              of all the users.</p></td>
        </tr>
      </table>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<p><a name="SetAdOrder" id="SetAdOrder"></a> </p>
<table width="95%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="left" class="admin14Bold">Set Ad Order</td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="90%" border="1" align="center" cellpadding="10" cellspacing="0" class="admin12Regular">
        <tr> 
          <td valign="top">
<p>This allows you to set the order of the ads in an Advertisement type blog.</p>
            <p>An ad item can either have random order or a fixed column placement 
              order. The fixed items are displayed in their column with the specified 
              ads at the top. The random ads are displayed below the fixed placement 
              items.</p>
            <p>Clicking an item in either list will move it to the other list. 
              This allows some shuffling without setting all to Random and starting 
              over. </p>
            <p>The &quot;Set All to Random&quot; button will set all ads to be 
              randomly ordered. </p>
            <p>After setting all to Random picking items from the Random list 
              will allow you to place them in to the Fixed list in the desired 
              order.</p>
            </td>
        </tr>
      </table>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<p><a name="GraphicAdUpload" id="GraphicAdUpload"></a> </p>
<table width="95%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="left" class="admin14Bold">Graphic Ad Upload</td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="90%" border="1" align="center" cellpadding="10" cellspacing="0" class="admin12Regular">
        <tr> 
          <td valign="top"> <p>This allows you to easily upload a graphic file 
              into an Advertisement type blog.</p>
            <p>A single column file is 160 px wide and can be any height.</p>
            <p>Inserting a picture that is too wide will break the page. </p>
            <p>Make sure the file is 160 px or less wide and then check your work 
              on a page in which the ad appears.</p>
            <p>If you enter a headline it will be shown above the graphic. </p>
            <p>The graphic will be linked to the clients web site via the target 
              URL that you provide. This link will open in a new window.</p>
            </td>
        </tr>
      </table>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<p><a name="ViewAllPosts" id="ViewAllPosts"></a></p>
<table width="95%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="left" class="admin14Bold">View All Postings</td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="90%" border="1" align="center" cellpadding="10" cellspacing="0" class="admin12Regular">
        <tr> 
          <td>This allows you to view all of the current posts by all users of 
            this forum.</td>
        </tr>
      </table>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<p><a name="AddLink" id="AddLink"></a></p>
<table width="95%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="left" class="admin14Bold">Add Link</td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="90%" border="1" align="center" cellpadding="10" cellspacing="0" class="admin12Regular">
        <tr> 
          <td><p>On blogs that have been configured to have links you can add 
              a link using this dialog. Your user ID will appear below each link 
              you post.</p>
            <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="18%" height="40" align="right" valign="middle" class="admin12Bold"> 
                  <div align="right"><span class="admin18BoldRed">*</span>&nbsp;&nbsp;Link 
                    URL:&nbsp;&nbsp;</div></td>
                <td width="82%" align="left" valign="middle" class="cts12Bold"> 
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                    <tr>
                      <td><p>The URL of the link. We will supply the http:// so 
                          all you need the the part after the http://.</p>
                        </td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td height="40" align="right" valign="middle" class="admin12Bold"><span class="admin18BoldRed">*</span>&nbsp;Site 
                  Name: &nbsp;</td>
                <td align="left" valign="middle"> 
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                    <tr> 
                      <td><p>The name of the site. This will appear as the link 
                          in the scroll of links. Short is good as this goes in 
                          a narrow column in most cases.</p></td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td align="right" valign="top" class="admin12Bold"> 
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td align="right" class="cts12Bold">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td height="25" align="right" class="admin12Bold">Link Description: 
                        &nbsp;&nbsp;</td>
                    </tr>
                  </table></td>
                <td align="right" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td align="left" valign="top">
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="admin12Regular">
                          <tr> 
                            <td><p>The description of the site. Why would a user 
                                of this forum want to visit this site ?</p></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
                  &nbsp; </td>
              </tr>
            </table>
            
          </td>
        </tr>
      </table>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<p><a name="EditDeleteLinks" id="EditDeleteLinks"></a></p>
<table width="95%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="left" class="admin14Bold">Edit and Delete Links</td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="90%" border="1" align="center" cellpadding="10" cellspacing="0" class="admin12Regular">
        <tr> 
          <td><p>This allows you to edit and delete your links. Each link is displayed 
              in a box with links at the top left for Editing and Right for Deletion.</p>
            <p>If you are deleting you will get a prompt asking wether you would 
              like to delete the post or not.</p>
            <p>The edit link will take to the new/edit dialog so that you can 
              edit the link copy. This will stay in the editor and allow you to 
              modify the post multiple times to correct that pesty typo.</p>
            <p>When you are done editing use the Back link to return to the Edit/Delete 
              link picker dialog.</p>
            <p>Administrators can edit or delete any link. When an Administrative 
              user views the edit/delete pick list they will see the links of 
              all the users.</p></td>
        </tr>
      </table>
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
