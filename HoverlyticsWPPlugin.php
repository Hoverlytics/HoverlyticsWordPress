<?php
/*
Plugin Name: Hoverlytics
Plugin URI: http://www.hoverlytics.com/
Description: Instant Hover-over Analytics
Version: 1.0.1
Author: Patrick Smith
Author URI: http://www.burntcaramel.com/
*/
/*
Copyright 2013 Patrick Smith
*/


// Basic template for this class used from: https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/

class HoverlyticsWPPlugin
{
	private static $pluginID = 'hoverlytics';
	private static $version = '1.0.1';
	private static $jsRevision = '';
	private static $styleRevision = '';
	
	private static $assetsBaseURL = 'https://hoverlytics-qawixu.backliftapp.com/services/version-1-0-1/';
	
	function __construct()
	{
		add_action('init', array($this, 'plugin_textdomain'));
		add_action('init', array($this, 'setup_styles_and_scripts'));
		
		register_activation_hook( __FILE__, array($this, 'activate' ));
		register_deactivation_hook( __FILE__, array($this, 'deactivate' ));
		register_uninstall_hook( __FILE__, array($this, 'uninstall' ));
	}
	
	public function activate($network_wide)
	{
	}
	
	public function deactivate($network_wide)
	{
	}
	
	public function uninstall($network_wide)
	{
	}
	
	public function disable_page_caching()
	{
		if (!defined('DONOTCACHEPAGE')) {
			define('DONOTCACHEPAGE', true);
		}
	}
	
	public function plugin_textdomain()
	{
		$domain = self::$pluginID;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
        load_plugin_textdomain( $domain, false, dirname(plugin_basename(__FILE__)) . '/lang/' );
	}
	
	public function setup_styles_and_scripts()
	{
		if (current_user_can('administrator')):
			$this::disable_page_caching();
			
			add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));
			add_action('wp_enqueue_scripts', array($this, 'register_plugin_scripts'));
		endif;
	}
	
	public function register_plugin_styles()
	{
		wp_enqueue_style( 'hoverlytics-plugin-styles', set_url_scheme(self::$assetsBaseURL . 'hoverlytics-plugin.css'), null, self::$version . self::$styleRevision);
		
		wp_enqueue_style( 'hoverlytics-symbol-styles', set_url_scheme(self::$assetsBaseURL . 'icomoon/symbols.css'), null, self::$version . self::$styleRevision);
	}

	public function register_plugin_scripts()
	{
?>
<script>
window.hoverlyticsPageViewer = {
	baseURL: "<?= esc_url(self::$assetsBaseURL) ?>",
	pageLinkTimeoutDuration: 400,
	localProxyFileURL: "<?= esc_url(plugins_url(self::$pluginID.'/assets/proxy.html')) ?>"
};
</script>
<?php
		wp_register_script('hoverlytics-porthole', set_url_scheme('https://hoverlytics-qawixu.backliftapp.com/app/porthole/porthole.min.js'), null, 'commit-7c2b8f6e03');
		wp_register_script('hoverlytics-jquery-cookie', set_url_scheme('http://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.3.1/jquery.cookie.min.js'), 'jquery');
		
		wp_enqueue_script('hoverlytics-plugin-script', set_url_scheme(self::$assetsBaseURL .'hoverlytics-plugin.js'), array('jquery', 'hoverlytics-porthole', 'hoverlytics-jquery-cookie'));
	}
	
	public function profileID()
	{
		$_COOKIE['hoverlyticsProfileID'];
	}
}

$hoverlyticsPlugin = new HoverlyticsWPPlugin();
