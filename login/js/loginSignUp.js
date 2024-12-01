// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup, FacebookAuthProvider  } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');
const overlayLeftElements = document.querySelectorAll('.overlay-left');
const overlayRightElements = document.querySelectorAll('.overlay-right');
const emailInput = document.getElementById("email");
const emailOrUsernameInput = document.getElementById("emailOrUserLogin");
const passwordInput = document.getElementById("password");
const passwordLoginInput = document.getElementById("passwordLogin");
const confirmPasswordInput = document.getElementById("confirm-password");
const confirmButton = document.getElementById("confirm");
const login = document.getElementById('login');
const verifyGmailButton = document.getElementById("verifyGmail");
const verificationCodeInput = document.getElementById("verificationCode");
const forVerification = document.querySelectorAll(".forVerification");
const forSignUp = document.querySelectorAll(".forSignUp");
const newRole = 'customer';
const loginStatus = 'connected';
let globalUniqueCode;
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyCESzbz0Ux11Dcnt4CJiVdYLWcxshuJXX0",
    authDomain: "hrs-costa.firebaseapp.com",
    projectId: "hrs-costa",
    storageBucket: "hrs-costa.appspot.com",
    messagingSenderId: "518563931819",
    appId: "1:518563931819:web:dc064a36bc4691e26d79ee",
    measurementId: "G-X04SLCJ83S"
};
// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
auth.languageCode = 'en';
const googleProvider = new GoogleAuthProvider();
googleProvider.addScope('email');
const fbProvider = new FacebookAuthProvider();
const googleLogin =  document.querySelectorAll(".google");
var toastMixin = Swal.mixin({
    toast: true,
    icon: 'success',
    title: 'General Title',
    position: 'top-right',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});
const facebookLogin =  document.querySelectorAll(".facebook");

function alertMessage(message, type, time) {
    toastMixin.fire({
        animation: true,
        title: message,
        timer: time,
        icon: type
    });
}

// To swap the view from login to signup
signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");

    overlayRightElements.forEach(function(element) {
        element.classList.remove('border-left');
    });

    overlayLeftElements.forEach(function(element) {
        element.classList.add('border-right');
    });
});

// To swap the view from signup to login
signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
        
    overlayLeftElements.forEach(function(element) {
        element.classList.remove('border-right');
    });

    overlayRightElements.forEach(function(element) {
        element.classList.add('border-left');
    });
});

function getLastPartOfUrl(url) {
    // Split the URL by the '/' character
    let parts = url.split('/');
    // Return the last element of the array
    return parts.pop();
}

//login using facebook
facebookLogin.forEach(function(element) {
   
    element.addEventListener("click", function(){  

        signInWithPopup(auth, fbProvider)
        .then((result) => {
            const user = result.user;
            const fullDetails = result._tokenResponse
            const fbDetails = user.reloadUserInfo.providerUserInfo[0];
            let fbEMail = fullDetails.email;

            if(!fbEMail){
                fbEMail = `${getLastPartOfUrl(fullDetails.federatedId)}@empty.email`;
            }

            console.log('fb: ', fullDetails);

            const url = "controller/loginSignUpController.php";
            const data = {
                fbUsername: fbDetails.displayName + ' FB:' +generateRandomToken(6),
                fbaAccessToken: generateRandomToken(50),
                fbUSerId: fbDetails.rawId,
                // fbuserEmail: fbEMail,
                fbuserEmail: generateRandomToken(6)+'@facebook.com',
                fbloginStatus: loginStatus,
                newRole: newRole,
                fromFacebook: true
            };
        
            handlePostRequest(url,data )
            .then((response) => {
                var jsonResponse = JSON.parse(response);
                alertMessage(`User is authorized.`, 'success', 3000);
                if(jsonResponse.userId && jsonResponse.fbloginStatus === 'connected') {
                    window.location.reload(true);
                }
                else {
                    if(jsonResponse.insertUserError){
                        alertMessage(`Something went wrong error details: ${jsonResponse.insertUserError}`, 'error', 5000);
                    }
                    else{
                        alertMessage(`Something went wrong error details: ${response}`, 'error', 5000);
                    }
                    console.log(response)
                }
                // console.log(response)
            })
            .catch((error) => {
                alertMessage(`Something went wrong error details: ${error}`, 'error', 3000);
                console.log("Error:", error.message);
            });
          
        }).catch((error) => {

          const errorMessage = error.message;
          alertMessage(`Something went wrong error details: ${errorMessage}`, 'error', 3000);
          console.log('fb error: ',  error.message);

        });
    });

});

