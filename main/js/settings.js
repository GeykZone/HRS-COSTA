let customerId = userOrAdminDetails.userId;
let UserRole = userOrAdminDetails.role;
let swiper;
const addPaymentMethodBtn = document.getElementById('addPaymentMethodBtn');
const paymenthMethodSettingsForm =  document.getElementById('paymenthMethodSettingsForm');
const ClosePaymentMethodForm = document.getElementById('ClosePaymentMethodForm');
const forEditPaymentMethod =  document.querySelectorAll('.forEditPaymentMethod');
const paymentMethodNameField =  document.getElementById('paymentMethodName');
const paymentMethodNumberField = document.getElementById('paymentMethodNumber');
const addQrButton = document.getElementById('addQrButton');
const uploadPaymentMethodQRImage = document.getElementById('uploadPaymentMethodQRImage');
const newPaymentMethodQRContainer = document.getElementById('newPaymentMethodQRContainer');
const newPaymentMethodQRError = document.getElementById('newPaymentMethodQRContainer-error');
const SubmitPaymentMethodChanges =  document.getElementById('SubmitPaymentMethodChanges');
const RemovePaymentMethod = document.getElementById('RemovePaymentMethod');
let uploadableQrImage = null; 
let paymentMethodSubmitType = 'Add';
let toUpdatePaymentMethodId = null;
let paymentMethodImageLink = [];
const newAccountUsername = document.getElementById('newAccountUsername');
const newAccountEmail = document.getElementById('newAccountEmail');
const newAccountPassword = document.getElementById('newAccountPassword');
const reTypeNewAccountPassword = document.getElementById('reTypeNewAccountPassword');
const updateDetails = document.getElementById('updateDetails');
const inputVerificationCode = document.getElementById('inputVerificationCode');
const reSendCode = document.getElementById('reSendCode');
const verifyCode = document.getElementById('verifyCode');
const emailVerification = document.getElementById('emailVerification');
const updateLoginDetailsContainer = document.getElementById('updateLoginDetailsContainer');
const resendLoading = document.getElementById('resendLoading');
let globalUniqueCode;

