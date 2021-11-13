<?php
/**
 * @author  Protect Forms RPOST
 * @package UniFree-protect-Forms-WP
 * @version 0.0.1
 * @license GPL-2.0+
 * @link    https://github.com/oz-moryarty/unifree-protect-forms-wp
 * @copyright Copyright (c) 2021, Protect Forms RPOST
 */
/*
Plugin Name: Protect Forms RPOST
Plugin URI: https://github.com/Fusses/unifree-protect-forms-wp
Description: Protecting forms from spam without captcha or api Using only javascript, php is detected by spam robots
Version: 0.0.3
Author: Unifree
Author URI: http://unifree.ru/
License: GPLv2 or later
*/

if(!class_exists('wpcf7_rpost_protect') ) {
    class wpcf7_rpost_protect {     
        /*
         * Constructor
         */
        public function __construct() {
            $this->includes();
          
            // Check if Contact Form 7 is activated
            $required_plugin = new wpcf7_required_plugin_checker(__FILE__, 'contact-form-7/wp-contact-form-7.php');
            
            if($required_plugin->is_active()) {
                add_action('wp_enqueue_scripts', array(&$this,'wpcf7_rpost_register_scripts') );
                add_action('wp_ajax_nopriv_rpost_ajax_handler',  array( $this, 'rpost_ajax_handler')); // ajax call for non-login user.
                add_action('wp_ajax_rpost_ajax_handler',  array( $this, 'rpost_ajax_handler')); // ajax call for login user.
            }
        }
        /**
        * Include modules
        */
        private function includes() {
            require_once dirname(__FILE__).'/includes/class-required-plugin-checker.php';
        }

        public function wpcf7_rpost_register_scripts() {
            wp_enqueue_script('wpcf7-rpost', plugin_dir_url( __FILE__ ).'assets/js/rpost.js', array('jquery'), '0.1.3', true );
            wp_localize_script('wpcf7-rpost', 'rpostajaxhandler', array('ajaxurl' => admin_url('admin-ajax.php') ) );
        }
        
        function wpcf7_rpost_protect_intercept($post){
            if($post[md5(date('d'))]==md5(date('dm')) || empty($post[md5(date('d'))])){
                $_POST=[];
            }
        }
        
        public function rpost_ajax_handler(){
            echo json_encode('<input class="rpost" type="hidden" name="'.md5(date('d')).'" value="'.md5(date('dm')).'">');
            exit;
        }
    }
}

function wpcf7_rpost_protect() {
    return new wpcf7_rpost_protect();
}

$GLOBALS['wpcf7_rpost_protect'] = wpcf7_rpost_protect();

if(!empty($_POST) && $_POST[md5(date('d'))])
    $wpcf7_rpost_protect->wpcf7_rpost_protect_intercept($_POST);
