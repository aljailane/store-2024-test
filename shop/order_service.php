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
$order_date = date('Y-m-d h:i:s'); // تاريخ ووقت إنشاء الطلب
$total_amount = 0; // يمكن إضافة المنطق هنا لحساب المبلغ الإجمالي إذا لزم الأمر
$status = '0'; // الحالة الافتراضية للطلب

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
// قيمة حالة الدفع الافتراضية (0)
$payment_status = '0';


// استعلام لاسترجاع الطلبات السابقة للمستخدم خلال الفترة الزمنية المحددة
$sql_previous_orders = "SELECT * FROM Orders WHERE user_id = :user_id AND product_id = :product_id AND order_date >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
$stmt_previous_orders = $conn->prepare($sql_previous_orders);
$stmt_previous_orders->bindParam(':user_id', $user_id);
$stmt_previous_orders->bindParam(':product_id', $product_id);
$stmt_previous_orders->execute();
$previous_orders = $stmt_previous_orders->fetchAll(PDO::FETCH_ASSOC);

// التحقق مما إذا كان هناك طلب سابق لنفس الخدمة خلال الفترة الزمنية المحددة
if (count($previous_orders) > 0) {
    // يمنع تقديم الطلب وعرض رسالة توضح ذلك للمستخدم
    $message = "لا يمكنك تقديم نفس الطلب مرة أخرى خلال 30 دقيقة.";
} else {
// الحالة الافتراضية للطلب (0)
$status = '0';

// إنشاء الاستعلام لإضافة الطلب إلى قاعدة البيانات مع تضمين حالة الدفع
$sql = "INSERT INTO Orders (user_id, product_id, order_date, total_amount, status, payment_status) VALUES (:user_id, :product_id, :order_date, :total_amount, :status, :payment_status)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':product_id', $product_id);
$stmt->bindParam(':order_date', $order_date);
$stmt->bindParam(':total_amount', $total_amount);
$stmt->bindParam(':status', $status);
$stmt->bindParam(':payment_status', $payment_status); // يتم إضافة هذا المتغير لإدخال قيمة حالة الدفع


    // تنفيذ الاستعلام
    if ($stmt->execute()) {
        $message = "تم انشاء طلبك فوريآ";
    } else {
        $message = "فشل انشاء الطلب تواصل بنا";
    }
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ادارة العضو</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Rubik', sans-serif;
        }
        .container {
            margin-top: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">تأكيد الطلب</h2>
        <div class="alert alert-<?php echo isset($message) ? ($message == 'تم وضع الطلب بنجاح.' ? 'success' : 'danger') : 'info'; ?>">
            <?php echo isset($message) ? $message : 'جاري معالجة طلبك...'; ?>
        </div>
        <div class="text-center">
            <a href="v_orders.php" class="btn btn-primary">طلباتي</a>
        </div>
    </div>
</body>
</html>

