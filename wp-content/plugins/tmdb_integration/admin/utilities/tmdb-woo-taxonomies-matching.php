<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Woo_Taxonomies_Matching
{
    private $found = [];
    private $not_found = [];
    private $current_taxonomy = null;
    private $tmdb_taxonomies = [];

    public function get_html($woo_term)
    {
        $term_id = $woo_term->term_id;
        $term_name = $woo_term->name;
        $html = '';
        if (in_array($term_id, $this->found) || in_array($term_name, $this->found)) {
            $found_index = array_search($term_id, $this->found);
            $html .= 'selected="selected" data-tmdb-id="' . $found_index . '" data-woo-id="' . $term_id . '"';
        }
        return $html;
    }

    public function match_taxonomies( \WP_Term $woo_taxonomy)
    {
        $tmdb_id = get_term_meta($woo_taxonomy->term_id, "_tmdb_id", true);
        global $tmdb_languages;
        if ($this->current_taxonomy !== $woo_taxonomy->taxonomy) {
            $this->found = [];
            $this->not_found = $this->tmdb_taxonomies[$tmdb_languages->get_current_language()];
            $this->current_taxonomy = $woo_taxonomy->taxonomy;
        }
        $found_taxonomy = array_filter($this->tmdb_taxonomies[$tmdb_languages->get_current_language()], function ($tmdb_tax) use ($tmdb_id) {
            if (isset($tmdb_tax['id'])) {
                return (string) $tmdb_tax['id'] === (string) $tmdb_id;
            }
            return false;
        });
        if (!empty($found_taxonomy)) {
            $this->found[$tmdb_id] = $woo_taxonomy->term_id;
        } else {
            foreach ($this->tmdb_taxonomies[$tmdb_languages->get_current_language()] as $tmdb_taxonomy) {
                $taxonomy_name = isset ($tmdb_taxonomy['name']) ? $tmdb_taxonomy['name'] : $tmdb_taxonomy;
                similar_text(GreekSlugGenerator::getSlug($taxonomy_name), GreekSlugGenerator::getSlug($woo_taxonomy->name), $percent);
                if ($percent > 80) {
                    if (isset($tmdb_taxonomy['id'])) {
                        $this->found[$tmdb_taxonomy['id']] = $woo_taxonomy->term_id;
                    } else {
                        array_push($this->found, $woo_taxonomy->name);
                    }
                }
            }
        }
    }

    public function get_not_found_buttons_html()
    {
        global $tmdb_languages;
        $this->not_found = array_filter($this->not_found, function ($element) {
            if (!isset($element['id'])) {
                return !in_array($element, $this->found);
            }
            return !array_key_exists($element['id'], $this->found);
        }); 
        $html = '<div class="add-taxonomy-buttons-container">';
        ob_start();
        foreach ($this->not_found as $id => $not_found) { ?>
            <button type="button" class="button button-primary add-taxonomy" data-current-language="<?php echo $tmdb_languages->get_current_language(); ?>" data-taxonomy="<?php echo $this->current_taxonomy; ?>" data-tmdb-id="<?php echo isset($not_found['id']) ? $not_found['id']: ''; ?>" data-tax-name="<?php echo  isset($not_found['name']) ? $not_found['name'] : $not_found; ?>"><?php echo isset($not_found['name']) ? __('Add') . ': ' . $not_found['name'] :  __('Add') . ': ' . $not_found; ?></button>
            <?php }
        $html .= ob_get_contents();
        ob_end_clean();

        $html .= '</div>';
        return $html;
    }
    public function set_tmdb_taxonomies ($tmdb_taxonomies, $woo_taxonomy_slug) {
        $this->current_taxonomy = $woo_taxonomy_slug;
        global $tmdb_languages;
        $this->found = [];
        $this->not_found = $tmdb_taxonomies[$tmdb_languages->get_current_language()];
        $this->tmdb_taxonomies =  $tmdb_taxonomies;
    }
}
