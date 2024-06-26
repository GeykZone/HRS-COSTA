let fileInput = document.getElementById('fileInput');
let fileInputSingleBookEvidence = document.getElementById('fileInputSingleBookEvidence');
let addImages = document.getElementById('addImages');
let addImagesEvidence = document.getElementById('addImagesEvidence');
let addRoomBtnDone = document.getElementById('addRoomBtnDone');
let imagePreviewContainer = document.getElementById('imagePreviewContainer');
let imagePreviewContainerSingleEvidence = document.getElementById('imagePreviewContainerSingleEvidence')
let newRate = document.getElementById('newRate');
let maximumCapacity = document.getElementById('maximumCapacity');
let publishedRate = document.getElementById('publishedRate');
let rateType = document.getElementById('rateType');
let rooName = document.getElementById('rooName');
let roomDescription = document.getElementById('roomDescription');
let roomQuantity = document.getElementById('roomQuantity');
let addOtherRateContainer = document.querySelector(".addOtherRate-container");
let addRoomBtn = document.getElementById('addRoomBtn');
let closeAddRoomsModal = document.getElementById('closeAddRoomsModal');
let closeroomDetails = document.getElementById('closeroomDetails');
let singleRoomBookingModalCLose = document.getElementById('singleRoomBookingModalCLose');
let addRoomModal = document.getElementById('addRoomModal');
let roomDetailsModal = document.getElementById('roomDetails');
let singleRoomBookingModalId = document.getElementById('singleRoomBookingModalId');
let imagePreviewContainerError = document.getElementById('imagePreviewContainer-error');
let imagePreviewContainerSingleEvidenceError = document.getElementById('imagePreviewContainerSingleEvidence-error')
let checkInDateInput = document.getElementById('check-in-date');
let checkOutDateInput = document.getElementById('check-out-date');
let searchAvailableRooms = document.getElementById('searchAvailableRooms');
let bookNOwBtn = document.getElementById('bookNOwBtn');
let addRateNowBtn = document.getElementById('addRateNow');
let addAmenityNowBtn = document.getElementById('addAmenityNow');
let amenityInput = document.getElementById('amenity');
let imagePreviewContainerDelete = document.getElementById('imagePreviewContainer');
let cardWrapper = document.querySelector('.card-wrapper');
let cardWrapperEvidence = document.querySelector('.card-wrapper-evidence');
let selectedPrice = document.getElementById('selectedPrice');
let singleRoomBookingModalBtnDone = document.getElementById('singleRoomBookingModalBtnDone');
let singleRoomPaymentPicklist = document.getElementById('single-room-payment-picklist');
let sRoomQuantity = document.getElementById('sRoomQuantity');
let sFullName = document.getElementById('sFullName');
let sCompleteAddress = document.getElementById('sCompleteAddress');
let sAmountToPay = document.getElementById('sAmountToPay');
let sContactInfo = document.getElementById('sContactInfo');
let submitChanges = document.getElementById('submitChanges');
let checkInDateParam = null;
let checkOutDateParam = null;
let imgCount = 0;
let imgCountSingleBookEvidence = 0;
let selectedFiles = [];
let selectedFilesSingleBook = [];
let otherRateObject = [];
let amenityObject = [];
let imageLink = [];
let singleBookingimageLink = [];
let otherRateCount = 0;
let amenityCount = 0
let swiper;
let singleBookingPaymentMethod = null;
let singleBookingRoomId;
const coursesBoxContainer = document.querySelector('.courses-boxes');

function initializeSwiper(className, swiperWrapperClass) {
  if (swiper) {
    swiper.destroy(true, true);
  }

  swiper = new Swiper(`.${className}`, {
    slidesPerView: 4,
    spaceBetween: 20,
    sliderPerGroup: 4,
    loop: shouldLoop(swiperWrapperClass),
    centerSlide: "true",
    fade: "true",
    grabCursor: "true",
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
      dynamicBullets: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },

    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      520: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 3,
      },
      1000: {
        slidesPerView: 4,
      },
    },
  });

  let SwiperButtonLock = document.querySelectorAll('.swiper-button-lock');

  if(shouldLoop(swiperWrapperClass)) {
    SwiperButtonLock.forEach(btn => {
        btn.classList.remove('swiper-button-lock');
    });
  }
}

function shouldLoop(swiperWrapperClass) {
  // Get the number of slides within the swiper wrapper
  let swiperWrapper = document.querySelector(`.${swiperWrapperClass}`);
  let numSlides = swiperWrapper.querySelectorAll('.card').length;
  
  return numSlides >= 4;
}

