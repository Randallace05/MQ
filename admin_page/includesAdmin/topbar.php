<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['unique_id'])) {
    echo "Session 'unique_id' is not set.";
    exit;
}

// Include database connection
include '../../conn/conn.php';


// Query to fetch user details
$sql = "SELECT tbl_user_id, unique_id, first_name, last_name FROM tbl_user WHERE unique_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing the query: " . $conn->error);
}

$stmt->bind_param("s", $_SESSION['unique_id']);

if (!$stmt->execute()) {
    die("Error executing the query: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // echo "Unique ID: " . htmlspecialchars($row['tbl_user_id']) . "<br>";
    // echo "Name: " . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "<br>";
} else {
    echo "No records found for the logged-in user.";
}

$stmt->close();
$conn->close();

?>

<style>
    .btn-css{
        color:#fff;
        background-color:#D24444;
        border-color:#D24444
        }
    .btn-css:hover{
        color:#fff;
        background-color:#f44336;
        border-color:#f44336
        }
    .btn-css.focus,.btn-primary:focus{
        color:#fff;
        background-color:#f44336;
        border-color:#f44336;
        box-shadow:0 0 0 .2rem rgba(105,136,228,.5)
        }
    .btn-css.disabled,.btn-primary:disabled{
        color:#fff;
        background-color:#D24444;
        border-color:#D24444
        }
</style>
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

<!-- Sidebar Toggle (Topbar) -->
<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
</button>



<!-- Topbar Navbar -->
<ul class="navbar-nav ml-auto">

    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
    <li class="nav-item dropdown no-arrow d-sm-none">
        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-search fa-fw"></i>
        </a>
        <!-- Dropdown - Messages -->
        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
            aria-labelledby="searchDropdown">
            <form class="form-inline mr-auto w-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small"
                        placeholder="Search for..." aria-label="Search"
                        aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </li>

    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Counter - Alerts -->
        <span class="badge badge-danger badge-counter" id="alertCount">0</span>
    </a>
    <!-- Dropdown - Alerts -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
        aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
            Alerts Center
        </h6>
        <div id="alertList">
            <!-- Alerts will be dynamically added here -->
        </div>
        <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
    </div>
</li>

    <!-- Nav Item - Messages -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-envelope fa-fw"></i>
            <!-- Counter - Messages -->
            <span class="badge badge-danger badge-counter">7</span>
        </a>
        <!-- Dropdown - Messages -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="messagesDropdown">
            <h6 class="dropdown-header">
                Message Center
            </h6>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="img/undraw_profile_1.svg"
                        alt="...">
                    <div class="status-indicator bg-success"></div>
                </div>
                <div class="font-weight-bold">
                    <div class="text-truncate">Hi there! I am wondering if you can help me with a
                        problem I've been having.</div>
                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                </div>
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="img/undraw_profile_2.svg"
                        alt="...">
                    <div class="status-indicator"></div>
                </div>
                <div>
                    <div class="text-truncate">I have the photos that you ordered last month, how
                        would you like them sent to you?</div>
                    <div class="small text-gray-500">Jae Chun · 1d</div>
                </div>
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="img/undraw_profile_3.svg"
                        alt="...">
                    <div class="status-indicator bg-warning"></div>
                </div>
                <div>
                    <div class="text-truncate">Last month's report looks great, I am very happy with
                        the progress so far, keep up the good work!</div>
                    <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                </div>
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                        alt="...">
                    <div class="status-indicator bg-success"></div>
                </div>
                <div>
                    <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                        told me that people say this to all dogs, even if they aren't good...</div>
                    <div class="small text-gray-500">Chicken the Dog · 2w</div>
                </div>
            </a>
            <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
        </div>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                <?php
                if (isset($row['first_name']) && isset($row['last_name'])) {
                    echo htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']);
                } else {
                    echo "User";
                }
                ?>
            </span>
            <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
        </a>

        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="userDropdown">
            <a class="dropdown-item" href="../profile_page/profile_page.php">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                Profile
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="../MQ/index.php" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
            </a>
        </div>
    </li>

</ul>


<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="../chat/php/logout.php?logout_id=<?php echo isset($row['unique_id']) ? $row['unique_id'] : $row['tbl_user_id']; ?>">Logout</a>
            </div>
        </div>
    </div>
</div>

</nav>

<script>
    // Fetch stock alerts from the server
    async function fetchStockAlerts() {
        const response = await fetch('../includesAdmin/fetch_stock_alerts.php'); // Replace with the correct PHP script path
        const stockData = await response.json();
        updateStockAlerts(stockData);
    }

    // Function to populate alerts
    function updateStockAlerts(stockData) {
        const alertList = document.getElementById("alertList");
        const alertCount = document.getElementById("alertCount");
        let lowStockCount = 0;

        // Clear existing alerts
        alertList.innerHTML = "";

        stockData.forEach((item) => {
            if (item.stock <= 5) {
                lowStockCount++;
                const alertItem = document.createElement("a");
                alertItem.className = "dropdown-item d-flex align-items-center";
                alertItem.href = "#";
                alertItem.innerHTML = `
                    <div class="mr-3">
                        <div class="icon-circle ${item.stock === 0 ? "bg-danger" : "bg-warning"}">
                            <i class="fas ${item.stock === 0 ? "fa-times-circle" : "fa-exclamation-triangle"} text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">${new Date().toLocaleDateString()}</div>
                        <span>${item.name} is ${item.stock === 0 ? "out of stock" : "low in stock "}</span>
                    </div>
                `;
                alertList.appendChild(alertItem);
            }
        });

        // Update alert count
        alertCount.textContent = lowStockCount > 0 ? lowStockCount : "";
    }

    // Fetch alerts on page load
    document.addEventListener("DOMContentLoaded", fetchStockAlerts);
</script>

<!-- End of Topbar -->
