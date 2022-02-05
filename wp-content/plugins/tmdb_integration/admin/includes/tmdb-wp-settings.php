<?php

if (!defined('ABSPATH')) {
    exit();
}

class Tmdb_Wp_Settings
{
    private $global_options;
    private $options;
    function __construct()
    {
        add_action('admin_init', [$this, 'get_options'], 5); 
        add_action('admin_init', [$this, 'tmdb_settings_init']);
        add_action('admin_init', [$this, 'tmdb_configuration_settings_section']);
    }

    public function get_options () {
        $this->global_options =  get_option(TMDB_OPTIONS);
        $this->options = json_decode($_SESSION[TMDB_PAGE_SESSION_CONFIG]);
    }

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
     <input type='text' style="min-width: 300px;" name='<?php echo TMDB_OPTIONS ;?>[api_key]' value='<?php echo $options['api_key']; ?>'>
    <?php
    }

    // Configuration Settings section

    public function tmdb_configuration_settings_section () {

        add_settings_section('tmdb_config', __('TMDB Configuration Settings', 'tmdb_int'), null, 'tmdbPlg');

        add_settings_field('tmdb_base_img_url', __('TMDB Base Image URL', 'tmdb_int'), [$this, 'tmdb_base_img_url_render'],'tmdbPlg','tmdb_config' );

        add_settings_field('tmdb_base_poster_size', __('TMDB Poster Size Width (Pixels)', 'tmdb_int'), [$this, 'tmdb_images_poster_sizes_render'],'tmdbPlg','tmdb_config' );

    }

    public function tmdb_base_img_url_render () { ?>
     <input type='text' style="min-width: 300px;" value='<?php  echo isset($this->global_options['base_img_url']) && $this->global_options['base_img_url']  ? $this->global_options['base_img_url']:  $this->options->images->secure_base_url  ; ?>' disabled>
     <input type="hidden"  name='<?php echo TMDB_OPTIONS ;?>[base_img_url]' value="<?php  echo $this->options->images->secure_base_url; ?>">
    <?php
    }

    public function tmdb_images_poster_sizes_render () { ?>

        <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS ;?>[poster_sizes]">
        <?php foreach ($this->options->images->poster_sizes as $poster_size): ?>
            <option <?php selected($this->global_options['poster_sizes'], $poster_size); ?> value="<?php echo $poster_size ?>"><?php echo $poster_size ?></option>
            <?php endforeach; ?>
    </select>
  <?php  }
}

new Tmdb_Wp_Settings();
