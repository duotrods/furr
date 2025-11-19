<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php requireAdmin(); ?>

<?php
$appointmentCounts = getAppointmentCounts();
$recentAppointments = getAllAppointments('confirmed');

// Initialize $products properly
$category_id = $_GET['category_id'] ?? null;
$products = $category_id ? getProductsByCategory($category_id) : getAllProducts();

// Get filter parameters
$appointmentFilter = $_GET['appointment_filter'] ?? 'week';
$salesFilter = $_GET['sales_filter'] ?? 'week';
$inventoryFilter = $_GET['inventory_filter'] ?? 'all';
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="container mx-auto px-6">
        <div class="container mx-auto px-6 py-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-sm text-gray-600 mt-1">Welcome back! Here's what's happening today.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900"><?php echo date('l, F j, Y'); ?></p>
                        <p class="text-xs text-gray-500"><?php echo date('g:i A'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 ">
            <div
                class="bg-white rounded-xl shadow-md  border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Today's Appointments</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $appointmentCounts['today']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center">
                        <span class="text-xs text-green-600 font-medium">Today</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">This Week</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $appointmentCounts['week']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center">
                        <span class="text-xs text-gray-500 font-medium">7 days</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">This Month</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $appointmentCounts['month']; ?></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center">
                        <span class="text-xs text-gray-500 font-medium">30 days</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Pending</p>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $appointmentCounts['pending'] ?? 0; ?>
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center">
                        <span class="text-xs text-amber-600 font-medium">Awaiting approval</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- charts for appointments and Product -->
    <div class="container mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- chart #1 -->
        <div class="w-full bg-white rounded-xl shadow-md border border-gray-100 p-4 md:p-6">
            <?php
            // Query for completed appointments with filter
            $appointmentWhereClause = "WHERE a.status = 'completed'";

            if ($appointmentFilter === 'week') {
                $start_date = date('Y-m-d', strtotime('-6 days'));
                $appointmentWhereClause = "WHERE a.status = 'completed' AND a.appointment_date >= '$start_date'";
            } elseif ($appointmentFilter === 'month') {
                $start_date = date('Y-m-d', strtotime('-30 days'));
                $appointmentWhereClause = "WHERE a.status = 'completed' AND a.appointment_date >= '$start_date'";
            } elseif ($appointmentFilter === 'year') {
                $start_date = date('Y-m-d', strtotime('-365 days'));
                $appointmentWhereClause = "WHERE a.status = 'completed' AND a.appointment_date >= '$start_date'";
            }

            $stmt = $pdo->prepare("SELECT a.*, s.name as service_name, s.price as service_price, 
                          s.id as service_id, u.first_name, u.last_name, u.email, u.phone 
                   FROM appointments a 
                   JOIN services s ON a.service_id = s.id 
                   JOIN users u ON a.user_id = u.id 
                   $appointmentWhereClause 
                   ORDER BY a.appointment_date DESC, a.appointment_time DESC");
            $stmt->execute();
            $appointments = $stmt->fetchAll();

            // Process data for charts
            $daily_revenue = [];
            $service_revenue = []; // Track revenue by service
            $total_revenue = 0;
            $appointment_count = 0;

            // Group appointments by date and service, calculate revenue
            foreach ($appointments as $appointment) {
                $date = $appointment['appointment_date'];
                $service_id = $appointment['service_id'];
                $service_name = $appointment['service_name'];
                $price = floatval($appointment['service_price']);

                // Initialize date if not exists
                if (!isset($daily_revenue[$date])) {
                    $daily_revenue[$date] = [
                        'total' => 0,
                        'services' => []
                    ];
                }

                // Initialize service if not exists
                if (!isset($daily_revenue[$date]['services'][$service_id])) {
                    $daily_revenue[$date]['services'][$service_id] = [
                        'name' => $service_name,
                        'revenue' => 0,
                        'count' => 0
                    ];
                }

                // Track service revenue globally
                if (!isset($service_revenue[$service_id])) {
                    $service_revenue[$service_id] = [
                        'name' => $service_name,
                        'revenue' => 0,
                        'count' => 0
                    ];
                }

                // Update all tracking
                $daily_revenue[$date]['total'] += $price;
                $daily_revenue[$date]['services'][$service_id]['revenue'] += $price;
                $daily_revenue[$date]['services'][$service_id]['count']++;

                $service_revenue[$service_id]['revenue'] += $price;
                $service_revenue[$service_id]['count']++;

                $total_revenue += $price;
                $appointment_count++;
            }

            // Sort by date
            ksort($daily_revenue);

            // Prepare data for chart based on filter
            $chart_dates = [];
            $chart_labels = [];
            $service_chart_data = []; // Will hold series data for each service
            
            if ($appointmentFilter === 'week') {
                // Get last 7 days of data
                $end_date_obj = new DateTime(); // Today
                for ($i = 6; $i >= 0; $i--) {
                    $current_date = clone $end_date_obj;
                    $current_date->modify("-{$i} days");
                    $date_string = $current_date->format('Y-m-d');
                    $label = $current_date->format('d M');

                    $chart_dates[] = $date_string;
                    $chart_labels[] = $label;
                }
            } elseif ($appointmentFilter === 'month') {
                // Get last 30 days grouped by week
                $end_date_obj = new DateTime();
                for ($i = 3; $i >= 0; $i--) {
                    $current_date = clone $end_date_obj;
                    $current_date->modify("-" . ($i * 7) . " days");
                    $date_string = $current_date->format('Y-m-d');
                    $label = 'Week ' . (4 - $i);

                    $chart_dates[] = $date_string;
                    $chart_labels[] = $label;
                }
            } else { // year
                // Get last 12 months
                $end_date_obj = new DateTime();
                for ($i = 11; $i >= 0; $i--) {
                    $current_date = clone $end_date_obj;
                    $current_date->modify("-$i months");
                    $date_string = $current_date->format('Y-m-01');
                    $label = $current_date->format('M Y');

                    $chart_dates[] = $date_string;
                    $chart_labels[] = $label;
                }
            }

            // Initialize service data structure
            foreach ($service_revenue as $service_id => $service) {
                $service_chart_data[$service_id] = [
                    'name' => $service['name'],
                    'data' => array_fill(0, count($chart_dates), 0) // Initialize with zeros
                ];
            }

            // Populate service data for each period
            foreach ($chart_dates as $day_index => $date) {
                if (isset($daily_revenue[$date])) {
                    foreach ($daily_revenue[$date]['services'] as $service_id => $service_data) {
                        if (isset($service_chart_data[$service_id])) {
                            $service_chart_data[$service_id]['data'][$day_index] = $service_data['revenue'];
                        }
                    }
                }
            }

            // Calculate total for current period
            $current_period_revenue = 0;
            foreach ($chart_dates as $date) {
                if (isset($daily_revenue[$date])) {
                    $current_period_revenue += $daily_revenue[$date]['total'];
                }
            }

            // Calculate percentage change (comparing current period to previous period)
            $previous_period_revenue = 0;
            // This would need to be calculated based on the filter period
            // For simplicity, we'll keep the existing week comparison
            
            // Convert PHP arrays to JavaScript
            $js_chart_labels = json_encode($chart_labels);
            $js_service_chart_data = json_encode(array_values($service_chart_data));
            ?>
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h5 class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">
                        ₱<?php echo number_format($current_period_revenue, 2); ?>
                    </h5>
                    <p class="text-base font-normal text-gray-500 dark:text-gray-400">Total appointment revenue</p>
                </div>
                <div class="flex items-center space-x-2">
                    <?php
                    // Calculate percentage change for the current period
                    $percentage_change = 0;
                    if ($appointmentFilter === 'week') {
                        // Calculate percentage change for week
                        $previous_week_start = new DateTime();
                        $previous_week_start->modify('-13 days');
                        $previous_week_end = new DateTime();
                        $previous_week_end->modify('-7 days');

                        $stmt_prev = $pdo->prepare("SELECT SUM(s.price) as prev_revenue
                               FROM appointments a 
                               JOIN services s ON a.service_id = s.id 
                               WHERE a.status = 'completed' 
                                 AND a.appointment_date BETWEEN ? AND ?");
                        $stmt_prev->execute([$previous_week_start->format('Y-m-d'), $previous_week_end->format('Y-m-d')]);
                        $previous_week_data = $stmt_prev->fetch();
                        $previous_period_revenue = floatval($previous_week_data['prev_revenue'] ?? 0);

                        if ($previous_period_revenue > 0) {
                            $percentage_change = (($current_period_revenue - $previous_period_revenue) / $previous_period_revenue) * 100;
                        }
                    }
                    // Add similar logic for month and year filters if needed
                    ?>
                    <div
                        class="flex items-center px-2.5 py-0.5 text-base font-semibold <?php echo $percentage_change >= 0 ? 'text-green-500' : 'text-red-500'; ?> text-center">
                        <?php echo abs(round($percentage_change, 1)); ?>%
                        <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="<?php echo $percentage_change >= 0 ? 'M5 13V1m0 0L1 5m4-4 4 4' : 'M5 1v12m0 0l4-4m-4 4L1 9'; ?>" />
                        </svg>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-1">
                        <a href="?appointment_filter=week&sales_filter=<?php echo $salesFilter; ?>&inventory_filter=<?php echo $inventoryFilter; ?>"
                            class="px-3 py-1 text-sm rounded-md <?php echo $appointmentFilter === 'week' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'; ?>">
                            Daily
                        </a>
                        <a href="?appointment_filter=month&sales_filter=<?php echo $salesFilter; ?>&inventory_filter=<?php echo $inventoryFilter; ?>"
                            class="px-3 py-1 text-sm rounded-md <?php echo $appointmentFilter === 'month' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'; ?>">
                            Weekly
                        </a>
                        <a href="?appointment_filter=year&sales_filter=<?php echo $salesFilter; ?>&inventory_filter=<?php echo $inventoryFilter; ?>"
                            class="px-3 py-1 text-sm rounded-md <?php echo $appointmentFilter === 'year' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'; ?>">
                            Monthly
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Chart Showing All Services -->
            <div id="grid-chart" class="w-full aspect-[16/9] p-2 md:p-6"></div>

            <div
                class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between mt-5">
            </div>
        </div>

        <!-- chart #2 -->
        <div class="w-full bg-white rounded-xl shadow-md border border-gray-100 p-4 md:p-6">
            <?php
            // Query for confirmed orders with filter
            if ($salesFilter === 'week') {
                $end_date = date('Y-m-d');
                $start_date = date('Y-m-d', strtotime('-6 days'));
                $group_by = "DATE(o.order_date), DAYNAME(o.order_date)";
                $order_by = "DATE(o.order_date)";

                $stmt = $pdo->prepare("SELECT 
                          DATE(o.order_date) as order_day,
                          DAYNAME(o.order_date) as day_name,
                          SUM(oi.price * oi.quantity) as daily_sales,
                          COUNT(DISTINCT o.id) as order_count
                        FROM orders o 
                        JOIN order_items oi ON o.id = oi.order_id
                        WHERE o.status = 'confirmed' 
                          AND DATE(o.order_date) BETWEEN ? AND ?
                        GROUP BY $group_by
                        ORDER BY $order_by");
                $stmt->execute([$start_date, $end_date]);
                $daily_sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Create data structure for week
                $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                $sales_by_period = array_fill_keys($days, 0);

                foreach ($daily_sales_data as $day_data) {
                    $day_abbr = substr($day_data['day_name'], 0, 3);
                    $sales_by_period[$day_abbr] = floatval($day_data['daily_sales']);
                }
                $chart_labels = $days;

            } elseif ($salesFilter === 'month') {
                $end_date = date('Y-m-d');
                $start_date = date('Y-m-d', strtotime('-29 days'));

                $stmt = $pdo->prepare("SELECT 
                          DATE(o.order_date) as order_day,
                          SUM(oi.price * oi.quantity) as daily_sales
                        FROM orders o 
                        JOIN order_items oi ON o.id = oi.order_id
                        WHERE o.status = 'confirmed' 
                          AND DATE(o.order_date) BETWEEN ? AND ?
                        GROUP BY DATE(o.order_date)
                        ORDER BY DATE(o.order_date)");
                $stmt->execute([$start_date, $end_date]);
                $daily_sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Group by week for monthly view
                $sales_by_period = [];
                $chart_labels = [];

                $current_date = new DateTime($start_date);
                $end_date_obj = new DateTime($end_date);

                // Create weeks
                $week_number = 1;
                while ($current_date <= $end_date_obj) {
                    $week_start = clone $current_date;
                    $week_label = 'Week ' . $week_number;
                    $sales_by_period[$week_label] = 0;
                    $chart_labels[] = $week_label;

                    // Skip 7 days
                    $current_date->modify('+7 days');
                    $week_number++;
                }

                // Assign data to weeks
                foreach ($daily_sales_data as $day_data) {
                    $order_date = new DateTime($day_data['order_day']);
                    $days_diff = (int) $order_date->diff(new DateTime($start_date))->format('%a');
                    $week_index = floor($days_diff / 7);

                    if (isset($chart_labels[$week_index])) {
                        $week_label = $chart_labels[$week_index];
                        $sales_by_period[$week_label] += floatval($day_data['daily_sales']);
                    }
                }

            } else { // year
                $current_year = date('Y');
                $start_date = $current_year . '-01-01';
                $end_date = $current_year . '-12-31';

                $stmt = $pdo->prepare("SELECT 
                          MONTH(o.order_date) as order_month,
                          YEAR(o.order_date) as order_year,
                          SUM(oi.price * oi.quantity) as monthly_sales
                        FROM orders o 
                        JOIN order_items oi ON o.id = oi.order_id
                        WHERE o.status = 'confirmed' 
                          AND YEAR(o.order_date) = ?
                        GROUP BY YEAR(o.order_date), MONTH(o.order_date)
                        ORDER BY YEAR(o.order_date), MONTH(o.order_date)");
                $stmt->execute([$current_year]);
                $monthly_sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Create data structure for year
                $months = [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec'
                ];

                $sales_by_period = array_fill_keys($months, 0);
                $chart_labels = $months;

                foreach ($monthly_sales_data as $month_data) {
                    $month_num = intval($month_data['order_month']);
                    if ($month_num >= 1 && $month_num <= 12) {
                        $month_name = $months[$month_num - 1];
                        $sales_by_period[$month_name] = floatval($month_data['monthly_sales']);
                    }
                }
            }

            $total_sales = array_sum($sales_by_period);
            $chart_data = [];

            foreach ($chart_labels as $label) {
                $chart_data[] = ['x' => $label, 'y' => $sales_by_period[$label]];
            }

            // Calculate percentage change from previous period
            $percentage_change = 0;
            if ($salesFilter === 'week') {
                $prev_start_date = date('Y-m-d', strtotime('-13 days'));
                $prev_end_date = date('Y-m-d', strtotime('-7 days'));

                $stmt_prev = $pdo->prepare("SELECT SUM(oi.price * oi.quantity) as prev_sales
                              FROM orders o 
                              JOIN order_items oi ON o.id = oi.order_id
                              WHERE o.status = 'confirmed' 
                                AND DATE(o.order_date) BETWEEN ? AND ?");
                $stmt_prev->execute([$prev_start_date, $prev_end_date]);
                $prev_period_sales = $stmt_prev->fetchColumn();

                if ($prev_period_sales > 0) {
                    $percentage_change = (($total_sales - $prev_period_sales) / $prev_period_sales) * 100;
                }
            } elseif ($salesFilter === 'month') {
                $prev_start_date = date('Y-m-d', strtotime('-59 days'));
                $prev_end_date = date('Y-m-d', strtotime('-30 days'));

                $stmt_prev = $pdo->prepare("SELECT SUM(oi.price * oi.quantity) as prev_sales
                              FROM orders o 
                              JOIN order_items oi ON o.id = oi.order_id
                              WHERE o.status = 'confirmed' 
                                AND DATE(o.order_date) BETWEEN ? AND ?");
                $stmt_prev->execute([$prev_start_date, $prev_end_date]);
                $prev_period_sales = $stmt_prev->fetchColumn();

                if ($prev_period_sales > 0) {
                    $percentage_change = (($total_sales - $prev_period_sales) / $prev_period_sales) * 100;
                }
            } else { // year
                $prev_year = date('Y') - 1;

                $stmt_prev = $pdo->prepare("SELECT SUM(oi.price * oi.quantity) as prev_sales
                              FROM orders o 
                              JOIN order_items oi ON o.id = oi.order_id
                              WHERE o.status = 'confirmed' 
                                AND YEAR(o.order_date) = ?");
                $stmt_prev->execute([$prev_year]);
                $prev_period_sales = $stmt_prev->fetchColumn();

                if ($prev_period_sales > 0) {
                    $percentage_change = (($total_sales - $prev_period_sales) / $prev_period_sales) * 100;
                }
            }
            ?>

            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center me-3">
                        <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 19">
                            <path
                                d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z" />
                            <path
                                d="M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z" />
                        </svg>
                    </div>
                    <div>
                        <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1">
                            ₱<?php echo number_format($total_sales, 2); ?></h5>
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Total product sales</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <?php
                    // Calculate percentage change for product sales
                    $percentage_change = 0;
                    if ($salesFilter === 'week') {
                        $prev_start_date = date('Y-m-d', strtotime('-13 days'));
                        $prev_end_date = date('Y-m-d', strtotime('-7 days'));

                        $stmt_prev = $pdo->prepare("SELECT SUM(oi.price * oi.quantity) as prev_sales
                              FROM orders o 
                              JOIN order_items oi ON o.id = oi.order_id
                              WHERE o.status = 'confirmed' 
                                AND DATE(o.order_date) BETWEEN ? AND ?");
                        $stmt_prev->execute([$prev_start_date, $prev_end_date]);
                        $prev_period_sales = $stmt_prev->fetchColumn();

                        if ($prev_period_sales > 0) {
                            $percentage_change = (($total_sales - $prev_period_sales) / $prev_period_sales) * 100;
                        }
                    }
                    ?>
                    <span
                        class="bg-<?php echo $percentage_change >= 0 ? 'green' : 'red'; ?>-100 text-<?php echo $percentage_change >= 0 ? 'green' : 'red'; ?>-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-<?php echo $percentage_change >= 0 ? 'green' : 'red'; ?>-900 dark:text-<?php echo $percentage_change >= 0 ? 'green' : 'red'; ?>-300">
                        <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 10 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="<?php echo $percentage_change >= 0 ? 'M5 13V1m0 0L1 5m4-4 4 4' : 'M5 1v12m0 0l4-4m-4 4L1 9'; ?>" />
                        </svg>
                        <?php echo abs(round($percentage_change, 1)); ?>%
                    </span>
                    <div class="bg-white border border-gray-200 rounded-lg p-1">
                        <a href="?appointment_filter=<?php echo $appointmentFilter; ?>&sales_filter=week&inventory_filter=<?php echo $inventoryFilter; ?>"
                            class="px-3 py-1 text-sm rounded-md <?php echo $salesFilter === 'week' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'; ?>">
                            Daily
                        </a>
                        <a href="?appointment_filter=<?php echo $appointmentFilter; ?>&sales_filter=month&inventory_filter=<?php echo $inventoryFilter; ?>"
                            class="px-3 py-1 text-sm rounded-md <?php echo $salesFilter === 'month' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'; ?>">
                            Weekly
                        </a>
                        <a href="?appointment_filter=<?php echo $appointmentFilter; ?>&sales_filter=year&inventory_filter=<?php echo $inventoryFilter; ?>"
                            class="px-3 py-1 text-sm rounded-md <?php echo $salesFilter === 'year' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'; ?>">
                            Monthly
                        </a>
                    </div>
                </div>
            </div>
            <div id="column-chart"></div>
            <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
            </div>
        </div>
    </div>

    <!-- Inventory Chart -->
    <div class="container mx-auto px-6 gap-6 mb-8">
        <div class="w-full bg-white rounded-xl shadow-md border border-gray-100 p-4 md:p-6">
            <div>
                <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center me-3">
                            <i class="fas fa-boxes text-gray-500 text-xl"></i>
                        </div>
                        <div>
                            <h5 class="leading-none text-2xl font-bold text-gray-900 pb-1">
                                <?php
                                if ($inventoryFilter === 'low') {
                                    $lowStockProducts = array_filter($products, function ($product) {
                                        return $product['stock'] <= 10; // Adjust threshold as needed
                                    });
                                    echo array_sum(array_column($lowStockProducts, 'stock'));
                                } else {
                                    echo array_sum(array_column($products, 'stock'));
                                }
                                ?>
                            </h5>
                            <p class="text-sm font-normal text-gray-500">
                                <?php echo $inventoryFilter === 'low' ? 'Low Stock Products' : 'Total Products in Stock'; ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span
                            class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md">
                            <i class="fas fa-chart-line mr-1"></i>
                            Stock Overview
                        </span>
                        <div class="bg-white border border-gray-200 rounded-lg p-1">
                            <a href="?appointment_filter=<?php echo $appointmentFilter; ?>&sales_filter=<?php echo $salesFilter; ?>&inventory_filter=all"
                                class="px-3 py-1 text-sm rounded-md <?php echo $inventoryFilter === 'all' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'; ?>">
                                All Stock
                            </a>
                            <a href="?appointment_filter=<?php echo $appointmentFilter; ?>&sales_filter=<?php echo $salesFilter; ?>&inventory_filter=low"
                                class="px-3 py-1 text-sm rounded-md <?php echo $inventoryFilter === 'low' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'; ?>">
                                Low Stock
                            </a>
                        </div>
                    </div>
                </div>
                <div id="inventory-chart"></div>
                <script>
                    // Pass the PHP data to JavaScript
                    window.productChartData = {
                        products: <?php echo json_encode(array_column($products, 'name')); ?>,
                        stocks: <?php echo json_encode(array_column($products, 'stock')); ?>,
                        categories: <?php echo json_encode(array_map(function ($p) {
                            return getProductCategoryById($p['category_id'])['name'];
                        }, $products)); ?>,
                        totalStock: <?php echo array_sum(array_column($products, 'stock')); ?>,
                        inventoryFilter: '<?php echo $inventoryFilter; ?>'
                    };
                </script>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<?php
echo '<script>
    var dashboardConfig = {
        appointmentFilter: "' . $appointmentFilter . '",
        salesFilter: "' . $salesFilter . '",
        inventoryFilter: "' . $inventoryFilter . '",
        chartLabels: ' . $js_chart_labels . ',
        serviceChartData: ' . $js_service_chart_data . ',
        columnChartData: ' . json_encode($chart_data) . '
    };
</script>';
?>

<script src="../assets/js/charts.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>