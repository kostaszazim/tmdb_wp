<?php

class TMDB_Admin_Main_Menu_Page extends TMDB_Admin_Page
{
    public static $isParent;

    function __construct($menu_name, $role, $icon_filename = '')
    {
        parent::__construct($menu_name, $role, $icon_filename);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_tmdb_admin_css']);
    }

    public function enqueue_tmdb_admin_css()
    {
        if (!in_array($this->page_slug, $_GET)) {
            return;
        }
        wp_register_style('tmdb-admin', TMDB_INT__PLUGIN_DIR_URL . '/admin/assets/css/tmdb-admin.css');
        wp_enqueue_style('tmdb-admin');
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        wp_enqueue_style('jquery-ui', 'https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css');
        wp_enqueue_script( 'select2-jq', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['jquery'] );
        wp_enqueue_script( 'tmdb',  TMDB_INT__PLUGIN_DIR_URL . '/admin/assets/js/tmdb_int.js' , ['jquery'] );
        wp_enqueue_style('select2-style', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
    }

    public function setup_menu_page()
    {
        add_action('admin_menu', [$this, 'add_main_menu_page'], 9);
    }

    public function add_main_menu_page()
    {
        add_menu_page($this->menu_name, $this->menu_name, $this->role, $this->page_slug, [$this, 'displayAdminPage'], $this->icon_filename, 26);
    }

    public static function isParent()
    {
        return true;
    }
}

new TMDB_Admin_Main_Menu_Page('TMDB Integration', 'administrator', 'tmdb_icon.png');
