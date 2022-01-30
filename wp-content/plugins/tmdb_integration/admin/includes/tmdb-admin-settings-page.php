<?php

class TMDB_Admin_Sub_Menu_Page extends TMDB_Admin_Page
{
    function __construct($menu_name, $role, $icon_finename = "")
    {
        parent::__construct($menu_name, $role, $icon_finename);
    }

    public function setup_menu_page()
    {
        $silbing_classes = $this->get_children_classes();
        $parent_class = array_filter($silbing_classes, function ($element) {
            return call_user_func($element .'::isParent');
        });
        $found_parent = array_shift($parent_class);
        $parent_class_slug = $this->setup_page_slug_by_class_name($found_parent);
        add_submenu_page($parent_class_slug, $this->menu_name, $this->menu_name, $this->role, $this->page_slug, [$this, 'displayAdminPage']  );
    }

    public static function isParent()
    {
       return false;
    }
}

add_action('admin_menu', function () {
    new TMDB_Admin_Sub_Menu_Page("Settings", 'administrator', );
});
