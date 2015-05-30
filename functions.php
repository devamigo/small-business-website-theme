<?php
/**
 *	Set content width
 *	----------------------------------------------------------------------------
 */
if( ! isset( $content_width ) ) {
	$content_width = 1200;	// pixels	
}

/**
 *	Setup theme features
 *	----------------------------------------------------------------------------
 */
add_action( 'after_setup_theme', 'da_theme_setup' );

function da_theme_setup() {
	add_theme_support( 'custom-background', array(
		'default-color'	=> 'E6E6E6',
		'default-image'	=> '',
	) );
	
	add_theme_support( 'custom-header', array(
		'default-image'	=> get_template_directory_uri() . '/img/logo.jpg',
		'flex-width'	=> true,
		'header-text'	=> false,
		'height'		=> 104,		// pixels
	) );
	
	add_theme_support( 'html5', array(
		'comment-form', 'comment-list', 'search-form',
	) );
	
	add_theme_support( 'post-thumbnails' );
	
	register_nav_menu( 'primary', 'Primary Navigation Menu' );
}

/**
 *	Enqueue theme assets
 *	----------------------------------------------------------------------------
 */
add_action( 'wp_enqueue_scripts', 'da_theme_assets' );

function da_theme_assets() {
	wp_enqueue_style( 'da-style', get_stylesheet_uri() );
	wp_enqueue_style( 'da-fonts', apply_filters(
		'da_fonts',
		'http://fonts.googleapis.com/css?family=Noto+Sans:400,400italic,700|Domine:700'
	) );
	wp_enqueue_style( 'da-icons', 
		'//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'
	);
	
	if( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_script( 'da-fitvids', 
		get_template_directory_uri() . '/js/jquery.fitvids.js',
		array( 'jquery' ), null, true );
	wp_enqueue_script( 'da-match-height', 
		get_template_directory_uri() . '/js/jquery.matchHeight.js',
		array( 'jquery' ), null, true );
	wp_enqueue_script( 'da-script', 
		get_template_directory_uri() . '/js/script.js',
		array( 'da-fitvids', 'da-match-height' ), null, true );
}

/**
 *	Register widget areas
 *	----------------------------------------------------------------------------
 */
add_action( 'widgets_init', 'da_widget_areas' );

function da_widget_areas() {
	$widget_areas = array(
		'da_sidebar_widgets' 	=> 'Sidebar Widgets',
		'da_frontpage_widgets'	=> 'Front Page Widgets',
		'da_footer_widgets'		=> 'Footer Widgets',
	);
	
	foreach( $widget_areas as $id => $name ) {
		register_sidebar( array(
			'id'			=> $id,
			'name'			=> $name,
			'before_widget'	=> '<div class="widget">',
			'after_widget'	=> '</div>',
			'before_title'	=> '<h3 class="widget-title">',
			'after_title'	=> '</h3>',
		) );
	}
}

/**
 *	Columnize widget areas
 *	----------------------------------------------------------------------------
 */
add_filter( 'dynamic_sidebar_params', 'da_columnize_widgets_areas' );

function da_columnize_widgets_areas( $params ) {
	$widget_area = $params[0]['id'];
	
	if( 'da_frontpage_widgets' == $widget_area ||
		'da_footer_widgets' == $widget_area ) {
		$total_widgets = wp_get_sidebars_widgets();
        $widget_count  = count( $total_widgets[ $widget_area ] );
		
		$before_widget = '<div class="col-xs-12 col-md-' . 
			floor( 12 / $widget_count ) . '">';
		$after_widget  = '</div>';
		
		$params[0]['before_widget'] = $before_widget . 
			$params[0]['before_widget'];
		$params[0]['after_widget']  = $after_widget . 
			$params[0]['after_widget'];
	}
	
	return $params;
}

/**
 *	Customizer options
 *	----------------------------------------------------------------------------
 */
add_action( 'customize_register', 'da_customizer_options' );

function da_customizer_options( $wp_customize ) {
	$colors = array(
		'da_primary_accent_color'	=> array(
			'label'		=> 'Primary Accent Color',
			'default'	=> '#c22b3a',
		),
		'da_secondary_accent_color'	=> array(
			'label'		=> 'Secondary Accent Color',
			'default'	=> '#2bc2b3',
		),
	);
	
	foreach( $colors as $setting => $color ) {
		$wp_customize->add_setting( $setting, array(
			'default'	=> $color['default'],
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize, $setting, array(
				'label'		=> $color['label'],
				'section'	=> 'colors',
				'settings'	=> $setting,
			)
		) );
	}
	
	$wp_customize->add_setting( 'da_on_accent_over', array(
		'default'	=> '-25',
	) );
	$wp_customize->add_control( new WP_Customize_Control(
		$wp_customize, 'da_on_accent_over', array(
			'label'		=> 'On Hover',
			'section'	=> 'colors',
			'settings'	=> 'da_on_accent_over',
			'type'		=> 'select',
			'choices'	=> array( 
				'-33.334' 	=> 'Darken Accent Color', 
				'33.334'	=> 'Lighten Accent Color' 
			),
		)
	) );
}

/**
 *	Customizer output
 *	----------------------------------------------------------------------------
 */
add_action( 'wp_head', 'da_customizer_output' );

function da_customizer_output() {
	$on_accent_over = get_theme_mod( 'da_on_accent_over', '-33.334' );
	
	$primary_accent_color = 
		get_theme_mod( 'da_primary_accent_color', '#c22b3a' );
	$secondary_accent_color = 
		get_theme_mod( 'da_secondary_accent_color', '#2bc2b3' );
	$alt_primary_accent_color = 
		da_get_alternate_color( $primary_accent_color, $on_accent_over );
	$alt_secondary_accent_color =
		da_get_alternate_color( $secondary_accent_color, $on_accent_over );
		
	ob_start(); ?>
<style type="text/css">
a {
	border-color: <?php echo $primary_accent_color ?>;
	color: <?php echo $primary_accent_color ?>;
}
a:hover {
	border-color: <?php echo $alt_primary_accent_color ?>;
	color: <?php echo $alt_primary_accent_color ?>;
}
input[type=submit], .btn {
	background-color: <?php echo $secondary_accent_color ?>;
	border-color: <?php echo $secondary_accent_color ?>;
	text-shadow: 1px 1px 0 <?php echo $alt_secondary_accent_color ?>;
}
input[type=submit]:hover, .btn:hover {
	background-color: <?php echo $alt_secondary_accent_color ?>;
	border-color: <?php echo $alt_secondary_accent_color ?>;
}
blockquote, pre {
	border-color: <?php echo $secondary_accent_color ?>;
}
blockquote:after, pre:after, .colored {
	color: <?php echo $secondary_accent_color ?>;
}
.page-wrapper {
	border-color: <?php echo $primary_accent_color ?>;
}
.site-title a {
	color: 	<?php echo $secondary_accent_color ?>;
}
.site-title a:hover, .entry-title a:hover {
	color: 	<?php echo $primary_accent_color ?>;
}
.primary-nav, .primary-nav .sub-menu {
	background-color: <?php echo $primary_accent_color ?>;
}
.primary-nav a:hover,
.primary-nav .current-menu-item > a, .primary-nav .current-menu-parent > a, 
.primary-nav .current_page_item > a, .primary-nav .current_page_parent > a {
	background-color: <?php echo $alt_primary_accent_color ?>;
}
</style> <?php
	echo "\n" . ob_get_clean() . "\n";
}

 
/**
 *	Darken customizer color
 *	----------------------------------------------------------------------------
 */
function da_get_alternate_color( $hex, $change = -25 ) {
    // Change should be between -255 and 255. Negative = darker, positive = lighter
    $change = max( -255, min( 255, $change ) );

    // Normalize into a six character long hex string
    $hex = str_replace( '#', '', $hex );
    if ( strlen( $hex ) == 3 ) {
        $hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . 
			str_repeat( substr( $hex, 1, 1), 2 ) .
			str_repeat( substr( $hex, 2, 1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split( $hex, 2 );
    $return = '#';

    foreach( $color_parts as $color ) {
        $color   = hexdec( $color ); // Convert to decimal
        $color   = max( 0, min( 255, $color + $change ) ); // Adjust color
        $return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // Make two char hex code
    }

    return $return;
}

/**
 *	Custom templating
 *	----------------------------------------------------------------------------
 */
add_filter( 'template_include', 'da_custom_templating' );

function da_custom_templating( $template ) {
	global $da_content_template;
	$da_content_template = $template;
	
	return get_template_directory() . '/base.php';
}

/**
 *	Custom body classes
 *	----------------------------------------------------------------------------
 */
add_filter( 'body_class', 'da_body_class' );

function da_body_class( $classes ) {
	$classes = array( 'custom-background' );
	
	if( get_header_image() ) {
		$classes[] = 'custom-header';
	}
	
	if( is_404() ) {
		$classes[] = 'error-404';
	}
	
	if( is_front_page() ) {
		$classes[] = 'front-page';
	}
	
	if( is_home() ) {
		$classes[] = 'index';
	}
	elseif( is_single() ) {
		$classes[] = 'single';
	}
	elseif( is_page() ) {
		$classes[] = 'page';
		$classes[] = 'page-' . get_queried_object_id();
	}
	
	if( is_404() || is_page() || ! is_active_sidebar( 'da_sidebar_widgets' ) ) {
		$classes[] = 'no-sidebar';
	}
	
	return $classes;
}

/**
 *	Custom post classes
 *	----------------------------------------------------------------------------
 */
add_filter( 'post_class', 'da_post_class' );

function da_post_class( $classes ) {
	$classes = array( 'entry' );
	
	if( is_page() ) {
		$classes[]	= 'page-entry';
	}
	elseif( is_single() ) {
		$classes[]	= 'single-entry';
	}
	
	return $classes;
}

/**
 *	Enable shortcodes in text widgets
 *	----------------------------------------------------------------------------
 */
add_filter('widget_text', 'do_shortcode');

/**
 *	Remove generator tag
 *	----------------------------------------------------------------------------
 */
add_filter( 'the_generator', '__return_false' );
 
/**
 *	Remove admin bar
 *	----------------------------------------------------------------------------
 */
add_filter( 'show_admin_bar', '__return_false' );
 
/**
 *	Cleanup <title> tag
 *	----------------------------------------------------------------------------
 */
add_action( 'wp_title', 'da_title', 10, 2 );

function da_title( $title, $sep ) {
	$title .= get_bloginfo( 'name' );
	
	$tagline = get_bloginfo( 'description', 'display' );
	if( $tagline && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $tagline";
	}
	
	return $title;
}

/**
 *	Cleanup <head> tag
 *	----------------------------------------------------------------------------
 */
add_action( 'init', 'da_head' );

function da_head() {
	remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wp_generator' );

    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );

    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );

    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
	
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}

/**
 *	Cleanup admin dashboard
 *	----------------------------------------------------------------------------
 */
add_action( 'admin_init', 'da_admin_init' );

function da_admin_init() {
	// clean dashboard
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'core' );  
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'core' );  
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'core' );  
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'core' );  
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'core' );  
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'core' );
	
	// clean edit screen
	remove_meta_box( 'authordiv', 'post', 'normal' );
	remove_meta_box( 'commentsdiv', 'post', 'normal' );
	remove_meta_box( 'postcustom', 'post', 'normal' );
	remove_meta_box( 'revisionsdiv', 'post', 'normal' );
	remove_meta_box( 'tagsdiv-post_tag', 'post', 'normal' );
	remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
	
	// clean admin menu
	remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag' );
}
 
