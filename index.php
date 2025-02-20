<!-- session -->
<?php 
  session_start();
  if(isset($_SESSION['unique_id'])){
    header("location: user_page/shop.php");
  }
?>
<style>
    /* Custom styles for MQ Kitchen */
body {
    font-family: 'Inter', sans-serif; /* Use a clean and modern font */
    background: linear-gradient(to bottom, #ffedd5, #fffbeb); /* Gradient background */
    color: #374151; /* Dark gray for better contrast */
    min-height: 100vh; /* Ensure the body covers the full viewport height */
}

header h1 {
    letter-spacing: 2px; /* Add some spacing for a modern look */
}

nav ul li a {
    transition: color 0.3s ease-in-out; /* Smooth hover effect */
}

marquee {
    font-weight: bold;
    animation: colorChange 10s infinite;
}

@keyframes colorChange {
    0%, 100% {
        color: #d97706; /* Yellow-800 */
    }
    50% {
        color: #ea580c; /* Orange-600 */
    }
}

img {
    transition: transform 0.3s ease-in-out; /* Smooth zoom on hover */
}

img:hover {
    transform: scale(1.05);
}

button {
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    transform: translateY(-2px); /* Lift button on hover */
}

input:focus, select:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5); /* Indigo ring on focus */
}

footer p {
    font-size: 0.875rem; /* Smaller font for a polished footer */
    color: #9ca3af; /* Gray-400 */
}

.radio-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem; /* Space between radio buttons and text */
}

.checkbox-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem; /* Space between checkbox and text */
}

@media (max-width: 768px) {
    header h1 {
        font-size: 1.5rem; /* Adjust font size for smaller screens */
    }

    marquee {
        font-size: 0.875rem; /* Smaller marquee text for mobile */
    }
}
/* try klung makaakpg push */

</style>
<head>
    <title>MQ Kitchen</title>
    <link rel="icon" type="image/x-icon" href="uploads/sili.ico" />
</head>

<?php include("loader.php"); ?>

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
                            <p>Forgot your password? <a href="forgot-password.php">Reset it here</a></p>
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
                                <input type="" class="form-control" id="contactNumber" name="contact_number" maxlength="11">
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
                        <div class="radio-group" style="display: none;">
                            <label><input type="radio" name="user_role" value="customer" checked> Customer</label>
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

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="errorModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
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

    // New login form submission handler
    document.getElementById('loginFormSubmit').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('endpoint/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                // Show error in modal
                document.getElementById('errorModalBody').textContent = data.error;
                $('#errorModal').modal('show');
            } else if (data.success) {
                // Redirect based on user role
                switch (data.role) {
                    case 'admin':
                        window.location.href = 'admin_page/dashboard/index.php';
                        break;
                    case 'customer':
                    case 'distributor':
                        window.location.href = 'user_page/shop.php';
                        break;
                    default:
                        document.getElementById('errorModalBody').textContent = 'An error occurred. Please try again later.';
                        $('#errorModal').modal('show');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('errorModalBody').textContent = 'An error occurred. Please try again later.';
            $('#errorModal').modal('show');
        });
    });
</script>

