<!DOCTYPE html>
<html lang="en">
<h>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    <style>
        /* From Uiverse.io by shadowmurphy */ 
        .chili{
        --pathlength: 1384;
        width: 90px;
        fill: transparent;
        stroke: red;
        stroke-linecap: round;
        stroke-width: 15px;
        stroke-dashoffset: var(--pathlength);
        stroke-dasharray: 0 var(--pathlength);
        animation: loader 3.5s cubic-bezier(.5,.1,.5,1) infinite both;
        }

        @keyframes loader {
        90%, 100% {
            stroke-dashoffset: 0;
            stroke-dasharray: var(--pathlength) 0;
        }
        }
    </style>
</head>

<body onload="myFunction()">
    
    <div id="loader" style="margin-top: 50vh; text-align: center;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="chili"> 
            <path d="M22.7 33.4c13.5-4.1 28.1 1.1 35.9 12.9L224 294.3 389.4 46.3c7.8-11.7 22.4-17 35.9-12.9S448 49.9 448 64l0 384c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-278.3L250.6 369.8c-5.9 8.9-15.9 14.2-26.6 14.2s-20.7-5.3-26.6-14.2L64 169.7 64 448c0 17.7-14.3 32-32 32s-32-14.3-32-32L0 64C0 49.9 9.2 37.5 22.7 33.4z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="chili">
            <path d="M64 256c0 88.4 71.6 160 160 160c28.9 0 56-7.7 79.4-21.1l-72-86.4c-11.3-13.6-9.5-33.8 4.1-45.1s33.8-9.5 45.1 4.1l70.9 85.1C371.9 325.8 384 292.3 384 256c0-88.4-71.6-160-160-160S64 167.6 64 256zM344.9 444.6C310 467 268.5 480 224 480C100.3 480 0 379.7 0 256S100.3 32 224 32s224 100.3 224 224c0 56.1-20.6 107.4-54.7 146.7l47.3 56.8c11.3 13.6 9.5 33.8-4.1 45.1s-33.8 9.5-45.1-4.1l-46.6-55.9z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="chili">
            <path d="M428.3 3c11.6-6.4 26.2-2.3 32.6 9.3l4.8 8.7c19.3 34.7 19.8 75.7 3.4 110C495.8 159.6 512 197.9 512 240c0 18.5-3.1 36.3-8.9 52.8c-6.1 17.3-28.5 16.3-36.8-.1l-11.7-23.4c-4.1-8.1-12.4-13.3-21.5-13.3L360 256c-13.3 0-24-10.7-24-24l0-80c0-13.3-10.7-24-24-24l-17.1 0c-21.3 0-30-23.9-10.8-32.9C304.7 85.4 327.7 80 352 80c28.3 0 54.8 7.3 77.8 20.2c5.5-18.2 3.7-38.4-6-55.8L419 35.7c-6.4-11.6-2.3-26.2 9.3-32.6zM171.2 345.5L264 160l40 0 0 80c0 26.5 21.5 48 48 48l76.2 0 23.9 47.8C372.3 443.9 244.3 512 103.2 512l-58.8 0C19.9 512 0 492.1 0 467.6c0-20.8 14.5-38.8 34.8-43.3l49.8-11.1c37.6-8.4 69.5-33.2 86.7-67.7z"/>
        </svg>
    </div>

    <div style="display:none;" id="myDiv" class="">
        <!-- session -->
<?php 
  session_start();
  if(isset($_SESSION['unique_id'])){
    header("location: user_page/shop.php");
  }
?>

<head>
    <title>MQ Kitchen</title>
    <link rel="icon" type="image/x-icon" href="uploads/sili.ico" />
</head>

<!-- start include header -->
    <?php include("includes/header.php"); ?>
