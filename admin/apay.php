<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// التحقق من إرسال معرف الطلب وحالة الدفع
if (!isset($_GET['order']) || !isset($_GET['status'])) {
    header('Location: all_orders.php');
    exit();
}

$order_id = $_GET['order'];
$payment_status = $_GET['status'];

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

// تحديث حالة الدفع في قاعدة البيانات
$sql = "UPDATE Orders SET payment_status = :payment_status WHERE order_id = :order_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':payment_status', $payment_status);
$stmt->bindParam(':order_id', $order_id);

if ($stmt->execute()) {
    $message = "Payment status updated successfully.";
} else {
    $message = "Failed to update payment status.";
}

// إعادة التوجيه إلى الصفحة التي تم منها إرسال طلب الدفع مع رسالة إعلام
$_SESSION['message'] = $message;
header('Location: all_orders.php');
exit();
?>

