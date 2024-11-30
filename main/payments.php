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
    <title>Payments</title>
  </head>
  <body>

    <!-- resources-->
    <?php include('openReservationNotificationModal.php') ?>
    <!-- resources-->

    <!-- resources-->
    <?php include('filterPaymentsModal.php') ?>
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
                <h3 class="room-page-title" >Payments</h3>
            </div>

            <div class="filter-container">
              <span class="clickable costa-btn-a" id="paymentsOpenFilterModalBtn">Filter</span>
            </div>

            <div class="paymentDetailsContainer" >
              <table id="paymentDetailsTable"  class="nowrap content-table" width="100%">
                <thead>
                  <tr>
                    <td ><span class="title-head-span">Customer</span></td>
                    <td ><span class="title-head-span">Payment Method</span></td>
                    <td ><span class="title-head-span">Total Price</span></td>
                    <td ><span class="title-head-span">Partial Amount</span></td>
                    <td ><span class="title-head-span">Quantity</span></td>
                    <td ><span class="title-head-span">Multibooked</span></td>
                    <td ><span class="title-head-span">Status</span></td>
                    <td ><span class="title-head-span">Last Process Date</span></td>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
        </div>


        <div class="projects-box admin" id="revenueContainer">
            <div class="title">
                <h3 class="room-page-title" >Estimated Sales Revenue</h3>
            </div>

            <div class="filter-container ">
              <span class="clickable costa-btn-a" id="dailyRevenu">Daily</span>
              <span class="clickable costa-btn-a" id="weeklyRevenue">Weekly</span>
              <span class="clickable costa-btn-a" id="monthlyRevenue">Monthly</span>

              <div class="input-container">
                <input 
                  type="number" 
                  id="yearInput" 
                  class=" room-input form-a " 
                  placeholder="Enter Year:" 
                  min="1900" 
                  max="2099" 
                  step="1" 
                  oninput="validateYear(this)" 
                  required />
                <div id="yearInput-error" class="room-error-message-label error"></div>
              </div>


              <style>

                #yearInput{
                  width: 300px;
                }

              </style>

            </div>

            <div class="revenueContainer" >
              <table id="revenueTable"  class="nowrap content-table" width="100%">
                <thead>
                  <tr>
                    <td ><span class="title-head-span" id="revenuePeriod">Daily Revenue</span></td>
                    <td ><span class="title-head-span">Total Revenue</span></td>
                    <td ><span class="title-head-span">Transaction Count</span></td>
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
    <script src="js/payments.js"></script>
    <script src="js/dataTables.js"></script>

  </body>
</html>
