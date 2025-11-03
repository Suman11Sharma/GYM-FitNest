<?php include("../database/db_connect.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gym Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <section class="container mt-4 analytics-overview">
        <h4 class="fw-bold mb-3"><i class="fas fa-chart-pie me-2"></i>Analytics & Overview</h4>

        <div class="row g-4">
            <!-- Income by Source -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white border-0 fw-semibold text-dark">
                        <i class="fas fa-hand-holding-usd me-2 text-primary"></i>Income by Source (Current Month)
                    </div>
                    <div class="card-body text-center">
                        <canvas id="incomeChart" height="150"></canvas>
                    </div>
                </div>
            </div>

            <!-- Revenue by Package Type -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-white border-0 fw-semibold text-dark">
                        <i class="fas fa-boxes me-2 text-success"></i>Revenue by Package Type
                    </div>
                    <div class="card-body text-center">
                        <canvas id="packageChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <!-- Monthly Revenue -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-10 col-md-11 col-sm-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Revenue Overview</h5>
                        <select id="yearSelect" class="form-select form-select-sm w-auto bg-light text-dark">
                            <option value="2025" selected>2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <div class="card-body px-3 py-4">
                        <canvas id="monthlyRevenueChart" height="130"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            loadAnalyticsData();

            document.getElementById('yearSelect').addEventListener('change', () => {
                loadAnalyticsData();
            });
        });

        function loadAnalyticsData() {
            const year = document.getElementById("yearSelect").value;

            fetch("get_analytics_data.php?year=" + year)
                .then(res => res.json())
                .then(data => {
                    renderCharts(data);
                })
                .catch(err => console.error(err));
        }

        function renderCharts(data) {
            // --- Pie Chart: Income by Source ---
            new Chart(document.getElementById("incomeChart"), {
                type: 'pie',
                data: {
                    labels: ['Customer Subscriptions', 'Gym Subscriptions', 'Paid Ads', 'Trainer Bookings', 'Visitor Passes'],
                    datasets: [{
                        data: [
                            data.income.customer_subscriptions,
                            data.income.gym_subscriptions,
                            data.income.paid_ads,
                            data.income.trainer_bookings,
                            data.income.visitor_passes
                        ],
                        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#fd7e14', '#6f42c1']
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // --- Doughnut: Revenue by Plan Type ---
            new Chart(document.getElementById("packageChart"), {
                type: 'doughnut',
                data: {
                    labels: data.plan_labels,
                    datasets: [{
                        data: data.plan_counts,
                        backgroundColor: ['#17a2b8', '#ffc107', '#28a745', '#dc3545', '#6f42c1']
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // --- Line Chart: Monthly Revenue ---
            new Chart(document.getElementById("monthlyRevenueChart"), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: `Monthly Revenue (NPR)`,
                        data: data.monthly_revenue,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0,123,255,0.15)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3
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
        }
    </script>
</body>

</html>