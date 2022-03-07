<?php

class TMDB_Admin_Ajax
{
    private $options;
    function __construct()
    {
        $this->options = get_option(TMDB_OPTIONS);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_ajax_script']);
        add_action('wp_ajax_fetch_tmdb_movie_info', [$this, 'fetch_tmdb_movie_info']);
        add_action('wp_ajax_tmdb_add_taxonomy_term', [$this, 'tmdb_add_taxonomy_term']);
        add_action('wp_ajax_fetch_local_woo_product', [$this, 'fetch_local_woo_product']);
        add_action('wp_ajax_tmdb_add_taxonomy_term_tmdb_id', [$this, 'tmdb_add_taxonomy_term_tmdb_id']);
        add_action('wp_ajax_tmdb_delete_taxonomy_term_tmdb_id', [$this, 'tmdb_delete_taxonomy_term_tmdb_id']);
    }

    public function fetch_tmdb_movie_info()
    {
        if (!isset($_POST['request']) || $_POST['request']['term'] === '') {
            wp_send_json_error('No movie title found');
        }

        $movie_term = $_POST['request']['term'];
        $request = new TMDB_Movie_Search_Request();
        $response = $request->search_movie($movie_term);
        $response = $this->build_movie_response_data(json_decode($response));
        wp_send_json_success($response);
        die();
    }

    public function fetch_local_woo_product()
    {
        if (!isset($_POST['request']) || $_POST['request']['term'] === '') {
            wp_send_json_error('No movie title found');
            die();
        }
        // WP_Query arguments
        $args = [
            'post_type' => ['product'],
            'post_status' => ['publish'],
            'posts_per_page' => '-1',
            'meta_query' => [
                [
                    'key' => '_sku',
                    'value' => sanitize_text_field($_POST['request']['term']),
                    'compare' => 'like',
                ],
            ],
        ];

        // The Query
        $results = new WP_Query($args);
        if ($results->post_count > 0) {
            wp_send_json_success($this->build_local_products_array($results));
            die();
        }
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            's' => sanitize_text_field($_POST['request']['term']),
        ];

