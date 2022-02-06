<?php

if (!defined('ABSPATH')) {
    exit();
}

class Tmdb_Wp_API_Settings extends Tmdb_Wp_Settings
{

    // Basic Settings Init
    public function tmdb_settings_init()
    {
        register_setting('tmdbPlg', TMDB_OPTIONS);

        add_settings_section('tmdb_api_settings', __('TMDB API Settings', 'tmdb_int'), [$this, 'tmdb_settings_section_cb'], 'tmdbPlg');

        add_settings_field('tmdb_api_key', __('TMDB API Key', 'tmdb_int'), [$this, 'tmdb_api_key_render'], 'tmdbPlg', 'tmdb_api_settings');
    }

    public function tmdb_settings_section_cb()
    {
        echo __('The Movie Database API Settings Section', 'tmdb_int');
    }

    public function tmdb_api_key_render()
    {
        $options = get_option(TMDB_OPTIONS); ?>
     <input type='text' style="min-width: 300px;" name='<?php echo TMDB_OPTIONS ;?>[api_key]' value='<?php echo isset($options['api_key']) ? $options['api_key'] : '' ; ?>'>
    <?php
    }
}

new Tmdb_Wp_API_Settings();
