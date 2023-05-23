$(document).ready(function () {
    $('.filtres').click(function () {
        $('#popup').show();
        $('.fermer').click(function () {
            $('#popup').hide();
        });
    })
    $('.ajouter').click(function () {
        $('#popup_ajouter').show();
        $('.fermer').click(function () {
            $('#popup_ajouter').hide();
        });
    })
})