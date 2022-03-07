<?php
if (!defined('ABSPATH')) {
    exit();
}

class Tmdb_Woo_Taxonomy_Settings extends Tmdb_Wp_Settings
{
    private $woo_product_attributes = [];
    public function __construct()
    {
        parent::__construct();

        add_action('admin_init', [$this, 'get_registered_product_attributes']);
       

    }


    public function get_registered_product_attributes () {
        $registered_taxonomies = get_taxonomies();
        $this->woo_product_attributes = array_filter($registered_taxonomies, function ($taxonomy) {
            return strpos($taxonomy, 'pa_') !== false;
        });
        array_unshift($this->woo_product_attributes, '-');

    }
    // Basic Settings Init
    public function tmdb_settings_init()
    {
        add_settings_section('tmdb_woo_config', __('TMDB WooCommerce Taxonomy Settings', 'tmdb_int'), null, 'tmdbPlg');

        add_settings_field(
            'tmdb_genre_map',
            __('Select Genre Woo Taxonomy', 'tmdb_int'),
            [$this, 'tmdb_genre_map_render'],
            'tmdbPlg',
            'tmdb_woo_config',
        );

        add_settings_field(
            'tmdb_actors_map',
            __('Select Actors Woo Taxonomy', 'tmdb_int'),
            [$this, 'tmdb_actors_map_render'],
            'tmdbPlg',
            'tmdb_woo_config',
        );

        add_settings_field(
            'tmdb_production_year_map',
            __('Select Production Year Woo Taxonomy', 'tmdb_int'),
            [$this, 'tmdb_production_year_map_render'],
            'tmdbPlg',
            'tmdb_woo_config',
        );

        add_settings_field(
            'tmdb_spoken_language_map',
            __('Select Spoken Language Woo Taxonomy', 'tmdb_int'),
            [$this, 'tmdb_spoken_language_map_render'],
            'tmdbPlg',
            'tmdb_woo_config',
        );

        add_settings_field(
            'tmdb_release_date_map',
            __('Select Release Date Woo Taxonomy', 'tmdb_int'),
            [$this, 'tmdb_release_date_map_render'],
            'tmdbPlg',
            'tmdb_woo_config',
        );

        add_settings_field(
            'tmdb_production_country_map',
            __('Select Production Country Woo Taxonomy', 'tmdb_int'),
            [$this, 'tmdb_production_country_map_render'],
            'tmdbPlg',
            'tmdb_woo_config',
        );

        add_settings_field(
            'tmdb_original_title_map',
            __('Select Original Title Woo Taxonomy', 'tmdb_int'),
            [$this, 'tmdb_original_title_map_render'],
            'tmdbPlg',
            'tmdb_woo_config',
        );

        add_settings_field(
            'tmdb_writer_map',
            __('Select Writer Woo Taxonomy', 'tmdb_int'),
            [$this, 'tmdb_writer_map_render'],
            'tmdbPlg',
            'tmdb_woo_config',
        );

        add_settings_field(
            'tmdb_director_map',
            __('Select Director Woo Taxonomy', 'tmdb_int'),
            [$this, 'tmdb_director_map_render'],
            'tmdbPlg',
            'tmdb_woo_config',
        );

        add_settings_field(
            'tmdb_production_company_map',
            __('Select Production Company Woo Taxonomy', 'tmdb_int'),
            [$this, 'tmdb_production_company_map_render'],
            'tmdbPlg',
            'tmdb_woo_config',
        );
    }

    public function tmdb_genre_map_render()
    {?>

        <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS; ?>[genre_woo_taxonomy]">
        <?php foreach ($this->woo_product_attributes as $woo_attribute): ?>
            <option <?php selected(
                isset($this->global_options['genre_woo_taxonomy']) ? $this->global_options['genre_woo_taxonomy'] : false,
                $woo_attribute,
            ); ?> value="<?php echo $woo_attribute; ?>"><?php echo $woo_attribute; ?></option>
            <?php endforeach; ?>
    </select>
  <?php
    }

