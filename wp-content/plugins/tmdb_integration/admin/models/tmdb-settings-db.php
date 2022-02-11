<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Settings_Db
{
    private $mapped_taxonomies = [];
    private $options;

    function __construct()
    {
        $this->options = get_option(TMDB_OPTIONS);
        $this->init_mapped_taxonomies();
    }

    private function init_mapped_taxonomies()
    {
        foreach ($this->options as $key => $option) {
            if (strpos($key, 'woo_taxonomy') !== false) {
                $this->mapped_taxonomies[$key] = $option;
            }
        }
    }

    public function get_mapped_taxonomies () {
        return $this->mapped_taxonomies;
    }
}