/**
 *	Remove unwanted widgets
 *	----------------------------------------------------------------------------
 */
add_action( 'widgets_init', 'da_remove_widgets' );

function da_remove_widgets() {
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_RSS' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
}

/**
 *	Remove jetpack sharing
 *	----------------------------------------------------------------------------
 */
add_action( 'loop_start', 'da_remove_share' );

function da_remove_share() {
	remove_filter( 'the_content', 'sharing_display', 19 );
    remove_filter( 'the_excerpt', 'sharing_display', 19 );
	
    if ( class_exists( 'Jetpack_Likes' ) ) {
        remove_filter( 'the_content', 
			array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
    }	
}
 
/**
 *	Reset image sizes
 *	----------------------------------------------------------------------------
 */
add_action( 'after_setup_theme', 'da_image_sizes' );

function da_image_sizes() {
	update_option( 'large_size_w', 0 );
	update_option( 'large_size_h', 0 );
	
	update_option( 'medium_size_w', 0 );
	update_option( 'medium_size_h', 0 );
	
	update_option( 'thumbnail_size_w', 0 );
	update_option( 'thumbnail_size_h', 0 );
}
 
/**
 *	Remove plugin styles
 *	----------------------------------------------------------------------------
 */
add_action( 'wp_print_styles', 'da_remove_plugin_styles' );

function da_remove_plugin_styles() {
	$plugin_styles_to_remove = array(
		'contact-form-7',
		'contact-form-7-rtl',
		'sharedaddy',
		'sharing',
	);
	
	foreach( $plugin_styles_to_remove as $plugin_style ) {
		wp_deregister_style( $plugin_style );		
	}
}
 
/**
 *	Remove gallery functionality
 *	----------------------------------------------------------------------------
 */
add_action( 'admin_footer-post-new.php', 'da_disable_media_gallery' );
add_action( 'admin_footer-post.php', 'da_disable_media_gallery' );

function da_disable_media_gallery() 
{ ?>
<script type="text/javascript">
jQuery(document).ready( function($) {
	$(document.body).one( 'click', '.insert-media', function( event ) {
		$(".media-menu").find("a:contains('Gallery')").remove();
	});
});
</script> <?php
}
 
/**
 *	Logo
 *	----------------------------------------------------------------------------
 */
function da_logo() {
	$logo = get_bloginfo( 'name' );
	
	if( ( $header_image = get_header_image() ) ) {
		$logo = sprintf( 
			'<img src="%1$s" alt="%2$s"><span class="screen-reader-text">%3$s</span>',
			esc_url( $header_image ), esc_attr( $logo ), $logo );
	}
	
	echo $logo;
}
 
/**
 *	Header call to action
 *	----------------------------------------------------------------------------
 */
function da_header_cta() {
	$content = apply_filters( 'da_header_cta', false );
	
	if( $content ) {
		echo 
			'<div class="header-cta col-xs-6 show-sm">' . 
				$content . 
			'</div>';
	}
}
 
/**
 *	Feature box call to action
 *	----------------------------------------------------------------------------
 */
function da_featurebox_cta() {
	if( ! is_front_page() ) {
		return;
	}

	$content = apply_filters( 'da_featurebox_cta', false );
	
	if( $content ) {
		echo 
			'<div class="featurebox-cta">' . 
				'<div class="content-wrapper wrapper-sm clr">' . 
					$content . 
				'</div>' . 
			'</div>';
	}
}
 
/**
 *	Entry byline call to action
 *	----------------------------------------------------------------------------
 */
function da_byline_cta() {
	$content = apply_filters( 'da_byline_cta', false );
	
	if( $content ) {
		echo 
			'<span class="entry-byline-cta">' . 
				$content . 
			'</span>';
	}
}
 
/**
 *	After post call to action
 *	----------------------------------------------------------------------------
 */
function da_afterpost_cta() {
	$content = apply_filters( 'da_afterpost_cta', false );
	
	if( $content ) {
		echo 
			'<div class="top-separated">' .
				'<div class="afterpost-cta">' . 
					$content . 
				'</div>' .
			'</div>';
	}
}
 
/**
 *	Footer call to action
 *	----------------------------------------------------------------------------
 */
function da_footerbox_cta() {
	$content = apply_filters( 'da_footerbox_cta', false );
	
	if( $content ) {
		echo 
			'<div class="footerbox-cta">' . 
				'<div class="content-wrapper wrapper-sm clr">' . 
					$content . 
				'</div>' . 
			'</div>';
	}
}
 
/**
 *	Copyright
 *	----------------------------------------------------------------------------
 */
function da_copyright() {
	$content = apply_filters( 
		'da_copyright', 
		sprintf( 
			'&copy %3$s <a href="%2$s" rel="home">%1$s</a>. All rights reserved.<br>' .
			'Powered by <a href="http://wordpress.org/">Wordpress</a> &amp; ' .
			'<a href="http://devamigo.com/free-small-business-website/">Devamigo</a>.<br>',
			get_bloginfo( 'name' ), esc_url( home_url( '/' ) ), Date( 'Y' )
		) 
	);
	
	echo $content;
}