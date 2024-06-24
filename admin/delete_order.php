<?php
// التحقق من تسجيل الدخول
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

// تحديد معرف الطلب المراد حذفه
if(isset($_GET['order'])) {
    $order_id = $_GET['order'];
    
    // إنشاء اتصال بقاعدة البيانات
    $db = new SourceDb();
    $conn = $db->connect();

    // استعلام SQL لحذف الطلب
    $sql = "DELETE FROM Orders WHERE order_id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

    // تنفيذ الاستعلام
    if ($stmt->execute()) {
        // تحويل المستخدم بنجاح إلى صفحة أخرى
        header('Location: all_orders.php');
        exit();
    } else {
        // إذا فشل حذف الطلب، يمكنك توجيه المستخدم إلى صفحة خطأ أو البقاء في الصفحة الحالية
        echo "حدث خطأ أثناء حذف الطلب.";
    }
} else {
    // إذا لم يتم تحديد معرف الطلب، يمكنك توجيه المستخدم إلى صفحة خطأ أو البقاء في الصفحة الحالية
    echo "معرف الطلب غير محدد.";
}
?>
