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

        $tmdb_options = get_option(TMDB_OPTIONS);
        if (isset($tmdb_options['selected_movie_prototype_id']) && !empty($tmdb_options['selected_movie_prototype_id'])) {
            $product = wc_get_product($tmdb_options['selected_movie_prototype_id']);
            global $tmdb_languages;
            if ($product instanceof WC_Product) {
                // Create from simple product prototype
                $tmdb_simple_product = new TMDB_Import_Simple_Variable_Product($product, $_POST, $tmdb_languages->get_current_language());
            }
        } else {
                // Create without product prototype
        }
    }
}
new TMDB_Int_Form_Submits();
