<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Credits extends TMDB_Multilanguage_Content
{
    private $actors = [];
    private $directors = [];
    private $writers = [];
    private $credits_response = [];
    private $options = [];

    public function __construct($movie_id)
    {
        parent::__construct($movie_id);
        $this->options = get_option(TMDB_OPTIONS);
        $this->fetch_movie_credits();
    }

    private function fetch_movie_credits()
    {
        foreach ($this->available_languages as $lang_code) {
            $request = new TMDB_Credits_Requests($this->tmdb_movie_id, $lang_code);
            $this->credits_response[$lang_code] = json_decode($request->fetch_movie_credits());
        }
        $this->setup_actors();
        $this->setup_writers();
        $this->setup_directors();
    }

    private function setup_actors()
    {
        foreach ($this->available_languages as $lang_code) {
            $counter = (int) $this->options['max_actors'];
            $min_actor_popularity = (int) $this->options['min_actor_popularity'];
            $this->actors[$lang_code] = array_map(
                function ($element) {
                    $actor = [];
                    $actor['id'] = $element->id;
                    $actor['name'] = $element->original_name;
                    return $actor;
                },
                array_filter($this->credits_response[$lang_code]->cast, function ($element) use (&$counter, $min_actor_popularity) {
                    while ($counter > 0 && (float) $element->popularity > $min_actor_popularity && $element->known_for_department === 'Acting') {
                        $counter--;
                        return $element;
                    }
                }),
            );
        }
    }

    private function setup_writers()
    {
        foreach ($this->available_languages as $lang_code) {
            $this->writers[$lang_code] = array_map(
                function ($element) {
                    $writer = [];
                    $writer['id'] = $element->id;
                    $writer['name'] = $element->original_name;
                    return $writer;
                },
                array_filter($this->credits_response[$lang_code]->crew, function ($element) {
                    return $element->known_for_department === 'Writing' && $element->department === 'Writing';
                }),
            );
        }
    }

    private function setup_directors()
    {
        foreach ($this->available_languages as $lang_code) {
            $this->directors[$lang_code] = array_map(
                function ($element) {
                    $writer = [];
                    $writer['id'] = $element->id;
                    $writer['name'] = $element->original_name;
                    return $writer;
                },
                array_filter($this->credits_response[$lang_code]->crew, function ($element) {
                    return $element->job === 'Director';
                }),
            );
        }
    }

    public function get_actors()
    {
        return $this->actors;
    }

    public function get_writers()
    {
        return $this->writers;
    }

    public function get_directors()
    {
        return $this->directors;
    }
}