    public function tmdb_production_year_map_render () { ?>
         <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS; ?>[production_year_woo_taxonomy]">
        <?php foreach ($this->woo_product_attributes as $woo_attribute): ?>
            <option <?php selected(
                isset($this->global_options['production_year_woo_taxonomy']) ? $this->global_options['production_year_woo_taxonomy'] : false,
                $woo_attribute,
            ); ?> value="<?php echo $woo_attribute; ?>"><?php echo $woo_attribute; ?></option>
            <?php endforeach; ?>
    </select>
   <?php }


public function tmdb_actors_map_render () { ?>
    <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS; ?>[actors_woo_taxonomy]">
   <?php foreach ($this->woo_product_attributes as $woo_attribute): ?>
       <option <?php selected(
           isset($this->global_options['actors_woo_taxonomy']) ? $this->global_options['actors_woo_taxonomy'] : false,
           $woo_attribute,
       ); ?> value="<?php echo $woo_attribute; ?>"><?php echo $woo_attribute; ?></option>
       <?php endforeach; ?>
</select>
<?php }

public function tmdb_spoken_language_map_render () { ?>
    <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS; ?>[spoken_language_woo_taxonomy]">
   <?php foreach ($this->woo_product_attributes as $woo_attribute): ?>
       <option <?php selected(
           isset($this->global_options['spoken_language_woo_taxonomy']) ? $this->global_options['spoken_language_woo_taxonomy'] : false,
           $woo_attribute,
       ); ?> value="<?php echo $woo_attribute; ?>"><?php echo $woo_attribute; ?></option>
       <?php endforeach; ?>
</select>
<?php }

public function tmdb_release_date_map_render () { ?>
    <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS; ?>[release_date_woo_taxonomy]">
   <?php foreach ($this->woo_product_attributes as $woo_attribute): ?>
       <option <?php selected(
           isset($this->global_options['release_date_woo_taxonomy']) ? $this->global_options['release_date_woo_taxonomy'] : false,
           $woo_attribute,
       ); ?> value="<?php echo $woo_attribute; ?>"><?php echo $woo_attribute; ?></option>
       <?php endforeach; ?>
</select>
<?php }

public function tmdb_production_country_map_render () { ?>
    <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS; ?>[production_country_woo_taxonomy]">
   <?php foreach ($this->woo_product_attributes as $woo_attribute): ?>
       <option <?php selected(
           isset($this->global_options['production_country_woo_taxonomy']) ? $this->global_options['production_country_woo_taxonomy'] : false,
           $woo_attribute,
       ); ?> value="<?php echo $woo_attribute; ?>"><?php echo $woo_attribute; ?></option>
       <?php endforeach; ?>
</select>
<?php }

public function tmdb_original_title_map_render () { ?>
    <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS; ?>[original_title_woo_taxonomy]">
   <?php foreach ($this->woo_product_attributes as $woo_attribute): ?>
       <option <?php selected(
           isset($this->global_options['original_title_woo_taxonomy']) ? $this->global_options['original_title_woo_taxonomy'] : false,
           $woo_attribute,
       ); ?> value="<?php echo $woo_attribute; ?>"><?php echo $woo_attribute; ?></option>
       <?php endforeach; ?>
</select>
<?php }


public function tmdb_writer_map_render () { ?>
    <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS; ?>[writer_woo_taxonomy]">
   <?php foreach ($this->woo_product_attributes as $woo_attribute): ?>
       <option <?php selected(
           isset($this->global_options['writer_woo_taxonomy']) ? $this->global_options['writer_woo_taxonomy'] : false,
           $woo_attribute,
       ); ?> value="<?php echo $woo_attribute; ?>"><?php echo $woo_attribute; ?></option>
       <?php endforeach; ?>
</select>
<?php }


public function tmdb_director_map_render () { ?>
    <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS; ?>[director_woo_taxonomy]">
   <?php foreach ($this->woo_product_attributes as $woo_attribute): ?>
       <option <?php selected(
           isset($this->global_options['director_woo_taxonomy']) ? $this->global_options['director_woo_taxonomy'] : false,
           $woo_attribute,
       ); ?> value="<?php echo $woo_attribute; ?>"><?php echo $woo_attribute; ?></option>
       <?php endforeach; ?>
</select>
<?php }


public function tmdb_production_company_map_render () { ?>
    <select  style="min-width: 300px;"  name="<?php echo TMDB_OPTIONS; ?>[production_company_woo_taxonomy]">
   <?php foreach ($this->woo_product_attributes as $woo_attribute): ?>
       <option <?php selected(
           isset($this->global_options['production_company_woo_taxonomy']) ? $this->global_options['production_company_woo_taxonomy'] : false,
           $woo_attribute,
       ); ?> value="<?php echo $woo_attribute; ?>"><?php echo $woo_attribute; ?></option>
       <?php endforeach; ?>
</select>
<?php }
}

new Tmdb_Woo_Taxonomy_Settings();

