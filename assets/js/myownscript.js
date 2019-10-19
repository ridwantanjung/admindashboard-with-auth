$('.custom-file-input').on('change', function () {
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
});


$('.form-check-input').on('click', function () {
    const menuId = $(this).data('menu');
    const roleId = $(this).data('role');

    $.ajax({
        type: "post",
        url: "http://localhost/myweb-login/admin/changeaccess",
        data: {
            menuId: menuId,
            roleId: roleId
        },
        success: function () {
            Swal.fire({
                type: 'success',
                title: 'Access Changed!',
                showConfirmButton: false,
                timer: 1500
            }).then(function () {
                document.location.href = "http://localhost/myweb-login/admin/roleaccess/" + roleId;
            })
        }
    });
});