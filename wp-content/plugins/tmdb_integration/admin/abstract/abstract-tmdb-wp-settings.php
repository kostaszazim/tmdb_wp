<?php

if (!defined('ABSPATH')) {
    exit();
}

abstract class Tmdb_Wp_Settings
{
    protected $global_options;
    protected $options;
    function __construct()
    {
        if (wp_doing_ajax()) {
            return;
        }
        add_action('admin_init', [$this, 'get_options'], 5); 
        add_action('admin_init', [$this, 'tmdb_settings_init']);
    }

    public function get_options () {
        $this->global_options =  get_option(TMDB_OPTIONS);
        $this->options = json_decode($_SESSION[TMDB_PAGE_SESSION_CONFIG]);
    }

    // Basic Settings Init
    abstract public function tmdb_settings_init();
}