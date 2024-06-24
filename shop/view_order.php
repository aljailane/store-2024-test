<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// التحقق من إرسال معرف الطلب
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: my_orders.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'];

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

// استعلام لجلب تفاصيل الطلب
$sql = "SELECT Orders.order_id, Products.product_name, Products.fee, Orders.order_date, Orders.total_amount, Orders.status, Orders.payment_status
        FROM Orders 
        INNER JOIN Products ON Orders.product_id = Products.product_id
        WHERE Orders.user_id = :user_id AND Orders.order_id = :order_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// التحقق مما إذا كان الطلب موجودًا
if (!$order) {
    echo "Order not found.";
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
        }
        .gift-card {
            border: 2px solid #f7f7f7;
            border-radius: 20px;
            padding: 30px;
            background-color: #f3e6e6;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .gift-card:hover {
            transform: translateY(-5px);
        }
        .gift-card .card-title {
            font-size: 24px;
            font-weight: bold;
            color: #b0281a;
        }
        .gift-card .card-text {
            font-size: 16px;
            color: #333;
        }
        .btn-back {
            background-color: #b0281a;
            border-color: #b0281a;
        }
        .btn-back:hover {
            background-color: #8e2016;
            border-color: #8e2016;
        }
    </style>
<?php 
$total_amount = $order['total_amount'];
$fee = $order['fee'];
$result = $total_amount + $fee;
?>



</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="gift-card">
                    <h2 class="text-center mb-4">تفاصيل الطلب</h2>
                    <div class="col">
                            <h5 class="card-title">الطلب رقم <?php echo $order['order_id']; ?></h5>
                            <p class="card-text">اسم المنتج: <?php echo $order['product_name']; ?></p>
                            <p class="card-text">تاريخ الطلب: <?php echo $order['order_date']; ?></p>
                          
<p style="color: blue;"> الخدمة: <?php echo $total_amount; ?> ريال</p>
<p style="color: green;">الرسوم: <?php echo $fee; ?> ريال</p>
<p style="color: red;">المبلغ النهائي: <?php echo $result; ?> ريال (<a href="/paypal.php?product=<?php echo $order['product_name']; ?>&price=<?php echo $result; ?>&bal=<?php echo $order['order_id']; ?>">دفع الآن</a>)</p>
                            <p class="card-text">الطلب: <?php echo getStatusName($order['status']); ?> | 
                         الدفع: <?php echo getStatusName($order['payment_status']); ?></p>
                    </div>
                    <div class="text-center mt-4">
                        <a href="v_orders.php" class="btn btn-back">العودة إلى طلباتي</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
function getStatusName($status) {
    switch ($status) {
        case 0:
            return '<span class="badge rounded-pill text-bg-primary">قيد المعالجة</span>';
        case 1:
            return '<span class="badge rounded-pill text-bg-primary">ملغي</span>';
        case 2:
            return '<span class="badge rounded-pill text-bg-primary">مكتمل</span>';
        case 3:
            return '<span class="badge rounded-pill text-bg-primary">غير مكتمل</span>';
        default:
            return '<span class="badge rounded-pill text-bg-primary">غير معروف</span>';
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

</body>
</html>

