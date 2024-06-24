<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// التحقق من أن المستخدم لديه صلاحية لإضافة أقسام (يمكنك تعديل هذا الشرط وفقًا لنظام الصلاحيات الخاص بك)
if ($_SESSION['user_id'] != 1) {
    echo "You don't have permission to access this page.";
    exit();
}

// الاتصال بقاعدة البيانات
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استقبال البيانات من النموذج
    $category_name = $_POST['category_name'];
    $net = isset($_POST['net']) ? $_POST['net'] : null;
    $is_pin = isset($_POST['is_pin']) ? 1 : 0;
    $status = $_POST['status'];

    // إنشاء الاستعلام لإضافة القسم إلى قاعدة البيانات
    $sql = "INSERT INTO Categories (category_name, net, is_pin, status) VALUES (:category_name, :net, :is_pin, :status)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':category_name', $category_name);
    $stmt->bindParam(':net', $net);
    $stmt->bindParam(':is_pin', $is_pin);
    $stmt->bindParam(':status', $status);

    // تنفيذ الاستعلام
    if ($stmt->execute()) {
        $message = "Category added successfully.";
    } else {
        $message = "Failed to add category.";
    }
}

// استعلام لجلب الأقسام من قاعدة البيانات
$sql = "SELECT * FROM Categories";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Add Category</h2>
        <?php if (isset($message)) : ?>
            <div class="alert alert-<?php echo $message == 'Category added successfully.' ? 'success' : 'danger'; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <div class="form-group">
                <label for="net">Net (optional)</label>
                <input type="text" class="form-control" id="net" name="net">
            </div>
            <div class="form-group">
                <label for="is_pin">Pin Category</label>
                <input type="checkbox" id="is_pin" name="is_pin">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
        <hr>
        <h2 class="text-center">Categories</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Net</th>
                    <th>Creation At</th>
                    <th>Is Pin</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['category_id']); ?></td>
                        <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($category['net']); ?></td>
                        <td><?php echo htmlspecialchars($category['creation_at']); ?></td>
                        <td><?php echo $category['is_pin'] ? 'Yes' : 'No'; ?></td>
                        <td><?php echo htmlspecialchars($category['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

