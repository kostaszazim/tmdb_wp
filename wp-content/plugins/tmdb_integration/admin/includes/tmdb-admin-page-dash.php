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
        add_action('admin_footer', [$this, 'add_micromodal_markup'], PHP_INT_MAX);
        wp_enqueue_style('tmdb-admin');
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        wp_enqueue_style('jquery-ui', 'https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css');
        wp_enqueue_script( 'select2-jq', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['jquery'] );
        wp_enqueue_script( 'tmdb',  TMDB_INT__PLUGIN_DIR_URL . '/admin/assets/js/tmdb_int.js' , ['jquery'] );
        wp_localize_script('tmdb', 'tmdb_languages', ['current_language' => apply_filters( 'wpml_current_language', NULL )]);
        wp_enqueue_style('select2-style', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
        wp_enqueue_script('micromodal', TMDB_INT__PLUGIN_DIR_URL . '/admin/libs/micromodal/micromodal.min.js' );
        wp_enqueue_style('micromodal-style', TMDB_INT__PLUGIN_DIR_URL . '/admin/libs/micromodal/micromodal.css' );

    }

    public function setup_menu_page()
    {
        add_action('admin_menu', [$this, 'add_main_menu_page'], 9);
    }

    public function add_micromodal_markup () {
	ob_start(); ?>
	<div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-1-title" data-original-title=" <?php echo __("Choose", "tmdb_int") . " %taxonomy%" . __(" as a replacement for", "tmdb_int"). ":"; ?>">
           
          </h2>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="modal-1-content">
          <p>
            <select name="chosen_taxonomy_override" id="chosen_taxonomy_override" data-selected_woo_term_id=""></select>
          </p>
        </main>
        <footer class="modal__footer">
          <button class="modal__btn modal__btn-primary"><?php echo __("Submit"); ?></button>
          <button class="modal__btn" data-micromodal-close aria-label="Close this dialog window"><?php echo __("Close"); ?></button>
        </footer>
      </div>
    </div>
  </div>
<?php
$output = ob_get_contents();
ob_end_clean();
echo $output;
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
