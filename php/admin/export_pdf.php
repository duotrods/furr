<?php
header('Content-Type: text/html; charset=UTF-8');
require_once '../../includes/config.php';
require_once '../../vendor/autoload.php'; // adjust if your autoload path differs
requireAdmin();

use Dompdf\Dompdf;
use Dompdf\Options;

$report_type = $_GET['type'] ?? 'appointments';
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');
$category = $_GET['category'] ?? 'all';

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Professional CSS styling
$css = "
<style>
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        margin: 0;
        padding: 20px;
        color: #333;
        background-color: #fff;
        line-height: 1.6;
    }
    
    .header {
        text-align: center;
        margin-bottom: 15px;
        padding-bottom: 20px;
        border-bottom: 3px solid #2c5aa0;
    }
    
    .header-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }
    
     .logo {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .logo img {
        width: 50px;
        height: 50px;
        object-fit: contain;
    }
    
    .company-name {
        font-size: 28px;
        font-weight: bold;
        color: #2c5aa0;
        margin: 0;
        letter-spacing: 1px;
    }
    
    .report-title {
        font-size: 22px;
        color: #444;
        margin: 10px 0 5px 0;
        font-weight: 600;
    }
    
    .date-range {
        font-size: 14px;
        color: #666;
        margin: 5px 0 0 0;
        font-style: italic;
    }
    
    .report-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 25px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #2c5aa0;
    }
    
    .meta-item {
        font-size: 12px;
        color: #555;
    }
    
    .meta-label {
        font-weight: bold;
        color: #2c5aa0;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
    }
    
    th {
        background-color: #2c5aa0 !important;
        background: #2c5aa0 !important;
        color: white !important;
        padding: 15px 10px;
        text-align: center;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }
    
    td {
        padding: 12px 10px;
        border-bottom: 1px solid #e9ecef;
        font-size: 11px;
        vertical-align: top;
    }
    
    tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    tr:hover {
        background-color: #e3f2fd;
    }
    
    .amount {
        font-weight: 600;
        color: #2c5aa0;
        text-align: right;
    }
    
    .status {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        text-align: center;
    }
    
    .status-confirmed {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
        text-align: center;
        font-size: 10px;
        color: #6c757d;
    }
    
    .summary-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .summary-title {
        font-size: 14px;
        font-weight: 600;
        color: #2c5aa0;
        margin-bottom: 10px;
    }
</style>
";

// Start building HTML content
$html = $css;

$html .= "<div class='header'>";
$html .= "<div class='header-content'>";
$html .= "<div class='logo'><img src='http://localhost/furr/assets/images/logo.png' alt='Furcare Logo'></div>";
$html .= "<h1 class='company-name'>FURCARE</h1>";
$html .= "</div>";
$html .= "<h2 class='report-title'>" . ucfirst($report_type) . " Report</h2>";
$html .= "<p class='date-range'>Date Range: " . formatDate($start_date) . " to " . formatDate($end_date) . "</p>";
$html .= "</div>";


$html .= "</div>";

