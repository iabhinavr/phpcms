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

export { changePasswordFormOnSubmit };