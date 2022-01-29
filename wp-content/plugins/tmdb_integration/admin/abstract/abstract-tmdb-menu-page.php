<?php

abstract class TMDB_Menu_Page
{
    public $page_slug;
    public static $isParent;

    function __construct($contruct = true)
    {
        $this->page_slug = $this->setup_page_slug_by_class_name();
        if ($contruct) {
            $this->setup_menu_page();
            self::$isParent = $this->isParent();
        }
    }

    public function setup_page_slug_by_class_name()
    {
        $this_class_lower = strtolower(get_class());
        return str_replace('_', '-', $this_class_lower);
    }

    abstract public function setup_menu_page();
    abstract public function addTmdbAdminMenu();
    abstract public function displayAdminPage();
    abstract protected function isParent();

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
