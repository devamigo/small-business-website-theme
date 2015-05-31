<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?php wp_title( '&mdash;', true, 'right' ); ?></title>
<?php wp_head(); ?>

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body <?php body_class(); ?>>
<!-- page-wrapper -->
<div class="page-wrapper">
	<!-- site-header -->
	<header class="site-header" role="banner">
		<div class="content-wrapper clr">
			<!-- brand -->
			<div class="brand col-xs-12 col-sm-6">
				<h1 class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" 
					   rel="home"><?php da_logo(); ?></a>
				</h1>
				
				<h2 class="site-description screen-reader-text">
					<?php bloginfo( 'description' ); ?>
				</h2>
			</div>
		
			<?php da_header_cta(); ?>
		</div>
	</header>
			
	<!-- primary-nav -->
	<nav class="primary-nav">
		<div class="content-wrapper clr">
			<a href="#" class="toggle"><i class="fa fa-bars"></i></a>
			
			<?php
			wp_nav_menu( array(
				'container'			=> false,
				'depth'				=> 2,
				'theme_location'	=> 'primary',
			) ); ?>
		</div>
	</nav>
	
	<?php da_featurebox_cta(); ?>
	
	<!-- site-content -->
	<div class="site-content">
		<div class="content-wrapper clr">
			<!-- main-content -->
			<div class="main-content col-xs-12 col-md-8" role="main">
				<?php include_once( $da_content_template ); ?>
			</div>
			
			<?php
			if( ! is_404() && 
				! is_page_template() && 
				is_active_sidebar( 'da_sidebar_widgets' ) ): ?>
			<!-- sidebar-widgets -->
			<div class="sidebar-widgets col-xs-12 col-md-4" role="complemetary">
				<?php dynamic_sidebar( 'da_sidebar_widgets' ); ?>
			</div> <?php
			endif;
			
			global $wp_query;
			if( 1 < $wp_query->max_num_pages ): ?>
			<!-- content-nav -->
			<nav class="content-nav col-xs-12">
				<div class="row">
					<!-- newer-posts -->
					<div class="newer-posts col-xs-6">
						<?php previous_posts_link( '&larr; Newer posts' ); ?>
					</div>
					
					<!-- older-posts -->
					<div class="older-posts col-xs-6">
						<?php next_posts_link( 'Older posts &rarr;' ); ?>
					</div>
				</div>
			</nav> <?php
			endif; ?>
		</div>
	</div>
	
	<?php
	if( is_front_page() && is_active_sidebar( 'da_frontpage_widgets' ) ): ?>
	<!-- frontpage-widgets -->
	<div class="frontpage-widgets">
		<div class="content-wrapper clr">
			<?php dynamic_sidebar( 'da_frontpage_widgets' ); ?>
		</div>
	</div> <?php
	endif;
	
	da_footerbox_cta(); ?>
	
	<!-- site-footer -->
	<footer class="site-footer" role="contentinfo">
		<?php
		if( is_active_sidebar( 'da_footer_widgets' ) ): ?>
		<!-- footer-widgets -->
		<div class="footer-widgets">
			<div class="content-wrapper clr">
				<?php dynamic_sidebar( 'da_footer_widgets' ); ?>
			</div>
		</div> <?php
		endif; ?>
		
		<!-- copyright -->
		<div class="copyright">
			<div class="content-wrapper clr">
				<?php da_copyright(); ?>
			</div>
		</div>
	</footer>
</div>

<!-- scripts -->
<?php wp_footer(); ?>
</body>
</html>