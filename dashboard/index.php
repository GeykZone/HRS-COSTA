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
    <title>Dashboard</title>
  </head>
  <body>
    <div class="loader">
      <h1>Loading<span>....</span></h1>
    </div>
    <!-- resources-->
    <?php include('openReservationNotificationModal.php') ?>
    <!-- resources-->
    <div class="page-content index-page">
      <!--place sidebar here-->
      <?php include('sidebar.php');?>
      <!--place sidebar here-->
      
      <main>
        <!--place header here-->
        <?php include('header.php'); ?>
        <!--place header here-->

        <div class="main-view">
          <div class="main-content">
        
        <div class="head-card-box">   
        <div class="flash-card">
                  <div class="flash-card-title">
                      <h3><i class="fa-solid fa-hotel"></i></h3>
                      <h3>Total Rooms</h3>
                  </div>
                  <div class="flash-card-body">
                     <h2>0</h2>
                     <span class="clickable">View Details</span>
                  </div>
              </div>

              <div class="flash-card">
                  <div class="flash-card-title">
                      <h3><i class="fa-solid fa-right-from-bracket"></i></h3>
                      <h3>Today's Checkout</h3>
                  </div>
                  <div class="flash-card-body">
                     <h2>0</h2>
                     <span class="clickable">View Details</span>
                  </div>
              </div>

              <div class="flash-card">
                  <div class="flash-card-title">
                      <h3><i class="fa-solid fa-download"></i></h3>
                      <h3>Today's In</h3>
                  </div>
                  <div class="flash-card-body">
                     <h2>0</h2>
                     <span class="clickable">View Details</span>
                  </div>
              </div>

              <div class="flash-card">
                  <div class="flash-card-title">
                      <h3><i class="fa-regular fa-circle-xmark"></i></h3>
                      <h3>Cancelations</h3>
                  </div>
                  <div class="flash-card-body">
                     <h2>0</h2>
                     <span class="clickable">View Details</span>
                  </div>
              </div>

              <div class="flash-card">
                  <div class="flash-card-title">
                      <h3><i class="fa-regular fa-credit-card"></i></h3>
                      <h3>Partial Payments</h3>
                  </div>
                  <div class="flash-card-body">
                     <h2>0</h2>
                     <span class="clickable">View Details</span>
                  </div>
              </div>
        </div>

          <div class="main-content-boxes">
            <div class="box first-box">
              <div class="box">
                <div class="third-box-section2">
                  <?php include('calendar.php') ?>
                </div>
              </div>
            </div>

            <div class="box">
            <div class="box-section1">
                <div class="box-title">
                  <h2>Booking Status</h2>
                </div>
              </div>
              <div class="fourth-box-section2">

              </div>
            </div>
          </div>
        </div>
        
        <div class="projects-box">
          <div class="box-section1">
            <div class="box-title">
              <h2>Available Rooms</h2>
            </div>
          </div>
          <div class="slider-message">There are no rooms available to display.</div>
          <div class="projects-box-section2">
          <div class="container swiper">
              <div class="slide-container">
              <div class="card-wrapper swiper-wrapper">

                  <!-- <div class="card swiper-slide">
                  <div class="image-box">
                      <img src="https://h-img1.cloudbeds.com/199021/1669205371354_featured~~638ec42d17964.jpg" alt="" />
                  </div>
                  <div class="profile-details">
                      <img src="https://h-img1.cloudbeds.com/199021/1669205371354_featured~~638ec42d17964.jpg" alt="" />
                      <div class="name-job">
                      <h3 class="name">Room A</h3>
                      <h4 class="job">Sample Description</h4>
                      </div>
                  </div>
                  </div> -->

              </div>
              </div>
              <div class="swiper-button-next swiper-navBtn"></div>
              <div class="swiper-button-prev swiper-navBtn"></div>
              <div class="swiper-pagination"></div>
          </div>
          </div>
        </div>

        <div class="projects-box">
          <div class="box-section1">
            <div class="box-title">
              <h2>Booking Details</h2>
            </div>
          </div>

          <div class="projects-box-section2">
            <table>
              <thead>
                <tr>
                  <td>Wala Pa</td>
                  <td>Wala Pa</td>
                  <td>Wala Pa</td>
                  <td>Wala Pa</td>
                  <td>Wala Pa</td>
                  <td>Wala Pa</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Wala Pa</td>
                  <td>10 May 2022</td>
                  <td>Wala Pa</td>
                  <td>$5300</td>
                  <td>
                    <img src="./images/team-01.png" alt="" />
                    <img src="./images/team-02.png" alt="" />
                    <img src="./images/team-03.png" alt="" />
                    <img src="./images/team-04.png" alt="" />
                  </td>
                  <td><a href="/#" class="pending">Wala Pa</a></td>
                </tr>
                <tr>
                  <td>Wala Pa</td>
                  <td>12 Oct 2021</td>
                  <td>Wala Pa</td>
                  <td>$1500</td>
                  <td>
                    <img src="./images/team-01.png" alt="" />
                    <img src="./images/team-02.png" alt="" />
                    <img src="./images/team-03.png" alt="" />
                  </td>
                  <td><a href="/#" class="in-progress">Wala Pa</a></td>
                </tr>
                <tr>
                  <td>Wala Pa</td>
                  <td>05 Sep 2021</td>
                  <td>Bouba</td>
                  <td>$800</td>
                  <td>
                    <img src="./images/team-01.png" alt="" />
                    <img src="./images/team-02.png" alt="" />
                  </td>
                  <td><a href="/#" class="completed">Wala Pa</a></td>
                </tr>
                
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
    <script src="js/index-slider.js"></script>
    <script src="js/notificationListener.js"></script>
  </body>
</html>
