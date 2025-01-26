<div class="wrap">
    <h1>Editor Dashboard</h1>

    <!-- Cards for Article Statistics -->
    <div class="container">
        <div class="kpi-card orange">
            <span class="card-value"><?php echo esc_html($total_articles); ?> </span>
            <span class="card-text">Total Articles</span>
            <i class="fas fa-ar icon"></i>
        </div>
        <div class="kpi-card purple">
            <span class="card-value"><?php echo esc_html($pending_articles_total); ?></span>
            <span class="card-text">Total Pending</span>
            <i class="fas fa-hourglass-half icon"></i>
        </div>

        <div class="kpi-card grey-dark">
            <span class="card-value"><?php echo esc_html($published_articles); ?></span>
            <span class="card-text">Total Published</span>
            <i class="fas fa-shopping-cart icon"></i>
        </div>
        <div class="kpi-card grey-dark">
            <span class="card-text">Rejected Articles</span>
            <i class="fas fa-shopping-cart icon"></i>
        </div>
    </div>
<div class="charts">
    <!-- Bar Chart -->
    <div class="chart-container">
        <canvas id="articleChart"></canvas>
    </div>

    <!-- Pie Chart -->
    <div class="chart-container pie">
        <canvas id="articlePieChart"></canvas>
    </div>
</div>
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ensure PHP variables are defined
            var totalArticles = <?php echo isset($total_articles) ? esc_js($total_articles) : 0; ?>;
            var pendingArticles = <?php echo isset($pending_articles_total) ? esc_js($pending_articles_total) : 0; ?>;
            var publishedArticles = <?php echo isset($published_articles) ? esc_js($published_articles) : 0; ?>;

            // Debugging: Log data
            console.log({
                totalArticles,
                pendingArticles,
                publishedArticles
            });

            // Bar Chart (for total articles comparison)
            var ctx = document.getElementById('articleChart').getContext('2d');
            var articleChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total Articles', 'Pending', 'Published'],
                    datasets: [{
                        label: 'Articles Count',
                        data: [totalArticles, pendingArticles, publishedArticles],
                        backgroundColor: ['#FF8C00', '#8A2BE2', '#A9A9A9', '#FF6347'],
                        borderColor: ['#FF8C00', '#8A2BE2', '#A9A9A9', '#FF6347'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Article Statistics'
                        },
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });

            // Pie Chart (for percentage distribution)
            var pieCtx = document.getElementById('articlePieChart').getContext('2d');
            var articlePieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['Pending', 'Published'],
                    datasets: [{
                        label: 'Articles Distribution',
                        data: [pendingArticles, publishedArticles],
                        backgroundColor: ['#FF8C00', '#A9A9A9', '#FF6347'],
                        borderColor: ['#FF8C00', '#A9A9A9', '#FF6347'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Article Status Distribution'
                        },
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        });
    </script>

    <style>
        .chrts{
            display:flex;
            flex:column;
            justify-content:center;
        }
        .chart-container {
            position: relative;
            height: 400px; /* Adjust height */
            width: 75%;
            display: flex;
            
        }
        .pie{
width:20%;
height: 400px;
        }
        canvas {
            background-color: #fff; /* Debug visibility */
        }

        .kpi-card {
            display: inline-block;
            padding: 20px;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .kpi-card.orange {
            background-color: #FF8C00;
            color: white;
        }

        .kpi-card.purple {
            background-color: #8A2BE2;
            color: white;
        }

        .kpi-card.grey-dark {
            background-color: #A9A9A9;
            color: white;
        }

        .kpi-card .card-value {
            font-size: 24px;
            font-weight: bold;
        }

        .kpi-card .card-text {
            font-size: 16px;
        }
    </style>
    
</div>
