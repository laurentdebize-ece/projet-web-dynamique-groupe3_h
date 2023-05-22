function showEvalModal(skillId, matId) {
    console.log(skillId);
    const evalModal = bootstrap.Modal.getOrCreateInstance("#addevalmodal");
    $("#skill_id").attr('value', skillId);
    $("#mat_id").attr('value', matId);
    evalModal.show();
}