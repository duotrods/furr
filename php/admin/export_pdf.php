<?php
header('Content-Type: text/html; charset=UTF-8');
require_once '../../includes/config.php';
require_once '../../vendor/autoload.php'; // adjust if your autoload path differs
requireAdmin();
use Dompdf\Dompdf;
use Dompdf\Options;

// Get current admin name
$admin_name = $_SESSION['admin_name'] ?? 'System Administrator';

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

    /* Header Layout - Centered Structure */
    .header {
        margin-bottom: 15px;
        padding-bottom: 20px;
        border-bottom: 3px solid #2c5aa0;
        text-align: center;
    }
    
    .logo-container {
        width: 80px;
        height: 80px;
        margin: 0 auto 15px;
    }
    
    .logo {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .logo-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background-color: #2c5aa0;
        color: white;
        font-weight: bold;
        border-radius: 50%;
        font-size: 24px;
    }
    
    .contact-info {
        font-size: 12px;
        color: #555;
        line-height: 1.4;
        margin: 0 auto 15px;
        max-width: 300px;
    }
    
    .company-name {
        font-size: 28px;
        font-weight: bold;
        color: #2c5aa0;
        margin: 0;
        letter-spacing: 1px;
    }

    /* Report Title Section */
    .report-title {
        font-size: 22px;
        color: #444;
        margin: 20px 0 5px 0;
        font-weight: 600;
        text-align: center;
    }

    .date-range {
        font-size: 14px;
        color: #666;
        margin: 5px 0 0 0;
        font-style: italic;
        text-align: center;
    }

    /* Report Meta Information */
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

    /* Table Styling */
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

    /* Special Cell Styles */
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

    /* Footer Section */
    .footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
        text-align: right;
        font-size: 10px;
        color: #6c757d;
    }

    /* Summary Box */
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

    /* Prepared By Section */
    .prepared-by {
        margin-top: 30px;
        text-align: right;
        font-size: 12px;
    }

    .signature-line {
        border-top: 1px solid #333;
        width: 200px;
        margin-left: auto;
        margin-top: 40px;
    }
</style>
";

// Start building HTML content
$html = $css;
$html .= "<div class='header'>";

// Logo at the top (centered)
$html .= "<div class='logo-container'>";
$logoPath = '../../assets/images/logo.png';
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/furr/assets/images/logo.png')) {
    $html .= "<img class='logo' src='http://" . $_SERVER['HTTP_HOST'] . "/furr/assets/images/logo.png' alt='Furcare Logo'>";
} else {
    $html .= "<div class='logo-fallback'>FC</div>";
}
$html .= "</div>";

// Company name (centered)
$html .= "<h1 class='company-name'>FURCARE</h1>";

// Contact info (centered)
$html .= "<div class='contact-info'>";
$html .= "Mabitad Sto. Nino, Panabo City, Davao del Norte<br>";
$html .= "Email: panabopetgrooming@gmail.com<br>";
$html .= "Contact: +639700249877";
$html .= "</div>";

$html .= "</div>"; // end header

// Report title and date range
$html .= "<h2 class='report-title'>" . ucfirst($report_type) . " Report</h2>";
$html .= "<p class='date-range'>Date Range: " . formatDate($start_date) . " to " . formatDate($end_date) . "</p>";

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
    $html .= "<div class='summary-box'>";
    $html .= "<h2 class='summary-title'>Product Sales Report</h2>";
    $html .= "<p>Sales overview from " . formatDate($start_date) . " to " . formatDate($end_date) . "</p>";

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
        $html .= "<p>Category: " . htmlspecialchars($categoryLabel) . "</p>";
    }
    $html .= "</div>";

    // Fetch sales data
    if ($category != 'all') {
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

    // Calculate summary data
    $unique_orders = array_unique(array_column($sales_data, 'order_id'));
    $total_orders = count($unique_orders);
    $total_revenue = array_sum(array_column($sales_data, 'total_amount'));
    $total_items = array_sum(array_column($sales_data, 'quantity'));

    $html .= "<div class='summary-box'>";
    $html .= "<div class='summary-title'>Sales Summary</div>";
    $html .= "<div style='display: flex; justify-content: space-between;'>";
    $html .= "<div><strong>Total Orders:</strong> {$total_orders}</div>";
    $html .= "<div><strong>Items Sold:</strong> {$total_items}</div>";
    $html .= "<div><strong>Total Revenue:</strong> PHP" . number_format($total_revenue, 2) . "</div>";
    $html .= "</div>";
    $html .= "</div>";

    $html .= "<table>";
    $html .= "<tr>
                <th>Order Details</th>
                <th>Date</th>
                <th>Product</th>
                <th>Quantity & Price</th>
                <th>Category</th>
                <th>Amount</th>
              </tr>";

    foreach ($sales_data as $sale) {
        $html .= "<tr>
                    <td><strong>#" . str_pad($sale['order_id'], 5, '0', STR_PAD_LEFT) . "</strong><br>
                        <small class='text-gray-500'>Order ID</small></td>
                    <td>" . formatDate($sale['order_date']) . "<br>
                        <small>" . date('l', strtotime($sale['order_date'])) . "</small></td>
                    <td><strong>" . htmlspecialchars($sale['product_name']) . "</strong></td>
                    <td>{$sale['quantity']} × PHP" . number_format($sale['price'], 2) . "<br>
                        <small>Unit Price</small></td>
                    <td>" . ($sale['category_name'] ?? 'Uncategorized') . "<br>
                        <small>Category</small></td>
                    <td class='amount'>PHP" . number_format($sale['total_amount'], 2) . "</td>
                  </tr>";
    }

    $html .= "<tr>
                <td colspan='5' style='text-align: right; font-weight: bold;'>Total Sales:</td>
                <td class='amount' style='font-weight: bold;'>PHP" . number_format($total_revenue, 2) . "</td>
              </tr>";
    $html .= "</table>";
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

// Prepared by section
$html .= "<div class='prepared-by'>";
$html .= "<div>Prepared by:</div>";
$html .= "<div class='signature-line'></div>";
$html .= "<div>{$admin_name}</div>";
$html .= "</div>";

// Footer
$html .= "<div class='footer'>";
$html .= "<p>This report was generated automatically by the Furcare Management System.<br>";
$html .= "Generated on " . date('F j, Y \a\t g:i A') . " | Confidential Business Information</p>";
$html .= "</div>";

// Load and render PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output PDF to browser
$dompdf->stream("furcare_report_{$report_type}_" . date('Ymd') . ".pdf", ["Attachment" => true]);
exit();