$(document).ready(function () {
    $('.ajouter').click(function () {
        $('#popup_ajouter').show();
        $('.fermer').click(function () {
            $('#popup_ajouter').hide();
        });
    })
})