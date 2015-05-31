<?php
/**
 *	Template name: Full Width Template
 *	----------------------------------------------------------------------------
 */
while( have_posts() ) {
	the_post();
	get_template_part( 'entry' );
}