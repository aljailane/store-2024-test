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
$orders_per_page = 5;

// عدد الصفحات الكلي
$total_pages = ceil($total_orders / $orders_per_page);

// الصفحة الحالية (افتراضياً الصفحة الأولى)
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// حساب السجل الأول لكل صفحة
$offset = ($current_page - 1) * $orders_per_page;

// التحقق من قيمة الفرز المحددة
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// تحديد الترتيب الافتراضي
$order_by = 'Orders.order_date DESC';

switch ($sort) {
    case 'newest':
        $order_by = 'Orders.order_date DESC';
        break;
    case 'oldest':
        $order_by = 'Orders.order_date ASC';
        break;
    case 'most_ordered':
        $order_by = 'COUNT(Orders.order_id) DESC';
        break;
    case 'status':
        $order_by = 'Orders.status ASC';
        break;
}

// استعلام SQL لاسترداد الطلبات بناءً على الفرز المحدد
$sql = "SELECT Orders.order_id, Products.product_name, Orders.order_date, Orders.total_amount, Orders.status, Orders.payment_status, Users.username AS customer_name 
        FROM Orders 
        INNER JOIN Products ON Orders.product_id = Products.product_id
        INNER JOIN Users ON Orders.user_id = Users.user_id
        WHERE Orders.user_id = :user_id
        ORDER BY $order_by
        LIMIT :offset, :orders_per_page";

// تنفيذ الاستعلام
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
        <h2 class="text-center mb-4">ادارة الطلبات</h2>

        <div class="row">
 <div class="col-md-12 mb-4">
    <label for="sort_orders" class="form-label">ترتيب الطلبات:</label>
    <select class="form-select" id="sort_orders" onchange="location = this.value;">
        <option value="all_orders.php?sort=newest">الأحدث أولاً</option>
        <option value="all_orders.php?sort=oldest">الأقدم أولاً</option>
        <option value="all_orders.php?sort=most_ordered">الأكثر طلبًا</option>
        <option value="all_orders.php?sort=status">حسب الحالة</option>
    </select>
</div>

        </div>
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

<p>حالة الدفع <?php echo getStatusName($order['payment_status']); ?></p>

                            <p class="card-text">صاحب الطلب: <?php echo $order['customer_name']; ?></p>
                          <p>
    <a href="action.php?order=<?php echo $order['order_id']; ?>&status=0" class="btn btn-primary btn-sm">قيد المعالجة</a>
    <a href="action.php?order=<?php echo $order['order_id']; ?>&status=1" class="btn btn-danger btn-sm">ملغى</a>
    <a href="action.php?order=<?php echo $order['order_id']; ?>&status=2" class="btn btn-success btn-sm">مكتمل</a>
    <a href="action.php?order=<?php echo $order['order_id']; ?>&status=3" class="btn btn-warning btn-sm">غير مكتمل</a>
    <a href="delete_order.php?order=<?php echo $order['order_id']; ?>" class="btn btn-secondary btn-sm">حذف الطلب</a>
<hr>
<a href="apay.php?order=<?php echo $order['order_id']; ?>&status=0">قيد المعالجة</a> /
<a href="apay.php?order=<?php echo $order['order_id']; ?>&status=1">ملغي</a> /
<a href="apay.php?order=<?php echo $order['order_id']; ?>&status=2">مكتمل</a> /
<a href="apay.php?order=<?php echo $order['order_id']; ?>&status=3">فشل</a> 

</p>


                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- تعدد الصفحات -->
       
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
                    <li class="page-item <?php echo $page == $current_page ? 'active' : ''; ?>">
                        <a class="page-link" href="all_orders.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
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

function getPaymentStatusName($payment_status) {
    switch ($payment_status) {
        case '0':
            return '<span class="badge rounded-pill text-bg-primary">بإنتظار الدفع</span>';
        case '1':
            return '<span class="badge rounded-pill text-bg-primary">ملغي</span>';
        case '2':
            return '<span class="badge rounded-pill text-bg-primary">مكتمل</span>';
        default:
            return '<span class="badge rounded-pill text-bg-primary">غير معروف</span>';
    }
}
?>
<script>
    document.getElementById('search_button').addEventListener('click', function() {
        var searchTerm = document.getElementById('search_order').value;
        window.location.href = 'search_orders.php?search=' + searchTerm;
    });
</script>

