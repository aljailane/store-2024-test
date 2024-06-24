<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// التحقق من إرسال معرف الطلب وحالته
if (!isset($_GET['order']) || !isset($_GET['status'])) {
    header('Location: products.php');
    exit();
}

$order_id = $_GET['order'];
$status = $_GET['status'];

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

// التحقق مما إذا كان المستخدم هو صاحب الطلب أو المشرف (رقم 1)
$user_id = $_SESSION['user_id'];
$is_admin = $user_id == 1;

if (!$is_admin) {
    // إذا لم يكن المستخدم صاحب الطلب أو مشرف، لا تسمح بتغيير حالة الطلب
    $_SESSION['message'] = "You don't have permission to change order status.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// إعداد الاستعلام لتحديث حالة الطلب
$sql = "UPDATE Orders SET status = :status WHERE order_id = :order_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':status', $status);
$stmt->bindParam(':order_id', $order_id);

// تنفيذ الاستعلام
if ($stmt->execute()) {
    $message = "Order status updated successfully.";
} else {
    $message = "Failed to update order status.";
}

// إعادة توجيه المستخدم إلى الصفحة السابقة مع رسالة الإعلام
$_SESSION['message'] = $message;
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
