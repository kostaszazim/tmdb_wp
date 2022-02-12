<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Int_Form_Submits
{
    function __construct()
    {
        add_action('admin_init', [$this, 'is_movie_details_submit'], 30);
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
}
new TMDB_Int_Form_Submits();
