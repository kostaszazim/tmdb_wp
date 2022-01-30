<?php

if (!defined('ABSPATH')) {
    exit();
}

class Tmdb_Wp_Settings
{
    function __construct()
    {
        add_action('admin_init', [$this, 'tmdb_settings_init']);
    }

    public function tmdb_settings_init()
    {
        register_setting('tmdbPlg', 'tmdb_settings');

        add_settings_section(
            'tmdb_api_settings',
            __('TMDB API Settings', 'tmdb_int'),
            [$this, 'tmdb_settings_section_cb'],
            'tmdbPlg'
        );

        add_settings_field(
            'tmdb_api_key',
            __('TMDB API Key', 'tmdb_int'),
            [$this, 'tmdb_api_key_render'],
            'tmdbPlg',
            'tmdb_api_settings'
        );
    }

    public function tmdb_settings_section_cb () {
        echo  __( 'The Movie Database API Settings Section', 'tmdb_int' );
    }

    public function tmdb_api_key_render (){ 
        $options = get_option( 'tmdb_settings' );    
    ?>
     <input type='text' style="min-width: 300px;" name='tmdb_settings[api_key]' value='<?php echo $options['api_key']; ?>'>
    <?php }
}

new Tmdb_Wp_Settings();