if(UserRole == 'admin'){
    const paymentMethodSettingsContainer = document.getElementById('paymentMethodSettingsContainer');

    const url = "controller/settingsController.php";
    const data = {
        queryAllPaymentMethods: true,
    };
    const detailsList = dynamicSynchronousPostRequest(url, data);

    if (isValidJSON(detailsList)) {
        const details = JSON.parse(detailsList);
        let status = details.status;
        let message = details.message;

        if (status === 'success') {
            const paymentMethodType = details.paymentMethods;

            // Clear the container before appending new items (optional)
            paymentMethodSettingsContainer.innerHTML = '';

            paymentMethodType.forEach(function (paymentMethod, index) {

                const inputField = document.createElement('input');
                inputField.type = 'radio';
                inputField.name = 'paymentMethodRadio';
                inputField.id =  `paymentMethodRadio-${paymentMethod.Id}`;
                inputField.value = `${paymentMethod.Id}`;

                const wrapper = document.createElement('div');
                wrapper.innerHTML = `
                    <label for="paymentMethodRadio-${paymentMethod.Id}">
                        <i class="fa-solid fa-money-check"></i>
                        ${paymentMethod.paymentMethodName}
                    </label>
                `;

                // Append the wrapper to the container
                paymentMethodSettingsContainer.appendChild(inputField)
                paymentMethodSettingsContainer.appendChild(wrapper);

                // add click event 
                inputField.addEventListener('click', function(){
                    const paymentMethodId = paymentMethod.Id;
                    const paymentMethodQRLink = paymentMethod.qrLink;
                    const paymentMethodName = paymentMethod.paymentMethodName;
                    const paymentMethodNumber = paymentMethod.paymentNumber;
                    const showOnlyIfManualPMIsClicked = document.getElementById('showOnlyIfManualPMIsClicked');
                    paymentMethodSubmitType = 'Update';
                    toUpdatePaymentMethodId = paymentMethodId;

                    if(paymentMethodName != 'Manual'){
                        if(paymenthMethodSettingsForm.classList.contains('display-none')){
                            paymenthMethodSettingsForm.classList.remove('display-none');
                        }
    
                        forEditPaymentMethod.forEach(function(pm){
                            if(pm.classList.contains('display-none')){
                                pm.classList.remove('display-none');
                            }
                        })

                        if (['Gcash', 'Palawan', 'Maya'].includes(paymentMethodName)){
                            if(!RemovePaymentMethod.classList.contains('display-none')){
                                RemovePaymentMethod.classList.add('display-none');
                            }
                        }
                        else{
                            if(RemovePaymentMethod.classList.contains('display-none')){
                                RemovePaymentMethod.classList.remove('display-none');
                            }
                        }

                        if(!showOnlyIfManualPMIsClicked.classList.contains('display-none')){
                            showOnlyIfManualPMIsClicked.classList.add('display-none');
                        }

                        if(paymentMethodName){
                            paymentMethodNameField.value = paymentMethodName;
                        }
                        
                        if(paymentMethodNumber){
                            paymentMethodNumberField.value = paymentMethodNumber;
                        }


                        if(paymentMethodQRLink){
                        
                            // Clear any existing image in the container
                            newPaymentMethodQRContainer.innerHTML = '';
    
                            // Create the image container
                            const imageContainer = document.createElement('div');
                            imageContainer.style.position = 'relative';
                            imageContainer.style.display = 'inline-block';
    
                            // Create the image element
                            const image = new Image();
                            image.src = paymentMethodQRLink;
                            image.alt = 'QR Code';
                            image.style.width = '200px'; // Adjust the size as needed
                            image.style.height = 'auto';
                            image.style.borderRadius = '8px';
    
                            // Create the delete button (Font Awesome "X" icon)
                            const deleteButton = document.createElement('span');
                            deleteButton.classList.add('fa', 'fa-times-circle');
                            deleteButton.style.position = 'absolute';
                            deleteButton.style.top = '5px';
                            deleteButton.style.right = '5px';
                            deleteButton.style.fontSize = '20px';
                            deleteButton.style.color = 'gray';
                            deleteButton.style.cursor = 'pointer';
                            deleteButton.style.display = 'none'; // Initially hidden
    
                            // Show delete button on hover
                            imageContainer.addEventListener('mouseover', () => {
                                deleteButton.style.display = 'block';
                            });
    
                            // Hide delete button when not hovering
                            imageContainer.addEventListener('mouseout', () => {
                                deleteButton.style.display = 'none';
                            });
    
                            // Append the image and delete button to the container
                            imageContainer.appendChild(image);
                            imageContainer.appendChild(deleteButton);
                            newPaymentMethodQRContainer.appendChild(imageContainer);
    
                            // Add event listener to the delete button
                            deleteButton.addEventListener('click', () => {
                                // Clear the container and reset the variable
                                newPaymentMethodQRContainer.innerHTML = '';
                                uploadPaymentMethodQRImage.value = '';
                                uploadableQrImage = null; // Remove the image from the variable
                                console.log('FIle Now: ' + uploadableQrImage)
                            });
    
                        }
                        
                        
                    }
                    else{
                        if(showOnlyIfManualPMIsClicked.classList.contains('display-none')){
                            showOnlyIfManualPMIsClicked.classList.remove('display-none');
                        }

                        if(!paymenthMethodSettingsForm.classList.contains('display-none')){
                            paymenthMethodSettingsForm.classList.add('display-none');
                        }
                    }
                    
                })
            });
        } else {
            alertMessage(message, 'error', 3000);
        }
    } else {
        console.error(detailsList);
        alertMessage('Something went wrong. Please see the error logs for additional information.', 'error', 3000);
    }

}

// open payment method form event
addPaymentMethodBtn.addEventListener('click',  function(){
    if(paymenthMethodSettingsForm.classList.contains('display-none')){
        paymenthMethodSettingsForm.classList.remove('display-none');
    }

    forEditPaymentMethod.forEach(function(pm){
        if(!pm.classList.contains('display-none')){
            pm.classList.add('display-none');
        }
    })

    if(!showOnlyIfManualPMIsClicked.classList.contains('display-none')){
        showOnlyIfManualPMIsClicked.classList.add('display-none');
    }

    newPaymentMethodQRContainer.innerHTML = '';
    paymentMethodNameField.value = '';
    paymentMethodNumberField.value = '';
    uploadableQrImage = null;
    toUpdatePaymentMethodId = null;
    paymentMethodSubmitType = 'Add';
})

