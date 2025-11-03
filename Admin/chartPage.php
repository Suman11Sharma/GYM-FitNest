<?php
include "../database/user_authentication.php";
include "../database/db_connect.php";

$gym_id = $_SESSION['gym_id'] ?? 0;
$currentYear = date('Y');

// ------------------- Gender Counts -------------------
$maleCount = 0;
$femaleCount = 0;
$otherCount = 0;

$genderQuery = "SELECT gender, COUNT(*) AS total  
                FROM customers 
                WHERE gym_id = $gym_id 
                GROUP BY gender";
$genderResult = mysqli_query($conn, $genderQuery);
if ($genderResult) {
    while ($row = mysqli_fetch_assoc($genderResult)) {
        $gender = strtolower($row['gender']);
        $count = (int)$row['total'];
        if ($gender === 'male') $maleCount = $count;
        elseif ($gender === 'female') $femaleCount = $count;
        else $otherCount = $count;
    }
}

// ------------------- Revenue by Package -------------------
$packageLabels = [];
$packageData = [];

$planQuery = "SELECT plan_id, plan_name FROM customer_plans WHERE gym_id = $gym_id AND status='active'";
$planResult = mysqli_query($conn, $planQuery);
while ($plan = mysqli_fetch_assoc($planResult)) {
    $plan_id = $plan['plan_id'];
    $plan_name = $plan['plan_name'];

    $countQuery = "SELECT COUNT(*) AS total FROM customer_subscriptions 
                   WHERE gym_id = $gym_id AND plan_id = $plan_id";
    $countResult = mysqli_query($conn, $countQuery);
    $countRow = mysqli_fetch_assoc($countResult);
    $total = $countRow['total'] ?? 0;

    $packageLabels[] = $plan_name;
    $packageData[] = (int)$total;
}

// ------------------- New Customers by Month -------------------
$newCustomerData = array_fill(0, 12, 0);

$customerQuery = "SELECT MONTH(join_date) AS month, COUNT(*) AS total 
                  FROM customers WHERE gym_id = $gym_id AND YEAR(join_date) = $currentYear
                  GROUP BY month";
$customerResult = mysqli_query($conn, $customerQuery);
if ($customerResult) {
    while ($row = mysqli_fetch_assoc($customerResult)) {
        $newCustomerData[(int)$row['month'] - 1] = (int)$row['total'];
    }
}
?>
<section class="container mt-4 analytics-overview">
    <h4 class="fw-bold mb-3"><i class="fas fa-chart-pie me-2"></i>Analytics & Overview</h4>
    <div class="row g-4">

        <!-- Gender Chart -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0 fw-semibold text-dark">
                    <i class="fas fa-venus-mars me-2 text-primary"></i>Customer Gender
                </div>
                <div class="card-body text-center">
                    <canvas id="genderChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Revenue by Package Type -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0 fw-semibold text-dark">
                    <i class="fas fa-boxes me-2 text-success"></i>Revenue by Package Type
                </div>
                <div class="card-body text-center">
                    <canvas id="packageChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- New Customers by Month -->
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-white border-0 fw-semibold text-dark">
                    <i class="fas fa-users me-2 text-warning"></i>New Customers by Month
                </div>
                <div class="card-body text-center">
                    <canvas id="newCustomersChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Overview -->
    <div class="row justify-content-center mt-4">
        <div class="col-lg-10 col-md-11 col-sm-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Revenue Overview</h5>
                    <select id="yearSelect" class="form-select form-select-sm w-auto bg-light text-dark">
                        <option value="<?= $currentYear ?>" selected><?= $currentYear ?></option>
                        <option value="<?= $currentYear - 1 ?>"><?= $currentYear - 1 ?></option>
                        <option value="<?= $currentYear - 2 ?>"><?= $currentYear - 2 ?></option>
                    </select>
                </div>
                <div class="card-body px-3 py-4">
                    <canvas id="monthlyRevenueChart" height="130"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Gender Chart
        new Chart(document.getElementById("genderChart"), {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female', 'Others'],
                datasets: [{
                    data: [<?= $maleCount ?>, <?= $femaleCount ?>, <?= $otherCount ?>],
                    backgroundColor: ['#007bff', '#e83e8c', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                cutout: "70%",
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Revenue by Package Type
        new Chart(document.getElementById("packageChart"), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($packageLabels) ?>,
                datasets: [{
                    data: <?= json_encode($packageData) ?>,
                    backgroundColor: ['#17a2b8', '#ffc107', '#28a745', '#6f42c1', '#fd7e14']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // New Customers by Month
        new Chart(document.getElementById("newCustomersChart"), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'New Customers',
                    data: <?= json_encode($newCustomerData) ?>,
                    backgroundColor: '#fd7e14'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // ---------------- Monthly Revenue Chart ----------------
        const ctx = document.getElementById("monthlyRevenueChart").getContext('2d');
        let monthlyRevenueChart;

        function loadMonthlyRevenue(year) {
            axios.get('get_monthly_revenue.php', {
                    params: {
                        year
                    }
                })
                .then(res => {
                    const data = res.data;
                    if (monthlyRevenueChart) {
                        monthlyRevenueChart.data.datasets[0].data = data;
                        monthlyRevenueChart.update();
                    } else {
                        monthlyRevenueChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                datasets: [{
                                    label: 'Monthly Revenue (NPR)',
                                    data: data,
                                    borderColor: '#007bff',
                                    backgroundColor: 'rgba(0,123,255,0.15)',
                                    borderWidth: 3,
                                    tension: 0.3,
                                    fill: true,
                                    pointBackgroundColor: '#007bff'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: v => 'NPR ' + v
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
        }

        // Initial load
        const yearSelect = document.getElementById('yearSelect');
        loadMonthlyRevenue(yearSelect.value);

        yearSelect.addEventListener('change', () => {
            loadMonthlyRevenue(yearSelect.value);
        });
    });
</script>