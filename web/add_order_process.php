<?php
// تحقق مما إذا تم إرسال البيانات عبر طريقة POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // التحقق من صحة البيانات المرسلة
    if (isset($_POST['service']) && isset($_POST['code'])) {
        // استدعاء SourceDb.php للاتصال بقاعدة البيانات
        require_once 'SourceDb.php';

        // البيانات المرسلة من صفحة إضافة الطلب
        $service_id = $_POST['service'];
        $code = htmlspecialchars($_POST['code']);
        $user_id = 1; // يمكنك تعيين هوية المستخدم هنا
        
        // إنشاء اتصال بقاعدة البيانات
        $database = new SourceDb();
        $db = $database->getConnection();

        if ($db) {
            try {
                // تحضير وتنفيذ الاستعلام لإضافة الطلب الجديد
                $query = "INSERT INTO orders (user_id, service_id, code) VALUES (:user_id, :service_id, :code)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':service_id', $service_id);
                $stmt->bindParam(':code', $code);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success' role='alert'>تم إضافة الطلب بنجاح!</div>";
                } else {
                    echo "<div class='alert alert-danger' role='alert'>حدث خطأ أثناء إضافة الطلب.</div>";
                }
            } catch (PDOException $exception) {
                echo "<div class='alert alert-danger' role='alert'>خطأ: " . $exception->getMessage() . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>خطأ في الاتصال بقاعدة البيانات.</div>";
        }
    } else {
        echo "<div class='alert alert-warning' role='alert'>يرجى ملء جميع الحقول المطلوبة.</div>";
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>طلب غير صالح.</div>";
}
?>
