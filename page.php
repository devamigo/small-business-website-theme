<?php
/**
 *	Page entry template
 *	----------------------------------------------------------------------------
 */
while( have_posts() ) {
	the_post();
	get_template_part( 'entry' );
}