const editorElem = document.getElementById('editorjs');
const editArticleForm = document.getElementById('edit-article-form');
const saveArticleButton = document.getElementById('save-article-button');
const deleteArticleButton = document.getElementById('delete-article-button');
const deleteImageButton = document.getElementById('delete-image-button');
const saveImageButton = document.getElementById('save-image-button');
const sidebarImageButton = document.getElementById('select-featured-image');
const imageLibrary = document.getElementById('image-library');
const imagesUl = document.getElementById('modal-image-list');
const setSelectedImageButton = document.getElementById('set-selected-image');
const prevElem = document.getElementById('image-library-prev');
const nextElem = document.getElementById('image-library-next');
const closeImageLibraryButton = document.getElementById('image-library-close');
const changePasswordForm = document.getElementById('change-password-form');
const siteSettingsForm = document.getElementById('site-settings-form');
const html = document.getElementsByTagName('html')[0];
const themeToggler = document.getElementById('theme-toggler');
const svg = themeToggler.getElementsByTagName('svg')[0];
const svgUse = themeToggler.getElementsByTagName('use')[0];
const deleteUserForm = document.getElementById('delete-user-form');

let resultElem = null;

const getResultElem = async function() {
    return resultElem;
}

const setResultElem = async function(elem) {
    resultElem = elem;
}

export {
    editorElem,
    editArticleForm,
    saveArticleButton,
    deleteArticleButton,
    deleteImageButton,
    saveImageButton,
    sidebarImageButton,
    resultElem,
    getResultElem,
    setResultElem,
    imageLibrary,
    imagesUl,
    setSelectedImageButton,
    prevElem,
    nextElem,
    closeImageLibraryButton,
    changePasswordForm,
    siteSettingsForm,
    html,
    themeToggler,
    svg,
    svgUse,
    deleteUserForm,
  };