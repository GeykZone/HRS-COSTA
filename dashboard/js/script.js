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
let cardWrapperEvidence = document.querySelector('.card-wrapper-evidence');
let activePage = window.location.pathname;
let getSideBarStatus = false;
let storage;
let rateDeleteCount = 0;

dynamicConfirmationMessage({
  logo: 'fa-solid fa-arrow-right-from-bracket', 
  message: 'Do you really want to logout?', 
  title: 'Logout', 
  logoColor: '#19C4B8', 
  buttonLeftCustomClass: 'logout-proceed',
  buttonRightCustomClass: 'cancel-logout',
  buttonLeftCustomText: 'Logout',
  buttonRightCustomText: 'Cancel',
  buttonLeftCustomColor: '#19C4B8',
  buttonRightCustomColor: '#C41919',
  mainClass: 'logout-confirmation',
  displayBox: false,
  messageBoxCustomClass: 'logout-message-box',
  messageBoxPlaceHolder: 'Rejection Reason'
})

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

function paymentMethodsOptions() {
  const url = "controller/roomsController.php";
  const data = {
      queryPaymentMethods: true,
  };

  return handlePostRequest(url, data)
      .then((response) => {
          var jsonResponse = JSON.parse(response);
          if (jsonResponse.rooms.length > 0) {
              let paymentMethods = jsonResponse.rooms;
              return paymentMethods;
          } else {
              alertMessage('There are no rooms available at this time.', 'warning', 3000);
              console.log(response);
              return null;
          }
      })
      .catch((error) => {
          alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
          console.log("Error:", error);
          return null;
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

// dynamuc currency convert for text value
function dynamicCurrencyforTxtValue(value){

  if (!isNaN(parseFloat(value))) {
      let numericAmount = parseFloat(value);
      let formattedCurrency = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(numericAmount);
      value = formattedCurrency;

      return value;
  }

  return 0;
}

// dynamic Currency Only Input
function dynamicCurrencyOnlyInput(inputNumber) {
  let enteredValue = inputNumber.value;

  // Remove all non-numeric characters except for '.', ',' and '₱'
  let cleanedValue = enteredValue.replace(/[^\d.,₱]/g, '');

  // Ensure there's only one '.' in the input
  const parts = cleanedValue.split('.');
  if (parts.length > 2) {
      // If there are multiple '.', join the parts back together and ignore the extra '.'
      cleanedValue = parts[0] + '.' + parts.slice(1).join('');
  }

  // Ensure there's only one '₱' at the start of the string
  if (cleanedValue.includes('₱')) {
      cleanedValue = '₱' + cleanedValue.replace(/₱/g, '');
  }

  // Remove all non-numeric characters except for one '.' and ','
  inputNumber.value = cleanedValue;

  if (cleanedValue === '₱') {
      inputNumber.value = '';
  } else {
      inputNumber.value = cleanedValue;
  }
}


// dynamic picklist sign error () 
function dynamicPicklistErrorSign (picklistClassName, isValid)
{
  // Create a style element
  var style = document.createElement('style');
  style.type = 'text/css';

  if(!isValid) {

    // Define the CSS rules
    var cssRules = `
    .${picklistClassName} .selectize-input {
    border: solid 1px red !important;
    }
    `;
  }
  else {

    // Define the CSS rules
    var cssRules = `
    .${picklistClassName} .selectize-input {
      border: solid 1px dimgray !important;
    }
    `;
  }


  // Add the CSS rules to the style element
  if (style.styleSheet) {
    style.styleSheet.cssText = cssRules; // For IE8 and below
  } else {
    style.appendChild(document.createTextNode(cssRules)); // For other browsers
  }

  // Append the style element to the head of the document
  document.head.appendChild(style);

}
 
// dynamic currency formatter
function dynamicInputFieldCurrencyFormatter(inputField) {
  let enteredValue = inputField.value;

  // Remove all commas from the input value
  enteredValue = enteredValue.replace(/[₱,]/g, '');
  
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

function removeErrorLabel(forLabelcls) {
  const errorLabel = document.querySelectorAll(`.${forLabelcls}`);
  errorLabel.forEach(function(errorLbl) {
    errorLbl.innerText = '';
  })
}

//remove validation error on inputs
function resetErrors(erroClass) {
  const errorElements = document.querySelectorAll('.'+erroClass);
  errorElements.forEach(function(errorElement) {
      errorElement.value = "";
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

function getTwoHoursFromNow() {
  const now = new Date();
  now.setHours(now.getHours() + 2);

  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
  const day = String(now.getDate()).padStart(2, '0');
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const seconds = String(now.getSeconds()).padStart(2, '0');

  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
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

// dynamic confirmation modal
function dynamicConfirmationMessage(modalValue) {
   let leftButton = ' #265df2';
   let rightButton = ' #265df2';
   let logoColor = ' #265df2';
   let leftClass = '';
   let rightClass = '';
   let custoMIcon = 'fa-regular fa-face-smile';
   let title = 'Default Title';
   let message = 'Hello this is a default message.';
   let leftButtonText = 'Left Button';
   let RightButtonText = 'Right Button';
   let mainClass = 'custom-confirmation';
   let messageBoxCustomClass = 'message-box';
   let displayBox = 'modal-hide';
   let messageBoxPlaceHolder = 'Message Box';
   let hideIfForReciept = '';
   let showIfForReciept = 'display-none';
   let customHTMLElements = '';
   let frameWidth = '380px';
   
    if (modalValue?.logo !== undefined && !modalValue.logo.length < 1) {
        custoMIcon = modalValue.logo;
    }
    if (modalValue?.message !== undefined && !modalValue.message.length < 1) {
        message = modalValue.message;
    }
    if (modalValue?.title !== undefined && !modalValue.title.length < 1) {
      title = modalValue.title;
    }
    if (modalValue?.logoColor !== undefined && !modalValue.logoColor.length < 1) {
      logoColor = modalValue.logoColor;
    }
    if (modalValue?.buttonLeftCustomClass !== undefined && !modalValue.buttonLeftCustomClass.length < 1) {
        leftClass = modalValue.buttonLeftCustomClass;
    }
    if (modalValue?.buttonRightCustomClass !== undefined && !modalValue.buttonRightCustomClass.length < 1) {
        rightClass = modalValue.buttonRightCustomClass;
    }
    if (modalValue?.buttonLeftCustomText !== undefined && !modalValue.buttonLeftCustomText.length < 1) {
        leftButtonText = modalValue.buttonLeftCustomText;
    }
    if (modalValue?.buttonRightCustomText !== undefined && !modalValue.buttonRightCustomText.length < 1) {
        RightButtonText = modalValue.buttonRightCustomText;
    }
    if (modalValue?.buttonLeftCustomColor !== undefined && !modalValue.buttonLeftCustomColor.length < 1) {
        leftButton = modalValue.buttonLeftCustomColor;
    }
    if (modalValue?.buttonRightCustomColor !== undefined && !modalValue.buttonRightCustomColor.length < 1) {
        rightButton = modalValue.buttonRightCustomColor;
    }
    if (modalValue?.mainClass !== undefined && !modalValue.mainClass.length < 1) {
        mainClass = modalValue.mainClass;
    }
    if (modalValue?.displayBox !== undefined && modalValue.displayBox == true) {
        displayBox = ''
    }
    if (modalValue?.messageBoxCustomClass !== undefined && !modalValue.messageBoxCustomClass.length < 1) {
      messageBoxCustomClass = modalValue.messageBoxCustomClass;
    }
    if (modalValue?.messageBoxPlaceHolder !== undefined && !modalValue.messageBoxPlaceHolder.length < 1) {
      messageBoxPlaceHolder = modalValue.messageBoxPlaceHolder;
    }
    if (modalValue?.isForReciept !== undefined && modalValue.isForReciept) {
        hideIfForReciept = 'display-none';
        showIfForReciept = '';

        if(modalValue?.customHTMLElements !== undefined){
          customHTMLElements = modalValue.customHTMLElements
        }

    }
    if(modalValue?.frameWidth !== undefined && !modalValue.frameWidth.length < 1) {
      frameWidth = modalValue.frameWidth;
    }

   // Create style element and append CSS to it
   const style = document.createElement('style');
   style.innerHTML = `
     /* Google Fonts - Poppins */
     @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap");

     .${mainClass} {
       font-family: "Poppins", sans-serif;
       position: fixed;
       height: 100%;
       width: 100%;
       background: #010F1AA2;
       z-index: 9999999999;
     }
     .${mainClass} button {
       font-size: 15px;
       font-weight: 400;
       color: #fff;
       padding: 14px 22px;
       border: none;
       border-radius: 6px;
       cursor: pointer;
     }
     .${mainClass} button:hover {
      font-size: 12px !important;
     }

      .${mainClass} .custom-modal-confirm-button {
       background-color: ${leftButton};
     }
     
      .${mainClass} .custom-modal-cancel-button {
      background-color: ${rightButton};
     }

     .${mainClass} button.show-modal,
     .modal-box {
       position: fixed;
       left: 50%;
       top: 50%;
       transform: translate(-50%, -50%);
       box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
     }
     .${mainClass}.active .show-modal {
       display: none;
     }
     
     .${mainClass} .modal-box {
       display: flex;
       flex-direction: column;
       align-items: center;
       max-width: ${frameWidth};
       width: 100%;
       padding: 30px 20px;
       border-radius: 24px;
       background-color: #fff;
       opacity: 0;
       pointer-events: none;
       transition: all 0.3s ease;
       transform: translate(-50%, -50%) scale(1.2);
     }
     .${mainClass}.active .modal-box {
       opacity: 1;
       pointer-events: auto;
       transform: translate(-50%, -50%) scale(1);
     }
     .${mainClass} .modal-box i {
       font-size: 70px;
       color: ${logoColor};
     }
     .${mainClass} .modal-box h2 {
       margin-top: 20px;
       font-size: 25px;
       font-weight: 500;
       color: #333;
     }
     .${mainClass} .modal-box h3 {
       font-size: 16px;
       font-weight: 400;
       color: #333;
       text-align: center;
     }
     .${mainClass} .modal-box .buttons {
       margin-top: 25px;
     }
     .${mainClass} .modal-box button {
       padding: 12px 12px;
       margin: 0 10px;
       width: 110px;
     }
     .modal-hide {
      display: none !important;
     }
     
    .${mainClass} .${messageBoxCustomClass} {
      height: 10rex;
      width: 283px;
      border-radius: 0.5rem;
      padding: 5px 12px;
      border: 1.9px solid black;
    }

    .${mainClass} .${messageBoxCustomClass}-container {
      display: flex;
      flex-direction: column;
      margin-top: 20px !important;
      gap: 5px;
    }

    .${mainClass} .${messageBoxCustomClass}-error-message-label {
      color: red !important;
      font-size: 12px;
      font-weight:300;
      align-self: flex-start;
      margin-left: 4px;
    }

   `;
   document.head.appendChild(style);

   // Create section element and append HTML to it
   const section = document.createElement('section');
   section.classList.add(`${mainClass}`, 'active', 'modal-hide');
   section.innerHTML = `
     <div class="modal-box ${hideIfForReciept}">
      <!-- fa-circle-check -->
       <i class="${custoMIcon}"></i>
       <h2>${title}</h2>
       <h3>${message}</h3>

       <div class='${messageBoxCustomClass}-container ${displayBox}'>
          <textarea  placeholder="${messageBoxPlaceHolder} *" maxlength="500" class="${messageBoxCustomClass}"></textarea>
          <div class="${messageBoxCustomClass}-error-message-label modal-hide">Input a valid ${messageBoxPlaceHolder}.</div>
       </div>

       <div class="buttons">
         <button class="custom-modal-confirm-button ${leftClass}">${leftButtonText}</button>
         <button class="custom-modal-cancel-button ${rightClass}">${RightButtonText}</button>
       </div>
     </div>

     <div class="modal-box ${showIfForReciept}">
        ${customHTMLElements}
     </div>
   `;
   document.body.insertBefore(section, document.body.firstChild);
}

// redirect to landing page
toLandingPage.forEach(function(element) {
  element.addEventListener("click", function(){
    window.location.href = '../landingPage/index.php'
  });
});

// logout session
if (logoutButton) { logoutButton.onclick = () => {
    document.querySelector('.logout-confirmation').classList.remove('modal-hide');      
  };
}
document.querySelector('.cancel-logout').addEventListener('click', function(){
  document.querySelector('.logout-confirmation').classList.add('modal-hide');
})
document.querySelector('.logout-proceed').addEventListener('click', function(){
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
})

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
