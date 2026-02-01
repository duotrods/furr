<?php
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
        line-height: 1.4;
        font-size: 11px;
    }

    /* Top Header Row */
    .top-header {
        display: table;
        width: 100%;
        margin-bottom: 10px;
        font-size: 10px;
        color: #666;
    }

    .top-header-left {
        display: table-cell;
        text-align: left;
    }

    .top-header-right {
        display: table-cell;
        text-align: right;
        color: #2c5aa0;
        font-weight: 600;
    }

    /* Header Layout - Centered Structure */
    .header {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 2px solid #2c5aa0;
        text-align: center;
    }

    .company-name {
        font-size: 28px;
        font-weight: bold;
        color: #2c5aa0;
        margin: 10px 0 5px 0;
        letter-spacing: 2px;
    }

    .contact-info {
        font-size: 11px;
        color: #555;
        line-height: 1.4;
        margin: 0 auto 10px;
    }

    /* Report Title Section */
    .report-title {
        font-size: 18px;
        color: #333;
        margin: 15px 0 5px 0;
        font-weight: 600;
        text-align: center;
    }

    .date-range {
        font-size: 12px;
        color: #666;
        margin: 5px 0 15px 0;
        font-style: italic;
        text-align: center;
    }

    /* Summary Box */
    .summary-box {
        border: 1px solid #333;
        padding: 10px 15px;
        margin-bottom: 15px;
    }

    .summary-title {
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .summary-content {
        display: table;
        width: 100%;
    }

    .summary-item {
        display: table-cell;
        font-size: 11px;
    }

    /* Table Styling with Visible Borders */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        border: 1px solid #333;
    }

    th {
        background-color: #e8e8e8 !important;
        color: #333 !important;
        padding: 10px 8px;
        text-align: left;
        font-weight: bold;
        font-size: 10px;
        text-transform: uppercase;
        border: 1px solid #333;
    }

    td {
        padding: 8px;
        border: 1px solid #333;
        font-size: 11px;
        vertical-align: top;
    }

    /* Special Cell Styles */
    .amount {
        font-weight: bold;
        text-align: left;
    }

    /* Total Row */
    .total-row td {
        font-weight: bold;
        border: 1px solid #333;
        padding: 10px 8px;
    }

    /* Prepared By Section */
    .prepared-by {
        margin-top: 40px;
        text-align: right;
        font-size: 11px;
    }

    .signature-line {
        border-top: 1px solid #333;
        width: 200px;
        margin-left: auto;
        margin-top: 40px;
        margin-bottom: 5px;
    }

    /* Footer Section */
    .footer {
        margin-top: 30px;
        text-align: center;
        font-size: 10px;
        color: #666;
    }
</style>
";

// Start building HTML content
$html = $css;

// Top header row with date/time on left and business name on right
$html .= "<div class='top-header'>";
$html .= "<div class='top-header-left'>" . date('n/j/y, g:i A') . "</div>";
$html .= "<div class='top-header-right'>FurCare Pet Grooming</div>";
$html .= "</div>";

$html .= "<div class='header'>";

// Company name (centered)
$html .= "<h1 class='company-name'>FURCARE</h1>";

