<?php

abstract class Tmdb_Request
{
    protected $curl;
    protected $api_key;
    protected $api_base_url;
    protected $api_endpoint;

    function __construct()
    {
        $this->setup_api_key();
        $this->setup_api_base_url();
        $this->setup_curl();
    }

    protected function setup_api_key () {
        $tmdb_options = get_option(TMDB_OPTIONS);
        $this->api_key = isset($tmdb_options['api_key']) ? $tmdb_options['api_key'] : '' ;
    }

    protected function setup_api_base_url () {
        $this->api_base_url = TMDB_BASE_API_URL;
    }


    protected function setup_curl () {
        $this->curl = curl_init();
    }

    protected function make_request ($endpoint_with_args) {
        $endpoint = $this->api_base_url . $endpoint_with_args;
        $auth_api_endpoint =  add_query_arg(['api_key' => $this->api_key], $endpoint);
        curl_setopt_array($this->curl, [
            CURLOPT_URL => $auth_api_endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ]);
        $response = curl_exec($this->curl);
        curl_close($this->curl);
        return $response;
    }
}