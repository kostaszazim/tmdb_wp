<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Int_Form_Submits
{
    function __construct()
    {
        add_action('admin_init', [$this, 'is_movie_details_submit'], 30);
        add_action('admin_init', [$this, 'is_movie_import'], 20);
    }

    public function is_movie_details_submit()
    {
        if (!isset($_POST['selected_movie_id']) || empty($_POST['selected_movie_id'])) {
            return;
        }
        $tmdb_movie = new TMDB_Movie(esc_sql($_POST['selected_movie_id']));
        $tmdb_movie->fetch_multilanguage_movie_details();
        $_POST['tmdb_movie_data'] = $tmdb_movie;
    }

    public function is_movie_import()
    {
        if (!isset($_POST['tmdb_movie_id']) || !isset($_POST['submit']) || empty($_POST['tmdb_movie_id']) || $_POST['submit'] !== 'Import Movie') {
            return;
        }
        $this->refresh_tmdb_woo_ids();
        $tmdb_options = get_option(TMDB_OPTIONS);
        if (isset($tmdb_options['selected_movie_prototype_id']) && !empty($tmdb_options['selected_movie_prototype_id'])) {
            $product = wc_get_product($tmdb_options['selected_movie_prototype_id']);
            global $tmdb_languages;
            if ($product instanceof WC_Product) {
                // Create from simple product prototype
                $tmdb_simple_product = new TMDB_Import_Simple_Variable_Product($product, $_POST, $tmdb_languages->get_current_language());
                $tmdb_simple_product_id = $tmdb_simple_product->get_created_product_id();
                foreach ($tmdb_languages->get_supported_languages() as $language_code) {
                    if ($tmdb_languages->get_current_language() !== $language_code) {
                        $original_translated_post_id = apply_filters('wpml_object_id', (int) $tmdb_options['selected_movie_prototype_id'], 'product', false, $language_code);
                        $translated_product = wc_get_product($original_translated_post_id);
                        $translated_product = new TMDB_Import_Simple_Variable_Product($translated_product, $_POST, $language_code);
                        $translated_product_id = $translated_product->get_created_product_id();
                        $this->assign_translations($tmdb_simple_product_id, $translated_product_id, $language_code);
                    }
                }
            }
        } else {
            // Create without product prototype
        }
    }

    public function refresh_tmdb_woo_ids()
    {
        if (isset($_POST['tmdb-woo-ids']) && $_POST['tmdb-woo-ids'] !== '') {
            $json_string = str_replace('\\', '', $_POST['tmdb-woo-ids']);
            $tmdb_woo_ids = json_decode($json_string, false, 512, JSON_THROW_ON_ERROR);
            foreach ($tmdb_woo_ids as $tmdb_woo_id) {
                $wp_term = get_term($tmdb_woo_id->woo_id, $tmdb_woo_id->tax_name);
                if ($wp_term instanceof \WP_Term) {
                    update_term_meta($wp_term->term_id, '_tmdb_id', $tmdb_woo_id->tmdb_id);
                }
            }
        }
    }

    public function assign_translations($original_lang_product_id, $translated_product_id, $translation_language_code)
    {
        $wpml_element_type = apply_filters('wpml_element_type', 'product');
        $get_language_args = ['element_id' => $original_lang_product_id, 'element_type' => $wpml_element_type];
        $original_post_language_info = apply_filters('wpml_element_language_details', null, $get_language_args);
        $set_language_args = [
            'element_id' => $translated_product_id,
            'element_type' => $wpml_element_type,
            'trid' => $original_post_language_info->trid,
            'language_code' => $translation_language_code,
            'source_language_code' => $original_post_language_info->language_code,
        ];
        do_action('wpml_set_element_language_details', $set_language_args);
    }
}
new TMDB_Int_Form_Submits();
