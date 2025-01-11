<?php
// Database connection (replace with your credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_email_verification";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get total users
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM tbl_user";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsers = $totalUsersResult->fetch_assoc()['total_users'];

// Query to get total distributors
$totalDistributorsQuery = "SELECT COUNT(*) AS total_distributors FROM tbl_user WHERE user_role = 'distributor'";
$totalDistributorsResult = $conn->query($totalDistributorsQuery);
$totalDistributors = $totalDistributorsResult->fetch_assoc()['total_distributors'];

$totalDishesQuery = "
    SELECT SUM(
        CAST(
            SUBSTRING_INDEX(
                SUBSTRING_INDEX(cart_items, 'x', 1),
                '(',
                -1
            ) AS UNSIGNED
        )
    ) AS total_dishes
    FROM transaction_history
";

$totalDishesResult = $conn->query($totalDishesQuery);
$totalDishes = $totalDishesResult->fetch_assoc()['total_dishes'] ?? 0;

$totalEarningsQuery = "SELECT SUM(total_amount) AS total_earnings FROM transaction_history";
$totalEarningsResult = $conn->query($totalEarningsQuery);
$totalEarnings = $totalEarningsResult->fetch_assoc()['total_earnings'] ?? 0;


$conn->close();
?>                       
                       <!-- Users Example -->
                       <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Users</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalUsers; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-solid fa-user fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        <!-- Dish Ordered -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Dish Ordered</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalDishes; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-solid fa-jar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Earnings</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱<?php echo number_format($totalEarnings, 2); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-solid fa-peso-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Distirbutor Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Distributor</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalDistributors; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-solid fa-user-tie fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>