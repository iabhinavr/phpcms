// Handle image library modal

import {
    setSelectedImageButton,
    sidebarImageButton,
    imageLibrary, 
    imagesUl, 
    prevElem, 
    nextElem 
} from "./elements";

let resultElem = null;

const getResultElem = async function() {
    return resultElem;
}

const setResultElem = async function(elem) {
    resultElem = elem;
}

const openImageLibraryModal = async function (action) {
    console.log('open image library modal');

    imagesUl.innerHTML = '';

    setSelectedImageButton.setAttribute('data-action', action);

    const fetchArgs = new FormData();
    fetchArgs.append('fetch-images', "submitted");
    fetchArgs.append('per_page', 6);
    fetchArgs.append('page_no', 1);

    const fetchImages = await fetch('/admin/image-library.php', {
        method: 'POST',
        body: fetchArgs,
    });

    const fetchJson = await fetchImages.json();

    if(fetchJson.status === true) {
        let images = fetchJson.result;

        await insertImages(images);
        calculatePagination(6,1);
        
    }
}

const imageOnClick = async function (event) {

    event.currentTarget.classList.toggle('selected');
    let siblings = event.currentTarget.parentNode.childNodes;

    console.log(siblings);

    siblings.forEach((sibling) => {
        if(!(sibling === event.currentTarget)) {
            sibling.classList.remove('selected');
        }
    });

    if(event.currentTarget.classList.contains('selected')) {
        const imageId = event.currentTarget.getAttribute('data-image-id');
        const imagePath = event.currentTarget.getAttribute('data-image-path');

        setSelectedImageButton.setAttribute('data-image-id', imageId);
        setSelectedImageButton.setAttribute('data-thumbnail-src', '../uploads/thumbnails/' + imagePath);
        setSelectedImageButton.setAttribute('data-full-src', '../uploads/fullsize/' + imagePath);
    }
    else {
        setSelectedImageButton.setAttribute('data-image-id', '');
        setSelectedImageButton.setAttribute('data-thumbnail-src', '');
        setSelectedImageButton.setAttribute('data-full-src', '');
    }

    
}

const setImageSelectEvent = async function(item) {
    item.removeEventListener('click', imageOnClick);
    item.addEventListener('click', imageOnClick);
}

const insertImages = async function (images) {
    images.map((image) => {
        let item = document.createElement('li');
        let itemLink = document.createElement('a');
        let itemImg = document.createElement('img');

        item.setAttribute('data-image-id', image.id);
        item.setAttribute('data-image-path', image.folder_path + '/' + image.file_name);
        item.setAttribute('class', 'item col');

        itemLink.setAttribute('href', '#');
        itemLink.setAttribute('class', 'd-block position-relative w-100')

        itemImg.setAttribute('src', '../uploads/thumbnails/' + image.folder_path + '/' + image.file_name);
        itemImg.setAttribute('alt', image.title);
        itemImg.setAttribute('class', 'w-100 h-100 object-fit-cover object-center')
        
        itemLink.appendChild(itemImg);
        item.appendChild(itemLink);

        setImageSelectEvent(item);

        console.log(item);

        imagesUl.appendChild(item);
    });
}

const sidebarImageButtonOnClick = async function (event) {

    event.preventDefault();

    setResultElem(document.querySelector('input[name="article-image"]'));

    openImageLibraryModal('set-featured-image');

}

const calculatePagination = async function (per_page, page_no) {

    per_page = parseInt(per_page);
    page_no = parseInt(page_no);

    console.log('page_no' + page_no);

    const args = new FormData();

    args.append('get_image_count', 'submitted');
    args.append('per_page', per_page);
    args.append('page_no', page_no);

    const getTotal = await fetch('/admin/image-library.php', {
        method: 'POST',
        body: args,
    });

    const resJson = await getTotal.json();
    let count = resJson.image_count;

    let totalPages = 
    (count % per_page) === 0 ? 
    Math.floor(count / per_page) : 
    Math.floor(count / per_page) + 1;

    console.log(totalPages);

    let prev = false;
    let next = false;

    if(page_no > 1) {
        prev = true;
    }

    if(page_no < totalPages) {
        next = true;
    }    

    if(prev) {
        prevElem.classList.remove('disabled');
        prevElem.setAttribute('data-page-no', page_no - 1);
    }
    else {
        prevElem.classList.add('disabled');
        prevElem.setAttribute('data-page-no', '');
    }

    if(next) {
        nextElem.classList.remove('disabled');
        nextElem.setAttribute('data-page-no', page_no + 1);
    }
    else {
        nextElem.classList.add('disabled');
        nextElem.setAttribute('data-page-no', '');
    }

}

const paginate = async function (event) {
    let page_no = event.currentTarget.getAttribute('data-page-no');
    console.log(page_no);

    imagesUl.innerHTML = '';

    const fetchArgs = new FormData();
    fetchArgs.append('fetch-images', "submitted");
    fetchArgs.append('per_page', 6);
    fetchArgs.append('page_no', page_no);

    const fetchImages = await fetch('/admin/image-library.php', {
        method: 'POST',
        body: fetchArgs,
    });

    const fetchJson = await fetchImages.json();

    if(fetchJson.status === true) {
        let images = fetchJson.result;
        console.log(images);

        await insertImages(images);
        calculatePagination(6,page_no);
        
    }
}

const setSelectedImageButtonOnClick = async function (event) {
    const buttonAction = event.currentTarget.getAttribute('data-action');
    const imageId = event.currentTarget.getAttribute('data-image-id');
    const thumbnailSrc = event.currentTarget.getAttribute('data-thumbnail-src');
    const fullSrc = event.currentTarget.getAttribute('data-full-src');

    console.log(resultElem);

    switch(buttonAction) {
        case 'set-featured-image' :
            const inputField = document.querySelector('input[name="article-image"]');
            inputField.value = imageId;
            if(thumbnailSrc && fullSrc) {
                sidebarImageButton.style.backgroundImage = 'url(' + thumbnailSrc + ')';
            }
            break;
        case 'insert-post-image' :
            resultElem.src = fullSrc;
            break;
        default:
            console.log('nothing happened');

    }

    imageLibrary.classList.remove('fixed');
    imageLibrary.classList.add('hidden');
}

const closeImageLibraryOnClick = async function (event) {
    console.log('close image library');

    imageLibrary.classList.remove('fixed');
    imageLibrary.classList.add('hidden');
}

export { 
    openImageLibraryModal,
    imageOnClick,
    sidebarImageButtonOnClick,
    paginate,
    setSelectedImageButtonOnClick,
    closeImageLibraryOnClick,
    resultElem,
    getResultElem,
    setResultElem,
}