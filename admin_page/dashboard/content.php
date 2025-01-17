
<div class="row">
    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                <div class="mt-4">
                    <button id="monthlyBtn" class="btn btn-primary mr-2">Monthly</button>
                    <button id="yearlyBtn" class="btn btn-primary mr-2">Yearly</button>
                    <button id="dailyBtn" class="btn btn-primary">Daily</button>
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
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
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
            <div class="mt-4">
                <br>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let salesData = [];
    let chart;

    // Fetch sales data from PHP
    fetch("fetch_sales_data.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }
            salesData = data;
            updateChart('monthly'); // Default view
        })
        .catch(error => {
            console.error("Error fetching sales data:", error);
        });

    function updateChart(period) {
        const ctx = document.getElementById("myAreaChart").getContext("2d");
        
        let labels, values;
        switch(period) {
            case 'yearly':
                labels = [...new Set(salesData.map(item => item.order_date.substring(0, 4)))];
                values = labels.map(year => 
                    salesData.filter(item => item.order_date.startsWith(year))
                        .reduce((sum, item) => sum + parseFloat(item.total), 0)
                );
                break;
            case 'monthly':
                labels = salesData.map(item => item.order_date);
                values = salesData.map(item => parseFloat(item.total));
                break;
            case 'daily':
                // Assuming you have daily data available
                labels = salesData.map(item => item.order_date);
                values = salesData.map(item => parseFloat(item.total));
                break;
        }

        if (chart) {
            chart.destroy();
        }

        chart = new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: "Sales (₱)",
                    data: values,
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: period.charAt(0).toUpperCase() + period.slice(1)
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
    }

    // Event listeners for filter buttons
    document.getElementById('yearlyBtn').addEventListener('click', () => updateChart('yearly'));
    document.getElementById('monthlyBtn').addEventListener('click', () => updateChart('monthly'));
    document.getElementById('dailyBtn').addEventListener('click', () => updateChart('daily'));

    // Generate Excel report
    function generateExcelReport(period) {
        let data = [];
        switch(period) {
            case 'yearly':
                data = [...new Set(salesData.map(item => item.order_date.substring(0, 4)))].map(year => ({
                    Year: year,
                    Total: salesData.filter(item => item.order_date.startsWith(year))
                        .reduce((sum, item) => sum + parseFloat(item.total), 0)
                }));
                break;
            case 'monthly':
                data = salesData.map(item => ({
                    Month: item.order_date,
                    Total: parseFloat(item.total)
                }));
                break;
            case 'daily':
                // Assuming you have daily data
                data = salesData.map(item => ({
                    Date: item.order_date,
                    Total: parseFloat(item.total)
                }));
                break;
        }

        const worksheet = XLSX.utils.json_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Sales Report");
        XLSX.writeFile(workbook, `Sales_Report_${period}.xlsx`);
    }

    // Event listeners for report generation
    document.getElementById('generateYearlyReport').addEventListener('click', () => generateExcelReport('yearly'));
    document.getElementById('generateMonthlyReport').addEventListener('click', () => generateExcelReport('monthly'));
    document.getElementById('generateDailyReport').addEventListener('click', () => generateExcelReport('daily'));
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

