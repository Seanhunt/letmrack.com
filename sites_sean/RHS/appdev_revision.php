<?php
// --------------------------------------------------------------------------------------------------------------
// appdev_revision.php
//
// This is my revision notes file as well as the rev number symbol
//
// 2.0.3	added rev number file
//			added rev number to admin footer
//			moving template files to /templates directory
//			adding admin special pages to admin home to handle site specific special admin issues
//			fixed spelling of jewelry in gallery_add.php
//			added underline to gallery UI link def
//			added site name to admin title
//			tri-furcated the new user stuff to eliminate unsed properties
//			added password_admin_user.php to create admin and super users
//			added password_blog_user.php
//			GetGalleryProfile, moved template to templates and changed default arg
//			moved the following to templates and replaced references to have relative path
//			templates/blog_t_picker_calendar.html
//			templates/blog_t_picker_lifo.html
//			templates/blog_t_viewer_calendar.html
//			templates/blog_t_viewer_lifo.html
//
// 2.1.0
//
//			moving files to dirs RHS, templates, messages so it is no longer
//			a flat directory model to increase modularity.
//			added personalization of mail sub portal pages
//			completed basic functional template
//			added file sharing posts for LIFO blogs
//			added specials in the admin home to show local special admin functions
//			added help for the super user functions
//			totally rebuilt the granular security system so it is not a kludge
//			redid the super user - user management dialogs so that they were clear
//			rebuilt the mail system and added templates for the message and message banner
//			fixed bugs in the ecard system that made the CR's get lost when you backed up a page.
//			added smart login and eliminated the three login pages. Everyone uses the same door now.
//			made all dir specs lowercase
//			added file upload to the mail console 
//			changed blog links to have proper names rather then the raw table names			
//			fixed a selection bug that I found in the A+ site in image.php
//
// 2.1.1
//
//			Cleaned up template site filenames
//			added local.css and removed all system css from client code
//			New gallery user now gives the gallery a title this as NULL caused problems elesewhere
//			fixed blog_create.php it had a slight problem
//			there were several functions in there and I moved them to blog_lib
//			added the debug message viewer to the super user menu and fixed the table, the newer schema had gotten lost.
//			fixed the calendar blog chron process
//			added resend user settings to super user menu
//			added simple file uploads to gallery_services
//			changed gallery_add to adhere to simple upload flag
//			added simple upload flag gSimpleFileUploads to local_info
//			deprecated preview gallery profile
//			fixed graphic bugs in gallery UI back buttons
//			moved template files for built-in gallery pages to the RHS directory
//			moved the gallery assets dir into RHS/images and changed up the assets for builtin use 
//			added a close link to the built-in preview gallery
//			added file resizing to gallery profiles, it now wants a file > 300 pixels
//			removed a bunch of required fields from the artist profile.
//
// 2.1.2
//
//			added RK feature to return gallery edit back to last image viewed
//			reviewd help files for accuracy and added a few items as well as more verbage on the existing items.
//			added stock robots.txt
//			added favicon.psd to extras folder
//			added blog name to headline of all blog pages
//			rechecked the gallery pages for banner link graphic bug grrrr.
//			This was test deployed to A+ as 2.1.2
//
// 2.1.3	
//			bugs were fixed from 2.1.2
//			all admin/gallery/blog home windows offer close window as the exit option
//			This is deployed in 3m live and A+ test
//			found a bad bug in the building of the edit link and fixed it moving me up to 
// 2.1.4
//			fixed bug in edit link from image lib picker BADDIE!!!
//			Making a release at this level
//			Redeploying to 3m and A+
//			Deploying to TSM and ND
//
// 2.1.5
//			fixed bug in bloglib <--- TROUBLE has canned table def that went stale...
//			Added type MUSIC to blog header schema
//			Added blog_table_definition.sql.txt that is loaded by Blog Create table
//			hopefully this will make the maintenance of that table def less of a problem
//			by getting rid of the def in code where it went stale
//			blog_lib now has a depend to file_lib the headers need a FIX!!!! this will create breakage.
//			Fixed messages on the blog server to only report on fail except on file upload
//			Downloads stats system and download_lib 
//			Top downloads viewer page in the admin
//			Shortened pause time in FormRedirect, didnt help performance much
//			2.1.5a was deployed to thestikman.com
//
// 2.1.6
//			Added voting in the gallery with an Ajax callback system to update the 
//			UI widget after the user votes.
//			This has a number of file depends, see image.php for example
// 2.1.7
//			Fixed template problem in blog admin viewer resulting in no toc or links
// 			Fixed bug in mailing list editor, subs1-3 not getting updated on modify
//			Fixed data table decoration to allow test and production data tables in one instance
//			This added lines to local_info.php
//			GenerateMailingListMessageB was calling a high level db call and trashing the handle.
//			I replaced the high level calls with the low level calls
//			Fixed bug where test list was getting the mailing flag. It should not get flagged.
//
// 2.1.8
//			Adding details to the gallery builder.
//			*** NEW VAR IN LOCAL INFO **
//			Fixed error return on gallery_services on unsupported mode
//			it now will send you to the sites home page
//			fixed bug in nvpGetFileNameFromURIString causing it to return blank file name if no ?
//			in the URI.
//			Fixed bug in prototype of FormRedirect($uriString,$method="GET",$debugFlag=false)
//			no ill effects from it yet but there it was, no quotes around GET
//			Added noRobots arg to the redirect so I can prevent robots from following
//			redirects added to a site to support deprecated pages.
//			fixed global gTestingEnabled in the seo lib that controlled the printing of dev tags
//		
// PATCH .2	092207
//			Added Open with retry routine
//
// PATCH .3 100407
//			Added query in admin_home as tables were not showing up.
//	
// PATCH .4 102907 on newarkdays.org
//
// 2.1.7.4	Added trap in mail_send_contact_message to make sure the message length is > 0
//
// 			Added several modules to create a picture package selling type of gallery, no backend bulk
// 			loader yet but the sales assistant part is done.
// 			These require a new PayPal var in local info for the default place for the logo image.
//
//			Added function IsBlankString($string) to further combat the blank message prankster sitch.
// 			this wont stop the attack as it is unstoppable but it will stop the blank message.
//			Now they will have to type some garbage.
//			I also added the sender IP and Host Name to the final message so that the owner can see if 
// 			it is the same person/station running the prank.
//
//	PATCH .5 110607 on newarkdays.org, updated mail_lib.php and mail_send_contact_message.php
//
// 2.1.8.rc2 was deployed for rdvproductions.
//
// 2.1.9	Deployed to CTG
//			Added a more robust captcha system which stopped the bot attacks. 
//			See the update steps for update info.
//
// 2.2.0	Slashed the crufty stylesheet in the client pages to one that 
//			has a proper pattern of inheritence. 
//
// Have reports of bug in join mailing list with 2.1.9 - could not reproduce. FOUND AND FIXED!
//
// 2.2.0 - 022208 	Reworked the beginning of login to get rid of spurious call to 
//					UserExists() attempting to improve login performance in 
//					password_services.php
//
//					Deprecated PrintBodyTag() in all client pages and from local_info
//					what was I thinking back then ???
//
//					Added the module wz_tooltip.js to the application and added its link in 
//					all client modules. Call onmouseover=Tip(Tip Here) to get good tool tip 
//					action
//
//					Added a SEO_Text blog and changed get SEO text to return a random entry 
//					from that blog if it had multiple entries. The entry is in plain text
//					and is styled in the container.
//
//					And lots more...
//
// 2.2.0.1			Adding tinyMCE editor for the blog text box and alternate traps
//					in its service routine bifurcated from a var in local info.
//					I am doing this so I can go back if its all bad...
//
// 2.2.0.2			Added tinyMCE to the music blog, it needs to ge everywhere a text filed is displayed
//					Did some work on the music download and included 3 new SR specs in BlogLoadItem()
//					this allows music blogs to have a customized interface.
//
// 2.2.0.3			Worked on titles and flow, cleaned up the blog and gallery admins.
//					Fixed re-org gallery flow
//					Alison reported the ? bug in the blog editor, though I had it but no...
//					I got it this time. bang. daid.
//
// 2.2.0.4			Added Query to table show
//					fixed Show/Hide Galleries it was missing a header file
// 					Fixed bug in around usage of PasswordIsOk()
//
// 2.2.0.5			Added two part blog posts
//
// 2.2.0.6 			******* SCHEMA UPDATE REQUIRED *****
//					Added large and small image size to the gallery profile table, 
//					these are referred to when the image is resampled.
//					The image dirs are still called 500x and 200x as these are referred
//					to specifically throughout the line code of sites.
//					Now that simply means thumb image and large image as changing that
//					will create work when updating sites. ERG!
//					
//
// 2.2.0.7			I balled up the reflow of the blog and gallery editors and am fixing 
//					it in this revision. I rolled it back so it should be good as it was
//					going to be a sweeping change for little gain.
//
// 2.2.0.8			Fixed a bug in the forsale galleries where it made all sizes be size 1.
//
// Watch the revision of the file blog_table_definition.sql.txt and blog_link_definition.sql.txt if these are 
// stale its all bad
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// --------------------------------------------------------------------------------------------------------------



$gRevisionNumber		=	"2.2.0.8";
?>
