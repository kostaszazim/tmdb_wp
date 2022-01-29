<?php

abstract class TMDB_Admin_Main_Menu_Page extends TMDB_Admin_Page
{

    function __construct($contruct = true, $menu_name, $role)
    {
        parent::__construct($contruct, $menu_name, $role);

        self::$isParent = true;
    }

    public function setup_page_slug_by_class_name()
    {
        $this_class_lower = strtolower(get_class());
        return str_replace('_', '-', $this_class_lower);
    }

    public function setup_menu_page()
    {
        add_action('admin_menu', [$this, 'add_main_menu_page'], 9);
    }

    public function add_main_menu_page () {
        add_menu_page($this->page_slug, $this->menu_name, $this->role, $this->page_slug, [$this, 'displayAdminPage'], 'dashicons-chart-area', 26 );
    }

    abstract public function addTmdbAdminMenu();
    abstract public function displayAdminPage();
    abstract protected function isParent();
}
