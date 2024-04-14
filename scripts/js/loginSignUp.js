// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-auth.js";
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');
const overlayLeftElements = document.querySelectorAll('.overlay-left');
const overlayRightElements = document.querySelectorAll('.overlay-right');
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