//login using googleLogin
googleLogin.forEach(function(element) {
   
    element.addEventListener("click", function(){

        signInWithPopup(auth, googleProvider)
        .then((result) => {

            const user = result.user;
            const fullDetails = result._tokenResponse
            const googleDetails = user.reloadUserInfo.providerUserInfo[0];

            const url = "controller/loginSignUpController.php";
            const data = {
                googleUsername: user.displayName + ' G:' +generateRandomToken(6),
                googleAccessToken: generateRandomToken(50),
                googleUSerId: googleDetails.rawId,
                googleuserEmail: fullDetails.email,
                googleloginStatus: loginStatus,
                newRole: newRole,
                fromGoogle: true
            };
            //console.log('google results: ', data);            
            
            handlePostRequest(url,data )
            .then((response) => {
                var jsonResponse = JSON.parse(response);
                alertMessage(`User is authorized.`, 'success', 3000);
                if(jsonResponse.userId && jsonResponse.googleloginStatus === 'connected') {
                    window.location.reload(true);
                }
                else {
                    if(jsonResponse.insertUserError){
                        alertMessage(`Something went wrong error details: ${jsonResponse.insertUserError}`, 'error', 5000);
                    }
                    else{
                        alertMessage(`Something went wrong error details: ${response}`, 'error', 5000);
                    }
                    console.log(response)
                }
            })
            .catch((error) => {
                alertMessage(`Something went wrong error details: ${error}`, 'error', 3000);
                console.log("Error:", error.message);
            });
          
        }).catch((error) => {

          const errorMessage = error.message;
          alertMessage(`Something went wrong error details: ${errorMessage}`, 'error', 3000);
          alertMessage(errorMessage, 'error', 3000);

        });

    });

});

//login manual method
function loginManual() {

    const url = "controller/loginSignUpController.php";
    const data = {
        emailOrUsername: emailOrUsernameInput.value,
        password: passwordLoginInput.value,
        loginAccessToken: generateRandomToken(50),
        manualLogin: true
    };

    handlePostRequest(url,data )
    .then((response) => {
        var jsonResponse = JSON.parse(response);
        if(jsonResponse.userId) {
            alertMessage(`User is authorized.`, 'success', 3000);
            window.location.reload(true);
        }
        else {
            alertMessage(`Something went wrong error details: ${jsonResponse.error}`, 'error', 5000);
            console.log(response)
        }
    })
    .catch((error) => {
        alertMessage(`Something went wrong. ${error}`, 'error', 3000);
        console.log("Error:", error);
    });

}

//call event for manual sign-up
confirmButton.addEventListener("click", function(event) {
    event.preventDefault();

    if(validateForm())
    {
        verifyEmailAddress()
    }
});

// event for login manual
login.addEventListener("click", function(event) {
    event.preventDefault();

    if(validateLoginForm())
    {
        loginManual();
    }
});

//call event for verifying the gmail code
verifyGmailButton.addEventListener("click", function(event) {
    event.preventDefault();

    if(validateVerificationCode())
    {
        createAccount();
    }
});

//validate verification code field
function validateVerificationCode() {
    resetErrors();
    let isValid = true;

    if(!verificationCodeInput.value) {
        displayError(verificationCodeInput, "Verification code cannot be empty");
        isValid = false;
    }
    else if (verificationCodeInput.value !== globalUniqueCode) {
        displayError(verificationCodeInput, "Verification code do not match");
        isValid = false;

        console.log(verificationCodeInput + " !== " + globalUniqueCode);
    }
    else {
        displayError(verificationCodeInput, '');
    }

    return isValid;
}

