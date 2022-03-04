(function ($) {
  $(window).on('load', () => {
    $('.tmdb-multi-select2').each(function () {
      $(this).select2({
        tags: true,
      });
      $(this).on('select2:select', (e) => {
        $('#chosen_taxonomy_override option').remove();
        let selectedItem = e.params.data;
        let title = $('#modal-1-title').data("original-title");
        title = title.replace('%taxonomy%', selectedItem.text);
        $('#modal-1-title').html(title);
        const selectElements = [];
        const buttons = e.target.closest('td').querySelectorAll('.add-taxonomy-buttons-container button');
        console.log(buttons);
        _.forEach(buttons, (element) => {
          selectElements.push({
            title: $(element).attr('data-tax-name'),
            tmdb_id: $(element).attr('data-tmdb-id'),
          });
        });
        _.forEach(selectElements, (element) => {
          const newOption = new Option(element.title, element.id, false, false);
          $('#chosen_taxonomy_override').append(newOption).trigger('change');
        });
        if (selectElements.length > 0) {
          MicroModal.show('modal-1');
        }
      });
    });
  });
})(jQuery);
