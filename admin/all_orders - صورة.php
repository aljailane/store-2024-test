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

// الحصول على عدد الطلبات الكلي
$sql_count = "SELECT COUNT(*) AS total_orders FROM Orders WHERE user_id = :user_id";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bindParam(':user_id', $user_id);
$stmt_count->execute();
$row = $stmt_count->fetch(PDO::FETCH_ASSOC);
$total_orders = $row['total_orders'];

// عدد الطلبات التي تظهر في كل صفحة
$orders_per_page = 1;

// عدد الصفحات الكلي
$total_pages = ceil($total_orders / $orders_per_page);

// الصفحة الحالية (افتراضياً الصفحة الأولى)
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// حساب السجل الأول لكل صفحة
$offset = ($current_page - 1) * $orders_per_page;

$sql = "SELECT Orders.order_id, Products.product_name, Orders.order_date, Orders.total_amount, Orders.status, Users.username AS customer_name 
        FROM Orders 
        INNER JOIN Products ON Orders.product_id = Products.product_id
        INNER JOIN Users ON Orders.user_id = Users.user_id
        WHERE Orders.user_id = :user_id
        ORDER BY Orders.order_date DESC
        LIMIT :offset, :orders_per_page";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':orders_per_page', $orders_per_page, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الطلبات</title>
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
        <h2 class="text-center mb-4">طلباتي</h2>
        <div class="row">
            <?php foreach ($orders as $order) : ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">رقم الطلب: <?php echo $order['order_id']; ?></h5>
                            <p class="card-text">اسم المنتج: <?php echo $order['product_name']; ?></p>
                            <p class="card-text">تاريخ الطلب: <?php echo $order['order_date']; ?></p>
                            <p class="card-text">المبلغ الإجمالي: <?php echo $order['total_amount']; ?></p>
                            
<p>Status: <?php echo getStatusName($order['status']); ?></p>


<p class="card-text">صاحب الطلب: <?php echo $order['customer_name']; ?></p>
<p><a href="action.php?order=<?php echo $order['order_id']; ?>&status=0">قيد المعالجه</a> | <a href="action.php?order=<?php echo $order['order_id']; ?>&status=1">ملغي</a> | <a href="action.php?order=<?php echo $order['order_id']; ?>&status=2">مكتمل</a> | <a href="action.php?order=<?php echo $order['order_id']; ?>&status=3">غير مكتمل</a></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- تعدد الصفحات -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
                    <li class="page-item <?php echo $page == $current_page ? 'active' :
'' ?>">
                        <a class="page-link" href="all_orders.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
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

</body>
</html>

