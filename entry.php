<?php
/**
 *	Entry template
 *	----------------------------------------------------------------------------
 */ ?>
<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
	<?php
	if( ( $title = get_the_title() ) ): ?>
	<!-- entry-header -->
	<header class="entry-header">
		<h1 class="entry-title" itemprop="name">
			<?php
			if( is_singular() ): 
				echo $title;
			else: ?>
			<a href="<?php the_permalink(); ?>"
			   title="Read &mdash; <?php echo esc_attr( $title ); ?>"
			   rel="bookmark"><?php echo $title; ?></a> <?php
			endif; ?>
		</h1>
	</header> <?php
	endif; ?>
	
	<!-- entry-content -->
	<div class="entry-content" itemprop="articleBody">
		<?php
		if( is_singular() ) {
			the_content();
		}
		else {
			the_content( 'Read post &rarr;' );
		} ?>
	</div>
	
	<!-- entry-footer -->
	<footer class="entry-footer">
		<div class="entry-meta">
			<span class="entry-author">
				by <?php the_author_link(); ?>
			</span>
			
			<?php
			if( ! is_page() ): ?>
			<span class="entry-category">
				in <?php the_category( ', ' ); ?>
			</span> <?php
			endif;

			da_byline_cta(); ?>
			
			<meta itemprop="datePublished"
				  content="<?php the_time( 'Y-m-d' ); ?>">
			<meta itemprop="dateModified"
				  content="<?php the_modified_time( 'Y-m-d' ); ?>">
		</div>
		
			  
		<?php
		if( is_singular() && function_exists( 'dvk_social_sharing' ) ): ?>
		<!-- entry-social-sharing -->
		<div class="entry-social-sharing top-separated">
			<?php echo dvk_social_sharing(); ?>
		</div>	<?php
		endif;
		
		if( is_single() ) {
			da_afterpost_cta();
		} ?>
	</footer>
</article>