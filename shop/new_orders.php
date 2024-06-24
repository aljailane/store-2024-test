<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

// إعداد استعلام لجلب الطلبات الجديدة
$sql = "SELECT Orders.order_id, Products.product_name, Orders.order_date, Orders.total_amount, Orders.status, Orders.payment_status 
        FROM Orders 
        INNER JOIN Products ON Orders.product_id = Products.product_id
        WHERE Orders.user_id = :user_id AND Orders.status = 0"; // حالة الطلب الجديد
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$new_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الطلبات الجديدة</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Rubik', sans-serif;
        }
        .order-card {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 1px;
            background-color: #f8f9fa;
        }
        .order-title {
            font-size: 18px;
            font-weight: bold;
        }
        .order-details {
            margin-top: 1px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">الطلبات الجديدة</h2>
        <div class="row">
            <?php foreach ($new_orders as $order) : ?>
                <div class="col-md-4 mb-4">
                    <div class="order-card">
                        <div class="order-title">طلب رقم <?php echo $order['order_id']; ?></div>
                        <div class="order-details">
                            <p><strong>اسم المنتج:</strong> <?php echo $order['product_name']; ?></p>
                            <p><strong>تاريخ الطلب:</strong> <?php echo $order['order_date']; ?></p>
                            <p><strong>المبلغ الإجمالي:</strong> <?php echo $order['total_amount']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
