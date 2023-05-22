function showEvalModal(skillId) {
    console.log(skillId);
    const evalModal = bootstrap.Modal.getOrCreateInstance("#addevalmodal");
    $("#skill_id").attr('value', skillId);
    evalModal.show();
}