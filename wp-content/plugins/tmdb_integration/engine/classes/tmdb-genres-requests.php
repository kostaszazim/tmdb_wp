<?php

if (!defined('ABSPATH')) {
    exit;
}

class TMDB_Genres_Requests extends Tmdb_Request
{
    private $language_code;
    
    function __construct($language)
    {
        $this->language_code = $language;
        parent::__construct();
    }

    public function fetch_genres () {
        return parent::make_request('genre/movie/list?language=' . $this->language_code);
    }
}