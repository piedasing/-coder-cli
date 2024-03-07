tinymce.init({
    selector: '.tiny-editor',
    height: 450,
    language: 'zh_TW',
    menubar: false,
    plugins:
        'anchor autolink autosave charmap codesample directionality emoticons help image insertdatetime link lists media nonbreaking pagebreak searchreplace table visualblocks visualchars wordcount code fullscreen',
    toolbar:
        'undo redo spellcheckdialog  | blocks fontfamily fontsizeinput | bold italic underline forecolor backcolor | link image code fullscreen | align lineheight checklist bullist numlist | indent outdent | removeformat typography',
    toolbar_mode: 'sliding',
    //
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
    convert_urls: false,
    remove_script_host: true,
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    image_caption: true,
    image_prepend_url: false,
    imagetools_toolbar: 'rotateleft rotateright | flipv fliph | editimage imageoptions',
    images_upload_handler: (blobInfo, progress) => {
        return new Promise((resolve, reject) => {
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '../comm/tiny_editor_upload.php');

            xhr.onload = function () {
                var json;

                if (xhr.status !== 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                json = JSON.parse(xhr.responseText);

                if (!json || typeof json.file_path != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                resolve(json.file_path);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        });
    },
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    },
});
