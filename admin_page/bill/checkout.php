<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "
    <script>
        alert('You must log in to proceed.');
        window.location.href = '../index.php'; // Redirect to login page
    </script>
    ";
    exit;
}

// Include database connection
include ('action_page.php');

// Database connection (adjust the credentials as necessary)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_email_verification";  // replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in user's ID from the session
$tbl_user_id = intval($_SESSION['tbl_user_id']); // Ensure the ID is an integer for safety

// Query to get items from the cart for the logged-in user
$sql = "SELECT * FROM cart WHERE tbl_user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tbl_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Check Out</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
.row {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  -ms-flex-wrap: wrap; /* IE10 */
  flex-wrap: wrap;
  margin: 0 -16px;
}

.col-25 {
  -ms-flex: 25%; /* IE10 */
  flex: 25%;
}

.col-50 {
  -ms-flex: 50%; /* IE10 */
  flex: 50%;
}

.col-75 {
  -ms-flex: 75%; /* IE10 */
  flex: 75%;
}

.col-25,
.col-50,
.col-75 {
  padding: 0 16px;
}

.container {
  background-color: #f2f2f2;
  padding: 5px 20px 15px 20px;
  border: 1px solid lightgrey;
  border-radius: 3px;
}

input[type=text] {
  width: 100%;
  margin-bottom: 20px;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 3px;
}

label {
  margin-bottom: 10px;
  display: block;
}

.icon-container {
  margin-bottom: 20px;
  padding: 7px 0;
  font-size: 24px;
}

.btn {
  background-color: #04AA6D;
  color: white;
  padding: 12px;
  margin: 10px 0;
  border: none;
  width: 100%;
  border-radius: 3px;
  cursor: pointer;
  font-size: 17px;
}

.btn:hover {
  background-color: #45a049;
}

span.price {
  float: right;
  color: grey;
}

/* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (and change the direction - make the "cart" column go on top) */
@media (max-width: 800px) {
  .row {
    flex-direction: column-reverse;
  }
  .col-25 {
    margin-bottom: 20px;
  }
}
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include("sidebar.php"); ?>
        <!-- End of Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Topbar -->
            <?php include("../includesAdmin/topbar.php"); ?>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <h2 class="mt-4">Check Out</h2>
                <div class="row">
                    <div class="col-75">
                        <div class="container">
                            <form id="checkoutForm" action="receipt.php" method="POST" onsubmit="return handleCheckout(event)">
                                <div class="row">
                                    <div class="col-50">
                                        <h3>Billing Address</h3>
                                        <label for="fname"><i class="fa fa-user"></i> First Name</label>
                                        <input type="text" id="fname" name="firstname" required>

                                        <label for="email"><i class="fa fa-user"></i> Middle Name</label>
                                        <input type="text" id="email" name="Mname" required>

                                        <label for="email"><i class="fa fa-user"></i> Last Name</label>
                                        <input type="text" id="email" name="lname" required>

                                        <label for="adr"><i class="fa fa-institution"></i> Address</label>
                                        <input type="text" id="adr" name="address" required>

                                        <label for="city"><i class="fa fa-institution"></i> City</label>
                                        <input type="text" id="city" name="city" required>

                                        <label for="z"><i class="fa fa-institution"></i> Zip Code</label>
                                        <input type="text" id="z" name="z" required>

                                        <label for="num"><i class="fa fa-phone"></i> Contact Number</label>
                                        <input type="text" id="num" name="num" required>
                                    </div>
                                    <div class="col-50">
                                        <h3>Payment</h3>
                                        <label>
                                            <input type="radio" name="payment_method" value="Cash on Delivery" onclick="togglePaymentImage(false)" required> Cash on Delivery
                                        </label>
                                        <br>
                                        <label>
                                            <input type="radio" name="payment_method" value="Gcash Payment" onclick="togglePaymentImage(true)" required> GCash Payment
                                        </label>
                                        <div id="gcash-image" style="display:none; margin-top: 10px;">
                                            <img src="../../uploads/gcash.png" alt="Gcash Payment" style="max-width: 100%; height: auto;">
                                        </div>
                                    </div>
                                </div>
                                <label>
                                    <input type="checkbox" checked="checked" name="sameadr"> Shipping address same as billing
                                </label>
                                <input type="submit" value="Continue to checkout" class="btn">
                            </form>
                        </div>
                    </div>
                    <div class="col-25">
                        <div class="container">
                            <h4>Cart
                                <span class="price" style="color:black">
                                    <i class="fa fa-shopping-cart"></i>
                                    <b><?php echo $result->num_rows; ?></b>
                                </span>
                            </h4>
                            <?php
                            $grandTotal = 0;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $itemTotal = $row["price"] * $row["quantity"];
                                    echo "<p><a href='#'>" . htmlspecialchars($row["name"]) . " (x" . htmlspecialchars($row["quantity"]) . ")</a> <span class='price'>₱" . htmlspecialchars($itemTotal) . "</span></p>";
                                    $grandTotal += $itemTotal;
                                }
                                echo "<hr>";
                                echo "<p>Total <span class='price' style='color:black'><b>₱" . htmlspecialchars($grandTotal) . "</b></span></p>";
                            } else {
                                echo "<p>Your cart is empty.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
    function togglePaymentImage(show) {
        const gcashImage = document.getElementById('gcash-image');
        gcashImage.style.display = show ? 'block' : 'none';
    }

    function handleCheckout(event) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        if (paymentMethod === "Gcash Payment") {
            event.preventDefault(); // Prevent default form submission
            window.location.href = "ref.php"; // Redirect to ref.php
        } else if (paymentMethod === "Cash on Delivery") {
            return confirm("You have chosen Cash on Delivery. Do you want to proceed to checkout?");
        }
        return true; // Allow form submission
    }
    </script>
</body>
</html>

<?php
$conn->close();
?>
