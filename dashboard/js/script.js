let getBarItem = document.querySelector(".bar-item");
let getSideBar = document.querySelector(".sidebar");
let getXmark = document.querySelector(".xmark");
let getPageContent = document.querySelector(".page-content");
let getLoader = document.querySelector(".loader");
let getToggle = document.querySelectorAll(".toggle");
let getHeart = document.querySelector(".heart");
let getSidebarLink = document.querySelectorAll(".sidebar-link");
let activePage = window.location.pathname;
let getSideBarStatus = false;
let logoutButton = document.querySelector(".logout-button");
let customer = document.querySelectorAll(".customer");
let admin = document.querySelectorAll(".admin");
let toLandingPage = document.querySelectorAll(".toLandingPage");
let addRoomBtn = document.getElementById('addRoomBtn');
let closeAddRoomsModal = document.getElementById('closeAddRoomsModal');
let modal = document.querySelector('.details-modal');
let addImages = document.getElementById('addImages');
let fileInput = document.getElementById('fileInput');
let imagePreviewContainer = document.getElementById('imagePreviewContainer');
let addRoomBtnDone = document.getElementById('addRoomBtnDone');
let rooName = document.getElementById('rooName');
let roomDescription = document.getElementById('roomDescription');
let maximumCapacity = document.getElementById('maximumCapacity');
let publishedRate = document.getElementById('publishedRate');
let showOtherRate = document.getElementById('showOtherRate');
let closeOtherRate = document.getElementById('closeOtherRate');
let addOtherRateContainer = document.querySelector(".addOtherRate-container");
let storage;
let imgCount = 0;
const selectedFiles = [];
const coursesBoxContainer = document.querySelector('.courses-boxes');

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

// only for addRoom modal
if(fileInput) {

  // select image button
  if(addImages) {
    addImages.addEventListener('click', function() {
      fileInput.value = "";
      fileInput.click();
    });
  }

  // event for previewig selected image
  fileInput.addEventListener('change', function() {
    files = this.files;
    const previewContainer = document.getElementById('imagePreviewContainer');

    for (let i = 0; i < files.length; i++) {

          const file = files[i];
          selectedFiles.push(file);
          console.log(selectedFiles);
          let reader = new FileReader();

          reader.onload = function() {
              const imageContainer = document.createElement('div');
              imageContainer.classList.add('imageContainer');

              const image = new Image();
              image.src = reader.result;
              image.classList.add('previewImage');

              const deleteButton = document.createElement('span');
              deleteButton.classList.add('deleteButton');
              deleteButton.id = imgCount;
              deleteButton.innerText = 'x';

              imageContainer.appendChild(image);
              imageContainer.appendChild(deleteButton);

              previewContainer.appendChild(imageContainer);
          }

          reader.readAsDataURL(file);
          imgCount = i;
      }
  });

  // Event delegation for delete buttons
  document.getElementById('imagePreviewContainer').addEventListener('click', function(event) {
    if (event.target.classList.contains('deleteButton')) {
        const imageContainer = event.target.parentElement;
        let indexNo = parseInt(event.target.id);

        const index = indexNo;
        if (index > -1) {
            selectedFiles.splice(index, 1);
            imgCount --;
        }

        imageContainer.remove();
    }
  });

  addRoomBtnDone.addEventListener('click', function() {
    validateAddRoomForm();
  });

  // validate the add room form
  function validateAddRoomForm() {
    resetErrors();
    let isValid = true;

    if (!rooName.value) {
        displayError(rooName, "Room Name cannot be empty");
        isValid = false;
    }
    else {
        displayError(rooName, '');
    }

    if (!roomDescription.value) {
        displayError(roomDescription, "Room Discription cannot be empty");
        isValid = false;
    }
    else {
        displayError(roomDescription, '');
    }

    if (!maximumCapacity.value) {
        displayError(maximumCapacity, "Maximum Capacity cannot be empty");
        isValid = false;
    }
    else {
        displayError(maximumCapacity, '');
    }

    if (!publishedRate.value) {
        displayError(publishedRate, "Published Rate cannot be empty");
        isValid = false;
    }
    else {
        displayError(publishedRate, '');
    }
  

    return isValid;
  }
  // validate the add room form
  
  //upload all images to firebase
  function uploadImageToFirebase() {
  selectedFiles.forEach(file => {
    const storageRef = storage.ref('images/' + file.name);
    const uploadTask = storageRef.put(file);
  
    uploadTask.on('state_changed', 
        (snapshot) => {
            // Observe state change events such as progress, pause, and resume
            const progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
            console.log('Upload is ' + progress + '% done');
        }, 
        (error) => {
            // Handle unsuccessful uploads
            console.error('Upload failed:', error);
        }, 
        () => {
            // Handle successful uploads on complete
            uploadTask.snapshot.ref.getDownloadURL().then((downloadURL) => {
                console.log('File available at', downloadURL);
            });
        }
    );
  });
  }

  showOtherRate.addEventListener('click', function(){
    showOtherRate.classList.add('display-none');
    closeOtherRate.classList.remove('display-none');
    addOtherRateContainer.classList.remove('display-none');
  });

  closeOtherRate.addEventListener('click', function(){
    showOtherRate.classList.remove('display-none');
    closeOtherRate.classList.add('display-none');
    addOtherRateContainer.classList.add('display-none');
  })

}
// only for addRoom modal

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
//add or display error on inputs if does not meet the validation

