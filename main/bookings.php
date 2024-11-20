<?php 
include('../login/controller/loginSignUpController.php');
include('controller/generalController.php');

// if( $response['role'] === 'customer') {
//   header("Location: rooms.php");
// }
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
    <title>Bookings</title>
  </head>
  <body>

    <!-- resources-->
    <?php include('openReservationNotificationModal.php') ?>
    <!-- resources-->

    <!-- resources-->
    <?php include('rateRoomModal.php') ?>
    <!-- resources-->

    <!-- resources-->
    <?php include('filterBookingsModal.php') ?>
    <!-- resources-->
    <div class="page-content">

      <!--place sidebar here-->
      <?php include('sidebar.php');?>
      <!--place sidebar here-->
      
      
      <main>
        <!--place header here-->
        <?php include('header.php'); ?>
        <!--place header here-->

        <div class="main-view">

        <div class="projects-box">
            <div class="title">
                <h3 class="room-page-title" >Bookings</h3>
            </div>

            <div class="filter-container">
              <span class="clickable costa-btn-a" id="bookingsOpenFilterModalBtn">Filter</span>
            </div>

            <style>
              li[data-dtr-index="10"] .dtr-title {
                display: none !important;
              }

              .table-head-title{
                text-align: center !important;
              }

              tbody tr td:nth-child(11) span {
                margin: 0 auto !important; /* Centers horizontally */
                text-align: center !important;
                vertical-align: middle !important;
              }
            </style>

            <div class="bookingDetailsContainer" >
              <table id="bookingDetailsTable"  class="nowrap content-table" width="100%">
                <thead>
                  <tr>
                    <td ><span class="title-head-span">Action</span></td>
                    <td ><span class="title-head-span">Room Name</span></td>
                    <td ><span class="title-head-span">Customer</span></td>
                    <td ><span class="title-head-span">Total Price</span></td>
                    <td ><span class="title-head-span">Check-in Date</span></td>
                    <td ><span class="title-head-span">Check-out Date</span></td>
                    <td ><span class="title-head-span">Quantity</span></td>
                    <td ><span class="title-head-span">Status</span></td>
                    <td ><span class="title-head-span">Process Date</span></td>
                    <td ><span class="title-head-span">Last Process Date</span></td>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>


        </div>

       </div>
         
        
      </main>
    </div>
    <script>var userOrAdminDetails = <?php echo json_encode($response); ?>;</script>
    <script src="js/script.js"></script>
    <script src="js/swiper-bundle.min.js"></script>
    <script src="js/notificationListener.js"></script>
    <script src="js/bookings.js"></script>
    <script src="js/dataTables.js"></script>

  </body>
</html>
