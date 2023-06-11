import swal from "sweetalert";
import { editArticleForm } from "./elements";
import { editor } from "./editor";

// Handle article save action

const saveArticle = async function (event) {
    event.preventDefault();

    const articleId = editArticleForm.querySelector('input[name="article-id"]').value;
    const articleTitle = editArticleForm.querySelector('input[name="article-title"]').value;
    const publishedDate = editArticleForm.querySelector('input[name="published-date"]').value;
    const modifiedDate = editArticleForm.querySelector('input[name="modified-date"]').value;
    const articleImage = editArticleForm.querySelector('input[name="article-image"]').value;
    const articleSlug = document.querySelector('input[name="article-slug"]').value;
    const articleExcerpt = document.querySelector('textarea[name="article-excerpt"]').value;
    const csrfToken = document.querySelector('input[name="csrf-token"]').value;
    const articleContent = await editor.save();

    console.log(articleContent);
    console.log(JSON.stringify(articleContent));
    

    let articleData = new FormData();

    articleData.append('id', articleId);
    articleData.append('title', articleTitle);
    articleData.append('published', publishedDate);
    articleData.append('modified', modifiedDate);
    articleData.append('image', articleImage);
    articleData.append('slug', articleSlug);
    articleData.append('excerpt', articleExcerpt);
    articleData.append('content', window.btoa(JSON.stringify(articleContent)));
    articleData.append('csrf-token', csrfToken);
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
    else {
        swal({
            title: "Error",
            text: resJson.result,
            icon: "error",
        });
    }

    console.log(resJson);
}

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

export { saveArticle, deleteArticleButtonOnClick };