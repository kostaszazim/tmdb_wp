<?php

if (!defined('ABSPATH')) {
    exit();
}

class ImageUploader
{
    private array $images_paths;
    private $assoc_post;
    private $found_image_path = null;
    private int $inserted_image_id = 0;
    private $is_local;
    function __construct(string $path, $post_id, string $filename, bool $is_local = true, bool $is_featured = true)
    {
        $this->is_local = $is_local;
        if (is_null($post_id) || $post_id instanceof WP_Error || $filename === '' || !is_string($filename)) {
            return;
        }
        $this->assoc_post = (int) $post_id;
        if ($is_local) {

            $existing_image_id = $this->wp_get_attachment_by_file_name($filename);

           
            if (is_array($existing_image_id) && !empty($existing_image_id)) {
              $this->inserted_image_id = $existing_image_id[0]->ID;
              if ($is_featured) {
                set_post_thumbnail($this->assoc_post, $this->inserted_image_id);
              }
              return;
            }
            $this->images_paths = $this->scanAllDir($path);
            $this->filter_product_images($filename);
        } else {
            preg_match_all('/[^\/\\\\&\?]+\.\w{3,4}(?=([\?&].*$|$))/m', $filename,  $matches, PREG_SET_ORDER, 0 );
            if (!empty($matches)) {
                $existing_image_id = $this->wp_get_attachment_by_file_name($matches[0][0]);
                if (is_array($existing_image_id) && !empty($existing_image_id)) {
                    $this->inserted_image_id = $existing_image_id[0]->ID;
                    if ($is_featured) {
                      set_post_thumbnail($this->assoc_post, $this->inserted_image_id);
                    }
                    return;
                  }
            }
            $this->found_image_path = $filename;
        }

        if (!is_null($this->found_image_path)) {
            $this->make_post_upload($is_featured);
        }
    }

    private function wp_get_attachment_by_file_name($filename)
    {
        global $wpdb;

       $query = "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND guid LIKE '%${filename}%'";

        $result = $wpdb->get_results($query);
        return $result;
    }

    public function scanAllDir($dir)
    {
        $result = [];
        foreach (scandir($dir) as $filename) {
            if ($filename[0] === '.') {
                continue;
            }
            $filePath = $dir . '/' . $filename;
            if (is_dir($filePath)) {
                foreach ($this->scanAllDir($filePath) as $childFilename) {
                    $result[] = $filename . '/' . $childFilename;
                }
            } else {
                $result[] = $filename;
            }
        }
        return $result;
    }

    private function filter_product_images(string $filename)
    {
        $image_path = [];

        $image_path = array_filter($this->images_paths, function ($img_path) use ($filename) {
            return strpos($img_path, $filename) !== false;
        });
        $image_path = array_values($image_path);
        if (count($image_path) > 0) {
            $this->found_image_path = content_url('pr_images/' . $image_path[0]);
        }
    }

    private function make_post_upload(bool $is_featured = true)
    {
        $file_array = [];
        $tmp = download_url($this->found_image_path);
        $file_array['name'] = basename($this->found_image_path);
        $file_array['tmp_name'] = $tmp;

        if (is_wp_error($tmp)) {
            @unlink($file_array['tmp_name']);
            return $tmp;
        }

        $post_id = $this->assoc_post;
        $media_id = media_handle_sideload($file_array, $this->assoc_post);

        if (is_wp_error($media_id)) {
            @unlink($file_array['tmp_name']);
            return $media_id;
        }
        if ($is_featured) {
            set_post_thumbnail($this->assoc_post, $media_id);
        }

        $this->inserted_image_id = $media_id;
    }

    public function get_inserted_media_id()
    {
        return $this->inserted_image_id;
    }
}
