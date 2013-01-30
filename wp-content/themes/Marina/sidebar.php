<div class="right">

<div class="featbox">
	<h3 class="sidetitl"> Twitter updates</h3>
<?php
$twit = get_option('marina_twit'); 
include('twitter.php');?>
<?php if(function_exists('twitter_messages')) : ?>
       <?php twitter_messages("$twit") ?>
       <?php endif; ?>
</div> 


<?php include (TEMPLATEPATH . '/sponsors.php'); ?>	

<div class="sidebar">
<ul>
	<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar') ) : else : ?>
	<?php endif; ?>
</ul>
</div>

</div>