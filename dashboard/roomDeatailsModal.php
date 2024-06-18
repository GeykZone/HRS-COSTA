<link rel="stylesheet" href="css/modal.css" />
<div class="details-modal" id="roomDetails">
      <div class="details-modal-title" id="details-modal-titleId">
        <div class="modalNaveHead">
          <h1 id="roomName"></h1>
          <div class="details-modal-close" id="roomDetailsModalClose">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" class="clickable" id="closeroomDetails">
              <path  fill-rule="evenodd" clip-rule="evenodd" d="M13.7071 1.70711C14.0976 1.31658 14.0976 0.683417 13.7071 0.292893C13.3166 -0.0976311 12.6834 -0.0976311 12.2929 0.292893L7 5.58579L1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L5.58579 7L0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683417 14.0976 1.31658 14.0976 1.70711 13.7071L7 8.41421L12.2929 13.7071C12.6834 14.0976 13.3166 14.0976 13.7071 13.7071C14.0976 13.3166 14.0976 12.6834 13.7071 12.2929L8.41421 7L13.7071 1.70711Z" fill="black" />
            </svg>
          </div>
        </div>
        <div class="roomDetailsNavBar-container">
        <select id="select-single-rates" class="pick-list selectAvailableRate" placeholder="Select Available Rates...">  
        </select>
        <span class="roomQuantityInShowSingleRoom"><h4 id="quantityText"></h4></span>
        </div>
      </div>
      <div class="details-modal-content">
      <div class="description-container">
        <div class="discription-header">
          <h3> <i class="fa-solid fa-user-group"></i> Maximum Capacity : <span id="capacityTotal">3</span> </h3>
        </div>
        <div class="discription-body">
          <h3> <i class="fa-solid fa-list-check"></i> Room Description  </h3>
          <p id="description-text"></p>
        </div>
      </div>
        <div class="room-form-container">
          <div class="viewRoomMainImage-container"><img src="" alt="main-image" id="viewRoomMainImage"></div>
          <div class="slider-container" id="singleRoomSliderContainerID">
          <div class="container swiper">
              <div class="slide-container">
              <div class="card-wrapper swiper-wrapper wrapper-forRoomDetails">
              </div>
              </div>
              <div class="swiper-button-next swiper-navBtn"></div>
              <div class="swiper-button-prev swiper-navBtn"></div>
              <div class="swiper-pagination"></div>
          </div>
          </div>
        </div>
      <div class="amenities-container">
        <div class="amenities-header">
          <h3><i class="fa-solid fa-cubes"></i> Amenities</h3>
        </div>
        <div class="amenities-body">
        </div>
      </div>
      </div>
      <div class="details-modal-footer">
        <span class="clickable" id="selectedPrice" style="width: 10rem;">P1250.00</span>
        <span class="clickable costa-btn-a customer" id="bookThisRoom">Book Now</span>
        <span class="clickable costa-btn-a admin" id="submitChanges" style="width: 10rem;">Edit Details</span>
      </div>
</div>