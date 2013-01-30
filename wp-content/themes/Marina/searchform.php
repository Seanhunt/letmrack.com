
<div id="search">
	<form method="get" id="searchform" action="<?php bloginfo('home'); ?>" >
	<input id="s" class="rounded" type="text" name="s" onfocus="if(this.value=='search site'){this.value=''};" onblur="if(this.value==''){this.value='search site'};" value="<?php echo wp_specialchars($s, 1); ?>" />
	<input id="searchsubmit" type="submit" value="" />
	</form>
</div>
