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
    <title>Available Rooms</title>
  </head>
  <body>
    <div class="page-content">
      
      <!--place sidebar here-->
      <?php include('sidebar.php');?>
      <!--place sidebar here-->

      <main>
         <!--place header here-->
         <?php include('header.php'); ?>
        <!--place header here-->
        
        <div class="nav-tab">
          <div class="nav-list">

            <div class="customer display-none">
              <label for="check-in-date">Check-in</label>
              <input type="date" id="check-in-date" class="costa-datePicker">
            </div>

            <div class="customer display-none">
              <label for="check-out-date">Check-out</label>
              <input type="date" id="check-out-date" class="costa-datePicker">
            </div>
            
           <div class="customer display-none">
              <label for="check-out-date" >Availability</label>
              <span class="clickable costa-btn-a" >Search </span>
           </div>
          </div>

          <div class="button-list">
            <span class="clickable costa-btn-a admin display-none" id="addRoomBtn">Add Room</span>
            <span class="clickable costa-btn-a customer display-none">Book Now</span>
          </div>
        </div>

        <div class="main-content main-view">

          <div class="title">
            <h1>Available Rooms</h1>
          </div>

          <div class="courses-boxes">

            <!-- resources-->
            <?php include('addRoomModal.php') ?>
            <!-- resources-->

            <div class="courses-box">
              <div class="card-image">
                <img src="https://h-img1.cloudbeds.com/uploads/199021/cds-1_1_thumb~~65c4477f47757.png" style="background-color: white;" alt="" />
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
            
      
          </div>
          </div>

      </main>
    </div>
     <!-- Firebase SDK -->
     <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-storage-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-firestore-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-auth-compat.js"></script>
    <script>var userOrAdminDetails = <?php echo json_encode($response); ?>;</script>
    <script src="js/script.js"></script>
  </body>
</html>
