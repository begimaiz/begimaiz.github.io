<?php 
/*
 * Plugin Name: PDF Poster 
 * Plugin URI:  http://pdfposter.com/
 * Description: You can easily Embed pdf file in wordress post, page, widget area and theme template file. 
 * Version:     2.0.11
 * Author:      bPlugins LLC
 * Author URI:  https://bplugins.com
 * License:     GPLv3
 * Text Domain: pdfp
 * Domain Path: /languages
 */

 use PDFP\Model\Import;
/*Some Set-up*/
define('PDFP_PLUGIN_DIR', plugin_dir_url(__FILE__)); 
define('PDFP_FILE_', (__FILE__)); 
define('PDFP_PLUGIN_VERSION',  '2.0.11' ); 
define('PDFP_VER',  '2.0.11' ); 
define('IMPORT_VER',  '1.1.0' ); 

if(!function_exists('pdfp_sdk')){
    function pdfp_sdk(){
        global $pdfp_bs;
        require_once(plugin_dir_path(__FILE__).'bplugins_sdk/init.php');
        $pdfp_bs = new BPlugins_SDK(__FILE__);
    }
    pdfp_sdk();
}else {
	$pdfp_bs->uninstall_plugin(__FILE__);
}

function pdfp_load_textdomain() {
    load_plugin_textdomain( 'pdfp', false, dirname( __FILE__ ) . "/languages" );
}
add_action( "plugins_loaded", 'pdfp_load_textdomain' );

require_once(__DIR__.'/upgrade.php');

// After activation redirect
register_activation_hook(__FILE__, 'pdfp_plugin_activate');

function pdfp_plugin_activate() {
	add_option('pdfp_plugin_do_activation_redirect', true);
}

add_action('admin_init', 'pdfp_plugin_redirect', 20);
function pdfp_plugin_redirect() {
	if (get_option('pdfp_plugin_do_activation_redirect', false)) {
        delete_option('pdfp_plugin_do_activation_redirect');
        // wp_redirect('edit.php?post_type=pdfposter&page=pdfp-support');
    }
    if(get_option('pdfp_import', '0') != IMPORT_VER){
        Import::meta();
        update_option('pdfp_import', IMPORT_VER);
    }

}

