<<<<<<< HEAD:login/index.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MO Kitchen Sign Up</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header and Navbar */
        .header {
            background-color: black;
            padding: 0px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .header img {
            width: 80px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .search-bar {
            padding: 5px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        /* Main Content */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            padding: 40px;
        }

        .form-container {
            max-width: 400px;
            width: 100%;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            box-sizing: border-box;
        }

        .form-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .form-container .radio-group {
            margin: 10px 0;
            display: flex;
            gap: 10px;
        }

        .form-container button {
            background-color: red;
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .form-container button:hover {
            background-color: darkred;
        }

        /* Add image styling */
        .image-container {
            max-width: 500px;
            width: 100%;
            margin-right: 20px;
        }

        .image-container img {
            width: 100%;
            border-radius: 10px;
        }

        .flex-container {
            display: flex;
            gap: 20px;
        }

        /* Footer */
        footer {
            background-color: black;
            color: white;
            padding: 2px 150px;
            display: flex;
            flex-wrap: wrap;
            font-size: 12px;
        }

        footer .column {
            flex: 1 1 200px;
            margin-left: 75px;
            margin-top: 40px;
            padding: 1px;
            box-sizing: border-box;
            font-size: 12px;
        }

        footer input[type="email"] {
            padding: 10px;
            border-radius: 5px;
            border: none;
            margin-right: 10px;
            width: 200px;
        }

        footer button {
            padding: 10px;
            border: none;
            background-color: red;
            color: white;
            border-radius: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
            }

            .search-bar {
                width: 100%;
                margin-top: 10px;
            }

            .container {
                padding: 20px;
            }

            footer .column {
                flex: 1 1 100%;
                margin-bottom: 20px;
            }

            .flex-container {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 10px;
            }

            .form-container button {
                padding: 10px;
            }
        }
        .switch-form-link {
            text-decoration: underline;
            cursor: pointer;
            color: rgb(100, 100, 200);
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <img src="assets/mq-kitchen.png" alt="MO Kitchen Logo">
        <div class="nav-links">
        </div>
        <input type="text" class="search-bar" placeholder="What are you looking for?">
    </div>

    <!-- Main Container -->
    <div class="container">
        <div class="flex-container">
            <!-- Image Section -->
            <div class="image-container">
                <img src="assets/mq-kitchen.png" alt="MO Kitchen">
            </div>

            <!-- Form Section -->
            <div class="main">
               <!-- Login Area -->

        <div class="login-container">
            <div class="login-form" id="loginForm">
                <h2 class="text-center">Welcome to MQ Kitchen!</h2>
                <p class="text-center">Fill your login details.</p>
                <form action="./endpoint/login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <p>No Account? Register <span  class="switch-form-link" onclick="showRegistrationForm()">Here.</span></p>
                    <button type="submit" class="btn btn-secondary login-btn form-control" >Login</button>
                </form>
            </div>
        </div>



        <!-- Registration Area -->
        <div class="registration-form" id="registrationForm">
            <h2 class="text-center">Registration Form</h2>
            <p class="text-center">Fill in you personal details.</p>
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
                    <label><input type="radio" name="accountType" value="customer" checked> Customer</label>
                    <label><input type="radio" name="accountType" value="distributor"> Distributor</label>
                </div>
                <p>Already have an account? Login <span class="switch-form-link" onclick="showLoginForm()">Here.</span></p>
                <button type="submit" class="btn btn-dark login-register form-control" name="register">Register</button>

            </form>

        </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="column">
            <h4>Exclusive</h4>
            <p>Get 10% off your first order</p>
            <input type="email" placeholder="Enter your email">
            <button>Subscribe</button>
        </div>
        <div class="column">
            <h4>Support</h4>
            <p>Tanza, Cavite</p>
            <p>Email: MQKitchen@gmail.com</p>
        </div>
        <div class="column">
            <h4>Account</h4>
            <p><a href="#" style="color: white;">My Account</a></p>
            <p><a href="#" style="color: white;">Login / Register</a></p>
            <p><a href="#" style="color: white;">Shop</a></p>
        </div>
        <div class="column">
            <h4>Quick Link</h4>
            <p><a href="#" style="color: white;">Terms Of Use</a></p>
            <p><a href="#" style="color: white;">FAQ</a></p>
            <p><a href="#" style="color: white;">Contact</a></p>
        </div>
    </footer>

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

    <!-- Bootstrap Js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
=======
<!-- start include header -->
<?php include("includes/header.php"); ?>
<!-- end include header -->

    <!-- Top Bar Section -->
        <?php include("includes/topbar.php"); ?>
    <!-- End Top Bar Section -->

    <!-- Main Container -->
    <div class="container">
        <div class="flex-container">
            <!-- Image Section -->
            <div class="image-container">
                <img src="mq-kitchen.png" alt="MO Kitchen">
            </div>

            <!-- Form Section -->
            <div class="main">

        <!-- Login Area -->
        <div class="login-container">
            <div class="login-form" id="loginForm">
                <h2 class="text-center">Welcome to MQ Kitchen!</h2>
                <p class="text-center">Fill your login details.</p>
                <form action="./endpoint/login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <p>No Account? Register <span  class="switch-form-link" onclick="showRegistrationForm()">Here.</span></p>
                    <button type="submit" class="btn btn-secondary login-btn form-control" >Login</button>
                </form>
            </div>
        </div>
        <!-- End Login Area -->


        <!-- Registration Area -->
        <div class="registration-form" id="registrationForm">
            <h2 class="text-center">Registration Form</h2>
            <p class="text-center">Fill in you personal details.</p>
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
                    <label><input type="radio" name="accountType" value="customer" checked> Customer</label>
                    <label><input type="radio" name="accountType" value="distributor"> Distributor</label>
                </div>
                <p>Already have an account? Login <span class="switch-form-link" onclick="showLoginForm()">Here.</span></p>
                <button type="submit" class="btn btn-dark login-register form-control" name="register">Register</button>
            </form>
        </div>
        <!-- Registration Area -->
            </div>
        </div>
    </div>

<!-- Footer Section -->
    <?php include("includes/footer.php"); ?>
<!-- End Footer Section -->
    


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
>>>>>>> 411c1e1bb4b2a0849556601f3d20cd513138e84f:index.php
