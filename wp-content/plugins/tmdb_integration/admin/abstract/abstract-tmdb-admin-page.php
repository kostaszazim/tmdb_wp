<?php

abstract class TMDB_Admin_Page
{
    public $page_slug;
    protected $menu_name;
    protected $role;
    protected $icon_filename;

    function __construct($menu_name, $role, $icon_filename = '')
    {
        $this->setup_icon_filename($icon_filename);
        $this->page_slug = $this->setup_page_slug_by_class_name();
        $this->menu_name = $menu_name;
        $this->role = $role;
        $this->setup_menu_page();
    }

    protected function setup_icon_filename ($icon_filename) {
        if (!empty($icon_filename)) {
            $filename = TMDB_INT__PLUGIN_DIR_URL . 'assets/icons/' . $icon_filename; 
        } else {
           $filename = 'dashicons-chart-area'; // Default fallback
        }
        $this->icon_filename = $filename;
    }

    protected function setup_page_slug_by_class_name($class_name = "")
    {   
        $this_class_lower = empty($class_name) ?  strtolower(get_class($this)): strtolower( $class_name );
        $final_slug = str_replace('_', '-', $this_class_lower);
        $_SESSION[TMDB_PAGE_NOW_SLUG] = $final_slug;
        return $final_slug;
    }

    abstract public function setup_menu_page();
    abstract static  function isParent();

    protected function get_children_classes()
    {
        $children_classes = [];
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, __CLASS__)) {
                $children_classes[] = $class;
            }
        }
        return $children_classes;
    }

    public function displayAdminPage () {
       $view_template = $this->page_slug . '-view.php';
       if (file_exists(TMDB_INT__PLUGIN_DIR . '/admin/views/' . $view_template)) {
           require_once TMDB_INT__PLUGIN_DIR . '/admin/views/' . $view_template;
       } else {
           echo __("View Template Not Found!");
       }
    }

}
