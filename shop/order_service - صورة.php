<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// التحقق من إرسال معرف المنتج
if (!isset($_POST['product_id'])) {
    header('Location: products.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$order_date = date('Y-m-d H:i:s'); // تاريخ ووقت إنشاء الطلب
$total_amount = 0; // يمكن إضافة المنطق هنا لحساب المبلغ الإجمالي إذا لزم الأمر
$status = 'Pending'; // الحالة الافتراضية للطلب

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

// استعلام لجلب سعر الخدمة من جدول المنتجات
$sql_price = "SELECT price FROM Products WHERE product_id = :product_id";
$stmt_price = $conn->prepare($sql_price);
$stmt_price->bindParam(':product_id', $product_id);
$stmt_price->execute();
$product = $stmt_price->fetch(PDO::FETCH_ASSOC);

// جلب سعر الخدمة
$price = $product['price'];

// تحديث المتغير $total_amount بسعر الخدمة
$total_amount = $price;

// إنشاء الاستعلام لإضافة الطلب إلى قاعدة البيانات
$sql = "INSERT INTO Orders (user_id, product_id, order_date, total_amount, status) VALUES (:user_id, :product_id, :order_date, :total_amount, :status)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':product_id', $product_id);
$stmt->bindParam(':order_date', $order_date);
$stmt->bindParam(':total_amount', $total_amount);
$stmt->bindParam(':status', $status);

// تنفيذ الاستعلام
if ($stmt->execute()) {
    $message = "Order placed successfully.";
} else {
    $message = "Failed to place order.";
}
?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Service</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
</head>
<body>
    <div class="container mt-2">
        <h2 class="text-center">تأكيد الطلب</h2>
        <div class="alert alert-<?php echo isset($message) ? ($message == 'Order placed successfully.' ? 'success' : 'danger') : 'info'; ?>">
            <?php echo isset($message) ? $message : 'Processing your order...'; ?>
        </div>
        <div class="text-center">
            <a href="v_orders.php" class="btn btn-primary">طلباتي</a>
        </div>
    </div>
</body>
</html>

