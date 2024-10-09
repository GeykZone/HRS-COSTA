let customerId = userOrAdminDetails.userId;
let UserRole = userOrAdminDetails.role;
let paymentsOpenFilterModalBtn = document.getElementById('paymentsOpenFilterModalBtn');
let openFilterPaymentsModal = document.getElementById('openFilterPaymentsModal');
let closeFilterPaymentsModal = document.getElementById('closeFilterPaymentsModal');
var filterPaymentMethod = document.getElementById('filter-payment-method');
let filterOperatorSelect = document.querySelectorAll('.filter-operator-select');
let filterCustomer = document.getElementById('filter-customer');
let totalPriceOperator = document.getElementById('total-price-operator');
let filterTotalPrice = document.getElementById('filter-total-price');
let bookedQuantityOperator = document.getElementById('booked-quantity-operator');
let filterBookedQuantity = document.getElementById('filter-booked-quantity');
let filterPaymentIsMultiBooked = document.getElementById('filter-payment-is-multiBooked');
let filterPaymentIsPartital =  document.getElementById('filter-payment-is-partital');
let partialAmountOperator = document.getElementById('partial-amount-operator');
let filterPartialAmount = document.getElementById('filter-partial-amount');
let filterPaymentStatus =  document.getElementById('filter-Payment-status');
let filterLastProcessStartDate = document.getElementById('filter-last-process-start-date');
let filterLastProcessEndDate =  document.getElementById('filter-last-process-end-date');
let proceedFilterBtn = document.getElementById('proceedFilterBtn');
let swiper;
let filterableValues = [];
changeFilterOperatorValueColor();
checkUrlParamValues();

//display filter modal when clicked
paymentsOpenFilterModalBtn.addEventListener('click', function(){
    if(openFilterPaymentsModal.classList.contains('display-none')){
        openFilterPaymentsModal.classList.remove('display-none');

        // Remove all existing options if there are any
        while (filterPaymentMethod.firstChild) {
            filterPaymentMethod.removeChild(filterPaymentMethod.firstChild);
        }

        // Define the new options
        var options = [ { class: 'color-gray', value: 'none', text: 'none' } ];

        options[1] = { class: 'color-gray', value: 'wew', text: 'wew' };

        const url = "controller/roomsController.php";
        const data = {
            queryPaymentMethods: true,
        };
      
        handlePostRequest(url,data )
        .then((response) => {
            let paymentMethods = JSON.parse(response).rooms;

            paymentMethods.forEach( (paymentMethod, index) => {
                options[index + 1] = { class: 'option-value', value:  paymentMethod.paymentMethodId, text: paymentMethod.paymentMethodName };
            })

             // Add new options dynamically
            options.forEach(function(optionData) {
                var option = document.createElement('option');
                option.className = optionData.class;
                option.value = optionData.value;
                option.textContent = optionData.text;
                filterPaymentMethod.appendChild(option);
            });

            if (new URLSearchParams(window.location.search).has('filterPaymentMethod')) {
                filterPaymentMethod.value = new URLSearchParams(window.location.search).get('filterPaymentMethod');
                if(filterPaymentMethod.value != 'none'){
                    filterPaymentMethod.classList.remove('color-gray');
                }
            }

        })
        .catch((error) => {
            alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
            console.log("Error:", error);
        });

        if(filterTotalPrice){
            dynamicInputFieldCurrencyFormatter(filterTotalPrice)
        }

        if(filterPartialAmount){
            dynamicInputFieldCurrencyFormatter(filterPartialAmount)
        }

    }
})
   
