<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include '../conn/conn.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "
    <script>
        alert('You must log in to access the cart.');
        window.location.href = '../index.php'; // Redirect to the login page
    </script>";
    exit;
}

// Get the logged-in user's ID securely from the session
$tbl_user_id = intval($_SESSION['unique_id']);

// Check if the cart is empty
$cart_empty_query = $conn->prepare("SELECT COUNT(*) AS total_items FROM `cart` WHERE tbl_user_id = ?");
$cart_empty_query->bind_param("i", $tbl_user_id);
$cart_empty_query->execute();
$cart_empty_result = $cart_empty_query->get_result();
$cart_empty = ($cart_empty_result->fetch_assoc()['total_items'] == 0);

// Handle "Add to Cart" action from the wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $product_id = intval($_POST['product_id']);

    // Fetch product details from the products table
    $product_query = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $product_query->bind_param("i", $product_id);
    $product_query->execute();
    $product_result = $product_query->get_result();

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
        $product_name = $product['name'];
        $product_image = $product['image'];
        $product_price = $product['price'];

        // Check if the product is already in the cart
        $check_cart_query = $conn->prepare("SELECT * FROM `cart` WHERE tbl_user_id = ? AND product_id = ?");
        $check_cart_query->bind_param("ii", $tbl_user_id, $product_id);
        $check_cart_query->execute();
        $check_cart_result = $check_cart_query->get_result();

        if ($check_cart_result->num_rows > 0) {
            // If already in the cart, display a message
            $_SESSION['error_message'] = "Product is already in your cart.";
        } else {
            // Add the product to the cart
            $insert_cart_query = $conn->prepare("INSERT INTO `cart` (tbl_user_id, product_id, name, image, price, quantity) VALUES (?, ?, ?, ?, ?, 1)");
            $insert_cart_query->bind_param("iissi", $tbl_user_id, $product_id, $product_name, $product_image, $product_price);

            if ($insert_cart_query->execute()) {
                $_SESSION['success_message'] = "Product added to your cart successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to add the product to your cart.";
            }
        }
    } else {
        $_SESSION['error_message'] = "Product not found.";
    }

    // Redirect back to wishlist page
    header("Location: wishlist.php");
    exit;
}

// Handle product quantity update
if (isset($_POST['update_product_quantity'])) {
    $update_value = intval($_POST['update_quantity']);
    $update_id = intval($_POST['update_quantity_id']);

    // Validate if the cart item belongs to the logged-in user
    $check_query = $conn->prepare("SELECT * FROM `cart` WHERE cart_id = ? AND tbl_user_id = ?");
    $check_query->bind_param("ii", $update_id, $tbl_user_id);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if the cart item exists for this user
        $update_quantity_query = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE cart_id = ? AND tbl_user_id = ?");
        $update_quantity_query->bind_param("iii", $update_value, $update_id, $tbl_user_id);
        $update_quantity_query->execute();
    } else {
        echo "
        <script>
            alert('Unauthorized action.');
            window.location.href = 'cart.php';
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/styless.css" rel="stylesheet">
    <style>
        .cart-container {
            max-width: 900px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .bottom-btn {
            display: inline-block;
            background-color:rgb(255, 0, 0);
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .bottom-btn.disabled {
            background-color: #ccc;
            pointer-events: none;
        }

        .delete-all-btn {
            display: block;
            text-align: center;
            color: red;
            margin-top: 15px;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container cart-container">
    <section class="shopping_cart">
        <h1 class="heading">My Cart</h1>
        <form id="cart-form" method="POST">
            <table>
                <?php
                $select_cart_products = $conn->prepare("SELECT * FROM `cart` WHERE tbl_user_id = ?");
                $select_cart_products->bind_param("i", $tbl_user_id);
                $select_cart_products->execute();
                $result = $select_cart_products->get_result();

                if ($result->num_rows > 0) {
                    echo "<thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Product Name</th>
                            <th>Product Image</th>
                            <th>Product Price</th>
                            <th>Product Quantity</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";

                    $sl_no = 1;
                    while ($fetch_cart_products = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $sl_no++; ?></td>
                            <td><?php echo htmlspecialchars($fetch_cart_products['name']); ?></td>
                            <td>
                                <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($fetch_cart_products['image']); ?>"
                                     alt="" style="width: 100px; height: auto;">
                            </td>
                            <td><?php echo "₱" . htmlspecialchars($fetch_cart_products['price']); ?></td>
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" value="<?php echo htmlspecialchars($fetch_cart_products['cart_id']); ?>" name="update_quantity_id">
                                    <div class="quantity_box">
                                        <select name="update_quantity">
                                            <?php for ($i = 1; $i <= 99; $i++): ?>
                                                <option value="<?php echo $i; ?>" <?php echo $fetch_cart_products['quantity'] == $i ? 'selected' : ''; ?>>
                                                    <?php echo $i; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                        <input type="submit" class="update_quantity" value="Update" name="update_product_quantity">
                                    </div>
                                </form>
                            </td>
                            <td><?php echo "₱" . htmlspecialchars($fetch_cart_products['price'] * $fetch_cart_products['quantity']); ?></td>
                            <td>
                                <a href="delete_cart_item.php?id=<?php echo htmlspecialchars($fetch_cart_products['cart_id']); ?>"
                                   onclick="return confirm('Are you sure you want to remove this item?');">
                                    <i class="fas fa-trash"></i> Remove
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    echo "</tbody>";
                } else {
                    echo "<tr><td colspan='7'>No products in the cart</td></tr>";
                }
                ?>
            </table>
        </form>
        <div class="table_bottom">
            <a href="shop.php" class="bottom-btn">Continue Shopping</a>
            <h3 class="bottom-btn">
                Total:
                <?php
                $total_query = $conn->prepare("SELECT SUM(price * quantity) AS total_price FROM `cart` WHERE tbl_user_id = ?");
                $total_query->bind_param("i", $tbl_user_id);
                $total_query->execute();
                $total_result = $total_query->get_result();
                $total = $total_result->fetch_assoc()['total_price'] ?? 0;
                echo "₱" . number_format($total, 2);
                ?>
            </h3>
            <a href="../admin_page/bill/checkout.php"
               class="bottom-btn<?php echo $cart_empty ? ' disabled' : ''; ?>"
               <?php echo $cart_empty ? 'onclick="return false;" style="pointer-events: none; opacity: 0.5;"' : ''; ?>>
                Proceed to Checkout
            </a>
        </div>
        <a href="delete_all_cart_items.php" class="delete-all-btn"
           onclick="return confirm('Are you sure you want to remove all items?');">
            <i class="fas fa-trash"></i> Delete All
        </a>
    </section>
</div>
</body>
</html>
