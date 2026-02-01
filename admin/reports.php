<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php requireAdmin(); ?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<?php
$report_type = $_GET['type'] ?? 'appointments';
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');
$category = $_GET['category'] ?? 'all';
?>

<style>
    @media print {

        /* Hide navigation, buttons, and filters when printing */
        nav,
        button,
        .no-print,
        form {
            display: none !important;
        }

        /* Hide filter tabs and actions */
        .bg-gradient-to-r.from-blue-600 {
            display: none !important;
        }

        /* Hide report type tabs */
        .border-b.border-gray-200.bg-gray-50 {
            display: none !important;
        }

        /* Optimize page layout for printing */
        body {
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
            font-size: 12pt !important;
        }

        .min-h-screen {
            background: white !important;
        }

        /* Remove shadows for cleaner print */
        .shadow-lg,
        .shadow-sm {
            box-shadow: none !important;
        }

        /* Table styling with visible borders */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            page-break-inside: auto;
        }

        table, th, td {
            border: 1px solid #333 !important;
        }

        th {
            background-color: #f3f4f6 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            font-size: 10pt !important;
            padding: 8px 12px !important;
        }

        td {
            padding: 8px 12px !important;
            font-size: 10pt !important;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tfoot td {
            background-color: #f9fafb !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-weight: bold !important;
        }

        /* Remove rounded corners for print */
        .rounded-lg,
        .rounded-xl,
        .rounded-full {
            border-radius: 0 !important;
        }

        /* Add print header - Landscape orientation */
        @page {
            margin: 1cm;
            size: A4 landscape;
        }

        .container {
            max-width: 100% !important;
            padding: 0 !important;
        }

        /* Show print date */
        .print-header {
            display: block !important;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        /* Hide screen-only header */
        .mb-8 > h1.text-4xl,
        .mb-8 > p.text-lg {
            display: none !important;
        }

        /* Status badges - print as text */
        .inline-flex.px-3.py-1 {
            background: none !important;
            border: 1px solid #666 !important;
            padding: 2px 6px !important;
        }

        /* Ensure text colors print properly */
        .text-green-600,
        .text-blue-600 {
            color: #000 !important;
        }

        /* Show prepared by section when printing */
        .print-prepared-by {
            display: block !important;
            margin-top: 30px;
        }

        /* Table header styling for print */
        thead.bg-blue-600,
        thead.bg-gray-50 {
            background-color: #2c5aa0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        thead.bg-blue-600 th,
        thead.bg-gray-50 th {
            color: #000 !important;
            background-color: #e5e7eb !important;
        }

        /* Summary box styling for print */
        .bg-gray-50.border {
            border: 1px solid #333 !important;
            padding: 10px !important;
            margin-bottom: 15px !important;
        }

        /* Print footer */
        .print-footer {
            display: block !important;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            font-size: 9pt;
            color: #666;
            text-align: center;
        }
    }

    /* Hide print header on screen */
    .print-header {
        display: none;
    }

    /* Hide prepared by section on screen */
    .print-prepared-by {
        display: none;
    }

    /* Hide print footer on screen */
    .print-footer {
        display: none;
    }
</style>

<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Print-only Header -->
        <div class="print-header" style="text-align: center;">
            <h1 style="font-size: 28px; font-weight: bold; color: #2c5aa0; margin: 0; letter-spacing: 1px;">FURCARE</h1>
            <div style="font-size: 12px; color: #555; margin: 10px 0;">
                Mabitad Sto. Nino, Panabo City, Davao del Norte<br>
                Email: panabopetgrooming@gmail.com | Contact: +639700249877
            </div>
            <h2 style="font-size: 20px; color: #444; margin: 15px 0 5px;"><?php echo ucfirst($report_type); ?> Report</h2>
            <p style="font-size: 14px; color: #666; font-style: italic;">
                Date Range: <?php echo date('F j, Y', strtotime($start_date)); ?> to <?php echo date('F j, Y', strtotime($end_date)); ?>
            </p>
        </div>

        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Business Reports</h1>
            <p class="text-lg text-gray-600">Comprehensive analytics and insights for your business</p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Filter Section -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                <form method="GET" action="reports.php" class="space-y-4">
                    <input type="hidden" name="type" value="<?php echo $report_type; ?>">

                    <?php if ($report_type == 'appointments'): ?>
                        <!-- Quick Time Period Filters -->
                        <div class="mb-4">
                            <div class="flex flex-wrap gap-2">
                                <a href="reports.php?type=appointments&start_date=<?php echo date('Y-m-d'); ?>&end_date=<?php echo date('Y-m-d'); ?>"
                                    class="px-3 py-1.5 text-sm bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                    Today
                                </a>
                                <a href="reports.php?type=appointments&start_date=<?php echo date('Y-m-d', strtotime('-7 days')); ?>&end_date=<?php echo date('Y-m-d'); ?>"
                                    class="px-3 py-1.5 text-sm bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                    Last 7 Days
                                </a>
                                <a href="reports.php?type=appointments&start_date=<?php echo date('Y-m-01'); ?>&end_date=<?php echo date('Y-m-t'); ?>"
                                    class="px-3 py-1.5 text-sm bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                    This Month
                                </a>
                                <a href="reports.php?type=appointments&start_date=<?php echo date('Y-01-01'); ?>&end_date=<?php echo date('Y-12-31'); ?>"
                                    class="px-3 py-1.5 text-sm bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                    This Year
                                </a>
                                <a href="reports.php?type=appointments&start_date=<?php echo date('Y-01-01', strtotime('-1 year')); ?>&end_date=<?php echo date('Y-12-31', strtotime('-1 year')); ?>"
                                    class="px-3 py-1.5 text-sm bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                    Last Year
                                </a>
                            </div>
                        </div>

                        <!-- Appointments Layout: 4 columns -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="w-full">
                                <label for="start_date" class="block text-blue-100 font-medium mb-2">Start Date</label>
                                <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>"
                                    class="w-full px-4 py-3 bg-white border-0 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none text-gray-900 shadow-sm">
                            </div>
                            <div>
                                <label for="end_date" class="block text-blue-100 font-medium mb-2">End Date</label>
                                <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>"
                                    class="w-full px-4 py-3 bg-white border-0 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none text-gray-900 shadow-sm">
                            </div>
                            <div class="flex items-end gap-4">
                                <button type="submit"
                                    class="w-dull bg-white text-blue-600 font-semibold py-3 px-6 rounded-lg hover:bg-blue-50 transition duration-300 shadow-sm flex items-center justify-center">
                                    <svg class="w-2xl h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                        </path>
                                    </svg>
                                    Filter
                                </button>
                                 <a href="../php/admin/export_pdf.php?type=<?php echo $report_type; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&view=true" target="_blank"
                                    class="w-full bg-yellow-400 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 shadow-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                    Print
                                </a>
                            </div>
                            <div class="flex items-end gap-2">
                               
                                <a href="../php/admin/export_pdf.php?type=<?php echo $report_type; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>"
                                    class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 shadow-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Quick Time Period Filters -->
                        <div class="mb-4">
                            <div class="flex flex-wrap gap-2">
                                <a href="reports.php?type=<?php echo $report_type; ?>&start_date=<?php echo date('Y-m-d'); ?>&end_date=<?php echo date('Y-m-d'); ?>&category=<?php echo $category; ?>"
                                    class="px-3 py-1.5 text-sm bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                    Today
                                </a>
                                <a href="reports.php?type=<?php echo $report_type; ?>&start_date=<?php echo date('Y-m-d', strtotime('-7 days')); ?>&end_date=<?php echo date('Y-m-d'); ?>&category=<?php echo $category; ?>"
                                    class="px-3 py-1.5 text-sm bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                    Last 7 Days
                                </a>
                                <a href="reports.php?type=<?php echo $report_type; ?>&start_date=<?php echo date('Y-m-01'); ?>&end_date=<?php echo date('Y-m-t'); ?>&category=<?php echo $category; ?>"
                                    class="px-3 py-1.5 text-sm bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                    This Month
                                </a>
                                <a href="reports.php?type=<?php echo $report_type; ?>&start_date=<?php echo date('Y-01-01'); ?>&end_date=<?php echo date('Y-12-31'); ?>&category=<?php echo $category; ?>"
                                    class="px-3 py-1.5 text-sm bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                    This Year
                                </a>
                                <a href="reports.php?type=<?php echo $report_type; ?>&start_date=<?php echo date('Y-01-01', strtotime('-1 year')); ?>&end_date=<?php echo date('Y-12-31', strtotime('-1 year')); ?>&category=<?php echo $category; ?>"
                                    class="px-3 py-1.5 text-sm bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                    Last Year
                                </a>
                            </div>
                        </div>

                        <!-- Sales Layout: All in one row -->
                        <div class="flex flex-wrap gap-4 items-end">
                            <div class="flex-1 min-w-[200px]">
                                <label for="start_date" class="block text-blue-100 font-medium mb-2">Start Date</label>
                                <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>"
                                    class="w-full px-4 py-3 bg-white border-0 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none text-gray-900 shadow-sm">
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <label for="end_date" class="block text-blue-100 font-medium mb-2">End Date</label>
                                <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>"
                                    class="w-full px-4 py-3 bg-white border-0 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none text-gray-900 shadow-sm">
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <label for="category" class="block text-blue-100 font-medium mb-2">Category</label>
                                <select id="category" name="category"
                                    class="w-full px-4 py-3 bg-white border-0 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none text-gray-900 shadow-sm">
                                    <option value="all" <?php echo $category == 'All Sales' ? 'selected' : ''; ?>>All Sales
                                    </option>
                                    <option value="1" <?php echo $category == 'Pet Food' ? 'selected' : ''; ?>>Pet Food
                                    </option>
                                    <option value="2" <?php echo $category == 'Pet Accessories' ? 'selected' : ''; ?>>Pet
                                        Accessories
                                    </option>
                                    <option value="3" <?php echo $category == 'Pet Milk' ? 'selected' : ''; ?>>Pet Milk
                                    </option>
                                    <option value="4" <?php echo $category == 'Pet Shampoo' ? 'selected' : ''; ?>>Pet Shampoo
                                    </option>
                                    <option value="5" <?php echo $category == 'Pet Treats' ? 'selected' : ''; ?>>Pet Treats
                                    </option>
                                    <option value="6" <?php echo $category == 'Pet Apparels' ? 'selected' : ''; ?>>Pet
                                        Apparels</option>
                                </select>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="submit"
                                    class="bg-white text-blue-600 font-semibold py-3 px-6 rounded-lg hover:bg-blue-50 transition duration-300 shadow-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                        </path>
                                    </svg>
                                    Filter
                                </button>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="../php/admin/export_pdf.php?type=<?php echo $report_type; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?><?php echo ($report_type == 'sales' && $category != 'all') ? '&category=' . $category : ''; ?>&view=true" target="_blank"
                                    class="bg-yellow-400 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 shadow-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                    Print
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="../php/admin/export_pdf.php?type=<?php echo $report_type; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?><?php echo ($report_type == 'sales' && $category != 'all') ? '&category=' . $category : ''; ?>"
                                    class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 shadow-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Report Type Tabs -->
            <div class="border-b border-gray-200 bg-gray-50">
                <nav class="flex space-x-8 px-8 py-4">
                    <a href="reports.php?type=appointments"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors <?php echo $report_type == 'appointments' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'; ?>">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Appointments
                    </a>
                    <a href="reports.php?type=sales"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors <?php echo $report_type == 'sales' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'; ?>">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Product Sales
                    </a>
                    <a href="reports.php?type=orders"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors <?php echo $report_type == 'orders' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'; ?>">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Order Reports
                    </a>
                </nav>
            </div>

            <!-- Report Content -->
            <div class="p-8">
                <?php if ($report_type == 'appointments'): ?>
                    <div class="mb-6 no-print">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Appointment Report</h2>
                        <p class="text-gray-600">Detailed view from <?php echo formatDate($start_date); ?> to
                            <?php echo formatDate($end_date); ?>
                        </p>
                    </div>

                    <?php
                    $stmt = $pdo->prepare("SELECT a.*, s.name as service_name, s.price as service_price,
                        u.first_name, u.last_name, u.email, u.phone
                        FROM appointments a
                        JOIN services s ON a.service_id = s.id
                        JOIN users u ON a.user_id = u.id
                        WHERE a.status = 'completed'
                        AND a.appointment_date BETWEEN ? AND ?
                        ORDER BY a.appointment_date DESC, a.appointment_time DESC");
                    $stmt->execute([$start_date, $end_date]);
                    $appointments = $stmt->fetchAll();

                    // Calculate summary
                    $total_appointments = count($appointments);
                    $total_revenue = array_sum(array_column($appointments, 'service_price'));
                    $confirmed_count = count(array_filter($appointments, function($row) {
                        return $row['status'] === 'confirmed';
                    }));
                    ?>

                    <!-- Summary Box -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-semibold text-blue-600 mb-3">Appointment Summary</h3>
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div><strong>Total Appointments:</strong> <?php echo $total_appointments; ?></div>
                            <div><strong>Confirmed:</strong> <?php echo $confirmed_count; ?></div>
                            <div><strong>Total Revenue:</strong> PHP <?php echo number_format($total_revenue, 2); ?></div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-600">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Customer</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Contact</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Service</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Date & Time</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Pet Details</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php foreach ($appointments as $appointment): ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    <?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?>
                                                </div>
                                                <div class="text-xs text-gray-500"><?php echo $appointment['email']; ?></div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm text-gray-900"><?php echo $appointment['phone']; ?></div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo $appointment['service_name']; ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo formatDate($appointment['appointment_date']); ?>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?php echo formatTime($appointment['appointment_time']); ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo $appointment['pet_name'] ?? 'N/A'; ?>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?php echo $appointment['pet_type'] ?? ''; ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-semibold text-blue-600">
                                                    PHP <?php echo number_format($appointment['service_price'], 2); ?></div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-right text-sm font-bold text-gray-900">Total
                                            Revenue:</td>
                                        <td class="px-4 py-3">
                                            <div class="text-lg font-bold text-green-600">
                                                PHP <?php echo number_format($total_revenue, 2); ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Prepared By Section (Print Only) -->
                    <div class="print-prepared-by mt-8 text-right text-sm" style="display: none;">
                        <div>Prepared by:</div>
                        <div style="border-top: 1px solid #333; width: 200px; margin-left: auto; margin-top: 40px;"></div>
                        <div><?php echo $_SESSION['admin_name'] ?? 'System Administrator'; ?></div>
                    </div>

                <?php elseif ($report_type == 'sales'): ?>
                    <?php
                    // Map category values to readable labels
                    $categoryLabels = [
                        'all' => 'All Sales',
                        '1' => 'Pet Food',
                        '2' => 'Pet Accessories',
                        '3' => 'Pet Milk',
                        '4' => 'Pet Shampoo',
                        '5' => 'Pet Treats',
                        '6' => 'Pet Apparels'
                    ];

                    if ($category != 'all') {
                        $sql = "SELECT o.id as order_id, o.order_date,
                                p.name as product_name,
                                c.name as category_name,
                                oi.quantity,
                                oi.price,
                                (oi.quantity * oi.price) as total_amount
                                FROM orders o
                                JOIN order_items oi ON o.id = oi.order_id
                                JOIN products p ON oi.product_id = p.id
                                LEFT JOIN product_categories c ON p.category_id = c.id
                                WHERE o.status = 'confirmed'
                                AND o.order_date BETWEEN ? AND ?
                                AND p.category_id = ?
                                ORDER BY o.order_date DESC, o.id, p.name";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$start_date, $end_date, $category]);
                    } else {
                        $sql = "SELECT o.id as order_id, o.order_date,
                                p.name as product_name,
                                c.name as category_name,
                                oi.quantity,
                                oi.price,
                                (oi.quantity * oi.price) as total_amount
                                FROM orders o
                                JOIN order_items oi ON o.id = oi.order_id
                                JOIN products p ON oi.product_id = p.id
                                LEFT JOIN product_categories c ON p.category_id = c.id
                                WHERE o.status = 'confirmed'
                                AND o.order_date BETWEEN ? AND ?
                                ORDER BY o.order_date DESC, o.id, p.name";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$start_date, $end_date]);
                    }

                    $sales_data = $stmt->fetchAll();

                    // Calculate summary
                    $unique_orders = array_unique(array_column($sales_data, 'order_id'));
                    $total_orders = count($unique_orders);
                    $total_revenue = array_sum(array_column($sales_data, 'total_amount'));
                    $total_items = array_sum(array_column($sales_data, 'quantity'));
                    ?>

                    <div class="mb-6 no-print">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Product Sales Report</h2>
                        <p class="text-gray-600">Sales overview from <?php echo formatDate($start_date); ?> to
                            <?php echo formatDate($end_date); ?>
                        </p>
                        <?php if ($category != 'all'): ?>
                            <p class="text-gray-600">Category: <?php echo htmlspecialchars($categoryLabels[$category] ?? 'Unknown'); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Summary Box -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-semibold text-blue-600 mb-3">Sales Summary</h3>
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div><strong>Total Orders:</strong> <?php echo $total_orders; ?></div>
                            <div><strong>Items Sold:</strong> <?php echo $total_items; ?></div>
                            <div><strong>Total Revenue:</strong> PHP <?php echo number_format($total_revenue, 2); ?></div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-600">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Order Details</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Product</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Quantity & Price</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Category</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php foreach ($sales_data as $sale): ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    #<?php echo str_pad($sale['order_id'], 5, '0', STR_PAD_LEFT); ?></div>
                                                <div class="text-xs text-gray-500">Order ID</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo formatDate($sale['order_date']); ?>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?php echo date('l', strtotime($sale['order_date'])); ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($sale['product_name']); ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo $sale['quantity']; ?> × PHP <?php echo number_format($sale['price'], 2); ?>
                                                </div>
                                                <div class="text-xs text-gray-500">Unit Price</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm text-gray-900">
                                                    <?php echo $sale['category_name'] ?? 'Uncategorized'; ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-semibold text-blue-600">
                                                    PHP <?php echo number_format($sale['total_amount'], 2); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-right text-sm font-bold text-gray-900">Total Sales:</td>
                                        <td class="px-4 py-3">
                                            <div class="text-lg font-bold text-green-600">
                                                PHP <?php echo number_format($total_revenue, 2); ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Prepared By Section (Print Only) -->
                    <div class="print-prepared-by mt-8 text-right text-sm">
                        <div>Prepared by:</div>
                        <div style="border-top: 1px solid #333; width: 200px; margin-left: auto; margin-top: 40px;"></div>
                        <div><?php echo $_SESSION['admin_name'] ?? 'System Administrator'; ?></div>
                    </div>

                <?php elseif ($report_type == 'orders'): ?>
                    <?php
                    // Get all orders within the date range
                    $sql = "SELECT o.*, u.first_name, u.last_name, u.email, u.phone
                            FROM orders o
                            JOIN users u ON o.user_id = u.id
                            WHERE o.status IN ('confirmed', 'shipped', 'completed')
                            AND o.order_date BETWEEN ? AND ?
                            ORDER BY o.order_date DESC, o.id DESC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$start_date, $end_date]);
                    $orders_report = $stmt->fetchAll();

                    // Calculate summary
                    $total_orders = count($orders_report);
                    $total_revenue = array_sum(array_column($orders_report, 'total_amount'));
                    ?>

                    <div class="mb-6 no-print">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Order Reports</h2>
                        <p class="text-gray-600">Complete order overview from <?php echo formatDate($start_date); ?> to
                            <?php echo formatDate($end_date); ?>
                        </p>
                    </div>

                    <!-- Summary Box -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-semibold text-blue-600 mb-3">Order Summary</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><strong>Total Orders:</strong> <?php echo $total_orders; ?></div>
                            <div><strong>Total Revenue:</strong> PHP <?php echo number_format($total_revenue, 2); ?></div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-600">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Order ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Customer</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Order Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Items</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php foreach ($orders_report as $order):
                                        // Get order items count
                                        $countStmt = $pdo->prepare("SELECT COUNT(*) as count FROM order_items WHERE order_id = ?");
                                        $countStmt->execute([$order['id']]);
                                        $item_count = $countStmt->fetch()['count'];
                                    ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    <?php echo $order['first_name'] . ' ' . $order['last_name']; ?>
                                                </div>
                                                <div class="text-xs text-gray-500"><?php echo $order['email']; ?></div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo formatDate($order['order_date']); ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-sm text-gray-900">
                                                    <?php echo $item_count; ?> item<?php echo $item_count != 1 ? 's' : ''; ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-sm text-gray-900">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-semibold text-blue-600">
                                                    PHP <?php echo number_format($order['total_amount'], 2); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-right text-sm font-bold text-gray-900">Total Revenue:</td>
                                        <td class="px-4 py-3">
                                            <div class="text-lg font-bold text-green-600">
                                                PHP <?php echo number_format($total_revenue, 2); ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-right text-sm font-bold text-gray-900">Total Orders:</td>
                                        <td class="px-4 py-3">
                                            <div class="text-lg font-bold text-blue-600">
                                                <?php echo $total_orders; ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Prepared By Section (Print Only) -->
                    <div class="print-prepared-by mt-8 text-right text-sm">
                        <div>Prepared by:</div>
                        <div style="border-top: 1px solid #333; width: 200px; margin-left: auto; margin-top: 40px;"></div>
                        <div><?php echo $_SESSION['admin_name'] ?? 'System Administrator'; ?></div>
                    </div>
                <?php endif; ?>

                <!-- Print Footer -->
                <div class="print-footer">
                    <p>This report was generated automatically by the Furcare Management System.<br>
                    Generated on <?php echo date('F j, Y \a\t g:i A'); ?> | Confidential Business Information</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>