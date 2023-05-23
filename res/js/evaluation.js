
function showValModal(evalId, uEval) {
    // console.log(skillId);
    console.log(uEval);
    $("#id").attr('value', evalId);
    const valModal = bootstrap.Modal.getOrCreateInstance("#validatemodal");
    switch (uEval) {
        case 1:
            $("#a").prop('checked', true);
            $("#eca").prop('checked', false);
            $("#na").prop('checked', false);
            break;
        case 2:
            $("#a").prop('checked', false);
            $("#eca").prop('checked', true);
            $("#na").prop('checked', false);
            break;
        case 3:
            $("#a").prop('checked', false);
            $("#eca").prop('checked', false);
            $("#na").prop('checked', true);
            break;
        
        default:
            $("#a").prop('checked', true);
            $("#eca").prop('checked', false);
            $("#na").prop('checked', false);
            break;
    }
    // $("#skill_id").attr('value', skillId);
    // $("#mat_id").attr('value', matId);
    valModal.show();
}