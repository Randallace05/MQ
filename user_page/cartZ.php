<?php
require_once '../endpoint/session_config.php';
include '../conn/conn.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>
        alert('You must log in to access the cart.');
        window.location.href = '../index.php';
    </script>";
    exit;
}

$tbl_user_id = intval($_SESSION['tbl_user_id']);

// Fetch cart items
$select_cart_products = $conn->prepare("SELECT c.*, p.stock FROM `cart` c JOIN `products` p ON c.product_id = p.id WHERE c.tbl_user_id = ?");
$select_cart_products->bind_param("i", $tbl_user_id);
$select_cart_products->execute();
$result = $select_cart_products->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart</title>
  <style>
   

    .cart-container {
      width: 100%;
      background: white;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .cart-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .cart-table th,
    .cart-table td {
      text-align: left;
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }

    .product-info {
      display: flex;
      align-items: center;
    }

    .product-info img {
      width: 50px;
      height: 80px;
      margin-right: 10px;
    }

    .remove-btn {
      background: none;
      border: none;
      font-size: 18px;
      cursor: pointer;
      color: #DB4444;
    }

    .cart-actions {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .btn {
      padding: 10px 20px;
      border: 1px solid #bab7b7;
      background: white;
      cursor: pointer;
      transition: 0.3s;
      border-radius: 5px;
    }

    .btn:hover {
      background: #f0f0f0;
    }

    .cart-summary {
      width: 80%;
      border: 1px solid black;
      padding: 20px;
      margin: 0 auto;
      border-radius: 5px;
    }

    .cart-summary h3 {
      margin-top: 0;
    }

    .summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .summary-item.total {
      font-weight: bold;
    }

   .checkout-btn {
      width: 50%;
      padding: 10px;
      background: #DB4444;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 16px;
      border-radius: 5px;
      text-transform: uppercase;
      font-weight: bold;
      display: block;
      margin: 0 auto;
    }

    .checkout-btn:hover {
      background: #d62839;
    }

    .quantity-controls {
      display: flex;
      align-items: center;
      justify-content: left;
    }

    .quantity-btn {
      width: 30px;
      height: 30px;
      border: 1px solid #ddd;
      background: #DB4444;
      cursor: pointer;
      font-size: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.3s;
      border-radius: 5px;
      color: white;
    }

    .quantity-btn:hover {
      background: #962f39;
    }

    .quantity-input {
      width: 40px;
      height: 30px;
      text-align: center;
      border: 1px solid #ddd;
      margin: 0 5px;
      font-size: 14px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="cart-container">
    <table class="cart-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total = 0;
        while ($fetch_cart_products = $result->fetch_assoc()) {
          $subtotal = $fetch_cart_products['price'] * $fetch_cart_products['quantity'];
          $total += $subtotal;
        ?>
          <tr>
            <td>
              <div class="product-info">
                <button class="remove-btn" onclick="removeItem(<?php echo $fetch_cart_products['cart_id']; ?>)">❌</button>
                <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($fetch_cart_products['image']); ?>" alt="<?php echo htmlspecialchars($fetch_cart_products['name']); ?>">
                <span><?php echo htmlspecialchars($fetch_cart_products['name']); ?></span>
              </div>
            </td>
            <td>₱ <?php echo number_format($fetch_cart_products['price'], 2); ?></td>
            <td>
              <div class="quantity-controls">
                <button class="quantity-btn minus" onclick="updateQuantity(<?php echo $fetch_cart_products['cart_id']; ?>, 'decrease')">-</button>
                <input type="text" value="<?php echo $fetch_cart_products['quantity']; ?>" class="quantity-input" readonly data-stock="<?php echo $fetch_cart_products['stock']; ?>">
                <button class="quantity-btn plus" onclick="updateQuantity(<?php echo $fetch_cart_products['cart_id']; ?>, 'increase')">+</button>
              </div>
            </td>
            <td>₱ <?php echo number_format($subtotal, 2); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <div class="cart-actions">
      <a href="shop.php" class="btn">Return To Shop</a>
    </div>
    <div class="cart-summary">
      <h3>Cart Total</h3>
      <div class="summary-item">
        <span>Subtotal:</span>
        <span>₱ <?php echo number_format($total, 2); ?></span>
      </div>
      <div class="summary-item">
        <span>Shipping:</span>
        <span>Free</span>
      </div>
      <div class="summary-item total">
        <span>Total:</span>
        <span>₱ <?php echo number_format($total, 2); ?></span>
      </div>
      <a href="../admin_page/bill/checkout.php" class="checkout-btn">Proceed to checkout</a>
    </div>
  </div>

  <script>
    function updateQuantity(cartId, action) {
      const quantityInput = document.querySelector(`input[data-cart-id="${cartId}"]`);
      let currentQuantity = parseInt(quantityInput.value);
      const maxStock = parseInt(quantityInput.dataset.stock);

      if (action === 'increase' && currentQuantity < maxStock) {
        currentQuantity++;
      } else if (action === 'decrease' && currentQuantity > 1) {
        currentQuantity--;
      }

      fetch('update_cart_quantity.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `cart_id=${cartId}&quantity=${currentQuantity}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          quantityInput.value = currentQuantity;
          location.reload(); // Reload the page to update totals
        } else {
          alert('Failed to update quantity');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the quantity');
      });
    }

    function removeItem(cartId) {
      if (confirm('Are you sure you want to remove this item?')) {
        fetch('delete_cart_item.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `id=${cartId}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload(); // Reload the page to update the cart
          } else {
            alert('Failed to remove item');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while removing the item');
        });
      }
    }
  </script>
</body>
</html>

