<?php
require_once '../../includes/config.php';
require_once '../../vendor/autoload.php'; // adjust if your autoload path differs
requireAdmin();

use Dompdf\Dompdf;
use Dompdf\Options;

$report_type = $_GET['type'] ?? 'appointments';
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');

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
  $stmt = $pdo->prepare("SELECT o.*, u.first_name, u.last_name, u.email, 
                          COUNT(oi.id) as item_count, SUM(oi.quantity) as total_quantity
                          FROM orders o
                          JOIN order_items oi ON o.id = oi.order_id
                          JOIN users u ON o.user_id = u.id
                          WHERE o.status = 'confirmed'
                         AND o.order_date BETWEEN ? AND ?
                          GROUP BY o.id
                          ORDER BY o.order_date DESC");
  $stmt->execute([$start_date, $end_date]);
  $data = $stmt->fetchAll();

  // Summary box
  $total_orders = count($data);
  $total_revenue = array_sum(array_column($data, 'total_amount'));
  $total_items = array_sum(array_column($data, 'total_quantity'));

  $html .= "<div class='summary-box'>";
  $html .= "<div class='summary-title'>Sales Summary</div>";
  $html .= "<div style='display: flex; justify-content: space-between;'>";
  $html .= "<div><strong>Total Orders:</strong> {$total_orders}</div>";
  $html .= "<div><strong>Items Sold:</strong> {$total_items}</div>";
  $html .= "<div><strong>Total Revenue:</strong> PHP " . number_format($total_revenue, 2) . "</div>";
  $html .= "</div>";
  $html .= "</div>";

  $html .= "<table>";
  $html .= "<tr>
                <th>Customer</th><th>Contact</th>
                <th>Date</th><th>Items</th><th>Quantity</th><th>Amount</th>
              </tr>";

  foreach ($data as $row) {
    $status_class = 'status-' . strtolower($row['status']);
    $html .= "<tr>
                    <td><strong>{$row['first_name']} {$row['last_name']}</strong></td>
                    <td><small>{$row['email']}</small></td>
                    <td>" . formatDate($row['order_date']) . "</td>
                    <td style='text-align: center;'>{$row['item_count']}</td>
                    <td style='text-align: center;'>{$row['total_quantity']}</td>
                    <td class='amount'>PHP " . number_format($row['total_amount'], 2) . "</td>
                  </tr>";
  }
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
