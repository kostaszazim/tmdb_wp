<?php

if (!defined('ABSPATH')) {
    exit();
}

abstract class TMDB_Import_Prototype
{
    protected $prototype_product;
    protected $tmdb_movie_info;
    protected $created_product;
    protected $product_attributes;
    protected $language_code;
    protected $created_product_id;
    protected $description_field;

    function __construct($prototype_product, $tmdb_movie_info, $language_code)
    {
        if (!($prototype_product instanceof WC_Product)) {
            return;
        }
        if (!isset($tmdb_movie_info['_tmdb_nonce']) || !wp_verify_nonce($tmdb_movie_info['_tmdb_nonce'], 'tmdb_import')) {
            return;
        }
        $tmdb_options = get_option(TMDB_OPTIONS);
        $this->description_field = isset($tmdb_options['woo_description_field']) && !empty($tmdb_options['woo_description_field']) ? $tmdb_options['woo_description_field'] : 'full_description';
        $this->prototype_product = $prototype_product;
        $this->tmdb_movie_info = $tmdb_movie_info;
        $this->language_code = $language_code;
        $wc_adp = new WC_Admin_Duplicate_Product();
        $this->created_product = $wc_adp->product_duplicate($this->prototype_product);
        $this->product_attributes = $this->created_product->get_attributes();
        $this->upload_movie_poster_to_library();
        $this->setup_basic_movie_info();
        $this->update_product_attributes();
        $this->clear_gallery_images();
        $this->publish_and_save_product();
    }

    protected function setup_basic_movie_info () {
        if ($this->description_field === 'full_description') {
            $this->created_product->set_description($this->tmdb_movie_info['tmdb_movie_summary_' . $this->language_code]);
        } elseif ($this->description_field === 'short_description') {
            $this->created_product->set_short_description($this->tmdb_movie_info['tmdb_movie_summary_' . $this->language_code]);
        }
        $sku_result = $this->created_product->set_sku($this->tmdb_movie_info['tmdb_sku']);
        if ($sku_result instanceof WP_Error) {
            throw new Exception("product with sku already exists");
        }
        update_post_meta($this->created_product->get_id(), '_tmdb_movie_id', $this->tmdb_movie_info['tmdb_movie_id']);
        $this->created_product->set_name($this->tmdb_movie_info['tmdb_movie_title_' . $this->language_code]);
    }

    protected function upload_movie_poster_to_library () {
        new ImageUploader('', $this->created_product->get_id(), $this->tmdb_movie_info['tmdb_poster_url'], false );
    }

    protected function publish_and_save_product () {
       $created_product_id = $this->created_product->save();
       wp_update_post(['ID' => $created_product_id, 'post_status' => 'publish', 'post_name' => GreekSlugGenerator::getSlug($this->tmdb_movie_info['tmdb_movie_title_' . $this->language_code])]);
    }

    protected function clear_gallery_images () {
        $this->created_product->set_gallery_image_ids([]);
    }

    abstract protected function update_product_attributes ();
}