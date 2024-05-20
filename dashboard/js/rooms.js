let fileInput = document.getElementById('fileInput');
let addImages = document.getElementById('addImages');
let addRoomBtnDone = document.getElementById('addRoomBtnDone');
let imagePreviewContainer = document.getElementById('imagePreviewContainer');
let newRate = document.getElementById('newRate');
let maximumCapacity = document.getElementById('maximumCapacity');
let publishedRate = document.getElementById('publishedRate');
let rateType = document.getElementById('rateType');
let rooName = document.getElementById('rooName');
let roomDescription = document.getElementById('roomDescription');
let showOtherRate = document.getElementById('showOtherRate');
let closeOtherRate = document.getElementById('closeOtherRate');
let addOtherRateContainer = document.querySelector(".addOtherRate-container");
let addRoomBtn = document.getElementById('addRoomBtn');
let closeAddRoomsModal = document.getElementById('closeAddRoomsModal');
let modal = document.querySelector('.details-modal');
let imagePreviewContainerError = document.getElementById('imagePreviewContainer-error');
let checkInDateInput = document.getElementById('check-in-date');
let checkOutDateInput = document.getElementById('check-out-date');
let searchAvailableRooms = document.getElementById('searchAvailableRooms');
let bookNOwBtn = document.getElementById('bookNOwBtn');
let imgCount = 0;
let selectedFiles = [];
let otherRateObject = [];
let imageLink = [];
let otherRateCount = 0;
let userRole = userOrAdminDetails.role;
const coursesBoxContainer = document.querySelector('.courses-boxes');

// select image button
if(addImages) {
  addImages.addEventListener('click', function() {
    fileInput.value = "";
    fileInput.click();
 });
}

function checkForSearchParams() {
    // Create a URLSearchParams object with the current URL
    let urlParams = new URLSearchParams(window.location.search);

    // Check if the check-in date parameter exists
    if (urlParams.has('checkInDateParam')) {
        let checkInDate = urlParams.get('checkInDateParam');
        checkInDateInput.value = checkInDate;
    } else {
        console.log("Check-in date parameter not found");
    }

    // Check if the check-out date parameter exists
    if (urlParams.has('checkOutDateParam')) {
        let checkOutDate = urlParams.get('checkOutDateParam');
        checkOutDateInput.value = checkOutDate;
    } else {
        console.log("Check-out date parameter not found");
    }
}

// Ensure the function is called as soon as the HTML document is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    checkForSearchParams()
    showAllrooms()
});

// Show all rooms
function showAllrooms() {
    const url = "controller/roomsController.php";
    const data = {
        queryAllRooms: true,
        checkInDate: checkInDateInput.value,
        checkOutDate: checkOutDateInput.value
    };

    handlePostRequest(url,data )
    .then((response) => {
        // console.log('response: ', response)
        var jsonResponse = JSON.parse(response);
        if(jsonResponse.rooms.length > 0) {
            jsonResponse.rooms.forEach(room => {
                displayRooms(room);
            });

            if (coursesBoxContainer) {
                const children = coursesBoxContainer.querySelectorAll('.courses-box');
                if (children.length === 1 && window.innerWidth > 1049) {
                    coursesBoxContainer.classList.add('width-800px');
                }
                else{
                    coursesBoxContainer.classList.remove('width-800px');
                }
            }
        }
        else {
            alertMessage('There are no rooms available at this time.', 'warning', 3000);
            console.log(response)
        }
    })
    .catch((error) => {
        alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
        console.log("Error:", error);
    });
}

// display rooms
function displayRooms(room) {

    // console.log(`Room ID: ${room.roomId}`);
    // console.log(`Room Name: ${room.roomName}`);
    // console.log(`Room Max Capacity: ${room.roomMaxCap}`);
    // console.log(`Room Description: ${room.roomDescription}`);
    // console.log(`Room Published Rate: ${room.roomPublishedRate}`);
    // console.log(`Image Link: ${room.imageLink}`);
    // console.log(`Other Rate: ${room.otherRate}`);
    // console.log(`Other Rate Amount: ${room.otherRateAmount}`);
    // console.log('---------------------');
    let publishedAmount = room.roomPublishedRate;

    if (!isNaN(parseFloat(publishedAmount))) {
        let numericAmount = parseFloat(publishedAmount);
        let formattedCurrency = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(numericAmount);
        publishedAmount = formattedCurrency;
    }
   
    const courseBoxHTML = `
        <div class="courses-box" id="courses-box-${room.roomId}">
            <div class="card-image">
                <img src="https://h-img1.cloudbeds.com/uploads/199021/cds-1_1_thumb~~65c4477f47757.png" class="hotel-room-logo" style="background-color: white;" alt="" />
                <img src="${room.imageLink}" alt="" />
            </div>
            <div class="courses-card-body">
                <h4>${room.roomName}</h4>
                <p>${room.roomDescription}</p>
                <span id="viewDetailsBtn-${room.roomId}" class="clickable costa-btn-b">View Room Details</span>
            </div>
            <div class="courses-card-footer">
                <span><i class="fa-regular fa-user"></i>${room.roomMaxCap}</span>
                <span>${publishedAmount}</span>
            </div>
        </div>
    `;

    // Create a container for the new HTML
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = courseBoxHTML;

    // Select the first child of the temporary container
    const newCourseBox = tempDiv.firstElementChild;

    // Append the new course box to the container with class 'courses-boxes'
    const coursesBoxesContainer = document.querySelector('.courses-boxes');
    if (coursesBoxesContainer) {
        coursesBoxesContainer.appendChild(newCourseBox);
    }
}
  
