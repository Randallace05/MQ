<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Shop Homepage - Start Bootstrap Template</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
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
</style>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#!">
                <img src="../uploads/bgMq.png" alt="MQ Logo" class="img-size">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#!">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="#!">Sign Up</a></li>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-dark" type="submit">
                        <i class="bi-cart-fill me-1"></i>
                        <a href="MQ/cart.php">Add to Cart</a>
                        <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Shop in style</h1>
                <p class="lead fw-normal text-white-50 mb-0">With this shop homepage template</p>
            </div>
        </div>
    </header>

    <hr>

    <!-- Section -->
    <section class="py-5">
        <h2>Most Popular Bagoong</h2>
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                include '../conn/conn.php';
                // Query to fetch products
                $sql = "SELECT id, name, price, image FROM products WHERE is_disabled = 0";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Loop through the products and display them dynamically
                foreach ($products as $product) {
                    ?>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image -->
                            <img class="card-img-top" src="../admin_page/foodMenu/uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
                            <!-- Product details -->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name -->
                                    <h5 class="fw-bolder"><?php echo $product['name']; ?></h5>
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
                ?>
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
