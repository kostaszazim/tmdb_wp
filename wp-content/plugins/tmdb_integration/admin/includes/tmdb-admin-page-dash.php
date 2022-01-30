<?php

class TMDB_Admin_Main_Menu_Page extends TMDB_Admin_Page
{
    public static $isParent;

    function __construct($menu_name, $role, $icon_filename = "")
    {
        parent::__construct($menu_name, $role, $icon_filename);
    }


    public function setup_menu_page()
    {
        add_action('admin_menu', [$this, 'add_main_menu_page'], 9);
    }

    public function add_main_menu_page () {
        add_menu_page($this->menu_name, $this->menu_name, $this->role, $this->page_slug, [$this, 'displayAdminPage'], $this->icon_filename, 26 );
    }

    public static function isParent()
    {
        return true;
    }
}

new TMDB_Admin_Main_Menu_Page("TMDB Integration", 'administrator', 'tmdb_icon.png' );