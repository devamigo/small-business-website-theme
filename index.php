<?php
/**
 *	Entry index template
 *	----------------------------------------------------------------------------
 */
while( have_posts() ) {
	the_post();
	get_template_part( 'entry' );
}

global $wp_query;
if( 1 < $wp_query->max_num_pages ): ?>
<!-- content-nav -->
<nav class="content-nav row" role="navigation">
	<!-- newer -->
	<div class="newer col-xs-6">
		<?php previous_posts_link( '&larr; Newer posts' ); ?>
	</div>
	
	<!-- older -->
	<div class="older col-xs-6">
		<?php next_posts_link( 'Older posts &rarr;' ); ?>
	</div>
</nav> <?php
endif;