//remove validation error on inputs
function resetErrors() {
  const errorElements = document.querySelectorAll(".error");
  errorElements.forEach(function(errorElement) {
      errorElement.innerText = "";
      errorElement.classList.remove("error");
  });
}
//remove validation error on inputs

// event for showing whats for admin and whats for costumer
window.addEventListener('load', function() {

  if( typeof userOrAdminDetails !== 'undefined' && Object.keys(userOrAdminDetails).length > 0){
    console.log(userOrAdminDetails);

    if(userOrAdminDetails.role === 'customer'){
      console.log('Active: Customer');
      showOnlyCustomerUi();
    }
    else {
      console.log('Active: Admin');
      showOnlyAdminUi();
    }
  }
  else {
    console.log(' The variable = userOrAdminDetails is not defined.');
  }

  if (coursesBoxContainer) {
    
    const children = coursesBoxContainer.querySelectorAll('.courses-box');

    if (children.length === 1 && window.innerWidth > 1049) {
        
        coursesBoxContainer.classList.add('width-800px');
    }
}

});

function showOnlyCustomerUi() {

  admin.forEach(function(element) {
    element.classList.add("display-none");
  });

  customer.forEach(function(element) {
    element.classList.remove("display-none");
  });

}

function showOnlyAdminUi() {

  admin.forEach(function(element) {
    element.classList.remove("display-none");
  });

  customer.forEach(function(element) {
    element.classList.add("display-none");
  });
  
}
// event for showing whats for admin and whats for costumer

// to show the add room modal
if (addRoomBtn) {
  addRoomBtn.addEventListener('click', function() {
    modal.classList.add('show');
  });
}
// to show the add room modal

// to cloase the add room modal
if (closeAddRoomsModal) {
  closeAddRoomsModal.addEventListener('click', function() {
    modal.classList.remove('show'); // add the show class to display the modal
  });
}
// to cloase the add room modal

// redirect to landing page
toLandingPage.forEach(function(element) {
  element.addEventListener("click", function(){
    window.location.href = '../landingPage/index.php'
  });
});
// redirect to landing page

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
           window.location.reload(true);;
        }
        else {
            console.log(response)
        }
    })
    .catch((error) => {
        console.log("Error:", error);
    });
      
  };
}
// logout session

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
// dynamic post request

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
// others default 