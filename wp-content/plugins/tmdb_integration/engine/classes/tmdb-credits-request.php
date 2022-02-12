<?php

if (!defined('ABSPATH')) {
    exit;
}

class TMDB_Credits_Requests extends Tmdb_Request
{
    private $tmdb_movie_id;
    private $language_code;
    
    function __construct($movie_id, $language)
    {
        $this->tmdb_movie_id = $movie_id;
        $this->language_code = $language;
        parent::__construct();
    }

    public function fetch_movie_credits () {
        return parent::make_request('movie/'. $this->tmdb_movie_id . '/credits?language=' . $this->language_code);
    }
}