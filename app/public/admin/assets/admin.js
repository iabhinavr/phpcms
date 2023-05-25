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
        console.log(editorContent);
        editorElem.innerHTML = '';

        let decodedEditorContent = '';
        let parsedEditorContent = {};

        try {
            decodedEditorContent = atob(editorContent);
            parsedEditorContent = JSON5.parse(decodedEditorContent);
        }
        catch(e) {
            return {};
        }

        return parsedEditorContent;

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

    const deleteArticleButton = document.getElementById('delete-article-button');

    const deleteArticleButtonOnClick = async function (event) {
        
        const confirm = await swal("Are you sure", {
            dangerMode: true,
            buttons: true,
            closeOnClickOutside: false,
            closeOnEsc: false,
        });

        if(!confirm) {
            return;
        }

        const articleId = event.target.getAttribute('data-article-id');
        console.log('current target ' + event.currentTarget);

        const articleData = new FormData();

        articleData.append('id', parseInt(articleId));
        articleData.append('delete-article', 'submitted');

        const result = await fetch('/admin/edit-article.php', {
            method: 'POST',
            body: articleData,
        });

        const resJson = await result.json();

        if(resJson.status) {
            swal({
                title: "Success",
                text: resJson.result,
                icon: "success",
                timer: 1000,
            });

            setTimeout(function() {
                window.location.href = '/admin/articles.php'
            }, 1500);
        }
    }

    if(deleteArticleButton) {
        deleteArticleButton.addEventListener('click', deleteArticleButtonOnClick);
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

    let resultElem = null;

    const prevElem = document.getElementById('image-library-prev');
    const nextElem = document.getElementById('image-library-next');

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

        resultElem = document.querySelector('input[name="article-image"]');

        openImageLibraryModal('set-featured-image');
    
    }
    
    if(sidebarImageButton) {
        sidebarImageButton.addEventListener('click', sidebarImageButtonOnClick);
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

    if(prevElem) {
        prevElem.addEventListener('click', paginate);
    }

    if(nextElem) {
        nextElem.addEventListener('click', paginate);
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

    class InsertImage {
        static get toolbox() {
            return {
                title: 'Insert Image',
                icon: '<svg width="17" height="15" viewBox="0 0 336 276" xmlns="http://www.w3.org/2000/svg"><path d="M291 150V79c0-19-15-34-34-34H79c-19 0-34 15-34 34v42l67-44 81 72 56-29 42 30zm0 52l-43-30-56 30-81-67-66 39v23c0 19 15 34 34 34h178c17 0 31-13 34-29zM79 0h178c44 0 79 35 79 79v118c0 44-35 79-79 79H79c-44 0-79-35-79-79V79C0 35 35 0 79 0z"/></svg>'
            }
        }

        constructor({data}) {
            this.data = data;
        }

        render() {
            let img = document.createElement('img');
            img.classList.add('w-100');

            let title = document.createElement('input');
            title.setAttribute('type', 'text');
            title.classList.add('form-control', 'mt-2');
            title.setAttribute('placeholder', 'Enter caption...');
            title.value = this.data.caption ? this.data.caption : '';

            let btn = document.createElement('button');
            btn.classList.add('btn', 'btn-primary', 'my-2');
            btn.setAttribute('data-bs-toggle', 'modal');
            btn.setAttribute('data-bs-target', '#image-library-modal-fullscreen');
            btn.innerHTML = 'Choose Image';

            console.log(this.data.file);

            img.src = (this.data.file && this.data.file.url) ? this.data.file.url : '/admin/assets/images/default-image.jpg';

            btn.addEventListener('click', function(event) {
                event.preventDefault();
                console.log('select image button clicked');

                resultElem = img;

                openImageLibraryModal('insert-post-image');

                if(typeof openImageLibraryModal === "function") {
                    console.log('openImageLibraryModal exists');
                }
            });

            let wrapper = document.createElement('div');
            wrapper.appendChild(img);
            wrapper.appendChild(title);
            wrapper.appendChild(btn);

            
            return wrapper;
        }

        save(blockContent) {
            const src = blockContent.querySelector('img').src;
            const caption = blockContent.querySelector('input').value;
            return {
                
                file: {
                    url: src
                },
                caption: caption,
                withBorder: false,
                withBackground: false,
                stretched: false,
                
            }
        }
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
                },
                insertImage: {
                    class: InsertImage
                }
            },

            data: editorContent,
            autofocus: true,
        });
    }


    const changePasswordForm = document.getElementById('change-password-form');

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

    if(changePasswordForm) {
        changePasswordForm.addEventListener('submit', changePasswordFormOnSubmit);
    }

    /**
     * Site Setting Form
     */

    const siteSettingsForm = document.getElementById('site-settings-form');

    const siteSettingsFormOnSubmit = async function(event) {
        event.preventDefault();

        const siteTitle = document.getElementsByName('site-title')[0].value;
        const siteTagline = document.getElementsByName('site-tagline')[0].value;
        const thumbnailSize = document.getElementsByName('thumbnail-size')[0].value;

        const settingsData = new FormData();

        settingsData.append('site_title', siteTitle);
        settingsData.append('site_tagline', siteTagline);
        settingsData.append('thumbnail_size', thumbnailSize);
        settingsData.append('settings_save', 'submitted');

        const result = await fetch('/admin/settings.php', {
            method: 'POST',
            body: settingsData
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

    if(siteSettingsForm) {
        siteSettingsForm.addEventListener('submit', siteSettingsFormOnSubmit);
    }

    /**
     * Light and theme dark theme toggling
     */

    const html = document.getElementsByTagName('html')[0];
    const themeToggler = document.getElementById('theme-toggler');
    const svg = themeToggler.getElementsByTagName('svg')[0];
    const svgUse = themeToggler.getElementsByTagName('use')[0];

    const getTheme = async function() {
        const theme = await localStorage.getItem('theme');
        return theme;
    }

    const setTheme = async function(theme) {
        localStorage.setItem('theme', theme);
        let fillAttr = (theme === 'dark') ? 'white' : 'black';
        let svgUseHref = (theme === 'dark') ? '#moon-fill' : '#light-fill';

        html.setAttribute('data-bs-theme', theme);
        svg.setAttribute('fill', fillAttr);
        svgUse.setAttribute('href', svgUseHref);
    }

    const initTheme = async function() {
        const savedTheme = await getTheme();
        console.log(savedTheme);

        if(savedTheme) {
            await setTheme(savedTheme);
        }
        else {
            await setTheme('light');
        }

    }

    await initTheme();

    const themeTogglerOnClick = async function(event) {
        
        const currentTheme =  await getTheme();
        
        if(currentTheme === 'light') {
            await setTheme('dark');
        }
        else {
            await setTheme('light');
        }
    }
    
    if(themeToggler) {
        themeToggler.addEventListener('click', themeTogglerOnClick);
    }

})();