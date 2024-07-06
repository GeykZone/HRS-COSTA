<link rel="stylesheet" href="css/modal.css" />
<div class="details-modal" id="openReservationNotificationModal">
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

        <div class="discription-header">
                <h3> <i class="fa-solid fa-hotel"></i> Room Name : <span id="reservedRoomName">Loading...</span> </h3>
        </div>

        <div class="discription-header">
            <h3> <i class="fa-solid fa-users"></i> Room Capacity : <span id="reservedRoomCapacity">Loading...</span> </h3>
        </div>
        
        <div class="discription-header customer">
            <h3> <i class="fa-regular fa-comment"></i> Rejection Reason : <span id="rejectionMessage">Loading...</span> </h3>
        </div>

        <div class="discription-header">
            <h3> <i class="fa-solid fa-user-group"></i> Customer's Full Name : <span id="customerFullName">Loading...</span> </h3>
        </div>
        <div class="discription-header">
            <h3> <i class="fa-solid fa-location-dot"></i> Customer's Complete Address : <span id="customerAddress">Loading...</span> </h3>
        </div>
        <div class="discription-header">
            <h3> <i class="fa-solid fa-address-book"></i> Customer's Contact Info. : <span id="customerCotact">Loading...</span> </h3>
        </div>

        <div class="discription-header">
            <h3> <i class="fa-solid fa-money-check-dollar"></i> Selected Payment Method : <span id="selectedPaymentMethod">Loading...</span> </h3>
        </div>

        <div class="discription-header">
            <h3> <i class="fa-solid fa-pen-to-square"></i> Reservation Quantity : <span id="selectedReservationQuantity">Loading...</span> </h3>
        </div>
        
         <h3 class="display-none noneManual"> <i class="fa-solid fa-file-invoice-dollar "></i> Payment Image Evidence </h3>
        <div class="viewRoomMainImage-paymentEvidence display-none noneManual"><img src="" alt="main-image" class="clickable" id="viewPaymentEvidence"></div>
        <div class="slider-container display-none noneManual" id="singleRoomSliderContainerID">
        <div class="container swiper">
        <div class="slide-container-evidence">
        <div class="card-wrapper-evidence swiper-wrapper wrapper-evidence">

            <!-- <div class="card swiper-slide">
            <div class="image-box">
                <img src="https://h-img1.cloudbeds.com/199021/1669205371354_featured~~638ec42d17964.jpg" alt="" />
            </div>
            <div class="profile-details">
                <img src="https://h-img1.cloudbeds.com/199021/1669205371354_featured~~638ec42d17964.jpg" alt="" />
                <div class="name-job">
                <h3 class="name">Room A</h3>
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

        <div class="discription-header">
            <h3> <i class="fa-solid fa-money-bill"></i> Expected Paid Amount : <span id="expectedPaidAmount">Loading...</span> </h3>
        </div>

        <div class="discription-header">
            <h3> <i class="fa-solid fa-money-bill"></i> Total Payable Amount : <span id="totaLPayable">Loading...</span> </h3>
        </div>

        <div class="discription-header only-for-manual">
            <h3> <i class="fa-solid fa-calendar-check"></i> Expected Date/Time To Pay Reservation : <span id="expectedReservationPayDateTime">Loading...</span> </h3>
        </div>

        <div class="discription-header">
            <h3>  <i class="fa-solid fa-check-to-slot"></i> Expected Check-in Date : <span id="expectedCheckInDate">Loading...</span> </h3>
        </div>

        <div class="discription-header">
            <h3> <i class="fa-solid fa-arrow-right-from-bracket"></i> Expected Check-out Date : <span id="expectedCheckOutDate">Loading...</span> </h3>
        </div>
      </div>
      <div class="room-form-container" id="rejectionMessageContainer">

      </div>
      </div>
      <div class="details-modal-footer">
        <span class="clickable costa-btn-d admin" id="rejectReservation" style="width: 10rem;">Reject</span>
        <span class="clickable costa-btn-c admin" id="approveReservation" style="width: 10rem;">Approve</span>
        <span class="clickable costa-btn-a customer" id="markAsRead" style="width: 10rem;">Mark as Read</span>
      </div>
</div>