<?php

class TMDB_Admin_Ajax
{
    private $options;
    function __construct()
    {
        $this->options = get_option(TMDB_OPTIONS);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_ajax_script']);
        add_action('wp_ajax_fetch_tmdb_movie_info', [$this, 'fetch_tmdb_movie_info']);
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

    public function enqueue_admin_ajax_script()
    {
        wp_register_script('tmdb-ajax', TMDB_INT__PLUGIN_DIR_URL . 'admin/assets/js/tmdb-admin-ajax.js', ['jquery']);
        wp_enqueue_script('tmdb-ajax');
        wp_localize_script('tmdb-ajax', 'admin_ajax', ['ajax_url' => admin_url('admin-ajax.php')]);
    }

    private function build_movie_response_data ($response) {
        $small_image_size = $this->options['available_poster_sizes'][0];
        $new_results = [];
        foreach ($response->results as $key => $result) {
            $new_results[$key]['id'] = $result->id;
            $new_results[$key]['title'] = $result->title;
            $new_results[$key]['poster_path'] = $this->options['base_img_url']. $small_image_size . $result->poster_path ;
            $timestamp = strtotime($result->release_date);
            $new_results[$key]['year'] = date("Y", $timestamp);
        }
        return $new_results;
    }
}

new TMDB_Admin_Ajax();
