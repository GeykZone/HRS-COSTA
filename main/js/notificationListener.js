let addRoomModal = document.getElementById('addRoomModal');
let roomDetailsModal = document.getElementById('roomDetails');
let defaultNotficationNumber = 0;
let currentUserRole = userOrAdminDetails.role;
let notificationPostData;
let ReservationCheckInId;
let bookingType;
let openReservationNotificationModal = document.getElementById('openReservationNotificationModal');
let closeReservationNotification = document.getElementById('closeReservationNotification');
let roomDetailsData;
let rateRoom = document.querySelector('#rateRoom');  


dynamicConfirmationMessage({
    logo: 'fa-regular fa-thumbs-up', 
    message: 'Do you really want to approve this pending request?', 
    title: 'Approval', 
    logoColor: '#19C43E', 
    buttonLeftCustomClass: 'approve',
    buttonRightCustomClass: 'cancelApprove',
    buttonLeftCustomText: 'Approve',
    buttonRightCustomText: 'Cancel',
    buttonLeftCustomColor: '#19C43E',
    buttonRightCustomColor: '#C41919',
    mainClass: 'checkin-approval-confirmation'
})

dynamicConfirmationMessage({
    logo: 'fa-regular fa-thumbs-down', 
    message: 'Do you really want to reject this pending request?', 
    title: 'Rejection', 
    logoColor: '#F56F02', 
    buttonLeftCustomClass: 'reject',
    buttonRightCustomClass: 'cancelReject',
    buttonLeftCustomText: 'Reject',
    buttonRightCustomText: 'Cancel',
    buttonLeftCustomColor: '#F56F02',
    buttonRightCustomColor: '#C41919',
    mainClass: 'checkin-rejection-confirmation',
    displayBox: true,
    messageBoxCustomClass: 'reject-message-box',
    messageBoxPlaceHolder: 'Rejection Reason'
})

if(currentUserRole === 'customer') {
    notificationPostData = {
        listening : true,
        userId: userOrAdminDetails.userId
    }
}
else {
    notificationPostData = {
        listening : true,
    }
}

