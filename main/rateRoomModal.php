<div class="details-modal-back display-none" id="openRateRoomModal">
<div class="details-modal show" id="openRateRoomModalInnerBody">
      <div class="details-modal-title" id="details-modal-titleId">
        <div class="modalNaveHead">
          <h1 id="rateRoomModalTitle">Rating Modal</h1>
          <div class="details-modal-close">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" class="clickable" id="closeRateModal">
              <path  fill-rule="evenodd" clip-rule="evenodd" d="M13.7071 1.70711C14.0976 1.31658 14.0976 0.683417 13.7071 0.292893C13.3166 -0.0976311 12.6834 -0.0976311 12.2929 0.292893L7 5.58579L1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L5.58579 7L0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683417 14.0976 1.31658 14.0976 1.70711 13.7071L7 8.41421L12.2929 13.7071C12.6834 14.0976 13.3166 14.0976 13.7071 13.7071C14.0976 13.3166 14.0976 12.6834 13.7071 12.2929L8.41421 7L13.7071 1.70711Z" fill="black" />
            </svg>
          </div>
        </div>
      </div>
      <div class="details-modal-content">
        <div class="room-form-container">
          <div>
              <h1 class="Stars" id="room-rating-point-stars" style="--rating: 5.0;" aria-label=""></h1>
          </div>
          <div class="input-container">
            <label style ="margin-bottom: 8px;" for="ratingsInput">To make this room stand out even more, you can provide a rating. This will help other customers make informed decisions and easily identify the best room to choose based on your feedback.</label>
              <input 
                  type="number" 
                  class="room-input form-d" 
                  placeholder="Rating Field *" 
                  id="ratingsInput" 
                  min="1" 
                  max="5" 
                  step="1"
              />
              <div id="ratingsInput-error" class="room-error-message-label form-d-label error"></div>
          </div>
        </div>
      </div>
      <div class="details-modal-footer" id="openRateRoomModalFooter">
        <span class="clickable costa-btn-a customer" id="submitRating" style="width: 10rem;">Submit</span>
      </div>
</div>
</div>

<style>
  #openRateRoomModalInnerBody{
    max-width: 500px !important;
  }

  #openRateRoomModalFooter{
    width: 100% !important;
    display: flex !important;
    justify-content: center !important;
  }
</style>