// select image button
if(addImages) {
  addImages.addEventListener('click', function() {
    fileInput.value = "";
    fileInput.click();
 });
}

// add image evidence button
if(addImagesEvidence) {
    addImagesEvidence.addEventListener('click', function() {
        fileInputSingleBookEvidence.value = "";
        fileInputSingleBookEvidence.click();
   });
}

// check for date search field
function checkForSearchParams() {
    // Create a URLSearchParams object with the current URL
    let urlParams = new URLSearchParams(window.location.search);

    // Check if the check-in date parameter exists
    if (urlParams.has('checkInDateParam')) {
        let checkInDate = urlParams.get('checkInDateParam');
        checkInDateParam = checkInDate;
        checkInDateInput.value = checkInDate;
    } else {
        console.log("Check-in date parameter not found");
    }

    // Check if the check-out date parameter exists
    if (urlParams.has('checkOutDateParam')) {
        let checkOutDate = urlParams.get('checkOutDateParam');
        checkOutDateParam = checkOutDate;
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

            // Add event listeners to all elements with the class 'roomDetailsViewBtn'
            let roomDetailsViewBtn = document.querySelectorAll('.roomDetailsViewBtn')
            if (roomDetailsViewBtn) {
                roomDetailsViewBtn.forEach(function(element) {
                    element.addEventListener('click', handleRoomDetailsViewBtnClick);
                });
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

    let images = JSON.parse(room.imageLink);
    let mainImage = images[0].Link;
    let otherRate = JSON.parse(room.otherRate);

    // console.log(otherRate)

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
    publishedAmount = dynamicCurrencyforTxtValue(publishedAmount);
    let totalAvailableRooms = parseInt(room.roomQuantity) - parseInt(room.totalCheckInQuantity);
   
    const courseBoxHTML = `
        <div class="courses-box" id="courses-box-${room.roomId}">
            <div class="card-image">
                <img src="https://h-img1.cloudbeds.com/uploads/199021/cds-1_1_thumb~~65c4477f47757.png" class="hotel-room-logo" style="background-color: white;" alt="" />
                <img src="${mainImage}" alt="" />
            </div>
            <div class="courses-card-body">
                <div class="roomCardHeader-Container">
                    <h4>${room.roomName}</h4>
                    <span class="roomQuantityInShowAllRooms"><h6>${totalAvailableRooms} Available</h6></span>
                </div>
                <p>${room.roomDescription}</p>
                <select id="select-${room.roomId}" class="pick-list selectAvailableRate"  placeholder="Select Available Rates...">  
                </select>
                <br>
                <span id="viewDetailsBtn-${room.roomId}" class="clickable roomDetailsViewBtn costa-btn-b">View Room Details</span>
            </div>
            <div class="courses-card-footer">
                <span><i class="fa-regular fa-user"></i>${room.roomMaxCap}</span>
                <span id="dispayAmount-${room.roomId}" >${publishedAmount}</span>
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

    if ($(`#select-${room.roomId}`)[0].selectize) {
        $(`#select-${room.roomId}`)[0].selectize.destroy();
    }

    let selectize = $(`#select-${room.roomId}`).selectize({
        options: [],
        optgroups: [
            { value: 'availableRates', label: 'Available Rates' }
        ],
        optgroupField: 'optgroup',
        labelField: 'text',
        valueField: 'value',
        searchField: ['text'],
        render: {
            optgroup_header: function (data, escape) {
                return '<div class="optgroup-header">' + escape(data.label) + '</div>';
            }
        }
    });
    let selectizeInstance = selectize[0].selectize;

    // Create the new options array
    let newOptions = [];

    newOptions.push({
        value: room.roomPublishedRate,
        text: `Published Rate :  ${dynamicCurrencyforTxtValue(room.roomPublishedRate)}`,
        optgroup: 'availableRates'
    });

    // Iterate through the rates array
    if(otherRate){
        otherRate.forEach(rate => {
            newOptions.push({
                value: rate.amount,
                text: `${rate.type} : ${dynamicCurrencyforTxtValue(rate.amount)}`,
                optgroup: 'availableRates'
            });
        })
    };

    // Add new options to the Selectize instance
    selectizeInstance.addOption(newOptions);

    // Refresh the options in the dropdown
    selectizeInstance.refreshOptions(false);

    // Get the value of the first option
    let firstOption = selectizeInstance.options[Object.keys(selectizeInstance.options)[newOptions.length - 1]].value;
    // Add the first option
    selectizeInstance.addItem(firstOption);

    // Add an event listener for the "change" event
    selectizeInstance.on('change', function(value) {
        document.getElementById(`dispayAmount-${room.roomId}`).innerText = dynamicCurrencyforTxtValue(value);
    });
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

// file input single book evidence event listener
fileInputSingleBookEvidence.addEventListener('change', function() {
    let files = this.files;
    const previewContainer = document.getElementById('imagePreviewContainerSingleEvidence');

    for (let i = 0; i < files.length; i++) {

        const file = files[i];

        // Check if the file is an image
        imagePreviewContainerSingleEvidenceError.classList.add('display-none')
        if (!file.type.startsWith('image/')) {
            imagePreviewContainerSingleEvidenceError.classList.remove('display-none')
            displayError(imagePreviewContainerSingleEvidence, "Selected file is not an image. Please select an image file.");
            continue; // Skip non-image files
        }
        
        let reader = new FileReader();

        reader.onload = function() {
            const imageContainer = document.createElement('div');
            imageContainer.classList.add('imageContainer');
            imageContainer.id = 'imageContainer-'+ imgCountSingleBookEvidence;
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

            
            selectedFilesSingleBook.push({
            id:imageContainer.id,
            file:file
            });
            // console.log(selectedFiles);
        }

        reader.readAsDataURL(file);
        displayError(imagePreviewContainerSingleEvidence, "");
    }

    imgCountSingleBookEvidence ++;
})

// event for searching available rooms
searchAvailableRooms.addEventListener('click', function() {
    if(searchAvailableRoomsValidator('fromSearch')) {
        // Construct the URL with parameters
        let url = `rooms.php?checkInDateParam=${checkInDateInput.value}&checkOutDateParam=${checkOutDateInput.value}`;
        // Redirect to the constructed URL
        window.location.href = url;
    }
})

// bulk booking room event
bookNOwBtn.addEventListener('click', function() {
    if(searchAvailableRoomsValidator('fromBooknow')) {
        alert("Bulk booking is currently under development.")
    }
})

// single booking room event
singleRoomBookingModalBtnDone.addEventListener('click', function() {
    if(singleBookingPaymentFormValidation()) {
        // Call the function to upload images
        if(singleBookingPaymentMethod != 'Manual'){
            uploadImageToFirebase(selectedFilesSingleBook, singleBookingimageLink).then(() => {
             if (singleBookingimageLink.length > 0) {
                sendReservationRequest();
             }
         }).catch(error => {
             alertMessage('Error uploading images, Error: ' + error, 'error', 5000);
             console.error('Error uploading images:', error);
         });}
         else {
            sendReservationRequest();
         }
    }
});

// send booking request for single room booking
function sendReservationRequest() {
    // alertMessage('Booking sent successfully. Please wait for approval.', 'success', 3000);
    const twoHoursFromNow = getTwoHoursFromNow();
    let sTotalPay =  document.getElementById('sTotalPay')
    let selectedSingleRoomName = document.getElementById('selectedSingleRoomName');

    const url = "controller/roomsController.php";
    const data = {
        sendReservationRequest: true,
        singleBookingimageLink: singleBookingimageLink,
        quantity: sRoomQuantity.value,
        paidAmount: convertCurrencyStringToNumber(sAmountToPay.value),
        totalAmount: convertCurrencyStringToNumber(sTotalPay.innerText),
        roomId: singleBookingRoomId,
        userId: userOrAdminDetails.userId,
        queueDateTime: twoHoursFromNow,
        paymentMethod: singleBookingPaymentMethod,
        checkInDate: checkInDateParam,
        checkOutDate: checkOutDateParam,
        customerfullName: sFullName.value,
        customerCompleteAddress: sCompleteAddress.value,
        customerContactInfo: sContactInfo.value
    };

    handlePostRequest(url,data )
    .then((response) => {
        let msg;
        let endMsg;

        endMsg = `We will notify you through the provided contact info if your reservation is successful or rejected.`
        if(singleBookingPaymentMethod === 'Manual') {
            msg = `\nExpected time to pay manually ${twoHoursFromNow}`
            endMsg = `We will wait for you until ${twoHoursFromNow} to come and pay your reservation.`
        }

        // console.log('response: ', response)
        var jsonResponse = JSON.parse(response);
        if(jsonResponse.reserve === 'success') {

            singleRoomBookingModalId.classList.remove('show');
            
            Swal.fire({
                title: 'Payment Due Details',
                html: `
                    <p><strong>Room Name:</strong> ${selectedSingleRoomName.innerText}</p>
                    <p>${msg}</p>
                    <p><strong>Payment Method:</strong> ${singleBookingPaymentMethod}</p>
                    <p><strong>Check-in Date:</strong> ${checkInDateParam}</p>
                    <p><strong>Check-out Date:</strong> ${checkOutDateParam}</p>
                    <p><strong>Paid Amount (To be confirmed):</strong> ${sAmountToPay.value}</p>
                    <p><strong>Payable Amount:</strong> ${sTotalPay.innerText}</p>
                    <p><strong>Reservation Status:</strong> Pending</p>
                    <br>
                    <p><strong>Customer Details:</strong></p>
                    <p><strong>Full Name:</strong> ${sFullName.value}</p>
                    <p><strong>Complete Address:</strong> ${sCompleteAddress.value}</p>
                    <p><strong>Contact Info (Email / Phone Number):</strong> ${sContactInfo.value}</p>
                    <br>
                    <p><strong>Message:</strong> ${endMsg}</p>
                `,
                icon: 'info',
                position: 'center', // Can be 'top', 'top-start', 'top-end', 'center', 'center-start', 'center-end', 'bottom', 'bottom-start', 'bottom-end'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Reload the page when the user presses "OK"
                    window.location.reload();
                }
            });
            
        }
        else {
            alertMessage('Something went wrong, Error: ' + jsonResponse.error, 'error', 5000);
            console.log(response)
        }
    })
    .catch((error) => {
        alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
        console.log("Error:", error);
    });
}

// single room booking validation
function singleBookingPaymentFormValidation() {

    let isValid = true;
    let sTotalPay =  document.getElementById('sTotalPay')

    if (!singleBookingPaymentMethod ) {
        displayError(singleRoomPaymentPicklist, "Payment Method cannot be empty");
        isValid = false;

        dynamicPicklistErrorSign ('singleRoomPaymentPicklist', isValid)
    }
    else {
        displayError(singleRoomPaymentPicklist, '');
        dynamicPicklistErrorSign ('singleRoomPaymentPicklist', isValid)
    }

    if(!sRoomQuantity.value) {
        displayError(sRoomQuantity, "Room Quantity cannot be empty");
        isValid = false;
    }
    else {
        displayError(sRoomQuantity, '');
    }
    
    if(!sFullName.value) {
        displayError(sFullName, "Full Name cannot be empty");
        isValid = false;
    }
    else {
        displayError(sFullName, '');
    }
        
    if(!sCompleteAddress.value) {
        displayError(sCompleteAddress, "Complete Address cannot be empty");
        isValid = false;
    }
    else {
        displayError(sCompleteAddress, '');
    }

    if(!sContactInfo.value) {
        displayError(sContactInfo, "Contact Info cannot be empty");
        isValid = false;
    }
    else {
        displayError(sContactInfo, '');
    }

    if(!sAmountToPay.value) {
        displayError(sAmountToPay, "Amount to Pay cannot be empty");
        isValid = false;
    }
    else {
        displayError(sAmountToPay, '');
    }

    if(selectedFilesSingleBook.length <= 0 && singleBookingPaymentMethod != 'Manual') {
        imagePreviewContainerSingleEvidenceError.classList.remove('display-none')
        displayError(imagePreviewContainerSingleEvidence, "Payment Evidence connot be empty.");
        isValid = false;
    }
    else {
        imagePreviewContainerSingleEvidenceError.classList.add('display-none')
        displayError(imagePreviewContainerSingleEvidence, "");
    }

    if( convertCurrencyStringToNumber(sAmountToPay.value) > convertCurrencyStringToNumber(sTotalPay.innerText)) {

        displayError(sAmountToPay, "Amount to Pay is greater than actual Total payable.");
        isValid = false;
    }
    else {
        displayError(sAmountToPay, '');
    }

    return isValid;
}

//validate the search bar for searching available rooms
function searchAvailableRoomsValidator(btnFrom) {
    let isValid = true;
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
    const day = String(now.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`;
    
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

    if(checkInDateInput.value < formattedDate ) {
        displayError(checkInDateInput, "Check-in date is invalid");
        isValid = false;
    }
    
    if(checkOutDateInput.value < formattedDate) {
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

    if(btnFrom === 'fromBooknow') {
        if ((!checkInDateParam && !checkOutDateParam) || (checkInDateParam != checkInDateInput.value) || (checkOutDateParam != checkOutDateInput.value)) {
            isValid = false;
            alertMessage('Ensure that your booking falls within a valid time frame. Check for availability first before booking', 'error', 5000);
        }
    }

    return isValid;
}
  
// Event delegation for delete buttons
imagePreviewContainerDelete.addEventListener('click', function(event) {
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

// Event delegation for delete buttons imagePreviewContainerSingleEvidence
imagePreviewContainerSingleEvidence.addEventListener('click', function(event) {
    if (event.target.classList.contains('deleteButton')) {
        const imageContainer = event.target.parentElement;
        let imageContainerId = imageContainer.id;

        let indexToDelete = selectedFilesSingleBook.findIndex(function(rateObject) {
        return rateObject.id === imageContainerId;
        });
        selectedFilesSingleBook.splice(indexToDelete, 1);

        // console.log('Remained files: ', selectedFiles);

        imageContainer.remove();
    }
})  
  
// Event for adding rooms and it's additional details
addRoomBtnDone.addEventListener('click', async function() {
    if(validateAddRoomForm()) {
       // Call the function to upload images
       uploadImageToFirebase(selectedFiles, imageLink).then(() => {
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
    let amenityToPass = null;

    if(otherRateObject.length > 0){
        otherRatesToPass = otherRateObject;
    }

    if(amenityObject.length > 0){
        amenityToPass = amenityObject;
    }

    const url = "controller/roomsController.php";
    const data = {
        saveRoomDetails: true,
        imageLink: imageLink,
        otherRates: otherRatesToPass,
        amenities: amenityToPass,
        roomName: rooName.value,
        roomDescription: roomDescription.value,
        maximumCapacity: maximumCapacity.value,
        roomQuantity: roomQuantity.value,
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

            resetErrors("form-a");
        }
        else {
            alertMessage('Something went wrong, Error: ' + jsonResponse.error, 'error', 5000);
            console.log(response)
        }
    })
    .catch((error) => {
        alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
        console.log("Error:", error);
    });
}

// Function to handle the click event for room details view
function handleRoomDetailsViewBtnClick(event) {
    var elementId = event.target.id;
    var numberPart = elementId.match(/\d+/)[0];
    roomDetailsModal.classList.add('show');
    singleRoomBookingModalId.classList.remove('show');
    if (closeroomDetails) {
        closeroomDetails.addEventListener('click', function() {
            
            roomDetailsModal.classList.remove('show');
     });
    }
    // console.log("Clicked element ID:", numberPart);
    querySingleRoomDetails(numberPart);
}

// Query Single room details
function querySingleRoomDetails(roomId) {
    const url = "controller/roomsController.php";
    const data = {
        querySingleRoom: true,
        roomId: roomId,
    };

    handlePostRequest(url,data )
    .then((response) => {
        // console.log('response: ', response)
        var jsonResponse = JSON.parse(response);
        if(jsonResponse.rooms.length > 0) {
            let roomName = jsonResponse.rooms[0].roomName;
            let roomId = jsonResponse.rooms[0].roomId;
            let roomMaxCap = jsonResponse.rooms[0].roomMaxCap;
            let roomDescription = jsonResponse.rooms[0].roomDescription;
            let roomPublishedRate = jsonResponse.rooms[0].roomPublishedRate;
            let quantity = jsonResponse.rooms[0].roomQuantity;
            let imageLink = JSON.parse(jsonResponse.rooms[0].imageLink);
            let otherRate = JSON.parse(jsonResponse.rooms[0].otherRate);
            let amenities = JSON.parse(jsonResponse.rooms[0].amenities);
            let modalHeader = document.getElementById('roomName');
            let image = document.getElementById('viewRoomMainImage');
            let displayedRate =  $(`#select-${roomId}`)[0].selectize;
            let displayedRateSelectedValue = displayedRate.getValue();
            displayedRate = displayedRate.options[displayedRateSelectedValue].text;
            modalHeader.textContent = roomName; 
            image.src = imageLink[0].Link;
            let descriptionText = document.getElementById('description-text');
            let capacityTotal = document.getElementById('capacityTotal');
            let quantityText = document.getElementById('quantityText');

            quantityText.innerText = `${quantity} Available`
            
            if (cardWrapper && cardWrapper.children.length > 0) {
                cardWrapper.innerHTML = '';
            }

            imageLink.forEach(img => {
                displayRoomsInSlider(img, cardWrapper);
            });
            initializeSwiper('slide-container', 'wrapper-forRoomDetails');

            document.querySelectorAll('.imageList').forEach(imgList => {
                imgList.addEventListener('click', function() {
                    image.src = imgList.src;
                })
            })

            let roomMap = {
                roomId: roomId,
                roomPublishedRate: roomPublishedRate,
                otherRate: otherRate,
                displayedRate: displayedRate
            }
            let selectedAvailableRate = singleRoomDetailsRatesPickList(roomMap)
            if(selectedAvailableRate) {
                selectedPrice.innerText = dynamicCurrencyforTxtValue(displayedRateSelectedValue) ;
            }

            descriptionText.innerText = roomDescription;
            capacityTotal.innerText = roomMaxCap;

        
            // Get the container element
            const amenitiesBody = document.querySelector('.amenities-body');
            // Remove all child elements
            while (amenitiesBody.firstChild) {
                amenitiesBody.removeChild(amenitiesBody.firstChild);
            }

            if(amenities) {
                amenities.forEach(amenity => {
                // Define the HTML to be inserted
                const amenityHTML = `
                <div class="amenityType-container" id="amenity-${amenity.Id}">
                <i class="fa-solid fa-circle-check" style="margin-right: 10px;"></i> 
                <span>${amenity.amenityName}</span>
                </div>
                `;

                // Create a temporary container to hold the HTML string
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = amenityHTML;

                // Append the new element to the amenities body
                while (tempDiv.firstChild) {
                    amenitiesBody.appendChild(tempDiv.firstChild);
                }

            })}
            
            const bookThisRoomButton = document.getElementById('bookThisRoom');
            const clonedButton = bookThisRoomButton.cloneNode(true);
            bookThisRoomButton.parentNode.replaceChild(clonedButton, bookThisRoomButton);

            clonedButton.addEventListener('click', function() {
                if(searchAvailableRoomsValidator('fromBooknow')) {
                    // alert(`Room Id = ${roomId} \n value = ${convertCurrencyStringToNumber(selectedPrice.innerText)}`);

                    resetErrors('form-d');
                    removeErrorLabel('form-d-label');
                    dynamicPicklistErrorSign ('singleRoomPaymentPicklist', true);

                    let paymentDetails = {
                        roomId: roomId,
                        selectedPrice: selectedPrice.innerText,
                        roomName: roomName,
                        quantity: quantity
                    }

                    singleRoomBookingPayment(paymentDetails)

                    singleRoomBookingModalId.classList.add('show');
                    roomDetailsModal.classList.remove('show');
                    if (singleRoomBookingModalCLose) {
                        singleRoomBookingModalCLose.addEventListener('click', function() {
                            singleRoomBookingModalId.classList.remove('show');
                     });
                    }
                }
            });

        }
        else {
            alertMessage('Cant\'t load room details, something went wrong', 'warning', 3000);
            console.log(response)
        }
    })
    .catch((error) => {
        alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
        console.log("Error:", error);
    });
}

