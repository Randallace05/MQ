<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "
    <script>
        alert('You must log in to proceed.');
        window.location.href = '../index.php'; // Redirect to login page
    </script>";
    exit;
}

// Include database connection
include '../../conn/conn.php';

try {
    // Create database connection
    $conn = new mysqli($servername, $username, $password, $db);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get logged-in user's ID
    $tbl_user_id = intval($_SESSION['tbl_user_id']); // Ensure ID is an integer for safety

    $cartItems = [];
    $grandTotal = 0;
    // Handle selected products
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products']) && !empty($_POST['selected_products'])) {
        $selected_products = array_map('intval', $_POST['selected_products']); // Ensure IDs are integers

        // Prepare query to fetch selected cart items
        $placeholders = implode(',', array_fill(0, count($selected_products), '?'));
        $stmt = $conn->prepare("SELECT * FROM cart WHERE tbl_user_id = ? AND cart_id IN ($placeholders)");

        // Bind parameters dynamically
        $types = str_repeat('i', count($selected_products) + 1); // 'i' for integers
        $stmt->bind_param($types, $tbl_user_id, ...$selected_products);

        $stmt->execute();
        $result = $stmt->get_result();
        $cartItems = $result->fetch_all(MYSQLI_ASSOC);

        // Calculate the total amount for selected items
        foreach ($cartItems as $item) {
            $grandTotal += $item['price'] * $item['quantity'];
        }
    } else {
        echo "<script>
            alert('No items selected. Please go back and select items to proceed.');
            window.history.back();
        </script>";
        exit;
    }


    // Handle form submission for checkout
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstname'])) {
        // Fetch form data
        $firstName = htmlspecialchars($_POST['firstname']);
        $middleName = htmlspecialchars($_POST['Mname']);
        $lastName = htmlspecialchars($_POST['lname']);
        $address = htmlspecialchars($_POST['address']);
        $city = htmlspecialchars($_POST['city']);
        $zipCode = htmlspecialchars($_POST['z']);
        $contactNumber = htmlspecialchars($_POST['num']);
        $paymentMethod = htmlspecialchars($_POST['payment_method']);

        // Optional: Handle Gcash payment proof upload
        $gcashProofPath = null;
        if ($paymentMethod === 'Gcash Payment' && isset($_FILES['gcash_proof']) && $_FILES['gcash_proof']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../uploads/payment_proofs/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
            }
            $fileName = uniqid() . '_' . basename($_FILES['gcash_proof']['name']);
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['gcash_proof']['tmp_name'], $uploadFile)) {
                $gcashProofPath = $fileName;
            } else {
                die("Error uploading GCash payment proof. Please try again.");
            }
        }

        // Start a transaction
        $conn->begin_transaction();

        try {
            // Insert data into the checkout table
            $stmt = $conn->prepare("
                INSERT INTO checkout (id, firstname, middlename, lastname, address, city, zip_code, contact_number, payment_method, gcash_proof, grand_total, batch_codename)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "isssssssssds",
                $tbl_user_id,
                $firstName,
                $middleName,
                $lastName,
                $address,
                $city,
                $zipCode,
                $contactNumber,
                $paymentMethod,
                $gcashProofPath,
                $grandTotal,
                $cartItems[0]['batch_codename']
            );

            if (!$stmt->execute()) {
                throw new Exception("Error inserting into checkout table: " . $stmt->error);
            }

            $order_id = $conn->insert_id;

            // Insert order items and update product batches
            $order_items_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, batch_codename) VALUES (?, ?, ?, ?, ?)");


            foreach ($cartItems as $item) {
                $order_items_stmt->bind_param("iiids", $order_id, $item['product_id'], $item['quantity'], $item['price'], $item['batch_codename']);
                if (!$order_items_stmt->execute()) {
                    throw new Exception("Error inserting order item: " . $order_items_stmt->error);
                }
            }

            $order_items_stmt->close();

            // Remove processed items from the cart
            $delete_stmt = $conn->prepare("DELETE FROM cart WHERE tbl_user_id = ? AND cart_id IN ($placeholders)");
            $delete_stmt->bind_param($types, $tbl_user_id, ...$selected_products);
            if (!$delete_stmt->execute()) {
                throw new Exception("Error deleting items from cart: " . $delete_stmt->error);
            }
            $delete_stmt->close();

            // Commit the transaction
            $conn->commit();

            // Redirect based on payment method
            if ($paymentMethod === "Cash on Delivery") {
                header("Location: receipt.php?order_id=$order_id");
            } elseif ($paymentMethod === "Gcash Payment") {
                header("Location: ref.php?order_id=$order_id");
            }
            exit;
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $conn->rollback();
            die("Error processing order: " . $e->getMessage());
        }
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
} finally {
    $conn->close(); // Close the database connection
}
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
        <div id="content-wrapper" class="d-flex flex-column">
            <div class="container-fluid">
                <h2 class="mt-4">Check Out</h2>
                <a href="../../user_page/shop.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Shop</a>
                <div class="row">
                    <div class="col-75">
                        <div class="container">
                            <form id="checkoutForm" action="action_page.php" method="POST" enctype="multipart/form-data" onsubmit="return handleCheckout(event)">
                                <div class="row">
                                    <div class="col-50">
                                        <h3>Billing Address</h3>
                                        <!-- Billing fields -->
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
                                        <input
                                            type="text"
                                            id="num"
                                            name="num"
                                            required
                                            pattern="\d{11}"
                                            title="Contact number must be exactly 11 digits."
                                        >
                                    </div>
                                    <div class="col-50">
                                        <h3>Payment</h3>
                                        <!-- Payment selection -->
                                        <label>
                                            <input type="radio" name="payment_method" value="Cash on Delivery" onclick="togglePaymentFields(false)" required> Cash on Delivery
                                        </label><br>
                                        <label>
                                            <input type="radio" name="payment_method" value="Gcash Payment" onclick="togglePaymentFields(true)" required> GCash Payment
                                        </label>
                                        <div id="gcash-image" style="display:none; margin-top: 10px;">
                                            <img src="../../uploads/gcash.png" alt="Gcash Payment" style="max-width: 100%; height: auto;">
                                        </div>
                                        <div id="gcash-upload" style="display:none; margin-top: 10px;">
                                            <label for="gcash-proof">Upload GCash Payment Proof</label>
                                            <input type="file" id="gcash-proof" name="gcash_proof" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <!-- Include hidden inputs for selected products -->
                                <?php foreach ($selected_products as $product_id): ?>
                                    <input type="hidden" name="selected_products[]" value="<?php echo htmlspecialchars($product_id); ?>">
                                <?php endforeach; ?>

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
                                    <b><?php echo count($cartItems); ?></b>
                                </span>
                            </h4>
                            <?php
                            $shippingFee = 60; // Fixed shipping fee
                            $totalPrice = 0;

                            foreach ($cartItems as $item) {
                                $itemTotal = $item['price'] * $item['quantity'];
                                $totalPrice += $itemTotal;
                            ?>
                                <p>
                                    <a href="#"><?php echo htmlspecialchars($item['name']); ?></a>
                                    <span class="price"><?php echo '₱' . number_format($itemTotal, 2); ?></span>
                                </p>
                            <?php } ?>

                            <p>
                                <a href="#">Shipping Fee</a>
                                <span class="price"><?php echo '₱' . number_format($shippingFee, 2); ?></span>
                            </p>

                            <?php $totalPrice += $shippingFee; ?>
                            <hr>
                            <p>
                                <b>Total</b>
                                <span class="price" style="color:black">
                                    <b><?php echo '₱' . number_format($totalPrice, 2); ?></b>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Scripts -->
<script>
    function togglePaymentFields(show) {
        const gcashImage = document.getElementById('gcash-image');
        const gcashUpload = document.getElementById('gcash-upload');

        gcashImage.style.display = show ? 'block' : 'none';
        gcashUpload.style.display = show ? 'block' : 'none';
    }

    function handleCheckout(event) {
        // Show a confirmation dialog
        const confirmation = confirm("Are you sure you want to proceed with the checkout?");
        if (!confirmation) {
            event.preventDefault(); // Stop form submission if the user cancels
            return false;
        }
        // Allow the form to submit and backend to handle the rest
        return true;
    }
</script>

</body>
</html>

