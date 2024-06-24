<?php
require_once 'SourceDb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $pincode = htmlspecialchars($_POST['pincode']);
    $ip = htmlspecialchars($_POST['ip']);
    $user_agent = htmlspecialchars($_POST['user_agent']);

    // اتصل بقاعدة البيانات
    $database = new SourceDb();
    $db = $database->getConnection();

    if ($db) {
        try {
            // تحقق مما إذا كان اسم المستخدم موجودًا بالفعل
            $query = "SELECT COUNT(*) FROM users WHERE username = :username";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                echo '<div class="notification is-warning">اسم المستخدم موجود بالفعل. يرجى اختيار اسم مستخدم آخر.</div>';
            } else {
                // إدراج المستخدم الجديد
                $query = "INSERT INTO users (name, username, password, pincode, ip, user_agent) VALUES (:name, :username, :password, :pincode, :ip, :user_agent)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':pincode', $pincode);
                $stmt->bindParam(':ip', $ip);
                $stmt->bindParam(':user_agent', $user_agent);
                if ($stmt->execute()) {
                    echo '<div class="notification is-success">تم التسجيل بنجاح!</div>';
                    // تحويل الصفحة بعد 3 ثوانٍ من التسجيل الناجح
                    echo '<script>setTimeout(function() { window.location.href = "login.php"; }, 3000);</script>';
                    exit; // تأكد من إيقاف تشغيل السكربت بعد تحويل الصفحة
                } else {
                    echo '<div class="notification is-danger">حدث خطأ أثناء التسجيل.</div>';
                }
            }
        } catch (PDOException $exception) {
            echo '<div class="notification is-danger">خطأ: ' . $exception->getMessage() . '</div>';
        }
    } else {
        echo '<div class="notification is-danger">خطأ في الاتصال بقاعدة البيانات.</div>';
    }
} else {
    echo '<div class="notification is-danger">طلب غير صالح.</div>';
}
?>