// Contact info (centered)
$html .= "<div class='contact-info'>";
$html .= "Mabitad Sto. Nino, Panabo City, Davao del Norte<br>";
$html .= "Email: panabopetgrooming@gmail.com | Contact: +639700249877";
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
    $html .= "<div class='summary-content'>";
    $html .= "<div class='summary-item'><strong>Total Appointments:</strong> {$total_appointments}</div>";
    $html .= "<div class='summary-item'><strong>Confirmed:</strong> {$confirmed_count}</div>";
    $html .= "<div class='summary-item'><strong>Total Revenue:</strong> PHP " . number_format($total_revenue, 2) . "</div>";
    $html .= "</div>";
    $html .= "</div>";

    $html .= "<table>";
    $html .= "<tr>
                <th>CUSTOMER</th><th>CONTACT</th><th>SERVICE</th>
                <th>DATE & TIME</th><th>PET DETAILS</th>
                <th>AMOUNT</th>
              </tr>";

    foreach ($data as $row) {
        $html .= "<tr>
                    <td><strong>{$row['first_name']} {$row['last_name']}</strong><br>
                        <span style='font-size: 10px; color: #666;'>{$row['email']}</span></td>
                    <td>{$row['phone']}</td>
                    <td>{$row['service_name']}</td>
                    <td>" . formatDate($row['appointment_date']) . "<br>
                        <span style='font-size: 10px;'>" . formatTime($row['appointment_time']) . "</span></td>
                    <td><strong>{$row['pet_name']}</strong><br>
                        <span style='font-size: 10px; color: #666;'>{$row['pet_type']}</span></td>
                    <td class='amount'>PHP " . number_format($row['service_price'], 2) . "</td>
                  </tr>";
    }

    // Total Revenue row
    $html .= "<tr class='total-row'>
                <td colspan='5' style='text-align: right;'>Total Revenue:</td>
                <td class='amount'>PHP " . number_format($total_revenue, 2) . "</td>
              </tr>";
} elseif ($report_type === 'sales') {
    $categoryLabels = [
        'all' => 'All Sales',
        '1' => 'Pet Food',
        '2' => 'Pet Accessories',
        '3' => 'Pet Milk',
        '4' => 'Pet Shampoo',
        '5' => 'Pet Treats',
        '6' => 'Pet Apparels'
    ];

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
    $html .= "<div class='summary-content'>";
    $html .= "<div class='summary-item'><strong>Total Orders:</strong> {$total_orders}</div>";
    $html .= "<div class='summary-item'><strong>Items Sold:</strong> {$total_items}</div>";
    $html .= "<div class='summary-item'><strong>Total Revenue:</strong> PHP " . number_format($total_revenue, 2) . "</div>";
    $html .= "</div>";
    $html .= "</div>";

    $html .= "<table>";
    $html .= "<tr>
                <th>ORDER DETAILS</th>
                <th>DATE</th>
                <th>PRODUCT</th>
                <th>QUANTITY & PRICE</th>
                <th>CATEGORY</th>
                <th>AMOUNT</th>
              </tr>";

    foreach ($sales_data as $sale) {
        $html .= "<tr>
                    <td><strong>#" . str_pad($sale['order_id'], 5, '0', STR_PAD_LEFT) . "</strong><br>
                        <span style='font-size: 10px; color: #666;'>Order ID</span></td>
                    <td>" . formatDate($sale['order_date']) . "<br>
                        <span style='font-size: 10px;'>" . date('l', strtotime($sale['order_date'])) . "</span></td>
                    <td>" . htmlspecialchars($sale['product_name']) . "</td>
                    <td>{$sale['quantity']} × PHP " . number_format($sale['price'], 2) . "<br>
                        <span style='font-size: 10px; color: #666;'>Unit Price</span></td>
                    <td>" . ($sale['category_name'] ?? 'Uncategorized') . "</td>
                    <td class='amount'>PHP " . number_format($sale['total_amount'], 2) . "</td>
                  </tr>";
    }

    $html .= "<tr class='total-row'>
                <td colspan='5' style='text-align: right;'>Total Sales:</td>
                <td class='amount'>PHP " . number_format($total_revenue, 2) . "</td>
              </tr>";
} elseif ($report_type === 'orders') {
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

    $html .= "<div class='summary-box'>";
    $html .= "<div class='summary-title'>Order Summary</div>";
    $html .= "<div class='summary-content'>";
    $html .= "<div class='summary-item'><strong>Total Orders:</strong> {$total_orders}</div>";
    $html .= "<div class='summary-item'><strong>Total Revenue:</strong> PHP " . number_format($total_revenue, 2) . "</div>";
    $html .= "</div>";
    $html .= "</div>";

    $html .= "<table>";
    $html .= "<tr>
                <th>ORDER ID</th><th>CUSTOMER</th><th>ORDER DATE</th>
                <th>ITEMS</th><th>STATUS</th><th>TOTAL AMOUNT</th>
              </tr>";

    foreach ($orders_report as $order) {
        // Get order items count
        $countStmt = $pdo->prepare("SELECT COUNT(*) as count FROM order_items WHERE order_id = ?");
        $countStmt->execute([$order['id']]);
        $item_count = $countStmt->fetch()['count'];

        $html .= "<tr>
                    <td><strong>#" . str_pad($order['id'], 5, '0', STR_PAD_LEFT) . "</strong></td>
                    <td><strong>{$order['first_name']} {$order['last_name']}</strong><br>
                        <span style='font-size: 10px; color: #666;'>{$order['email']}</span></td>
                    <td>" . formatDate($order['order_date']) . "</td>
                    <td>{$item_count} item" . ($item_count != 1 ? 's' : '') . "</td>
                    <td>" . ucfirst($order['status']) . "</td>
                    <td class='amount'>PHP " . number_format($order['total_amount'], 2) . "</td>
                  </tr>";
    }

    $html .= "<tr class='total-row'>
                <td colspan='5' style='text-align: right;'>Total Revenue:</td>
                <td class='amount'>PHP " . number_format($total_revenue, 2) . "</td>
              </tr>";
    $html .= "<tr class='total-row'>
                <td colspan='5' style='text-align: right;'>Total Orders:</td>
                <td class='amount'>{$total_orders}</td>
              </tr>";
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
    $html .= "<div class='summary-content'>";
    $html .= "<div class='summary-item'><strong>Total Customers:</strong> {$total_customers}</div>";
    $html .= "<div class='summary-item'><strong>Average Spent:</strong> PHP " . number_format($avg_spent, 2) . "</div>";
    $html .= "<div class='summary-item'><strong>Total Revenue:</strong> PHP " . number_format($total_revenue, 2) . "</div>";
    $html .= "</div>";
    $html .= "</div>";

    $html .= "<table>";
    $html .= "<tr>
                <th>CUSTOMER</th><th>CONTACT INFORMATION</th><th>ADDRESS</th>
                <th>APPOINTMENTS</th><th>ORDERS</th><th>TOTAL SPENT</th>
              </tr>";

    foreach ($data as $row) {
        $html .= "<tr>
                    <td><strong>{$row['first_name']} {$row['last_name']}</strong></td>
                    <td>{$row['email']}<br><span style='font-size: 10px;'>{$row['phone']}</span></td>
                    <td><span style='font-size: 10px;'>{$row['address']}</span></td>
                    <td>{$row['appointment_count']}</td>
                    <td>{$row['order_count']}</td>
                    <td class='amount'>PHP " . number_format($row['total_spent'], 2) . "</td>
                  </tr>";
    }
}

$html .= "</table>";

// Prepared by section (signature line only)
$html .= "<div class='prepared-by'>";
$html .= "<div class='signature-line'></div>";
$html .= "<div>{$admin_name}</div>";
$html .= "</div>";

// Footer
$html .= "<div class='footer'>";
$html .= "This report was generated automatically by the Furcare Management System.<br>";
$html .= "Generated on " . date('F j, Y \a\t g:i A') . " | Confidential Business Information";
$html .= "</div>";

// Load and render PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Output PDF to browser
// If view=true parameter is passed, display in browser; otherwise download
$view_in_browser = isset($_GET['view']) && $_GET['view'] === 'true';
$dompdf->stream("furcare_report_{$report_type}_" . date('Ymd') . ".pdf", ["Attachment" => !$view_in_browser]);
exit();