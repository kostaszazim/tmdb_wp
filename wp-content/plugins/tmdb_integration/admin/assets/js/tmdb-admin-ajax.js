(function ($) {
  //Function to search for movie
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
            $('#tmdb-movie-input').val(ui.item.title + ' (' + ui.item.year + ')');
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

(function ($) {
  //Search for woo product to use as prototype
  $(window).on('load', () => {
    $(function () {
      const moviePrototypeInput = $('#movie_prototype');
      if (moviePrototypeInput.length === 0 || moviePrototypeInput.autocomplete === undefined) {
        return;
      }
      moviePrototypeInput
        .autocomplete({
          minLength: 3,
          source: function (request, response) {
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url: admin_ajax.ajax_url,
              data: { action: 'fetch_local_woo_product', request },
              success: function (data) {
                // const jsoned_resp = JSON.parse(response.data);
                response(data.data);
                //   window.location.reload();
              },
            });
          },
          focus: function (event, ui) {
            $('#movie_prototype').val(ui.item.title);
            return false;
          },
          select: function (event, ui) {
            $('#selected_movie_prototype_id').val(ui.item.id);

            return false;
          },
        })
        .autocomplete('instance')._renderItem = function (ul, item) {
        return $('<li>')
          .append('<div>' + item.title + '</div>')
          .appendTo(ul);
      };
    });
  });
})(jQuery);

//Ajax Taxonomy Input

(function ($) {
  $(window).on('load', () => {
    $('.add-taxonomy-buttons-container button.add-taxonomy').each(function () {
      $(this).on('click', (e) => {
        const tax_data = $(e.target).data();
        const nonce = $('[name="_tmdb_nonce"]').val();
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url: admin_ajax.ajax_url,
          data: {
            action: 'tmdb_add_taxonomy_term',
            tax_data,
            nonce,
          },
          success: function (response) {
            console.log(response);
            if (response.status === 'ok') {
              const option = new Option(response.term.name, response.term.term_id, false, true);
              $(e.target).closest('td').find('select').append(option);
              $(e.target).animate({ opacity: 0 });
            }
          },
        });
      });
    });
    $('#tmdb_options').on('submit', function (e) {
      const prototypeProduct = $('#movie_prototype').val();
      if (prototypeProduct === '') {
        $('#selected_movie_prototype_id').val(prototypeProduct);
      }
    });
  });
})(jQuery);

//Clear Duplicate Buttons
(function ($) {
  $(window).on('load', () => {
    const existing_ids = [];
    $('.add-taxonomy-buttons-container button.add-taxonomy').each(function () {
        if (existing_ids.includes($(this).data('tmdb-id')) && $(this).data('tmdb-id') !== "" ) {
          $(this).remove();
        } else {
          existing_ids.push($(this).data('tmdb-id'));
        }
    });
    $('#tmdb_options').on('submit', function (e) {
      const prototypeProduct = $('#movie_prototype').val();
      if (prototypeProduct === '') {
        $('#selected_movie_prototype_id').val(prototypeProduct);
      }
    });
  });
})(jQuery);
