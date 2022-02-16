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

    public function fetch_local_woo_product () {
        if (!isset($_POST['request']) || $_POST['request']['term'] === '') {
            wp_send_json_error('No movie title found');
            die();
        }
        // WP_Query arguments
        $args = array(
        	'post_type'              => array( 'product' ),
        	'post_status'            => array( 'publish' ),
        	'posts_per_page'         => '-1',
        	'meta_query'             => array(
        		array(
        			'key'     => '_sku',
        			'value'   => sanitize_text_field($_POST['request']['term']),
        			'compare' => 'like',
        		),
        	),
        );

// The Query
        $results = new WP_Query( $args );
        if ($results->post_count > 0) {
            wp_send_json_success($this->build_local_products_array($results));
            die();
        }
        $args = array(
            'post_type'     => 'product',
            'post_status'   => 'publish',
            's' => sanitize_text_field( $_POST['request']['term']),
        );

        $results = new WP_Query($args);
        $formated_results = $this->build_local_products_array($results);
        wp_send_json_success($formated_results);
        die();
    }

    private function build_local_products_array ($response) {
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
        $nonce = isset($_POST['nonce']) ? $_POST['nonce']: '';
        if (isset($_POST['taxonomy']) && isset($_POST['termValue'])  && wp_verify_nonce($nonce, 'tmdb_import')) {
            $inserted_term = wp_insert_term(esc_html($_POST['termValue']), esc_sql($_POST['taxonomy']));
            if (isset($_POST['tmdbId'])) {
                update_term_meta($inserted_term['term_id'], '_tmdb_id', $_POST['tmdbId'] );
            }
            if ($inserted_term['term_id']) {
                $inserted_term = get_term_by('id', $inserted_term['term_id'], $_POST['taxonomy']);
            } else {
                wp_send_json_error($inserted_term);
            }
            wp_send_json(['status' => 'ok', 'term' => $inserted_term]);
        } else {
            wp_send_json_error("No valid data received");
        }
        die();
    }
}

new TMDB_Admin_Ajax();
