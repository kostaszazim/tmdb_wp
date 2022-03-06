<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Language_Setup
{
    private $site_language_codes = [];
    private $tmdb_language_codes = [];
    private $supported_languages = [];

    function __construct()
    {
        add_action('admin_init', [$this, 'get_site_language_codes']);
        add_action('admin_init', [$this, 'get_tmdb_available_languages']);
        add_action('admin_init', [$this, 'filter_supported_languages'], 20);
    }


    public function get_tmdb_available_languages () {
        $api_translations = new TMDB_Available_Translations_Request();
        $api_translations_response = $api_translations->get_translations();
        $api_translations_response = json_decode($api_translations_response);
        if (is_array($api_translations_response) && !empty($api_translations_response)) {
            $this->tmdb_language_codes = array_map(function($element) {
                return $element->iso_639_1;
            }, $api_translations_response);
        } 
    }

    public function get_site_language_codes () {
        $languages = [];  
        if (class_exists('SitePress')) {
          $languages = apply_filters( 'wpml_active_languages', null );  
          $this->site_language_codes = array_map(function ($element) {
              return $element['language_code'];
          }, $languages);
        } else {
            $this->site_language_codes[] = get_bloginfo("language");
        }
    }

    public function filter_supported_languages () {
        foreach ($this->site_language_codes as $site_language_code) {
            if (in_array($site_language_code, $this->tmdb_language_codes)) {
                $this->supported_languages[] = $site_language_code;
            }
        }
    }

    public function get_current_language () {
        if (class_exists('SitePress')) {
            return ICL_LANGUAGE_CODE;
        }

        return  get_bloginfo("language");
    }

    public function get_supported_languages () {
        return $this->supported_languages;
    }

    public function get_other_languages () {
        return array_filter($this->get_supported_languages(), function ($language) {
            return $language !== $this->get_current_language();
        });
    }
}


$tmdb_languages = new TMDB_Language_Setup();