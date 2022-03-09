<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Import_Simple_Variable_Product extends TMDB_Import_Prototype
{
    private $last_position;
    protected function update_product_attributes()
    {
        $new_attributes = [];
        foreach ($this->product_attributes as $pa_name => $product_attribute) {
            if (isset($this->tmdb_movie_info[$pa_name])) {
                if ($product_attribute instanceof WC_Product_Attribute) {
                    $new_wc_attribute = new WC_Product_Attribute();
                    $new_wc_attribute->set_id($product_attribute->get_id());
                    $new_wc_attribute->set_name($product_attribute->get_name());
                    $new_wc_attribute->set_options(
                        array_map(function ($element) use ($product_attribute) {
                            return (int) apply_filters('wpml_object_id', $element, $product_attribute->get_taxonomy(), false, $this->language_code );
                        }, $this->tmdb_movie_info[$pa_name]),
                    );
                    $new_wc_attribute->set_position($product_attribute->get_position());
                    $new_wc_attribute->set_visible($product_attribute->get_visible());
                    $new_wc_attribute->set_variation($product_attribute->get_variation());
                }
            } else {
                $new_wc_attribute = $product_attribute;
            }

            $new_attributes[$pa_name] = $new_wc_attribute;
            $this->last_position = $new_wc_attribute->get_position();
        }

        $existing_attribute_keys = array_keys($this->product_attributes);
        $missing_attributes = array_filter(
            $this->tmdb_movie_info,
            function ($element, $key) use ($existing_attribute_keys) {
                return !in_array($key, $existing_attribute_keys) && strpos($key, 'pa_') !== false;
            },
            ARRAY_FILTER_USE_BOTH,
        );

        if (!empty($missing_attributes)) {
            foreach ($missing_attributes as $tax_name => $attributes) {
                $this->last_position++;
                $attribute_obj = new WC_Product_Attribute();
                $attribute_obj->set_id(wc_attribute_taxonomy_id_by_name($tax_name));
                $attribute_obj->set_name($tax_name);
                $attribute_obj->set_options(
                    array_map(function ($element) {
                        return (int) $element;
                    }, $attributes),
                );
                $attribute_obj->set_position($this->last_position);
                $attribute_obj->set_variation(false);
                $attribute_obj->set_visible(true);
                $new_attributes[$tax_name] = $attribute_obj;
            }
        }

        $this->created_product->set_attributes($new_attributes);
    }
}
