<?php
/**
 *	Category archive template
 *	----------------------------------------------------------------------------
 */ ?>
<div class="category-header">
	<h1 class="category-title"><?php single_cat_title(); ?></h1>
	
	<?php
	if( $cat_description = category_description() ){
		printf( '<div class="category-description">%1$s</div>', 
			$cat_description );
	} ?>
</div> <?php

include_once( get_template_directory() . '/index.php' );