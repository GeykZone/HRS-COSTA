<?php 
include('../login/controller/loginSignUpController.php');
include('controller/generalController.php')
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- resources-->
    <?php include('resources.php') ?>
    <!-- resources-->
    <title>Settings</title>
  </head>
  <body>

    <!-- resources-->
    <?php include('openReservationNotificationModal.php') ?>
    <!-- resources-->

    <div class="page-content">
      <!--place sidebar here-->
      <?php include('sidebar.php');?>
      <!--place sidebar here-->
      <main>
        <!--place header here-->
        <?php include('header.php'); ?>
        <!--place header here-->
        <div class="main-content main-view">
          <div class="title">
            <h1>Settings</h1>
          </div>

          <div class="main-content-boxes">

            <!-- Payment Methods -->
            <div class="box admin display-none" >
              <div class="box-section1 settings-section1">
                <div class="box-title">
                  <h2>Payment Methods</h2>
                  <p>Add edit or remove payment methods</p>
                  <span class="clickable costa-btn-a" id="addPaymentMethodBtn">Add Payment Method</span>
                </div>

                <style>
                  #addPaymentMethodBtn{
                    margin-top: 10px !important;
                    width: 180px !important;                 
                  }

                  #addQrButton{
                    width: 180px !important;  
                  }

                  #RemovePaymentMethod{
                    background-color: #C41919 !important;
                  }

                  #ClosePaymentMethodForm{
                    background-color: grey !important;
                  }

                  #showOnlyIfManualPMIsClicked p{
                    color: gray !important;
                  }
                </style>
              </div>


              <div class="backup-manager-section3" id="paymentMethodSettingsContainer">
              </div>

              <!-- payment method form container -->
              <div class="room-form-container display-none" id="paymenthMethodSettingsForm">

                <div class="input-container">
                  <input type="text" class="room-input form-a" placeholder="Payment Method Name *" id="paymentMethodName" maxlength="50" />
                  <div id="paymentMethodName-error" class="room-error-message-label error"></div>
                </div>

                <div class="input-container">
                  <input type="text" class="room-input form-a" placeholder="Payment Method Number / Code *" id="paymentMethodNumber" maxlength="50" />
                  <div id="paymentMethodNumber-error" class="room-error-message-label error"></div>
                </div>

                <!-- add qr image -->
                <div class="room-form-button-container">
                  <div id="getPaymentMethodQRImage">
                    <input type="file" id="uploadPaymentMethodQRImage" multiple style="display: none;">
                  </div>
                  <span class="clickable costa-btn-a addImagesButton" id="addQrButton">Add QR Code <i class="fa-regular fa-images"></i></span>
                </div>

                <div id="newPaymentMethodQRContainer-error" class="room-error-message-label error display-none" style="margin-top: -10px; margin-bottom: -10px;"></div>
                <div id="newPaymentMethodQRContainer"></div>

                <div class="newAmenity-container">
                  <span class="clickable costa-btn-a" id="ClosePaymentMethodForm">Close</span>
                  <span class="clickable costa-btn-a forEditPaymentMethod display-none" id="RemovePaymentMethod">Remove</span>
                  <span class="clickable costa-btn-a" id="SubmitPaymentMethodChanges">Submit</span>
                </div>

              </div>

              <div  class="newAmenity-container display-none" id="showOnlyIfManualPMIsClicked">
                  <p>You can't modify Manual payment method.</p>
              </div>

            </div>

            <!-- General Info -->
            <div class="box">

              <div class="box-section1">
                <div class="box-title">
                  <h2>Login Details</h2>
                  <p>Update Account login details</p>
                </div>
              </div>

              <div class="room-form-container" id="updateLoginDetailsContainer">
                <div class="input-container">
                  <input type="text" class="room-input form-a updateDetailsInput" placeholder="New Account Username" id="newAccountUsername" maxlength="50" />
                  <div id="newAccountUsername-error" class="room-error-message-label error"></div>
                </div>

                <div class="input-container">
                  <input type="email" class="room-input form-a updateDetailsInput" placeholder="New Account Email" id="newAccountEmail" maxlength="50" />
                  <div id="newAccountEmail-error" class="room-error-message-label error"></div>
                </div>

                <div class="input-container">
                  <input type="password" class="room-input form-a updateDetailsInput" placeholder="New Account Password" id="newAccountPassword" maxlength="50" />
                  <div id="newAccountPassword-error" class="room-error-message-label error"></div>
                </div>

                <div class="input-container">
                  <input type="password" class="room-input form-a " placeholder="Re-type New Account Password" id="reTypeNewAccountPassword" maxlength="50" />
                  <div id="reTypeNewAccountPassword-error" class="room-error-message-label error"></div>
                </div>

                <div class="newAmenity-container updateDetailsButtonsContainer" id="updateUserDetailsButtonsContainer">
                    <span class="clickable costa-btn-a" id="updateDetails">Submit</span>
                </div> 
              </div>

              <div class="room-form-container display-none" style = " margin-top: 30px;" id = "emailVerification">

                  <div class="box-title">
                    <p>Check your Email for your verification code. Please don't refresh the page.</p>
                  </div>      
                         
                  <div class="input-container">
                    <input type="text" class="room-input form-a" placeholder="Gmail Verification Code *" id="inputVerificationCode" maxlength="50" />
                    <div id="inputVerificationCode-error" class="room-error-message-label error"></div>
                  </div>
                  
                  <div class="newAmenity-container  updateDetailsButtonsContainer" id="verificationCodeContainer">
                    <span class="clickable costa-btn-a" id="reSendCode">Resend</span>
                    <span class="clickable costa-btn-a" id="verifyCode">Verify</span>
                  </div>     

              </div>

              <div class="newAmenity-container display-none" id="resendLoading">
                   <p>Please wait...</p>
              </div>

               <style>
                  .updateDetailsButtonsContainer{
                    margin-top: -25px !important;
                    background: transparent;
                  }

                  #resendLoading p{
                    color: gray;
                  }

                  .updateDetailsInput{
                    margin-bottom: -10px !important;
                  }

                  #reTypeNewAccountPassword-error{
                    margin-top: -10px !important;
                    margin-bottom: 10px!important;
                  }
                  
                  #reSendCode{
                    background-color: gray !important;
                  }
                </style>
            </div>

          </div>
        </div>
      </main>
    </div>
    <script>var userOrAdminDetails = <?php echo json_encode($response); ?>;</script>
    <script src="js/script.js"></script>
    <script src="js/swiper-bundle.min.js"></script>
    <script src="js/notificationListener.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/dataTables.js"></script>
  </body>
</html>
