<?php //Form Data Variables
if (!defined('ABSPATH')) {
    exit();
}
if (isset($_POST['submit']) && $_POST['submit'] === 'Import Movie') {
    echo '<pre>';
    print_r($_POST);    
    echo '</pre>';
}
$settings_db = new TMDB_Settings_Db();
$mapped_taxonomies = $settings_db->get_mapped_taxonomies();
$tmdb_movie_data = isset($_POST['tmdb_movie_data']) ? $_POST['tmdb_movie_data'] : false;
//$tmdb_movie_data = unserialize( file_get_contents(ABSPATH. '/fight_club.json'));
//file_put_contents(ABSPATH. '/fight_club.json', serialize( $tmdb_movie_data));

?>
<div class="tmdb-movies-input-container wrap">
    <h1><?php echo __('Tmdb Movie Import Page', 'tmdb_int'); ?></h1>
    <form method="POST">
   <table class="form-table" role="presentation">
       <tbody>
           <tr>
               <th scope="row"><?php echo __('Insert Movie Name', 'tmdb_int'); ?></th>
               <td>
                   <input id="tmdb-movie-input" style="min-width: 500px;" type="text">
                   <button type="submit" class="button button-primary" id="fetch_movie"><?php echo __('Fetch Movie', 'tmdb_int'); ?></button>
               </td>
           </tr>
       </tbody>
   </table>

   <input type="hidden" name="selected_movie_id" id="selected_movie_id">
    </form>
<?php if ($tmdb_movie_data instanceof TMDB_Movie): ?>  
<div class="movie_data-container">
   <h2 class="movie_details"><?php echo __('Movie Data', 'tmdb_int'); ?></h2>
