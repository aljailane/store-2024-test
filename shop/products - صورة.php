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

// استعلام لاسترجاع جميع المنتجات
$sql = "SELECT * FROM Products";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .gift-card { 
            margin: 5px;
            border: 2px solid #f3e6e6;
            border-radius: 20px;
            padding: 20px;
            background-color: #f3e6e6;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .gift-card:hover {
            transform: translateY(-5px);
        }
        .gift-card .card-title {
            font-size: 20px;
            font-weight: bold;
            color: #b0281a;
        }
        .gift-card .card-text {
            font-size: 16px;
            color: #333;
        }
        .gift-card .btn-primary {
            background-color: #b0281a;
            border-color: #b0281a;
        }
        .gift-card .btn-primary:hover {
            background-color: #8e2016;
            border-color: #8e2016;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Products</h2>
        <div class="row">
            <?php foreach ($products as $product): ?>
            <div class="col-md-4">
                <div class="gift-card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                        <p class="card-text">Price: <?php echo $product['price']; ?></p>
                       <a href="view_product.php?product_id=<?php echo $product['product_id']; ?>">View Details</a>

                        <form action="order_service.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <button type="submit" class="btn btn-primary">Order Service</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

