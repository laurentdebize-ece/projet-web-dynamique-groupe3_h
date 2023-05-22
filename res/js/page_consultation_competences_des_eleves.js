$(document).ready(function () {
    $('#scroll_1').click(function () {
        $('#consul_comp').show();
    });
    $('#bt_check').click(function(){
        $('#bt_x').css('background-color', 'rgb(229, 226, 224)');
        $('#bt_x').prop('disabled', true);
        $('text_check').show();
        $('#popup_check').show();
        setTimeout(function() {
          $('#popup_check').hide();
        }, 5000);
        $('.fermer').click(function () {
            $('#popup_check').hide();
        });
    })
    $('#bt_x').click(function(){
        $('#popup_x').show();
        $('.fermer').click(function () {
            $('#popup_x').hide();
        });
    })
});