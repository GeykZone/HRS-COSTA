<?php 
include('../login/controller/loginSignUpController.php');
include('controller/generalController.php');

if( $response['role'] === 'customer') {
  header("Location: rooms.php");
}
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

            <div class="projects-box-section2" >
            <table id="dashboardBookingDetailsTable" class="nowrap">
              <thead>
                <tr>
                  <td>Room Name</td>
                  <td>Customer</td>
                  <td>Total Price</td>
                  <td>Check-out Date</td>
                  <td>Booked Quantity</td>
                  <td>Action</td>
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

    <script src="js/notificationListener.js"></script>

  </body>
</html>