// close payment method form event
ClosePaymentMethodForm.addEventListener('click', function(){
    if(!paymenthMethodSettingsForm.classList.contains('display-none')){
        paymenthMethodSettingsForm.classList.add('display-none');
    }
})

// Event listener for the Add QR button
addQrButton.addEventListener('click', () => {
    uploadPaymentMethodQRImage.click(); // Simulate a click on the hidden file input
});

// Event listener for the file input
uploadPaymentMethodQRImage.addEventListener('change', (event) => {
    // Clear any previous errors
    newPaymentMethodQRError.textContent = '';
    newPaymentMethodQRError.classList.add('display-none');

    const file = event.target.files[0]; // Get the first selected file

    if (file) {
        // Check if the file is an image
        if (!file.type.startsWith('image/')) {
            newPaymentMethodQRError.textContent = 'Only image files are allowed.';
            newPaymentMethodQRError.classList.remove('display-none');
            return;
        }

        // Update the variable with the selected image file
        uploadableQrImage = file;
        console.log('FIle Now: ' + uploadableQrImage)

        // Clear any existing image in the container
        newPaymentMethodQRContainer.innerHTML = '';

        // Create the image container
        const imageContainer = document.createElement('div');
        imageContainer.style.position = 'relative';
        imageContainer.style.display = 'inline-block';

        // Create the image element
        const image = new Image();
        image.src = URL.createObjectURL(file);
        image.alt = 'QR Code';
        image.style.width = '200px'; // Adjust the size as needed
        image.style.height = 'auto';
        image.style.borderRadius = '8px';

        // Create the delete button (Font Awesome "X" icon)
        const deleteButton = document.createElement('span');
        deleteButton.classList.add('fa', 'fa-times-circle');
        deleteButton.style.position = 'absolute';
        deleteButton.style.top = '5px';
        deleteButton.style.right = '5px';
        deleteButton.style.fontSize = '20px';
        deleteButton.style.color = 'gray';
        deleteButton.style.cursor = 'pointer';
        deleteButton.style.display = 'none'; // Initially hidden

        // Show delete button on hover
        imageContainer.addEventListener('mouseover', () => {
            deleteButton.style.display = 'block';
        });

        // Hide delete button when not hovering
        imageContainer.addEventListener('mouseout', () => {
            deleteButton.style.display = 'none';
        });

        // Append the image and delete button to the container
        imageContainer.appendChild(image);
        imageContainer.appendChild(deleteButton);
        newPaymentMethodQRContainer.appendChild(imageContainer);

        // Add event listener to the delete button
        deleteButton.addEventListener('click', () => {
            // Clear the container and reset the variable
            newPaymentMethodQRContainer.innerHTML = '';
            uploadPaymentMethodQRImage.value = '';
            uploadableQrImage = null; // Remove the image from the variable
            console.log('FIle Now: ' + uploadableQrImage)
        });
    }
});

// submet Payment method new or changes
SubmitPaymentMethodChanges.addEventListener('click',  function(){
    let isNotForEdit = false;
    if(paymentMethodSubmitType == 'Add'){
        isNotForEdit = true;
    }
    if(validatePaymentMethodForm(isNotForEdit)){

        if(uploadableQrImage){

            console.log( uploadableQrImage.name)
     
             const newUploadableQrImage = [{
                 file:uploadableQrImage
              }]
     
             uploadImageToFirebase(newUploadableQrImage, paymentMethodImageLink).then(() => {
                 if (paymentMethodImageLink.length > 0) {
                    submitPaymentMethodDetails(false);
                 }
             }).catch(error => {
                 alertMessage('Error uploading images, Error: ' + error, 'error', 5000);
                 console.error('Error uploading images:', error);
             });
     
         }
         else{
            submitPaymentMethodDetails(false);
         }
       
    }
})

// delete payment method
RemovePaymentMethod.addEventListener('click', function(){
    submitPaymentMethodDetails(true);
})

