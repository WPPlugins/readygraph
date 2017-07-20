<?php
/**
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/*----------------------------------------------------------------------------*
/*
require_once( plugin_dir_path( __FILE__ ) . 'public/class-readygraph.php' );
/*
register_activation_hook( __FILE__, array( 'ReadyGraph', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ReadyGraph', 'deactivate' ) );
/*
add_action( 'plugins_loaded', array( 'ReadyGraph', 'get_instance' ) );
add_action('wp_head', 'readygraph_script_head');
/*----------------------------------------------------------------------------*
/*
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
	}
function readygraph_script_head() {
	if (get_option('readygraph_access_token', '') != '') {
<script type='text/javascript'>
</script>
	<?php
	}
}
?>