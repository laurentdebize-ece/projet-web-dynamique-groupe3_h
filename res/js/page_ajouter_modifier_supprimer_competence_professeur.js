$(document).ready(function () {
     $('.bt_modifier').click(function () {
        $('#popup_ajouter').show();
        $('.fermer').click(function() {
            $('#popup_ajouter').hide();
          });
    }); 
    $('#bt_ajouter').click(function () {
        $('#popup_ajouter').show();
        $('.fermer').click(function() {
            $('#popup_ajouter').hide();
          });
    });
});