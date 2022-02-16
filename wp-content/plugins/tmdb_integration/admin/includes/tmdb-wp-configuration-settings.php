<?php

if (!defined('ABSPATH')) {
    exit();
}

class Tmdb_Wp_Configuration_Settings extends Tmdb_Wp_Settings
{

    // Basic Settings Init
    public function tmdb_settings_init()
    {

        add_settings_section('tmdb_config', __('TMDB Configuration Settings', 'tmdb_int'), null, 'tmdbPlg');

        add_settings_field('tmdb_base_img_url', __('TMDB Base Image URL', 'tmdb_int'), [$this, 'tmdb_base_img_url_render'],'tmdbPlg','tmdb_config' );

        add_settings_field('tmdb_base_poster_size', __('TMDB Poster Size Width (Pixels)', 'tmdb_int'), [$this, 'tmdb_images_poster_sizes_render'],'tmdbPlg','tmdb_config' );


        add_settings_field('tmdb_combined_languages', __('Available Languages', 'tmdb_int'), [$this, 'tmdb_available_languages_render'],'tmdbPlg','tmdb_config' );

        add_settings_field('tmdb_max_actors', __('Max Actors to Fetch', 'tmdb_int'), [$this, 'tmdb_max_actors_render'],'tmdbPlg','tmdb_config' );


        add_settings_field('tmdb_min_actor_tmdb_popularity', __('Minimum Tmdb Actor Popularity', 'tmdb_int'), [$this, 'tmdb_min_actor_tmdb_popularity_render'],'tmdbPlg','tmdb_config' );

        add_settings_field('tmdb_movie_prototype', __('Woocommerce Product to use as Prototype', 'tmdb_int'), [$this, 'tmdb_movie_prototype_render'],'tmdbPlg','tmdb_config' );

    }

    public function tmdb_base_img_url_render () { 
       global $tmdb_error;
        if (!$tmdb_error->has_error()):
        ?>
     <input type='text' style="min-width: 300px;" value='<?php  echo isset($this->global_options['base_img_url']) && $this->global_options['base_img_url']  ? $this->global_options['base_img_url']:  $this->options->images->secure_base_url  ; ?>' disabled>
     <input type="hidden"  name='<?php echo TMDB_OPTIONS ;?>[base_img_url]' value="<?php  echo $this->options->images->secure_base_url; ?>">
    <?php endif; }

    public function tmdb_images_poster_sizes_render () { 
       global $tmdb_error;
        if (!$tmdb_error->has_error()):
        ?>

        <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS ;?>[poster_sizes]">
        <?php foreach ($this->options->images->poster_sizes as $poster_size): ?>
            <option <?php selected(isset( $this->global_options['poster_sizes']) ? $this->global_options['poster_sizes']: false, $poster_size); ?> value="<?php echo $poster_size ?>"><?php echo $poster_size ?></option>
            <?php endforeach; ?>
    </select>
    <?php foreach ($this->options->images->poster_sizes as $key => $poster_size): ?>
        <input type="hidden" name="<?php echo TMDB_OPTIONS; ?>[available_poster_sizes][<?php echo $key; ?>]" value="<?php  echo $poster_size; ?>">
        <?php endforeach; ?>
  <?php endif;  }

  public function tmdb_available_languages_render () { 
      global $tmdb_languages;
      echo implode(", ", $tmdb_languages->get_supported_languages());
    ?>

 <?php }

public function tmdb_max_actors_render () { 
  ?>
    <input type="number" name="<?php echo TMDB_OPTIONS ;?>[max_actors]" value="<?php echo isset($this->global_options['max_actors']) ? $this->global_options['max_actors'] : ""; ?>">
<?php }

public function tmdb_min_actor_tmdb_popularity_render () { 
  ?>
    <input type="number" name="<?php echo TMDB_OPTIONS ;?>[min_actor_popularity]" value="<?php echo isset($this->global_options['min_actor_popularity']) ? $this->global_options['min_actor_popularity'] : ""; ?>">
<?php }

public function tmdb_movie_prototype_render () { ?>
 <input type="text" style="min-width:300px" id="movie_prototype" value="<?php echo isset($this->global_options['selected_movie_prototype_id']) && !empty($this->global_options['selected_movie_prototype_id']) ? get_post( $this->global_options['selected_movie_prototype_id'])->post_title : '' ?>">
 <input type="hidden" id="selected_movie_prototype_id" name="<?php echo TMDB_OPTIONS ;?>[selected_movie_prototype_id]">
<?php }
}


new Tmdb_Wp_Configuration_Settings();
