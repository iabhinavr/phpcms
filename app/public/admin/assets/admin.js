import EditorJS from "@editorjs/editorjs";
import Header from "@editorjs/header";
import List from "@editorjs/list";
import swal from 'sweetalert';
import JSON5 from 'json5';
import ImageTool from '@editorjs/image';

(async function(){
    const editorElem = document.getElementById('editorjs');
    let editor = null;

    const getEditorData = async function (editorElem) {
        let editorContent = editorElem.innerHTML;
        editorElem.innerHTML = '';

        return JSON5.parse(atob(editorContent));
    }
    
    if(editorElem) {
    
        let editorContent = await getEditorData(editorElem);
        console.log(editorContent);

        editor = new EditorJS({
            holder: 'editorjs',
        
            tools: {
                header: {
                    class: Header,
                    inlineToolbar: ['link']
                },
                list: {
                    class: List,
                    inlineToolbar: true
                },
                image: {
                    class: ImageTool,
                    config: {
                        endpoints: {
                            byFile: '/admin/image-library.php',
                        },
                        field: 'editor-image',
                    }
                }
            },

            data: editorContent,
        });
    }
    
    // Handle article save action
    
    const editArticleForm = document.getElementById('edit-article-form');
    const saveArticleButton = document.getElementById('save-article-button');
    
    const saveArticle = async function (event) {
        event.preventDefault();
    
        const articleId = editArticleForm.querySelector('input[name="article-id"]').value;
        const articleTitle = editArticleForm.querySelector('input[name="article-title"]').value;
        const publishedDate = editArticleForm.querySelector('input[name="published-date"]').value;
        const modifiedDate = editArticleForm.querySelector('input[name="modified-date"]').value;
        const articleImage = editArticleForm.querySelector('input[name="article-image"]').value;
        const articleSlug = document.querySelector('input[name="article-slug"]').value;
        const articleExcerpt = document.querySelector('textarea[name="article-excerpt"]').value;
        const articleContent = await editor.save();

        console.log(articleContent);
        console.log(JSON5.stringify(articleContent));
        
    
        let articleData = new FormData();
    
        articleData.append('id', articleId);
        articleData.append('title', articleTitle);
        articleData.append('published', publishedDate);
        articleData.append('modified', modifiedDate);
        articleData.append('image', articleImage);
        articleData.append('slug', articleSlug);
        articleData.append('excerpt', articleExcerpt);
        articleData.append('content', btoa(JSON.stringify(articleContent)));
        articleData.append('article-edit-submit', "submitted");
    
        const result = await fetch('/admin/edit-article.php', {
            method: 'POST',
            body: articleData
        });
    
        const resJson = await result.json();

        if(resJson.status) {
            swal({
                title: "Success",
                text: resJson.result,
                icon: "success",
                timer: 2000,
            });
        }
    
        console.log(resJson);
    }
    
    if(editArticleForm && saveArticleButton) {
        saveArticleButton.addEventListener('click', saveArticle);
    }
    
    // Handle image delete action
    
    const deleteImageButton = document.getElementById('delete-image-button');
    
    const deleteImage = async function (event) {
    
        const confirm = await swal("Are you sure", {
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
    
        imageData.append('id', imageId);
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
    
    if(deleteImageButton) {
        deleteImageButton.addEventListener('click', deleteImage);
    }
    
    // Handle image save action
    
    const saveImageButton = document.getElementById('save-image-button');
    
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
    
    if(saveImageButton) {
        saveImageButton.addEventListener('click', saveImage);
    }
    
    // Handle image library modal
    
    const sidebarImageButton = document.getElementById('select-featured-image');
    const imageLibrary = document.getElementById('image-library');
    const imagesUl = document.getElementById('modal-image-list');
    const setSelectedImageButton = document.getElementById('set-selected-image');

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
            item.setAttribute('class', 'item');
    
            itemLink.setAttribute('href', '#');

            itemImg.setAttribute('src', '../uploads/thumbnails/' + image.folder_path + '/' + image.file_name);
            itemImg.setAttribute('alt', image.title);
            
            itemLink.appendChild(itemImg);
            item.appendChild(itemLink);

            setImageSelectEvent(item);
    
            console.log(item);
    
            imagesUl.appendChild(item);
        });
    }
    
    const sidebarImageButtonOnClick = async function (event) {
        console.log('open image library');
    
        imagesUl.innerHTML = '';
    
        imageLibrary.classList.remove('hidden');
        imageLibrary.classList.add('fixed');

        setSelectedImageButton.setAttribute('data-action', 'set-featured-image');
    
        const fetchArgs = new FormData();
        fetchArgs.append('fetch-images', "submitted");
        fetchArgs.append('limit', 12);
        fetchArgs.append('offset', 0);
    
        const fetchImages = await fetch('/admin/image-library.php', {
            method: 'POST',
            body: fetchArgs,
        });
    
        const fetchJson = await fetchImages.json();
    
        if(fetchJson.status === true) {
            let images = fetchJson.result;
            console.log(images);
    
            await insertImages(images);
            
        }
    
    }
    
    if(sidebarImageButton) {
        sidebarImageButton.addEventListener('click', sidebarImageButtonOnClick);
    }

    const setSelectedImageButtonOnClick = async function (event) {
        const buttonAction = event.currentTarget.getAttribute('data-action');
        const imageId = event.currentTarget.getAttribute('data-image-id');
        const thumbnailSrc = event.currentTarget.getAttribute('data-thumbnail-src');
        const fullSrc = event.currentTarget.getAttribute('data-full-src');

        switch(buttonAction) {
            case 'set-featured-image' :
                const inputField = document.querySelector('input[name="article-image"]');
                inputField.value = imageId;
                if(thumbnailSrc && fullSrc) {
                    sidebarImageButton.style.backgroundImage = 'url(' + thumbnailSrc + ')';
                }
                break;
            default:
                console.log('nothing happened');

        }

        imageLibrary.classList.remove('fixed');
        imageLibrary.classList.add('hidden');
    }
    

    if(setSelectedImageButton) {
        setSelectedImageButton.addEventListener('click', setSelectedImageButtonOnClick);
    }
    const closeImageLibraryButton = document.getElementById('image-library-close');
    
    const closeImageLibraryOnClick = async function (event) {
        console.log('close image library');
    
        imageLibrary.classList.remove('fixed');
        imageLibrary.classList.add('hidden');
    }
    
    if(closeImageLibraryButton) {
        closeImageLibraryButton.addEventListener('click', closeImageLibraryOnClick);
    }

    const setSelectedImage = async function (event) {

    }

    if(setSelectedImageButton) {
        setSelectedImageButton.addEventListener('click', setSelectedImage);
    }
})();