
<?php include (TEMPLATEPATH . '/bottom.php'); ?>	

<div id="footer">
	
	
<div class="fleft">
Design: <a href="http://lasvegascondohighrise.com/">Las Vegas High Rise Condos</a>  <br/>
Copyright &copy; <?php echo date('Y');?> <?php bloginfo('name');?><br/>
<?php $foot = get_option('marina_foot'); echo stripslashes($foot); ?>
</div>

<div class="fright">
<a href="<?php bloginfo('rss2_url'); ?>">Subscribe to Posts</a> | <a href="<?php bloginfo('comments_rss2_url'); ?>">Subscribe to Comments</a> 
</div>
<div class="clear"></div>
<?php wp_footer(); ?>
</div>
</div>


</body>
</html>      