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

export { siteSettingsFormOnSubmit };