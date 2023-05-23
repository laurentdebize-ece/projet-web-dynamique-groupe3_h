$(document).ready(function () {
    $('.filtres').click(function () {
        $('#popup_filtres').show();
        $('.fermer').click(function () {
            $('#popup_filtres').hide();
        });
    })
    $('.ajouter').click(function () {
        $('#popup_ajouter').show();
        $('.fermer').click(function () {
            $('#popup_ajouter').hide();
        });
    })
})