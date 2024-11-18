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
    <title class="room-page-title"  >Available Rooms</title>
  </head>
  <body>
    <!-- resources-->
    <?php include('roomDeatailsModal.php') ?>
    <!-- resources-->

    <!-- resources-->
    <?php include('singleRoomBookingModal.php') ?>
    <!-- resources-->

    <!-- resources-->
    <?php include('multiRoomBookingModal.php') ?>
    <!-- resources-->

    <!-- resources-->
    <?php include('openReservationNotificationModal.php') ?>
    <!-- resources-->

    <!-- resources-->
    <?php include('addRoomModal.php') ?>
    <!-- resources-->

    <div class="page-content">
      
      <!--place sidebar here-->
      <?php include('sidebar.php');?>
      <!--place sidebar here-->

      <main>
         <!--place header here-->
         <?php include('header.php'); ?>
        <!--place header here-->

        <link rel="stylesheet" href="css/modal.css" />
        
        <div class="nav-tab">
          <div class="nav-list">

            <div class=" room-booking-nav-list-container">
              <label for="check-in-date">Check-in</label>
              <input type="date" id="check-in-date" class="costa-datePicker">
              <div id="check-in-date-error" class="room-error-message-label error"></div>
            </div>

            <div class=" room-booking-nav-list-container">
              <label for="check-out-date">Check-out</label>
              <input type="date" id="check-out-date" class="costa-datePicker">
              <div id="check-out-date-error" class="room-error-message-label error"></div>
            </div>
            
           <div class=" room-booking-nav-list-container">
              <label for="check-out-date" >Availability</label>
              <span class="clickable costa-btn-a" id="searchAvailableRooms">Search </span>
              <div  class="room-error-message-label error"></div>
           </div>

          </div>

          <div class="button-list">
            <span class="clickable costa-btn-a admin display-none" id="addRoomBtn">Add Room</span>

            <div class=" room-booking-nav-list-container display-none multi-booking-element">
              <label for="check-out-date" >Book Selected</label> 
              <span class="clickable costa-btn-a  fa-solid fa-money-check" id="bookNOwBtn"></span>
              <div  class="room-error-message-label error"></div>
           </div>

            <div class="multi-booking-toggle-container customer display-none">
              <span class="multi-booking-toggle-label">Multi Booking Off</span>
              <label class="switch">
                <input type="checkbox" class="multi-booking-toggle-box" onchange="handleMultibookingToggle(event)">
                <span class="slider round"></span>
              </label>
              <div  class="room-error-message-label error"></div>
            </div>

          </div>
        </div>

        <div class="main-content main-view">

          <div class="title">
            <h1 class="room-page-title" >Available Rooms</h1>
          </div>

          <div class="courses-boxes">

            <!-- <div class="courses-box">
              <div class="card-image">
                <img src="https://h-img1.cloudbeds.com/uploads/199021/cds-1_1_thumb~~65c4477f47757.png" class="hotel-room-logo" style="background-color: white;" alt="" />
                <img src="https://h-img3.cloudbeds.com/uploads/199021/pbv_7_gallery~~614068a056257.jpg" alt="" />
              </div>
              <div class="courses-card-body">
                <h4>Room A</h4>
                <p>
                  Sample Description 
                </p>
                <span>View Room Details</span>
              </div>
              <div class="courses-card-footer">
                <span><i class="fa-regular fa-user"></i>950</span>
                <span><i class="fa-solid fa-dollar-sign"></i>165</span>
              </div>
            </div>
             -->
      
          </div>
          </div>

      </main>
    </div>
     <!-- Firebase SDK -->
    <script>var userOrAdminDetails = <?php echo json_encode($response); ?>;</script>
    <script src="js/script.js"></script>
    <script src="js/swiper-bundle.min.js"></script>
    <script src="js/notificationListener.js"></script>
    <script src="js/rooms.js"></script>
  </body>
</html>
