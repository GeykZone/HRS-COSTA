<div class="details-modal-back display-none" id="openReservationNotificationModal">
<div class="details-modal show" >
      <div class="details-modal-title" id="details-modal-titleId">
        <div class="modalNaveHead">
          <h1 id="reservationStatus">Loading...</h1>
          <div class="details-modal-close">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" class="clickable" id="closeReservationNotification">
              <path  fill-rule="evenodd" clip-rule="evenodd" d="M13.7071 1.70711C14.0976 1.31658 14.0976 0.683417 13.7071 0.292893C13.3166 -0.0976311 12.6834 -0.0976311 12.2929 0.292893L7 5.58579L1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L5.58579 7L0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683417 14.0976 1.31658 14.0976 1.70711 13.7071L7 8.41421L12.2929 13.7071C12.6834 14.0976 13.3166 14.0976 13.7071 13.7071C14.0976 13.3166 14.0976 12.6834 13.7071 12.2929L8.41421 7L13.7071 1.70711Z" fill="black" />
            </svg>
          </div>
        </div>
      </div>
      <div class="details-modal-content">
      <div class="room-form-container">


        <div class="multiBookOpenReciept" style="margin-bottom: -10px;">
            <span> <i style="margin-right: 10px;"  class="fa-solid fa-receipt"></i> Multi Booked Room List: </span>
        </div>

        <div class="multiBookOpenReciept-container multiBookOpenReciept display-none">
            <!-- <div class="multiBookOpenReciept-child">
                 <div><span>Selected Room: </span><span>sample</span></div>
                 <div><span>Selected Room: </span><span>sample</span></div>
                 <div><span>Selected Room: </span><span>sample</span></div>
            </div>

            <div class="multiBookOpenReciept-child">
                 <div><span>Selected Room: </span><span>sample</span></div>
                 <div><span>Selected Room: </span><span>sample</span></div>
                 <div><span>Selected Room: </span><span>sample</span></div>
            </div>

            <div class="multiBookOpenReciept-child">
                 <div><span>Selected Room: </span><span>sample</span></div>
                 <div><span>Selected Room: </span><span>sample</span></div>
                 <div><span>Selected Room: </span><span>sample</span></div>
            </div>

            <div class="multiBookOpenReciept-child">
                 <div><span>Selected Room: </span><span>sample</span></div>
                 <div><span>Selected Room: </span><span>sample</span></div>
                 <div><span>Selected Room: </span><span>sample</span></div>
            </div>

            <div class="multiBookOpenReciept-child">
                 <div><span>Selected Room: </span><span>sample</span></div>
                 <div><span>Selected Room: </span><span>sample</span></div>
                 <div><span>Selected Room: </span><span>sample</span></div>
            </div> -->
        </div>

        <span class="display-none noneManual"> <i style="margin-right: 10px;" class="fa-solid fa-file-invoice-dollar "></i> Payment Image Evidence </span>
        <div class="viewRoomMainImage-paymentEvidence display-none noneManual"><img src="" alt="main-image" class="clickable" id="viewPaymentEvidence"></div>
        <div class="slider-container display-none noneManual" id="singleRoomSliderContainerID">
        <div class="container swiper notificationSwiper">
        <div class="slide-container-evidence">
        <div class="card-wrapper-evidence swiper-wrapper wrapper-evidence">

            <!-- <div class="card swiper-slide">
            <div class="image-box">
                <img src="https://h-img1.cloudbeds.com/199021/1669205371354_featured~~638ec42d17964.jpg" alt="" />
            </div>
            <div class="profile-details">
                <img src="https://h-img1.cloudbeds.com/199021/1669205371354_featured~~638ec42d17964.jpg" alt="" />
                <div class="name-job">
                <span class="name">Room A</span>
                <h4 class="job">Sample Description</h4>
                </div>
            </div>
            </div> -->

        </div>
        </div>
        <div class="swiper-button-next swiper-navBtn"></div>
        <div class="swiper-button-prev swiper-navBtn"></div>
        <div class="swiper-pagination"></div>
      </div>
        </div>

        <span class="notif-detail-label singleBooking">Room Name</span>
        <div class="discription-header singleBooking">
            <span> <i class="fa-solid fa-hotel"></i><span id="reservedRoomName">Loading...</span> </span>
        </div>

        <span class="notif-detail-label singleBooking">Room Capacity</span>
        <div class="discription-header singleBooking">
            <span> <i class="fa-solid fa-users"></i><span id="reservedRoomCapacity">Loading...</span> </span>
        </div>
        
        <span class="notif-detail-label showIfRejected customer">Rejection Reason</span>
        <div class="discription-header showIfRejected customer">
            <span> <i class="fa-regular fa-comment"></i><span id="rejectionMessage">Loading...</span> </span>
        </div>

        <span class="notif-detail-label">Customer's Full Name</span>
        <div class="discription-header">
            <span> <i class="fa-solid fa-user-group"></i><span id="customerFullName">Loading...</span> </span>
        </div>

        <span class="notif-detail-label">Customer's Complete Address</span>
        <div class="discription-header">
            <span> <i class="fa-solid fa-location-dot"></i><span id="customerAddress">Loading...</span> </span>
        </div>

        <span class="notif-detail-label">Customer's Contact Info.</span>
        <div class="discription-header">
            <span> <i class="fa-solid fa-address-book"></i><span id="customerCotact">Loading...</span> </span>
        </div>

        <span class="notif-detail-label">Selected Payment Method</span>
        <div class="discription-header">
            <span> <i class="fa-solid fa-money-check-dollar"></i><span id="selectedPaymentMethod">Loading...</span> </span>
        </div>

        <span class="notif-detail-label">Reservation Quantity</span>
        <div class="discription-header">
            <span> <i class="fa-solid fa-pen-to-square"></i><span id="selectedReservationQuantity">Loading...</span> </span>
        </div>

        <span class="notif-detail-label">Total Payable Amount</span>
        <div class="discription-header">
            <span> <i class="fa-solid fa-money-bill"></i><span id="totaLPayable">Loading...</span> </span>
        </div>

        <span class="notif-detail-label ifPartial">Partial</span>
        <div class="discription-header ifPartial">
            <span> <i class="fa-solid fa-tag"></i><span id="isPartial">Loading...</span> </span>
        </div>

        <span class="notif-detail-label ifPartial">Partial Payment</span> 
        <div class="discription-header ifPartial">
            <span> <i class="fa-solid fa-comments-dollar"></i><span id="allocatedPartial">Loading...</span> </span> 
        </div>

        <span class="notif-detail-label only-for-manual">Expected Date/Time To Pay Reservation</span>
        <div class="discription-header only-for-manual">
            <span> <i class="fa-solid fa-calendar-check"></i><span id="expectedReservationPayDateTime">Loading...</span> </span>
        </div>

        <span class="notif-detail-label">Expected Check-in Date</span>
        <div class="discription-header">
            <span>  <i class="fa-solid fa-check-to-slot"></i><span id="expectedCheckInDate">Loading...</span> </span>
        </div>

        <span class="notif-detail-label">Expected Check-out Date</span>
        <div class="discription-header">
            <span> <i class="fa-solid fa-arrow-right-from-bracket"></i><span id="expectedCheckOutDate">Loading...</span> </span>
        </div>
      </div>
      <div class="room-form-container" id="rejectionMessageContainer">

      </div>
      </div>
      <div class="details-modal-footer">
        <span class="clickable costa-btn-d admin" id="rejectReservation" style="width: 10rem;">Reject</span>
        <span class="clickable costa-btn-c admin" id="approveReservation" style="width: 10rem;">Approve</span>
        <span class="clickable costa-btn-a customer" id="cancelReservation" style="width: 10rem;">Cancel</span>
        <span class="clickable costa-btn-a customer" id="markAsRead" style="width: 10rem;">Confirm</span>
      </div>
</div>
</div>
