let customerId = userOrAdminDetails.userId;
let UserRole = userOrAdminDetails.role;
let swiper;
let openFilterBookingsModal = document.getElementById('openFilterBookingsModal');
let bookingsOpenFilterModalBtn = document.getElementById('bookingsOpenFilterModalBtn');
let closeFilterBookingsModal = document.getElementById('closeFilterBookingsModal');
let filterOperatorSelect = document.querySelectorAll('.filter-operator-select');
let proceedFilterBtn = document.getElementById('proceedFilterBtn');
let filterCheck_InStartDate = document.getElementById('filter-check-in-start-date');
let filterCheck_InEndDate = document.getElementById('filter-check-in-end-date');
let filterCheck_OutStartDate = document.getElementById('filter-check-out-start-date');
let filterCheck_OutEndDate =  document.getElementById('filter-check-out-end-date');
let filterRoomName =  document.getElementById('filter-room-name');
let filterCustomer = document.getElementById('filter-customer');
let totalPriceOperator = document.getElementById('total-price-operator');
let filterTotalPrice = document.getElementById('filter-total-price');
let bookedQuantityOperator = document.getElementById('booked-quantity-operator');
let filterBookedQuantity = document.getElementById('filter-booked-quantity');
let filterPaymentIsPartital =  document.getElementById('filter-payment-is-partital');
let filterBookingStatus =  document.getElementById('filter-booking-status');
let filterProcessStartDate =  document.getElementById('filter-process-start-date');
let filterProcessEndDate =  document.getElementById('filter-process-end-date');
let filterLastProcessStartDate = document.getElementById('filter-last-process-start-date');
let filterLastProcessEndDate =  document.getElementById('filter-last-process-end-date');
let filterableValues = [];
changeFilterOperatorValueColor();
checkUrlParamValues();


