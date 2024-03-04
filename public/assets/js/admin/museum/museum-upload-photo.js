document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const fileInput = document.querySelector('.account-file-input');
    const fileInputGeneral = document.querySelector('.account-file-input-general');
   
    
    const uploadedImagesContainer = document.getElementById('uploadedImagesContainer');
    const uploadedImagesContainerGeneral = document.getElementById('uploadedImagesContainerGeneral');
    const uploadedImages = [];

    fileInput.onchange = () => {
      if (fileInput.files.length > 0) {
        if (fileInput.files.length > 3) {
          alert('Դուք կարող եք ավելացնել մինչև 3 նկար։');
          this.preventDefault();
        }

        uploadedImages.length = 0;

        for (let i = 0; i < Math.min(fileInput.files.length, 3); i++) {
          const imageData = {
            url: window.URL.createObjectURL(fileInput.files[i]),
            fileName: fileInput.files[i].name
          };

          uploadedImages.push(imageData);

          const imageContainer = document.createElement('div');
          imageContainer.className = 'uploaded-image-container-div mx-2';

          const image = document.createElement('img');
          image.src = imageData.url;
          image.alt = 'uploaded-image';
          image.className = 'd-block rounded uploaded-image uploaded-photo-project';

          imageContainer.appendChild(image);

          const removeButton = document.createElement('button');
          removeButton.type = 'button';
          removeButton.className = 'btn btn-outline-danger remove_file btn-sm mt-2';
          removeButton.id = fileInput.files[i].lastModified;
          removeButton.textContent = 'Ջնջել';

          imageContainer.appendChild(removeButton);

          uploadedImagesContainer.appendChild(imageContainer);
       
          if(document.getElementById('photos_div')!=null){

            document.getElementById('photos_div').innerHTML!=='' ? document.getElementById('photos_div').innerHTML='' : null

          }
        }
        document.querySelectorAll('.remove_file').forEach((btn) => btn.addEventListener('click', removeFile))

      }
    };

    fileInputGeneral.onchange = () => {
      if (fileInputGeneral.files.length > 0) {
        uploadedImages.length = 0;
          const imageData = {
            url: window.URL.createObjectURL(fileInputGeneral.files[0]),
            fileName: fileInputGeneral.files[0].name
          };

          uploadedImages.push(imageData);

          const imageContainer = document.createElement('div');
          imageContainer.className = 'uploaded-image-container-div mx-2';

          const image = document.createElement('img');
          image.src = imageData.url;
          image.alt = 'uploaded-image';
          image.className = 'd-block rounded uploaded-image uploaded-photo-project-general';

          imageContainer.appendChild(image);
          while (uploadedImagesContainerGeneral.firstChild) {
            uploadedImagesContainerGeneral.removeChild(uploadedImagesContainerGeneral.firstChild);
          }
          uploadedImagesContainerGeneral.appendChild(imageContainer);
       
          if(document.getElementById('photos_div')!=null){
            document.getElementById('photos_div').innerHTML!=='' ? document.getElementById('photos_div').innerHTML='' : null
          }
        }
        document.querySelectorAll('.remove_file').forEach((btn) => btn.addEventListener('click', removeFile))

      }

  })();


  const removeFile = (e) => {
              let dt = new DataTransfer();
              let key = e.target.id
              let delfile = document.querySelector('.account-file-input')
              console.log(delfile)
              for (let file of delfile.files) {

            dt.items.add(file);
           }

           delfile.files = dt.files;

              for(let i = 0; i < dt.files.length; i++){
             if(key == dt.files[i].lastModified){
              dt.items.remove(i);
              continue;
             }
           }
              delfile.files = dt.files
                  console.log(delfile.files)

              e.target.parentNode.remove()
          }
});