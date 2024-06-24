<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

// التحقق من وجود معرف المنتج في الطلب
if (!isset($_GET['product_id'])) {
    echo "Product ID is missing.";
    exit();
}

$product_id = $_GET['product_id'];

// استعلام لجلب تفاصيل المنتج
$sql = "SELECT * FROM Products WHERE product_id = :product_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found.";
    exit();
}
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
        }</style>
</head>
<body>
    <div class="container mt-3">
    <h2 class="text-center">عرض الخدمه</h2>
<div class="card border-dark mb-3" style="max-width: 22rem;">
  <div class="card-header border-success"><?php echo htmlspecialchars($product['product_name']); ?></div>
  <div class="card-body text-danger">
    <h5 class="card-title">
             <p class="card-text">سعر الخدمه: <span class="badge text-bg-primary"><?php echo htmlspecialchars($product['price']); ?></span> ريال</p>
              
                <p class="card-text">حالة التوفر: <span class="badge text-bg-success"><?php echo htmlspecialchars($product['status']); ?></span></p>
                <p class="card-text">رسوم تحويل: <span class="badge text-bg-info"><?php echo htmlspecialchars($product['fee']); ?></span> ريال</p>
               
                <a href="order_service.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-primary">طلب الخدمه</a>
</h5>
<p class="card-text"><?php echo $product['description']; ?></p>
  </div>
  <div class="card-footer bg-transparent border-success">أضيفت: <?php echo htmlspecialchars($product['creation_at']); ?></div>
</div>
     
    </div>
</body>
</html>