// event for previewig selected image
fileInput.addEventListener('change', function() {
    let files = this.files;
    const previewContainer = document.getElementById('imagePreviewContainer');

    for (let i = 0; i < files.length; i++) {

        const file = files[i];

        // Check if the file is an image
        imagePreviewContainerError.classList.add('display-none')
        if (!file.type.startsWith('image/')) {
            imagePreviewContainerError.classList.remove('display-none')
            displayError(imagePreviewContainer, "Selected file is not an image. Please select an image file.");
            continue; // Skip non-image files
        }
        
        let reader = new FileReader();

        reader.onload = function() {
            const imageContainer = document.createElement('div');
            imageContainer.classList.add('imageContainer');
            imageContainer.id = 'imageContainer-'+ imgCount;
            if(files.length > 1)
            {
            imageContainer.id = 'imageContainer-'+ i;
            }

            const image = new Image();
            image.src = reader.result;
            image.classList.add('previewImage');

            const deleteButton = document.createElement('span');
            deleteButton.classList.add('deleteButton','fa-regular', 'fa-circle-xmark');
            imageContainer.appendChild(image);
            imageContainer.appendChild(deleteButton);

            previewContainer.appendChild(imageContainer);

            
            selectedFiles.push({
            id:imageContainer.id,
            file:file
            });
            // console.log(selectedFiles);
        }

        reader.readAsDataURL(file);
        displayError(imagePreviewContainer, "");
    }

    imgCount ++;
});

// event for searching available rooms
searchAvailableRooms.addEventListener('click', function() {
    if(searchAvailableRoomsValidator('fromSearch')) {
        // Construct the URL with parameters
        let url = `rooms.php?checkInDateParam=${checkInDateInput.value}&checkOutDateParam=${checkOutDateInput.value}`;
        // Redirect to the constructed URL
        window.location.href = url;
    }
})

// book a room event
bookNOwBtn.addEventListener('click', function() {
    if(searchAvailableRoomsValidator('fromBooknow')) {
        alert("You can book")
    }
})

//validate the search bar for searching available rooms
function searchAvailableRoomsValidator(btnFrom) {
    let isValid = true;

    if(!checkInDateInput.value) {
        displayError(checkInDateInput, "Check-in date is invalid");
        isValid = false;
    }
    else {
        displayError(checkInDateInput, '');
    }

    if(!checkOutDateInput.value) {
        displayError(checkOutDateInput, "Check-out date is invalid");
        isValid = false;
    }
    else {
        displayError(checkOutDateInput, '');
    }
    
    if((checkOutDateInput.value != '' && checkInDateInput.value != '') && ((checkInDateInput.value > checkOutDateInput.value) || (checkInDateInput.value === checkOutDateInput.value))) {
        displayError(checkInDateInput, "Check-in date is invalid");
        displayError(checkOutDateInput, "Check-out date is invalid");
        isValid = false;
    }

    if(btnFrom === 'fromSearch') {
        if((!checkOutDateInput.value && !checkInDateInput.value)){
            isValid = true;
            displayError(checkInDateInput, '');
            displayError(checkOutDateInput, '');
        }
    }

    return isValid;
}
  
// Event delegation for delete buttons
document.getElementById('imagePreviewContainer').addEventListener('click', function(event) {
    if (event.target.classList.contains('deleteButton')) {
        const imageContainer = event.target.parentElement;
        let imageContainerId = imageContainer.id;

        let indexToDelete = selectedFiles.findIndex(function(rateObject) {
        return rateObject.id === imageContainerId;
        });
        selectedFiles.splice(indexToDelete, 1);

        // console.log('Remained files: ', selectedFiles);

        imageContainer.remove();
    }
});
  
// Event for adding rooms and it's additional details
addRoomBtnDone.addEventListener('click', async function() {
    if(validateAddRoomForm()) {
         resetErrors("form-a")
       // Call the function to upload images
        uploadImageToFirebase().then(() => {
            if (imageLink.length > 0) {
                saveRoomDetailsToDatabase()
            }
        }).catch(error => {
            console.error('Error uploading images:', error);
        });
    }
});

