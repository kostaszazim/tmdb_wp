<?php

if (!defined('ABSPATH')) {
    exit();
}


class TMDB_Available_Translations_Request extends Tmdb_Request
{

    public function get_translations () {
        return parent::make_request('configuration/languages');
    }

}