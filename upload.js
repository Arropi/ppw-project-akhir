document.addEventListener('DOMContentLoaded', () => {
    const uploadInput = document.getElementById('upload-file');
    const imgShow = document.getElementById('img-show');
    const informationDiv = document.getElementById('information');
    const uploadFileContainer = document.querySelector('.upload-file');
    const labelInput = document.querySelector('.penanda')

    uploadInput.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imgShow.src = e.target.result;
                imgShow.style.display = 'block'; 
                informationDiv.style.display = 'none'; 

                imgShow.style.height = '100%'; 
                imgShow.style.width = 'auto'; 
                imgShow.style.objectFit = 'contain'; 
                imgShow.style.display = 'block'; 
                imgShow.style.margin = '0 auto'; 

                uploadFileContainer.style.backgroundColor = 'black';
                uploadFileContainer.style.position = 'relative'
                
                
                labelInput.style.position = 'absolute';
            };

            reader.readAsDataURL(file);
        } else {
        
            imgShow.src = '';
            imgShow.style.display = 'none';
            informationDiv.style.display = 'block';
            uploadFileContainer.style.backgroundColor = '#D9D9D9'; 
        }
    });
});