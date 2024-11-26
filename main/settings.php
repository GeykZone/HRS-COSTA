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

            <!-- Social Info -->
            <div class="box">
              <div class="box-section1">
                <div class="box-title">
                  <h2>Social Info</h2>
                  <p>Social Media Information</p>
                </div>
              </div>
              <div class="social-info-section2">
                <form>
                  <div class="social-info-icon">
                    <i class="fa-solid fa-money-check"></i>
                    <input type="text" placeholder="Twitter Username" />
                  </div>
                </form>
              </div>
            </div>

            <!-- Site Control -->
            <div class="box">
              <div class="box-section1">
                <div class="box-title">
                  <h2>Site Control</h2>
                  <p>Control The Website If There Is Maintenance</p>
                </div>
              </div>
              <div class="settings-box-section2">
                <div class="settings-box-website-control">
                  <div class="settings-box-title">
                    <p>Website Control</p>
                    <p>Open/Close Website And Type The Reason</p>
                  </div>
                  <span class="toggle"></span>
                </div>
                <form>
                  <textarea
                    name=""
                    placeholder="Close Message Content"
                  ></textarea>
                </form>
              </div>
            </div>

            <!-- General Info -->
            <div class="box">
              <div class="box-section1">
                <div class="box-title">
                  <h2>General Info</h2>
                  <p>General Information About Your Account</p>
                </div>
              </div>
              <div class="general-info-section2">
                <form>
                  <label for="first-name">First Name</label>
                  <input type="text" placeholder="First Name" id="first-name" />
                  <label for="last-name">Last Name</label>
                  <input type="text" placeholder="Last Name" id="last-name" />
                  <label for="Email">Email</label>
                  <div class="email">
                    <input
                      type="email"
                      disabled
                      id="Email"
                      value="zana.suleiman.44@gmail.com"
                    />
                    <a href="/#">Change</a>
                  </div>
                </form>
              </div>
            </div>

            <!-- Security Info -->
            <div class="box">
              <div class="box-section1">
                <div class="box-title">
                  <h2>Security Info</h2>
                  <p>Security Information About Your Account</p>
                </div>
              </div>
              <div class="security-info-section2">
                <div class="person-security-info">
                  <div class="person-info">
                    <p style="font-weight: normal">Password</p>
                    <span>Last Change On 25/10/2021</span>
                  </div>
                  <a href="/#">Change</a>
                </div>

                <div class="person-security-info">
                  <div class="person-info">
                    <p style="font-weight: normal">Two-Factor Authentication</p>
                    <span>Enable/Disable The Feature</span>
                  </div>
                  <span class="toggle toggle1"></span>
                </div>

                <div class="person-security-info">
                  <div class="person-info">
                    <p style="font-weight: normal">Devices</p>
                    <span>Check The Login Devices List</span>
                  </div>
                  <a href="/#">Devices</a>
                </div>
              </div>
            </div>

            <!-- Wiget Control -->
            <div class="box">
              <div class="box-section1 settings-section1">
                <div class="box-title">
                  <h2>Widgets Control</h2>
                  <p>Show/Hide Widgets</p>
                </div>
              </div>
              <div class="widgets-control-section2">
                <form>
                  <ul>
                    <li>
                      <input type="checkbox" id="one" />
                      <label for="one">Quick Draft</label>
                    </li>
                    <li>
                      <input type="checkbox" id="two" checked />
                      <label for="two">Yearly Targets</label>
                    </li>
                    <li>
                      <input type="checkbox" id="three" />
                      <label for="three">Tickets Statistics</label>
                    </li>
                    <li>
                      <input type="checkbox" id="four" checked />
                      <label for="four">Latest News</label>
                    </li>
                    <li>
                      <input type="checkbox" id="five" />
                      <label for="five">Latest Tasks</label>
                    </li>
                    <li>
                      <input type="checkbox" id="six" checked />
                      <label for="six">Top Search Items</label>
                    </li>
                  </ul>
                </form>
              </div>
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
