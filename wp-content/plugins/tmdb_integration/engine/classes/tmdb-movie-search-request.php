<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Movie_Search_Request extends Tmdb_Request
{
    public function search_movie ($movie) { 
        return $this->make_request('search/movie?language=' . ICL_LANGUAGE_CODE . '&query='. rawurlencode($movie) .'&include_adult=false');
    }
}
