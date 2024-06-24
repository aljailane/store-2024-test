<?php
require_once 'SourceDb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // اتصل بقاعدة البيانات
    $database = new SourceDb();
    $db = $database->getConnection();

    if ($db) {
        try {
            // جلب بيانات المستخدم باستخدام اسم المستخدم
            $query = "SELECT * FROM users WHERE username = :username";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // التحقق من صحة كلمة المرور
                if (password_verify($password, $user['password'])) {
                    // كلمة المرور صحيحة، يمكنك تنفيذ الإجراءات اللازمة هنا مثل تخزين معلومات الجلسة
                    session_start();
                    $_SESSION['username'] = $username;
                    // تحويل الصفحة بعد 2 ثوانٍ من الدخول الناجح
                    echo '<div class="notification is-success">تم تسجيل الدخول بنجاح! جاري تحويلك...</div>';
                    echo '<script>setTimeout(function() { window.location.href = "dashboard.php"; }, 2000);</script>';
                    exit;
                } else {
                    // كلمة المرور غير صحيحة
                    echo '<div class="notification is-danger">اسم المستخدم أو كلمة المرور غير صحيحة.</div>';
                }
            } else {
                // لم يتم العثور على المستخدم
                echo '<div class="notification is-danger">اسم المستخدم أو كلمة المرور غير صحيحة.</div>';
            }
        } catch (PDOException $exception) {
            echo '<div class="notification is-danger">خطأ: ' . $exception->getMessage() . '</div>';
        }
    } else {
        echo '<div class="notification is-danger">خطأ في الاتصال بقاعدة البيانات.</div>';
    }
} else {
    // إذا لم يتم إرسال طلب POST، يتم توجيه المستخدم إلى صفحة تسجيل الدخول
    header('Location: login.php');
    exit;
}
?>

