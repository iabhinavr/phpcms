import EditorJS from "@editorjs/editorjs";
import Header from "@editorjs/header";
import List from "@editorjs/list";
import swal from 'sweetalert';

const editorElem = document.getElementById('editorjs');

if(editorElem) {
    const editor = new EditorJS({
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
        }
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
    const articleContent = await editor.save();
    

    let articleData = new FormData();

    articleData.append('id', articleId);
    articleData.append('title', articleTitle);
    articleData.append('published', publishedDate);
    articleData.append('modified', modifiedDate);
    articleData.append('image', articleImage);
    articleData.append('content', JSON.stringify(articleContent));
    articleData.append('article-edit-submit', "submitted");

    const result = await fetch('/admin/edit-article.php', {
        method: 'POST',
        body: articleData
    });

    const resJson = await result.json();

    console.log(resJson);
}

if(editArticleForm && saveArticleButton) {
    saveArticleButton.addEventListener('click', saveArticle);
}

// Handle image delete action

const deleteImageButton = document.getElementById('delete-image-button');

const deleteImage = async function (event) {
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
        });
    }
    

    console.log(resJson);
}

if(saveImageButton) {
    saveImageButton.addEventListener('click', saveImage);
}