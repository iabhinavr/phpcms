import swal from "sweetalert";

// Handle image delete action
    
const deleteImage = async function (event) {

    event.preventDefault();

    const confirm = await swal("Are you sure you want to delete this image?", {
        dangerMode: true,
        buttons: true,
        closeOnClickOutside: false,
        closeOnEsc: false,
    });

    if(!confirm) {
        console.log(confirm);
        return;
    }

    const imageId = event.target.getAttribute('data-image-id');
    console.log('want to delete image with id ' + imageId);

    let imageData = new FormData();

    imageData.append('id', parseInt(imageId));
    imageData.append('image-delete', "submitted");

    const result = await fetch('/admin/edit-image.php', {
        method: 'POST',
        body: imageData
    });

    const resJson = await result.json();

    console.log(resJson);

    if(resJson.status) {
        window.location.href = '/admin/image-library.php';
    }
}

const saveImage = async function (event) {
    const imageId = document.querySelector('input[name="image-id"]').value;
    const imageTitle = document.querySelector('input[name="image-title"]').value;

    let imageData = new FormData();

    imageData.append('id', imageId);
    imageData.append('title', imageTitle);
    imageData.append('image-save', "submitted");

    const result = await fetch('/admin/edit-image.php', {
        method: 'POST',
        body: imageData,
    });

    const resJson = await result.json();

    if(resJson.status) {
        swal({
            title: "Success",
            text: resJson.result,
            icon: "success",
            timer: 1000,
        });
    }
    

    console.log(resJson);
}

export { deleteImage, saveImage };