function initializeSwiperWithParam(className, swiperWrapperClass) {
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

function displayRoomsInSliderWithWrapper(room, wrapper) {

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

//display filter modal when clicked
bookingsOpenFilterModalBtn.addEventListener('click', function(){
 if(openFilterBookingsModal.classList.contains('display-none')){
    openFilterBookingsModal.classList.remove('display-none');
 }
})

//close filter modal when clicked
closeFilterBookingsModal.addEventListener('click', function(){
  if(!openFilterBookingsModal.classList.contains('display-none')){
    openFilterBookingsModal.classList.add('display-none');
 }
})

//change the color of filter operator to gray if the value is none
function changeFilterOperatorValueColor(){
  filterOperatorSelect.forEach(select => {
    select.addEventListener('change', function() {
  
        if (select.value == 'none') {
            if (!select.classList.contains('color-gray')) {
                select.classList.add('color-gray');
            }
        } else {
            if (select.classList.contains('color-gray')) {
                select.classList.remove('color-gray');
            }
        }
  
    });
  });
}

//validate the filter operation
proceedFilterBtn.addEventListener('click', function(){
  let isValid = true;

  if((filterCheck_InStartDate.value && filterCheck_InEndDate.value) && (filterCheck_InStartDate.value > filterCheck_InEndDate.value)) {
    displayError(filterCheck_InStartDate, "Check-in start date must be less or equals to Check-in end date");
    displayError(filterCheck_InEndDate, "Check-in end date must be greater or equals to Check-in start date");
    isValid = false;
  }
  else{
    displayError(filterCheck_InStartDate, '');
    displayError(filterCheck_InEndDate, '');
  }

  if((filterCheck_OutStartDate.value && filterCheck_OutEndDate.value) && (filterCheck_OutStartDate.value > filterCheck_OutEndDate.value)) {
    displayError(filterCheck_OutStartDate, "Check-out start date must be less or equals to Check-out end date");
    displayError(filterCheck_OutEndDate, "Check-out end date must be greater or equals to Check-out start date");
    isValid = false;
  }
  else{
    displayError(filterCheck_OutStartDate, '');
    displayError(filterCheck_OutEndDate, '');
  }

  if(totalPriceOperator.value == 'none' && filterTotalPrice.value) {
    displayErrorSelectElement(totalPriceOperator, "Please select an operator before you proceed");
    isValid = false;
  }
  else if(totalPriceOperator.value != 'none' && !filterTotalPrice.value) {
    displayError(filterTotalPrice, "Please input a total price before you proceed");
    isValid = false;
  }
  else{
    displayErrorSelectElement(totalPriceOperator, "");
    displayError(filterTotalPrice, "");
  }

  if(bookedQuantityOperator.value == 'none' && filterBookedQuantity.value) {
    displayErrorSelectElement(bookedQuantityOperator, "Please select an operator before you proceed");
    isValid = false;
  }
  else if(bookedQuantityOperator.value != 'none' && !filterBookedQuantity.value) {
    displayError(filterBookedQuantity, "Please input a Booked Quantity before you proceed");
    isValid = false;
  }
  else{
    displayErrorSelectElement(bookedQuantityOperator, "");
    displayError(filterBookedQuantity, "");
  }

  if((filterProcessStartDate.value && filterProcessEndDate.value) && (filterProcessStartDate.value > filterProcessEndDate.value)) {
    displayError(filterProcessStartDate, "Processed start date must be less or equals to Processed end date");
    displayError(filterProcessEndDate, "Processed end date must be greater or equals to Processed start date");
    isValid = false;
  }
  else{
    displayError(filterProcessStartDate, '');
    displayError(filterProcessEndDate, '');
  }

  if((filterLastProcessStartDate.value && filterLastProcessEndDate.value) && (filterLastProcessStartDate.value > filterLastProcessEndDate.value)) {
    displayError(filterLastProcessStartDate, "Last Processed start date must be less or equals to Last Processed end date");
    displayError(filterLastProcessEndDate, "Last Processed end date must be greater or equals to Last Processed start date");
    isValid = false;
  }
  else{
    displayError(filterLastProcessStartDate, '');
    displayError(filterLastProcessEndDate, '');
  }

  if(isValid) {
    let url = `bookings.php`;
    let queryParams = [];

    if(filterCheck_InStartDate.value){
      queryParams.push(`filterCheck_InStartDate=${encodeURIComponent(filterCheck_InStartDate.value)}`);
    }
    if(filterCheck_InEndDate.value){
      queryParams.push(`filterCheck_InEndDate=${encodeURIComponent(filterCheck_InEndDate.value)}`);
    }

    if(filterCheck_OutStartDate.value){
      queryParams.push(`filterCheck_OutStartDate=${encodeURIComponent(filterCheck_OutStartDate.value)}`);
    }
    if(filterCheck_OutEndDate.value){
      queryParams.push(`filterCheck_OutEndDate=${encodeURIComponent(filterCheck_OutEndDate.value)}`);
    }

    if(filterRoomName.value){
      queryParams.push(`filterRoomName=${encodeURIComponent(filterRoomName.value)}`);
    }

    if(filterCustomer.value){
      queryParams.push(`filterCustomer=${encodeURIComponent(filterCustomer.value)}`);
    }
    
    if(totalPriceOperator.value != 'none'){
      queryParams.push(`totalPriceOperator=${encodeURIComponent(totalPriceOperator.value)}`);
    }
    if(filterTotalPrice.value){
      queryParams.push(`filterTotalPrice=${encodeURIComponent(convertCurrencyStringToNumber(filterTotalPrice.value))}`);
    }

    if(bookedQuantityOperator.value != 'none'){
      queryParams.push(`bookedQuantityOperator=${encodeURIComponent(bookedQuantityOperator.value)}`);
    }
    if(filterBookedQuantity.value){
      queryParams.push(`filterBookedQuantity=${encodeURIComponent(filterBookedQuantity.value)}`);
    }

    if(filterPaymentIsPartital.value != 'none'){
      queryParams.push(`filterPaymentIsPartital=${encodeURIComponent(filterPaymentIsPartital.value)}`);
    }

    if(filterBookingStatus.value != 'none'){
      queryParams.push(`filterBookingStatus=${encodeURIComponent(filterBookingStatus.value)}`);
    }

    if(filterProcessStartDate.value){
      queryParams.push(`filterProcessStartDate=${encodeURIComponent(filterProcessStartDate.value)}`);
    }
    if(filterProcessEndDate.value){
      queryParams.push(`filterProcessEndDate=${encodeURIComponent(filterProcessEndDate.value)}`);
    }

    if(filterLastProcessStartDate.value){
      queryParams.push(`filterLastProcessStartDate=${encodeURIComponent(filterLastProcessStartDate.value)}`);
    }
    if(filterLastProcessEndDate.value){
      queryParams.push(`filterLastProcessEndDate=${encodeURIComponent(filterLastProcessEndDate.value)}`);
    }
    
    if (queryParams.length > 0) {
      url += '?' + queryParams.join('&');
    }
    // console.log(url);

    window.location.href = url;
  }


})

//filter total price format into currency
filterTotalPrice.addEventListener('blur', function() {
  dynamicInputFieldCurrencyFormatter(filterTotalPrice)
});

//check url parameter value
function checkUrlParamValues() {

  let urlParams = new URLSearchParams(window.location.search);

  if (urlParams.has('filterCheck_InStartDate')) { 
    filterCheck_InStartDate.value = urlParams.get('filterCheck_InStartDate');
    filterableValues.filterCheck_InStartDate = filterCheck_InStartDate.value;
  }
  if (urlParams.has('filterCheck_InEndDate')) {
    filterCheck_InEndDate.value = urlParams.get('filterCheck_InEndDate');
    filterableValues.filterCheck_InEndDate = filterCheck_InEndDate.value;
  }

  if (urlParams.has('filterCheck_OutStartDate')) {
    filterCheck_OutStartDate.value = urlParams.get('filterCheck_OutStartDate');
    filterableValues.filterCheck_OutStartDate = filterCheck_OutStartDate.value;
  }
  if (urlParams.has('filterCheck_OutEndDate')) {
    filterCheck_OutEndDate.value = urlParams.get('filterCheck_OutEndDate');
    filterableValues.filterCheck_OutEndDate = filterCheck_OutEndDate.value;
  }

  if (urlParams.has('filterRoomName')) {
    filterRoomName.value = urlParams.get('filterRoomName');
    filterableValues.filterRoomName = filterRoomName.value;
  }

  if (urlParams.has('filterCustomer')) {
    filterCustomer.value = urlParams.get('filterCustomer');
    filterableValues.filterCustomer = filterCustomer.value;
  }

  if (urlParams.has('filterTotalPrice')) {
     filterTotalPrice.value = urlParams.get('filterTotalPrice');
     filterableValues.filterTotalPrice =  convertCurrencyStringToNumber(filterTotalPrice.value);
  }
  if (urlParams.has('totalPriceOperator')) {
    totalPriceOperator.value = urlParams.get('totalPriceOperator');
    if (totalPriceOperator.classList.contains('color-gray')) {
      totalPriceOperator.classList.remove('color-gray');
    }
    filterableValues.totalPriceOperator = totalPriceOperator.value;
  }

  if (urlParams.has('bookedQuantityOperator')) {
    bookedQuantityOperator.value = urlParams.get('bookedQuantityOperator');
    if (bookedQuantityOperator.classList.contains('color-gray')) {
      bookedQuantityOperator.classList.remove('color-gray');
    }
    filterableValues.bookedQuantityOperator = bookedQuantityOperator.value;
  }
  if (urlParams.has('filterBookedQuantity')) {
    filterBookedQuantity.value = urlParams.get('filterBookedQuantity');
    filterableValues.filterBookedQuantity = filterBookedQuantity.value;
  }

  if (urlParams.has('filterPaymentIsPartital')) {
    filterPaymentIsPartital.value = urlParams.get('filterPaymentIsPartital');
    if (filterPaymentIsPartital.classList.contains('color-gray')) {
      filterPaymentIsPartital.classList.remove('color-gray');
    }
    filterableValues.filterPaymentIsPartital = filterPaymentIsPartital.value;
  }

  if (urlParams.has('filterBookingStatus')) {
    filterBookingStatus.value = urlParams.get('filterBookingStatus');
    if (filterBookingStatus.classList.contains('color-gray')) {
      filterBookingStatus.classList.remove('color-gray');
    }
    filterableValues.filterBookingStatus = filterBookingStatus.value;
  }

  if (urlParams.has('filterProcessStartDate')) {
    filterProcessStartDate.value = urlParams.get('filterProcessStartDate');
    filterableValues.filterProcessStartDate = filterProcessStartDate.value;
  }
  if (urlParams.has('filterProcessEndDate')) {
    filterProcessEndDate.value = urlParams.get('filterProcessEndDate');
    filterableValues.filterProcessEndDate = filterProcessEndDate.value;
  }

  if (urlParams.has('filterLastProcessStartDate')) {
    filterLastProcessStartDate.value = urlParams.get('filterLastProcessStartDate');
    filterableValues.filterLastProcessStartDate = filterLastProcessStartDate.value;
  }
  if (urlParams.has('filterLastProcessEndDate')) {
    filterLastProcessEndDate.value = urlParams.get('filterLastProcessEndDate');
    filterableValues.filterLastProcessEndDate = filterLastProcessEndDate.value;
  }
}

//show more details if spicific booking record is selected
function moreDetails(e){
  alert('working => '+ e)
}




