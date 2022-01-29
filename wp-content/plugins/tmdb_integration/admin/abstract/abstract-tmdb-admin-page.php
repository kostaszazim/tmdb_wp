<?php

abstract class TMDB_Admin_Page
{
    public $page_slug;
    public static $isParent;
    protected $menu_name;
    protected $role;

    function __construct($contruct = true,  $menu_name, $role)
    {
        $this->page_slug = $this->setup_page_slug_by_class_name();
        if ($contruct) {
            $this->setup_menu_page();
        }
    }

    public function setup_page_slug_by_class_name()
    {
        $this_class_lower = strtolower(get_class());
        return str_replace('_', '-', $this_class_lower);
    }

    abstract public function setup_menu_page();
    abstract public function displayAdminPage();

    protected function get_children_classes()
    {
        $children_classes = [];
        foreach (get_declared_classes() as $class) {
            if ($class instanceof static) {
                $children_classes[] = $class;
            }
        }
        return $children_classes;
    }
}
