<div class="wrap">
    <h1>Article Analytics</h1>

    <!-- Analytics Cards -->
    <div class="cpp-cards">
        <div class="cpp-card">
            <h3>Total Articles Reviewed</h3>
            <p><?php echo $total_published + $total_rejected; ?></p>
        </div>
        <div class="cpp-card">
            <h3>Published Articles</h3>
            <p><?php echo $total_published; ?></p>
        </div>
        <div class="cpp-card">
            <h3>Rejected Articles</h3>
            <p><?php echo $total_rejected; ?></p>
        </div>
    </div>

    <!-- Article Analytics Chart -->
    <div class="cpp-chart">
        <canvas id="articleAnalyticsChart"></canvas>
    </div>
</div>

<script>
    // Chart.js code for Article Analytics
    const ctxAnalytics = document.getElementById('articleAnalyticsChart').getContext('2d');
    const articleAnalyticsChart = new Chart(ctxAnalytics, {
        type: 'pie',
        data: {
            labels: ['Published', 'Rejected'],
            datasets: [{
                label: 'Articles Analytics',
                data: [<?php echo $total_published; ?>, <?php echo $total_rejected; ?>],
                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
</script>

<style>
    .cpp-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .cpp-card {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .cpp-card h3 {
        margin-bottom: 10px;
    }

    .cpp-chart {
        margin-top: 30px;
    }
</style>
