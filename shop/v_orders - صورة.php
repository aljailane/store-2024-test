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

// إعداد استعلام لجلب طلبات المستخدم الحالي مع تفاصيل المنتج باستثناء الطلبات الملغاة
$sql = "SELECT Orders.order_id, Products.product_name, Orders.order_date, Orders.total_amount, Orders.status 
        FROM Orders 
        INNER JOIN Products ON Orders.product_id = Products.product_id
        WHERE Orders.user_id = :user_id AND Orders.status != 2"; // استثناء الطلبات التي تكون حالتها 2 (ملغية)
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .order-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        .order-title {
            font-size: 18px;
            font-weight: bold;
        }
        .order-details {
            margin-top: 10px;
            font-size: 14px;
        }
        .btn-view-order {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-view-order:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">My Orders</h2>
        <div class="row">
            <?php foreach ($orders as $order) : ?>
                <div class="col-md-4 mb-4">
                    <div class="order-card">
                        <div class="order-title">Order <?php echo $order['order_id']; ?></div>
                        <div class="order-details">
                            <p>Product Name: <?php echo $order['product_name']; ?></p>
                            <p>Status: <?php echo $order['status']; ?></p>
                        </div>
                        <div class="text-center">
                            <a href="view_order.php?id=<?php echo $order['order_id']; ?>" class="btn btn-primary btn-view-order">View Order</a>
                            <a href="action.php?order=<?php echo $order['order_id']; ?>&status=2" class="btn btn-danger btn-cancel-order">Cancel Order</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center">
            <a href="products.php" class="btn btn-primary">Back to Products</a>
        </div>
    </div>
</body>
</html>

