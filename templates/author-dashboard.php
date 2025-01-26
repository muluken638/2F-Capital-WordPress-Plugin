
    

    <div class="wrap">
        <h1>Author Dashboard</h1>

        <!-- Cards for Article Statistics -->
        <div class="container">
            <div class="kpi-card orange">
                <span class="card-value"><?php echo esc_html($total_articles); ?> </span>
                <span class="card-text">Total Articles</span>
                <i class="fas fa-file-alt icon"></i>
            </div>
            <div class="kpi-card purple">
                <span class="card-value"><?php echo esc_html($pending_articles_total); ?></span>
                <span class="card-text">Total Pending</span>
                <i class="fas fa-hourglass-half icon"></i>
            </div>

            <div class="kpi-card grey-dark">
                <span class="card-value"><?php echo esc_html($draft_articles_total); ?></span>
                <span class="card-text">Total Draft</span>
                <i class="fas fa-pencil-alt icon"></i>
            </div>

            <div class="kpi-card red">
                <span class="card-value"><?php echo esc_html($published_articles_total); ?></span>
                <span class="card-text">Total Published</span>
                <i class="fas fa-check icon"></i>
            </div>
        </div>
    </div>

    <!-- Bar Chart -->
    <div class="chart-container">
        <canvas id="articleChart"></canvas>
    </div>
    <!-- Pie Chart -->
    <div class="chart-container">
        <canvas id="articlePieChart"></canvas>
    </div>
    <link rel="stylesheet" href="assets/css/style.css"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // PHP variables to JavaScript
            var totalArticles = <?php echo isset($total_articles) ? esc_js($total_articles) : 0; ?>;
            var pendingArticles = <?php echo isset($pending_articles_total) ? esc_js($pending_articles_total) : 0; ?>;
            var draftArticles = <?php echo isset($draft_articles_total) ? esc_js($draft_articles_total) : 0; ?>;
            var publishedArticles = <?php echo isset($published_articles_total) ? esc_js($published_articles_total) : 0; ?>;

            // Bar Chart Configuration
            var ctx = document.getElementById('articleChart').getContext('2d');
            var articleChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total Articles', 'Pending', 'Draft', 'Published'],
                    datasets: [{
                        label: 'Articles Count',
                        data: [totalArticles, pendingArticles, draftArticles, publishedArticles],
                        backgroundColor: ['#FF8C00', '#8A2BE2', '#A9A9A9', '#5BDB5B'],
                        borderColor: [ '##5BDB5B'],
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
        });
        // Pie Chart (for percentage distribution)
        // Pie Chart Configuration
        var pieCtx = document.getElementById('articlePieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['Pending', 'Published'],
                    datasets: [{
                        label: 'Articles Distribution',
                        data: [pendingArticles, publishedArticles],
                        backgroundColor: ['#FF8C00', '#5BDB5B'],
                        borderColor: ['#FFFFFF'],
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
                        legend: { position: 'top' }
                    }
                }
            });
         </script>

    <style>
       

        .wrap {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .kpi-card {
            flex: 1;
            margin: 10px;
            padding: 20px;
            border-radius: 8px;
            color: #fff;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .kpi-card.orange {
            background-color: #FF8C00;
        }

        .kpi-card.purple {
            background-color: #8A2BE2;
        }

        .kpi-card.grey-dark {
            background-color: #A9A9A9;
        }

        .kpi-card.red {
            background-color:rgb(71, 255, 126);
        }

        .kpi-card .card-value {
            font-size: 24px;
            font-weight: bold;
        }

        .kpi-card .card-text {
            font-size: 16px;
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }
    </style>

    

    <div class="wrap">
        <h1>Author Dashboard</h1>

        <!-- Cards for Article Statistics -->
        <div class="container">
            <div class="kpi-card orange">
                <span class="card-value"><?php echo esc_html($total_articles); ?> </span>
                <span class="card-text">Total Articles</span>
                <i class="fas fa-file-alt icon"></i>
            </div>
            <div class="kpi-card purple">
                <span class="card-value"><?php echo esc_html($pending_articles_total); ?></span>
                <span class="card-text">Total Pending</span>
                <i class="fas fa-hourglass-half icon"></i>
            </div>

            <div class="kpi-card grey-dark">
                <span class="card-value"><?php echo esc_html($draft_articles_total); ?></span>
                <span class="card-text">Total Draft</span>
                <i class="fas fa-pencil-alt icon"></i>
            </div>

            <div class="kpi-card red">
                <span class="card-value"><?php echo esc_html($published_articles_total); ?></span>
                <span class="card-text">Total Published</span>
                <i class="fas fa-check icon"></i>
            </div>
        </div>
    </div>

    <!-- Bar Chart -->
    <div class="chart-container">
        <canvas id="articleChart"></canvas>
    </div>
    <!-- Pie Chart -->
    <div class="chart-container">
        <canvas id="articlePieChart"></canvas>
    </div>
    <link rel="stylesheet" href="assets/css/style.css"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // PHP variables to JavaScript
            var totalArticles = <?php echo isset($total_articles) ? esc_js($total_articles) : 0; ?>;
            var pendingArticles = <?php echo isset($pending_articles_total) ? esc_js($pending_articles_total) : 0; ?>;
            var draftArticles = <?php echo isset($draft_articles_total) ? esc_js($draft_articles_total) : 0; ?>;
            var publishedArticles = <?php echo isset($published_articles_total) ? esc_js($published_articles_total) : 0; ?>;

            // Bar Chart Configuration
            var ctx = document.getElementById('articleChart').getContext('2d');
            var articleChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total Articles', 'Pending', 'Draft', 'Published'],
                    datasets: [{
                        label: 'Articles Count',
                        data: [totalArticles, pendingArticles, draftArticles, publishedArticles],
                        backgroundColor: ['#FF8C00', '#8A2BE2', '#A9A9A9', '#5BDB5B'],
                        borderColor: [ '##5BDB5B'],
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
        });
        // Pie Chart (for percentage distribution)
        // Pie Chart Configuration
        var pieCtx = document.getElementById('articlePieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['Pending', 'Published'],
                    datasets: [{
                        label: 'Articles Distribution',
                        data: [pendingArticles, publishedArticles],
                        backgroundColor: ['#FF8C00', '#5BDB5B'],
                        borderColor: ['#FFFFFF'],
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
                        legend: { position: 'top' }
                    }
                }
            });
         </script>

    <style>
       

        .wrap {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .kpi-card {
            flex: 1;
            margin: 10px;
            padding: 20px;
            border-radius: 8px;
            color: #fff;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .kpi-card.orange {
            background-color: #FF8C00;
        }

        .kpi-card.purple {
            background-color: #8A2BE2;
        }

        .kpi-card.grey-dark {
            background-color: #A9A9A9;
        }

        .kpi-card.red {
            background-color:rgb(71, 255, 126);
        }

        .kpi-card .card-value {
            font-size: 24px;
            font-weight: bold;
        }

        .kpi-card .card-text {
            font-size: 16px;
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }
    </style>
