<?php

if (!defined('ABSPATH')) {
    exit;
}

class TMDB_Admin_Settings extends TMDB_Menu_Page
{

    public function setup_menu_page()
    {
        $this->addTmdbAdminMenu();
    }

    public function displayAdminPage()
    {
        
    }

    public function addTmdbAdminMenu()
    {
        $silbing_classes = $this->get_children_classes();
        $parent_class = array_filter($silbing_classes, function ($element) {
            return $element::isParent;
        });

        $parent_class_instance = new $parent_class();
        $parent_class_slug = $parent_class_instance->page_slug ;
        add_submenu_page($parent_class_slug, $this->page_slug, 'Settings', 'administrator', $this->page_slug, [$this, 'displayAdminPage']  );
    }

    protected function isParent()
    {
        return false;
    }

}