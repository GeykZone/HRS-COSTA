<link rel="stylesheet" href="css/modal.css" />
<div class="details-modal">
      <div class="details-modal-close">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none" class="clickable" id="closeAddRoomsModal">
          <path  fill-rule="evenodd" clip-rule="evenodd" d="M13.7071 1.70711C14.0976 1.31658 14.0976 0.683417 13.7071 0.292893C13.3166 -0.0976311 12.6834 -0.0976311 12.2929 0.292893L7 5.58579L1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L5.58579 7L0.292893 12.2929C-0.0976311 12.6834 -0.0976311 13.3166 0.292893 13.7071C0.683417 14.0976 1.31658 14.0976 1.70711 13.7071L7 8.41421L12.2929 13.7071C12.6834 14.0976 13.3166 14.0976 13.7071 13.7071C14.0976 13.3166 14.0976 12.6834 13.7071 12.2929L8.41421 7L13.7071 1.70711Z" fill="black" />
        </svg>
      </div>
      <div class="details-modal-title">
        <h1>Add Room</h1>
      </div>
      <div class="details-modal-content">
          <form action="" class="room-form-container">

          <div class="input-container">
            <input type="text" class="room-input" placeholder="Room Name" id="rooName" maxlength="50" />
            <div id="rooName-error" class="room-error-message-label error"></div>
          </div>

          <div class="input-container">
             <textarea id="roomDescription" placeholder="Room Description" maxlength="500" class="room-input"></textarea>
            <div id="roomDescription-error" class="room-error-message-label error"></div>
          </div>

          <div class="input-container">
            <input type="number" class="room-input" placeholder="Maximum Capacity" id="maximumCapacity" maxlength="10" />
            <div id="maximumCapacity-error" class="room-error-message-label error"></div>
          </div>

          <div class="input-container">
            <input type="number" class="room-input" placeholder="Published Rate" id="publishedRate" maxlength="10" />
            <div id="publishedRate-error" class="room-error-message-label error"></div>
          </div>

          <div class="addOtherRate-container display-none">
            <div class="input-container">
              <input type="number" class="room-input" placeholder="Rate Type" id="rateType" maxlength="10" />
              <div id="rateType-error" class="room-error-message-label error"></div>
            </div>

            <div class="input-container">
              <input type="number" class="room-input" placeholder="New Rate" id="newRate" maxlength="10" />
              <div id="newRate-error" class="room-error-message-label error"></div>
            </div>

            <div class="room-form-button-container">
               <span class="clickable costa-btn-a" id="addRateNow">Add +</span>
            </div>

            <div class="newAddedRate-container">
              <span class="otherRate-card"><span>Flash Sale</span><span> : </span><span>200</span></span>
            </div>
          </div>

          <div class="room-form-button-container">
            <div id="fileInputContainer">
            <input type="file" id="fileInput" multiple style="display: none;">
            </div>
            <span class="clickable costa-btn-a" id="addImages">Add Image</span>
            <span class="clickable costa-btn-a" id="showOtherRate">Show Other Rate</span>
            <span class="clickable costa-btn-a display-none" id="closeOtherRate">Close Other Rate</span>
          </div>
          <div id="imagePreviewContainer"></div>

        </form>
      </div>
      <div class="details-modal-footer">
        <span class="clickable costa-btn-a" id="addRoomBtnDone">Done</span>
      </div>
</div>