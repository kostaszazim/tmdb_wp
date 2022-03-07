<?php

if (!defined('ABSPATH')) {
    exit();
}

abstract class TMDB_Multilanguage_Content
{
    protected $available_languages = [];
    protected $tmdb_movie_id;


    function __construct($movie_id)
    {
        $this->tmdb_movie_id = $movie_id;
        $this->setup_languages();
    }

    protected function setup_languages()
    {
        global $tmdb_languages;
        $this->available_languages = $tmdb_languages->get_supported_languages();
    }
}
