<?php 
include('controller/loginSignUpController.php');

if (isset($_COOKIE['hrsCostaToken'])) {  
	
	$hrsCostaToken = $_COOKIE['hrsCostaToken'];
	// SQL query with INNER JOIN and WHERE clause
	$selectUserSql = "
	SELECT 
		u.Id AS userId,
		u.username,
		u.email,
		u.role,
		cff.fbUserId,
		cfg.googleUserId,
		at.token AS accessToken,
		at.expirationDate
	FROM 
		user AS u
	LEFT JOIN 
		created_from_facebook AS cff 
		ON u.Id = cff.userId
	LEFT JOIN 
		created_from_google AS cfg 
		ON u.Id = cfg.userId
	LEFT JOIN 
		access_token AS at 
		ON u.Id = at.userId
	WHERE 
		at.token = ?
	";
	
	$stmt = $conn->prepare($selectUserSql);
	$stmt->bind_param("s", $hrsCostaToken);
	getUserAccess($stmt);

    ?> <script>
		var response = <?php echo json_encode($response); ?>;
		console.log('Cookie Exists: ', response);
		window.location.href = "../dashboard/index.php";
	   </script> 
	<?php
} else {
    ?> <script>console.log('Cookie does not Exists')</script> <?php
}

?>

<link rel="stylesheet" type="text/css" href="css/loginSignUp.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>

<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="">
            <div class="title-container">
             <h1 class="forSignUp" style="color:rgba(255, 121, 19, 1); align-self:center;">Sign Up</h1>
            </div>

			<input type="email" id="email" placeholder="Email" class="forSignUp" maxlength="50"/>
			<div id="email-error" class="error errormsg forSignUp"></div>

			<input type="password" id="password" class="forSignUp" placeholder="Password" maxlength="50" />
			<div id="password-error" class="error errormsg forSignUp"></div>

            <input type="password" id="confirm-password" class="forSignUp" placeholder="Confirm Password" maxlength="50" />
			<div id="confirm-password-error" class="error errormsg forSignUp"></div>

			<h6 class="forVerification display-none" >Check your Email for your verification code. Please don't refresh the page.</h6>
			<input type="text" class="forVerification display-none" id="verificationCode" placeholder="Gmail Verification Code" maxlength="50" />
			<div id="verificationCode-error" class="error errormsg forVerification display-none"></div>

            <div class="loginSignUp-button-container">
			<button class="isClickable forVerification display-none" id="verifyGmail">Verify Email</button>
            <button class="isClickable" id="confirm">Confirm</button>
            <span style="font-size: small;">Already have an account? <span  class="isClickable" id="signIn">Login</span></span>
            </div>

            <span>or</span>
            <div class="social-container">
				<div class="facebook isClickable"><i class="fa-brands fa-facebook-f"></i></div>
				<div class="google isClickable"><i class="fa-brands fa-google-plus-g"></i></div>
			</div>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="">
            <div class="title-container">
            <h1 style="color:rgba(0, 199, 199, 1); font-family: Arial, Helvetica, sans-serif;">Hello</h1>
            <h1 style="color:rgba(255, 121, 19, 1);">Welcome!</h1>
            </div>
			<input type="email" class="forSignUp" placeholder="Email / Username" id="emailOrUserLogin" maxlength="50" />
			<div id="emailOrUserLogin-error" class="error errormsg forSignUp"></div>

			<input type="password" class="forSignUp" placeholder="Password" id="passwordLogin" maxlength="50" />
			<div id="passwordLogin-error" class="error errormsg forSignUp"></div>

            <div class="loginSignUp-button-container">
            <button id="login" class="isClickable" >login</button>
            <button type="button" id="signUp" class="isClickable" >Sign Up</button>
            </div>
			
            <span>or</span>
            <div class="social-container">
				<div class="facebook isClickable"><i class="fa-brands fa-facebook-f"></i></div>
				<div class="google isClickable"><i class="fa-brands fa-google-plus-g"></i></div>
			</div>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left border-right">
                <div class="logo-container">
                  <img src="css/logo.png" alt="Logo" class="logo">
                </div>
			</div>
			<div class="overlay-panel overlay-right border-left">
				<div class="logo-container">
                  <img src="css/logo.png" alt="Logo" class="logo">
                </div>
			</div>
		</div>
	</div>
</div>

<script src="js/loginSignUp.js" defer type="module"></script>