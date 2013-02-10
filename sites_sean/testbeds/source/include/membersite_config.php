<?PHP
require_once("./include/fg_membersite.php");

$fgmembersite = new FGMembersite();

//Provide your site name here
$fgmembersite->SetWebsiteName('letmrack.com');

//Provide the email address where you want to get notifications
$fgmembersite->SetAdminEmail('admin@letmrack.com');

//Provide your database login details here:
//hostname, user name, password, database name and table name
//note that the script will create the table (for example, fgusers in this case)
//by itself on submitting register.php for the first time
$fgmembersite->InitDB(/*hostname*/'localhost',
                      /*username*/'letmrack_sean',
                      /*password*/'gwalchmai',
                      /*database name*/'letmrack_sean',
                      /*table name*/'users');

//For better security. Get a random string from this link: http://tinyurl.com/randstr
// and put it here
$fgmembersite->SetRandomKey('OVYvHz2gYLMIyLe');

?>