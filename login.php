<link rel="stylesheet" type="text/css" href="frontend/loginSignUp.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>

<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="#">
            <div class="title-container">
             <h1  style="color:rgba(255, 121, 19, 1); align-self:center;">Sign Up</h1>
            </div>

			<input type="email" id="email" placeholder="Gmail" />
			<div id="email-error" class="error errormsg"></div>

			<input type="password" id="password" placeholder="Password" />
			<div id="password-error" class="error errormsg"></div>

            <input type="password" id="confirm-password" placeholder="Confirm Password" />
			<div id="confirm-password-error" class="error errormsg"></div>

            <div class="loginSignUp-button-container">
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
		<form action="#">
            <div class="title-container">
            <h1 style="color:rgba(0, 199, 199, 1); font-family: Arial, Helvetica, sans-serif;">Hello</h1>
            <h1 style="color:rgba(255, 121, 19, 1);">Welcome!</h1>
            </div>
			<input type="email" placeholder="Gmail" />
			<input type="password" placeholder="Password" />

            <div class="loginSignUp-button-container">
            <button id="login" class="isClickable" >login</button>
            <button id="signUp" class="isClickable" >Sign Up</button>
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
                  <img src="frontend/logo.png" alt="Logo" class="logo">
                </div>
			</div>
			<div class="overlay-panel overlay-right border-left">
				<div class="logo-container">
                  <img src="frontend/logo.png" alt="Logo" class="logo">
                </div>
			</div>
		</div>
	</div>
</div>

<script src="scripts/js/loginSignUp.js" defer type="module"></script>