(function($) {
    $(window).on('load', () => {
        $("#send_tmdb_config_ajax").on("click", function () {
            const buttonEl = $(this);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: admin_ajax.ajax_url,
                data: {action: "fetch_tmdb_configuration"},
                success: function (response) {
                   const jsoned_resp = JSON.parse(response.data);
                   console.log(jsoned_resp);
                //   window.location.reload();
                }
            })
        })
    })
})(jQuery)