        $results = new WP_Query($args);
        $formated_results = $this->build_local_products_array($results);
        wp_send_json_success($formated_results);
        die();
    }

    private function build_local_products_array($response)
    {
        $formated_results = [];
        foreach ($response->posts as $post) {
            array_push($formated_results, ['id' => $post->ID, 'title' => $post->post_title]);
        }
        return $formated_results;
    }
    public function enqueue_admin_ajax_script()
    {
        wp_register_script('tmdb-ajax', TMDB_INT__PLUGIN_DIR_URL . 'admin/assets/js/tmdb-admin-ajax.js', ['jquery']);
        wp_enqueue_script('tmdb-ajax');
        wp_localize_script('tmdb-ajax', 'admin_ajax', ['ajax_url' => admin_url('admin-ajax.php')]);
    }

    private function build_movie_response_data($response)
    {
        $small_image_size = $this->options['available_poster_sizes'][0];
        $new_results = [];
        foreach ($response->results as $key => $result) {
            $new_results[$key]['id'] = $result->id;
            $new_results[$key]['title'] = $result->title;
            $new_results[$key]['poster_path'] = $this->options['base_img_url'] . $small_image_size . $result->poster_path;
            $timestamp = strtotime($result->release_date);
            $new_results[$key]['year'] = date('Y', $timestamp);
        }
        return $new_results;
    }

    public function tmdb_add_taxonomy_term()
    {
        global $tmdb_languages;
        $current_language = $tmdb_languages->get_current_language();
        $other_languages = $tmdb_languages->get_other_languages();
        $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
        if (isset($_POST['tax_data']) && wp_verify_nonce($nonce, 'tmdb_import')) {
            $tax_data = $_POST['tax_data'];
            $inserted_term = wp_insert_term(esc_html($tax_data['taxName' . ucfirst($current_language)]), esc_sql($tax_data['taxonomy']));
            if (is_wp_error($inserted_term)) {
                $tx_id_or = $inserted_term->get_error_data();
            }

            if (!is_wp_error($inserted_term)) {
                $tx_id_or = $inserted_term['term_id'];
            }
            if (isset($tax_data['tmdbId']) && $inserted_term instanceof WP_Term) {
                update_term_meta($inserted_term['term_id'], '_tmdb_id', $tax_data['tmdbId']);
            }
            if ($inserted_term['term_id']) {
                $inserted_term = get_term_by('id', $inserted_term['term_id'], $tax_data['taxonomy']);

                foreach ($other_languages as $other_language) {
                    global $sitepress;
                    $term_title = isset($tax_data['taxName' . ucfirst($other_language)]) ? $tax_data['taxName' . ucfirst($other_language)] : $tax_data['taxName' . ucfirst($current_language)];
                    $inserted_term_trans = wp_insert_term(esc_html($term_title), esc_sql($tax_data['taxonomy']), [
                        'slug' => sanitize_title($term_title) . '_' . $other_language,
                    ]);
                    if (is_wp_error($inserted_term_trans)) {
                        $tx_id_trans = $inserted_term_trans->get_error_data();
                    }

                    if (!is_wp_error($inserted_term_trans)) {
                        $tx_id_trans = $inserted_term_trans['term_id'];
                    }
                    update_term_meta($tx_id_trans, '_tmdb_id', $tax_data['tmdbId']);
                    $wpml_element_type = apply_filters('wpml_element_type', $tax_data['taxonomy']);
                    $get_language_args = ['element_id' => $tx_id_or, 'element_type' => $wpml_element_type];
                    $tx_info = apply_filters('wpml_element_language_details', null, $get_language_args);
                    $set_language_args = [
                        'element_id' => $tx_id_trans,
                        'element_type' => $wpml_element_type,
                        'trid' => $tx_info->trid,
                        'language_code' => $other_language,
                        'source_language_code' => $tx_info->language_code,
                    ];
                    do_action('wpml_set_element_language_details', $set_language_args);
                }
            } else {
                wp_send_json_error($inserted_term);
            }
            wp_send_json(['status' => 'ok', 'term' => $inserted_term]);
        } else {
            wp_send_json_error('No valid data received');
        }
        die();
    }

    public function tmdb_add_taxonomy_term_tmdb_id()
    {
        $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
        $tmdb_tax_id = isset($_POST['tmdb_tax_id']) ? $_POST['tmdb_tax_id'] : '';
        $woo_id = isset($_POST['woo_id']) ? $_POST['woo_id'] : '';
        $woo_tax = isset($_POST['woo_tax']) ? $_POST['woo_tax'] : '';

        if (!wp_verify_nonce($nonce, 'tmdb_import') || empty($tmdb_tax_id) || empty($woo_id) || empty($woo_tax)) {
            wp_send_json_error('wrong data received');
            die();
        }
        $wp_term = get_term($woo_id, $woo_tax);
        if ($wp_term instanceof WP_Term) {
            update_term_meta($wp_term->term_id, '_tmdb_id', $tmdb_tax_id);
        }

        wp_send_json_success(['woo_id' => $wp_term->term_id, 'woo_tax' => $wp_term->taxonomy, 'inserted_tmdb_id' => get_term_meta($wp_term->term_id, '_tmdb_id', true)]);
    }

    public function tmdb_delete_taxonomy_term_tmdb_id()
    {
        $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
        $woo_id = isset($_POST['woo_id']) ? $_POST['woo_id'] : '';
        $woo_tax = isset($_POST['woo_tax']) ? $_POST['woo_tax'] : '';

        if (!wp_verify_nonce($nonce, 'tmdb_import') || empty($woo_id) || empty($woo_tax)) {
            wp_send_json_error('wrong data received');
            die();
        }
        $wp_term = get_term($woo_id, $woo_tax);
        if ($wp_term instanceof WP_Term) {
            update_term_meta($wp_term->term_id, '_tmdb_id', '');
        }

        wp_send_json_success(['woo_id' => $wp_term->term_id, 'woo_tax' => $wp_term->taxonomy, 'inserted_tmdb_id' => get_term_meta($wp_term->term_id, '_tmdb_id', true)]);
    }
}

new TMDB_Admin_Ajax();
