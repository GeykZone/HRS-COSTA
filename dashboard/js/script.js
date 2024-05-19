let getBarItem = document.querySelector(".bar-item");
let getSideBar = document.querySelector(".sidebar");
let getXmark = document.querySelector(".xmark");
let getPageContent = document.querySelector(".page-content");
let getLoader = document.querySelector(".loader");
let getToggle = document.querySelectorAll(".toggle");
let getHeart = document.querySelector(".heart");
let getSidebarLink = document.querySelectorAll(".sidebar-link");
let logoutButton = document.querySelector(".logout-button");
let customer = document.querySelectorAll(".customer");
let admin = document.querySelectorAll(".admin");
let toLandingPage = document.querySelectorAll(".toLandingPage");
let activePage = window.location.pathname;
let getSideBarStatus = false;
let storage;
let rateDeleteCount = 0;

// intialize sweet alert
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

//dynamic alert message
function alertMessage(message, type, time) {
  toastMixin.fire({
      animation: true,
      title: message,
      timer: time,
      icon: type
  });
}

// Initialize Firebase
if(typeof firebase !== 'undefined' && firebase.apps.length === 0){
  const firebaseConfig = {
    apiKey: "AIzaSyCESzbz0Ux11Dcnt4CJiVdYLWcxshuJXX0",
    authDomain: "hrs-costa.firebaseapp.com",
    projectId: "hrs-costa",
    storageBucket: "hrs-costa.appspot.com",
    messagingSenderId: "518563931819",
    appId: "1:518563931819:web:dc064a36bc4691e26d79ee",
    measurementId: "G-X04SLCJ83S"
  };
  firebase.initializeApp(firebaseConfig);
  storage = firebase.storage();
}

// Function to capitalize the first letter of a string
function capitalizeFirstLetter(string) {
  return string.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
}

// Function to format a number as Philippine pesos currency
function formatCurrency(number) {
  return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(number);
}

// Function to parse a Philippine pesos currency string back to a number
function parseCurrency(currencyString) {
  return parseFloat(currencyString.replace(/[^0-9.-]+/g, ""));
}

// covernt currency into number
function convertCurrencyStringToNumber(currencyString) {
  let numberString = currencyString.replace(/[₱,]/g, '');
  let numberValue = parseFloat(numberString);
  return numberValue;
}

// dynamic Currency Only Input
function dynamicCurrencyOnlyInput(inputNUmber) {

  let enteredValue = inputNUmber.value;
  let cleanedValue = enteredValue.replace(/[^\d.,₱]/g, '');
  inputNUmber.value = cleanedValue;

  if (cleanedValue === '₱') {
    inputNUmber.value = '';
  } else {
    inputNUmber.value = cleanedValue;
  }
}

// dynamic currency formatter
function dynamicInputFieldCurrencyFormatter(inputField) {
  let enteredValue = inputField.value;
  
  if (enteredValue.trim() !== '' && !isNaN(enteredValue)) {
      let parsedValue = parseCurrency(enteredValue);
      let formattedCurrency = formatCurrency(parsedValue);
      inputField.value = formattedCurrency;
  }
}

// dynamic post request
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
function resetErrors(erroClass) {
  const errorElements = document.querySelectorAll('.'+erroClass);
  errorElements.forEach(function(errorElement) {
      errorElement.innerText = "";
      errorElement.classList.remove("error");
  });
}

// event for showing whats for admin and whats for costumer
window.addEventListener('load', function() {
  
  if( typeof userOrAdminDetails !== 'undefined' && Object.keys(userOrAdminDetails).length > 0){
    // console.log(userOrAdminDetails);

    if(userOrAdminDetails.role === 'customer'){
      console.log('Active: Customer');
      showOnlyCustomerUi();
    }
    else {
      console.log('Active: Admin');
      showOnlyAdminUi();
    }
  }

});

// show only customer
function showOnlyCustomerUi() {
  admin.forEach(function(element) {
    element.classList.add("display-none");
  });
  customer.forEach(function(element) {
    element.classList.remove("display-none");
  });
}

// show only admin
function showOnlyAdminUi() {
  admin.forEach(function(element) {
    element.classList.remove("display-none");
  });
  customer.forEach(function(element) {
    element.classList.add("display-none");
  });

  
}

// redirect to landing page
toLandingPage.forEach(function(element) {
  element.addEventListener("click", function(){
    window.location.href = '../landingPage/index.php'
  });
});

// logout session
if (logoutButton) { logoutButton.onclick = () => {
    const url = "../login/controller/loginSignUpController.php";
    const data = {
        logout: true
    };
    handlePostRequest(url,data )
    .then((response) => {
        var jsonResponse = JSON.parse(response);
        if(jsonResponse.logout) {
           window.location.reload(true);
        }
        else {
            alertMessage('Something went wrong, unknown error.', 'error', 3000);
            console.log(response)
        }
    })
    .catch((error) => {
        alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
        console.log("Error:", error);
    });
      
  };
}

// others default 
getBarItem.onclick = () => {
  getSideBar.style = "transform: translateX(0px);width:220px";
  getSideBar.classList.add("sidebar-active");
};

getXmark.onclick = () => {
  getSideBar.style =
    "transform: translateX(-220px);width:220px;box-shadow:none;";
  getSideBarStatus = true;
  if (getSideBar.classList.contains("sidebar-active")) {
    getSideBar.classList.remove("sidebar-active");
  }
};

window.addEventListener("resize", (e) => {
  if (getSideBarStatus === true) {
    if (e.target.innerWidth > 768) {
      getSideBar.style = "transform: translateX(0px);width:220px";
    } else {
      getSideBar.style =
        "transform: translateX(-220px);width:220px;box-shadow:none;";
    }
  }
});

if (getLoader) {
  window.addEventListener("load", () => {
    getLoader.style.display = "none";
    getPageContent.style.display = "grid";
    activePage = "index.php";
    getSidebarLink.forEach((item) => {
      if (item.href.includes(`${activePage}`)) {
        item.classList.add("active");
      } else item.classList.remove("active");
    });
  });
}

document.onclick = (e) => {
  if (getSideBar.classList.contains("sidebar-active")) {
    if (
      !e.target.classList.contains("bar-item") &&
      !e.target.classList.contains("sidebar") &&
      !e.target.classList.contains("brand") &&
      !e.target.classList.contains("brand-name")
    ) {
      getSideBar.style =
        "transform: translateX(-220px);width:220px;box-shadow:none;";
      getSideBar.classList.remove("sidebar-active");
      getSideBarStatus = true;
    }
  }
};

window.addEventListener("scroll", () => {
  if (getSideBar.classList.contains("sidebar-active")) {
    getSideBar.style =
      "transform: translateX(-220px);width:220px;box-shadow:none;";
    getSideBar.classList.remove("sidebar-active");
  }
});

if (getHeart) {
  getHeart.addEventListener("click", (e) => {
    if (e.target.classList.contains("fa-regular")) {
      getHeart.classList.replace("fa-regular", "fa-solid");
      getHeart.style.color = "red";
    } else {
      getHeart.classList.replace("fa-solid", "fa-regular");
      getHeart.style.color = "#888";
    }
  });
}

getToggle.forEach((item) => {
  item.addEventListener("click", () => {
    if (item.classList.contains("left")) {
      item.classList.remove("left");
    } else {
      item.classList.add("left");
    }
  });
});

getSidebarLink.forEach((item) => {
  if (item.href.includes(`${activePage}`)) {
    item.classList.add("active");
  }
});
