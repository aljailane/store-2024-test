<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// التحقق من أن المستخدم لديه صلاحية لإضافة منتجات
if ($_SESSION['user_id'] != 1) {
    echo "You don't have permission to access this page.";
    exit();
}

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

// استعلام لجلب الأقسام من قاعدة البيانات
$sql = "SELECT category_id, category_name FROM Categories";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استقبال البيانات من النموذج
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $fee = $_POST['fee'];

    // إنشاء الاستعلام لإضافة المنتج إلى قاعدة البيانات
    $sql = "INSERT INTO Products (category_id, product_name, price, description, status, fee) VALUES (:category_id, :product_name, :price, :description, :status, :fee)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':category_id', $category);
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':fee', $fee);

    // تنفيذ الاستعلام
    if ($stmt->execute()) {
        $message = "Product added successfully.";
    } else {
        $message = "Failed to add product.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اضافة منتج</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/cni8l2kma6pf6fkxkfh7ddhkwkcyalvm3lgyeusz1o11alcz/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        body {
            font-family: 'Rubik', sans-serif;
        }
    </style>
    <script>
        tinymce.init({
            selector: '#description',
            language: 'ar',
            height: 300,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | removeformat | help',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">اضافة منتج</h2>
        <?php if (isset($message)) : ?>
            <div class="alert alert-<?php echo $message == 'Product added successfully.' ? 'success' : 'danger'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group m-1">
                <label for="product_name">اسم الخدمه</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="form-group m-1">
                <label for="price">سعر</label>
                <input type="text" class="form-control" id="price" name="price" required>
            </div>
            <div class="form-group m-1">
                <label for="description">وصف</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group m-1">
                <label for="category">قسم</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="">حدد قسم</option>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group m-1">
                <label for="status">حالة</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="متوفر">Available</option>
                    <option value="غير متوفر">Unavailable</option>
                </select>
            </div>
            <input type="hidden" class="form-control" id="fee" name="fee" value="1" required>
            <button type="submit" class="btn btn-primary m-2">حفظ</button>
        </form>
    </div>
    <!-- Bootstrap 5.3 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

