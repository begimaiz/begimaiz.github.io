<?php
namespace PDFP\Model;

class GlobalChanges{
    protected static $_instance = null;

    /**
     * construct function
     */
    public function __construct(){
        add_action( 'admin_menu', [$this, 'pdfp_add_custom_link_into_cpt_menu'] );
        add_action( 'admin_head', [$this, 'pdfp_my_custom_script'] );
        add_filter( 'admin_footer_text', [$this, 'pdfp_admin_footer']);	
        if(is_admin()){
            add_action('admin_footer', [$this, 'admin_footer']);
        }
    }

    function admin_footer() {
        $screen = get_current_screen();
		if($screen->post_type === 'pdfposter' || $screen->base === 'plugins'){ ?>
            <script type="text/javascript">
                document.querySelector("ul#adminmenu a[href$='https://bplugins.com/products/pdf-poster/#pricing']")?.setAttribute('target', '_blank');
            </script> <?php
		}
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

    function pdfp_add_custom_link_into_cpt_menu() {
        global $submenu;
        $link = 'https://bplugins.com/products/pdf-poster/#pricing';
        $submenu['edit.php?post_type=pdfposter'][] = array( 'Upgrade to PRO', 'manage_options', $link, 'meta'=>'target="_blank"' );
    }
    function pdfp_admin_footer( $text ) {
        if ( 'pdfposter' == get_post_type() ) {
            $url = 'https://wordpress.org/support/plugin/pdf-poster/reviews/?filter=5#new-post';
            $text = sprintf( __( 'If you like <strong>Pdf Poster</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'h5vp-domain' ), $url );
        }
        return $text;
    }

    function pdfp_my_custom_script() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready( function($) {
                $( "ul#adminmenu a[href$='http://pdfposter.com']" ).attr( 'target', '_blank' );
            });
        </script>
        <?php
    }

    

    
}

GlobalChanges::instance();