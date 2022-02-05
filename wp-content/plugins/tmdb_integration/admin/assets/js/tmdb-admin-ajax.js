(function ($) {
  $(window).on('load', () => {
    $(function () {
      const movieInput = $('#tmdb-movie-input');
      if (movieInput.length === 0 || movieInput.autocomplete === undefined) {
        return;
      }
      movieInput
        .autocomplete({
          minLength: 3,
          source: function (request, response) {
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url: admin_ajax.ajax_url,
              data: { action: 'fetch_tmdb_movie_info', request },
              success: function (data) {
                console.log(data);
                // const jsoned_resp = JSON.parse(response.data);
                response(data.data);
                //   window.location.reload();
              },
            });
          },
          focus: function (event, ui) {
            $('#tmdb-movie-input').val(ui.item.title);
            return false;
          },
          select: function (event, ui) {
            $('#selected_movie_id').val(ui.item.id);

            return false;
          },
        })
        .autocomplete('instance')._renderItem = function (ul, item) {
        return $('<li>')
          .append('<div><img class="poster_thumbnail" src="' + item.poster_path + '" height="50">' + item.title + ' (' + item.year + ')</div>')
          .appendTo(ul);
      };
    });
  });
})(jQuery);
