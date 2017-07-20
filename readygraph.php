<?php
/*** The WordPress Plugin Boilerplate.** A foundation off of which to build well-documented WordPress plugins that* also follow WordPress Coding Standards and PHP best practices.** @package   ReadyGraph* @author    dan@readygraph.com* @license   GPL-2.0+* @link      http://www.readygraph.com* @copyright 2014 ReadyGraph (Under App Uprising, Inc)** @wordpress-plugin* Plugin Name:       ReadyGraph - Social Plugin* Plugin URI:        http://www.readygraph.com* Description:       Grow like the pros without all the effort. Reach and engage your site's social graph with our proven viral tools.* Version:           1.1.1* Author:            Dan Abelon, Tanay Lakhani* Author URI:        http://www.readygraph.com/company* Text Domain:       readygraph-locale* License:           GPL-2.0+* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt* Domain Path:       /languages* GitHub Plugin URI: https://github.com/jasukkas/readygraph-wordpress* WordPress-Plugin-Boilerplate: v2.6.1*/
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/*----------------------------------------------------------------------------** Public-Facing Functionality*----------------------------------------------------------------------------*/
/** @TODO:** - replace `class-readygraph.php` with the name of the plugin's class file**/
require_once( plugin_dir_path( __FILE__ ) . 'public/class-readygraph.php' );
/** Register hooks that are fired when the plugin is activated or deactivated.* When the plugin is deleted, the uninstall.php file is loaded.** @TODO:** - replace Plugin_Name with the name of the class defined in*   `class-readygraph.php`*/
register_activation_hook( __FILE__, array( 'ReadyGraph', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ReadyGraph', 'deactivate' ) );
/** @TODO:** - replace Plugin_Name with the name of the class defined in*   `class-readygraph.php`*/
add_action( 'plugins_loaded', array( 'ReadyGraph', 'get_instance' ) );
add_action('wp_head', 'readygraph_script_head');
/*----------------------------------------------------------------------------** Dashboard and Administrative Functionality*----------------------------------------------------------------------------*/
/** @TODO:** - replace `class-readygraph-admin.php` with the name of the plugin's admin file* - replace Plugin_Name_Admin with the name of the class defined in*   `class-readygraph-admin.php`** If you want to include Ajax within the dashboard, change the following* conditional to:** if ( is_admin() ) {*   ...* }** The code below is intended to to give the lightest footprint possible.*/
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-readygraph-admin.php' );
	add_action( 'plugins_loaded', array( 'ReadyGraph_Admin', 'get_instance' ) );
}
	register_activation_hook(__FILE__, 'rg_plugin_activate');
	add_action('admin_init', 'rg_plugin_redirect');
	function rg_plugin_activate() {
		add_option('rg_plugin_do_activation_redirect', true);
	}
	function rg_plugin_redirect() {
		if (get_option('rg_plugin_do_activation_redirect', false)) {
			delete_option('rg_plugin_do_activation_redirect');
			$setting_url="options-general.php?page=readygraph";
			wp_redirect($setting_url);
		}
	}	add_action( 'wp_ajax_nopriv_rg-myajax-submit', 'rg_myajax_submit' );	add_action( 'wp_ajax_rg-myajax-submit', 'rg_myajax_submit' );	function rg_myajax_submit() {	global $wpdb;	//	wp_die();}function rg_popup_options_enqueue_scripts() {    if ( get_option('readygraph_popup_template') == 'default-template' ) {        wp_enqueue_style( 'default-template', plugin_dir_url( __FILE__ ) .'admin/views/assets/css/default-popup.css' );    }    if ( get_option('readygraph_popup_template') == 'red-template' ) {        wp_enqueue_style( 'red-template', plugin_dir_url( __FILE__ ) .'admin/views/assets/css/red-popup.css' );    }    if ( get_option('readygraph_popup_template') == 'blue-template' ) {        wp_enqueue_style( 'blue-template', plugin_dir_url( __FILE__ ) .'admin/views/assets/css/blue-popup.css' );    }	if ( get_option('readygraph_popup_template') == 'black-template' ) {        wp_enqueue_style( 'black-template', plugin_dir_url( __FILE__ ) .'admin/views/assets/css/black-popup.css' );    }	if ( get_option('readygraph_popup_template') == 'gray-template' ) {        wp_enqueue_style( 'gray-template', plugin_dir_url( __FILE__ ) .'admin/views/assets/css/gray-popup.css' );    }	if ( get_option('readygraph_popup_template') == 'green-template' ) {        wp_enqueue_style( 'green-template', plugin_dir_url( __FILE__ ) .'admin/views/assets/css/green-popup.css' );    }	if ( get_option('readygraph_popup_template') == 'yellow-template' ) {        wp_enqueue_style( 'yellow-template', plugin_dir_url( __FILE__ ) .'admin/views/assets/css/yellow-popup.css' );    }    if ( get_option('readygraph_popup_template') == 'custom-template' ) {		wp_enqueue_style( 'custom-template', plugin_dir_url( __FILE__ ) .'admin/views/assets/css/custom-popup.css' );    }	}add_action( 'admin_enqueue_scripts', 'rg_popup_options_enqueue_scripts' );add_action( 'wp_enqueue_scripts', 'rg_popup_options_enqueue_scripts' );function rg_post_updated_send_email( $post_id ) {	// If this is just a revision, don't send the email.	if ( wp_is_post_revision( $post_id ) )		return;	if(get_option('readygraph_application_id') && strlen(get_option('readygraph_application_id')) > 0 && get_option('readygraph_send_blog_updates') == "true"){	$post_title = get_the_title( $post_id );	$post_url = get_permalink( $post_id );	$post_image = wp_get_attachment_url(get_post_thumbnail_id($post_id));	$post_content = get_post($post_id);	$post_excerpt = (isset($post_content->post_excerpt) && (!empty($post_content->post_excerpt))) ? $post_content->post_excerpt : wp_trim_words(strip_tags(strip_shortcodes($post_content->post_content)),500);	$url = 'http://readygraph.com/api/v1/post.json/';	if (get_option('readygraph_send_real_time_post_updates')=='true'){	$response = wp_remote_post($url, array( 'body' => array('is_wordpress'=>1, 'is_realtime'=>1, 'message' => $post_title, 'message_link' => $post_url,'message_excerpt' => $post_excerpt,'client_key' => get_option('readygraph_application_id'), 'email' => get_option('readygraph_email'))));	}	else {	$response = wp_remote_post($url, array( 'body' => array('is_wordpress'=>1, 'message' => $post_title, 'message_link' => $post_url,'message_excerpt' => $post_excerpt,'client_key' => get_option('readygraph_application_id'), 'email' => get_option('readygraph_email'))));	}	if ( is_wp_error( $response ) ) {	$error_message = $response->get_error_message();	} 	else {	}	$app_id = get_option('readygraph_application_id');	wp_remote_get( "http://readygraph.com/api/v1/tracking?event=post_created&app_id=$app_id" );	}	else{	}}add_action( 'publish_post', 'rg_post_updated_send_email' );add_action( 'publish_page', 'rg_post_updated_send_email' );function rg_delete_rg_options() {delete_option('readygraph_access_token');delete_option('readygraph_application_id');delete_option('readygraph_refresh_token');delete_option('readygraph_email');delete_option('readygraph_settings');delete_option('readygraph_delay');delete_option('readygraph_enable_sidebar');delete_option('readygraph_auto_select_all');delete_option('readygraph_enable_notification');delete_option('readygraph_enable_branding');delete_option('readygraph_send_blog_updates');delete_option('readygraph_send_real_time_post_updates');delete_option('readygraph_popup_template');delete_option('readygraph_upgrade_notice');}if(!function_exists('readygraph_client_script_head')) {
function readygraph_script_head() {
	if (get_option('readygraph_access_token', '') != '') {	if (get_option('readygraph_enable_branding', '') == 'false') {	?><style>/* FOR INLINE WIDGET */.rgw-text {    display: none !important;}</style><?php } ?>
<script type='text/javascript'>var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';var d = top.document;var h = d.getElementsByTagName('head')[0], script = d.createElement('script');script.type = 'text/javascript';script.src = '//cdn.readygraph.com/scripts/readygraph.js';script.onload = function(e) {  var settings = <?php echo str_replace("\\\"", "\"", get_option('readygraph_settings', '{}')) ?>;  settings['applicationId'] = '<?php echo get_option('readygraph_application_id', '') ?>';  settings['overrideFacebookSDK'] = true;  settings['platform'] = 'others';  settings['enableLoginWall'] = <?php echo get_option('readygraph_enable_popup', 'true') ?>;  settings['enableSidebar'] = <?php echo get_option('readygraph_enable_sidebar', 'false') ?>;	settings['inviteFlowDelay'] = <?php echo get_option('readygraph_delay', '5000') ?>;	settings['enableNotification'] = <?php echo get_option('readygraph_enable_notification', 'true') ?>;	settings['inviteAutoSelectAll'] = <?php echo get_option('readygraph_auto_select_all', 'true') ?>;	top.readygraph.setup(settings);	readygraph.ready(function() {		readygraph.framework.require(['auth', 'invite', 'compact.sdk'], function() {			function process(userInfo) {				var rg_email = userInfo.get('email');				var first_name = userInfo.get('first_name');				var last_name = userInfo.get('last_name');				jQuery.post(ajaxurl,				{					action : 'rg-myajax-submit',					email : rg_email				},				function() {				}				);			}			readygraph.framework.authentication.getUserInfo(function(userInfo) {				if (userInfo.get('uid') != null) {					process(userInfo);				}				else {					userInfo.on('change:fb_access_token change:rg_access_token', function() {						readygraph.framework.authentication.getUserInfo(function(userInfo) {							process(userInfo);						});					});				}			}, true);		});	});}h.appendChild(script);
</script>
	<?php
	}
}}
?>