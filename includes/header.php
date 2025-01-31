<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MQ-Kitchen</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="user_page/assets/sili.ico" />

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">

    <style type="text/css">
    /* Import Google font - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
        *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif; 
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            max-width: 400px;
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

        .topbar {
            background-color: black;
            height: 40px; 
        }
        .notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4CAF50; /* Green for success */
    color: white;
    padding: 15px 20px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    font-size: 16px;
    z-index: 1000;
    opacity: 0;
    transition: opacity 0.5s ease, transform 0.5s ease;
    transform: translateY(-20px); /* Hidden state */
}

.notification.hidden {
    display: none;
}

.notification.show {
    display: block;
    opacity: 1;
    transform: translateY(0); /* Visible state */
}
    </style>
</head>
<div id="notification" class="notification hidden"></div>

<body>

</body>
</html>