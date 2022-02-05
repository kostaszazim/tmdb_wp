<?php

class TMDB_Admin_Ajax
{
    function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_ajax_script']);
        add_action('wp_ajax_fetch_tmdb_configuration', [$this, 'fetch_tmdb_configuration']);
    }

    

    public function enqueue_admin_ajax_script () {
        wp_register_script('tmdb-ajax', TMDB_INT__PLUGIN_DIR_URL . 'admin/assets/js/tmdb-admin-ajax.js', ['jquery']);
        wp_enqueue_script('tmdb-ajax');
        wp_localize_script( 'tmdb-ajax', 'admin_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
}

new TMDB_Admin_Ajax();