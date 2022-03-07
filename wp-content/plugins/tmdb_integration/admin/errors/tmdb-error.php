<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Error 
{
    protected $has_error;
    protected $status_code;
    protected $status_message;

    function __construct()
    {
        add_action('admin_init', [$this, 'determine_error'], 8);
    }

    public function determine_error () {
        if (isset($_SESSION[TMDB_PAGE_SESSION_CONFIG])) {
            $error_object = json_decode($_SESSION[TMDB_PAGE_SESSION_CONFIG]);
            if (isset($error_object->status_code)) {
                $this->has_error = true;
                $this->status_code = $error_object->status_code;
                $this->status_message = $error_object->status_message;
            }
        }
    }

    public function has_error () {
        return $this->has_error;
    }

    public function get_error_message () {
        return $this->status_message;
    }
}

$tmdb_error = new TMDB_Error();