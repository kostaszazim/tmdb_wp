<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Movie extends TMDB_Multilanguage_Content
{
    private $movie_details_response = [];
    private $movie_titles = [];
    private $movie_summary = [];
    private $movie_poster;
    private $movie_genres = [];
    private $movie_actors = [];
    private $movie_prod_year;
    private $movie_spoken_languages = [];
    private $movie_release_date;
    private $movie_prod_countries = [];
    private $movie_production_companies = [];
    private $movie_original_title;
    private $movie_writers = [];
    private $movie_directors = [];

    public function fetch_multilanguage_movie_details()
    {
        foreach ($this->available_languages as $language_code) {
            $movies_request = new TMDB_Movie_Requests($this->tmdb_movie_id, $language_code);
            $this->movie_details_response[$language_code] = json_decode($movies_request->fetch_movie_details());
        }

        if (empty($this->movie_details_response)) {
            return;
        }

        $this->setup_movie_data();
        $this->setup_credits();
    }

    private function setup_movie_data()
    {
        $tmdb_options = get_option(TMDB_OPTIONS);
        foreach ($this->movie_details_response as $lang_code => $response) {
            $this->movie_titles[$lang_code] = $response->title;
            $this->movie_summary[$lang_code] = $response->overview;
            $this->movie_poster = $tmdb_options['base_img_url'] . $tmdb_options['poster_sizes'] . $response->poster_path;
            $this->movie_genres[$lang_code] = array_map(function ($element) {
                $genre = [];
                $genre['id'] = $element->id;
                $genre['name'] = $element->name;
                return $genre;
            }, $response->genres);
            $date = DateTime::createFromFormat('Y-m-d', $response->release_date);
            $this->movie_prod_year = $date->format('Y');
            $this->movie_spoken_languages[$lang_code] = array_map(function ($element) {
                $spoken_lang = [];
                $spoken_lang['id'] = $element->iso_639_1;
                $spoken_lang['name'] = $element->name;
                return $spoken_lang;
            }, $response->spoken_languages);

            $this->movie_release_date = $response->release_date;
            $this->movie_prod_countries = array_map(function ($element) {
                $prod_country = [];
                $prod_country['id'] = $element->iso_3166_1;
                $prod_country['name'] = $element->name;
                return $prod_country;
            }, $response->production_countries);

            $this->movie_original_title = $response->original_title;

            $this->movie_production_companies = array_map(function ($element) {
                $company = [];
                $company['id'] = $element->id;
                $company['name'] = $element->name;
                return $company;
            }, $response->production_companies);
        }
    }

    private function setup_credits () {
        $movie_credits = new TMDB_Credits($this->tmdb_movie_id);
        $this->movie_actors = $movie_credits->get_actors();
        $this->movie_directors = $movie_credits->get_directors();
        $this->movie_writers = $movie_credits->get_writers();
    }

    public function get_movie_title () {
        return $this->movie_titles;
    }

    public function get_movie_poster () {
        return $this->movie_poster;
    }

    public function get_movie_summary () {
        return $this->movie_summary;
    }
}
