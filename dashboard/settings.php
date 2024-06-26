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
                    <i class="fa-brands fa-twitter"></i>
                    <input type="text" placeholder="Twitter Username" />
                  </div>
                  <div class="social-info-icon">
                    <i class="fa-brands fa-facebook-f"></i>
                    <input type="text" placeholder="Facebook Username" />
                  </div>
                  <div class="social-info-icon">
                    <i class="fa-brands fa-linkedin"></i>
                    <input type="text" placeholder="Linkedin Username" />
                  </div>
                  <div class="social-info-icon">
                    <i class="fa-brands fa-youtube"></i>
                    <input type="text" placeholder="Youtube Username" />
                  </div>
                </form>
              </div>
            </div>
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
            <div class="box">
              <div class="box-section1 settings-section1">
                <div class="box-title">
                  <h2>Backup Manager</h2>
                  <p>Control Backup Time And Location</p>
                </div>
              </div>
              <div class="backup-mangager-section2">
                <form>
                  <ul>
                    <li>
                      <input type="radio" id="radio-one" name="time" />
                      <label for="radio-one">Daily</label>
                    </li>
                    <li>
                      <input type="radio" id="radio-two" name="time" checked />
                      <label for="radio-two">Weekly</label>
                    </li>
                    <li>
                      <input type="radio" id="radio-three" name="time" />
                      <label for="radio-three">Monthly</label>
                    </li>
                  </ul>
                </form>
              </div>
              <div class="backup-manager-section3">
                <input type="radio" name="box-radio" id="box-radio-1" />
                <div>
                  <label for="box-radio-1">
                    <i class="fa-solid fa-server"></i>
                    Megaman
                  </label>
                </div>
                <input type="radio" name="box-radio" id="box-radio-2" />
                <div>
                  <label for="box-radio-2">
                    <i class="fa-solid fa-server"></i>
                    Zero
                  </label>
                </div>
                <input type="radio" name="box-radio" id="box-radio-3" checked />
                <div>
                  <label for="box-radio-3">
                    <i class="fa-solid fa-server"></i>
                    Sigma
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    <script src="../js/script.js"></script>
  </body>
</html>
