function showUserDeletePopup(uid) {
    alert("Delete user with id: " + uid);
}

function showUserEditPopup(uid) {
    const editUserModal = bootstrap.Modal.getOrCreateInstance("#editusermodal");
    let editUserId = document.getElementById("edited_student_id");
    editUserId.value = uid;
    editUserModal.show();
}


$(document).ready(function () {

    const addUserModal = bootstrap.Modal.getOrCreateInstance("#addusermodal");

    const createAdminCollapse = bootstrap.Collapse.getOrCreateInstance("#createadmin", {
        show: false
    });
    const createEleveCollapse = bootstrap.Collapse.getOrCreateInstance("#createeleve", {
        show: false
    });
    const createProfCollapse = bootstrap.Collapse.getOrCreateInstance("#createprof", {
        show: false
    });


    $("#adduserbtn").click(function () {
        addUserModal.show();
        createEleveCollapse.hide();
        createAdminCollapse.hide();
        createProfCollapse.hide();
    });


    $("#createadminbtn").click(function () {
        $("#createadminbtn").addClass("active");
        $("#createelevebtn").removeClass("active");
        $("#createprofbtn").removeClass("active");
        createAdminCollapse.show();
        createEleveCollapse.hide();
        createProfCollapse.hide();
    });

    $("#createelevebtn").click(function () {
        $("#createelevebtn").addClass("active");
        $("#createadminbtn").removeClass("active");
        $("#createprofbtn").removeClass("active");
        createAdminCollapse.hide();
        createEleveCollapse.show();
        createProfCollapse.hide();
    });

    $("#createprofbtn").click(function () {
        $("#createprofbtn").addClass("active");
        $("#createadminbtn").removeClass("active");
        $("#createelevebtn").removeClass("active");
        createAdminCollapse.hide();
        createEleveCollapse.hide();
        createProfCollapse.show();
    });

});