<div class="details-modal-back display-none" id="editRoomModal">
<div class="details-modal show" >
      <div class="details-modal-title">
      <div class="modalNaveHead">
      <h1>Edit Room</h1>
      <div class="details-modal-close">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" class="clickable" id="closeEditRoomModal">
          <path  fill-rule="evenodd" clip-rule="evenodd" d="M13.7071 1.70711C14.0976 1.31658 14.0976 0.683417 13.7071 0.292893C13.3166 -0.0976311 12.6834 -0.0976311 12.2929 0.292893L7 5.58579L1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L5.58579 7L0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683417 14.0976 1.31658 14.0976 1.70711 13.7071L7 8.41421L12.2929 13.7071C12.6834 14.0976 13.3166 14.0976 13.7071 13.7071C14.0976 13.3166 14.0976 12.6834 13.7071 12.2929L8.41421 7L13.7071 1.70711Z" fill="black" />
        </svg>
      </div>
      </div>
      </div>
      <div class="details-modal-content">
          <form action="" class="room-form-container">

          <div class="input-container">
            <input type="text" class="room-input form-a" placeholder="Room Name *" id="new-rooName" maxlength="50" />
            <div id="new-rooName-error" class="room-error-message-label error"></div>
          </div>

          <div class="input-container">
             <textarea id="new-roomDescription" placeholder="Room Description *" maxlength="500" style="height: 100px;" class="room-input  form-a"></textarea>
            <div id="new-roomDescription-error" class="room-error-message-label error"></div>
          </div>

          <div class="input-container">
            <input type="number" class="room-input  form-a" placeholder="Maximum Capacity *" id="new-maximumCapacity" maxlength="10" />
            <div id="new-maximumCapacity-error" class="room-error-message-label error"></div>
          </div>

          <div class="input-container">
            <input type="number" class="room-input  form-a" placeholder="Room Quantity *" id="new-roomQuantity" maxlength="10" />
            <div id="new-roomQuantity-error" class="room-error-message-label error"></div>
          </div>

          <div class="input-container">
            <input type="text" class="room-input  form-a" placeholder="Published Rate *" id="new-publishedRate" maxlength="10" />
            <div id="new-publishedRate-error" class="room-error-message-label error"></div>
          </div>

          <div class="addOtherRate-container">

            <div class="input-container">Existing Rates</div>
            <div class="newAddedRate-container" id="existingRate-container">
            </div>

            <div class="input-container">
              Other Rates
            </div>
            <div class="input-container">
              <input type="text" class="room-input  form-edit-rates" placeholder="Rate Type" id="new-rateType" maxlength="50" />
              <div id="new-rateType-error" class="room-error-message-label error"></div>
            </div>

            <div class="input-container">
              <input type="text" class="room-input  form-edit-rates" placeholder="New Rate" id="new-newRate" maxlength="10" />
              <div id="new-newRate-error" class="room-error-message-label error"></div>
            </div>

            <div class="room-form-button-container">
               <span class="clickable costa-btn-a" id="new-addRateNow">Add +</span>
            </div>

            <div class="newAddedRate-container" id="editExistingRate-container">
            </div>
          </div>

          <div class="amenities-container">

            <div class="input-container">
              Existing Amenities
            </div>

            <div class="newAmenity-container" id="existingAmenities">
            </div>

            <div class="input-container">
              Amenities
            </div>
            <div class="input-container">
              <input type="text" class="room-input  form-addNewAmenitiesEditRoom" placeholder="Amenity" id="new-amenity" maxlength="50" />
              <div id="new-amenity-error" class="room-error-message-label error"></div>
            </div>

            <div class="room-form-button-container">
               <span class="clickable costa-btn-a" id="new-addAmenityNow">Add +</span>
            </div>

            <div class="newAmenity-container" id="newAmenityContainerIdForEdit">
            </div>
          </div>

          <div class="amenities-container">
            <div class="input-container">
                Existing Room Images
              </div>

            <div class="newAmenity-container" id="existingRoomImage">
            </div>
          </div>

          <div class="room-form-button-container">
            <div id="new-fileInputContainer">
              <input type="file" id="new-fileInput" multiple style="display: none;">
            </div>
            <span class="clickable costa-btn-a" id="new-addImages">Add Image <i class="fa-regular fa-images"></i></span>
          </div>
          <div id="new-imagePreviewContainer-error" class="room-error-message-label error display-none" style="margin-top: -10px; margin-bottom: -10px;"></div>
          <div id="new-imagePreviewContainer"></div>

        </form>
      </div>
      <div class="details-modal-footer">
        <span class="clickable costa-btn-a" id="new-addRoomBtnDone">Done</span>
      </div>
</div>
</div>
