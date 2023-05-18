$(document).ready(function () {
    $('#bt_NA').click(function () {
        $('#bt_A').css('background-color', 'rgb(229, 226, 224)');
        $('#bt_ECA').css('background-color', 'rgb(229, 226, 224)');
        $('#bt_A').prop('disabled', true);
        $('#bt_ECA').prop('disabled', true);

        $('#popup').show();
        setTimeout(function() {
          $('#popup').hide();
        }, 5000);
        $('#popup').css('background-color', 'rgba(217, 61, 47, 0.15)')
        $('#text_NA').show();
        $('#text_ECA').hide();
        $('#text_A').hide();
        $('.fermer').click(function() {
            $('#popup').hide();
          });
    });
    $('#bt_A').click(function () {
        $('#bt_NA').css('background-color', 'rgb(229, 226, 224)');
        $('#bt_ECA').css('background-color', 'rgb(229, 226, 224)');
        $('#bt_NA').prop('disabled', true);
        $('#bt_ECA').prop('disabled', true);

        $('#popup').show();
        setTimeout(function() {
          $('#popup').hide();
        }, 5000);
        $('#popup').css('background-color', 'rgba(48, 209, 99, 0.15)')
        $('#text_NA').hide();
        $('#text_ECA').hide();
        $('#text_A').show();
        $('.fermer').click(function() {
            $('#popup').hide();
          });
    });

    $('#bt_ECA').click(function () {
        $('#bt_NA').css('background-color', 'rgb(229, 226, 224)');
        $('#bt_A').css('background-color', 'rgb(229, 226, 224)');
        $('#bt_NA').prop('disabled', true);
        $('#bt_A').prop('disabled', true);

        $('#popup').show();
        setTimeout(function() {
          $('#popup').hide();
        }, 5000);
        $('#popup').css('background-color', 'rgba(217, 112, 47, 0.15)')
        $('#text_NA').hide();
        $('#text_ECA').show();
        $('#text_A').hide();
        $('.fermer').click(function() {
            $('#popup').hide();
          });
    });
});
