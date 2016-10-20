<?php
/**
 * BadJohnny functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage BadJohnny
 * @since BadJohnny 1.0
 */


// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 625;

/**
 * BadJohnny setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * BadJohnny supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since BadJohnny 1.0
 */
function badjohnny_setup() {

    /*Editor Style Support*/
    add_editor_style();

	/*
	 * Makes BadJohnny available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on BadJohnny, use a find and replace
	 * to change 'badjohnny' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'badjohnny', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	add_theme_support( "title-tag" );

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'badjohnny' ) );

	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 ); // Unlimited height, soft crop

	add_theme_support( 'custom-logo', array(
	'height'      => 100,
	'width'       => 400,
	'flex-height' => true,
	'flex-width'  => true,
	'header-text' => array( 'site-title', 'site-description' ),
    ) );

}
add_action( 'after_setup_theme', 'badjohnny_setup' );

/**
 * Add support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Return the Google font stylesheet URL if available.
 *
 * The use of Open Sans by default is localized. For languages that use
 * characters not supported by the font, the font can be disabled.
 *
 * @since BadJohnny 1.2
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function badjohnny_get_font_url() {
	$font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'badjohnny' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'badjohnny' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		$font_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since BadJohnny 1.0
 */
function badjohnny_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Adds JavaScript for handling the navigation menu hide-and-show behavior.
	wp_enqueue_script( 'badjohnny-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20140711', true );
	
	wp_enqueue_script( 'custom', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), '20150720', true );

	$font_url = badjohnny_get_font_url();
	if ( ! empty( $font_url ) )
		wp_enqueue_style( 'badjohnny-fonts', esc_url_raw( $font_url ), array(), null );

	// Loads our main stylesheet.
	wp_enqueue_style( 'badjohnny-style', get_stylesheet_uri() );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'badjohnny-ie', get_template_directory_uri() . '/css/ie.css', array( 'badjohnny-style' ), '20121010' );
	$wp_styles->add_data( 'badjohnny-ie', 'conditional', 'lt IE 9' );

	// Loads the fontawesome stylesheet.
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome/css/font-awesome.min.css', array( 'badjohnny-style' ), '' );
}
add_action( 'wp_enqueue_scripts', 'badjohnny_scripts_styles' );

/**
 * Filter TinyMCE CSS path to include Google Fonts.
 *
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses badjohnny_get_font_url() To get the Google Font stylesheet URL.
 *
 * @since BadJohnny 1.2
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string Filtered CSS path.
 */
function badjohnny_mce_css( $mce_css ) {
	$font_url = badjohnny_get_font_url();

	if ( empty( $font_url ) )
		return $mce_css;

	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );

	return $mce_css;
}
add_filter( 'mce_css', 'badjohnny_mce_css' );

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since BadJohnny 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function badjohnny_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'badjohnny' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'badjohnny_wp_title', 10, 2 );

/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since BadJohnny 1.0
 */
function badjohnny_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'badjohnny_page_menu_args' );

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since BadJohnny 1.0
 */
function badjohnny_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'badjohnny' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'badjohnny' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'badjohnny' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'badjohnny' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Front Page Widget Area', 'badjohnny' ),
		'id' => 'sidebar-3',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'badjohnny' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'badjohnny_widgets_init' );

if ( ! function_exists( 'badjohnny_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since BadJohnny 1.0
 */
function badjohnny_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'badjohnny' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'badjohnny' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'badjohnny' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'badjohnny_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own badjohnny_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since BadJohnny 1.0
 */
function badjohnny_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'badjohnny' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'badjohnny' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'badjohnny' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'badjohnny' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'badjohnny' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'badjohnny' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'badjohnny' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'badjohnny_entry_meta' ) ) :
/**
 * Set up post entry meta.
 *
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own badjohnny_entry_meta() to override in a child theme.
 *
 * @since BadJohnny 1.0
 */
function badjohnny_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'badjohnny' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'badjohnny' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'badjohnny' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( '%1$s / tagged %2$s / %3$s / <span class="by-author"> by %4$s</span>', 'badjohnny' );
	} elseif ( $categories_list ) {
		$utility_text = __( '%1$s / %3$s / <span class="by-author"> by %4$s</span>', 'badjohnny' );
	} else {
		$utility_text = __( '%3$s / <span class="by-author"> by %4$s</span>', 'badjohnny' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since BadJohnny 1.0
 *
 * @param array $classes Existing class values.
 * @return array Filtered class values.
 */
function badjohnny_body_class( $classes ) {
	$background_color = get_background_color();
	$background_image = get_background_image();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';
    
    if ( is_page_template( 'page-templates/about-page.php' ) ) {
    	$classes[] = 'template-about-page';
    }

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_image ) ) {
		if ( empty( $background_color ) )
			$classes[] = 'custom-background-empty';
		elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
			$classes[] = 'custom-background-white';
	}

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'badjohnny-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'badjohnny_body_class' );

/**
 * Adjust content width in certain contexts.
 *
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since BadJohnny 1.0
 */
function badjohnny_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'badjohnny_content_width' );


