<div class="tmdb-movies-input-container wrap">
    <h1><?php echo __('Tmdb Movie Import Page', 'tmdb_int'); ?></h1>
   <table class="form-table" role="presentation">
       <tbody>
           <tr>
               <th scope="row"><?php echo __('Insert Movie Name', 'tmdb_int'); ?></th>
               <td>
                   <input id="tmdb-movie-input" style="min-width: 500px;" type="text">
                   <button type="button" class="button button-primary" id="fetch_movie"><?php echo __("Fetch Movie", "tmdb_int"); ?></button>
               </td>
           </tr>
       </tbody>
   </table>

   <input type="hidden" id="selected_movie_id">

   <h2 class="movie_details"><?php echo __("Movie Data", "tmdb_int"); ?></h2>

   <table id="movie_data_table" role="presentation">
       <tbody>
           <tr>
               <th scope="row"><?php echo __("Movie Poster", "tmdb_int"); ?></th>
               <td><img src="https://image.tmdb.org/t/p/w500//pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg" alt="" width="300"></td>
           </tr>
           <tr>
               <th scope="row"><?php echo __("SKU", "tmdb_int"); ?></th>
               <td>
                   <input style="min-width: 200px;" type="text" name="tmdb_sku" value="22052">
               </td>
           </tr>
           <tr>
               <th scope="row"><?php echo __("Genre", "tmdb_int"); ?></th>
               <td>
                   <input style="min-width: 200px;" type="text" name="tmdb_sku" value="22052">
               </td>
           </tr>
       </tbody>
   </table>
</div>  