function singleRoomBookingPayment(paymentDetails) {

    let selectedSingleRoomName = document.getElementById('selectedSingleRoomName');
    selectedSingleRoomName.innerText = `Selected Room: ${paymentDetails.roomName}`;
    let selectedRate =  parseFloat(parseCurrency(paymentDetails.selectedPrice));
    let sTotalPay =  document.getElementById('sTotalPay')
    let newRate = 0;
    singleBookingRoomId = paymentDetails.roomId

    paymentMethodsOptions().then((val) => {
        let paymentMethods = val;

        // Destroy existing Selectize instance if it exists
        if ($('#single-room-payment-picklist')[0].selectize) {
            $('#single-room-payment-picklist')[0].selectize.destroy();
        }

        let selectize = $('#single-room-payment-picklist').selectize({
            options: [],
            optgroups: [
                { value: 'Payment Methods', label: 'Payment Methods' }
            ],
            optgroupField: 'optgroup',
            labelField: 'text',
            valueField: 'value',
            searchField: ['text'],
            render: {
                optgroup_header: function (data, escape) {
                    return '<div class="optgroup-header">' + escape(data.label) + '</div>';
                }
            }
        });
        let selectizeInstance = selectize[0].selectize;



        // Create the new options array
        let options = [];

        paymentMethods.forEach(function(method){
            options.push({
                value: method.paymentMethodName,
                text: method.paymentMethodName
            });
        })

        // console.log(paymentMethods.paymentMethodName)

        // Add new options to the Selectize instance
        selectizeInstance.addOption(options);

        // Refresh the options in the dropdown
        selectizeInstance.refreshOptions(false);

        // Set the default selected value to 'none'
        selectizeInstance.setValue('none');
        let paymentMethodMsgContainer = document.querySelector('.paymentMethodMsg-container');
        let roomFormButtonContainer = document.getElementById('addImagesEvidence-container');
        let paymentMethodContainerDetailsContainer = document.querySelector('.paymentMethodContainerDetails-container');

        paymentMethodContainerDetailsContainer.classList.add('display-none')
        paymentMethodMsgContainer.classList.add('display-none')
        roomFormButtonContainer.classList.remove('display-none');

        singleBookingPaymentMethod =null

        selectizeInstance.on('change', function(value) {
            singleBookingPaymentMethod = value;
            
            let paymenthMethodHeaderName = document.getElementById('paymenthMethodHeaderName');
            paymenthMethodHeaderName.innerText = singleBookingPaymentMethod;
            let paymentNumber = document.getElementById('paymentNumber');
            let paymentMethodMsg = document.getElementById('paymentMethodMsg');
            let qrImageId = document.getElementById('qrImageId');


            paymentMethodContainerDetailsContainer.classList.add('display-none')
            paymentMethodMsgContainer.classList.add('display-none')
            roomFormButtonContainer.classList.remove('display-none');
            if(singleBookingPaymentMethod) {
                paymentMethodContainerDetailsContainer.classList.remove('display-none')
                paymentMethodMsgContainer.classList.remove('display-none')

                paymentMethods.forEach(function(method, indexNumber){
                    if(singleBookingPaymentMethod === method.paymentMethodName){
                        paymentNumber.innerText = method.paymentMethodPaymentNumber;
                        qrImageId.src = method.paymentMethodOrLink;
                    }
                })

                paymentMethodMsg.innerText = `You have selected ${singleBookingPaymentMethod} as your payment method. Please be aware that we will review the image evidence you provide before confirming your reservation.`;
                
                if(singleBookingPaymentMethod === 'Manual'){
                    imagePreviewContainerSingleEvidenceError.classList.add('display-none')
                    displayError(imagePreviewContainerSingleEvidence, "");
                    paymentMethodContainerDetailsContainer.classList.add('display-none')
                    roomFormButtonContainer.classList.add('display-none');
                    paymentMethodMsg.innerText = 'You have chosen the manual payment method. Please note that we will hold your reservation for 2 hours while we wait for you to come and complete the payment.'
                }
            }
        });


        sTotalPay.innerText = dynamicCurrencyforTxtValue(newRate);
        // Define the blur event handler function
        function blurEventHandler() {
        newRate = selectedRate * parseInt(sRoomQuantity.value);
        sTotalPay.innerText = dynamicCurrencyforTxtValue(newRate);
        }

        // Remove the blur event listener if it's already declared
        sRoomQuantity.removeEventListener('blur', blurEventHandler);

        // Re-add the blur event listener
        sRoomQuantity.addEventListener('blur', blurEventHandler);
    });
}

