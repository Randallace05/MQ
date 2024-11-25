<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>MQ Kitchen</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="../user_page/assets/sili.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<style>
    .img-size{
        width: 50px;
        height: 50px;
    }
    .chili-rating{
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 15px;
    }

    .chili {
        cursor: pointer;
        margin: 0 5px;
        transition: transform 0.3s ease;
    }

    .chili.selected, .chili:hover{
        transform: scale(1.2);
    }

    #rating-display{
        margin-top: 20px;
        text-align: center;
        font-size: 1.5em;
    }

    /* picture sizing */
    .head{
        background-color: white !important;
        width: 90%;
        margin: auto;
        margin-top: -80px;
    }

    .right-box, .left-box{
        width: 89% !important;
        height: 250px !important;
        background-color: red;
        margin-top: -30px;
    }
    
    .grid-container {
        display: grid;
        grid-template-columns: 1fr 1fr; /* Two equal columns */
        justify-items: center; /* Center items horizontally */
        margin-top: 10px;
    }

    .top-picture{
        height: 300px ;
        background-color: red;
        width: 100%;
    }

</style>
<body>
    <!-- Navigation -->
        <?php include("../includes/topbar1.php"); ?> 
    <!-- end Navigation -->
    
    <!-- Header -->
    <header class="py-5 head">
        <div class="container px-4 px-lg-5 my-5 top-picture">
            top picture
        </div>
        <div class="grid-container">
            <div class="container left-box">left box pic</div>
            <div class="container right-box">right box pic</div>
        </div>
    </header>

            <!-- <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">MQ Kitchen</h1>
                <p class="lead fw-normal text-white-50 mb-0">The Best Spicy Condiments!</p>
            </div> -->

    <!-- Section -->
    <section class="py-5">
    <h2>Most Popular Bagoong</h2>
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php
            include '../conn/conn.php';

            // Query to fetch products
            $sql = "SELECT id, name, price, image FROM products WHERE is_disabled = 0";
            $result = $conn->query($sql);

            // Check if query executed successfully
            if ($result && $result->num_rows > 0) {
                // Loop through the products and display them dynamically
                while ($product = $result->fetch_assoc()) {
                    ?>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image -->
                            <img class="card-img-top" src="../admin_page/foodMenu/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                            <!-- Product details -->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name -->
                                    <h5 class="fw-bolder"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <!-- Product price -->
                                    <p>&#8369;<?php echo number_format($product['price'], 2); ?></p> <!-- Displaying the price with Peso sign -->
                                </div>
                                <div class="chili-rating" id="chili-rating">
                                    <span class="chili" data-value="1">🌶️</span>
                                    <span class="chili" data-value="2">🌶️</span>
                                    <span class="chili" data-value="3">🌶️</span>
                                    <span class="chili" data-value="4">🌶️</span>
                                    <span class="chili" data-value="5">🌶️</span>
                                </div>
                            </div>
                            <!-- Product actions -->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center">
                                    <a class="btn btn-outline-dark mt-auto" href="items.php?id=<?php echo $product['id']; ?>">View options</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Display a message if no products are found
                echo "<p>No products found.</p>";
            }
            ?>
        </div>
    </div>
</section>

            </div>
        </div>
    </section>
    

    <!-- Footer -->
    <?php include("../includes/footer.php"); ?>

    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS -->
    <script src="js/scripts.js"></script>
    <script>
    const chilies = document.querySelectorAll('.chili');
    const ratingDisplay = document.getElementById('rating-display');
    let selectedRating = 0;

    chilies.forEach(chili => {
        chili.addEventListener('mouseover', () => {
            resetChilies();
            highlightChilies(chili.dataset.value);
        });

        chili.addEventListener('click', () => {
            selectedRating = chili.dataset.value;
            highlightChilies(selectedRating);
            ratingDisplay.textContent = `You selected ${selectedRating} chili(s)`;
        });

        chili.addEventListener('mouseout', () => {
            if (selectedRating == 0) {
                resetChilies();
            } else {
                highlightChilies(selectedRating);
            }
        });
    });

    function highlightChilies(rating) {
        for (let i = 0; i < rating; i++) {
            chilies[i].classList.add('selected');
        }
    }

    function resetChilies() {
        chilies.forEach(chili => {
            chili.classList.remove('selected');
        });
    }
</script>
</body>
</html>