if ($report_type === 'appointments') {
    $stmt = $pdo->prepare("SELECT a.*, s.name as service_name, s.price as service_price, 
                          u.first_name, u.last_name, u.email, u.phone 
                          FROM appointments a 
                          JOIN services s ON a.service_id = s.id 
                          JOIN users u ON a.user_id = u.id 
                          WHERE a.status = 'completed' 
                         AND a.appointment_date BETWEEN ? AND ?
                       ORDER BY a.appointment_date DESC, a.appointment_time DESC");
    $stmt->execute([$start_date, $end_date]);
    $data = $stmt->fetchAll();

    // Summary box
    $total_appointments = count($data);
    $total_revenue = array_sum(array_column($data, 'service_price'));
    $confirmed_count = count(array_filter($data, function ($row) {
        return $row['status'] === 'confirmed';
    }));

    $html .= "<div class='summary-box'>";
    $html .= "<div class='summary-title'>Appointment Summary</div>";
    $html .= "<div style='display: flex; justify-content: space-between;'>";
    $html .= "<div><strong>Total Appointments:</strong> {$total_appointments}</div>";
    $html .= "<div><strong>Confirmed:</strong> {$confirmed_count}</div>";
    $html .= "<div><strong>Total Revenue:</strong> PHP " . number_format($total_revenue, 2) . "</div>";
    $html .= "</div>";
    $html .= "</div>";

    $html .= "<table>";
    $html .= "<tr>
                <th>Customer</th><th>Contact</th><th>Service</th>
                <th>Date & Time</th><th>Pet Details</th>
                <th>Amount</th>
              </tr>";

    foreach ($data as $row) {
        $status_class = 'status-' . strtolower($row['status']);
        $html .= "<tr>
                    <td><strong>{$row['first_name']} {$row['last_name']}</strong><br>
                        <small style='color: #666;'>{$row['email']}</small></td>
                    <td>{$row['phone']}</td>
                    <td><strong>{$row['service_name']}</strong></td>
                    <td>" . formatDate($row['appointment_date']) . "<br>
                        <small>" . formatTime($row['appointment_time']) . "</small></td>
                    <td><strong>{$row['pet_name']}</strong><br>
                        <small style='color: #666;'>{$row['pet_type']}</small></td>
                    <td class='amount'>PHP " . number_format($row['service_price'], 2) . "</td>
                  </tr>";
    }
} elseif ($report_type === 'sales') {
    $html .= "<div class='mb-6'>";
    $html .= "<h2 class='text-2xl font-bold text-gray-900 mb-2'>Product Sales Report</h2>";
    $html .= "<p class='text-gray-600'>Sales overview from " . formatDate($start_date) . " to " . formatDate($end_date) . "</p>";

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
        $categoryLabel = $categoryLabels[$category] ?? 'Unknown';
        $html .= "<p class='text-gray-600'>Category: " . htmlspecialchars($categoryLabel) . "</p>";
    }
    $html .= "</div>";
    // Fetch and display sales data with enhanced design matching appointments
    if ($category != 'all') {
        // When filtering by category, show individual products from that category
        $sql = "SELECT o.id as order_id, o.order_date,
                p.name as product_name,
                p.category_id,
                c.name as category_name,
                oi.quantity,
                oi.price,
                (oi.quantity * oi.price) as total_amount,
                o.status
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
        // When showing all categories, show individual products
        $sql = "SELECT o.id as order_id, o.order_date,
                p.name as product_name,
                p.category_id,
                c.name as category_name,
                oi.quantity,
                oi.price,
                (oi.quantity * oi.price) as total_amount,
                o.status
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

    // Calculate summary data - matching appointments structure
    $unique_orders = array_unique(array_column($sales_data, 'order_id'));
    $total_orders = count($unique_orders);
    $total_revenue = array_sum(array_column($sales_data, 'total_amount'));
    $total_items = array_sum(array_column($sales_data, 'quantity'));

    // Summary box - matching appointments design exactly
    $html .= "<div class='summary-box'>";
    $html .= "<div class='summary-title'>Sales Summary</div>";
    $html .= "<div style='display: flex; justify-content: space-between;'>";
    $html .= "<div><strong>Total Orders:</strong> {$total_orders}</div>";
    $html .= "<div><strong>Items Sold:</strong> {$total_items}</div>";
    $html .= "<div><strong>Total Revenue:</strong> PHP" . number_format($total_revenue, 2) . "</div>";
    $html .= "</div>";
    $html .= "</div>";

    // Add the table wrapper with proper styling
    $html .= "<div class='bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm'>";
    $html .= "<div class='overflow-x-auto'>";
    $html .= "<table class='min-w-full divide-y divide-gray-200'>";

    // Table header
    $html .= "<thead class='bg-gray-50'>";
    $html .= "<tr>";
    $html .= "<th class='px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider'>Order Details</th>";
    $html .= "<th class='px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider'>Date</th>";
    $html .= "<th class='px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider'>Product</th>";
    $html .= "<th class='px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider'>Quantity & Price</th>";
    $html .= "<th class='px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider'>Category</th>";
    $html .= "<th class='px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider'>Amount</th>";
    $html .= "</tr>";
    $html .= "</thead>";

    // Table body
    $html .= "<tbody class='bg-white divide-y divide-gray-100'>";
    foreach ($sales_data as $sale) {
        $html .= "<tr class='hover:bg-gray-50 transition-colors'>";
        $html .= "<td class='px-6 py-4'>";
        $html .= "<div class='text-sm font-semibold text-gray-900'>#" . str_pad($sale['order_id'], 5, '0', STR_PAD_LEFT) . "</div>";
        $html .= "<small class='text-gray-500'>Order ID</small>";
        $html .= "</td>";
        $html .= "<td class='px-6 py-4'>";
        $html .= "<div class='text-sm font-medium text-gray-900'>" . formatDate($sale['order_date']) . "</div>";
        $html .= "<small class='text-gray-500'>" . date('l', strtotime($sale['order_date'])) . "</small>";
        $html .= "</td>";
        $html .= "<td class='px-6 py-4'>";
        $html .= "<div class='text-sm font-medium text-gray-900'>" . htmlspecialchars($sale['product_name']) . "</div>";
        $html .= "</td>";
        $html .= "<td class='px-6 py-4'>";
        $html .= "<div class='text-sm font-medium text-gray-900'>{$sale['quantity']} × PHP" . number_format($sale['price'], 2) . "</div>";
        $html .= "<small class='text-gray-500'>Unit Price</small>";
        $html .= "</td>";
        $html .= "<td class='px-6 py-4'>";
        $html .= "<div class='text-sm font-medium text-gray-900'>" . ($sale['category_name'] ?? 'Uncategorized') . "</div>";
        $html .= "<small class='text-gray-500'>Category</small>";
        $html .= "</td>";
        $html .= "<td class='px-6 py-4'>";
        $html .= "<div class='text-sm font-semibold text-gray-900'>PHP" . number_format($sale['total_amount'], 2) . "</div>";
        $html .= "</td>";
        $html .= "</tr>";
    }
    $html .= "</tbody>";

    // Table footer with total
    $html .= "<tfoot class='bg-gray-50'>";
    $html .= "<tr>";
    $html .= "<td colspan='5' class='px-6 py-4 text-right text-sm font-bold text-gray-900'>Total Sales:</td>";
    $html .= "<td class='px-6 py-4'>";
    $html .= "<div class='text-lg font-bold text-green-600'>PHP" . number_format($total_revenue, 2) . "</div>";
    $html .= "</td>";
    $html .= "</tr>";
    $html .= "</tfoot>";

    $html .= "</table>";
    $html .= "</div>";
    $html .= "</div>";
} elseif ($report_type === 'customers') {
    $stmt = $pdo->prepare("SELECT u.*, 
                          COUNT(a.id) as appointment_count,
                          COUNT(o.id) as order_count,
                          IFNULL(SUM(o.total_amount), 0) as total_spent
                          FROM users u
                          LEFT JOIN appointments a ON u.id = a.user_id
                          LEFT JOIN orders o ON u.id = o.user_id
                          WHERE u.created_at BETWEEN ? AND ?
                          GROUP BY u.id
                          ORDER BY total_spent DESC");
    $stmt->execute([$start_date, $end_date]);
    $data = $stmt->fetchAll();

    // Summary box
    $total_customers = count($data);
    $total_revenue = array_sum(array_column($data, 'total_spent'));
    $avg_spent = $total_customers > 0 ? $total_revenue / $total_customers : 0;

    $html .= "<div class='summary-box'>";
    $html .= "<div class='summary-title'>Customer Summary</div>";
    $html .= "<div style='display: flex; justify-content: space-between;'>";
    $html .= "<div><strong>Total Customers:</strong> {$total_customers}</div>";
    $html .= "<div><strong>Average Spent:</strong> PHP " . number_format($avg_spent, 2) . "</div>";
    $html .= "<div><strong>Total Revenue:</strong> PHP " . number_format($total_revenue, 2) . "</div>";
    $html .= "</div>";
    $html .= "</div>";

    $html .= "<table>";
    $html .= "<tr>
                <th>Customer</th><th>Contact Information</th><th>Address</th>
                <th>Appointments</th><th>Orders</th><th>Total Spent</th>
              </tr>";

    foreach ($data as $row) {
        $html .= "<tr>
                    <td><strong>{$row['first_name']} {$row['last_name']}</strong></td>
                    <td>{$row['email']}<br><small>{$row['phone']}</small></td>
                    <td><small>{$row['address']}</small></td>
                    <td style='text-align: center;'>{$row['appointment_count']}</td>
                    <td style='text-align: center;'>{$row['order_count']}</td>
                    <td class='amount'>PHP " . number_format($row['total_spent'], 2) . "</td>
                  </tr>";
    }
}

$html .= "</table>";

$html .= "<div class='footer'>";
$html .= "<p>This report was generated automatically by the Furcare Management System.<br>";
$html .= "Generated on " . date('F j, Y \a\t g:i A') . " | Confidential Business Information</p>";
$html .= "</div>";

// Load and render PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait'); // Set paper size and orientation
$dompdf->render();

// Output PDF to browser
$dompdf->stream("furcare_report_{$report_type}_" . date('Ymd') . ".pdf", ["Attachment" => true]);
exit();