//close filter modal when clicked
closeFilterPaymentsModal.addEventListener('click', function(){
    if(!openFilterPaymentsModal.classList.contains('display-none')){
        openFilterPaymentsModal.classList.add('display-none');
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

//filter total price format into currency
filterTotalPrice.addEventListener('blur', function() {
    dynamicInputFieldCurrencyFormatter(filterTotalPrice)
});

//filter partial amount format into currency
filterPartialAmount.addEventListener('blur', function() {
    dynamicInputFieldCurrencyFormatter(filterPartialAmount)
});

//validate the filter operation
proceedFilterBtn.addEventListener('click', function() {
    let isValid = true;

    if((filterLastProcessStartDate.value && filterLastProcessEndDate.value) && (filterLastProcessStartDate.value > filterLastProcessEndDate.value)) {
        displayError(filterLastProcessStartDate, "Last Processed start date must be less or equals to Last Processed end date");
        displayError(filterLastProcessEndDate, "Last Processed end date must be greater or equals to Last Processed start date");
        isValid = false;
    }
    else{
        displayError(filterLastProcessStartDate, '');
        displayError(filterLastProcessEndDate, '');
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

    if(partialAmountOperator.value == 'none' && filterPartialAmount.value) {
        displayErrorSelectElement(partialAmountOperator, "Please select an operator before you proceed");
        isValid = false;
    }
    else if(partialAmountOperator.value != 'none' && !filterPartialAmount.value) {
        displayError(filterPartialAmount, "Please input a partial amount before you proceed");
        isValid = false;
    }
    else{
        displayErrorSelectElement(partialAmountOperator, "");
        displayError(filterPartialAmount, "");
    }

    if(isValid) {
        let url = `payments.php`;
        let queryParams = [];

        if(filterCustomer.value){
            queryParams.push(`filterCustomer=${encodeURIComponent(filterCustomer.value)}`);
        }

        if(filterPaymentMethod.value != 'none'){
            queryParams.push(`filterPaymentMethod=${encodeURIComponent(filterPaymentMethod.value)}`);
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

        if(filterPaymentIsMultiBooked.value != 'none'){
            queryParams.push(`filterPaymentIsMultiBooked=${encodeURIComponent(filterPaymentIsMultiBooked.value)}`);
        }   

        if(filterPaymentIsPartital.value != 'none'){
            queryParams.push(`filterPaymentIsPartital=${encodeURIComponent(filterPaymentIsPartital.value)}`);
        }

        if(partialAmountOperator.value != 'none'){
            queryParams.push(`partialAmountOperator=${encodeURIComponent(partialAmountOperator.value)}`);
        }
        if(filterPartialAmount.value){
            queryParams.push(`filterPartialAmount=${encodeURIComponent(convertCurrencyStringToNumber(filterPartialAmount.value))}`);
        }

        if(filterPaymentStatus.value != 'none'){
            queryParams.push(`filterPaymentStatus=${encodeURIComponent(filterPaymentStatus.value)}`);
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

//check url parameter value
function checkUrlParamValues() {
  let urlParams = new URLSearchParams(window.location.search);

  if (urlParams.has('filterCustomer')) {
    filterCustomer.value = urlParams.get('filterCustomer');
    filterableValues.filterCustomer = filterCustomer.value;
  }

  if (urlParams.has('filterPaymentMethod')) {
    filterPaymentMethod.value = urlParams.get('filterPaymentMethod');
    filterableValues.filterPaymentMethod = urlParams.get('filterPaymentMethod')
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

  if (urlParams.has('filterPaymentIsMultiBooked')) {
    filterPaymentIsMultiBooked.value = urlParams.get('filterPaymentIsMultiBooked');
    if (filterPaymentIsMultiBooked.classList.contains('color-gray')) {
      filterPaymentIsMultiBooked.classList.remove('color-gray');
    }
    filterableValues.filterPaymentIsMultiBooked = filterPaymentIsMultiBooked.value;
  }

  if (urlParams.has('filterPaymentIsPartital')) {
    filterPaymentIsPartital.value = urlParams.get('filterPaymentIsPartital');
    if (filterPaymentIsPartital.classList.contains('color-gray')) {
      filterPaymentIsPartital.classList.remove('color-gray');
    }
    filterableValues.filterPaymentIsPartital = filterPaymentIsPartital.value;
  }

  if (urlParams.has('filterPartialAmount')) {
    filterPartialAmount.value = urlParams.get('filterPartialAmount');
    filterableValues.filterPartialAmount =  convertCurrencyStringToNumber(filterPartialAmount.value);
  }
  if (urlParams.has('partialAmountOperator')) {
    partialAmountOperator.value = urlParams.get('partialAmountOperator');
    if (partialAmountOperator.classList.contains('color-gray')) {
      partialAmountOperator.classList.remove('color-gray');
    }
    filterableValues.partialAmountOperator = partialAmountOperator.value;
  }

  if (urlParams.has('filterPaymentStatus')) {
    filterPaymentStatus.value = urlParams.get('filterPaymentStatus');
    if (filterPaymentStatus.classList.contains('color-gray')) {
      filterPaymentStatus.classList.remove('color-gray');
    }
    filterableValues.filterPaymentStatus = filterPaymentStatus.value;
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
  