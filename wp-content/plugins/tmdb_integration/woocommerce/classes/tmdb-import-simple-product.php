<?php

if (!defined('ABSPATH')) {
    exit();
}

class TMDB_Import_Simple_Product extends TMDB_Import_Prototype
{
    protected function update_product_attributes()
    {
        $new_attributes = [];
        foreach ($this->product_attributes as $pa_name => $product_attribute) {
            if (isset($this->tmdb_movie_info[$pa_name])) {
                if ($product_attribute instanceof WC_Product_Attribute) {
                    $new_wc_attribute = new WC_Product_Attribute();
                    $new_wc_attribute->set_id($product_attribute->get_id());
                    $new_wc_attribute->set_name($product_attribute->get_name());
                    $new_wc_attribute->set_options(array_map(function ($element) {
                            return (int) $element;
                         }, $this->tmdb_movie_info[$pa_name]));
                    $new_wc_attribute->set_position($product_attribute->get_position());
                    $new_wc_attribute->set_visible($product_attribute->get_visible());
                    $new_wc_attribute->set_variation($product_attribute->get_variation());     
                }
                // $product_attribute->set_options(array_map(function ($element) {
                //     return (int) $element;
                // }, $this->tmdb_movie_info[$pa_name]));
            }

           $new_attributes[$pa_name] = $new_wc_attribute;
        }

        $this->created_product->set_attributes($new_attributes);
    }
}