// update login details
updateDetails.addEventListener('click', function(){

    let isValid = true;

    if(newAccountEmail.value) {
        
        if(!isValidEmail(newAccountEmail.value)){
            displayError(newAccountEmail, "Please input a valid email format.");
            isValid = false;
        }
        else{
            displayError(newAccountEmail, '');
        }
    }

    if(newAccountPassword.value){
        
        if(newAccountPassword.value != reTypeNewAccountPassword.value){
            displayError(newAccountPassword, "Password does not match.");
            displayError(reTypeNewAccountPassword, "Password does not match.");
            isValid = false;
        }
        else{
            displayError(newAccountPassword, '');
            displayError(reTypeNewAccountPassword, '');
        }

    }

    if(isValid){
        
        if(newAccountEmail.value){

            verifyEmailAddress();
            const showVerificationContainer = setInterval(() => {
                if(globalUniqueCode){
                    clearInterval(showVerificationContainer)
                    if(emailVerification.classList.contains('display-none')){
                        emailVerification.classList.remove('display-none');
                    }
                    
                    if(!updateLoginDetailsContainer.classList.contains('display-none')){
                        updateLoginDetailsContainer.classList.add('display-none');
                    }
                }
            }, 100);

        }
        else{
            updateDmlOperation()
        }

    }

})

// verify code from email
verifyCode.addEventListener('click',  function(){
    if(validateVerificationCode()){
        updateDmlOperation()
    }
})

// resend code from email
reSendCode.addEventListener('click',  function(){

    globalUniqueCode = '';
    if(!emailVerification.classList.contains('display-none')){
        emailVerification.classList.add('display-none');
    }

    if(resendLoading.classList.contains('display-none')){
        resendLoading.classList.remove('display-none');
    }
    

    verifyEmailAddress();

    const showVerificationContainer = setInterval(() => {
        if(globalUniqueCode){
            clearInterval(showVerificationContainer)
            if(emailVerification.classList.contains('display-none')){
                emailVerification.classList.remove('display-none');
            }

            if(!resendLoading.classList.contains('display-none')){
                resendLoading.classList.add('display-none');
            }
            
            if(!updateLoginDetailsContainer.classList.contains('display-none')){
                updateLoginDetailsContainer.classList.add('display-none');
            }
        }
    }, 100);
    
})

// update account details dml
function updateDmlOperation(){

    const url = "controller/settingsController.php";
    const data = {
        userId: customerId,
        userDetailsUpdate: true,
        ...(newAccountUsername.value.trim() && { newAccountUsername: newAccountUsername.value }),
        ...(newAccountEmail.value.trim() && { newAccountEmail: newAccountEmail.value }),
        ...(newAccountPassword.value.trim() && { newAccountPassword: newAccountPassword.value }),
    };
    const detailsList = dynamicSynchronousPostRequest(url, data);

        if(isValidJSON(detailsList)){
            const details = JSON.parse(detailsList);
            let status = details.status;
            let message = details.message
            if(status == 'success'){
                alertMessage(message, 'success', 3000);
                setTimeout(function(){
                    window.location.reload();
                },2500)
            }
            else{
                alertMessage(message, 'error', 3000);
                dmlSuccess = false;
            }
        }
        else{
            dmlSuccess = false;
            
            if (detailsList.includes("Duplicate entry")) {
                alertMessage('Something went wrong. Duplicate Entry please open console for error logs.', 'error', 3000);
            } else {
                alertMessage('Something went wrong. Please see the error logs for additional information.', 'error', 3000);
            }
            console.error(detailsList);
            
        }

}

// validate verification input field
function validateVerificationCode() {
    let isValid = true;

    if(!inputVerificationCode.value) {
        displayError(inputVerificationCode, "Verification code cannot be empty");
        isValid = false;
    }
    else if (inputVerificationCode.value !== globalUniqueCode) {
        displayError(inputVerificationCode, "Verification code do not match");
        isValid = false;
    }
    else {
        displayError(inputVerificationCode, '');
    }

    return isValid;
}

