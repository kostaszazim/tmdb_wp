<div class="tmdb-movies-input-container wrap">
    <h1><?php echo __('Tmdb Movie Import Page', 'tmdb_int'); ?></h1>
   <table class="form-table" role="presentation">
       <tbody>
           <tr>
               <th scope="row">Insert Movie Name</th>
               <td>
                   <input id="tmdb-movie-input" style="min-width: 500px;" type="text">
               </td>
           </tr>
       </tbody>
   </table>

   <input type="hidden" id="selected_movie_id">
</div>  