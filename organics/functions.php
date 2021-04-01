<?php
/**
 * Theme sprecific functions and definitions
 */


/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'organics_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_theme_setup', 1 );
	function organics_theme_setup() {

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Enable support for Post Thumbnails
        add_theme_support( 'post-thumbnails' );

        // Custom header setup
        add_theme_support( 'custom-header', array('header-text'=>false));

        // Custom backgrounds setup
        add_theme_support( 'custom-background');

        // Supported posts formats
        add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') );

        // Autogenerate title tag
        add_theme_support('title-tag');

        // Add user menu
        add_theme_support('nav-menus');

        // WooCommerce Support
        add_theme_support( 'woocommerce' );

        // Register theme menus
		add_filter( 'organics_filter_add_theme_menus',		'organics_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'organics_filter_add_theme_sidebars',	'organics_add_theme_sidebars' );

		
		// Add theme specified classes into the body
		add_filter( 'body_class', 'organics_body_classes' );

        add_action('wp_head', 'organics_head_add_page_meta', 1);

        // Gutenberg support
        add_theme_support( 'align-wide' );

        // Set list of the theme required plugins
        organics_storage_set('required_plugins', array(
            'visual_composer',
            'revslider',
            'woocommerce',
            'essgrids',
            'gdpr-compliance',
            'elegro-payment',
            'contact-form-7',
            'product-delivery-date',
            'trx_updater'
        ));


        if ( is_dir(ORGANICS_THEME_PATH . 'demo-rtl/') || is_dir(ORGANICS_THEME_PATH . 'demo/') ) {
            $demo_folder = is_rtl() ? 'demo-rtl/' : 'demo/';
            organics_storage_set('demo_data_url',  ORGANICS_THEME_PATH . $demo_folder);
        } else {
            $link = is_rtl() ? esc_url(organics_get_protocol().'://rtl.organics.axiomthemes.com/demo-rtl/') : esc_url(organics_get_protocol().'://organics.axiomthemes.com/demo/');
            organics_storage_set('demo_data_url', $link ); // Demo-site domain
        }
	}
}


// Add theme specified classes into the body
if ( !function_exists('organics_body_classes') ) {
	function organics_body_classes( $classes ) {

		$classes[] = 'organics_body';
		$classes[] = 'body_style_' . trim(organics_get_custom_option('body_style'));
		$classes[] = 'body_' . (organics_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'theme_skin_' . trim(organics_get_custom_option('theme_skin'));
		$classes[] = 'article_style_' . trim(organics_get_custom_option('article_style'));
		
		$blog_style = organics_get_custom_option(is_singular() && !organics_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(organics_get_template_name($blog_style));
		
		$body_scheme = organics_get_custom_option('body_scheme');
		if (empty($body_scheme)  || organics_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = organics_get_custom_option('top_panel_position');
		if (!organics_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = organics_get_sidebar_class();

		if (organics_get_custom_option('show_video_bg')=='yes' && (organics_get_custom_option('video_bg_youtube_code')!='' || organics_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (organics_get_theme_option('page_preloader')!='')
			$classes[] = 'preloader';

		return $classes;
	}
}



// Add/Remove theme nav menus
if ( !function_exists( 'organics_add_theme_menus' ) ) {
	function organics_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'organics_add_theme_sidebars' ) ) {
	function organics_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'organics' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'organics' )
			);
            if (function_exists('organics_exists_woocommerce') && organics_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'organics' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}

// Add theme required plugins
if ( !function_exists( 'organics_add_trx_utils' ) ) {
    add_filter( 'trx_utils_active', 'organics_add_trx_utils' );
    function organics_add_trx_utils($enable=true) {
        return true;
    }
}

// Add page meta to the head
if (!function_exists('organics_head_add_page_meta')) {
    function organics_head_add_page_meta() {
        ?>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1<?php echo (organics_get_theme_option('responsive_layouts') == 'yes' ? ', maximum-scale=1' : ''); ?>">
        <meta name="format-detection" content="telephone=no">

        <?php
        if (floatval(get_bloginfo('version')) < 4.1) {
            ?><title><?php wp_title( '|', true, 'right' ); ?></title><?php
        }
        ?>

        <link rel="profile" href="//gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <?php
    }
}

// Return text for the Privacy Policy checkbox
if ( ! function_exists('organics_get_privacy_text' ) ) {
    function organics_get_privacy_text() {
        $page = get_option( 'wp_page_for_privacy_policy' );
        $privacy_text = organics_get_theme_option( 'privacy_text' );
        return apply_filters( 'organics_filter_privacy_text', wp_kses(
                $privacy_text
                . ( ! empty( $page ) && ! empty( $privacy_text )
                    // Translators: Add url to the Privacy Policy page
                    ? ' ' . sprintf( __( 'For further details on handling user data, see our %s', 'organics' ),
                        '<a href="' . esc_url( get_permalink( $page ) ) . '" target="_blank">'
                        . __( 'Privacy Policy', 'organics' )
                        . '</a>' )
                    : ''
                ), 'organics_kses_content'
            )
        );
    }
}

// Return text for the "I agree ..." checkbox
if ( ! function_exists( 'organics_trx_utils_privacy_text' ) ) {
    add_filter( 'trx_utils_filter_privacy_text', 'organics_trx_utils_privacy_text' );
    function organics_trx_utils_privacy_text( $text='' ) {
        return organics_get_privacy_text();
    }
}

/**
 * Fire the wp_body_open action.
 *
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
 */
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action('wp_body_open');
    }
}

//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'organics_importer_set_options' ) ) {
    add_filter( 'trx_utils_filter_importer_options', 'organics_importer_set_options', 9 );
    function organics_importer_set_options( $options=array() ) {
        if ( is_array( $options ) ) {

            $rtl_slug = is_rtl() ? '-rtl' : '';
            $rtl_subdomen = is_rtl() ? 'rtl.' : '';

            // Save or not installer's messages to the log-file
            $options['debug'] = false;
            // Prepare demo data
            if ( is_dir( ORGANICS_THEME_PATH . 'demo' . $rtl_slug . '/' ) ) {
                $options['demo_url'] = ORGANICS_THEME_PATH . 'demo' . $rtl_slug . '/';
            } else {
                $options['demo_url'] = esc_url( organics_get_protocol().'://demofiles.axiomthemes.com/organics' . $rtl_slug . '/' ); // Demo-site domain
            }

            // Required plugins
            $options['required_plugins'] =  array(
                'js_composer',
                'revslider',
                'woocommerce',
                'essential-grid'
            );

            $options['theme_slug'] = 'organics';

            // Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
            // Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
            $options['regenerate_thumbnails'] = 3;
            // Default demo
            $options['files']['default']['title'] = esc_html__( 'Organics Demo', 'organics' );
            $options['files']['default']['domain_dev'] = esc_url(organics_get_protocol().'://' . $rtl_subdomen . 'organics.axiomthemes.com'); // Developers domain
            $options['files']['default']['domain_demo']= esc_url(organics_get_protocol().'://' . $rtl_subdomen . 'organics.axiomthemes.com'); // Demo-site domain

        }
        return $options;
    }
}


/* Include framework core files
------------------------------------------------------------------- */
// If now is WP Heartbeat call - skip loading theme core files
	require_once get_template_directory().'/fw/loader.php';
?>