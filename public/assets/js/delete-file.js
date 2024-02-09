/**
 * Account Settings - Account
 */

'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
    (function () {

        // Update/reset user image of account page
        let file = document.getElementById('uploadedFile');
        const fileInput = document.querySelector('.upload-file-input'),
            resetFileInput = document.querySelector('.file-reset');

        if (file) {

            const resetFile = file.src;
            fileInput.onchange = () => {

                if (fileInput.files[0]) {
                  // document.getElementById('videoF').src=window.URL.createObjectURL(fileInput.files[0])
                    file.src = window.URL.createObjectURL(fileInput.files[0]);
                }
            };
            resetFileInput.onclick = () => {
                fileInput.value = '';
                file.src = resetFile;
            };
        }
    })();
});
