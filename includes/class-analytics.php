<?php

class CPP_Analytics {
    public static function init() {
        add_shortcode('cpp_analytics', [__CLASS__, 'render_analytics']);
    }

    public static function render_analytics() {
        ob_start();
        include CPP_PATH . 'templates/analytics-charts.php';
        return ob_get_clean();
    }
}