// Pick List field for single single room details
function singleRoomDetailsRatesPickList(roomMap){
    // Destroy existing Selectize instance if it exists
    if ($('#select-single-rates')[0].selectize) {
        $('#select-single-rates')[0].selectize.destroy();
    }

    let selectize = $('#select-single-rates').selectize({
        options: [],
        optgroups: [
            { value: 'availableRates', label: 'Available Rates' }
        ],
        optgroupField: 'optgroup',
        labelField: 'text',
        valueField: 'value',
        searchField: ['text'],
        render: {
            optgroup_header: function (data, escape) {
                return '<div class="optgroup-header">' + escape(data.label) + '</div>';
            }
        }
    });
    let selectizeInstance = selectize[0].selectize;

    // Create the new options array
    let newOptions = [];

    newOptions.push({
        value: roomMap.roomPublishedRate,
        text: `Published Rate :  ${dynamicCurrencyforTxtValue(roomMap.roomPublishedRate)}`,
        optgroup: 'availableRates'
    });

    // Iterate through the rates array
    if(roomMap.otherRate){
        roomMap.otherRate.forEach(rate => {
            newOptions.push({
                value: rate.amount,
                text: `${rate.type} : ${dynamicCurrencyforTxtValue(rate.amount)}`,
                optgroup: 'availableRates'
            });
        })
    };


    // Add new options to the Selectize instance
    selectizeInstance.addOption(newOptions);

    // Refresh the options in the dropdown
    selectizeInstance.refreshOptions(false);

    selectTizedSearch(selectizeInstance, roomMap.displayedRate);

    // Add an event listener for the "change" event
    selectizeInstance.on('change', function(value) {
        selectedPrice.innerText = dynamicCurrencyforTxtValue(value);
        let mainSelected = $(`#select-${roomMap.roomId}`)
        let selectizeMainSelected = mainSelected[0].selectize;
        selectTizedSearch(selectizeMainSelected, selectedPrice.innerText);
        // console.log(selectedPrice.innerText)
    });

    return selectizeInstance.getValue();
}

