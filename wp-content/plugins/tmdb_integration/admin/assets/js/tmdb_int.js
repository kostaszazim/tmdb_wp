(function ($) {
  $(window).on('load', () => {
    $('.tmdb-multi-select2').each(function () {
      $(this).select2({
        tags: true,
      });
      $(this).on('select2:select', (e) => {
        $('#chosen_taxonomy_override option').remove();
        let selectedItem = e.params.data;
        const wooId = selectedItem.id;
        const wooTax = $(e.target).closest('td').find('select').attr('name').replace("[]", "");
        let title = $('#modal-1-title').data("original-title");
        title = title.replace('%taxonomy%', selectedItem.text);
        $('#modal-1-title').html(title);
        const selectElements = [];
        const buttons = e.target.closest('td').querySelectorAll('.add-taxonomy-buttons-container button');
        _.forEach(buttons, (element) => {
          if ($(element).attr('data-is-hidden') !== 'true') {
            selectElements.push({
              title: $(element).attr('data-tax-name-'+ tmdb_languages.current_language),
              tmdb_id: $(element).attr('data-tmdb-id'),
            });
          }
        });
        const hasTmdbId = _.reduce(selectElements, (prev, element) => {
          return prev && !_.isEmpty(element.tmdb_id);
        }, true);
        if (!hasTmdbId) {
          return;
        }
        _.forEach(selectElements, (element) => {
          const newOption = new Option(element.title, element.id, false, false);
          $('#chosen_taxonomy_override').append(newOption).trigger('change');
        });
        if (selectElements.length > 0) {
          MicroModal.show('modal-1');
          $('.modal__btn.modal__btn-primary').on('click', function (e) {
            e.preventDefault();
            const nonce = $('[name="_tmdb_nonce"]').val();
            const selectedTmdbTax = $('#chosen_taxonomy_override').val();
            const selectedTmdbTaxId = _.filter(selectElements, (element) => {
              return element.title === selectedTmdbTax;
            }).pop();
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url: admin_ajax.ajax_url,
              data: {
                action: 'tmdb_add_taxonomy_term_tmdb_id',
                tmdb_tax_id: selectedTmdbTaxId.tmdb_id,
                woo_tax: wooTax,
                woo_id: wooId,
                nonce,
              },
              success: function (response) {
                if (response.success === true) {
                  $('[data-tmdb-id="'+ response.data.inserted_tmdb_id +'"]').remove();
                  MicroModal.close('modal-1');
                }
              },
            });
          })
        }
      });
      $(this).on("select2:unselect", (e) => {
        const nonce = $('[name="_tmdb_nonce"]').val();
        const selectedItem = e.params.data;
        const wooId = selectedItem.id;
        const wooTax = $(e.target).closest('td').find('select').attr('name').replace("[]", "");
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url: admin_ajax.ajax_url,
          data: {
            action: 'tmdb_delete_taxonomy_term_tmdb_id',
            woo_tax: wooTax,
            woo_id: wooId,
            nonce,
          },
          success: function (response) {
            console.log(response);
          },
        });
      })
    });
   $("input#submit").on("click", (e) => {
     const options = $('[selected="selected"]');
     const optionsValues = [];
     _.forEach(options, (option) => {
      if ($(option).data('tmdb-id') > 0) {
        let tax_name = $(option).closest('td').find('select').attr('name').replace("[]", "");
        let tmdb_id = $(option).data('tmdb-id');
        let woo_id = $(option).data('woo-id')
        optionsValues.push({
          tmdb_id,
          woo_id,
          tax_name
        })
      }
     })
     $('#tmdb-woo-ids').val(JSON.stringify(optionsValues))
   })
  });
})(jQuery);
