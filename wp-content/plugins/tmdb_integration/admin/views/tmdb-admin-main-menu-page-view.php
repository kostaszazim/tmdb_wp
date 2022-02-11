<?php //Form Data Variables
if (!defined('ABSPATH')) {
    exit();
}
$settings_db = new TMDB_Settings_Db();
$mapped_taxonomies = $settings_db->get_mapped_taxonomies();

print_r($mapped_taxonomies);
?>
<div class="tmdb-movies-input-container wrap">
    <h1><?php echo __('Tmdb Movie Import Page', 'tmdb_int'); ?></h1>
   <table class="form-table" role="presentation">
       <tbody>
           <tr>
               <th scope="row"><?php echo __('Insert Movie Name', 'tmdb_int'); ?></th>
               <td>
                   <input id="tmdb-movie-input" style="min-width: 500px;" type="text">
                   <button type="button" class="button button-primary" id="fetch_movie"><?php echo __('Fetch Movie', 'tmdb_int'); ?></button>
               </td>
           </tr>
       </tbody>
   </table>

   <input type="hidden" id="selected_movie_id">
<div class="movie_data-container" style="display: block;">
   <h2 class="movie_details"><?php echo __('Movie Data', 'tmdb_int'); ?></h2>

   <table id="movie_data_table" role="presentation">
       <tbody>
           <tr>
           <th scope="row"><?php echo __('Available Languages', 'tmdb_int'); ?></th>
           <td>
               <h3>GR</h3>
           </td>
           <td>
               <h3>EN</h3>
           </td>
           </tr>
           <tr>
           <th scope="row"><?php echo __('Movie Title', 'tmdb_int'); ?></th>
           <td>
           <input style="min-width: 400px;" type="text" name="tmdb_movie_title" value="Fight Club">
           </td>
           <td>
           <input style="min-width: 400px;" type="text" name="tmdb_movie_title" value="Fight Club">
           </td>
           </tr>
           <tr>
               <th scope="row"><?php echo __('Movie Poster', 'tmdb_int'); ?></th>
               <td><img src="https://image.tmdb.org/t/p/w500//pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg" alt="" width="300"></td>
           </tr>
           <tr>
               <th scope="row"><?php echo __('SKU', 'tmdb_int'); ?></th>
               <td>
                   <input style="min-width: 200px;" type="text" name="tmdb_sku" value="22052">
               </td>
           </tr>
           <?php if (array_key_exists('genre_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['genre_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['genre_woo_taxonomy'], 'hide_empty' => false]);
            ?>
           <tr>
               <th scope="row"><?php echo __('Genre', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_genre" id="tmdb_genre" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <option value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('actors_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['actors_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['actors_woo_taxonomy'], 'hide_empty' => false]);
            ?>
           <tr>
               <th scope="row"><?php echo __('Actors', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_actors" id="tmdb_actors" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <option value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('production_year_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['production_year_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['production_year_woo_taxonomy'], 'hide_empty' => false]);
            ?>
           <tr>
               <th scope="row"><?php echo __('Production Year', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_prod_year" id="tmdb_prod_year" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <option value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('spoken_language_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['spoken_language_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['spoken_language_woo_taxonomy'], 'hide_empty' => false]);
            ?>
           <tr>
               <th scope="row"><?php echo __('Spoken Language', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_spoken_lang" id="tmdb_spoken_lang" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <option value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('release_date_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['release_date_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['release_date_woo_taxonomy'], 'hide_empty' => false]);
            ?>
           <tr>
               <th scope="row"><?php echo __('Release Date', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_release_date" id="tmdb_release_date" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <option value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('production_country_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['production_country_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['production_country_woo_taxonomy'], 'hide_empty' => false]);
            ?>
           <tr>
               <th scope="row"><?php echo __('Production Country', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_production_country" id="tmdb_production_country" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <option value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('original_title_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['original_title_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['original_title_woo_taxonomy'], 'hide_empty' => false]);
            ?>
           <tr>
               <th scope="row"><?php echo __('Original Title', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_original_title" id="tmdb_original_title" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <option value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('writer_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['writer_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['writer_woo_taxonomy'], 'hide_empty' => false]);
            ?>
           <tr>
               <th scope="row"><?php echo __('Writer', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_writer" id="tmdb_writer" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <option value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
               </td>
           </tr>
           <?php endif; ?>
           <?php if (array_key_exists('director_woo_taxonomy', $mapped_taxonomies) && $mapped_taxonomies['director_woo_taxonomy'] !== "-"): 
            $woo_terms = get_terms(['taxonomy' =>  $mapped_taxonomies['director_woo_taxonomy'], 'hide_empty' => false]);
            ?>
           <tr>
               <th scope="row"><?php echo __('Director', 'tmdb_int'); ?></th>
               <td>
                  <select class="tmdb-multi-select2" name="tmdb_director" id="tmdb_director" style="min-width: 200px;" multiple="multiple">
                        <?php foreach ($woo_terms as $woo_term): ?>
                            <option value="<?php echo $woo_term->term_id; ?>"><?php echo $woo_term->name; ?></option>
                            <?php endforeach; ?>
                  </select>
               </td>
           </tr>
           <?php endif; ?>
       </tbody>
   </table>
</div>
</div>  