<!-- end include header -->

    <!-- Top Bar Section -->
        <div class="topbar">
            <marquee>
                <p></p>
            </marquee>
        </div>
    <!-- End Top Bar Section -->

    <!-- Main Container -->
    <div class="container">
        <div class="flex-container">
            <!-- Image Section -->
            <div class="image-container">
                <img src="uploads/try3.png" alt="MO Kitchen">
            </div>

            <!-- Form Section -->
            <div class="main">

                <!-- Login Area -->
                <div class="login-container">
                    <div class="login-form" id="loginForm">
                        <img src="uploads/welcome to.png" alt="MO Kitchen" style="width: 250px; margin-top:60px;">
                        <p class="text-center">Fill your login details.</p>
                        <form id="loginFormSubmit" action="endpoint/login.php" method="POST">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <p>No Account? Register <span class="switch-form-link" onclick="showRegistrationForm()">Here.</span></p>
                            <button type="submit" class="btn btn-secondary login-btn form-control">Login</button>
                        </form>
                        <div class="error-text"></div>
                    </div>
                </div>
                <!-- End Login Area -->


                <!-- Registration Area -->
                <div class="registration-form" id="registrationForm">
                    <h2 class="text-center">Registration Form</h2>
                    <p class="text-center">Fill in your personal details.</p>
                    <form action="./endpoint/add-user.php" method="POST">
                        <div class="form-group registration row">
                            <div class="col-6">
                                <label for="firstName">First Name:</label>
                                <input type="text" class="form-control" id="firstName" name="first_name">
                            </div>
                            <div class="col-6">
                                <label for="lastName">Last Name:</label>
                                <input type="text" class="form-control" id="lastName" name="last_name">
                            </div>
                        </div>
                        <div class="form-group registration row">
                            <div class="col-5">
                                <label for="contactNumber">Contact Number:</label>
                                <input type="number" class="form-control" id="contactNumber" name="contact_number" maxlength="11">
                            </div>
                            <div class="col-7">
                                <label for="email">Email:</label>
                                <input type="text" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="form-group registration">
                            <label for="registerUsername">Username:</label>
                            <input type="text" class="form-control" id="registerUsername" name="username">
                        </div>
                        <div class="form-group registration">
                            <label for="registerPassword">Password:</label>
                            <input type="password" class="form-control" id="registerPassword" name="password">
                        </div>
                        <div class="radio-group">
                            <label><input type="radio" name="user_role" value="customer"> Customer</label>
                            <label><input type="radio" name="user_role" value="distributor"> Distributor</label>
                        </div>
                        <!-- New Terms and Conditions Checkbox with Modal Trigger -->
                        <div class="form-group terms">
                            <input type="checkbox" id="termsConditions" name="terms_conditions" required>
                            <label for="termsConditions">I agree to the <a href="#" data-toggle="modal" data-target="#termsModal">Terms and Conditions</a></label>
                        </div>
                        <p>Already have an account? Login <span class="switch-form-link" onclick="showLoginForm()">Here.</span></p>
                        <button type="submit" class="btn btn-dark login-register form-control" name="register">Register</button>
                    </form>
                </div>
                <!-- end of Registration Area -->

                    <!-- Modal -->
                    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                    <div class="modal-body">
                                        <p>Welcome to MQ Kitchen Store! Please read these terms and conditions carefully before using our services. By registering on our website through the form provided, you agree to be bound by the following terms:</p>

                                        <p><strong>1. Accuracy of Information</strong><br>
                                        You are responsible for ensuring that all information you provide during registration is accurate, current, and complete. Any inaccuracies may result in restrictions or suspension of your account.</p>

                                        <p><strong>2. User Responsibilities</strong><br>
                                        You agree not to use the registration form for any illegal or unauthorized purpose.<br>
                                        You must be at least 18 years old to register as a user of this site.<br>
                                        You are solely responsible for maintaining the confidentiality of your password and for any activities that occur under your account.</p>

                                        <p><strong>3. User Roles and Rights</strong><br>
                                        During registration, you may be asked to select a user role (e.g., Customer or Distributor). The access rights and available features may differ depending on the role you choose.<br>
                                        It is your responsibility to select the appropriate role for your intended usage. Misuse of roles may result in account restrictions.</p>

                                        <p><strong>4. Data Privacy</strong><br>
                                        Any personal data collected through this form will be used and stored in compliance with our Privacy Policy.<br>
                                        We respect your privacy and commit to protecting your personal information from unauthorized access or disclosure. By submitting the registration form, you consent to the processing of your data for account creation and other related services.</p>

                                        <p><strong>5. Account Management</strong><br>
                                        You may update or correct your account information at any time by contacting our support team or accessing your user account settings.<br>
                                        We reserve the right to suspend or terminate accounts that violate our terms, misuse our platform, or engage in fraudulent activities.</p>

                                        <p><strong>6. Use of Services</strong><br>
                                        You may not engage in activities that disrupt the functionality of our website or compromise the security of our services.<br>
                                        You are prohibited from using automated systems (such as bots or scripts) to interact with our registration process or any other website features.</p>

                                        <p><strong>7. Changes to Terms</strong><br>
                                        We may modify these terms and conditions at any time. Registered users will be notified of any significant changes via email or on our website. Continued use of the services following any updates constitutes your acceptance of the modified terms.</p>

                                        <p><strong>8. Disclaimer</strong><br>
                                        While we strive to ensure the security and functionality of our website, we cannot guarantee uninterrupted service or the absolute security of your data.<br>
                                        We are not liable for any losses or damages resulting from your use of our services unless caused directly by our negligence.</p>

                                        <p><strong>9. Governing Law</strong><br>
                                        These terms are governed by and construed in accordance with the laws of [Your Jurisdiction], without regard to its conflict of law provisions.</p>
                                    </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

<!-- Footer Section -->
    <?php include("includes/footer.php"); ?>
<!-- End Footer Section -->

<script src="admin_page/chat/javascript/login.js"></script>

<script>
    const loginForm = document.getElementById('loginForm');
    const registrationForm = document.getElementById('registrationForm');

    registrationForm.style.display = "none";

    function showRegistrationForm() {
        registrationForm.style.display = "";
        loginForm.style.display = "none";
    }

    function showLoginForm() {
        registrationForm.style.display = "none";
        loginForm.style.display = "";
    }

    function sendVerificationCode() {
        const registrationElements = document.querySelectorAll('.registration');
        registrationElements.forEach(element => {
            element.style.display = 'none';
        });
        const verification = document.querySelector('.verification');
        if (verification) {
            verification.style.display = 'none';
        }
    }
</script>

    </div>


    <script>
        var myVar;

        function myFunction() {
            myVar = setTimeout(showPage, 2000);
        }
            
        function showPage() {
            document.getElementById("loader").style.display = "none";
            document.getElementById("myDiv").style.display = "block";
        }
    </script>
</body>