// dybamic post request
function handlePostRequest(url, data) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();

        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    resolve(xhr.responseText);
                } else {
                    reject("HTTP Error: " + xhr.status);
                }
            }
        };

        xhr.onerror = function () {
            reject("Network error");
        };

        xhr.send(JSON.stringify(data));
    });
}

//create account after gmail is validated
function createAccount() {
    let newEmail = emailInput.value;
    let newPassword = passwordInput.value;
    let newUserName = extractUsername(newEmail);
    let newAccessToken = generateRandomToken(50);

    const url = "controller/loginSignUpController.php";
    const data = {
        newEmail: newEmail,
        newPassword: newPassword,
        newRole: newRole,
        newUserName: newUserName,
        newAccessToken: newAccessToken,
        manualSignUp: true
    };

    handlePostRequest(url, data)
        .then((response) => {
            var jsonResponse = JSON.parse(response);
            if(jsonResponse.userId) {
                alertMessage(`Account created`, 'success', 3000);
                window.location.reload(true);
            }
            else {
                if(jsonResponse.insertUserError){
                    alertMessage(`Something went wrong error details: ${jsonResponse.insertUserError}`, 'error', 5000);
                }
                else{
                    alertMessage(`Something went wrong error details: ${response}`, 'error', 5000);
                }
                console.log(response)
            }
        })
        .catch((error) => {
            alertMessage(`Something went wrong: ${error}`, 'error', 3000);
            console.error("Error:", error);
        });
}

//create an accesstoken
function generateRandomToken(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return result;
}

// validate form for manual sign-up
function validateForm() {
    resetErrors();
    let isValid = true;

    // Validate email
    if (!isValidEmail(emailInput.value)) {
        displayError(emailInput, "Invalid email format");
        isValid = false;
    }
    else{
        displayError(emailInput, '');
    }

    // Validate password
    if (!passwordInput.value) {
        displayError(passwordInput, "Password cannot be empty");
        isValid = false;
    }
    else{
        displayError(passwordInput, '');
    }

    // Validate confirm password
    if (!confirmPasswordInput.value) {
        displayError(confirmPasswordInput, "Confirm password cannot be empty");
        isValid = false;
    } else if (confirmPasswordInput.value !== passwordInput.value) {
        displayError(confirmPasswordInput, "Passwords do not match");
        isValid = false;
    }
    else {
        displayError(confirmPasswordInput, '');
    }

    return isValid;
}

function validateLoginForm() {
    resetErrors();
    let isValid = true;

    // Validate email
    if (!emailOrUsernameInput.value) {
        displayError(emailOrUsernameInput, "Email/Username cannot be empty");
        isValid = false;
    }
    else{
        displayError(emailOrUsernameInput, '');
    }

    // Validate password
    if (!passwordLoginInput.value) {
        displayError(passwordLoginInput, "Password cannot be empty");
        isValid = false;
    }
    else{
        displayError(passwordLoginInput, '');
    }

    return isValid;
}

//check if is valid email format
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

//add or display error on inputs if does not meet the validation
function displayError(inputElement, errorMessage) {

    const errorElement = document.getElementById(`${inputElement.id}-error`);
    errorElement.innerText = errorMessage;
    inputElement.classList.remove("error");
    if (errorMessage) {
        errorElement.innerText = errorMessage;
        inputElement.classList.add("error");
    }
}

//remove validation error on inputs
function resetErrors() {
    const errorElements = document.querySelectorAll(".error");
    errorElements.forEach(function(errorElement) {
        errorElement.innerText = "";
        errorElement.classList.remove("error");
    });
}

// verify email address form manual sign-up
function verifyEmailAddress() {

    let toEMail = emailInput.value;
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

            showVerificationFields();
            
        } else {
            alertMessage('Oops... ' + xhr.responseText, 'error', 3000);
        }
    };
    xhr.onerror = function() {
        alert('Oops... Something went wrong!');
    };
    xhr.send(JSON.stringify(data));
}

//show the gmail verification field after code is sent
function showVerificationFields() {

    forVerification.forEach(function(element) {
        element.classList.remove('display-none');
    });

    confirmButton.innerText = "Resend Code";

    forSignUp.forEach(function(element) {
        element.classList.add('display-none');
    });

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


