<?php
require_once '../endpoint/session_config.php';
?>

<!-- start include header -->
<?php include("../includes/header.php"); ?>
<!-- end include header -->

    <!-- Top Bar Section -->
        <?php include("../includes/topbar1.php"); ?>
    <!-- End Top Bar Section -->

    <!-- Main Container -->
    <div class="container">
        <div class="flex-container">
            <!-- Image Section -->
            <?php include("cartZ.php"); ?>
            <!-- Form Section -->
            <div class="main">
<script>
    document.querySelectorAll('.quantity_box select').forEach(select => {
        select.addEventListener('change', function () {
            const cartId = this.closest('form').querySelector('[name="update_quantity_id"]').value;
            const quantity = this.value;

            fetch('update_cart_quantity.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ cart_id: cartId, quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

 
</script>



