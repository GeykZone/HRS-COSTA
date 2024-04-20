// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');
const overlayLeftElements = document.querySelectorAll('.overlay-left');
const overlayRightElements = document.querySelectorAll('.overlay-right');
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirm-password");
const confirmButton = document.getElementById("confirm");
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
const provider = new GoogleAuthProvider();
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

// Initialize the Facebook SDK
window.fbAsyncInit = function() {
    FB.init({
      appId      : '7486960388056831',
      cookie     : true,
      xfbml      : true,
      version    : 'v19.0'
    });
};

// Load the Facebook SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'http://connect.facebook.net/en_US/sdk.js';
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//login using facebook
facebookLogin.forEach(function(element) {
   
    element.addEventListener("click", function(){
        
        let fromFacebook = true;
        let fromGoogle = false;
        let fromSignUpPage = false;
        let username;
        let accessToken;
        let loginStatus;
    
        FB.login(function(response) {
    
            if (response.authResponse) {
                accessToken = response.authResponse.accessToken;
                loginStatus = "connected"
    
                FB.api('/me', function(response) {
                    username = response.name;
                });
    
                toastMixin.fire({
                    animation: true,
                    title: `User is authorized.`,
                    timer: 3000,
                    icon: 'success'
                });
    
            }
            else {
    
                toastMixin.fire({
                    animation: true,
                    title: `User cancelled login or did not authorize.`,
                    timer: 3000,
                    icon: 'error'
                });
            }
        }, { scope: 'email' });

    });

});

//login using googleLogin
googleLogin.forEach(function(element) {
   
    element.addEventListener("click", function(){

        signInWithPopup(auth, provider)
        .then((result) => {

            let fromFacebook = false;
            let fromGoogle = true;
            let fromSignUpPage = false;
            let username;
            let accessToken;
            let loginStatus;
            let email;
            const credential = GoogleAuthProvider.credentialFromResult(result);
            const token = credential.accessToken;
            const user = result.user;

            username = user.displayName;
            accessToken = token;
            loginStatus = "connected";
            email = user.email;
            toastMixin.fire({
                animation: true,
                title: `User is authorized.`,
                timer: 3000,
                icon: 'success'
            });
          
        }).catch((error) => {

          const errorMessage = error.message;
          toastMixin.fire({
            animation: true,
            title: errorMessage,
            timer: 3000,
            icon: 'error'
          });

        });

    });

});

//call event for manual sign-up
confirmButton.addEventListener("click", function(event) {
    event.preventDefault();

    if(validateForm())
    {
        verifyEmailAddress()
    }
});

// validate formfor manual sign-up
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

            toastMixin.fire({
                animation: true,
                title: `Your mail is sent!`,
                timer: 3000,
                icon: 'success'
            });
            
        } else {

            toastMixin.fire({
                animation: true,
                title: 'Oops... ' + xhr.responseText,
                timer: 3000,
                icon: 'error'
              });
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


