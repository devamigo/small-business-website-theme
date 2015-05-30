<?php
/**
 *	Single entry template
 *	----------------------------------------------------------------------------
 */
while( have_posts() ) {
	the_post();
	get_template_part( 'entry' );
}

if( comments_open() || '0' != get_comments_number() ) {
	comments_template();
}