<div class="details-modal-back display-none" id="openFilterBookingsModal">
<div class="details-modal show" >
      <div class="details-modal-title" id="filterBooking-modal-titleId">
        <div class="modalNaveHead">
          <h1 id='filter'>Filter Bookings</h1>
          <div class="details-modal-close">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" class="clickable" id="closeFilterBookingsModal">
              <path  fill-rule="evenodd" clip-rule="evenodd" d="M13.7071 1.70711C14.0976 1.31658 14.0976 0.683417 13.7071 0.292893C13.3166 -0.0976311 12.6834 -0.0976311 12.2929 0.292893L7 5.58579L1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L5.58579 7L0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683417 14.0976 1.31658 14.0976 1.70711 13.7071L7 8.41421L12.2929 13.7071C12.6834 14.0976 13.3166 14.0976 13.7071 13.7071C14.0976 13.3166 14.0976 12.6834 13.7071 12.2929L8.41421 7L13.7071 1.70711Z" fill="black" />
            </svg>
          </div>
        </div>
      </div>
      <div class="details-modal-content filter-modal-content">
      <div class="room-form-container">

        <!-- Check-in Date  -->
        <span class="notif-detail-label singleBooking">Check-in Date</span>
        <div class="filter-modal-input-container">
        <div class="filter-modal-input-date-container">
        <label class="filter-modal-inner-label" for="filter-check-in-start-date">Start</label>
        <input type="date" id="filter-check-in-start-date" class="filter-modal-value-input">
        <div id="filter-check-in-start-date-error" class="room-error-message-label error"></div>
        </div>

        
        <div class="filter-modal-input-date-container">
        <label class="filter-modal-inner-label" for="filter-check-in-end-date">End</label>
        <input type="date" id="filter-check-in-end-date" class="filter-modal-value-input">
        <div id="filter-check-in-end-date-error" class="room-error-message-label error"></div>
        </div>
        </div>

        <!-- Check-out Date -->
        <span class="notif-detail-label singleBooking">Check-out Date</span>
        <div class="filter-modal-input-container">
        <div class="filter-modal-input-date-container">
        <label class="filter-modal-inner-label" for="filter-check-out-start-date">Start</label>
        <input type="date" id="filter-check-out-start-date" class="filter-modal-value-input">
        <div id="filter-check-out-start-date-error" class="room-error-message-label error"></div>
        </div>

        
        <div class="filter-modal-input-date-container">
        <label class="filter-modal-inner-label" for="filter-check-out-end-date">End</label>
        <input type="date" id="filter-check-out-end-date" class="filter-modal-value-input">
        <div id="filter-check-out-end-date-error" class="room-error-message-label error"></div>
        </div>
        </div>

        <!-- Room Name -->
        <span class="notif-detail-label singleBooking">Room Name</span>
        <div class="filter-modal-input-container">

        <div class="filter-modal-inputvalue-container">
        <label class="filter-modal-inner-label" for="filter-room-name">Value</label>
        <input type="text" id="filter-room-name" placeholder="Room Name" class="filter-modal-value-input">
        <div id="filter-room-name-error" class="room-error-message-label error"></div>
        </div>
        </div>

        <!-- Customer -->
        <span class="notif-detail-label singleBooking">Customer</span>
        <div class="filter-modal-input-container">

        <div class="filter-modal-inputvalue-container">
        <label class="filter-modal-inner-label" for="filter-customer">Value</label>
        <input type="text" id="filter-customer" placeholder="Customer" class="filter-modal-value-input">
        <div id="filter-customer-error" class="room-error-message-label error"></div>
        </div>
        </div>

        <!-- Total Price -->
        <span class="notif-detail-label singleBooking">Total Price</span>
        <div class="filter-modal-input-container">
        <div class="filter-modal-input-date-container">
            <label  class="filter-modal-inner-label">Operator</label>
            <select class="filter-operator-select color-gray" id="total-price-operator">
                <option class="color-gray" value="none">none</option>
                <option class="option-value" value="==">==</option>
                <option class="option-value" value="<=">&lt;=</option>
                <option class="option-value" value=">=">&gt;=</option>
                <option class="option-value" value="<">&lt;</option>
                <option class="option-value" value=">">&gt;</option>
                <option class="option-value" value="!=">!=</option>
            </select>
            <div id="total-price-operator-error" class="room-error-message-label error"></div>
        </div>


        <div class="filter-modal-inputvalue-container">
        <label  class="filter-modal-inner-label" for="filter-total-price">Value</label>
        <input type="text" id="filter-total-price" placeholder="Total Price" class="filter-modal-value-input">
        <div id="filter-total-price-error" class="room-error-message-label error"></div>
        </div>
        </div>

        <!-- Booked Quantity -->
        <span class="notif-detail-label singleBooking">Booked Quantity</span>
        <div class="filter-modal-input-container">
        <div class="filter-modal-input-date-container">
            <label  class="filter-modal-inner-label">Operator</label>
            <select class="filter-operator-select color-gray" id="booked-quantity-operator">
                <option class="color-gray" value="none">none</option>
                <option class="option-value" value="==">==</option>
                <option class="option-value" value="<=">&lt;=</option>
                <option class="option-value" value=">=">&gt;=</option>
                <option class="option-value" value="<">&lt;</option>
                <option class="option-value" value=">">&gt;</option>
                <option class="option-value" value="!=">!=</option>
            </select>
            <div id="booked-quantity-operator-error" class="room-error-message-label error"></div>
        </div>


        <div class="filter-modal-inputvalue-container">
        <label class="filter-modal-inner-label" for="filter-booked-quantity">Value</label>
        <input type="text" id="filter-booked-quantity" placeholder="Booked Quantity" class="filter-modal-value-input">
        <div id="filter-booked-quantity-error" class="room-error-message-label error"></div>
        </div>
        </div>

        <!-- Payment is Partial -->
        <span class="notif-detail-label singleBooking">Payment is Partial</span>
        <div class="filter-modal-input-container">

        <div class="filter-modal-input-date-container">
            <label class="filter-modal-inner-label" for="filter-payment-is-partital">Value</label>
            <select class="filter-operator-select color-gray" id="filter-payment-is-partital">
                <option class="color-gray" value="none">none</option>
                <option class="option-value" value="1">True</option>
                <option class="option-value" value="00">False</option>
            </select>
            <div id="filter-payment-is-partital-error" class="room-error-message-label error"></div>
        </div>
        </div>

        <!-- Booking Status -->
        <span class="notif-detail-label singleBooking">Booking Status</span>
        <div class="filter-modal-input-container">

        <div class="filter-modal-input-date-container">
            <label class="filter-modal-inner-label" for="filter-booking-status">Value</label>
            <select class="filter-operator-select color-gray" id="filter-booking-status">
                <option class="color-gray" value="none">none</option>
                <option class="option-value" value="approved">Approved</option>
                <option class="option-value" value="pending">Pending</option>
                <option class="option-value" value="rejected">Rejected</option>
            </select>
            <div id="filter-booking-status-error" class="room-error-message-label error"></div>
        </div>
        </div>

        <!-- Process Date  -->
        <span class="notif-detail-label singleBooking">Process Date</span>
        <div class="filter-modal-input-container">
        <div class="filter-modal-input-date-container">
        <label class="filter-modal-inner-label" for="filter-process-start-date">Start</label>
        <input type="date" id="filter-process-start-date" class="filter-modal-value-input">
        <div id="filter-process-start-date-error" class="room-error-message-label error"></div>
        </div>

        
        <div class="filter-modal-input-date-container">
        <label class="filter-modal-inner-label" for="filter-process-end-date">End</label>
        <input type="date" id="filter-process-end-date" class="filter-modal-value-input">
        <div id="filter-process-end-date-error" class="room-error-message-label error"></div>
        </div>
        </div>

        <!-- Last Process Date  -->
        <span class="notif-detail-label singleBooking">Last Process Date</span>
        <div class="filter-modal-input-container">
        <div class="filter-modal-input-date-container">
        <label class="filter-modal-inner-label" for="filter-last-process-start-date">Start</label>
        <input type="date" id="filter-last-process-start-date" class="filter-modal-value-input">
        <div id="filter-last-process-start-date-error" class="room-error-message-label error"></div>
        </div>

        
        <div class="filter-modal-input-date-container">
        <label class="filter-modal-inner-label" for="filter-last-process-end-date">End</label>
        <input type="date" id="filter-last-process-end-date" class="filter-modal-value-input">
        <div id="filter-last-process-end-date-error" class="room-error-message-label error"></div>
        </div>
        </div>

      </div>

      </div>
      <div class="details-modal-footer">
        <span class="clickable costa-btn-a" id="proceedFilterBtn" style="width: 10rem;">Proceed</span>
      </div>
</div>
</div>
