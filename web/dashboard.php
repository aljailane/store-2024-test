<?php
session_start();

// التحقق من وجود الجلسة
if (!isset($_SESSION['username'])) {
    // إذا لم تكن الجلسة موجودة، يتم توجيه المستخدم إلى صفحة تسجيل الدخول
    header('Location: login.php');
    exit;
}

// استمرار العملية على أنها صفحة dashboard.php
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <!-- Bluma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <!-- Google Fonts - Rubik -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik&display=swap">
    <style>
        /* تخصيص الخط */
        body, h1, p {
            font-family: 'Rubik', sans-serif;
            margin: 5px;
        }
        /* تخصيص توجيه النص */
        body {
            direction: rtl;
        }
        /* تخصيص هامش النموذج */
        .container {
            margin: 10px auto;
            max-width: 800px;
        }
        /* تخصيص الحاوية */
        .dashboard-container {
            background-color: #f1f1f1;
            border-radius: 10px;
            padding: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-container">
            <h4 class="title is-1">مرحباً <?php echo $_SESSION['username']; ?> في لوحة التحكم!</h4>
            <p>مرحباً بك في لوحة التحكم الخاصة بك. يمكنك الآن البدء في إدارة حسابك.</p>
            <!-- يمكنك إضافة المحتوى الخاص بلوحة التحكم هنا -->
        </div>
    </div>
</body>
</html>

