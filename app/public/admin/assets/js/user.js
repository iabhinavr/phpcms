import swal from "sweetalert";

const changePasswordFormOnSubmit = async function (event) {
    event.preventDefault();

    const userId = document.querySelector('input[name="user-id"]').value;
    const existingPassword = document.querySelector('input[name="existing-password"]').value;
    const newPassword = document.querySelector('input[name="new-password"]').value;
    const retypePassword = document.querySelector('input[name="retype-password"').value;


    const userData = new FormData();
    userData.append('id', userId);
    userData.append('existing', existingPassword);
    userData.append('new', newPassword);
    userData.append('retype', retypePassword);
    userData.append('password-change-submit', 'submitted');

    const result = await fetch('/admin/edit-user.php', {
        method: 'POST',
        body: userData,
    });

    const resJson = await result.json();

    if(resJson.status) {
        swal({
            title: "Success",
            text: resJson.result,
            icon: "success",
        });
    }
    else {
        swal({
            title: "Check Again!",
            text: resJson.result,
            icon: "error",
        });
    }
    console.log(resJson);
}

const deleteUserFormOnSubmit = async function(event) {
    event.preventDefault();
    const confirm = await swal("Are you sure you want to delete this user?", {
        dangerMode: true,
        buttons: true,
        closeOnClickOutside: false,
        closeOnEsc: false,
    });

    if(!confirm) {
        console.log(confirm);
        return;
    }

    const userId = document.querySelector('input[name="user-id"]').value;
    console.log('want to delete image with id ' + userId);

    let userData = new FormData();

    userData.append('id', parseInt(userId));
    userData.append('user-delete', "submitted");

    const result = await fetch('/admin/edit-user.php', {
        method: 'POST',
        body: userData
    });

    const resJson = await result.json();

    console.log(resJson);

    if(resJson.status) {
        window.location.href = '/admin/users.php';
    }
    else {
        swal({
            title: "Error",
            text: resJson.result,
            icon: "error",
        });
    }
}

export { changePasswordFormOnSubmit, deleteUserFormOnSubmit };