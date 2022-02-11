(function($) {
    $(window).on('load', () => {
       $('.tmdb-multi-select2').each(function () {
        $(this).select2({
            tags: true
           });
       })
    })
})(jQuery)