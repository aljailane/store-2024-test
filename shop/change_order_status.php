<?php
// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';
$db = new SourceDb();
$conn = $db->connect();

// فحص معرف الطلب وحالة الطلب الجديدة
if(isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    // تحديث حالة الطلب
    $sql = "UPDATE Orders SET status = :new_status WHERE order_id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':new_status', $new_status, PDO::PARAM_INT); // تحديد نوع البيانات
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT); // تحديد نوع البيانات
    $stmt->execute();

    echo "success"; // إرجاع إشارة نجاح للطلب AJAX
} else {
    echo "error";
}
?>
