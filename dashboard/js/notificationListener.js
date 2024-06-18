$(document).ready(function() {

    let defaultNotficationNumber = 0;
    let currentUserRole = userOrAdminDetails.role;
    let notificationPostData;
    let ReservationCheckInId;
    let openReservationNotificationModal = document.getElementById('openReservationNotificationModal');
    let closeReservationNotification = document.getElementById('closeReservationNotification');

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
        notificationContainer.appendChild(newNotification);
        const notificationId = notificationData.Id;


        newNotification.addEventListener('click', function(){
            openReserationNotification(notificationId);
        })
    }

    function openReserationNotification(notificationId) {
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
                    displayRoomsInSlider(img, cardWrapperEvidence);
                });
                initializeSwiper('slide-container-evidence', 'wrapper-evidence');

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

    document.getElementById('approveReservation').addEventListener('click', function(){
        alert(ReservationCheckInId)
    })

    document.getElementById('rejectReservation').addEventListener('click', function(){
        alert(`Reject ${ReservationCheckInId}`)
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