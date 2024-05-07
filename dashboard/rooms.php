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

        <div class="main-content main-view">

          <div class="title">
            <h1>Available Rooms</h1>
          </div>

          <div class="courses-boxes">

            <div class="courses-box">
              <div class="card-image">
              <img src="https://h-img1.cloudbeds.com/uploads/199021/cds-1_1_thumb~~65c4477f47757.png" style="background-color: white;" alt="" />
                <img src="https://h-img1.cloudbeds.com/199021/1669205371354_featured~~638ec42d17964.jpg" alt="" />
              </div>
              <div class="courses-card-body">
                <h4>Room D</h4>
                <p>
                Sample Description
                </p>
                <span>View Room Details</span>
              </div>
              <div class="courses-card-footer">
                <span><i class="fa-regular fa-user"></i>950</span>
                <span><i class="fa-solid fa-dollar-sign"></i>250</span>
              </div>
            </div>

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

            <div class="courses-box">
              <div class="card-image">
              <img src="https://h-img1.cloudbeds.com/uploads/199021/cds-1_1_thumb~~65c4477f47757.png" style="background-color: white;" alt="" />
                <img src="https://h-img3.cloudbeds.com/uploads/199021/deluxe_2_gallery~~61406d9556939.jpg" alt="" />
              </div>
              <div class="courses-card-body">
                <h4>Room B</h4>
                <p>
                Sample Description 
                </p>
                <span>View Room Details</span>
              </div>
              <div class="courses-card-footer">
                <span><i class="fa-regular fa-user"></i>1150</span>
                <span><i class="fa-solid fa-dollar-sign"></i>210</span>
              </div>
            </div>

            <div class="courses-box">
              <div class="card-image">
                <img src="https://h-img1.cloudbeds.com/uploads/199021/cds-1_1_thumb~~65c4477f47757.png" style="background-color: white;" alt="" />
                <img src="https://h-img3.cloudbeds.com/uploads/199021/213_featured~~65c44a016abf5.jpg" alt="" />
              </div>
              <div class="courses-card-body">
                <h4>Room C</h4>
                <p>
                Sample Description 
                </p>
                <span>View Room Details</span>
              </div>
              <div class="courses-card-footer">
                <span><i class="fa-regular fa-user"></i>650</span>
                <span><i class="fa-solid fa-dollar-sign"></i>90</span>
              </div>
            </div>

            <div class="courses-box">
              <div class="card-image">
              <img src="https://h-img1.cloudbeds.com/uploads/199021/cds-1_1_thumb~~65c4477f47757.png" style="background-color: white;" alt="" />
                <img src="https://h-img1.cloudbeds.com/199021/1669205371354_featured~~638ec42d17964.jpg" alt="" />
              </div>
              <div class="courses-card-body">
                <h4>Room D</h4>
                <p>
                Sample Description
                </p>
                <span>View Room Details</span>
              </div>
              <div class="courses-card-footer">
                <span><i class="fa-regular fa-user"></i>950</span>
                <span><i class="fa-solid fa-dollar-sign"></i>250</span>
              </div>
            </div>

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

            <div class="courses-box">
              <div class="card-image">
              <img src="https://h-img1.cloudbeds.com/uploads/199021/cds-1_1_thumb~~65c4477f47757.png" style="background-color: white;" alt="" />
                <img src="https://h-img3.cloudbeds.com/uploads/199021/deluxe_2_gallery~~61406d9556939.jpg" alt="" />
              </div>
              <div class="courses-card-body">
                <h4>Room B</h4>
                <p>
                Sample Description 
                </p>
                <span>View Room Details</span>
              </div>
              <div class="courses-card-footer">
                <span><i class="fa-regular fa-user"></i>1150</span>
                <span><i class="fa-solid fa-dollar-sign"></i>210</span>
              </div>
            </div>

            <div class="courses-box">
              <div class="card-image">
                <img src="https://h-img1.cloudbeds.com/uploads/199021/cds-1_1_thumb~~65c4477f47757.png" style="background-color: white;" alt="" />
                <img src="https://h-img3.cloudbeds.com/uploads/199021/213_featured~~65c44a016abf5.jpg" alt="" />
              </div>
              <div class="courses-card-body">
                <h4>Room C</h4>
                <p>
                Sample Description 
                </p>
                <span>View Room Details</span>
              </div>
              <div class="courses-card-footer">
                <span><i class="fa-regular fa-user"></i>650</span>
                <span><i class="fa-solid fa-dollar-sign"></i>90</span>
              </div>
            </div>

            <div class="courses-box">
              <div class="card-image">
              <img src="https://h-img1.cloudbeds.com/uploads/199021/cds-1_1_thumb~~65c4477f47757.png" style="background-color: white;" alt="" />
                <img src="https://h-img1.cloudbeds.com/199021/1669205371354_featured~~638ec42d17964.jpg" alt="" />
              </div>
              <div class="courses-card-body">
                <h4>Room D</h4>
                <p>
                Sample Description
                </p>
                <span>View Room Details</span>
              </div>
              <div class="courses-card-footer">
                <span><i class="fa-regular fa-user"></i>950</span>
                <span><i class="fa-solid fa-dollar-sign"></i>250</span>
              </div>
            </div>
            
          </div>
          </div>

      </main>
    </div>
    <script>var userOrAdminDetails = <?php echo json_encode($response); ?>;</script>
    <script src="js/script.js"></script>
  </body>
</html>