/**
 * Register postMessage support.
 *
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since BadJohnny 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function badjohnny_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'badjohnny_customize_register' );

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since BadJohnny 1.0
 */
function badjohnny_customize_preview_js() {
	wp_enqueue_script( 'badjohnny-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20141120', true );
}
add_action( 'customize_preview_init', 'badjohnny_customize_preview_js' );



/**
 * Change the container tag LOGO and post title for SEO
 */

function badjohnny_seo_wrapper($tag,$link){
	return '<'.$tag.'>'.$link.'</'.$tag.'>';
}

/**
 * Registers options with the Theme Customizer
 * @since      1.0
 */
function badjohnny_register_theme_customizer( $wp_customize ) {
	//Link Color
	$wp_customize->add_setting(
		'badjohnny_link_color',
		array(
			'default'     => '#269fea',
			'sanitize_callback' => 'esc_attr'

		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'link_color',
			array(
			    'label'      => __( 'Link Color', 'badjohnny' ),
			    'section'    => 'colors',
			    'settings'   => 'badjohnny_link_color'			
			)
		)
	);
	
	//Link Hover Color
	$wp_customize->add_setting(
		'badjohnny_link_hover_color',
		array(
			'default'     => '#00b3ff',
            'sanitize_callback' => 'esc_attr'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'link_hover_color',
			array(
			    'label'      => __( 'Link Hover Color', 'badjohnny' ),
			    'section'    => 'colors',
			    'settings'   => 'badjohnny_link_hover_color',
			)
		)
	);
} // end badjohnny_register_theme_customizer
add_action( 'customize_register', 'badjohnny_register_theme_customizer' );

function badjohnny_customizer_css() {
?>
	 <style type="text/css">
	     a{ color: <?php echo get_theme_mod( 'badjohnny_link_color' ); ?>; }
	     a:hover,
	     .format-status .entry-header header a:hover,
	     .widget-area .widget a:hover,
	     .comments-link a:hover,
	     .main-navigation a:hover,
.main-navigation a:focus,
.entry-meta a:hover,
.comments-area article header a:hover,
a.comment-reply-link:hover,
a.comment-edit-link:hover,
.entry-content a:hover,
footer[role="contentinfo"] a:hover,
.template-front-page .widget-area .widget li a:hover{ color: <?php echo get_theme_mod( 'badjohnny_link_hover_color' ); ?>; }
         .bypostauthor cite span {background-color: <?php echo get_theme_mod( 'badjohnny_link_hover_color' ); ?>}
	 </style>
<?php
} // end badjohnny_customizer_css
add_action( 'wp_head', 'badjohnny_customizer_css');


/*Add social profile fields to user page*/
if( !function_exists( 'badjohnny_custom_profile') ):
function badjohnny_custom_profile( $contactmethods ) {
    $contactmethods['facebook'] = 'Facebook';
	$contactmethods['twitter'] = 'Twitter';
	$contactmethods['google-plus'] = 'Google+';
	$contactmethods['dribbble'] = 'Dribbble';
	$contactmethods['behance'] = 'Behance';
	$contactmethods['flickr'] = 'Flickr+';
	$contactmethods['instagram'] = 'Instagram';
	$contactmethods['tumblr'] = 'Tumblr';
	$contactmethods['github'] = 'Github';
	$contactmethods['youtube'] = 'Youtube';
	$contactmethods['vimeo'] = 'Vimeo';
	$contactmethods['pinterest'] = 'Pinterest';
	$contactmethods['wordpress'] = 'WordPress';
    return $contactmethods;
}
endif;
add_filter('user_contactmethods','badjohnny_custom_profile',10,1);

if( !function_exists( 'badjohnny_author_socials') ):
function badjohnny_author_socials(){
	$social_profile='';
	$social_array=array('facebook','twitter','google-plus','dribbble','behance','flickr','instagram','tumblr','github','youtube','vimeo','wordpress');
	for($i=0;$i<count($social_array);$i++){
	   if(get_the_author_meta($social_array[$i])<>''){
	    $social_profile.='<a href="'.get_the_author_meta($social_array[$i]).'" target="_blank"><i class="fa fa-'.$social_array[$i].'"></i></a>';
	   }
	}
	echo $social_profile;
}
endif;

/* Required plugins reminder*/
require_once get_template_directory() .'/plugins/plugins.php';

