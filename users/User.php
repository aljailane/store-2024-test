<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';
// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// البيانات الثابتة للمستخدم (يمكن استبدالها بالبيانات الحقيقية المسترجعة من قاعدة البيانات)
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// الحصول على بيانات العضو المحدد من قاعدة البيانات
$db = new SourceDb();
$conn = $db->connect();
$stmt = $conn->prepare('SELECT username, full_name, pincode, ip, status, date FROM Users WHERE user_id = :user_id');
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();


?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ادارة العضو</title>
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
        <h2 class="text-center">لوحة العضو</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">مرحبـا, <?php echo $username; ?></h5>
                            <p>IP: <small><?php echo $user['ip']; ?></small></p>
                            <p>التسجيل: <?php echo $user['date']; ?></p>
                       
                <a href="/users/edit_profile.php" class="btn btn-primary btn-sm m-1">تعديل الملف</a>
                <a href="/admin/Users.php" class="btn btn-success btn-sm m-1">الآعضاء</a>
<a href="/users/cpas_u.php" class="btn btn-secondary btn-sm m-1">كلمة السر</a>
                <a href="/logout.php" class="btn btn-danger btn-sm m-1">تسجيل خروج</a>
            </div>
        </div>

<a href="/shop/products.php" class="btn btn-danger btn-sm m-1">منتجات</a>
        <div class="mt-4">
            <h4>Previous Requests:</h4>
            <ul class="list-group">
  <li class="list-group-item d-flex justify-content-between align-items-center">
    الاعضاء
    <span class="badge text-bg-primary rounded-pill"><?php
$conn = (new SourceDb())->connect();
echo $conn ? 'عضو: ' . $conn->query('SELECT COUNT(*) FROM Users')->fetchColumn() : 'فشل الاتصال بقاعدة البيانات.';
?></span>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    طلبات
    <span class="badge text-bg-primary rounded-pill"><?php
$conn = (new SourceDb())->connect();
echo $conn ? 'طلب: ' . $conn->query('SELECT COUNT(*) FROM Orders')->fetchColumn() : 'فشل الاتصال بقاعدة البيانات.';
?></span>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    منتجات
    <span class="badge text-bg-primary rounded-pill"><?php
$conn = (new SourceDb())->connect();
echo $conn ? 'منتج: ' . $conn->query('SELECT COUNT(*) FROM Products')->fetchColumn() : 'فشل الاتصال بقاعدة البيانات.';
?></span>
  </li>
</ul>
        </div>
    </div>
</body>
</html>