function checkForNewRows() {
    $.ajax({
        url: 'controller/notificationListenerController.php', // Your PHP controller URL
        type: 'POST',
        dataType: 'json',
        data: notificationPostData,
        success: function(response) {
            // Process the response
            if (response.newRows && response.newRows.length > 0) {
                if(parseInt(response.rowCount) !=  defaultNotficationNumber) {  
                    $('#notif_icon').removeClass('display-none');
                    $('#notif_icon').text(parseInt(response.rowCount));
                    $('#empty-notif').addClass('display-none');
                    
                    let notifications = response.newRows

                    // console.log(notifications);

                    clearNotifications();

                    notifications.forEach(notification => {

                        // console.log(notification)
                        
                        let notificationData = {
                            Id: notification.checkInId,
                            status: notification.checkInStatus,
                            roomName: notification.roomName,
                            total: notification.checkInTotalAmount,
                            userFullname: notification.checkInCustomerFullName,
                            roomQuantity: notification.checkInQuantity,
                            checkInDate: notification.checkInCheckInDate,
                            chekOutDate: notification.checkInCheckOutDate,
                            checkInCustomerCompleteAddress: notification.checkInCustomerCompleteAddress,
                            isPartial: notification.isPartial,
                            partialPayment: notification.partialPayment,
                            checkInCustomerContactInfo: notification.checkInCustomerContactInfo
                        };

                        // Check if `notification` has the `multiBookId` property
                        if ('multiBookId' in notification) {
                            notificationData.multiBookId = notification.multiBookId;
                            notificationData.multibookTotalAmount = notification.multibookTotalAmount;
                            notificationData.multiBookPartialPayment = notification.multiBookPartialPayment;
                            notificationData.totalQuantity = notification.totalQuantity;
                        }
                        addNotification(notificationData);
                    });
                    
                }
                defaultNotficationNumber = parseInt(response.rowCount);
            }
            else {
                clearNotifications();
                defaultNotficationNumber = 0;
                const notificationContainer = document.querySelector('.notification-container');        
                const newNotification = document.createElement('li');
                newNotification.textContent = `Notification box is empty.`;
                newNotification.id = `empty-notif`;
                notificationContainer.appendChild(newNotification);
                $('#notif_icon').addClass('display-none');
                $('#empty-notif').removeClass('display-none');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function addNotification(notificationData) {

    let isMultibookIndicator = '';
    
    const notificationContainer = document.querySelector('.notification-container');        
    const newNotification = document.createElement('li');
    if(notificationData.multiBookId){
        isMultibookIndicator = 'Multi Booked'
        newNotification.textContent = `Room ${notificationData.roomName} - Reservation ${notificationData.status} - Payable ${dynamicCurrencyforTxtValue(notificationData.multibookTotalAmount)}
        - Requested by ${notificationData.userFullname} - Total Booked Quantity ${notificationData.totalQuantity} - ${isMultibookIndicator}`;
        newNotification.id = `notif-${notificationData.multiBookId}`;
    }
    else{
        newNotification.textContent = `Room ${notificationData.roomName} - Reservation ${notificationData.status} - Payable ${dynamicCurrencyforTxtValue(notificationData.total)}
        - Requested by ${notificationData.userFullname} - Quantity ${notificationData.roomQuantity}`;
        newNotification.id = `notif-${notificationData.Id}`;
    }

    if(notificationData.status === 'rejected'){
        newNotification.classList.add('background-red');
    }
    else if(notificationData.status === 'approved'){
        newNotification.classList.add('background-green');
    }
    notificationContainer.appendChild(newNotification);


    newNotification.addEventListener('click', function(){

        if(notificationData.multiBookId){
            
            openMultiReservationNotification(notificationData);
        }
        else {
            openSingleReservationNotification(notificationData);
        }
        
    })
}

function openSingleReservationNotification(notificationData) {
    const notificationId = notificationData.Id;
    if(openReservationNotificationModal.classList.contains('display-none')){
        openReservationNotificationModal.classList.remove('display-none');

        const singleBooking = document.querySelectorAll('.singleBooking');
        const multiBookOpenReciept = document.querySelectorAll('.multiBookOpenReciept');

        singleBooking.forEach(Booking => {

            if(Booking.classList.contains('display-none')){
                Booking.classList.remove('display-none');
            }

        })

        multiBookOpenReciept.forEach(bookReciept => {
            if(!bookReciept.classList.contains('display-none')){
                bookReciept.classList.add('display-none');
            }
        })
    }
    

    const url = "controller/roomsController.php";
    const data = {
        openReservationNotification: true,
        notificationId: notificationId,
    };
    let specificDate;
    handlePostRequest(url,data )
    .then((response) => {
        // console.log('response: ', response)
        let jsonResponse = JSON.parse(response);
        let roomDetails = jsonResponse.rooms[0];
        let nonManualFields = document.querySelectorAll('.noneManual');
        let onlyForManualFields = document.querySelectorAll('.only-for-manual');
        const ifPartial = document.querySelectorAll('.ifPartial');
        let isPartialVal = 'False';
        if(notificationData.isPartial == 1){
            isPartialVal = 'True';

            ifPartial.forEach((item) => {
                if(item.classList.contains('display-none')){
                    item.classList.remove('display-none');
                }
            });
        }
        else{
            ifPartial.forEach((item) => {
                if(!item.classList.contains('display-none')){
                    item.classList.add('display-none');
                }
            });
        }
        let showIfRejected = document.querySelectorAll('.showIfRejected');
        if(roomDetails.reservationStatus != 'rejected'){
            showIfRejected.forEach(val => {
                if(!val.classList.contains('display-none')){
                    val.classList.add('display-none')
                }
            })
        }
        else{
            showIfRejected.forEach(val => {
                if(val.classList.contains('display-none')){
                    val.classList.remove('display-none')
                }
            })
        }
        ReservationCheckInId = roomDetails.checkInId
        document.getElementById('reservationStatus').innerText = `Reservation ${roomDetails.reservationStatus}`;
        document.getElementById('reservedRoomName').innerText = roomDetails.roomName;
        document.getElementById('reservedRoomCapacity').innerText = roomDetails.roomMaxCap;
        document.getElementById('customerFullName').innerText = roomDetails.customerfullName;
        document.getElementById('customerAddress').innerText = roomDetails.customerCompleteAddress;
        document.getElementById('customerCotact').innerText = roomDetails.customerContactInfo;
        document.getElementById('selectedPaymentMethod').innerText = roomDetails.paymentMethodName;
        document.getElementById('selectedReservationQuantity').innerText = roomDetails.reservationQuantity;
        document.getElementById('totaLPayable').innerText = dynamicCurrencyforTxtValue(roomDetails.reservationTotalPayable);
        document.getElementById('expectedCheckInDate').innerText = roomDetails.checkInDate;
        document.getElementById('expectedCheckOutDate').innerText = roomDetails.checkOutDate;
        document.getElementById('expectedReservationPayDateTime').innerText = roomDetails.queueDateTime;
        document.getElementById('rejectionMessage').innerText = roomDetails.reservationMessage;
        document.getElementById('isPartial').innerText = isPartialVal;
        document.getElementById('allocatedPartial').innerText = dynamicCurrencyforTxtValue(notificationData.partialPayment);
        specificDate = roomDetails.checkOutDate; 
        roomDetailsData = roomDetails;
        

        if(roomDetails.paymentMethodName != 'Manual') {
            
            nonManualFields.forEach(nonManualField => {
                nonManualField.classList.remove('display-none')
            });

            onlyForManualFields.forEach(onlyForManualField => {
                onlyForManualField.classList.add('display-none')
            });

            let image = document.getElementById('viewPaymentEvidence');
            let imageJSON = JSON.parse(roomDetails.imageLink);
            let imgLink;
            if (imageJSON && imageJSON[0] && imageJSON[0].Link){
                imgLink = imageJSON[0].Link;
                console.log(imgLink)
                image.src = imgLink;
            }


            if (cardWrapperEvidence && cardWrapperEvidence.children.length > 0) {
                cardWrapperEvidence.innerHTML = '';
            }

            // console.log(imageJSON)
            if (imageJSON && imageJSON[0] && imageJSON[0].Link){
                imageJSON.forEach(img => {
                    displayRoomsInSliderWithWrapper(img, cardWrapperEvidence);
                });
            }
            
            initializeSwiperWithParam('slide-container-evidence', 'wrapper-evidence');

            document.querySelectorAll('.imageList').forEach(imgList => {
                imgList.addEventListener('click', function() {
                    image.src = imgList.src;
                })
            })

            image.addEventListener('click', function() {
                const imageUrl = image.src;
                const newTab = window.open(imageUrl, '_blank');
                newTab.focus();
            });
        }
        else {
            nonManualFields.forEach(nonManualField => {
                nonManualField.classList.add('display-none')
            });

            onlyForManualFields.forEach(onlyForManualField => {
                onlyForManualField.classList.remove('display-none')
            });
        }

    })
    .catch((error) => {
        alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
        console.log("Error:", error);
    });
    

    let moreDetails = document.querySelectorAll('.more-details');  
    let bookingDetails = document.querySelectorAll('.booking-details');  
    const currentDate = new Date().toISOString().split('T')[0]; // Get current date in YYYY-MM-DD format
    // console.log(currentDate)
    if(typeof openFromTable !== 'undefined' && openFromTable && openFromTable.length > 0){
        moreDetails.forEach(button => {
            if(!button.classList.contains('hide-notif-btn')){
                button.classList.add('hide-notif-btn')
            }
        })

        
        let setIntervalNew = setInterval(function(){
            if(specificDate){
                console.log(document.getElementById('reservationStatus').innerText)
                clearInterval(setIntervalNew);
                if (specificDate <= currentDate && document.getElementById('reservationStatus').innerText == 'Reservation approved' ) {
                    if(rateRoom.classList.contains('hide-notif-btn')){
                        rateRoom.classList.remove('hide-notif-btn')
                    }

                    
                    bookingDetails.forEach(button => {
                    if(button.classList.contains('hide-notif-btn')){
                        button.classList.remove('hide-notif-btn')
                        }
                    })
                }
                else{
                    if(!rateRoom.classList.contains('hide-notif-btn')){
                        rateRoom.classList.add('hide-notif-btn')
                    }
                }
            
            }

        },100)

       openFromTable = null;
    }
    else{
        moreDetails.forEach(button => {
            if(button.classList.contains('hide-notif-btn')){
                button.classList.remove('hide-notif-btn')
            }
        })



        let setIntervalNew1 = setInterval(function(){
            if(specificDate){
                clearInterval(setIntervalNew1);
                if (specificDate > currentDate ) {
                    if(!rateRoom.classList.contains('hide-notif-btn')){
                        rateRoom.classList.add('hide-notif-btn')
                    }
                }
                else{
                    if(rateRoom.classList.contains('hide-notif-btn')){
                        rateRoom.classList.remove('hide-notif-btn')
                    }
                }


                bookingDetails.forEach(button => {
                    if(!button.classList.contains('hide-notif-btn')){
                        button.classList.add('hide-notif-btn')
                    }
                })
            
            }

        },100)

        
    }
}

function openMultiReservationNotification(multiBook){

    if(openReservationNotificationModal.classList.contains('display-none')){
        openReservationNotificationModal.classList.remove('display-none');

        const singleBooking = document.querySelectorAll('.singleBooking');
        const multiBookOpenReciept = document.querySelectorAll('.multiBookOpenReciept');

        singleBooking.forEach(Booking => {

            if(!Booking.classList.contains('display-none')){
                Booking.classList.add('display-none');
            }

        })

        multiBookOpenReciept.forEach(bookReciept => {
            if(bookReciept.classList.contains('display-none')){
                bookReciept.classList.remove('display-none');
            }
        })
    }
    
    const multiBookOpenRecieptContainer =  document.querySelector('.multiBookOpenReciept-container')

        // Check if the child element exists
    const child = multiBookOpenRecieptContainer.querySelectorAll('.multiBookOpenReciept-child');
    // Remove the child element if it exists
    if (child) {
        child.forEach(value => {
            multiBookOpenRecieptContainer.removeChild(value);
        })
    }

    const ifPartial = document.querySelectorAll('.ifPartial');
    let isPartialVal = 'False';
    if(multiBook.isPartial == 1){
        isPartialVal = 'True';

        ifPartial.forEach((item) => {
            if(item.classList.contains('display-none')){
                item.classList.remove('display-none');
            }
        });
    }
    else{
        ifPartial.forEach((item) => {
            if(!item.classList.contains('display-none')){
                item.classList.add('display-none');
            }
        });
    }

    let showIfRejected = document.querySelectorAll('.showIfRejected');
    if(multiBook.status != 'rejected'){
        showIfRejected.forEach(val => {
            if(!val.classList.contains('display-none')){
                val.classList.add('display-none')
            }
        })
    }
    else{
        showIfRejected.forEach(val => {
            if(val.classList.contains('display-none')){
                val.classList.remove('display-none')
            }
        })
    }
    
    let nonManualFields = document.querySelectorAll('.noneManual');
    let onlyForManualFields = document.querySelectorAll('.only-for-manual');
    let imageJSON;
    ReservationCheckInId = multiBook.multiBookId
    document.getElementById('reservationStatus').innerText = `Reservation ${multiBook.status}`;
    document.getElementById('customerFullName').innerText = multiBook.userFullname;
    document.getElementById('customerAddress').innerText = multiBook.checkInCustomerCompleteAddress;
    document.getElementById('customerCotact').innerText = multiBook.checkInCustomerContactInfo;
    
    document.getElementById('selectedReservationQuantity').innerText = multiBook.totalQuantity;
    document.getElementById('totaLPayable').innerText = dynamicCurrencyforTxtValue(multiBook.multibookTotalAmount);
    document.getElementById('expectedCheckInDate').innerText = multiBook.checkInDate;
    document.getElementById('expectedCheckOutDate').innerText = multiBook.chekOutDate;
    document.getElementById('isPartial').innerText = isPartialVal;
    document.getElementById('allocatedPartial').innerText = dynamicCurrencyforTxtValue(multiBook.multiBookPartialPayment);
    
    const url = "controller/roomsController.php";
    const data = {
        openMultiReservationNotification: true,
        notificationId: multiBook.multiBookId,
    };

    handlePostRequest(url,data )
    .then((response) => {
        let jsonResponse = JSON.parse(response);
        let rowsList = jsonResponse.newRows;
        // console.log('response: ', rowsList);

        rowsList.forEach(row => {
            const newDiv = document.createElement('div');
            newDiv.className = 'multiBookOpenReciept-child';
            let partialColomn = '';
            if(multiBook.isPartial == 1){
                partialColomn = `<div><span>Partial Payment: </span><span>${dynamicCurrencyforTxtValue(`${row.partialPayment}`)}</span></div>`
            }
            else{
                partialColomn = '';
            }

            const content = `
            <div><span>Selected Room: </span><span>${row.roomName}</span></div>
            <div><span>Reserved Quantity: </span><span>${row.reservationQuantity}</span></div>
            ${partialColomn}
            <div><span>Total Payable: </span><span>${dynamicCurrencyforTxtValue(`${row.reservationTotalPayable}`)}</span></div>
            `;

            newDiv.innerHTML = content;
            multiBookOpenRecieptContainer.appendChild(newDiv);

            document.getElementById('selectedPaymentMethod').innerText = row.paymentMethodName;
            document.getElementById('rejectionMessage').innerText = row.reservationMessage;
            document.getElementById('expectedReservationPayDateTime').innerText = row.queueDateTime;
            imageJSON = JSON.parse(row.imageLinks);
        })

        if(document.getElementById('selectedPaymentMethod').innerText != 'Manual') {
            
            nonManualFields.forEach(nonManualField => {
                nonManualField.classList.remove('display-none')
            });

            onlyForManualFields.forEach(onlyForManualField => {
                onlyForManualField.classList.add('display-none')
            });

            let image = document.getElementById('viewPaymentEvidence');
            let imgLink = imageJSON[0].Link;
            image.src = imgLink;

            if (cardWrapperEvidence && cardWrapperEvidence.children.length > 0) {
                cardWrapperEvidence.innerHTML = '';
            }

            imageJSON.forEach(img => {
                displayRoomsInSliderWithWrapper(img, cardWrapperEvidence);
            });
            initializeSwiperWithParam('slide-container-evidence', 'wrapper-evidence');

            document.querySelectorAll('.imageList').forEach(imgList => {
                imgList.addEventListener('click', function() {
                    image.src = imgList.src;
                })
            })

            image.addEventListener('click', function() {
                const imageUrl = image.src;
                const newTab = window.open(imageUrl, '_blank');
                newTab.focus();
            });
        }
        else {
            nonManualFields.forEach(nonManualField => {
                nonManualField.classList.add('display-none')
            });

            onlyForManualFields.forEach(onlyForManualField => {
                onlyForManualField.classList.remove('display-none')
            });
        }

        
    })
    .catch((error) => {
        alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
        console.log("Error:", error);
    });



}

function closeAllOpenedModal(){
    document.querySelector('.checkin-approval-confirmation').classList.add('modal-hide');
    document.querySelector('.checkin-rejection-confirmation').classList.add('modal-hide');
    if (addRoomModal && !addRoomModal.classList.contains('display-none')) {
        addRoomModal.classList.add('display-none');
    }
    if (roomDetailsModal && !roomDetailsModal.classList.contains('display-none')) {
        roomDetailsModal.classList.add('display-none');
    }
    if (openReservationNotificationModal && !openReservationNotificationModal.classList.contains('display-none')) {
        openReservationNotificationModal.classList.add('display-none');
    }

}

function approveCheckInRequest() {
    const url = "controller/notificationListenerController.php";
    const data = {
        approve: true,
        ReservationCheckInId: ReservationCheckInId,
    };
    handlePostRequest(url,data )
    .then((response) => {
        var jsonResponse = JSON.parse(response);
        // console.log(jsonResponse)
        if(jsonResponse.approved === true) {    
            alertMessage('You have successfully approved a check-in reservation request.', 'success', 3000);
        }
        else {
            alertMessage('Approve failed. Something went wrong.', 'warning', 3000);
            // console.log(response)
        }
    })
    .catch((error) => {
        alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
        console.log("Error:", error);
    });

    closeAllOpenedModal();
}

function rejectCheckInRequest() {
    const rejectMessageBox =  document.querySelector('.reject-message-box');
    const rejectMessageBoxLabel = document.querySelector('.reject-message-box-error-message-label');
    let rejectable = true;

    if(rejectMessageBox && rejectMessageBox.value.length < 1){
        rejectMessageBoxLabel.classList.remove('modal-hide');
        rejectable = false;
    }
    else{
        if(!rejectMessageBoxLabel.classList.contains('modal-hide')){
            rejectMessageBoxLabel.classList.add('modal-hide');
            rejectable = true;
        }
    }

    if(rejectable){
        const url = "controller/notificationListenerController.php";
        const data = {
            reject: true,
            ReservationCheckInId: ReservationCheckInId,
            rejectionReason: rejectMessageBox.value
        };
        handlePostRequest(url,data )
        .then((response) => {
            var jsonResponse = JSON.parse(response);
            if(jsonResponse.rejected === true) {    
                alertMessage('You have successfully rejected a check-in reservation request.', 'success', 3000);
            }
            else {
                alertMessage('Rejection failed. Something went wrong.', 'warning', 3000);
                // console.log(response)
            }
        })
        .catch((error) => {
            alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
            console.log("Error:", error);
        });

        closeAllOpenedModal();
    }
}

function markAsRead() {
    const url = "controller/notificationListenerController.php";
    const data = {
        markAsRead: true,
        ReservationCheckInId: ReservationCheckInId,
    };
    handlePostRequest(url,data )
    .then((response) => {
        var jsonResponse = JSON.parse(response);
        if(jsonResponse.read === true) {    
            alertMessage('Notification was confirmed by the customer.', 'success', 3000);
        }
        else {
            alertMessage('Something went wrong confirming.', 'warning', 3000);
            // console.log(response)
        }
    })
    .catch((error) => {
        alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
        console.log("Error:", error);
    });

    closeAllOpenedModal();
}

document.getElementById('approveReservation').addEventListener('click', function(){
    document.querySelector('.checkin-approval-confirmation').classList.remove('modal-hide');
})
document.querySelector('.approve').addEventListener('click', function() {
    approveCheckInRequest();
});
document.querySelector('.cancelApprove').addEventListener('click', function(){
    document.querySelector('.checkin-approval-confirmation').classList.add('modal-hide');
})

document.getElementById('rejectReservation').addEventListener('click', function(){
    document.querySelector('.checkin-rejection-confirmation').classList.remove('modal-hide');
})
document.querySelector('.reject').addEventListener('click', function() {
    rejectCheckInRequest();
});
document.querySelector('.cancelReject').addEventListener('click', function(){
    document.querySelector('.checkin-rejection-confirmation').classList.add('modal-hide');
})

document.getElementById('markAsRead').addEventListener('click', function(){
    markAsRead();
})

if(rateRoom){
    const openRateRoomModal = document.getElementById('openRateRoomModal');
    rateRoom.addEventListener('click', function(){

        const roomDetailsDataInterval = setInterval(function(){

            if(roomDetailsData){
                clearInterval(roomDetailsDataInterval)
                // console.log(roomDetailsData)

                if(openRateRoomModal.classList.contains('display-none')){
                    openRateRoomModal.classList.remove('display-none')

                    let starRatingElement = document.getElementById("room-rating-point-stars");
                    starRatingElement.style.setProperty('--rating', 5.0);
                    const ratingsInput = document.getElementById('ratingsInput');
                    ratingsInput.value = '';
                }
            }

        },100)

    })

    let closeRateModal = document.getElementById('closeRateModal');

    if(closeRateModal){
        closeRateModal.addEventListener('click', function(){
            if(!openRateRoomModal.classList.contains('display-none')){
                openRateRoomModal.classList.add('display-none')
            }
        })
    }

}

function clearNotifications() {
    const notificationContainer = document.querySelector('.notification-container');
    while (notificationContainer.firstChild) {
        notificationContainer.removeChild(notificationContainer.firstChild);
    }
}

closeReservationNotification.addEventListener('click', function() {
    if( !openReservationNotificationModal.classList.contains('display-none'))
    {
        !openReservationNotificationModal.classList.add('display-none')
    }
    
})

$(".notification-drop .item").on('click',function() {
    $(this).find('ul').toggle();
});

setInterval(checkForNewRows, 1000);
