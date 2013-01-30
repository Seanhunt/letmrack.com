<form method="get" id="searchform" action="<?php echo home_url(); ?>/">
<input type="text" value="Search..." onblur="if (this.value == '')

{this.value = 'Search...';}"

onfocus="if (this.value == 'Search...')

{this.value = '';}" name="s" id="s" />

<input type="submit" id="searchsubmit" value="Search">

</form>