<form id="tmdb_movie_data" method="POST">
   <table role="presentation">
       <tbody>
           <tr>
           <th scope="row"><?php echo __('Available Languages', 'tmdb_int'); ?></th>
           <?php  global $tmdb_languages; ?>
           <?php foreach ($tmdb_languages->get_supported_languages() as $language_code): ?>
           <td>
               <h3><?php echo strtoupper($language_code) ; ?></h3>
           </td>
           <?php endforeach; ?> 
           </tr>
           <tr>
           <th scope="row"><?php echo __('Movie Title', 'tmdb_int'); ?></th>
           <?php foreach ($tmdb_languages->get_supported_languages() as $language_code): ?>
           <td>
           <input style="min-width: 400px;" type="text" name="tmdb_movie_title_<?php echo $language_code; ?>" value="<?php echo $tmdb_movie_data->get_movie_title()[$language_code]; ?>">
           </td>
           <?php endforeach; ?>
           </tr>
           <tr>
               <th scope="row"><?php echo __('Movie Poster', 'tmdb_int'); ?></th>
               <td><img src="<?php echo $tmdb_movie_data->get_movie_poster(); ?>" alt="" width="300"></td>
               <input type="hidden" name="tmdb_poster_url" value="<?php echo $tmdb_movie_data->get_movie_poster(); ?>">
               <input type="hidden" name="tmdb_movie_id" value="<?php echo $tmdb_movie_data->get_tmdb_movie_id(); ?>">
           </tr>
           <tr>
           <th scope="row"><?php echo __('Movie Summary', 'tmdb_int'); ?></th>
           <?php foreach ($tmdb_languages->get_supported_languages() as $language_code): ?>
           <td>
           <textarea style="min-width: 500px;" type="text" name="tmdb_movie_summary_<?php echo $language_code; ?>" cols="40" rows="10"><?php echo $tmdb_movie_data->get_movie_summary()[$language_code]; ?></textarea>
           </td>
           <?php endforeach; ?>
           </tr>
           <tr>
               <th scope="row"><?php echo __('SKU', 'tmdb_int'); ?></th>
               <td>
                   <input style="min-width: 200px;" type="text" name="tmdb_sku" value="">
               </td>
           </tr>
           <?php if (array_key_exists('genre_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['genre_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['genre_woo_taxonomy'], 'hide_empty' => false]);
            $taxonomies_matching = new TMDB_Woo_Taxonomies_Matching();
            $taxonomies_matching->set_tmdb_taxonomies($tmdb_movie_data->get_genres(),  $mapped_taxonomies['genre_woo_taxonomy']);
            ?>
           <tr>
               <th scope="row"><?php echo __('Genre', 'tmdb_int'); ?></th>
               <td class="taxonomy_container">
                  <select class="tmdb-multi-select2" name="tmdb_genre[]" id="tmdb_genre" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <?php $taxonomies_matching->match_taxonomies($woo_term); ?>
                            <option <?php echo $taxonomies_matching->get_html($woo_term); ?> value="<?php echo $woo_term->term_id; ?>" data-woo-id="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
                  <?php echo $taxonomies_matching->get_not_found_buttons_html(); ?>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('actors_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['actors_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['actors_woo_taxonomy'], 'hide_empty' => false]);
            $taxonomies_matching->set_tmdb_taxonomies($tmdb_movie_data->get_actors(), $mapped_taxonomies['actors_woo_taxonomy']);
            ?>
           <tr>
               <th scope="row"><?php echo __('Actors', 'tmdb_int'); ?></th>
               <td class="taxonomy_container">
                  <select class="tmdb-multi-select2" name="tmdb_actors[]" id="tmdb_actors" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <?php $taxonomies_matching->match_taxonomies($woo_term); ?>
                            <option <?php echo $taxonomies_matching->get_html($woo_term); ?> value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
                  <?php echo $taxonomies_matching->get_not_found_buttons_html(); ?>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('production_year_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['production_year_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['production_year_woo_taxonomy'], 'hide_empty' => false]);
            $taxonomies_matching->set_tmdb_taxonomies($tmdb_movie_data->get_production_year(),  $mapped_taxonomies['production_year_woo_taxonomy']);
            ?>
           <tr>
               <th scope="row"><?php echo __('Production Year', 'tmdb_int'); ?></th>
               <td class="taxonomy_container">
                  <select class="tmdb-multi-select2" name="tmdb_prod_year" id="tmdb_prod_year" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <?php $taxonomies_matching->match_taxonomies($woo_term); ?>
                            <option <?php echo $taxonomies_matching->get_html($woo_term); ?> value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
                  <?php echo $taxonomies_matching->get_not_found_buttons_html(); ?>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('spoken_language_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['spoken_language_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['spoken_language_woo_taxonomy'], 'hide_empty' => false]);
            $taxonomies_matching->set_tmdb_taxonomies($tmdb_movie_data->get_spoken_languages(),  $mapped_taxonomies['spoken_language_woo_taxonomy']);
            ?>
           <tr>
               <th scope="row"><?php echo __('Spoken Languages', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_spoken_lang" id="tmdb_spoken_lang" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <?php $taxonomies_matching->match_taxonomies($woo_term); ?>
                            <option <?php echo $taxonomies_matching->get_html($woo_term); ?> value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
                  <?php echo $taxonomies_matching->get_not_found_buttons_html(); ?>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('release_date_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['release_date_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['release_date_woo_taxonomy'], 'hide_empty' => false]);
            $taxonomies_matching->set_tmdb_taxonomies($tmdb_movie_data->get_release_date(),  $mapped_taxonomies['release_date_woo_taxonomy']);
            ?>
           <tr>
               <th scope="row"><?php echo __('Release Date', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_release_date" id="tmdb_release_date" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <?php $taxonomies_matching->match_taxonomies($woo_term); ?>
                            <option <?php echo $taxonomies_matching->get_html($woo_term); ?> value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
                  <?php echo $taxonomies_matching->get_not_found_buttons_html(); ?>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('production_country_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['production_country_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['production_country_woo_taxonomy'], 'hide_empty' => false]);
            $taxonomies_matching->set_tmdb_taxonomies($tmdb_movie_data->get_production_countries(),  $mapped_taxonomies['production_country_woo_taxonomy']);
            ?>
           <tr>
               <th scope="row"><?php echo __('Production Country', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_production_country" id="tmdb_production_country" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <?php $taxonomies_matching->match_taxonomies($woo_term); ?>
                            <option <?php echo $taxonomies_matching->get_html($woo_term); ?> value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
                  <?php echo $taxonomies_matching->get_not_found_buttons_html(); ?>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('original_title_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['original_title_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['original_title_woo_taxonomy'], 'hide_empty' => false]);
            $taxonomies_matching->set_tmdb_taxonomies($tmdb_movie_data->get_original_title(),  $mapped_taxonomies['original_title_woo_taxonomy']);
            ?>
           <tr>
               <th scope="row"><?php echo __('Original Title', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_original_title" id="tmdb_original_title" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <?php $taxonomies_matching->match_taxonomies($woo_term); ?>
                            <option <?php echo $taxonomies_matching->get_html($woo_term); ?>  value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
                  <?php echo $taxonomies_matching->get_not_found_buttons_html(); ?>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('writer_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['writer_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['writer_woo_taxonomy'], 'hide_empty' => false]);
            $taxonomies_matching->set_tmdb_taxonomies($tmdb_movie_data->get_writers(),  $mapped_taxonomies['writer_woo_taxonomy']);
            ?>
           <tr>
               <th scope="row"><?php echo __('Writer', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_writer" id="tmdb_writer" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <?php $taxonomies_matching->match_taxonomies($woo_term); ?>
                            <option  <?php echo $taxonomies_matching->get_html($woo_term); ?> value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
                  <?php echo $taxonomies_matching->get_not_found_buttons_html(); ?>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('director_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['director_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['director_woo_taxonomy'], 'hide_empty' => false]);
            $taxonomies_matching->set_tmdb_taxonomies($tmdb_movie_data->get_directors(),  $mapped_taxonomies['director_woo_taxonomy']);
            ?>
           <tr>
               <th scope="row"><?php echo __('Director', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_director" id="tmdb_director" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <?php $taxonomies_matching->match_taxonomies($woo_term); ?>
                            <option <?php echo $taxonomies_matching->get_html($woo_term); ?> value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
                  <?php echo $taxonomies_matching->get_not_found_buttons_html(); ?>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('production_company_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['production_company_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['production_company_woo_taxonomy'], 'hide_empty' => false]);
            $taxonomies_matching->set_tmdb_taxonomies($tmdb_movie_data->get_production_companies(),  $mapped_taxonomies['production_company_woo_taxonomy']);
            ?>
           <tr>
               <th scope="row"><?php echo __('Production Company', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_production_company" id="tmdb_production_company" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <?php $taxonomies_matching->match_taxonomies($woo_term); ?>
                            <option <?php echo $taxonomies_matching->get_html($woo_term); ?> value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
                  <?php echo $taxonomies_matching->get_not_found_buttons_html(); ?>
               </td>
           </tr>
           <?php endif; ?>
       </tbody>
   </table>
   <?php wp_nonce_field('tmdb_import', '_tmdb_nonce'); ?>
   <?php submit_button(__('Import Movie', 'tmdb_int')); ?>
</form>
</div>
<?php endif; ?>
</div>  