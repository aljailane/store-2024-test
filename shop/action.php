<?php
// التحقق من وجود بيانات المستخدم
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';
$db = new SourceDb();
$conn = $db->connect();

// فحص معرف الطلب وحالة الطلب الجديدة
if(isset($_GET['order']) && isset($_GET['status'])) {
    $order_id = $_GET['order'];
    $new_status = $_GET['status'];

    // تغيير حالة الطلب
    changeOrderStatus($conn, $order_id, $new_status);
}

// دالة لتغيير حالة الطلب
function changeOrderStatus($conn, $order_id, $new_status) {
    // تحديث الحالة في قاعدة البيانات
    $sql = "UPDATE Orders SET status = :new_status WHERE order_id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':new_status', $new_status, PDO::PARAM_INT); // تحديد نوع البيانات
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT); // تحديد نوع البيانات
    $stmt->execute();

    // إعادة التوجيه إلى صفحة الطلبات
    header('Location: v_orders.php');
    exit();
}

?>

