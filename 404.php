<?php
/**
 *	404 template
 *	----------------------------------------------------------------------------
 */ ?>
<div class="nothing-found wrapper-sm">
	<h1>Page not found.</h1>
	
	<p>
		Oops! The page your are looking for couldn&rsquo;t be found.
		You can start over from the 
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">home page</a> 
		or try searching below.
	</p>
	
	<div class="horizontal-form">
		<?php get_search_form(); ?>
	</div>
</div>