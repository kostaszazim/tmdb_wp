<?php

abstract class TMDB_Admin_Sub_Menu_Page extends TMDB_Admin_Page
{
    function __construct($contruct = true,$menu_name, $role)
    {
        parent::__construct($contruct, $menu_name, $role);
        self::$isParent = false;
    }

    public function setup_page_slug_by_class_name()
    {
        $this_class_lower = strtolower(get_class());
        return str_replace('_', '-', $this_class_lower);
    }

    abstract public function setup_submenu_page();
    abstract public function addTmdbAdminMenu();
    abstract public function displayAdminPage();

    public function setup_menu_page()
    {
        $silbing_classes = $this->get_children_classes();
        $parent_class = array_filter($silbing_classes, function ($element) {
            return $element::isParent;
        });

        $parent_class_instance = new $parent_class();
        $parent_class_slug = $parent_class_instance->page_slug ;
        add_submenu_page($parent_class_slug, $this->page_slug, $this->menu_name, $this->role, $this->page_slug, [$this, 'displayAdminPage']  );
    }
}
