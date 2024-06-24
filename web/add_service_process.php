<?php
require_once 'SourceDb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // احصل على البيانات المرسلة من النموذج
    $name = htmlspecialchars($_POST['name']);
    $price = $_POST['price'];
    $description = htmlspecialchars($_POST['description']);
    $fee = isset($_POST['fee']) ? $_POST['fee'] : null;
    $fee_paypal = isset($_POST['fee_paypal']) ? $_POST['fee_paypal'] : null;
    $status = $_POST['status'];

    // اتصل بقاعدة البيانات
    $database = new SourceDb();
    $db = $database->getConnection();

    if ($db) {
        try {
            // استعداد الاستعلام
            $query = "INSERT INTO services (name, price, description, fee, fee_paypal, status) VALUES (:name, :price, :description, :fee, :fee_paypal, :status)";
            $stmt = $db->prepare($query);

            // قم بتعيين قيم الباراميترات
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':fee', $fee);
            $stmt->bindParam(':fee_paypal', $fee_paypal);
            $stmt->bindParam(':status', $status);

            // قم بتنفيذ الاستعلام
            if ($stmt->execute()) {
                // إذا تمت العملية بنجاح، عرض رسالة نجاح
                echo '<div class="notification is-success">تم إضافة الخدمة بنجاح!</div>';
            } else {
                // إذا فشلت العملية، عرض رسالة خطأ
                echo '<div class="notification is-danger">حدث خطأ أثناء إضافة الخدمة. يرجى المحاولة مرة أخرى.</div>';
            }
        } catch (PDOException $exception) {
            // في حالة حدوث خطأ أثناء تنفيذ الاستعلام، عرض رسالة الخطأ
            echo '<div class="notification is-danger">خطأ: ' . $exception->getMessage() . '</div>';
        }
    } else {
        // إذا فشل الاتصال بقاعدة البيانات، عرض رسالة خطأ
        echo '<div class="notification is-danger">خطأ في الاتصال بقاعدة البيانات.</div>';
    }
} else {
    // إذا لم يتم إرسال البيانات عبر الطريقة POST، عرض رسالة خطأ
    echo '<div class="notification is-danger">لم يتم إرسال بيانات الخدمة. يرجى المحاولة مرة أخرى.</div>';
}
?>
