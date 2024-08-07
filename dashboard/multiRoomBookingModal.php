<div class="details-modal-back display-none" id="multiBookingPaymentModal">
<div class="details-modal show" >
    <div class="details-modal-title">
    <div class="modalNaveHead">
    <h1>Payment Form</h1>
    <div class="details-modal-close">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" class="clickable"  id="multiRoomBookingModalCLose">
        <path  fill-rule="evenodd" clip-rule="evenodd" d="M13.7071 1.70711C14.0976 1.31658 14.0976 0.683417 13.7071 0.292893C13.3166 -0.0976311 12.6834 -0.0976311 12.2929 0.292893L7 5.58579L1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L5.58579 7L0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683417 14.0976 1.31658 14.0976 1.70711 13.7071L7 8.41421L12.2929 13.7071C12.6834 14.0976 13.3166 14.0976 13.7071 13.7071C14.0976 13.3166 14.0976 12.6834 13.7071 12.2929L8.41421 7L13.7071 1.70711Z" fill="black" />
        </svg>
    </div>
    </div>
    </div>
    <div class="details-modal-content">
        <form action="" class="room-form-container">

        <div class="input-container">
            <select id="multi-room-payment-picklist"  class="multiRoomPaymentPicklist pick-list selectAvailableRate" placeholder="Select Payment Method">
            </select>
            <div id="multi-room-payment-picklist-error" class="room-error-message-label form-e-label error"></div>
        </div>

        <div class="paymentMethodContainerDetailsForMultiBooking pm-container display-none">
            <h4 id="paymenthMethodHeaderNameForMultiBooking"></h4>
            <div id="qrImg-container-for-multibooking" class="pm-qr"> <img  id='qrImageId-for-multibooking'  alt=""></div>
            <h4 id="paymentNumber-for-multibooking"></h4>
        </div>

        <div class="paymentMethodMsgForMultiBooking-container display-none pm-msg-container">
            <h4 id="paymentMethodMsgForMultiBooking"></h4>
        </div>
        
        <div class="input-container">
            <input type="text" class="room-input  form-e" placeholder="Full Name *" id="ForMultiBookingFullName" maxlength="55" />
            <div id="ForMultiBookingFullName-error" class="room-error-message-label form-e-label error"></div>
        </div>

        <div class="input-container">
            <input type="text" class="room-input  form-e" placeholder="Complete Address *" id="ForMultiBookingCompleteAddress" maxlength="55" />
            <div id="ForMultiBookingCompleteAddress-error" class="room-error-message-label form-e-label error"></div>
        </div>

        <div class="input-container">
            <input type="text" class="room-input  form-e" placeholder="Contact Info (Email / Phone Number) *" id="ForMultiBookingContactInfo" maxlength="12" />
            <div id="ForMultiBookingContactInfo-error" class="room-error-message-label form-e-label error"></div>
        </div>

        <div class="input-container">
            <input type="text" class="room-input  form-e" placeholder="Amount to Pay *" id="ForMultiBookingAmountToPay" maxlength="55" />
            <div id="ForMultiBookingAmountToPay-error" class="room-error-message-label form-e-label error"></div>
        </div>

        <div class="input-container">
            <span>Calculation Info</span>
            <div class="calculation-info-container">
                <!-- <div class="calculation-info-peer-room">
                    <div><span>Room Name: </span><span>Room A</span></div>
                    <div><span>Booked Quantity: </span><span>15</span></div>
                    <div><span>Room Price: </span><span>2,000.00</span></div>
                    <div><span>Total Payable: </span><span>10,000.00</span></div>
                </div> -->
            </div>
        </div>

        <div class="input-container">
            <span>Total : <span id="ForMultiBookingTotalPay"></span></span>
        </div>          

        <div class="room-form-button-container" id="addImagesEvidenceForMultiBooking-container">
            <div id="fileInputContainer">
            <input type="file" id="fileInputmultiBookEvidence" multiple style="display: none;">
            </div>
            <span class="clickable costa-btn-a" id="addImagesEvidenceForMultiBooking">Add Payment Evidence <i class="fa-regular fa-images"></i></span>
        </div>
        <div id="imagePreviewContainermultiEvidence-error" class="room-error-message-label form-e-label error display-none" style="margin-top: -10px; margin-bottom: -10px;"></div>
        <div id="imagePreviewContainermultiEvidence"></div>

        </form>
    </div>
    <div class="details-modal-footer">
        <span class="clickable costa-btn-a" id="multiRoomBookingModalBtnDone">Submit</span>
    </div>
</div>
</div>
