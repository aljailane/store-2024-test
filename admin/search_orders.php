<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

// التحقق من وجود مصطلح البحث
if (isset($_GET['search']) && !empty($_GET['search'])) {
    // البحث المطلوب
    $search_term = $_GET['search'];
    
    // استعلام SQL لاسترداد الطلبات المطابقة لمصطلح البحث
    $sql = "SELECT * FROM Orders WHERE user_id = :user_id AND (order_id LIKE :search OR product_name LIKE :search)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindValue(':search', '%' . $search_term . '%');
    $stmt->execute();
    $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // في حالة عدم وجود مصطلح البحث، يتم توجيه المستخدم إلى الصفحة الرئيسية
    header('Location: all_orders.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتائج البحث عن الطلبات</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <style>
        body {
            font-family: 'Rubik', sans-serif;
        }
        /* أي أسلوب CSS إضافي يمكنك إضافته هنا */
    </style>
</head>
<body>
    <div class="container mt-2">
        <h2 class="text-center mb-4">نتائج البحث عن الطلبات</h2>
        <?php if (count($search_results) > 0) : ?>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>اسم المنتج</th>
                                <th>تاريخ الطلب</th>
                                <th>المبلغ الإجمالي</th>
                                <th>حالة الطلب</th>
                                <th>صاحب الطلب</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($search_results as $order) : ?>
                                <tr>
                                    <td><?php echo $order['order_id']; ?></td>
                                    <td><?php echo $order['product_name']; ?></td>
                                    <td><?php echo $order['order_date']; ?></td>
                                    <td><?php echo $order['total_amount']; ?></td>
                                    <td><?php echo getStatusName($order['status']); ?></td>
                                    <td><?php echo $order['customer_name']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-info" role="alert">
                لا توجد نتائج للبحث.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
function getStatusName($status) {
    switch ($status) {
        case 0:
            return '<span class="badge bg-primary">قيد المعالجة</span>';
        case 1:
            return '<span class="badge bg-danger">ملغي</span>';
        case 2:
            return '<span class="badge bg-success">مكتمل</span>';
        case 3:
            return '<span class="badge bg-warning text-dark">غير مكتمل</span>';
        default:
            return '<span class="badge bg-secondary">غير معروف</span>';
    }
}
?>
