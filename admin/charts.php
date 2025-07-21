<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php requireAdmin(); ?>

<?php
$appointmentCounts = getAppointmentCounts();
$recentAppointments = getAllAppointments('confirmed');
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


    <!-- charts -->

    <div class="container mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- chart #1 -->
        <div class="w-full bg-white rounded-xl shadow-md border border-gray-100 p-4 md:p-6">
            <?php
            // Query for completed appointments (no date filtering)
            $stmt = $pdo->prepare("SELECT a.*, s.name as service_name, s.price as service_price, 
                          s.id as service_id, u.first_name, u.last_name, u.email, u.phone 
                   FROM appointments a 
                   JOIN services s ON a.service_id = s.id 
                   JOIN users u ON a.user_id = u.id 
                   WHERE a.status = 'completed' 
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

            // Prepare data for chart (last 7 days)
            $chart_dates = [];
            $chart_labels = [];
            $service_chart_data = []; // Will hold series data for each service
            
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

            // Initialize service data structure
            foreach ($service_revenue as $service_id => $service) {
                $service_chart_data[$service_id] = [
                    'name' => $service['name'],
                    'data' => array_fill(0, 7, 0) // Initialize with 7 days of zeros
                ];
            }

            // Populate service data for each day
            foreach ($chart_dates as $day_index => $date) {
                if (isset($daily_revenue[$date])) {
                    foreach ($daily_revenue[$date]['services'] as $service_id => $service_data) {
                        if (isset($service_chart_data[$service_id])) {
                            $service_chart_data[$service_id]['data'][$day_index] = $service_data['revenue'];
                        }
                    }
                }
            }

            // Calculate total for current week
            $current_week_revenue = 0;
            foreach ($chart_dates as $date) {
                if (isset($daily_revenue[$date])) {
                    $current_week_revenue += $daily_revenue[$date]['total'];
                }
            }

            // Calculate percentage change (comparing current week to previous week)
            $previous_week_start = new DateTime();
            $previous_week_start->modify('-13 days');
            $previous_week_end = new DateTime();
            $previous_week_end->modify('-7 days');

            // Query for previous week data
            $stmt_prev = $pdo->prepare("SELECT SUM(s.price) as prev_revenue
                   FROM appointments a 
                   JOIN services s ON a.service_id = s.id 
                   WHERE a.status = 'completed' 
                     AND a.appointment_date BETWEEN ? AND ?");
            $stmt_prev->execute([$previous_week_start->format('Y-m-d'), $previous_week_end->format('Y-m-d')]);
            $previous_week_data = $stmt_prev->fetch();
            $previous_week_revenue = floatval($previous_week_data['prev_revenue'] ?? 0);

            // Calculate percentage change
            $percentage_change = 0;
            if ($previous_week_revenue > 0) {
                $percentage_change = (($current_week_revenue - $previous_week_revenue) / $previous_week_revenue) * 100;
            }

            // Convert PHP arrays to JavaScript
            $js_chart_labels = json_encode($chart_labels);
            $js_service_chart_data = json_encode(array_values($service_chart_data));
            ?>
            <div class="flex justify-between mb-5">
                <div>
                    <h5 class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">
                        ₱<?php echo number_format($current_week_revenue, 2); ?>
                    </h5>
                    <p class="text-base font-normal text-gray-500 dark:text-gray-400">Total appointment revenue</p>
                </div>
                <div
                    class="flex items-center px-2.5 py-0.5 text-base font-semibold <?php echo $percentage_change >= 0 ? 'text-green-500' : 'text-red-500'; ?> text-center">
                    <?php echo abs(round($percentage_change, 1)); ?>%
                    <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="<?php echo $percentage_change >= 0 ? 'M5 13V1m0 0L1 5m4-4 4 4' : 'M5 1v12m0 0l4-4m-4 4L1 9'; ?>" />
                    </svg>
                </div>
            </div>

            <!-- Main Chart Showing All Services -->
            <div id="grid-chart"></div>

            <div
                class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between mt-5">
                <div class="flex justify-between items-center pt-5">
                    <!-- Button -->
                    <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown"
                        data-dropdown-placement="bottom"
                        class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                        type="button">
                        Last 7 days
                        <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="lastDaysdropdown"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby="dropdownDefaultButton">
                            <li>
                                <a href="#"
                                    class="hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-black">Yesterday</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-black">Today</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-black">Last
                                    7 days</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-black">Last
                                    30 days</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-blacke">Last
                                    90 days</a>
                            </li>
                        </ul>
                    </div>
                    <a href="#"
                        class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                        Sales Report
                        <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- chart #2 -->
        <div class="w-full bg-white rounded-xl shadow-md border border-gray-100 p-4 md:p-6">
            <?php
            // Query for confirmed orders (last 7 days)
            $end_date = date('Y-m-d');
            $start_date = date('Y-m-d', strtotime('-6 days'));

            $stmt = $pdo->prepare("SELECT 
                          DATE(o.order_date) as order_day,
                          DAYNAME(o.order_date) as day_name,
                          SUM(oi.price * oi.quantity) as daily_sales,
                          COUNT(DISTINCT o.id) as order_count
                        FROM orders o 
                        JOIN order_items oi ON o.id = oi.order_id
                        WHERE o.status = 'confirmed' 
                          AND DATE(o.order_date) BETWEEN ? AND ?
                        GROUP BY DATE(o.order_date), DAYNAME(o.order_date)
                        ORDER BY DATE(o.order_date)");
            $stmt->execute([$start_date, $end_date]);
            $daily_sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Create a map of all days in the week
            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $sales_by_day = array_fill_keys($days, 0);

            // Calculate total sales and populate daily sales
            $total_sales = 0;
            $total_orders = 0;

            foreach ($daily_sales_data as $day_data) {
                $day_abbr = substr($day_data['day_name'], 0, 3);
                $sales_by_day[$day_abbr] = floatval($day_data['daily_sales']);
                $total_sales += $day_data['daily_sales'];
                $total_orders += $day_data['order_count'];
            }

            // Calculate percentage change from previous week
            $prev_start_date = date('Y-m-d', strtotime('-13 days'));
            $prev_end_date = date('Y-m-d', strtotime('-7 days'));

            $stmt_prev = $pdo->prepare("SELECT SUM(oi.price * oi.quantity) as prev_sales
                              FROM orders o 
                              JOIN order_items oi ON o.id = oi.order_id
                              WHERE o.status = 'confirmed' 
                                AND DATE(o.order_date) BETWEEN ? AND ?");
            $stmt_prev->execute([$prev_start_date, $prev_end_date]);
            $prev_week_sales = $stmt_prev->fetchColumn();

            $percentage_change = 0;
            if ($prev_week_sales > 0) {
                $percentage_change = (($total_sales - $prev_week_sales) / $prev_week_sales) * 100;
            }

            // Prepare data for chart
            $chart_data = [];
            foreach ($days as $day) {
                $chart_data[] = ['x' => $day, 'y' => $sales_by_day[$day]];
            }
            ?>

            <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
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
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Total product sales this week
                        </p>
                    </div>
                </div>
                <div>
                    <span
                        class="bg-<?php echo $percentage_change >= 0 ? 'green' : 'red'; ?>-100 text-<?php echo $percentage_change >= 0 ? 'green' : 'red'; ?>-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-<?php echo $percentage_change >= 0 ? 'green' : 'red'; ?>-900 dark:text-<?php echo $percentage_change >= 0 ? 'green' : 'red'; ?>-300">
                        <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 10 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="<?php echo $percentage_change >= 0 ? 'M5 13V1m0 0L1 5m4-4 4 4' : 'M5 1v12m0 0l4-4m-4 4L1 9'; ?>" />
                        </svg>
                        <?php echo abs(round($percentage_change, 1)); ?>%
                    </span>
                </div>
            </div>
            <div id="column-chart"></div>
            <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
                <div class="flex justify-between items-center pt-5">
                    <!-- Button -->
                    <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown"
                        data-dropdown-placement="bottom"
                        class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                        type="button">
                        Last 7 days
                        <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="lastDaysdropdown"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby="dropdownDefaultButton">
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Yesterday</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                    7 days</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                    30 days</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                    90 days</a>
                            </li>
                        </ul>
                    </div>
                    <a href="#"
                        class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                        Sales Report
                        <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<script>

    const chartLabels = <?php echo $js_chart_labels; ?>;
    const serviceChartData = <?php echo $js_service_chart_data; ?>;

    // Color palette for 6 services
    const serviceColors = [
        '#1A56DB', // Blue
        '#7E3AF2', // Purple
        '#F05252', // Red
        '#F59E0B', // Yellow
        '#10B981', // Green
        '#3F83F8'  // Light Blue
    ];

    // Chart options showing all services
    const chartOptions = {
        grid: {
            show: true,
            strokeDashArray: 4,
            padding: {
                left: 10,
                right: 2,
                top: 0
            },
        },
        series: serviceChartData.map((service, index) => ({
            name: service.name,
            data: service.data,
            color: serviceColors[index % serviceColors.length]
        })),
        chart: {
            height: "100%",
            maxWidth: "100%",
            type: "area",
            fontFamily: "Inter, sans-serif",
            dropShadow: {
                enabled: false,
            },
            toolbar: {
                show: false,
            },
        },
        tooltip: {
            enabled: true,
            x: {
                show: false,
            },
            y: {
                formatter: function (value) {
                    return '₱' + value.toFixed(2);
                }
            }
        },
        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center',
            itemMargin: {
                horizontal: 8,
                vertical: 8
            }
        },
        dataLabels: {
            enabled: false,
        },

        stroke: {
            width: 4,
            curve: 'smooth'
        },


        xaxis: {
            categories: chartLabels,
            labels: {
                show: true,
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },
        yaxis: {
            show: true,
            labels: {
                formatter: function (value) {
                    return '₱' + value.toFixed(2);
                }
            }
        },
    };

    // Initialize chart
    if (document.getElementById("grid-chart") && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById("grid-chart"), chartOptions);
        chart.render();
    }


    const column = {
        colors: ["#1A56DB", "#FDBA8C"],
        series: [
            {
                name: "Product Sales",
                color: "#1A56DB",
                data: <?php echo json_encode($chart_data); ?>
            }
        ],
        chart: {
            type: "bar",
            height: "320px",
            fontFamily: "Inter, sans-serif",
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "70%",
                borderRadiusApplication: "end",
                borderRadius: 8,
            },
        },
        tooltip: {
            shared: true,
            intersect: false,
            style: {
                fontFamily: "Inter, sans-serif",
            },
            y: {
                formatter: function (value) {
                    return '₱' + value.toFixed(2);
                }
            }
        },
        states: {
            hover: {
                filter: {
                    type: "darken",
                    value: 1,
                },
            },
        },
        stroke: {
            show: true,
            width: 0,
            colors: ["transparent"],
        },
        grid: {
            show: false,
            strokeDashArray: 4,
            padding: {
                left: 2,
                right: 2,
                top: -14
            },
        },
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: false,
        },
        xaxis: {
            floating: false,
            labels: {
                show: true,
                style: {
                    fontFamily: "Inter, sans-serif",
                    cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                }
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },
        yaxis: {
            show: false,
        },
        fill: {
            opacity: 1,
        },
    };

    if (document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById("column-chart"), column);
        chart.render();
    }
</script>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>