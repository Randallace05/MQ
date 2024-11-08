<?php
include '../conn/conn.php'; // Ensure that this file establishes a connection using PDO

if (isset($_POST['update_product_quantity'])) {
    $update_value = intval($_POST['update_quantity']); // Sanitize input to prevent SQL injection
    $update_id = intval($_POST['update_quantity_id']); // Sanitize input to prevent SQL injection

    // Use prepared statements for safer queries
    $update_quantity_query = $conn->prepare("UPDATE `cart` SET quantity = :quantity WHERE cart_id = :cart_id");
    $update_quantity_query->bindParam(':quantity', $update_value, PDO::PARAM_INT);
    $update_quantity_query->bindParam(':cart_id', $update_id, PDO::PARAM_INT);
    $update_quantity_query->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Cart</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styless.css" rel="stylesheet" />
</head>
<body>
<div class="container">
  <section class="shopping_cart">
    <h1 class="heading">My Cart</h1>
    <table>
      <?php
      $select_cart_products = $conn->query("SELECT * FROM `cart`");
      if ($select_cart_products->rowCount() > 0) {
        echo "<thead>
          <th>Sl No</th>
          <th>Product Name</th>
          <th>Product Image</th>
          <th>Product Price</th>
          <th>Product Quantity</th>
          <th>Total Price</th>
          <th>Action</th>
        </thead>
        <tbody>";

        $sl_no = 1;
        while ($fetch_cart_products = $select_cart_products->fetch(PDO::FETCH_ASSOC)) {
          ?>
          <tr>
            <td><?php echo $sl_no++; ?></td>
            <td><?php echo htmlspecialchars($fetch_cart_products['name']); ?></td>
            <td>
              <img src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($fetch_cart_products['image']); ?>" alt="" style="width: 100px; height: auto;">
            </td>
            <td><?php echo "₱". htmlspecialchars($fetch_cart_products['price']); ?></td>
            <td>
              <form action="" method="POST">
                <input type="hidden" value="<?php echo htmlspecialchars($fetch_cart_products['cart_id']); ?>" name="update_quantity_id">
                <div class="quantity_box">
                  <input type="number" min="1" value="<?php echo htmlspecialchars($fetch_cart_products['quantity']); ?>" name="update_quantity">
                  <input type="submit" class="update_quantity" value="Update" name="update_product_quantity">
                </div>
              </form>
            </td>
            <td><?php echo "₱". htmlspecialchars($fetch_cart_products['price'] * $fetch_cart_products['quantity']); ?></td>
            <td>
              <a href="delete_cart_item.php?id=<?php echo htmlspecialchars($fetch_cart_products['cart_id']); ?>">
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

    <div class="table_bottom">
      <a href="shop.php" class="bottom_btn">Continue Shopping</a>
      <h3 class="bottom_btn">
        Total: 
        <?php
          $total = $conn->query("SELECT SUM(price * quantity) AS total_price FROM `cart`")->fetch(PDO::FETCH_ASSOC)['total_price'] ?? 0;
          echo "₱" . number_format($total, 2);
        ?>
      </h3>
      <a href="checkout.php" class="bottom_btn">Proceed to checkout</a>
    </div>

    <a href="delete_all_cart_items.php" class="delete_all_btn">
      <i class="fas fa-trash"></i> Delete All
    </a>
  </section>
</div>
</body>
</html>
