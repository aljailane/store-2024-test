<?php
session_start();

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 10; // عدد المنتجات التي يتم تحميلها في كل مرة

$sql = "SELECT * FROM Products WHERE 1";

if ($category) {
    $sql .= " AND category_id = :category_id";
}

switch ($sort) {
    case 'newest':
        $sql .= " ORDER BY creation_at DESC";
        break;
    case 'oldest':
        $sql .= " ORDER BY creation_at ASC";
        break;
    case 'price-asc':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price-desc':
        $sql .= " ORDER BY price DESC";
        break;
    default:
        $sql .= " ORDER BY creation_at DESC";
}

$sql .= " LIMIT :limit OFFSET :offset";

$stmt = $conn->prepare($sql);

if ($category) {
    $stmt->bindParam(':category_id', $category, PDO::PARAM_INT);
}

$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
    echo '<div class="col-md-4">
        <div class="gift-card">
            <div class="card-body">
                <h5 class="card-title">' . htmlspecialchars($product['product_name']) . '</h5>
                <p class="card-text">السعر: ' . htmlspecialchars($product['price']) . ' ريال</p>
                <p class="card-text">رسوم التحويل: ' . htmlspecialchars($product['fee']) . ' ريال (<a href="/fee_transfer.php">ماهي؟</a>)</p>
<div class="d-flex">
    <a href="view_product.php?product_id=' . $product['product_id'] . '" class="btn btn-info btn-sm m-1">تفاصيل</a>
    <form action="order_service.php" method="post">
        <input type="hidden" name="product_id" value="' . $product['product_id'] . '">
        <button type="submit" class="btn btn-primary m-1">طلب الباقه</button>
    </form>
</div>

            </div>
        </div>
    </div>';

}
?>

