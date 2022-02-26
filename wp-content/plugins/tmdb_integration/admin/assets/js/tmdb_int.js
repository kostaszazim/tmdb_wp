(function ($) {
  $(window).on('load', () => {
    $('.tmdb-multi-select2').each(function () {
      $(this).select2({
        tags: true,
      });
      $(this).on('select2:selecting', (e) => {
        $(this).attr('data-preselected', $(this).val().join(","));
      })
      $(this).on('change', (e) => {
        console.log($(this).data('preselected'));
        console.log($(this).val());
        MicroModal.show('modal-1');
      })
    });
  });
})(jQuery);
