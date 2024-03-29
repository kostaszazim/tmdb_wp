<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Page_Config_Interceptor
{
    function __construct()
    {
        add_action('admin_init', [$this, 'save_live_tmdb_config_data_session'], 1);
    }

    public function save_live_tmdb_config_data_session () {
        if (wp_doing_ajax()) {
            return;
        }
        $request = new TMDB_Configuration_Request();
        $response = $request->get_configuration();
        $_SESSION[TMDB_PAGE_SESSION_CONFIG] = $response;
    }
}

new TMDB_Page_Config_Interceptor();
