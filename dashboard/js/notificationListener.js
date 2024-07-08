let addRoomModal = document.getElementById('addRoomModal');
let roomDetailsModal = document.getElementById('roomDetails');
let defaultNotficationNumber = 0;
let currentUserRole = userOrAdminDetails.role;
let notificationPostData;
let ReservationCheckInId;
let openReservationNotificationModal = document.getElementById('openReservationNotificationModal');
let closeReservationNotification = document.getElementById('closeReservationNotification');

$(document).ready(function() {
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

                        clearNotifications();

                        notifications.forEach(notification => {
                            let notificationData = {
                                Id: notification.checkInId,
                                status: notification.checkInStatus,
                                roomName: notification.roomName,
                                total: notification.checkInTotalAmount,
                                userFullname: notification.checkInCustomerFullName,
                                roomQuantity: notification.checkInQuantity
                            };
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

        const notificationContainer = document.querySelector('.notification-container');        
        const newNotification = document.createElement('li');
        newNotification.textContent = `Room ${notificationData.roomName} - Reservation ${notificationData.status} - Payable ${dynamicCurrencyforTxtValue(notificationData.total)}
         - Requested by ${notificationData.userFullname} - Quantity ${notificationData.roomQuantity}`;
        newNotification.id = `notif-${notificationData.Id}`;
        if(notificationData.status === 'rejected'){
            newNotification.classList.add('background-red');
        }
        else if(notificationData.status === 'approved'){
            newNotification.classList.add('background-green');
        }
        notificationContainer.appendChild(newNotification);
        const notificationId = notificationData.Id;


        newNotification.addEventListener('click', function(){
            openReservationNotification(notificationId);
        })
    }

    function openReservationNotification(notificationId) {
        openReservationNotificationModal.classList.add('show');

        const url = "controller/roomsController.php";
        const data = {
            openReservationNotification: true,
            notificationId: notificationId,
        };

        handlePostRequest(url,data )
        .then((response) => {
            // console.log('response: ', response)
            let jsonResponse = JSON.parse(response);
            let roomDetails = jsonResponse.rooms[0];
            let nonManualFields = document.querySelectorAll('.noneManual');
            let onlyForManualFields = document.querySelectorAll('.only-for-manual');
            ReservationCheckInId = roomDetails.checkInId
            document.getElementById('reservationStatus').innerText = `Reservation ${roomDetails.reservationStatus}`;
            document.getElementById('reservedRoomName').innerText = roomDetails.roomName;
            document.getElementById('reservedRoomCapacity').innerText = roomDetails.roomMaxCap;
            document.getElementById('customerFullName').innerText = roomDetails.customerfullName;
            document.getElementById('customerAddress').innerText = roomDetails.customerCompleteAddress;
            document.getElementById('customerCotact').innerText = roomDetails.customerContactInfo;
            document.getElementById('selectedPaymentMethod').innerText = roomDetails.paymentMethodName;
            document.getElementById('selectedReservationQuantity').innerText = roomDetails.reservationQuantity;
            document.getElementById('expectedPaidAmount').innerText = roomDetails.paidAmount;
            document.getElementById('totaLPayable').innerText = roomDetails.reservationTotalPayable;
            document.getElementById('expectedCheckInDate').innerText = roomDetails.checkInDate;
            document.getElementById('expectedCheckOutDate').innerText = roomDetails.checkOutDate;
            document.getElementById('expectedReservationPayDateTime').innerText = roomDetails.queueDateTime;
            document.getElementById('rejectionMessage').innerText = roomDetails.reservationMessage;

            if(roomDetails.paymentMethodName != 'Manual') {
                
                nonManualFields.forEach(nonManualField => {
                    nonManualField.classList.remove('display-none')
                });

                onlyForManualFields.forEach(onlyForManualField => {
                    onlyForManualField.classList.add('display-none')
                });

                let image = document.getElementById('viewPaymentEvidence');
                let imageJSON = JSON.parse(roomDetails.imageLink);
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
        if (addRoomModal && addRoomModal.classList.contains('show')) {
            addRoomModal.classList.remove('show');
        }
        if (roomDetailsModal && roomDetailsModal.classList.contains('show')) {
            roomDetailsModal.classList.remove('show');
        }
        if (openReservationNotificationModal && openReservationNotificationModal.classList.contains('show')) {
            openReservationNotificationModal.classList.remove('show');
        }
        if (closeReservationNotification && closeReservationNotification.classList.contains('show')) {
            closeReservationNotification.classList.remove('show');
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
            if(jsonResponse.approved === true) {    
                alertMessage('You have successfully approved a check-in reservation request.', 'success', 3000);
            }
            else {
                alertMessage('Approve failed. Something went wrong.', 'warning', 3000);
                console.log(response)
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
                    console.log(response)
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
                console.log(response)
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

    function clearNotifications() {
        const notificationContainer = document.querySelector('.notification-container');
        while (notificationContainer.firstChild) {
            notificationContainer.removeChild(notificationContainer.firstChild);
        }
    }

    closeReservationNotification.addEventListener('click', function() {
        openReservationNotificationModal.classList.remove('show');
    })

    $(".notification-drop .item").on('click',function() {
        $(this).find('ul').toggle();
    });

    setInterval(checkForNewRows, 1000);
});