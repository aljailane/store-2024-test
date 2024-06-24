<?php
session_start();
require 'web/SourceDb.php'; // استدعاء ملف الاتصال بقاعدة البيانات

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// البيانات الثابتة للمستخدم (يمكن استبدالها بالبيانات الحقيقية المسترجعة من قاعدة البيانات)
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$errors = [];

// التحقق من إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_pin = $_POST['pin'];

    // الاتصال بقاعدة البيانات
    $db = new SourceDb();
    $conn = $db->connect();

    if ($conn) {
        // استعلام SQL لاسترداد الـ pincode للمستخدم
        $stmt = $conn->prepare('SELECT pincode FROM Users WHERE user_id = :user_id');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $user_pincode = $stmt->fetchColumn();

        // التحقق من تطابق الـ pincode المدخل مع الـ pincode المسترجع من قاعدة البيانات
        if ($entered_pin == $user_pincode) {
            // إذا تطابقت الـ pincode، يمكن إعادة توجيه المستخدم إلى الصفحة الرئيسية أو أي صفحة أخرى
            header('Location: users/User.php');
            exit();
        } else {
            // إذا لم يتطابق الـ pincode، يتم إضافة رسالة خطأ
            $errors[] = 'كود غير صحيح';
        }
    } else {
        // في حالة فشل الاتصال بقاعدة البيانات
        $errors[] = 'Database connection failed.';
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pin Verification</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Rubik', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">التحقق من الرمز السري</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">مرحبًا، <?php echo $username; ?></h5>
                <p class="card-text">الرجاء إدخال الرمز السري الخاص بك للمتابعة.</p>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="post">
                    <div class="form-group">
                        <label for="pin">الرمز السري:</label>
                        <input type="password" class="form-control" id="pin" name="pin" required>
                    </div>
                    <button type="submit" class="btn btn-primary m-2">تأكيد</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

