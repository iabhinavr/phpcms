import {  
    editArticleForm, 
    saveArticleButton,
    deleteArticleButton,
    deleteImageButton,
    saveImageButton,
    sidebarImageButton,
    prevElem,
    nextElem,
    closeImageLibraryButton,
    setSelectedImageButton,
    changePasswordForm,
    siteSettingsForm,
    themeToggler
} from "./elements";

import { deleteImage, saveImage } from "./image";
import { saveArticle, deleteArticleButtonOnClick } from "./article";
import { sidebarImageButtonOnClick, paginate, setSelectedImageButtonOnClick, closeImageLibraryOnClick } from "./image-library";
import { changePasswordFormOnSubmit } from "./user";
import { siteSettingsFormOnSubmit } from "./settings";
import { themeTogglerOnClick, initTheme } from "./darkmode";

(async function() {

    if(editArticleForm && saveArticleButton) {
        saveArticleButton.addEventListener('click', saveArticle);
    }
    
    if(deleteArticleButton) {
        deleteArticleButton.addEventListener('click', deleteArticleButtonOnClick);
    }
    
    if(deleteImageButton) {
        deleteImageButton.addEventListener('click', deleteImage);
    }
    
    if(saveImageButton) {
        saveImageButton.addEventListener('click', saveImage);
    }
    
    if(sidebarImageButton) {
        sidebarImageButton.addEventListener('click', sidebarImageButtonOnClick);
    }
    
    if(prevElem) {
        prevElem.addEventListener('click', paginate);
    }
    
    if(nextElem) {
        nextElem.addEventListener('click', paginate);
    }
    
    if(setSelectedImageButton) {
        setSelectedImageButton.addEventListener('click', setSelectedImageButtonOnClick);
    }
    
    if(closeImageLibraryButton) {
        closeImageLibraryButton.addEventListener('click', closeImageLibraryOnClick);
    }
    
    if(changePasswordForm) {
        changePasswordForm.addEventListener('submit', changePasswordFormOnSubmit);
    }
    
    if(siteSettingsForm) {
        siteSettingsForm.addEventListener('submit', siteSettingsFormOnSubmit);
    }

    await initTheme();
    
    if(themeToggler) {
        themeToggler.addEventListener('click', themeTogglerOnClick);
    }
})();

