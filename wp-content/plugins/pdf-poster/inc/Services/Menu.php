<?php
namespace PDPF\Services;

class Menu {
    protected static $_instance = null;

    /**
     * construct function
     */
    public function __construct(){
        add_action( 'init', [$this, 'registerSettings'] );
        add_action( 'admin_menu', [$this, 'registerMenu'] );
        add_action( 'admin_footer', [$this, 'admin_footer'] );
    }

    /**
     * Create instance function
     */
    public static function instance(){
        if(self::$_instance === null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Register Settings
     */
    function registerSettings() {
        register_setting(
            'pdfp_settings',
            'pdfp_gutenberg_enable',
            array(
                'type'         => 'boolean',
                'show_in_rest' => true,
                'default'      => false,
            )
        );
        register_setting(
            'pdfp_settings',
            'bpm_gutenberg_enable',
            array(
                'type'         => 'boolean',
                'show_in_rest' => true,
                'default'      => false,
            )
        );
    
    }

    /**
     * Register Menu
     */
    function registerMenu() {
        $page_hook_suffix = add_submenu_page(
            'edit.php?post_type=pdfposter',
            __( 'Settings', 'pdfp' ),
            __( 'Settings', 'pdfp' ),
            'manage_options',
            'settings',
            [$this, 'settings_callback']
        );
    
        add_action( "admin_print_scripts-{$page_hook_suffix}", [$this, 'eqnueueAssets'] );

        global $submenu;
        $link = 'https://bplugins.com/products/pdf-poster/#pricing';
        $submenu['edit.php?post_type=pdfposter'][] = array( 'Upgrade to PRO', 'manage_options', $link);

    }

    /**
     * Settings Page Callback
     */
    function settings_callback() {
        echo '<div id="pdfp-settings"></div>';
    }
    
    function eqnueueAssets() {
        wp_enqueue_script( 'pdfp-settings', PDFP_PLUGIN_DIR . 'dist/settings.js', array( 'wp-api', 'wp-i18n', 'wp-components', 'wp-element' ), PDFP_VER, true );
        wp_enqueue_style( 'pdfp-settings', PDFP_PLUGIN_DIR . 'dist/settings.css', array( 'wp-components' ) );
    }

    function admin_footer() {
        $screen = get_current_screen();
		if($screen->post_type === 'pdfposter' || $screen->base === 'plugins'){ ?>
            <script type="text/javascript">
                document.querySelector("ul#adminmenu a[href$='https://bplugins.com/products/pdf-poster/#pricing']")?.setAttribute('target', '_blank');
            </script> <?php
		}
    }
}
Menu::instance();