// Save Romm Details to the Database
function saveRoomDetailsToDatabase() {
    let otherRatesToPass = null;
    if(otherRateObject.length > 0){
        otherRatesToPass = otherRateObject;
    }
    const url = "controller/roomsController.php";
    const data = {
        saveRoomDetails: true,
        imageLink: imageLink,
        otherRates: otherRatesToPass,
        roomName: rooName.value,
        roomDescription: roomDescription.value,
        maximumCapacity: maximumCapacity.value,
        publishedRate: convertCurrencyStringToNumber(publishedRate.value)
    };

    handlePostRequest(url,data )
    .then((response) => {

        // console.log('response: ', response)
        var jsonResponse = JSON.parse(response);
        if(jsonResponse.roomId && jsonResponse.roomInserted === true) {
            alertMessage(`Room ${jsonResponse.roomName} has been added successfully`, 'success', 3000);
            setTimeout(function(){
                window.location.reload(true);
            },3000)
        }
        else {
            alertMessage('Something went wrong, Error: ' + response, 'error', 3000);
            console.log(response)
        }
    })
    .catch((error) => {
        alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
        console.log("Error:", error);
    });
}

// Attach blur event listener to the input field
newRate.addEventListener('blur', function() {
    dynamicInputFieldCurrencyFormatter(newRate)
});

// Attach blur event listener to the input field
publishedRate.addEventListener('blur', function() {
    dynamicInputFieldCurrencyFormatter(publishedRate)
});

// Attach input event listener to the input field
newRate.addEventListener('input', function() {
    dynamicCurrencyOnlyInput(newRate);
});

// Event for auto changing the format of published rate
publishedRate.addEventListener('input', function() {
    dynamicCurrencyOnlyInput(publishedRate);
});
  
// add other rates event
document.getElementById('addRateNow').addEventListener('click', function() {
    if (validateOtherRatesForm()) {
        resetErrors("form-b")
        let rateTypeValue = capitalizeFirstLetter(rateType.value);
        let newRateValue = parseCurrency(newRate.value);
        newRateValue = formatCurrency(newRateValue);

        let otherRateCard = document.createElement('div');
        otherRateCard.classList.add('otherRate-card');
        otherRateCard.id = `otherRate-card-${otherRateCount}`;

        otherRateObject.push({
            rateType: rateTypeValue,
            newRateValue: convertCurrencyStringToNumber(newRateValue),
            id: otherRateCard.id
        });

        // console.log(otherRateObject);

        let rateTypeSpan = document.createElement('span');
        rateTypeSpan.id = `ratype-${otherRateCount}`;
        rateTypeSpan.textContent = rateTypeValue;

        let colonSpan = document.createElement('span');
        colonSpan.textContent = ' : ';

        let newRateValueSpan = document.createElement('span');
        newRateValueSpan.id = `newRateValue-${otherRateCount}`;
        newRateValueSpan.textContent = newRateValue;

        let removeOtherRateCard = document.createElement('span');
        removeOtherRateCard.classList.add('fa-regular', 'fa-circle-xmark');
        removeOtherRateCard.style.marginLeft = '10px';
        removeOtherRateCard.style.cursor = 'pointer';

        otherRateCard.appendChild(rateTypeSpan);
        otherRateCard.appendChild(colonSpan);
        otherRateCard.appendChild(newRateValueSpan);
        otherRateCard.appendChild(removeOtherRateCard);

        document.querySelector('.newAddedRate-container').appendChild(otherRateCard);

        otherRateCount++;
        rateType.value = null;
        newRate.value = null;

        removeOtherRateCard.addEventListener('click', function() {
            otherRateCard.remove();
            let indexToDelete = otherRateObject.findIndex(function(rateObject) {
                return rateObject.id === otherRateCard.id;
            });
            otherRateObject.splice(indexToDelete, 1);
        });
    }
});
  
// validate the add room form
function validateAddRoomForm() {

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

    if (selectedFiles.length < 1) {
        imagePreviewContainerError.classList.remove('display-none')
        displayError(imagePreviewContainer, "No image selected, please select at least one");
        isValid = false;
    }

    return isValid;
}
  
//validate other rates
function validateOtherRatesForm() {

    let isValid = true;

    if(!rateType.value) {
        displayError(rateType, "Type cannot be empty");
        isValid = false;
    }
    else {
        displayError(rateType, '');
    }

    if(!newRate.value) {
        displayError(newRate, "New Rate cannot be empty");
    isValid = false;
    }
    else {
        displayError(newRate, '');
    }

    return isValid;
}
   
// Upload all images to Firebase
async function uploadImageToFirebase() {
    
    const uploadPromises = selectedFiles.map(selected => {
        const storageRef = storage.ref('images/' + selected.file.name);
        const uploadTask = storageRef.put(selected.file);
        imageLink = [];

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

// show other rates form
showOtherRate.addEventListener('click', function(){
    showOtherRate.classList.add('display-none');
    closeOtherRate.classList.remove('display-none');
    addOtherRateContainer.classList.remove('display-none');
});

// close other rates form
closeOtherRate.addEventListener('click', function(){
    showOtherRate.classList.remove('display-none');
    closeOtherRate.classList.add('display-none');
    addOtherRateContainer.classList.add('display-none');
})

  // to show the add room modal
if (addRoomBtn) {
    addRoomBtn.addEventListener('click', function() {
      modal.classList.add('show');
    });
  }
  
// to cloase the add room modal
if (closeAddRoomsModal) {
  closeAddRoomsModal.addEventListener('click', function() {
    modal.classList.remove('show'); // add the show class to display the modal
 });
}