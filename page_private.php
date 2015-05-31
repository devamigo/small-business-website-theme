<?php
/**
 *	Template name: Private Template
 *	----------------------------------------------------------------------------
 */
while( have_posts() ) {
	the_post();
	get_template_part( 'entry' );
}