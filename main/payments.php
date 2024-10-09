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

            <style>
              li[data-dtr-index="9"] .dtr-title {
                display: none !important;
              }

              .table-head-title{
                text-align: center !important;
              }

              tbody tr td:nth-child(10) span {
                margin: 0 auto !important; /* Centers horizontally */
                text-align: center !important;
                vertical-align: middle !important;
              }
            </style>

            <div class="paymentDetailsContainer" >
              <table id="paymentDetailsTable"  class="nowrap content-table" width="100%">
                <thead>
                  <tr>
                    <td ><span class="title-head-span">Customer</span></td>
                    <td ><span class="title-head-span">Payment Method</span></td>
                    <td ><span class="title-head-span">Total Price</span></td>
                    <td ><span class="title-head-span">Booked Quantity</span></td>
                    <td ><span class="title-head-span">Is Multibooked</span></td>
                    <td ><span class="title-head-span">Is Partial</span></td>
                    <td ><span class="title-head-span">Partial Amount</span></td>
                    <td ><span class="title-head-span">Payment Status</span></td>
                    <td ><span class="title-head-span">Last Process Date</span></td>
                    <td class="table-head-title"><span class="title-head-span">Action</span></td>
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
