<?php
/* ThemeVan Updater and License key
*/


/*Theme updater*/
define('THEMEVAN_STORE_URL', 'http://www.themevan.com'); 
define('THEMEVAN_STORE_NAME', 'ThemeVan'); 
define('THEMEVAN_THEME_NAME', 'BadJohnny');

if( !class_exists( 'EDD_SL_Theme_Updater' ) ) {
	// load our custom theme updater
	include( dirname( __FILE__ ) . '/THEMEVAN_Theme_Updater.php' );
}

/*Get License key*/
$this_license_key =get_option( 'license_key');
/*Get the current version of the theme*/
$this_theme = wp_get_theme();
$this_theme_version=$this_theme->get( 'Version' );

$edd_updater = new EDD_SL_Theme_Updater( array( 
	'remote_api_url'=> THEMEVAN_STORE_URL, 	// our store URL that is running EDD
	'version' 	=> $this_theme_version, 		// the current theme version we are running
	'license' 	=> $this_license_key, 	// the license key (used get_option above to retrieve from DB)
	'item_name' 	=> THEMEVAN_THEME_NAME,	// the name of this theme
	'author'	=> THEMEVAN_STORE_NAME,	// the author's name
	'url'           => home_url()
  )
);

/*License Key required notice*/
function require_license_key_notice() {
	 global $current_user;
	 $user_id = $current_user->ID;
      if ( ! get_user_meta($user_id, 'ignore_license_key_notice') ) {
        echo '<div class="updated"><p>';
        printf(__('Enter your license key of '.THEMEVAN_THEME_NAME.' theme to activate the theme updater, <a href="'.home_url().'/wp-admin/options-general.php#licensekey">click here</a> | <a href="%1$s">Hide Notice</a>'), '?hide_license_key_notice=0');
        echo "</p></div>";
	    }
}
function hide_license_key_notice() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['hide_license_key_notice']) && '0' == $_GET['hide_license_key_notice'] ) {
             add_user_meta($user_id, 'hide_license_key_notice', 'true', true);
	/* Gets where the user came from after they click Hide Notice */
		if ( wp_get_referer() ) {
    /* Redirects user to where they were before */
    wp_safe_redirect( wp_get_referer() );
		} else {
    /* This will never happen, I can almost gurantee it, but we should still have it just in case*/
    wp_safe_redirect( home_url() );
		}
	}
}

if( is_super_admin() && !isset($this_license_key) || $this_license_key=='' ){
   add_action( 'admin_notices', 'require_license_key_notice' );
   add_action('admin_init', 'hide_license_key_notice');
}

/* Add License Key field to genera setting page*/
$new_general_setting = new new_general_setting();

class new_general_setting {
    function new_general_setting( ) {
        add_filter( 'admin_init' , array( &$this , 'register_fields' ) );
    }
    function register_fields() {
        register_setting( 'general', 'license_key', 'esc_attr' );
        add_settings_field('fav_color', '<a id="licensekey"></a><label for="license_key">'.__('Theme\'s License Key' , 'license_key' ).'</label>' , array(&$this, 'fields_html') , 'general' );
    }
    function fields_html() {
        $value = get_option( 'license_key', '' );
        echo '<input type="text" id="license_key" name="license_key" value="' . $value . '" /><p>You can get the license key from your ThemeVan account. If you can\'t find it, please <a href="http://themevan.com/contact" target="_blank">contact us</a>.</p>';
    }
}

/*Add Widget to Dashboard*/
function themevan_widget() {
	include_once(ABSPATH . WPINC . '/feed.php');
	$rss = fetch_feed('http://www.themevan.com/feed/');
	if(!empty($rss)):
	$maxitems = $rss->get_item_quantity(5);
	$rss_items = $rss->get_items(0, $maxitems);
	endif;
	
	$html='';
	$html.='<ul>';
    if ($maxitems == 0){ 
      echo 'No news.';
    }else{
     foreach ( $rss_items as $item ):
     $html.='<li><a href="'.$item->get_permalink().'">'.$item->get_title().'</a> <span class="rss-date">'.$item->get_date().'</span></li>';
     endforeach; 
    $html.='</ul>';
    echo $html;
    }
}
function themevan_add_dashboard_widgets() {
    wp_add_dashboard_widget('themevan_widget', 'ThemeVan News', 'themevan_widget');
}
add_action('wp_dashboard_setup', 'themevan_add_dashboard_widgets');