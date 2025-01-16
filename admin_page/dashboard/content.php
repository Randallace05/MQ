|<div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="chart-pie pt-4 pb-2">
    <canvas id="myPieChart"></canvas>
</div>
<div id="customLegend" class="mt-4 text-center small">
    <!-- Custom legend will be generated here -->
</div>

                            </div>
                        </div>
                    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Fetch sales data from PHP
    fetch("fetch_sales_data.php")
        .then(response => response.json())
        .then(data => {
            // Handle error if the response contains an error
            if (data.error) {
                console.error(data.error);
                return;
            }

            // Parse the data
            const salesData = data.reduce((acc, curr) => {
                acc[curr.order_date] = parseFloat(curr.total);
                return acc;
            }, {});

            // Define months (January to December)
            const months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            // Populate sales amounts for each month
            const monthlySales = months.map((month, index) => {
                const monthKey = `2025-${String(index + 1).padStart(2, "0")}`;
                return salesData[monthKey] || 0; // Default to 0 if no data
            });

            // Configure Chart.js
            const ctx = document.getElementById("myAreaChart").getContext("2d");
            new Chart(ctx, {
                type: "bar", // Change to 'bar' for a bar graph
                data: {
                    labels: months,
                    datasets: [{
                        label: "Sales (₱)",
                        data: monthlySales,
                        backgroundColor: "rgba(54, 162, 235, 0.7)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Allow custom width/height
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: "Months"
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: "Total Sales (₱)"
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error("Error fetching sales data:", error);
        });
});

document.addEventListener("DOMContentLoaded", function () {
    fetch("fetch_sales_data1.php")
        .then((response) => response.json())
        .then((data) => {
            if (data.error) {
                console.error(data.error);
                return;
            }

            // Extract product names and their counts
            const productNames = Object.keys(data);
            const productCounts = Object.values(data);

            // Define colors for each product
            const productColors = {
                "Chili Garlic Bagoong": "#FF6384",
                "Chicken Binagoongan": "#36A2EB",
                "Plain Alamang": "#FFCE56",
                "Bangus Belly Binagoongan": "#4BC0C0",
                "Salmon Binagoongan": "#9966FF",
            };

            const backgroundColors = productNames.map((name) => productColors[name] || "#FF9F40");

            // Configure Chart.js
            const ctx = document.getElementById("myPieChart").getContext("2d");
            new Chart(ctx, {
                type: "pie",
                data: {
                    labels: productNames,
                    datasets: [
                        {
                            data: productCounts,
                            backgroundColor: backgroundColors,
                            hoverOffset: 4,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false, // Hide default legend
                        },
                    },
                },
            });

            // Create a custom legend (row layout)
            const legendContainer = document.getElementById("customLegend");
            legendContainer.style.display = "flex";
            legendContainer.style.justifyContent = "center"; // Center the legend
            legendContainer.style.flexWrap = "wrap"; // Wrap rows if it overflows
            legendContainer.style.gap = "10px"; // Add spacing between items

            productNames.forEach((name, index) => {
                const color = backgroundColors[index];
                const legendItem = document.createElement("div");
                legendItem.style.display = "flex";
                legendItem.style.alignItems = "center";

                const colorBox = document.createElement("span");
                colorBox.style.width = "15px";
                colorBox.style.height = "15px";
                colorBox.style.backgroundColor = color;
                colorBox.style.display = "inline-block";
                colorBox.style.marginRight = "5px";

                const labelText = document.createElement("span");
                labelText.textContent = name;

                legendItem.appendChild(colorBox);
                legendItem.appendChild(labelText);
                legendContainer.appendChild(legendItem);
            });
        })
        .catch((error) => {
            console.error("Error fetching product data:", error);
        });
});


</script>
