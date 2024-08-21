<div class="details-modal-back display-none"  id="singleRoomBookingModalId">
<div class="details-modal show">
      <div class="details-modal-title">
      <div class="modalNaveHead">
      <h1>Payment Form</h1>
      <div class="details-modal-close" id="singleRoomBookingModalCLose">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" class="clickable">
          <path  fill-rule="evenodd" clip-rule="evenodd" d="M13.7071 1.70711C14.0976 1.31658 14.0976 0.683417 13.7071 0.292893C13.3166 -0.0976311 12.6834 -0.0976311 12.2929 0.292893L7 5.58579L1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L5.58579 7L0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683417 14.0976 1.31658 14.0976 1.70711 13.7071L7 8.41421L12.2929 13.7071C12.6834 14.0976 13.3166 14.0976 13.7071 13.7071C14.0976 13.3166 14.0976 12.6834 13.7071 12.2929L8.41421 7L13.7071 1.70711Z" fill="black" />
        </svg>
      </div>
      </div>
      </div>
      <div class="details-modal-content">
          <form action="" class="room-form-container">

          <div class="selectedRoom-container">
            <span class="fa-solid fa-check-to-slot"></span>
            <span id="selectedSingleRoomName"></span>
          </div>

          <div class="input-container">
            <select id="single-room-payment-picklist"  class="singleRoomPaymentPicklist pick-list selectAvailableRate" placeholder="Select Payment Method">
            </select>
            <div id="single-room-payment-picklist-error" class="room-error-message-label form-d-label error"></div>
          </div>

          <div class="paymentMethodContainerDetails-container pm-container display-none">
            <h4 id="paymenthMethodHeaderName"></h4>
            <div id="qrImg-container" class="pm-qr"> <img  id='qrImageId'  alt=""></div>
            <h4 id="paymentNumber"></h4>
          </div>

          <div class="paymentMethodMsg-container display-none pm-msg-container">
            <h4 id="paymentMethodMsg"></h4>
          </div>

          <div class="input-container">
            <input type="number" class="room-input  form-d" placeholder="Room Quantity *" id="sRoomQuantity" maxlength="10" />
            <div id="sRoomQuantity-error" class="room-error-message-label form-d-label error"></div>
          </div>
          
          <div class="input-container">
            <input type="text" class="room-input  form-d" placeholder="Full Name *" id="sFullName" maxlength="55" />
            <div id="sFullName-error" class="room-error-message-label form-d-label error"></div>
          </div>

          <div class="input-container">
            <input type="text" class="room-input  form-d" placeholder="Complete Address *" id="sCompleteAddress" maxlength="55" />
            <div id="sCompleteAddress-error" class="room-error-message-label form-d-label error"></div>
          </div>

          <div class="input-container">
            <input type="text" class="room-input  form-d" placeholder="Contact Info (Email / Phone Number) *" id="sContactInfo" maxlength="12" />
            <div id="sContactInfo-error" class="room-error-message-label form-d-label error"></div>
          </div>

          <div class="input-container is-partial-checkbox-container-single display-none">
            <span>Check if this payment is partial</span>
            <input type="checkbox" class="room-input checkbox-booking  form-e" id="singleBookPartialPayment" onchange="handleSingleBookPartialPayment(event)"/>
          </div>

          <div class="input-container showIfPartialSingle display-none">
            <input type="text" class="room-input  form-d" placeholder="Partial Payment *" id="sAmountToPay" maxlength="55" />
            <div id="sAmountToPay-error" class="room-error-message-label form-d-label error"></div>
          </div>

          <div class="input-container">
            <span>Total : <span id="sTotalPay"></span></span>
          </div>          

          <div class="room-form-button-container" id="addImagesEvidence-container">
            <div id="fileInputContainer">
             <input type="file" id="fileInputSingleBookEvidence" multiple style="display: none;">
            </div>
            <span class="clickable costa-btn-a" id="addImagesEvidence">Add Payment Evidence <i class="fa-regular fa-images"></i></span>
          </div>
          <div id="imagePreviewContainerSingleEvidence-error" class="room-error-message-label form-d-label error display-none" style="margin-top: -10px; margin-bottom: -10px;"></div>
          <div id="imagePreviewContainerSingleEvidence"></div>

        </form>
      </div>
      <div class="details-modal-footer">
        <span class="clickable costa-btn-a" id="singleRoomBookingModalBtnDone">Submit</span>
      </div>
</div>
</div>