<?php
// تحقق مما إذا تم إرسال معرف الخدمة بطريقة صحيحة
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // استدعاء SourceDb.php للاتصال بقاعدة البيانات
    require_once 'SourceDb.php';

    // إنشاء اتصال بقاعدة البيانات
    $database = new SourceDb();
    $db = $database->getConnection();

    if ($db) {
        try {
            // تحضير وتنفيذ الاستعلام لاسترداد تفاصيل الخدمة
            $query = "SELECT * FROM services WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->execute();
            $service = $stmt->fetch(PDO::FETCH_ASSOC);

            // إرجاع تفاصيل الخدمة كـ JSON
            echo json_encode($service);
        } catch (PDOException $exception) {
            // في حالة وجود خطأ في استعلام قاعدة البيانات
            echo json_encode(array('error' => 'خطأ في استعلام قاعدة البيانات: ' . $exception->getMessage()));
        }
    } else {
        // إذا لم يتم الاتصال بقاعدة البيانات
        echo json_encode(array('error' => 'خطأ في الاتصال بقاعدة البيانات.'));
    }
} else {
    // إذا لم يتم إرسال معرّف الخدمة بطريقة صحيحة
    echo json_encode(array('error' => 'معرّف الخدمة غير موجود.'));
}
?>