// validate payment method form
function validatePaymentMethodForm(isNotForEdit){

    isValid = true

    if(!paymentMethodNameField.value) {
        displayError(paymentMethodNameField, "Payment Method Name cannot be empty");
        isValid = false;
    }
    else {
        displayError(paymentMethodNameField, '');
    }

    if(!paymentMethodNumberField.value) {
        displayError(paymentMethodNumberField, "Payment Method Number / Code cannot be empty");
        isValid = false;
    }
    else {
        displayError(paymentMethodNumberField, '');
    }

    if(isNotForEdit && !uploadableQrImage){
        newPaymentMethodQRError.textContent = 'Please insert an image.';
        newPaymentMethodQRError.classList.remove('display-none');
        isValid = false;
    }
    else{
        newPaymentMethodQRError.textContent = '';
        newPaymentMethodQRError.classList.add('display-none');
    }

    return isValid;
}

// submit payment method details
function submitPaymentMethodDetails(isDeletePm){

    const url = "controller/settingsController.php";
    const data = {
        paymentMethodNameField:paymentMethodNameField.value,
        paymentMethodNumberField:paymentMethodNumberField.value,
        paymentMethodDml: true
    };
    
    if (paymentMethodImageLink.length > 0) {
        data.paymentMethodImageLink = paymentMethodImageLink[0].Link;
    }

    if(toUpdatePaymentMethodId){
        data.toUpdatePaymentMethodId = toUpdatePaymentMethodId;
    }

    if(isDeletePm){
        data.deleteThisPaymentMethod = true;
    }

    console.log(data)

    const detailsList = dynamicSynchronousPostRequest(url, data);

    if(isValidJSON(detailsList)){
        const details = JSON.parse(detailsList);
        let status = details.status;
        let message = details.message
        if(status == 'success'){
            alertMessage(message, 'success', 3000);
            setTimeout(function(){
                window.location.reload();
            },2500)
        }
        else{
            alertMessage(message, 'error', 3000);
            dmlSuccess = false;
        }
    }
    else{
        dmlSuccess = false;
        console.error(detailsList);
        alertMessage('Something went wrong. Please see the error logs for additional information.', 'error', 3000);
    }

}

// Upload all images to Firebase
async function uploadImageToFirebase(selectedFiles, imageLink) {
    
    const uploadPromises = selectedFiles.map(selected => {
        const storageRef = storage.ref('images/' + selected.file.name);
        const uploadTask = storageRef.put(selected.file);
        imageLink;

        return new Promise((resolve, reject) => {
            uploadTask.on('state_changed', 
                (snapshot) => {
                    const progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                }, 
                (error) => {
                    alertMessage('Upload failed: ' + error, 'error', 3000);
                    console.error('Upload failed:', error);
                    reject(error);
                }, 
                () => {
                    uploadTask.snapshot.ref.getDownloadURL().then((downloadURL) => {
                        // console.log('File available at', downloadURL);
                        let downloadURLString = String(downloadURL);
                        imageLink.push({
                            Link : downloadURLString
                        });
                        resolve();
                    }).catch(error => {
                        alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
                        reject(error);
                    });
                }
            );
        });
    });

    await Promise.all(uploadPromises);
}

//check if is valid email format
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// verify email address form manual sign-up
function verifyEmailAddress() {

    let toEMail = newAccountEmail.value;
    let toName = extractUsername(toEMail);
    let generateUniqueCode = Math.random().toString(36).substring(2, 8).toUpperCase();
    
    var data = {
        service_id: 'service_lb5am8l',
        template_id: 'template_gak32m7',
        user_id: '9YUtu9MVMdqaqe0T7', //public key
        template_params: {
            'fromName': 'HRS-COSTA',
            'message': generateUniqueCode,
            'toEmail': toEMail,
            'toName': toName
        }
    };

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'https://api.emailjs.com/api/v1.0/email/send');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            alertMessage(`Your mail is sent!`, 'success', 3000);
            globalUniqueCode = generateUniqueCode;
            
        } else {
            alertMessage('Oops... ' + xhr.responseText, 'error', 3000);
        }
    };
    xhr.onerror = function() {
        alert('Oops... Something went wrong!');
    };
    xhr.send(JSON.stringify(data));
}

//Convert Email into a Username
function extractUsername(email) {
    const atIndex = email.indexOf("@");
    let username = email.substring(0, atIndex);
    username = username.replace(/\./g, " ");
    username = username.replace(/\b\w/g, function(char) {
        return char.toUpperCase();
    });
    return username;
}
