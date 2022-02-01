<?php

class TMDB_Configuration_Request extends Tmdb_Request
{
    public function get_configuration () {
       return   parent::make_request('configuration');
    }
}