// used for searching text in picklist
function selectTizedSearch(selectizeInstance, searchVal)
{
    let targetValue = null;
    for (let key in selectizeInstance.options) {
        if (selectizeInstance.options.hasOwnProperty(key)) {
            let option = selectizeInstance.options[key];
            if (option.text.includes(searchVal)) {
                targetValue = option.value;
                break;
            }
        }
    }
    
    // If the target value is found, add it as the selected item
    if (targetValue) {
        selectizeInstance.addItem(targetValue);
    }
}

// Attach blur event listener to the input field
newRate.addEventListener('blur', function() {
    dynamicInputFieldCurrencyFormatter(newRate)
});

// Attach blur event listener to the input field
sAmountToPay.addEventListener('blur', function() {
    dynamicInputFieldCurrencyFormatter(sAmountToPay)
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

submitChanges.addEventListener('click', function(){
    alert('Editing room details is currently in development');
})
  
// add other rates event
addRateNowBtn.addEventListener('click', function() {
    if (validateOtherRatesForm()) {
   
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

        resetErrors("form-b")
    }
});

// add amenity button
addAmenityNowBtn.addEventListener('click', function() {
    if (validateAmenityForm()) {
        
        let amenityInputValue = capitalizeFirstLetter(amenityInput.value);

        let amenityCard = document.createElement('div');
        amenityCard.classList.add('amenity-card');
        amenityCard.id = `amenity-card-${amenityCount}`;

        amenityObject.push({
            amenity: amenityInputValue,
            id: amenityCard.id
        });

        // console.log(otherRateObject);

        let amenitySpan = document.createElement('span');
        amenitySpan.id = `ratype-${amenityCount}`;
        amenitySpan.textContent = amenityInputValue;

        let removeAmenityCard = document.createElement('span');
        removeAmenityCard.classList.add('fa-regular', 'fa-circle-xmark');
        removeAmenityCard.style.marginLeft = '10px';
        removeAmenityCard.style.cursor = 'pointer';

        amenityCard.appendChild(amenitySpan);
        amenityCard.appendChild(removeAmenityCard);

        document.querySelector('.newAmenity-container').appendChild(amenityCard);

        amenityCount++;
        amenityInput.value = null;

        removeAmenityCard.addEventListener('click', function() {
            amenityCard.remove();
            let indexToDelete = amenityObject.findIndex(function(amintyObj) {
                return amintyObj.id === amenityCard.id;
            });
            amenityObject.splice(indexToDelete, 1);
        });

        resetErrors("form-c")
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

    if(!roomQuantity.value) {
        displayError(roomQuantity, "Room Quantity cannot be empty");
        isValid = false;
    }
    else {
        displayError(roomQuantity, '');
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
  
// validate other rates
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

// validate Amenity
function validateAmenityForm() {
    let isValid = true;

    if(!amenityInput.value) {
        displayError(amenityInput, "Amenity cannot be empty");
        isValid = false;
    }
    else {
        displayError(amenityInput, '');
    }

    return isValid;
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

function displayRoomsInSlider(room, wrapper) {

    // console.log(`Room ID: ${room.roomId}`);
    // console.log(`Room Name: ${room.roomName}`);
    // console.log(`Room Max Capacity: ${room.roomMaxCap}`);
    // console.log(`Room Description: ${room.roomDescription}`);
    // console.log(`Room Published Rate: ${room.roomPublishedRate}`);
    // console.log(`Image Link: ${room.imageLink}`);
    // console.log(`Other Rate: ${room.otherRate}`);
    // console.log(`Other Rate Amount: ${room.otherRateAmount}`);
    // console.log('---------------------');
   
    const swiperCards = `
        <div class="card swiper-slide" id="swiperCard-${room.Id}">
        <div class="image-box" style="border: solid white 2px; border-radius: 10px">
            <img src="${room.Link}" class="clickable imageList" id="image--${room.Id}" alt="" />
        </div>
        </div>
    `;
  
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = swiperCards;
  
    const cardSwiperContainer = tempDiv.firstElementChild;
  
    if (wrapper) {
        wrapper.appendChild(cardSwiperContainer);
    }
  }

// to show the add room addRoomModal
if (addRoomBtn) {
    addRoomBtn.addEventListener('click', function() {
      addRoomModal.classList.add('show');
    });
  }
  
// to cloase the add room addRoomModal
if (closeAddRoomsModal) {
  closeAddRoomsModal.addEventListener('click', function() {
    addRoomModal.classList.remove('show'); // add the show class to display the addRoomModal
 });
}


